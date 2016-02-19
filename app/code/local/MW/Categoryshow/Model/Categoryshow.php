<?php

class MW_Categoryshow_Model_Categoryshow extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('categoryshow/categoryshow');
    }
}