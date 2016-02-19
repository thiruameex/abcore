<?php
class MW_Ipchecker_Block_setup extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('country', array(
            'label' => Mage::helper('adminhtml')->__('Country'),
            'style' => 'width:120px',
        ));
        $this->addColumn('storeview', array(
            'label' => Mage::helper('adminhtml')->__('Default Store View'),
            'style' => 'width:160px',
        ));
		/* $this->addColumn('currnecy', array(
            'label' => Mage::helper('adminhtml')->__('Default Currency'),
            'style' => 'width:200px',
        ));		 */
						 
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Rules');
        parent::__construct();
    }
}