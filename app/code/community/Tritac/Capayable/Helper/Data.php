<?php
/**
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Tritac_Capayable_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Get current extension environment
     *
     * @return int
     */
    public function getMode() {
        $configMode = Mage::getStoreConfig('payment/capayable/test');
        if($configMode) {
            return Tritac_CapayableApiClient_Enums_Environment::TEST;
        } else {
            return Tritac_CapayableApiClient_Enums_Environment::PROD;
        }
    }

    /**
     * Convert price to cents.
     *
     * @param $amount
     * @return int
     */
    public function convertToCents($amount) {
        return (int) ($amount * 100);
    }
}