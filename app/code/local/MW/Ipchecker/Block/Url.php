<?php
class MW_Ipchecker_Block_url extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('url', array(
            'label' => Mage::helper('adminhtml')->__('URL'),
            'style' => 'width:120px',
        ));
		$this->addColumn('description', array(
            'label' => Mage::helper('adminhtml')->__('Description'),
            'style' => 'width:160px',
        )); 
        /* $this->addColumn('storeview', array(
            'label' => Mage::helper('adminhtml')->__('Default Store View'),
            'style' => 'width:160px',
        ));
		$this->addColumn('currnecy', array(
            'label' => Mage::helper('adminhtml')->__('Default Currency'),
            'style' => 'width:200px',
        ));		 */ 
						 
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add URLs');
        parent::__construct();
    }
}