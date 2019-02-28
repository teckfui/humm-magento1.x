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
        return Mage::getStoreConfig('payment/HummPayments/gateway_url');
    }

	/**
	 * get the URL of the configured humm gateway checkout
	 * @return string
	 */
	public static function getRefundUrl() {
		$checkoutUrl = self::getCheckoutUrl();
		if (strpos($checkoutUrl, ".co.nz") !== false){
			$country_domain = '.co.nz';
		} else {
			$country_domain = '.com.au'; // default value
		}

		if (strpos($checkoutUrl, 'sandbox') === false) {
			$isSandbox = false;
		} else {
			$isSandbox = true; //default value
		}

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