<?php

class MW_Cmspro_Block_Adminhtml_News_Edit_Tab_Content 
	extends Mage_Adminhtml_Block_Widget_Form  
		implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
   /**
     * Load Wysiwyg on demand and Prepare layout
     */
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $version = Mage::getVersion();
        if(version_compare($version, '1.4.0.1', '>=')===true){
	        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
	            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
	        }
        }
    }
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $form->setHtmlIdPrefix('news_');
      $this->setForm($form);
      $fieldset = $form->addFieldset('content_fieldset', array('legend'=>Mage::helper('cmspro')->__('Content'),'class'=>'fieldset-wide'));
	  $wysiwygConfig = "";
	  //echo Mage::getVersion();exit;
	  $version = Mage::getVersion();
	  if(version_compare($version, '1.4.0.1', '>=')===true){
	      $wysiwygConfig = Mage::getSingleton('cmspro/wysiwyg_config')->getConfig(
	            array('tab_id' => $this->getTabId())
	        );
	  }
      //$wysiwygConfig-> 
      $fieldset->addField('summary', 'editor', array(
          'label'     => Mage::helper('cmspro')->__('Summary'),
          'class'     => 'required-entry',
     	  'style'     => 'width:640px; height:50px;',
          'required'  => true,
          'name'      => 'summary',
      ));
     $contentField = $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'style'     => 'height:36em;',
            'required'  => true,
            'config'    => $wysiwygConfig
        ));
       // Setting custom renderer for content field to remove label column
       $renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset_element')
                    ->setTemplate('cmspro/news/edit/form/renderer/content.phtml');
       $contentField->setRenderer($renderer);
      if ( Mage::getSingleton('adminhtml/session')->getNewsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
          Mage::getSingleton('adminhtml/session')->setNewsData(null);
      } elseif ( Mage::registry('cmspronews_data') ) {
          $form->setValues(Mage::registry('cmspronews_data')->getData());
      }
      Mage::dispatchEvent('adminhtml_news_edit_tab_content_prepare_form', array('form' => $form));
      return parent::_prepareForm();
  }
  
    public function getTabLabel()
    {
        return Mage::helper('cmspro')->__('Content');
    }

    public function getTabTitle()
    {
        return Mage::helper('cmspro')->__('Content');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
    
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cmspro/adminhtml/news/' . $action);
    }
}