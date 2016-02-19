<?php

class MW_Cmspro_Model_Relation extends Mage_Core_Model_Abstract
{
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('cmspro/relation');
    }
	
	
	public function getRelatedProductCollection(){
		$collection = Mage::getModel('cmspro/relation')
				->getCollection();
		return $collection;
	}
	
	public function setRelatedProducts($newId, $arr){
	
		$model = Mage::getResourceModel('cmspro/relation');
		
		$data = array();	
		
		// foreach($arr as $key => $val){
			// $data['entity_id'][] = $key;
			// $data['position'][] = $val['position'];
		// }
		
		if($newId  == 0){
			$newId = 1;
		}
		 
		$productIds = array_keys($arr);
		// echo $newId;
		// echo "<pre>";
		 // print_r($productIds);
		 // die;
		$model->processRelations($newId, $productIds);
		//$model->setData($data)->setId($newId);
		//$model->save();
	}
	
}