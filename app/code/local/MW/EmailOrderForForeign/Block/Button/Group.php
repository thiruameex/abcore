<?php
class MW_EmailOrderForForeign_Block_Button_Group extends Mage_Core_Block_Template{
    public function _toHtml() {
        if(Mage::getSingleton('core/session')->getForeigncustomer() && Mage::getStoreConfig('emailorderforforeign/general/enabled')){
            return parent::_toHtml();
        }
    }
    
    public function getDistributorUrl(){
        $block = $this->getLayout()->createBlock('ipchecker/ipchecker');       
        $geoIp = $block->getGeoip();
        
        return Mage::getSingleton('emailorderforforeign/distributor')->getDistributorUrl($geoIp[1]);
    }
    
//    public function getOrderFormUrl(){
//        $storeId = Mage::app()->getStore()->getStoreId();
//        $urls = Mage::getModel('core/url_rewrite')->getCollection()
//                ->addFieldToFilter('id_path', array('like' => 'email_order'))
//                ->addFieldToFilter('store_id', array('eq' => $storeId));
//        if($urls->count() > 0){
//            
//        }
//    }
}
?>
