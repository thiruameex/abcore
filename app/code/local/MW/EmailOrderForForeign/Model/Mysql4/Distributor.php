<?php

class MW_EmailOrderForForeign_Model_Mysql4_Distributor extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the helpdesk_id refers to the key field in your database table.
        $this->_init('emailorderforforeign/distributor', 'distributor_id');
    }
}
?>
