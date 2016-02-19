<?php  
/**
 * Product price block
 *
 * @category   Mage
 * @package    Mage_Catalog
 */
class MW_Ipchecker_Block_Typegroup extends Mage_Catalog_Block_Product_View_Type_Grouped
{ 
public function _construct()
    {
        parent::_construct();   
            
    }
    /**
     * Prevent displaying if the price is not available
     *
     * @return string
     */
	 public function getTierPrices($product = null)
    {
		 $con = Mage::getSingleton('core/session')->getForeigncustomer();
		if(($con==1)&&(Mage::getStoreConfig('ipchecker/info/enabled')==1))
		return null;
		else 
			return parent::getTierPrices();
		
	}
	 public function getTierPriceHtml($product = null)
    {	
		 $con = Mage::getSingleton('core/session')->getForeigncustomer();
		if(($con==1)&&(Mage::getStoreConfig('ipchecker/info/enabled')==1))
		return null;
		else 
			return parent::getTierPriceHtml();
		
    } 
	 
    
}
