<?php
class MW_Ipchecker_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/ipchecker?id=15 
    	 *  or
    	 * http://site.com/ipchecker/id/15 	
    	 */
    	/* 
		$ipchecker_id = $this->getRequest()->getParam('id');

  		if($ipchecker_id != null && $ipchecker_id != '')	{
			$ipchecker = Mage::getModel('ipchecker/ipchecker')->load($ipchecker_id)->getData();
		} else {
			$ipchecker = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($ipchecker == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$ipcheckerTable = $resource->getTableName('ipchecker');
			
			$select = $read->select()
			   ->from($ipcheckerTable,array('ipchecker_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$ipchecker = $read->fetchRow($select);
		}
		Mage::register('ipchecker', $ipchecker);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}