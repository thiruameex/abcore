<?php
class MW_EmailOrderForForeign_Block_Order_Request_Success extends Mage_Core_Block_Template{
    public function getEmailAddress(){
        return Mage::getStoreConfig('emailorderforforeign/general/email_for_order');
    }
    
    public function getDistributorUrl(){
        return Mage::getSingleton('emailorderforforeign/distributor')->getDistributorUrl();
    }
}
?>
