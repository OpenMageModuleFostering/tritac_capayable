<?php
class Tritac_Capayable_AjaxController extends Mage_Core_Controller_Front_Action{
    public function registrationcheckAction() {
	$helper = Mage::helper('capayable');	
	$public_key = $helper->getPublicKey();
	$secret_key = $helper->getSecretKey();
	$mode = $helper->getMode();
	
	$client = new Tritac_CapayableApiClient_Client($public_key, $secret_key, $mode);
	
	$coc_number = $this->getRequest()->getParam("coc_number");
	if (!$coc_number) {
		$coc_number = 0;
	}
	$coc_number = intval($coc_number);
	
    	$registrationCheckRequest = new Tritac_CapayableApiClient_Models_RegistrationCheckRequest($coc_number);
	$registrationCheckResult = $client->doRegistrationCheck($registrationCheckRequest);

	$arrayData = array();
	$arrayData['isAccepted'] = $registrationCheckResult->getIsAccepted();
	$arrayData['houseNumber'] = $registrationCheckResult->getHouseNumber();
	$arrayData['houseNumberSuffix'] = $registrationCheckResult->getHouseNumberSuffix();
	$arrayData['zipCode'] = $registrationCheckResult->getZipCode();
	$arrayData['city'] = $registrationCheckResult->getCity();
	$arrayData['countryCode'] = $registrationCheckResult->getCountryCode();
	$arrayData['phoneNumber'] = $registrationCheckResult->getPhoneNumber();
	$arrayData['corporationName'] = $registrationCheckResult->getCorporationName();
	$arrayData['cocNumber'] = $coc_number;
	$arrayData['streetName'] = $registrationCheckResult->getStreetName();
				
	$jsonData = json_encode($arrayData);
	
	$this->getResponse()->setHeader('Content-type', 'application/json');
	$this->getResponse()->setBody($jsonData);
	
    }
}