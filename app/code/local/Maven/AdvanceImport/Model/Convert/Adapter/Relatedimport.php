<?php
class Maven_AdvanceImport_Model_Convert_Adapter_Relatedimport
    extends Maven_AdvanceImport_Model_Convert_Adapter_Abstract
	{
		public function import($importData,$product)
		{ 
			if(isset($importData['related']) && trim($importData['related'])!='') 
			{
				$rel = $this->userCSVDataAsArray($importData['related']);
				$pos = $this->userCSVDataAsArray($importData['related_position']);
				$linkIds = $this->getLinkArray($rel, $pos, $product);
				if(count($linkIds)>0) {
					$product->setRelatedLinkData($linkIds);
				}
			}        
		}
		protected function getLinkArray($rel, $pos, $product)
		{
			$ar = array();
			$i=0;
			foreach($rel as $oneSku) {
				
				if($id = (int)$product->getIdBySku($oneSku)) {
					$p = (isset($pos[$i]))?$pos[$i]:'';
					$i++;
					$ar[$id] = array('position'=>$p);
				}
			}
			return $ar;
		}		
	}
?>	