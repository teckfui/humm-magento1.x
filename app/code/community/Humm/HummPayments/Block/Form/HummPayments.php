<?php

/**
 * Class Humm_HummPayments_Block_Form_HummPayments
 * @Description Code behind for the custom Humm payment form.
 * @Remarks Invokes the view template in a distant folder structure: ~/app/design/frontend/base/default/template/HummPayments/form.phtml
 *
 */
class Humm_HummPayments_Block_Form_HummPayments extends Mage_Payment_Block_Form {
    const LAUNCH_TIME_URL = 'https://s3-ap-southeast-2.amazonaws.com/humm-variables/launch-time.txt';
    const LAUNCH_TIME_DEFAULT = "2019-04-07 14:30:00";
    const LAUNCH_TIME_CHECK_ENDS = "2019-10-07 13:30:00";

    protected function _construct() {
        $this->updateLaunchDate();
        $mark = Mage::getConfig()->getBlockClassName( 'core/template' );
        $mark = new $mark;
        $mark->setTemplate( 'HummPayments/mark.phtml' );
        $this->setMethodLabelAfterHtml( $mark->toHtml() );
        parent::_construct();
        $this->setTemplate( 'HummPayments/form.phtml' );
    }

    private function updateLaunchDate() {
        $launch_time_string             = Mage::getStoreConfig( 'payment/HummPayments/launch_time' );
        $launch_time_update_time_string = Mage::getStoreConfig( 'payment/HummPayments/launch_time_updated' );
        if ( empty( $launch_time_string ) || ( time() - $launch_time_update_time_string >= 3600 ) ) {
            $remote_launch_time_string = ( time() - self::LAUNCH_TIME_CHECK_ENDS < 0 )? file_get_contents( self::LAUNCH_TIME_URL ) : '';
            if ( ! empty( $remote_launch_time_string ) ) {
                $launch_time_string = $remote_launch_time_string;
                Mage::getConfig()->saveConfig( 'payment/HummPayments/launch_time', $launch_time_string );
                Mage::getConfig()->saveConfig( 'payment/HummPayments/launch_time_updated', time() );
            } elseif ( empty( $launch_time_string ) || ( empty( $launch_time_update_time_string ) && $launch_time_string != self::LAUNCH_TIME_DEFAULT ) ) {
                // this is when $launch_time_string never set (first time run of the plugin), or local const LAUNCH_TIME_DEFAULT changes and and never update from remote.
                // Mainly for development, for changing const LAUNCH_TIME_DEFAULT to take effect.
                $launch_time_string = self::LAUNCH_TIME_DEFAULT;
                Mage::getConfig()->saveConfig( 'payment/HummPayments/launch_time', $launch_time_string );
            }
        }
    }
}