<?php
class MW_Uploadattribute_Block_Adminhtml_Catalog_Product_Edit_Tab_File extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

  protected function _prepareForm()
  {    
  	  $pId = $this->getRequest()->getParam('id');
  	  $product = Mage::getModel('catalog/product')->load($pId);
  	  if($product->getMsds())
  	  {
  	  	$msds = $product->getMsds();
  	  }
  	  if($product->getManual())
  	  {
  	  	$manual = $product->getManual();
  	  }
      $form = new Varien_Data_Form(array(
            'id'      => 'upload_form',
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));
      $this->setForm($form);
      $fieldset = $form->addFieldset('file_form', array('legend'=>Mage::helper('uploadattribute')->__('File Upload')));
	  
      $fieldset->addField('msds_file', 'file', array(
	  	'label'     => 'Upload MSDS',
	    'required'  => false,
	    'name'      => 'msds_file',
		));
	  $fieldset->addField('msds', 'text', array(
	  	'label'     => 'MSDS File',
	    'required'  => false,
	    'name'      => 'product[msds]',
	  	'value'		=>$msds,
		));		
	  $fieldset->addField('manual_file', 'file', array(
	  	'label'     => 'Upload Manual',
	    'required'  => false,
	    'name'      => 'manual_file',
		));
	  $fieldset->addField('manual', 'text', array(
	  	'label'     => 'Manual File',
	    'required'  => false,
	    'name'      => 'product[manual]',
	 	'value'		=>$manual,
		));	
	  
      return parent::_prepareForm();
  }
/**
     * Retrieve Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('catalog')->__('File Upload');
    }

    /**
     * Retrieve Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('catalog')->__('File Upload');
    }

    /**
     * Can show tab flag
     *
     * @return bool
     */
    public function canShowTab()
    {
        $param=Mage::app()->getRequest()->getParams();
        if( isset($param['type']) || isset($param['id']) ){
			return true;
		}
		else return false;
    }

    /**
     * Check is a hidden tab
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}


