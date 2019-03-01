<?php

$installer = $this;

$installer->startSetup();

$installer->run( "DELETE FROM `{$installer->getTable('sales_order_status_state')}` WHERE status='humm_processing';" );
$installer->run( "DELETE FROM `{$installer->getTable('sales_order_status')}` WHERE status='humm_processing';" );

$installer->endSetup();
?>
