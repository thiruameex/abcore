<?php

/**
* 
*/
class Ameex_Otherproducts_Block_Otherproducts extends Mage_Core_Block_Template
{

	public function _productcollection()
	{
        
		return Mage::getModel('otherproducts/otherproducts')->outputcollection();
		
	}
}
