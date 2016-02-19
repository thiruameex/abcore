<?php
class MW_EmailOrderForForeign_Model_Mysql4_Distributor_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('emailorderforforeign/distributor', 'distributor_id');
    }
}
?>
