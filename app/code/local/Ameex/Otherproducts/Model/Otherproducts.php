<?php

/**
* 
*/
class Ameex_Otherproducts_Model_Otherproducts extends Mage_core_Model_Abstract
{
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('otherproducts/otherproducts');
	}

	public function saveintable()
	{
		/* Get the collection from sales order */
		$orders = Mage::getModel('sales/order')->getCollection()
			 	  ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
				  ->addAttributeToFilter('total_item_count', array('gt' => 1));
		foreach($orders as $order){
			$orderid= $order->getIncrementId();
			$orderdate= $order->getCreatedAt();
			$orderstore= $order->getStoreId();
		    $items = $order->getAllVisibleItems();
		    foreach($items as $item){
		    	$_productId = Mage::getModel('catalog/product')->getIdBySku($item->getSku());
		        $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
                  ->getParentIdsByChild($_productId);
				$product = Mage::getModel('catalog/product')->load($parentIds[0]);
				/*if($product->getTypeId() =='configurable'){
					$skus[] = $product->getSku();
				}else{
					$skus[] = $item->getSku();
				}*/
		        $skus[] = $item->getSku();        
		    }
		    $skuscomma=implode(',', $skus);
		    $existingorders = $this->getCollection();
			foreach ($existingorders as $existingorder) {
		    	$existingorderids[] = $existingorder->getOrderId();
		    }  
		    if(!in_array($orderid,$existingorderids)){
				$data['store_id']=$orderstore;
			    $data['order_id']=$orderid;
			    $data['created_date']=$orderdate;
			    $data['skus']=$skuscomma;
			    $otherProducts = $this;
				$otherProducts->setData($data);
				$otherProducts->save();
			}
		}
		return true;
	}

	public function retrieveskus($sku){
		$otherskus = $this->getCollection()
					->addFieldToFilter('skus', array(
                     array('finset' => $sku),
                ));
		return $otherskus;
	}

	public function currentProduct()
	{
		return Mage::registry('product');
	}

	public function requiredskus($requiredskus)
	{
		$collectedskus =$this->retrieveskus($requiredskus);
		return $collectedskus;
	}

	public function finalcollection()
	{
        $collectedskus =$this->requiredskus($this->currentProduct()->getSku());
		$skus_filter = array();
		foreach ($collectedskus as $collectedsku) {
			$finalskus=$collectedsku->getSkus();
			$finalsku=explode(',', $finalskus);
			foreach ($finalsku as $final) {
				if($this->currentProduct()->getSku() != $final) {
					array_push($skus_filter, $final);
				}
			}
		}
		$resultingskus=array_unique($skus_filter);
		return $resultingskus;
	}

	public function outputcollection()
	{
		$_skus = $this->finalcollection();
		$collection=Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*')
		/*->addAttributeToFilter('visibility', array('in' => array(
	      Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
	      Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)))*/
		->addAttributeToFilter( 'sku', array( 'in' => $_skus ) );
		return $collection;
	}
}