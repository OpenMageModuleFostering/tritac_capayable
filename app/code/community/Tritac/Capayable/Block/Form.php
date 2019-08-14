<?php
/**
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Tritac_Capayable_Block_Form extends Mage_Payment_Block_Form
{
    /**
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote;

    /**
     * @var Tritac_Capayable_Model_Customer
     */
    protected $_customer;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('capayable/form.phtml');
    }

    /**
     * Get quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote() {
        if(!$this->_quote) {
            $this->_quote = $this->getMethod()->getInfoInstance()->getQuote();
        }

        return $this->_quote;
    }

    /**
     * Get capayable customer
     *
     * @return Tritac_Capayable_Model_Customer
     */
    public function getCustomer() {
        if(!$this->_customer) {
            $email = $this->getQuote()->getCustomerEmail();
            // If customer doesn't exists then return empty model
            $this->_customer = Mage::getModel('capayable/customer')->loadByEmail($email);
        }

        return $this->_customer;
    }
}
