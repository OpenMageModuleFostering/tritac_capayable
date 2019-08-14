<?php
class Tritac_CapayableApiClient_Models_InvoiceCreditRequest extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $transactionNumber;
    protected $returnNumber;
    protected $creditAmount;

    public function __construct($transactionNumber, $returnNumber, $creditAmount){

        if(strlen($transactionNumber) > 32)  {
            throw new Exception('Transaction number may not exceed 32 characters');
        }
        $this->transactionNumber = $transactionNumber;

        if(strlen($returnNumber) > 150)  {
            throw new Exception('Return number may not exceed 150 characters');
        }
        $this->returnNumber = $returnNumber;

        if(!is_numeric($creditAmount)) {
            throw new Exception('Credit amount must be numeric');
        }
        $this->creditAmount = $creditAmount;
    }

    public function getTransactionNumber(){
        return $this->transactionNumber;
    }
    
    public function getReturnNumber(){
        return $this->returnNumber;
    }

    public function getCreditAmount(){
        return $this->creditAmount;
    }
}
