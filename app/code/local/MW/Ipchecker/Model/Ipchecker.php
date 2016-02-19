<?php

class MW_Ipchecker_Model_Ipchecker extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ipchecker/ipchecker');
    }
}