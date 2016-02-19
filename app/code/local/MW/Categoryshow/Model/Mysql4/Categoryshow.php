<?php

class MW_Categoryshow_Model_Mysql4_Categoryshow extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the categoryshow_id refers to the key field in your database table.
        $this->_init('categoryshow/categoryshow', 'categoryshow_id');
    }
}