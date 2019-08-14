<?php
/**
 * Capayable payment method model
 *
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Tritac_Capayable_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    /**
     * Unique internal payment method identifier
     */
    protected $_code = 'capayable';
    protected $_paymentMethod    = 'Capayable';
    protected $_formBlockType = 'capayable/form';
    protected $_infoBlockType = 'capayable/info';

    /**
     * Availability options
     */
    protected $_isGateway                   = true;
    protected $_canOrder                    = true;
    protected $_canAuthorize                = true;
    protected $_canCapture                  = true;
    protected $_canCapturePartial           = false;
    protected $_canRefundInvoicePartial     = false;
    protected $_canVoid                     = false;

    /**
     * Capayable API client
     */
    protected $_client                      = null;

    /**
     * Extension helper
     */
    protected $_helper                      = null;

    public function __construct()
    {
        $this->_helper = Mage::helper('capayable');

        $public_key = $this->getConfigData('public_key');
        $secret_key = $this->getConfigData('secret_key');

        /**
         * Initialize new api client
         * @var Tritac_CapayableApiClient_Client _client
         */
        $this->_client = new Tritac_CapayableApiClient_Client($public_key, $secret_key, $this->_helper->getMode());

        parent::__construct();
    }

    /**
     * Get capayable helper
     *
     * @return Mage_Core_Helper_Abstract|Mage_Payment_Helper_Data|null
     */
    public function getHelper() {
        return $this->_helper;
    }

    /**
     * Check customer credit via capayable
     *
     * @param Varien_Object $payment
     * @param float $amount
     * @return $this|Mage_Payment_Model_Abstract
     * @throws Mage_Payment_Model_Info_Exception
     */
    public function authorize(Varien_Object $payment, $amount) {

        if ($amount <= 0) {
            Mage::throwException(Mage::helper('capayable')->__('Invalid amount for authorize.'));
        }

        // Convert amount to cents
        $amount = $this->getHelper()->convertToCents($amount);
        $_order = $payment->getOrder();
        // Load saved capayable customer if exists. Otherwise load empty model.
        $capayableCustomer = Mage::getModel('capayable/customer')->loadByEmail($_order->getCustomerEmail());

        // Throw exception if capayable can't provide customer credit
        if(!$this->checkCredit($capayableCustomer, $amount)) {
            throw new Mage_Payment_Model_Info_Exception(Mage::helper('capayable')->__("Can't authorize capayable payment."));
        }

        return $this;
    }

    /**
     * Assign data to info model instance
     * Save capayable customer
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }

        $quote = $this->getInfoInstance()->getQuote();
        $address = $quote->getBillingAddress();

        if(!$quote->getCustomerMiddlename()) {
            $quote->setCustomerMiddlename($data->getCustomerMiddlename());
        }
        if(!$quote->getCustomerGender()) {
            $quote->setCustomerGender($data->getCustomerGender());
        }

        // Convert date format
        $dob = $quote->getCustomerDob() ? $quote->getCustomerDob() : $data->getCustomerDob();
        $dob = Mage::app()->getLocale()->date($dob, null, null, false)->toString('yyyy-MM-dd 00:00:00');
        $data->setCustomerDob($dob);
        $quote->setCustomerDob($dob);

        $capayableCustomer = Mage::getModel('capayable/customer')->loadByEmail($quote->getCustomerEmail());

        /**
         * If capayable customer doesn't exist fill new customer data from quote data.
         * Otherwise rewrite saved customer fields from form data.
         */
        if(!$capayableCustomer->getId()) {
            $capayableCustomer
                ->setCustomerEmail($quote->getCustomerEmail())
                ->setCustomerLastname($quote->getCustomerLastname())
                ->setCustomerMiddlename($quote->getCustomerMiddlename())
                ->setCustomerGender($quote->getCustomerGender())
                ->setCustomerDob($quote->getCustomerDob())
                ->setStreet($data->getStreet())
                ->setHouseNumber((int) $data->getHouseNumber())
                ->setHouseSuffix($data->getHouseSuffix())
                ->setPostcode($address->getPostcode())
                ->setCity($address->getCity())
                ->setCountryId($address->getCountryId())
                ->setTelephone($address->getTelephone())
                ->setFax($address->getFax())
                ->setIsCorporation($data->getIsCorporation())
                ->setCorporationName($data->getCorporationName())
                ->setCocNumber($data->getCocNumber());
        } else {
            $capayableCustomer->addData($data->getData());
        }

        // Validate capayable customer required fields
        $result = $capayableCustomer->validate();

        if (true !== $result && is_array($result)) {
            throw new Mage_Payment_Model_Info_Exception(implode(', ', $result));
        }

        // Save capayable customer to 'capayable/customer' table
        $capayableCustomer->save();

        $this->getInfoInstance()->addData($data->getData());

        return $this;
    }

    /**
     * Customer credit check with Capayable.
     * Can take quote or order object.
     *
     * @param Tritac_Capayable_Model_Customer $_customer
     * @param float $amount
     * @param bool $isFinal
     * @return bool|Tritac_CapayableApiClient_Models_CreditCheckResponse
     */
    public function checkCredit(Tritac_Capayable_Model_Customer $_customer, $amount, $isFinal = false)
    {
        /**
         * Initialize new request model and fill all requested fields
         */
        $req = new Tritac_CapayableApiClient_Models_CreditCheckRequest();
        $req->setLastName($_customer->getCustomerLastname());
        $req->setInitials($_customer->getCustomerMiddlename());

        $gender = Tritac_CapayableApiClient_Enums_Gender::UNKNOWN;
        if($_customer->getCustomerGender() == 1) {
            $gender = Tritac_CapayableApiClient_Enums_Gender::MALE;
        }elseif($_customer->getCustomerGender() == 2) {
            $gender = Tritac_CapayableApiClient_Enums_Gender::FEMALE;
        }
        $req->setGender($gender);
        $req->setBirthDate($_customer->getCustomerDob());
        $req->setStreetName($_customer->getStreet());
        $req->setHouseNumber($_customer->getHouseNumber());
        $req->setHouseNumberSuffix($_customer->getHouseSuffix());
        $req->setZipCode($_customer->getPostcode());
        $req->setCity($_customer->getCity());
        $req->setCountryCode($_customer->getCountryId());
        $req->setPhoneNumber($_customer->getTelephone());
        $req->setFaxNumber($_customer->getFax());
        $req->setEmailAddress($_customer->getCustomerEmail());
        $req->setIsCorporation((bool) $_customer->getIsCorporation());
        $req->setCocNumber($_customer->getCocNumber());
        $req->setCorporationName($_customer->getCorporationName());
        $req->setIsFinal($isFinal);
        $req->setClaimAmount($amount);

        $result = $this->_client->doCreditCheck($req);

        /**
         * If request is final return result array which contain transaction id.
         */
        if($isFinal) {
            return $result;
        }

        return $result->getIsAccepted();
    }

    /**
     * Post new invoice to Capayable
     *
     * @param $invoice
     * @return mixed
     */
    public function processApiInvoice($invoice)
    {
        // Initialize new api invoice
        $apiInvoice = new Tritac_CapayableApiClient_Models_Invoice();
        // Set required information
        $apiInvoice->setTransactionNumber($invoice->getTransactionId());
        $apiInvoice->setInvoiceNumber($invoice->getId());
        $apiInvoice->setInvoiceAmount($this->getHelper()->convertToCents($invoice->getGrandTotal()));
        $apiInvoice->setInvoiceDescription(Mage::helper('capayable')->__('Order').' '.$invoice->getOrder()->getIncrementId());
        // Register new invoice with Capayable
        $isAccepted = $this->_client->registerInvoice($apiInvoice);

        return $isAccepted;
    }
}