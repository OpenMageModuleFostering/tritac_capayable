<?php
class Tritac_CapayableApiClient_Models_RegistrationCheckRequest extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $cocNumber;

    public function __construct($cocNumber){
        

        if(strlen($cocNumber) > 20)  {
            throw new Exception('CoC-number number may not exceed 20 characters');
        }
        $this->cocNumber = $cocNumber;

    }

    public function getCocNumber(){
        return $this->cocNumber;
    }
}
