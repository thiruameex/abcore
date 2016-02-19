<?php
class MW_EmailOrderForForeign_Block_Order_Request_Form extends Mage_Core_Block_Template{
    private $productList = null;
    
    public function getDescription(){
        return Mage::getStoreConfig('emailorderforforeign/general/order_description');
    }
    
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    public function getOptionsHtml(){
        if(is_null($this->productList)){
            
            $productList = $this->prepareOption();
 //           $this->productList = "<option value=\"0\">---Select Category---</option>";
//            foreach($productList as $categoryId => $categoryName){
 //               $this->productList .= '<option value="'.$categoryId.'">'.$categoryName.'</option>';
 //           }
			 $this->productList = "<option value=\"0\">-----Select Product-----</option>";
            foreach($productList as $categoryId => $categoryName){
                $this->productList .= '<option value="'.$categoryId.'">'.$categoryName.'</option>';
            }
        }
        return $this->productList;
    }
    
    public function prepareOption(){
 //       $option = array();
 //       $collection = Mage::helper('catalog/category')->getStoreCategories();
 //       foreach($collection as $item){
 //           $childs = $item->getAllChildNodes();
  //          foreach($childs as $child){
  //              if(!$child->hasChild()){
 //                   $option[$child->getId()] = $child->getName();
 //               }
 //           }
 //       }
 //       return $option;
//        
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
//            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToFilter('type_id', array('like' => 'simple'));
//            ->joinField('qty',
//                'cataloginventory/stock_item',
//                'qty',
//                'product_id=entity_id',
//                '{{table}}.stock_id=1',
//                'left');

 //       if ($store->getId()) {
            //$collection->setStoreId($store->getId());
//            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
//            $collection->addStoreFilter($store);
//            $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore);
//            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
//            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
////            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
////            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
//        }
//        else {
////           $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
////            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
//        }
        $collection->addAttributeToFilter('status', array('eq' => 1));
        $productList = array();
		//echo $collection->count();
		//die();
        foreach($collection as $product){
            
            if($product->getHasOptions()){
                $product = Mage::getModel('catalog/product')->load($product->getId());
                foreach($product->getOptions() as $options){
                    
                    if($options->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX 
                            || $options->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO ){
                       foreach($options->getValues() as $value){
                            $productList[$product->getId().'@OptSku:'.$value->getSku()] = $product->getName().'-'.$value->getTitle().':'.$value->getSku();
                        }
                    }
                }
            }else{
                $productList[$product->getId()] = $product->getName().' - '.$product->getSku();
            }
            
        }
        return $productList;
    }
    
    public function _toHtml() {
        if(Mage::getStoreConfig('emailorderforforeign/general/enabled')){
            return parent::_toHtml();
        }
    }
}
?>
