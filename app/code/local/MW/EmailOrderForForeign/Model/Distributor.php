<?php
class MW_EmailOrderForForeign_Model_Distributor extends Mage_Core_Model_Abstract{
    
    private $distributorOptionArray = null;
    private $countryOptionArray = null;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('emailorderforforeign/distributor');
    }
    
    public function getDistributorLinkOptionArray(){
        if(is_null($this->distributorOptionArray)){
            $cmsCollection = Mage::getSingleton('cms/page')->getCollection()
                ->addFieldToFilter('is_active', array('eq' => Mage_Cms_Model_Page::STATUS_ENABLED));
            $this->distributorOptionArray = array();
            foreach($cmsCollection as $cms){
                $this->distributorOptionArray[$cms->getId()] = $cms->getTitle();
            }
        }
        return $this->distributorOptionArray;
    }
    
    public function getCountryOptionArray(){
        if(is_null($this->countryOptionArray)){
            $countries = Mage::getSingleton('adminhtml/system_config_source_country')->toOptionArray();
            foreach($countries as $country){
                if($country['value']){
                    $this->countryOptionArray[$country['value']] = $country['label'];
                }
            }
        }
        return $this->countryOptionArray;
    }
    
    public function getDistributorUrl($countryCode){
        $countryCode = strtoupper($countryCode);
        $collection = $this->getCollection()
                ->addFieldToFilter('country', array('like' => $countryCode));
        if($collection->count() > 0){
            $distributor = $collection->getFirstItem();
            $cms = Mage::getSingleton('cms/page')->load($distributor->getDistributorLink());
            return Mage::getBaseUrl().$cms->getIdentifier();
        }else{
            return Mage::getBaseUrl().Mage::getStoreConfig('emailorderforforeign/distributor/default_link');
        }
    }
}
?>
