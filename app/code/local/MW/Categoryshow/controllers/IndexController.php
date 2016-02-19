<?php
class MW_Categoryshow_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/categoryshow?id=15 
    	 *  or
    	 * http://site.com/categoryshow/id/15 	
    	 */
    	/* 
		$categoryshow_id = $this->getRequest()->getParam('id');

  		if($categoryshow_id != null && $categoryshow_id != '')	{
			$categoryshow = Mage::getModel('categoryshow/categoryshow')->load($categoryshow_id)->getData();
		} else {
			$categoryshow = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($categoryshow == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$categoryshowTable = $resource->getTableName('categoryshow');
			
			$select = $read->select()
			   ->from($categoryshowTable,array('categoryshow_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$categoryshow = $read->fetchRow($select);
		}
		Mage::register('categoryshow', $categoryshow);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}