<?php

class MW_EmailOrderForForeign_Block_Adminhtml_Distributor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'ditribtor_id';
        $this->_blockGroup = 'emailorderforforeign';		// module name
        $this->_controller = 'adminhtml_distributor';
        
        $this->_updateButton('save', 'label', Mage::helper('emailorderforforeign')->__('Save Mapping'));
        $this->_updateButton('delete', 'label', Mage::helper('emailorderforforeign')->__('Delete Mapping'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('department_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'department_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'department_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('distributor_data') && Mage::registry('distributor_data')->getDistribtorId() ) {
            return Mage::helper('emailorderforforeign')->__("Edit Mapping Id '%s'", $this->htmlEscape(Mage::registry('department_data')->getDistribtorId()));
        } else {
            return Mage::helper('emailorderforforeign')->__('Add Mapping');
        }
    }
}
