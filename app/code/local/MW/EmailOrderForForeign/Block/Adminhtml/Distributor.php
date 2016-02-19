<?php

class MW_EmailOrderForForeign_Block_Adminhtml_Distributor extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_distributor';
        $this->_blockGroup = 'emailorderforforeign'; // module name
        $this->_headerText = Mage::helper('emailorderforforeign')->__('Mapping Country To Distributor');
        $this->_addButtonLabel = Mage::helper('emailorderforforeign')->__('Add Mapping');
        parent::__construct();
    }

}

?>
