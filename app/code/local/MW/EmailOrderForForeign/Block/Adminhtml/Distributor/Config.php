<?php
class MW_EmailOrderForForeign_Block_Adminhtml_Distributor_Config extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('distributor', array(
            'label' => Mage::helper('emailorderforforeign')->__('distributor'),
            'style' => 'width:100px',
        ));
        $this->addColumn('distributor_link', array(
            'label' => Mage::helper('emailorderforforeign')->__("ditributor's link"),
            'style' => 'width:100px',
        ));	
	
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('emailorderforforeign')->__('Add distributor');
        parent::__construct();
    }
}