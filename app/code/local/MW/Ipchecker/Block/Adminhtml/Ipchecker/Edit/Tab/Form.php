<?php

class MW_Ipchecker_Block_Adminhtml_Ipchecker_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('ipchecker_form', array('legend'=>Mage::helper('ipchecker')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('ipchecker')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('ipchecker')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('ipchecker')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('ipchecker')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('ipchecker')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('ipchecker')->__('Content'),
          'title'     => Mage::helper('ipchecker')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getIpcheckerData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getIpcheckerData());
          Mage::getSingleton('adminhtml/session')->setIpcheckerData(null);
      } elseif ( Mage::registry('ipchecker_data') ) {
          $form->setValues(Mage::registry('ipchecker_data')->getData());
      }
      return parent::_prepareForm();
  }
}