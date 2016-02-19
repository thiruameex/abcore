<?php

class MW_Ipchecker_Block_Adminhtml_Ipchecker_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('ipchecker_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('ipchecker')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('ipchecker')->__('Item Information'),
          'title'     => Mage::helper('ipchecker')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('ipchecker/adminhtml_ipchecker_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}