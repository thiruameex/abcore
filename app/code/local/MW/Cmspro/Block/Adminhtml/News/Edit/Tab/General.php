<?php

class MW_Cmspro_Block_Adminhtml_News_Edit_Tab_General 
	extends Mage_Adminhtml_Block_Widget_Form 
		implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
  protected function _prepareForm()
  {
  	  //var_dump(Mage::registry('cmspronews_data')); exit;
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('general_form', array('legend'=>Mage::helper('cmspro')->__('General information')));
      
     $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('cmspro')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

	  $fieldset->addField('identifier', 'text', array( 
          'label'     => Mage::helper('cmspro')->__('URL Key'),
          'name'      => 'identifier',
      ));
     $categories = array();
     $categories = Mage::getModel('cmspro/category')->getTreeCategory();
	  
	  $fieldset->addField('category_id', 'multiselect', array(
          'label'     => Mage::helper('cmspro')->__('Categories'),
          'required'  => true,
	  	  'values'    => $categories,
	  	  'style'	  => 'height:150px',
          'name'      => 'news_category[]',
      )); 
      
	 /* if (!Mage::app()->isSingleStoreMode()) {
	            $fieldset->addField('store_id', 'multiselect', array(
	                'name'      => 'news_stores[]',
	                'label'     => Mage::helper('cms')->__('Store View'),
	                'title'     => Mage::helper('cms')->__('Store View'),
	                'required'  => true,
	    	        'style'	    => 'height:120px',
	                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
	            ));
	   }*/
	 	$fieldset->addField('images', 'image', array(
	          'label'     => Mage::helper('cmspro')->__('Images'),
	          'required'  => false,
	          'name'      => 'images',
	  	));
	  	
		$fieldset->addField('feature', 'select', array( 
          'label'     => Mage::helper('cmspro')->__('Featured News'),
          'name'      => 'feature',
	      'values'    => array(
		              array(
		                  'value'     => 1,
		                  'label'     => Mage::helper('cmspro')->__('Yes'),
		              ),
		
		              array(
		                  'value'     => 0,
		                  'label'     => Mage::helper('cmspro')->__('No'),
		              ),
		          ),
        ));
        $fieldset->addField('allowcomment', 'select', array(
          'label'     => Mage::helper('cmspro')->__('Enable Comments'),
          'name'      => 'allowcomment',
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
     
	    $fieldset->addField('active', 'select', array(
	          'label'     => Mage::helper('cmspro')->__('Is Active'),
	          'name'      => 'active',
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
	          
      if ( Mage::getSingleton('adminhtml/session')->getNewsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
          Mage::getSingleton('adminhtml/session')->setNewsData(null);
      } elseif ( Mage::registry('cmspronews_data') ) {
          $form->setValues(Mage::registry('cmspronews_data')->getData());
          $news = Mage::registry('cmspronews_data');

      	  if($news->getNewsId()){
        	$arrStoreId = array();
			$arrCategoryId = array();
				  	
         	$collection_categories =  Mage::getModel('cmspro/news')->getCollection();
			$table_cat = $collection_categories->getTable('news_category'); //$collection_categories->getTable('news_category');

			$collection_categories->getSelect()
						->join(array('table_cat'=>$table_cat), 'table_cat.news_id = main_table.news_id', array('table_cat.category_id'))
						->where('main_table.news_id=?',$news->getNewsId());
						

			if($collection_categories->getData()){
				foreach($collection_categories->getData() as $col){
					if(isset($col['category_id'])) { $arrCategoryId[] = $col['category_id'];}
				}
			}
			//var_dump($arrCategoryId); exit;
			$form->getElement('category_id')->setValue($arrCategoryId);
			
			/*if (!Mage::app()->isSingleStoreMode()) {
				$collection_stores =  Mage::getModel('cmspro/news')->getCollection();
				$table_store = $collection_stores->getTable('news_store');
				//$table_store = 'cmspro_news_store';
				$collection_stores->getSelect()->join(array('table_store'=>$table_store), 'table_store.news_id = main_table.news_id', array('table_store.store_id'));
				if($collection_stores->getData()){
					foreach($collection_stores->getData() as $col){
						if(isset($col['store_id'])) { $arrStoreId[] = $col['store_id'];}
					}
				}
				$form->getElement('store_id')->setValue($arrStoreId);
			}*/
		 }
      }
      return parent::_prepareForm();
  }
    public function getTabLabel(){
    	return Mage::helper('cmspro')->__('General');
    }
    public function getTabTitle(){
    	return Mage::helper('cmspro')->__('General');
    }
    public function canShowTab(){
    	return true;
    }
    public function isHidden(){
    	return false;
    }
}