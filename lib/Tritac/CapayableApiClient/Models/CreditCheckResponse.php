<?php
class Tritac_CapayableApiClient_Models_CreditCheckResponse extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $transactionNumber;
    protected $invoiceNumber;
    protected $invoiceDate;
    protected $invoiceAmount;
    protected $invoiceDescription;

    public function __construct($isAccepted, $transactionNumber)
    {
        $this->transactionNumber = $transactionNumber;
        $this->isAccepted = $isAccepted;
    }

    function getTransactionNumber() { return $this->transactionNumber; }
    function getIsAccepted() { return $this->isAccepted; }
}