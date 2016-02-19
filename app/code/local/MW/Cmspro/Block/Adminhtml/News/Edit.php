<?php

class MW_Cmspro_Block_Adminhtml_News_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {	
	 	parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'cmspro';
        $this->_controller = 'adminhtml_news';
        
        $this->_updateButton('save', 'label', Mage::helper('cmspro')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('cmspro')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
			function toggleEditor() {
                if (tinyMCE.getInstanceById('news_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'news_content');
                } 
                else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'news_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('cmspronews_data') && Mage::registry('cmspronews_data')->getNewsId() ) {
            return Mage::helper('cmspro')->__("Edit News '%s'", $this->htmlEscape(Mage::registry('cmspronews_data')->getFullname()));
        } else {
            return Mage::helper('cmspro')->__('Add News');
        }
    }
    
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cmspro/adminhtml/news/' . $action);
    }
}