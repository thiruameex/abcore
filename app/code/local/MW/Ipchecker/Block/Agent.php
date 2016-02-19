<?php
class MW_Ipchecker_Block_agent extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('agent', array(
            'label' => Mage::helper('adminhtml')->__('User-Agents String & ID'),
            'style' => 'width:120px',
        ));
        $this->addColumn('description', array(
            'label' => Mage::helper('adminhtml')->__('Description'),
            'style' => 'width:160px',
        ));
			
						 
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Agents');
        parent::__construct();
    }
}