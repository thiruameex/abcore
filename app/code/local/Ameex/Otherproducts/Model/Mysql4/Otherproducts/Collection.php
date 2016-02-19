<?php 
/**
* 
*/
class Ameex_Otherproducts_Model_Mysql4_Otherproducts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('otherproducts/otherproducts');
	}
}