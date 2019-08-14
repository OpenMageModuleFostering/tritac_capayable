<?php
class Tritac_CapayableApiClient_Client {

    private $environment;
    private $tenant;
    private $certificate;

    private $apiUrl;
    private $apiKey;
    private $apiSecret;

    const FIDDLER_PROXY 	= '127.0.0.1:8888';
    const USE_FIDDLER 		= false;
    const FIDDLER_CERTIFICATE_PATH = 'fiddler.crt';

    const ACC_URL 			= 'http://capayable-api-acc.tritac.com';
    const TEST_URL 			= 'https://capayable-api-test.tritac.com';
    const PROD_URL 			= 'https://capayable-api.tritac.com';

    const VERSION_PATH 		= '/v1';
    const CERTIFICATE_PATH	= 'AddTrustExternalCARoot.crt';

    const CREDITCHECK_PATH	= '/creditcheck';
    const INVOICE_PATH		= '/invoice';

    public function __construct($apiKey, $apiSecret, $env = null)
    {
        if($env == null || $env == Tritac_CapayableApiClient_Enums_Environment::PROD) {
            $this->apiUrl = self::PROD_URL;
        } elseif($env == Tritac_CapayableApiClient_Enums_Environment::TEST) {
            $this->apiUrl = self::TEST_URL;
        } elseif($env == Tritac_CapayableApiClient_Enums_Environment::TEST) {
            $this->apiUrl = self::ACC_URL;
        }

        $this->environment = $env;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->certificate = __DIR__ . DIRECTORY_SEPARATOR . (self::USE_FIDDLER ? self::FIDDLER_CERTIFICATE_PATH : self::CERTIFICATE_PATH);
    }

    public function doCreditCheck(Tritac_CapayableApiClient_Models_CreditCheckRequest $request)
    {
        $args = $request->toArray();
        if(!$request->getIsCorporation()){
            unset($args['CocNumber']);
            unset($args['CorporationName']);
        }

        $path = self::VERSION_PATH . self::CREDITCHECK_PATH;

        $response = json_decode($this->makeRequest(Tritac_CapayableApiClient_Enums_HttpMethod::GET, $path, $this->buildQueryString($args, $path)));

        return new Tritac_CapayableApiClient_Models_CreditCheckResponse($response->IsAccepted, $response->TransactionNumber);
    }

    public function registerInvoice(Tritac_CapayableApiClient_Models_Invoice $invoice)
    {
        $args = $invoice->toArray();
        $path = self::VERSION_PATH . self::INVOICE_PATH;

        $response = json_decode($this->makeRequest(Tritac_CapayableApiClient_Enums_HttpMethod::GET, $path, $this->buildQueryString($args, $path)));

        return $response->IsAccepted;
    }

    /* Private methods */

    private function makeRequest($method, $url, $queryString = '', $content = null)
    {
        $request = curl_init();

        // Create the required Http headers
        $headers = $this->buildHeaders($method, $content);

        if(self::USE_FIDDLER)
        {
            // We use this to redirect the request through a local proxy and trace it with fiddler
            curl_setopt($request, CURLOPT_PROXY, self::FIDDLER_PROXY);
        }

        // Set the Url
        curl_setopt($request, CURLOPT_URL, $this->apiUrl . $url . $queryString);

        // Add the headers and hmac auth.
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);

        // Return the response as a string
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        // Set custom request method because curl has no setting for PUT and DELETE
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);

        // Make the headers accessible for debugging purposes
        curl_setopt($request, CURLINFO_HEADER_OUT, true);

        // Point curl to the correct certificate.
        // See: http://stackoverflow.com/questions/6400300/php-curl-https-causing-exception-ssl-certificate-problem-verify-that-the-ca-cer
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($request, CURLOPT_CAINFO, $this->certificate);

        // If we have a request body send it too
        if(strlen($content) > 0)
            curl_setopt($request, CURLOPT_POSTFIELDS, $content);

        // Make the request
        $response = curl_exec($request);

        // Get the status code
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);



        // Check for errors
        // First we check if the response is missing which will probably be caused by a cURL error
        // After this the check if there are not HTTP errors (status codes other than 200-206)
        if ($response === false)
        {
            $error = curl_error($request);
            curl_close($request);
            throw new Exception('cURL error: ' . $error);
        }
        else if($status < 200 || $status > 206)
        {
            $headers = curl_getinfo($request, CURLINFO_HEADER_OUT);
            $message = json_decode($response);

            curl_close($request);

            throw new Exception('Output headers: '. "\n" . $headers ."\n\n".
                                'Content: ' . $content ."\n\n".
                                'Unexpected status code [' . $status . ']. The server returned the following message: "' . $message->Message . '"');
        }
        else
        {
            curl_close($request);

            return $response;
        }
    }

    private function buildHeaders($method, $content = null)
    {
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($content),
        );

        return $headers;
    }

    private function buildQueryString(array $args, $path)
    {

        $hash = $this->getHash($args, $path);
        $args['signature'] = $hash;
        $args['key'] = $this->apiKey;
        $args['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        return $this->encodeQueryString($args);
    }

    private function getHash(array $args, $path){
        // Copy the array
        $sortedArgs = $args;

        // Sort it
        ksort($sortedArgs);

        $sortedArgs['key'] = $this->apiKey;
        $sortedArgs['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');

        $representation = $path . urldecode($this->encodeQueryString($sortedArgs));
        $hash = hash_hmac('sha256', utf8_encode($representation), utf8_encode($this->apiSecret), true);

        return base64_encode($hash);
    }

    private function encodeQueryString(array $args) {
        $queryString = (count($args) > 0) ? '?' . http_build_query($args) : '';

        // .Net does not seem to like the /?foo[0]=bar&foo[1]=baz notation so we
        // convert it to /?foo=bar&foo=baz
        return preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $queryString);
    }
}