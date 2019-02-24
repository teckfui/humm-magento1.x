<?php

/**
 * Class Humm_HummPayments_Helper_Data
 *
 * Provides helper methods for retrieving data for the humm plugin
 */
class Humm_HummPayments_Helper_Data extends Mage_Core_Helper_Abstract
{   
    public static function init()
    {
    }

    /**
     * get the URL of the configured humm gateway checkout
     * @return string
     */
    public static function getCheckoutUrl() {
        $country = Mage::getStoreConfig('payment/HummPayments/specificcountry');
        $country_domain = $country == 'NZ'? '.co.nz' : '.com.au';  // .com.au is the default value
        $isSandbox = Mage::getStoreConfig('is_testing')=='yes'? false : true;
        if (!$isSandbox){
			return 'https://secure.oxipay'.$country_domain.'/Checkout?platform=Default';
		} else {
			return 'https://securesandbox.oxipay'.$country_domain.'/Checkout?platform=Default';
		}
    }

	/**
	 * get the URL of the configured humm gateway checkout
	 * @return string
	 */
	public static function getRefundUrl() {
        $country = Mage::getStoreConfig('payment/HummPayments/specificcountry');
        $country_domain = $country == 'NZ'? '.co.nz' : '.com.au';  // .com.au is the default value
        $isSandbox = Mage::getStoreConfig('is_testing')=='yes'? false : true;
		if (!$isSandbox){
			return 'https://portals.shophumm'.$country_domain.'/api/ExternalRefund/processrefund';
		} else {
			return 'https://portalssandbox.shophumm'.$country_domain.'/api/ExternalRefund/processrefund';
		}
	}

    /**
     * @return string
     */
    public static function getCompleteUrl() {
        return Mage::getBaseUrl() . 'HummPayments/payment/complete';
    }

    /**
     * @return string
     */
    public static function getCancelledUrl($orderId) {
        return Mage::getBaseUrl() . "HummPayments/payment/cancel?orderId=$orderId";
    }
}
Humm_HummPayments_Helper_Data::init();