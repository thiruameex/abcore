<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Checkoutpromo
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */class AW_Checkoutpromo_Block_Checkoutpromo extends Mage_Core_Block_Template {

    protected $_appliedBlockIds = array();
    public $re_product_sku;

    public function getAppliedBlockIds() {

        $customer = Mage::getModel('customer/session')->getCustomer();
        $customerGroupId = Mage::getModel('customer/session')->getCustomerGroupId();
        $quote = Mage::getModel('checkout/session')->getQuote();

        
        try {
            $validator = Mage::getModel('checkoutpromo/validator')
                            ->init($customer->getWebsiteId(), $customerGroupId);
        } catch (Exception $e) {
            Mage::log('Exception: ' . $e . ' in ' . __CLASS__ . ' on ' . __LINE__);
        }
		

        try {
            if (count($quote->getAllItems())) {
                $v = $validator->process($quote);
                $this->_appliedBlockIds = $v->appliedBlockIds;
                $this->re_product_sku = $v->re_product_sku;
            }
        } catch (Exception $e) {
            Mage::log('Exception: ' . $e->getMessage() . ' in ' . __CLASS__ . ' on line ' . $e->getLine());
           
        }
        return $this->_appliedBlockIds;
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getCheckoutpromo() {
        if (!$this->hasData('checkoutpromo')) {
            $this->setData('checkoutpromo', Mage::registry('checkoutpromo'));
        }
        return $this->getData('checkoutpromo');
    }

    protected function _toHtml() {
        return parent::_toHtml();
    }
    
    /* ---------------mw---------------- */
	
	protected function getImageUrl($model_product) {
        if (strcmp($model_product->getData("thumbnail"),"no_selection")!=0){
			$linkimageproduct= $model_product->getThumbnailUrl(141,167);
			return $linkimageproduct;
		}elseif (strcmp($model_product->getData("small_image"),"no_selection")!=0){
			$linkimageproduct= $model_product->getSmallImageUrl(141,167);
			return $linkimageproduct;
		}elseif (strcmp($model_product->getData("image"),"no_selection")!=0){
			$linkimageproduct= $model_product->getImageUrl();
			return $linkimageproduct;
		}
		$linkimageproduct= $model_product->getThumbnailUrl(141,167);
		return $linkimageproduct;
    }
	
	protected function checkSku($productobj,$array_sku) {
        foreach($array_sku as $sku){
				$productid= $productobj->getIdBySku($sku);
				if ($productid){
					return 1;
				}
		}
		return 0;
    }
}