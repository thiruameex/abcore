<?php
class MW_Ipchecker_Block_Adminhtml_Ipchecker extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_ipchecker';
    $this->_blockGroup = 'ipchecker';
    $this->_headerText = Mage::helper('ipchecker')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('ipchecker')->__('Add Item');
    parent::__construct();
  }
}