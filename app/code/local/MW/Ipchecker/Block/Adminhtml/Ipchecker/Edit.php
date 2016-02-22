<?php

class MW_Ipchecker_Block_Adminhtml_Ipchecker_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'ipchecker';
        $this->_controller = 'adminhtml_ipchecker';
        
        $this->_updateButton('save', 'label', Mage::helper('ipchecker')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('ipchecker')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('ipchecker_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'ipchecker_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'ipchecker_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('ipchecker_data') && Mage::registry('ipchecker_data')->getId() ) {
            return Mage::helper('ipchecker')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('ipchecker_data')->getTitle()));
        } else {
            return Mage::helper('ipchecker')->__('Add Item');
        }
    }
}