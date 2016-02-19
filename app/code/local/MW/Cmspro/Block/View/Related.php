<?php
class MW_Cmspro_Block_View_Related extends Mage_Catalog_Block_Product_Abstract
{
    protected $_itemCollection;

	public function getStore(){
		// get current store
        return Mage::app()->getStore()->getId();
	}
	
    protected function _prepareData()
    {
				
		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->addStoreFilter($this->getStore());
			
		$collection->getSelect()
			 ->join(array('relation'=>'cmspro_news_product'), 'relation.entity_id = e.entity_id')
			 ->where('relation.new_id='.$this->getRequest()->getParam('id') )	;		 
			
			$collection	->setOrder('created_at','desc')->setPageSize(Mage::getStoreConfig('mw_cmspro/product/number_related_product') ? Mage::getStoreConfig('mw_cmspro/product/number_related_product'):5);

		$collection
			->addAttributeToSelect('*')
			->load()
			;		
			
        /* @var $product Mage_Catalog_Model_Product */

        $this->_itemCollection = $collection;
		
         Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($collection,
             Mage::getSingleton('checkout/session')->getQuoteId()
         );
      //  $this->_addProductAttributesAndPrices($collection);

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_itemCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        $collection->load();

        foreach ($collection as $product) {
       //     $product->setDoNotUseCategoryId(true);
        }

        return $this;
    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    public function getItems()
    {
        return $this->_itemCollection;
    }
}
