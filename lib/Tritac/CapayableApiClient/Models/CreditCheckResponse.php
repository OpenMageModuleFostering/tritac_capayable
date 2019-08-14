<?php
class Tritac_CapayableApiClient_Models_CreditCheckResponse extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $isAccepted;
		protected $transactionNumber;
		protected $refuseReason;
		protected $refuseContactName;
		protected $refuseContactPhoneNumber;

		public function __construct(){
			$this->isAccepted = false;
			$this->transactionNumber = '';
			$this->refuseReason = Tritac_CapayableApiClient_Enums_RefuseReason::CREDITCHECK_UNAVAILABLE;
		}

		public function setAccepted($transactionNumber){
			$this->isAccepted = true;
			$this->transactionNumber = $transactionNumber;
		}
		
		public function setRefused($refuseReason, $refuseContactName, $refusePhoneNumber){
			$this->isAccepted = false;
			$this->refuseReason = $refuseReason;
			$this->refuseContactName = $refuseContactName;
			$this->refusePhoneNumber = $refusePhoneNumber;
		}
		
		function getIsAccepted() { return $this->isAccepted; }
		function getTransactionNumber() { return $this->transactionNumber; }
		function getRefuseReason() { return $this->refuseReason; }
		function getRefuseContactName() { return $this->refuseContactName; }
		function getRefuseContactPhoneNumber() { return $this->refuseContactPhoneNumber; }
}