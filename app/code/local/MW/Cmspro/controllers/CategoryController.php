<?php

class MW_Cmspro_CategoryController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->_redirect('/');
    }
    
    public function viewAction(){
    	$uri = explode('/cmspro/',$_SERVER['REQUEST_URI']);
		if(sizeof($uri)>1){
			$this->_forward('defaultNoRoute');
		}else{
	    	$this->loadLayout();
	    	$category = Mage::getModel('cmspro/category')->load($this->getRequest()->get('id'), 'category_id');
	    	if ($this->getLayout()->getBlock('head')) {
	    		$head = $this->getLayout()->getBlock('head');
	            $head->setTitle($category->getPageTitle());
	            $head->setDescription($category->getMetaDescription());
	            $head->setKeywords($category->getMetaKeyword());
	        }
	        $this->renderLayout();
		}
    }
}