<?php

class MW_Breadcrumbs_Block_Breadcrumbs extends Mage_Cms_Block_Page
{
    /**
     * Array of breadcrumbs
     *
     * array(
     *  [$index] => array(
     *                  ['label']
     *                  ['title']
     *                  ['link']
     *                  ['first']
     *                  ['last']
     *              )
     * )
     *
     * @var array
     */
	protected function _prepareLayout()
	{
    	$page = $this->getPage();
        $area = $this->getPage()->getData('area');
		$moduleController = $this->getRequest()->getControllerModule();
        // show breadcrumbs
        if($moduleController == 'Mage_Cms')
        {
	        if (Mage::getStoreConfig('web/default/show_cms_breadcrumbs')
	            && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))
	            && ($page->getIdentifier()!==Mage::getStoreConfig('web/default/cms_home_page'))
	            && ($page->getIdentifier()!==Mage::getStoreConfig('web/default/cms_no_route'))) {
	                $breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Go to Home Page'), 'link'=>Mage::getBaseUrl() ));
	                if(($area))
	                {
	                	$breadcrumbs->addCrumb('distributor', array('label'=>Mage::helper('cms')->__('distributors'), 'title'=>Mage::helper('cms')->__('Go to Distributor Page'),'link'=>Mage::getModel('cms/page')->load(8)->getData('identifier') ));
	                }
	                //$breadcrumbs->addCrumb('cms_page', array('label'=>$page->getTitle(), 'title'=>$page->getTitle()));
	        }
        }
    }
    
}
