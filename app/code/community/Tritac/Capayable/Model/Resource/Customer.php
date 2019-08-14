<?php
/**
 * Capayable customer resource model
 *
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Tritac_Capayable_Model_Resource_Customer extends Mage_Core_Model_Resource_DB_Abstract {

    protected function _construct()
    {
        $this->_init('capayable/customer', 'entity_id');
    }

    /**
     * Load customer data by customer email
     *
     * @param Tritac_Capayable_Model_Customer $customer
     * @param $email
     * @return $this
     */
    public function loadByEmail(Tritac_Capayable_Model_Customer $customer, $email)
    {

        $adapter = $this->_getReadAdapter();
        $bind    = array('customer_email' => $email);
        $select  = $adapter->select()
            ->from($this->getMainTable(), array($this->getIdFieldName()))
            ->where('customer_email = :customer_email');

        $entityId = $adapter->fetchOne($select, $bind);
        if ($entityId) {
            $this->load($customer, $entityId);
        } else {
            $customer->setData(array());
        }

        return $this;
    }

    /**
     * Load customer data by customer id
     *
     * @param Tritac_Capayable_Model_Customer $customer
     * @param $customerId
     * @return $this
     */
    public function loadByCustomerId(Tritac_Capayable_Model_Customer $customer, $customerId)
    {

        $adapter = $this->_getReadAdapter();
        $bind    = array('customer_id' => $customerId);
        $select  = $adapter->select()
            ->from($this->getMainTable(), array($this->getIdFieldName()))
            ->where('customer_id = :customer_id');

        $entityId = $adapter->fetchOne($select, $bind);
        if ($entityId) {
            $this->load($customer, $entityId);
        } else {
            $customer->setData(array());
        }

        return $this;
    }
}