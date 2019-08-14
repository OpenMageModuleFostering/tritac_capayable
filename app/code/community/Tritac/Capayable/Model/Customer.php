<?php
/**
 * Capayable customer model
 *
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Tritac_Capayable_Model_Customer extends Mage_Core_Model_Abstract {

    public function _construct() {
        $this->_init('capayable/customer');
    }

    /**
     * Load capayable customer by email
     *
     * @param   string $email
     * @return  Tritac_Capayable_Model_Customer
     */
    public function loadByEmail($email)
    {
        $this->_getResource()->loadByEmail($this, $email);
        return $this;
    }

    /**
     * Load capayable customer by customer id
     *
     * @param   string $customerId
     * @return  Tritac_Capayable_Model_Customer
     */
    public function loadByCustomerId($customerId)
    {
        $this->_getResource()->loadByCustomerId($this, $customerId);
        return $this;
    }

    /**
     * Validate capayable customer required fields
     *
     * @return array|bool
     */
    public function validate() {

        $errors = array();
        if (!Zend_Validate::is( trim($this->getCustomerLastname()) , 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The first name cannot be empty.');
        }

        if (!Zend_Validate::is( trim($this->getCustomerMiddlename()) , 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The initials cannot be empty.');
        }

        if (!Zend_Validate::is($this->getCustomerEmail(), 'EmailAddress')) {
            $errors[] = Mage::helper('capayable')->__('Invalid email address "%s".', $this->getEmail());
        }

        if (!Zend_Validate::is($this->getCustomerGender(), 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('Gender is required.');
        }

        if (!Zend_Validate::is($this->getCustomerDob(), 'Date', array('format' => 'yyyy-MM-dd hh:ii:ss'))) {
            $errors[] = Mage::helper('capayable')->__('The Date of Birth is requiredd.');
        }

        if (!Zend_Validate::is($this->getStreet(), 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The street is required.');
        }

        if (!Zend_Validate::is($this->getHouseNumber(), 'Alnum')) {
            $errors[] = Mage::helper('capayable')->__('House number must be a numeric.');
        }

        if (!Zend_Validate::is($this->getPostcode(), 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The zip/postal is required.');
        }

        if (!Zend_Validate::is($this->getCity(), 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The city is required.');
        }

        if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The country is required.');
        }

        if (!Zend_Validate::is($this->getTelephone(), 'NotEmpty')) {
            $errors[] = Mage::helper('capayable')->__('The telephone number is required.');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }
}