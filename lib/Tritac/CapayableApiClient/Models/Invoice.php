<?php
class Tritac_CapayableApiClient_Models_Invoice extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $transactionNumber;
    protected $invoiceNumber;
    protected $invoiceDate;
    protected $invoiceAmount;
    protected $invoiceDescription;

    public function __construct()
    {
        $this->transactionNumber = '';
        $this->invoiceNumber = '';
        $this->invoiceDate = new DateTime();
        $this->invoiceAmount = 0;
        $this->invoiceDescription = '';
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
}