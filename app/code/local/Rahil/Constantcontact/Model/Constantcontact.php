<?php 

class Rahil_Constantcontact_Model_Constantcontact extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('constantcontact/constantcontact');
    }
}
