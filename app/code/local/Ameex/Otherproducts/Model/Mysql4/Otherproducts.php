<?php
/**
* 
*/
class Ameex_Otherproducts_Model_Mysql4_Otherproducts extends Mage_Core_Model_Mysql4_Abstract
{
	
	function _construct()
	{
		$this->_init('otherproducts/otherproducts','id');
	}
}