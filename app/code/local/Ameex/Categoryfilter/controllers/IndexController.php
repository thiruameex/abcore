<?php
class Ameex_Categoryfilter_IndexController extends Mage_Core_Controller_Front_Action
{
	public function loadcategoryproductAction() 
	{
        $id = $this->getRequest()->getPost('id');
	   	$dir = $this->getRequest()->getPost('dir');
	    $sortby = $this->getRequest()->getPost('sortby');
	    $categoryUrl = $this->getRequest()->getPost('categoryUrl');
	    
	    if ($id) {
	    	$_category = Mage::getModel('catalog/category')->load($id);
	        $product = Mage::getModel('catalog/product');

	        //load the category's products as a collection
	        $_productCollection = $product->getCollection()
	                ->addAttributeToSelect('*')
	                ->addAttributeToSort($sortby,$dir)
	                ->addCategoryFilter($_category)
	                ->load();
            $json_products = array();
	        foreach ($_productCollection as $_product) 
	        {
	            $_product->getData();
	            $json_products[] = array(
	                'name' => $_product->getName(),
	                'category' => $_product->getCat(),
	                'type' => $_product->getType(),
	                'view' => $_product->getProductUrl(),
	                'categoryUrl' => $categoryUrl);
	        }
	        $data = json_encode($json_products);
	        echo $data;
    	}
	}
}