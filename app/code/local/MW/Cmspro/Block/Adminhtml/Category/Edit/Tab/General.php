<?php

class MW_Cmspro_Block_Adminhtml_Category_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('general_form', array('legend'=>Mage::helper('cmspro')->__('General information')));

	  if (!Mage::app()->isSingleStoreMode()) {
	            $fieldset->addField('store_id', 'multiselect', array(
	                'name'      => 'category_stores[]',
	                'label'     => Mage::helper('cms')->__('Store View'),
	                'title'     => Mage::helper('cms')->__('Store View'),
	                'required'  => true,
	                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
	            ));
	   }
	              
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('cmspro')->__('Category Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));
      
      $categories = Mage::getModel('cmspro/category')->getTreeCategory(true);
		
	  $fieldset->addField('parent_id', 'select', array(
          'label'     => Mage::helper('cmspro')->__('Parent Category'),
          'required'  => true,
	  	  'values'    => $categories,
          'name'      => 'parent_id',
      )); 
           
	  $fieldset->addField('identifier', 'text', array( 
          'label'     => Mage::helper('cmspro')->__('URL Key'),
          'name'      => 'identifier',
      ));
      
      $fieldset->addField('order_cat', 'text', array(
          'label'     => Mage::helper('cmspro')->__('Order'),
          'required'  => false,
          'name'      => 'order_cat',
      )); 
      
      $fieldset->addField('images', 'image', array(
          'label'     => Mage::helper('cmspro')->__('Images'),
          'required'  => false,
          'name'      => 'images',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('cmspro')->__('Is Active'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('cmspro')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('cmspro')->__('Disabled'),
              ),
          ),
      ));

      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('cmspro')->__('Description'),
          'title'     => Mage::helper('cmspro')->__('Description'),
          'style'     => 'width:600px; height:100px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCategoryData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCategoryData());
          Mage::getSingleton('adminhtml/session')->setCategoryData(null);
      } elseif ( Mage::registry('cmsprocategory_data') ) {
          $form->setValues(Mage::registry('cmsprocategory_data')->getData());
          if (!Mage::app()->isSingleStoreMode()) {
			  $catc = Mage::registry('cmsprocategory_data');
			  if($catc->getCategoryId()){     	  	
				// get array of selected store_id 
				$collection =  Mage::getModel('cmspro/category')->getCollection();
				$collection->getSelect()->join('cmspro_category_store', 'cmspro_category_store.category_id = main_table.category_id AND main_table.category_id='.$catc->getCategoryId(), array('cmspro_category_store.store_id'));
				
				if($collection->getData()){
					$arrStoreId = array();
					foreach($collection->getData() as $col){
						$arrStoreId[] = $col['store_id'];	
					}
					// set value for store view selected:
					$form->getElement('store_id')->setValue($arrStoreId);
				} 
			 }
		 }
      }
      return parent::_prepareForm();
  }
}