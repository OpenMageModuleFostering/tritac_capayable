<?php
class Tritac_CapayableApiClient_Models_InvoiceCreditResponse extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $result;
    protected $amountNotCredited;
    protected $amountCredited;

    public function __construct($result, $amountCredited, $amountNotCredited){
        $this->result = $result;
        $this->amountCredited = $amountCredited;
        $this->amountNotCredited = $amountNotCredited;
    }

    public function getResult(){
        return $this->result;
    }
    
    public function getAmountNotCredited(){
        return $this->amountNotCredited;
    }

    public function getAmountCredited(){
        return $this->amountCredited;
    }
}
