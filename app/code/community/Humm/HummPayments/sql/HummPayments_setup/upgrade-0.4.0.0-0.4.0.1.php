<?php
/**
 * Created by PhpStorm.
 * User: jimbur
 * Date: 22/09/2016
 * Time: 3:46 PM
 */
$installer = $this;

$installer->startSetup();

// add default Humm Status "Humm Processed" for STATE_PROCESSING state
$installer->run( "INSERT INTO `{$this->getTable('sales_order_status')}` (`status`, `label`) VALUES ('pending_humm', 'Pending Humm');" );

// update the existing status
$installer->run( "UPDATE `{$this->getTable('sales_order_status')}` set `label`= 'Humm Processed' where `status`='humm_processed'" );

// Cancelled Humm
$installer->run( "INSERT INTO `{$this->getTable('sales_order_status')}` (`status`, `label`) VALUES ('cancelled_humm', 'Cancelled Humm');" );

// Declined Humm
$installer->run( "INSERT INTO `{$this->getTable('sales_order_status')}` (`status`, `label`) VALUES ('declined_humm', 'Declined Humm');" );


$installer->endSetup();