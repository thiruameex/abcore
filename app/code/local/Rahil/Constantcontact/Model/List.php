<?php
class Rahil_Constantcontact_Model_List extends  Mage_Core_Model_Abstract {

	public function _construct()
	{
		parent::_construct();
		$this->_init('constantcontact/list');
	}

	 public function toOptionArray()
     {
	  
		$path =  Mage::getBaseDir().'/app/code/local/Rahil/Constantcontact/api';
		require_once($path."/ctctWrapper.php"); 
		$listObj = new ListsCollection();
		$lists = $listObj->getLists();
		
		foreach($lists[0] as $li){
			if($li->getOptInDefault()){
				$label =  $li->getName();
				$value =  $li->getId();
				//$data['label'][] =  (array)$label[0]; 
				//$data['value'][] =  (array)$value[0]; 				
				$data[] = array('label'=>(array)$label[0],'value'=>(array)$value[0]);
				
			}
		}
			
		if(!empty($data)){
			foreach($data as $d){
				$array[] = array('label'=>$d['label'][0],'value'=>$d['value'][0]);
			}
			

		
			return $array;
		
		}	
	   
     }
}
