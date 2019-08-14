<?php
class Tritac_CapayableApiClient_Models_CreditCheckRequest extends Tritac_CapayableApiClient_Models_BaseModel
{
    protected $lastName;
    protected $initials;
    protected $gender; // https://en.wikipedia.org/wiki/ISO/IEC_5218
    protected $birthDate;
    protected $streetName;
    protected $houseNumber;
    protected $houseNumberSuffix;
    protected $zipCode;
    protected $city;
    protected $countryCode;
    protected $phoneNumber;
    protected $faxNumber;
    protected $emailAddress;
    protected $isCorporation;

		// For corporations
    protected $corporationName;
    protected $cocNumber;
		protected $isSoleProprietor;

    protected $isFinal;
    protected $claimAmount;

    public function __construct()
    {
        $this->lastName = '';
        $this->initials = '';
        $this->gender = Tritac_CapayableApiClient_Enums_Gender::UNKNOWN; // https://en.wikipedia.org/wiki/ISO/IEC_5218
        $this->birthDate = new DateTime();
        $this->streetName = '';
        $this->houseNumber = '';
        $this->houseNumberSuffix = '';
        $this->zipCode = '';
        $this->city = '';
        $this->countryCode = '';
        $this->phoneNumber = '';
        $this->faxNumber = '';
        $this->emailAddress = '';
        $this->isCorporation = false;
        $this->corporationName = '';
        $this->cocNumber = '';
			$this->isSoleProprietor = false;
        $this->isFinal = false;
        $this->claimAmount = 0;
    }

    function getLastName() { return $this->lastName; }
    function setLastName($lastName) {

        if(strlen($lastName) > 150)  {
            throw new Exception('Last name may not exceed 150 characters');
        }

        $this->lastName = $lastName;
    }


    function getInitials() { return $this->initials; }
    function setInitials($initials) {

        if(strlen($initials) > 10)  {
            throw new Exception('Initials may not exceed 10 characters');
        }

        $this->initials = $initials;
    }


    function getGender() { return $this->gender; }
    function setGender($gender) {
        if(
            $gender != Tritac_CapayableApiClient_Enums_Gender::MALE &&
            $gender != Tritac_CapayableApiClient_Enums_Gender::FEMALE &&
            $gender != Tritac_CapayableApiClient_Enums_Gender::UNKNOWN
        ) {
            throw new Exception('Gender invalid, please use CapayableApiClient\Enums\Gender');
        }
        $this->gender = $gender;
    }

    function getBirthDate() { return $this->birthDate->format('Y-m-d'); }
    function getBirthDateAsDateTime() { return $this->birthDate; }
    function setBirthDate($birthDate) {

        $this->birthDate = new DateTime($birthDate);
    }
    function setBirthDateAsDateTime(DateTime $birthDate) {
        $this->birthDate = $birthDate;
    }

    function getStreetName() { return $this->streetName; }
    function setStreetName($streetName) {

        if(strlen($streetName) > 150)  {
            throw new Exception('Street name may not exceed 150 characters');
        }

        $this->streetName = $streetName;
    }

    function getHouseNumber() { return $this->houseNumber; }
    function setHouseNumber($houseNumber) {

        if(!is_numeric($houseNumber)) {
            throw new Exception('House number must be numeric');
        }

        $this->houseNumber = $houseNumber;
    }

    function getHouseNumberSuffix() { return $this->houseNumberSuffix; }
    function setHouseNumberSuffix($houseNumberSuffix) {

        if(strlen($houseNumberSuffix) > 10)  {
            throw new Exception('House number suffix may not exceed 10 characters');
        }

        $this->houseNumberSuffix = $houseNumberSuffix;

    }

    function getZipCode() { return $this->zipCode; }
    function setZipCode($zipCode) {

        if(strlen($zipCode) > 20)  {
            throw new Exception('Zip code may not exceed 20 characters');
        }

        $this->zipCode = $zipCode;
    }

    function getCity() { return $this->city; }
    function setCity($city) {

        if(strlen($city) > 150)  {
            throw new Exception('Zip code may not exceed 150 characters');
        }

        $this->city = $city;
    }

    function getCountryCode() { return $this->countryCode; }
    function setCountryCode($countryCode) {

        if(strlen($countryCode) > 2)  {
            throw new Exception('Country code may not exceed 2 characters');
        }

        $this->countryCode = $countryCode;

    }

    function getPhoneNumber() { return $this->phoneNumber; }
    function setPhoneNumber($phoneNumber) {

        if(strlen($phoneNumber) > 20)  {
            throw new Exception('Phone number may not exceed 20 characters');
        }

        $this->phoneNumber = $phoneNumber;

    }

    function getFaxNumber() { return $this->faxNumber; }
    function setFaxNumber($faxNumber) {

        if(strlen($faxNumber) > 20)  {
            throw new Exception('Fax number may not exceed 20 characters');
        }

        $this->faxNumber = $faxNumber;

    }

    function getIsCorporation() { return $this->isCorporation; }
    function setIsCorporation($isCorporation) {

        if(!is_bool($isCorporation)){
            throw new Exception('Is corporation must be a boolean');
        }

        $this->isCorporation = $isCorporation;
    }

		function getIsSoleProprietor() { return $this->isSoleProprietor; }
		function setIsSoleProprietor($isSoleProprietor) {

			if(!is_bool($isSoleProprietor)){
				throw new Exception('Is sole proprietor must be a boolean');
			}

			$this->isSoleProprietor = $isSoleProprietor;
		}

    function getEmailAddress() { return $this->emailAddress; }
    function setEmailAddress($emailAddress) {

        if(!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){
            throw new Exception('Must be a valid email');
        }

        $this->emailAddress = $emailAddress;
    }

    function getCorporationName() { return $this->corporationName; }
    function setCorporationName($corporationName) {
        $this->corporationName = $corporationName;
    }

    function getCocNumber() { return $this->cocNumber; }
    function setCocNumber($cocNumber) {

        if(strlen($cocNumber) > 20)  {
            throw new Exception('Chamber of commerce number may not exceed 20 characters');
        }

        $this->cocNumber = $cocNumber;

    }

    function getIsFinal() { return $this->isFinal; }
    function setIsFinal($isFinal) {

        if(!is_bool($isFinal)) {
            throw new Exception('Is final must be a boolean');
        }

        $this->isFinal = $isFinal;
    }

    function getClaimAmount() { return $this->claimAmount; }
    function setClaimAmount($claimAmount) {

        if(!is_numeric($claimAmount)) {
            throw new Exception('Claim amount must be numeric');
        }

        $this->claimAmount = $claimAmount;
    }
}