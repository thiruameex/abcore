<?php  
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Product View block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @module     Catalog
 */
class MW_Ipchecker_Block_View extends Mage_Catalog_Block_Product_View
{       /**
     * Retrieve child block HTML
     *
     * @param   string $name
     * @param   boolean $useCache
     * @return  string
     */
	 //overwrite parent function
   public function _construct()
    {
        parent::_construct();   
            
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
