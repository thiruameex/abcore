<?php

class MW_Cmspro_Model_Mysql4_News extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the cmspro_id refers to the key field in your database table.
        $this->_init('cmspro/news', 'news_id');
    }
    
    protected function _afterSave(Mage_Core_Model_Abstract $object){
		$condition = $this->_getWriteAdapter()->quoteInto('news_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('news_category'), $condition);

		if (!$object->getData('news_category'))
		{
			$storeArray = array();
            $storeArray['news_id'] = $object->getId();
            $storeArray['category_id'] = '0';
            $this->_getWriteAdapter()->insert($this->getTable('news_category'), $storeArray);
		}
		else
		{
			foreach ((array)$object->getData('news_category') as $store) {
				$storeArray = array();
				$storeArray['news_id'] = $object->getId();
				$storeArray['category_id'] = $store;
				$this->_getWriteAdapter()->insert($this->getTable('news_category'), $storeArray);
			}
		}

        return parent::_afterSave($object);
    }
    
    protected function _beforeDelete(Mage_Core_Model_Abstract $object){
		
		// Cleanup stats on blog delete
		$adapter = $this->_getReadAdapter();
		// 1. Delete testimonial/store
		$adapter->delete($this->getTable('cmspro/news_category'), 'news_id='.$object->getId());

	}
	
}