<?php

/**
 * Class Humm_HummPayments_Block_Form_HummPayments
 * @Description Code behind for the custom Humm payment form.
 * @Remarks Invokes the view template in a distant folder structure: ~/app/design/frontend/base/default/template/HummPayments/form.phtml
 *
 */
class Humm_HummPayments_Block_Adminhtml_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return (string)Mage::getConfig()->getNode()->modules->Humm_HummPayments->version;
    }
}