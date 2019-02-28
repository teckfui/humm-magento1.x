<?php

/**
 * Class Humm_HummPayments_Block_Form_HummPayments
 * @Description Code behind for the custom Humm payment form.
 * @Remarks Invokes the view template in a distant folder structure: ~/app/design/frontend/base/default/template/HummPayments/form.phtml
 *
 */
class Humm_HummPayments_Block_Form_HummPayments extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $mark = Mage::getConfig()->getBlockClassName('core/template');
        $mark = new $mark;
        $mark->setTemplate('HummPayments/mark.phtml');
        $this->setMethodLabelAfterHtml($mark->toHtml());
        parent::_construct();
        $this->setTemplate('HummPayments/form.phtml');
    }
}