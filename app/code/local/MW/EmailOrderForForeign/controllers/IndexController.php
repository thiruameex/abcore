<?php

class MW_EmailOrderForForeign_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbBlock->addCrumb('Name', array(
                                'label' => 'Home',
                                'title' => 'Home',
                                'link' => Mage::getUrl()));
        $breadcrumbBlock->addCrumb('InternationalOrder', array(
                                'label' => 'International Order Request Form',
                                'title' => 'International Order Request Form'));
        $this->renderLayout();
    }

    public function submitAction() {
        $this->loadLayout();
        if ($params = $this->getRequest()->getParams()) {
            $storeId = Mage::app()->getStore()->getId();
			$hasProduct = false;
            try {
                $to = Mage::getStoreConfig('emailorderforforeign/general/email_for_order');
                $sender = array('email' => $params['email'], 'name' => $params['customer_name']);

                $data['subject'] = Mage::getStoreConfig('emailorderforforeign/general/subject');
                
                $data['datetime'] = Mage::getSingleton('core/date')->date('Y-m-d h:m:i');
                
                $data['customer_name'] = $params['customer_name'];
                $data['company'] = $params['company'];
                $data['country'] = $params['country'];
                $data['customer_email'] = $params['email'];
                $data['phone'] = $params['phone'];
                if (isset($params['content'])) {
                    $data['comment'] = $params['content'];
                }
                $data['order'] = '';
                foreach ($params['order'] as $orderItem) {
                    if ($orderItem['sku'] && $orderItem['qty']) {
						$hasProduct = true;
                        if ($post = strpos($orderItem['sku'], '@OptSku:')) {
                            $productId = substr($orderItem['sku'], 0, $post);
                            $optionSku = substr($orderItem['sku'], $post + 8);
                        } else {
                            $productId = $orderItem['sku'];
                            $optionSku = null;
                        }
                        $product = Mage::getSingleton('catalog/product');
                        if ($productId) {
                            $product->load($productId);
                            $data['order'] .= '<p> Product Name: ' . $product->getName() . '<br/>';
                        }
                        $price = null;
                        if (!is_null($optionSku)) {
                            $data['order'] .= 'Sku: '.$optionSku . '<br/>';
//                            foreach ($product->getOptions() as $option) {
//                                foreach ($option->getValues() as $value) {
//                                    if ($value->getSku() == 'sku-' . $optionSku) {
//                                        if ($value->getPriceType() == 'fixed') {
//                                            $price .= 'Price:' . ($value->getPrice() + $product->getPrice());
//                                        } else {
//                                            $price .= 'Price:' . (($value->getPrice() + 1) * $product->getPrice());
//                                        }
//                                        break 2;
//                                    }
//                                }
//                            }
                        } else {
                            $data['order'] .= 'Sku:' . $product->getSku() . '<br/>';
                        }
                        $data['order'] .= 'Qty: ' . $orderItem['qty'] . '</p>';
//                        if (is_null($price)) {
//                            $price .= 'Price:' . $product->getPrice();
//                        }
//                        $data['order'] .= $price . '';
                    }
                }
//				if(!$hasProduct){
//					Mage::getSingleton('core/session')->addError($this->__('Please select a product'));
//					$this->_redirect('*/*/index');
//					return;
//				}
				
                $translate = Mage::getModel('core/translate');
                $translate->setTranslateInline(false);
                $templateId = Mage::getStoreConfig('emailorderforforeign/general/email_template');
                $emailTemplate = Mage::getModel('core/email_template');
				if(Mage::getStoreConfig('emailorderforforeign/general/bcc')){
                    $emailTemplate->addBcc(Mage::getStoreConfig('emailorderforforeign/general/bcc'));
                }
                $emailTemplate->sendTransactional(
                        $templateId, $sender, $to, null, // name sender, not describle
                        $data, $storeId
                );
                $translate->setTranslateInline(true);
                $translate = Mage::getModel('core/translate');
                $translate->setTranslateInline(false);
                $to = $params['email'];
                $sender = array('email' => Mage::getStoreConfig('emailorderforforeign/general/email_for_order'), 'name' => Mage::getStoreConfig('emailorderforforeign/general/email_for_order'));
                $data['subject'] = Mage::getStoreConfig('emailorderforforeign/general/notification_subject');
                $templateId = Mage::getStoreConfig('emailorderforforeign/general/notify_customer_template');
                $emailTemplate->sendTransactional(
                        $templateId, $sender, $to, null, // name sender, not describle
                        $data, $storeId
                );
                $translate->setTranslateInline(true);
                $this->_redirect('*/*/success');
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($this->__('Email can\'t send. Sorry about this inconvenience'));
                $this->_redirect('*/*/index');
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Please fill all necessary fileds'));
            $this->_redirect('*/*/index');
        }
    }

    public function getProductInCategoryAction() {
        
        if ($this->getRequest()->getParam('id')) {
            $categoryId = $this->getRequest()->getParam('id');
            $products = Mage::getModel('catalog/category')->load($categoryId)->getProductCollection()
                    ->addAttributeToSelect('sku')
                    ->addAttributeToSelect('name')
                    ->addAttributeToFilter('type_id', array('like' => 'simple'))
                    ->addAttributeToFilter('status', array('eq' => 1));
            $productList = array();
            foreach ($products as $product) {
                if ($product->getHasOptions()) {
                    $product = Mage::getModel('catalog/product')->load($product->getId());
                    foreach ($product->getOptions() as $options) {

                        if ($options->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX
                                || $options->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO) {
                            foreach ($options->getValues() as $value) {
                                $productList[$product->getId() . '@OptSku:' . $value->getSku()] = $product->getName() . '-' . $value->getTitle() . ':' . $value->getSku();
                            }
                        }
                    }
                } else {
                    $productList[$product->getId()] = $product->getName() . ' - ' . $product->getSku();
                }
            }
            $optionHtml = "<option value=\"0\">---Select product---</option>";
            foreach($productList as $categoryId => $categoryName){
                $optionHtml .= '<option value="'.$categoryId.'">'.$categoryName.'</option>';
            }
        } else {
            $optionHtml = "<option value=\"0\">---Select product---</option>";
        }
        $this->getResponse()->setBody($optionHtml);
    }

    public function successAction() {
        $this->loadLayout();
        $breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbBlock->addCrumb('Name', array(
                                'label' => 'Home',
                                'title' => 'Home',
                                'link' => Mage::getUrl()));
        $breadcrumbBlock->addCrumb('InternationalOrder', array(
                                'label' => 'International Order Request Form',
                                'title' => 'International Order Request Form'));
        $this->renderLayout();
    }

    public function reinstallAction() {

//        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
//            echo 'delete from `'.Mage::getSingleton('core/resource')->getTableName('core/resource').'` where `code` like \'emailorderforforeign_setup\'';die;
//        $result = $db->query('delete from `' . Mage::getSingleton('core/resource')->getTableName('core/resource') . '` where `code` like \'emailorderforforeign_setup\'');
//        echo
//        var_dump($result->fetch(PDO::FETCH_ASSOC));
    }

}