<?php

class MW_EmailOrderForForeign_Block_Adminhtml_Distributor_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('distributor_id' => $this->getRequest()->getParam('distributor_id'))),
                                      'method' => 'post'
                                   )
      );

      $form->setUseContainer(true);
      $this->setForm($form);
      return parent::_prepareForm();
  }
}