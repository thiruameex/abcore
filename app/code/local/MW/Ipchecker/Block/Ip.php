<?php
class MW_Ipchecker_Block_ip extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('ip', array(
            'label' => Mage::helper('adminhtml')->__('IP Address'),
            'style' => 'width:120px',
        ));
	 	$this->addColumn('description', array(
            'label' => Mage::helper('adminhtml')->__('Description'),
            'style' => 'width:160px',
        )); 
       
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add IPs');
        parent::__construct();
    }
}