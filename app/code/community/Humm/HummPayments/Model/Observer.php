<?php

class Humm_HummPayments_Model_Observer {
    const LOG_FILE = 'humm.log';

    const JOB_PROCESSING_LIMIT = 50;

    /**
     * Cron job to cancel Pending Payment Humm orders
     *
     * @param Mage_Cron_Model_Schedule $schedule
     *
     * @throws Exception
     */
    public function cancelHummPendingOrders( Mage_Cron_Model_Schedule $schedule ) {
        Mage::log( '[humm][cron][cancelHummPendingOrders]Start', Zend_Log::DEBUG, self::LOG_FILE );

        $orderCollection = Mage::getResourceModel( 'sales/order_collection' );
        $orderCollection->join(
            array( 'p' => 'sales/order_payment' ),
            'main_table.entity_id = p.parent_id',
            array()
        );
        $orderCollection
            ->addFieldToFilter( 'main_table.state', Humm_HummPayments_Helper_OrderStatus::STATUS_PENDING_PAYMENT )
            ->addFieldToFilter( 'p.method', array( 'like' => 'humm%' ) )
            ->addFieldToFilter( 'main_table.created_at', array( 'lt' => new Zend_Db_Expr( "DATE_ADD('" . now() . "', INTERVAL -'90:00' HOUR_MINUTE)" ) ) );

        $orderCollection->setOrder( 'main_table.updated_at', Varien_Data_Collection::SORT_ORDER_ASC );
        $orderCollection->setPageSize( self::JOB_PROCESSING_LIMIT );

        $orders = "";
        foreach ( $orderCollection->getItems() as $order ) {
            $orderModel = Mage::getModel( 'sales/order' );
            $orderModel->load( $order['entity_id'] );

            if ( ! $orderModel->canCancel() ) {
                continue;
            }

            $orderModel->cancel();

            $history = $orderModel->addStatusHistoryComment( 'Humm payment was not received for this order after 90 minutes' );
            $history->save();

            $orderModel->save();
        }
    }

    public function carryOverSettings( $observer ) {
        if ( ! Mage::getStoreConfig( 'payment/HummPayments/merchant_number' ) ) {
            $is_carried_over = Mage::getStoreConfig( 'payment/HummPayments/settings_carried_over' );
            if ( Mage::getStoreConfig( 'payment/OxiPayments/merchant_number' ) && empty( $is_carried_over ) ) {
                $carry_over_targets = [
                    'active',
                    'merchant_number',
                    'api_key',
                    'is_testing',
                    'humm_approved_order_status',
                    'automatic_invoice',
                    'email_customer',
                    'min_order_total',
                    'max_order_total',
                    'specificcountry',
                    'sort_order',
                ];
                foreach ( $carry_over_targets as $target ) {
                    $source = ( $target == 'humm_approved_order_status' ) ? 'oxipay_approved_order_status' : $target;
                    Mage::getConfig()->saveConfig( 'payment/HummPayments/' . $target, Mage::getStoreConfig( 'payment/OxiPayments/' . $source ) );
                }
                // set approved order status to 'processing' by default because other statuses may not exist
                Mage::getConfig()->saveConfig( 'payment/HummPayments/humm_approved_order_status', 'processing' );
                // 'is_testing' is a new setting so it does not exist in earlier versions.
                // So setting it to 0 by default to avoid going into sandbox
                // This is different from the default value in config.xml because we want new merchants to do testing first but old merchants should remain in live.
                Mage::getConfig()->saveConfig( 'payment/HummPayments/is_testing', '0' );
            }

            Mage::getConfig()->saveConfig( 'payment/HummPayments/settings_carried_over', 1 );
        }
    }
}