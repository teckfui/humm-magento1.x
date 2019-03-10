<?php

/**
 * Class Humm_HummPayments_Helper_Data
 *
 * Provides helper methods for retrieving data for the humm plugin
 */
class Humm_HummPayments_Helper_Data extends Mage_Core_Helper_Abstract {
    public static function init() {
    }

    /**
     * get the URL of the configured humm gateway checkout
     * @return string
     */
    public static function getCheckoutUrl() {
        $checkoutUrl = Mage::getStoreConfig( 'payment/HummPayments/gateway_url' );
        if ( isset( $checkoutUrl ) and strtolower( substr( $checkoutUrl, 0, 4 ) ) == 'http' ) {
            return $checkoutUrl;
        } else {
            $country        = Mage::getStoreConfig( 'payment/HummPayments/specificcountry' );
            $country_domain = $country == 'NZ' ? '.co.nz' : '.com.au';  // .com.au is the default value
            $isSandbox      = Mage::getStoreConfig( 'payment/HummPayments/is_testing' ) ? true : false;

            $launch_time_string = Mage::getStoreConfig( 'payment/HummPayments/launch_time_string' );
            $is_after           = ( time() - strtotime( $launch_time_string ) >= 0 ) || Mage::getStoreConfig( 'payment/HummPayments/force_humm' );
            $main_domain        = ( $is_after && Mage::getStoreConfig( 'payment/HummPayments/specificcountry' ) == 'AU' ) ? 'shophumm' : 'oxipay';

            if ( ! $isSandbox ) {
                return 'https://secure.' . $main_domain . $country_domain . '/Checkout?platform=Default';
            } else {
                return 'https://securesandbox.' . $main_domain . $country_domain . '/Checkout?platform=Default';
            }
        }
    }

    /**
     * get the URL of the configured humm gateway checkout
     * @return string
     */
    public static function getRefundUrl() {
        $country        = Mage::getStoreConfig( 'payment/HummPayments/specificcountry' );
        $country_domain = $country == 'NZ' ? '.co.nz' : '.com.au';  // .com.au is the default value
        $isSandbox      = Mage::getStoreConfig( 'payment/HummPayments/is_testing' ) == 'yes' ? false : true;

        $launch_time_string = Mage::getStoreConfig( 'payment/HummPayments/launch_time_string' );
        $is_after           = ( time() - strtotime( $launch_time_string ) >= 0 ) || Mage::getStoreConfig( 'payment/HummPayments/force_humm' );
        $main_domain        = ( $is_after && Mage::getStoreConfig( 'payment/HummPayments/specificcountry' ) == 'AU' ) ? 'shophumm' : 'oxipay';

        if ( ! $isSandbox ) {
            return 'https://portals.' . $main_domain . $country_domain . '/api/ExternalRefund/processrefund';
        } else {
            return 'https://portalssandbox.' . $main_domain . $country_domain . '/api/ExternalRefund/processrefund';
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
    public static function getCancelledUrl( $orderId ) {
        return Mage::getBaseUrl() . "HummPayments/payment/cancel?orderId=$orderId";
    }
}

Humm_HummPayments_Helper_Data::init();