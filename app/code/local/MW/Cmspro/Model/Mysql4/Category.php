<?php

class MW_Cmspro_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the cmspro_id refers to the key field in your database table.
        $this->_init('cmspro/category', 'category_id');
    }
    
    protected function _afterSave(Mage_Core_Model_Abstract $object){
		$condition = $this->_getWriteAdapter()->quoteInto('category_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('category_store'), $condition);

		if (!$object->getData('category_stores'))
		{
			$storeArray = array();
            $storeArray['category_id'] = $object->getId();
            $storeArray['store_id'] = '0';
            $this->_getWriteAdapter()->insert($this->getTable('category_store'), $storeArray);
		}
		else
		{
			foreach ((array)$object->getData('category_stores') as $store) {
				$storeArray = array();
				$storeArray['category_id'] = $object->getId();
				$storeArray['store_id'] = $store;
				$this->_getWriteAdapter()->insert($this->getTable('category_store'), $storeArray);
			}
		}

        return parent::_afterSave($object);
    }
    
    protected function _beforeDelete(Mage_Core_Model_Abstract $object){
		
		// Cleanup stats on blog delete
		$adapter = $this->_getReadAdapter();
		// 1. Delete testimonial/store
		$adapter->delete($this->getTable('cmspro/category_store'), 'category_id='.$object->getId());

	}
}