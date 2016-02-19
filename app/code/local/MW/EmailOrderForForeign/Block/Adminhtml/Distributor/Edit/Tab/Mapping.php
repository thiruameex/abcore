<?php

class MW_EmailOrderForForeign_Block_Adminhtml_Distributor_Edit_Tab_Mapping extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('emailorderforforeign_form', array('legend'=>Mage::helper('emailorderforforeign')->__('Detail Mapping')));
      $countries = Mage::getSingleton('emailorderforforeign/distributor')->getCountryOptionArray();
      $fieldset->addField('country', 'select', array(
          'label'     => Mage::helper('emailorderforforeign')->__('Country'),
          'required'  => true,
          'name'      => 'country',
          'options' => $countries
      ));
      
      $distribute_links = Mage::getSingleton('emailorderforforeign/distributor')->getDistributorLinkOptionArray();
      
      $fieldset->addField('distributor_link', 'select', array(
          'label'     => Mage::helper('emailorderforforeign')->__('Distributor\'s link'),
          'name'      => 'distributor_link',
          'required'  => true,
          'options'   => $distribute_links
      ));
      
      
      if ( Mage::getSingleton('adminhtml/session')->getDistributorData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDistributorData());
          Mage::getSingleton('adminhtml/session')->setDistributorData(null);
      } elseif ( Mage::registry('distributor_data') ) {

          $form->setValues(Mage::registry('distributor_data')->getData());
      }
      return parent::_prepareForm();
  }
}