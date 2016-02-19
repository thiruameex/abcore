<?php 

class MW_Cmspro_ViewController extends Mage_Core_Controller_Front_Action
{
   /*public function indexAction(){
		$this->_redirect('/');
    }*/
    
    public function detailsAction()
    {
    	//var_dump($this->getRequest()->getParam('id')); exit;
    	$uri = explode('/cmspro/',$_SERVER['REQUEST_URI']);
		if(sizeof($uri)>1){
			//$this->_forward('defaultNoRoute');
		}else{
	        $this->loadLayout();
	    	$news = Mage::getModel('cmspro/news')->load($this->getRequest()->getParam('id'), 'news_id');
	    	if ($this->getLayout()->getBlock('head')) {
	    		$head = $this->getLayout()->getBlock('head');
	            $head->setTitle($news->getPageTitle());
	            $head->setDescription($news->getMetaDescription());
	            $head->setKeywords($news->getMetaKeyword());
	        }
	        $this->renderLayout(); 
		}
    }
}