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
     * get the Domain part of the configured humm gateway checkout/refund URL
     * @return string
     */
    public static function getCheckoutDomain() {
        $checkoutUrl = Mage::getStoreConfig( 'payment/HummPayments/gateway_url' );
        if ( isset( $checkoutUrl ) and strtolower( substr( $checkoutUrl, 0, 4 ) ) == 'http' ) {
            return $checkoutUrl;
        } else {
            $country            = Mage::getStoreConfig( 'payment/HummPayments/specificcountry' );
            $country_domain     = $country == 'NZ' ? '.co.nz' : '.com.au';  // .com.au is the default value
            $isSandbox          = Mage::getStoreConfig( 'payment/HummPayments/is_testing' ) ? true : false;
            $launch_time_string = Mage::getStoreConfig( 'payment/HummPayments/launch_time_string' );
            $is_after           = ( time() - strtotime( $launch_time_string ) >= 0 ) || Mage::getStoreConfig( 'payment/HummPayments/force_humm' );
            $title              = ( $is_after && Mage::getStoreConfig( 'payment/HummPayments/specificcountry' ) == 'AU' ) ? 'Humm' : 'Oxipay';
            $domainsTest        = array(
                'Humm'   => 'test3-cart.shophumm',
                'Oxipay' => 'securesandbox.oxipay'
            );
            $domains            = array(
                'Humm'   => 'cart.shophumm',
                'Oxipay' => 'secure.oxipay'
            );

            return 'https://' . ( $isSandbox ? $domainsTest[ $title ] : $domains[ $title ] ) . $country_domain;

        }
    }

    /**
     * get the URL of the configured humm gateway checkout
     * @return string
     */
    public static function getCheckoutURL() {
        return self::getCheckoutDomain() . '/Checkout?platform=Default';
    }

    /**
     * get the URL of the configured humm gateway refund
     * @return string
     */
    public static function getRefundURL() {
        return self::getCheckoutDomain() . '/api/ExternalRefund/processrefund';
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