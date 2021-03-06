<?php

class MW_EmailOrderForForeign_Block_Adminhtml_Distributor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('distributor_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('emailorderforforeign')->__('Mapping Country To Distributor'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('Detail Mapping', array(
          'label'     => Mage::helper('emailorderforforeign')->__('Detail Mapping'),
          'title'     => Mage::helper('emailorderforforeign')->__('Detail Mapping'),
          'content'   => $this->getLayout()->createBlock('emailorderforforeign/adminhtml_distributor_edit_tab_mapping')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}