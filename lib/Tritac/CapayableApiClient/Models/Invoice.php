<?php
class Tritac_CapayableApiClient_Models_Invoice extends Tritac_CapayableApiClient_Models_BaseModel
{
	protected $transactionNumber;
	protected $invoiceNumber;
	protected $invoiceDate;
	protected $invoiceAmount;
	protected $invoiceDescription;
	
	protected $invoicePdfSubmitType;
	
	// For InvoicePdfSubmitType::URL
	protected $invoicePdfUrl;
	
	// For InvoicePdfSubmitType::BCC_EMAIL
	protected $invoicePdfEmailSentDate;
	protected $invoicePdfFromEmail; //sender
	protected $invoicePdfEmailSubject;

    public function __construct()
    {
        $this->transactionNumber = '';
        $this->invoiceNumber = '';
        $this->invoiceDate = new DateTime();
        $this->invoiceAmount = 0;
        $this->invoiceDescription = '';

	// PDF invoice submission
	$this->invoicePdfSubmitType = Tritac_CapayableApiClient_Enums_InvoicePdfSubmitType::BCC_EMAIL;
	$this->invoicePdfUrl = '';
	$this->invoicePdfEmailSentDate = new DateTime();
	$this->invoicePdfFromEmail = ''; 
	$this->invoicePdfEmailSubject = '';
    }

    function getTransactionNumber() { return $this->transactionNumber; }
    function setTransactionNumber($transactionNumber) {

        if(strlen($transactionNumber) > 32)  {
            throw new Exception('Transaction number may not exceed 32 characters');
        }

        $this->transactionNumber = $transactionNumber;
    }

    function getInvoiceNumber() { return $this->invoiceNumber; }
    function setInvoiceNumber($invoiceNumber) {

        if(strlen($invoiceNumber) > 150)  {
            throw new Exception('Invoice number may not exceed 150 characters');
        }

        $this->invoiceNumber = $invoiceNumber;
    }

    function getInvoiceDate() { return $this->invoiceDate->format('Y-m-d'); }
    function getInvoiceDateAsDateTime() { return $this->invoiceDate; }
    function setInvoiceDate($invoiceDate) {

        $this->invoiceDate = new DateTime($invoiceDate);
    }
    function setInvoiceDateAsDateTime(DateTime $invoiceDate) {
        $this->invoiceDate = $invoiceDate;
    }

    function getInvoiceAmount() { return $this->invoiceAmount; }
    function setInvoiceAmount($invoiceAmount) {

        if(!is_numeric($invoiceAmount)) {
            throw new Exception('Invoice amount must be numeric');
        }

        $this->invoiceAmount = $invoiceAmount;
    }

    function getInvoiceDescription() { return $this->invoiceDescription; }
    function setInvoiceDescription($invoiceDescription) {

        if(strlen($invoiceDescription) > 225)  {
            throw new Exception('Invoice description may not exceed 225 characters');
        }

        $this->invoiceDescription = $invoiceDescription;
    }

	// PDF Invoice submission:
	function getInvoicePdfSubmitType() { return $this->invoicePdfSubmitType; }

	// By email:
	function getInvoicePdfEmailSentDate() { return $this->invoicePdfEmailSentDate->format('Y-m-d'); }
	function getInvoicePdfEmailSentDateAsDateTime() { return $this->invoicePdfEmailSentDate; }
	function getInvoicePdfFromEmail() { return $this->invoicePdfFromEmail; }
	function getInvoicePdfEmailSubject() { return $this->invoicePdfEmailSubject; }
	function setInvoiceByEmail($sender, $subject, DateTime $date = null){
		if(is_null($date)){
			$date = new DateTime();
		}

		if(strlen($sender) > 127 || strlen($subject) > 127 )  {
			throw new Exception('Email sender and/or subject may not exceed 127 characters');
		}

		$this->invoicePdfSubmitType = Tritac_CapayableApiClient_Enums_InvoicePdfSubmitType::BCC_EMAIL;
		
		$this->invoicePdfFromEmail = $sender;
		$this->invoicePdfEmailSubject = $subject;
		$this->invoicePdfEmailSentDate = $date;
	}

	// By URL:
	function getInvoicePdfUrl() { return $this->invoicePdfUrl; }
	function setInvoiceByUrl($url){

		if(strlen($url) > 511 )  {
			throw new Exception('Url may not exceed 511 characters');
		}

		$this->invoicePdfSubmitType = Tritac_CapayableApiClient_Enums_InvoicePdfSubmitType::URL;

		$this->invoicePdfUrl = $url;
	}
}