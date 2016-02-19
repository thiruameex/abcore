<?php

class MW_Cmspro_Adminhtml_NewsController extends Mage_Adminhtml_Controller_action
{

	 protected function _outputBlocks()
    {
        $blocks = func_get_args();
        $output = $this->getLayout()->createBlock('adminhtml/text_list');
        foreach ($blocks as $block) {
            $output->insert($block, '', true);
        }
        $this->getResponse()->setBody($output->toHtml());
    }
	
	  /**
     * Product grid for AJAX request
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
		
    }
	
	/**
     * Get specified tab grid
     */
    public function gridOnlyAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cmspro/adminhtml_news_edit_tab_' . $this->getRequest()->getParam('gridOnlyBlock'))
                ->toHtml()
        );
    }
	
	 protected function _initNews()
    {
	 $newId  = (int) $this->getRequest()->getParam('id');
	 
        $news    = Mage::getModel('cmspro/news')
            ->setStoreId($this->getRequest()->getParam('store', 0));
		
		
		$news->setData('_edit_mode', true);
		
		if ($newId) {
            try {
                $news->load($newId);
            } catch (Exception $e) {
               
                Mage::logException($e);
            }
        }
		$attributes = $this->getRequest()->getParam('attributes');
       
	}
	
	/**
     * Create serializer block for a grid
     *
     * @param string $inputName
     * @param Mage_Adminhtml_Block_Widget_Grid $gridBlock
     * @param array $productsArray
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Ajax_Serializer
     */
    protected function _createSerializerBlock($inputName, Mage_Adminhtml_Block_Widget_Grid $gridBlock, $productsArray)
    {
        return $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_ajax_serializer')
            ->setGridBlock($gridBlock)
            ->setProducts($productsArray)
            ->setInputElementName($inputName)
        ;
    }

	
	public function relatedAction()
    {
        $this->_initNews();
        $this->loadLayout();
        $this->getLayout()->getBlock('adminhtml.news.related')
            ->setProductsRelated($this->getRequest()->getPost('products_related', null))
		   ;
		   
        $this->renderLayout();
		
    }
	
	/**
     * Get related products grid
     */
    public function relatedGridAction()
    {
        $this->_initNews();
        $this->loadLayout();
        $this->getLayout()->getBlock('adminhtml.news.related')
            ->setProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }

	protected function _initAction() {
		
		$this->loadLayout()
			->_setActiveMenu('cmspro/news')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('cmspro/news');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
			
            if (!$model->getNewsId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::help('cmspro')->__('This page no longer exists'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('cmspronews_data', $model);

        // 5. Build edit form
        $this->loadLayout()
			->_setActiveMenu('cmspro/news')
            ->_addBreadcrumb($id ? Mage::helper('cmspro')->__('Edit News') : Mage::helper('cmspro')->__('Add News'), $id ? Mage::helper('cmspro')->__('Edit News') : Mage::helper('cmspro')->__('Add News'));

        $this->renderLayout();
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
	$redirectBack   = $this->getRequest()->getParam('back', false);
			$data = $this->getRequest()->getPost();Mage::log($data);
			$newId = $this->getRequest()->getParam('id');
		if ($data) {
			// Upload images new:
			if(isset($_FILES['images']['name']) && $_FILES['images']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('images');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS .'news'.DS ;
					$uploader->save($path, $_FILES['images']['name'] );
				} catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setProductData($data);
                $redirectBack = true;	
				} catch (Exception $e) {
				   Mage::logException($e);
					$this->_getSession()->addError($e->getMessage());
					$redirectBack = true;
		        }	        	        
		        //this way the name is saved in DB
	  			$data['images'] = 'news/'.$_FILES['images']['name'];
			}else{
				//$images = "";
				//if(isset($data['images']['value'])) { $images = $data['images']['value'];}
				if(isset($data['images']['delete']) && $data['images']['delete']==1){ 
					$data['images']=""; 
				}else{
					unset($data['images']);
				}
			}
			
			 if ($redirectBack) {
				$this->_redirect('*/*/edit', array(
					'id'    => $newId,
					'_current'=>true
				));
			}

	  		// Rewrite URL
			$suffix = Mage::getStoreConfig('mw_cmspro/info/category_suffix') ? Mage::getStoreConfig('mw_cmspro/info/category_suffix'):".html"; 
			if($data['identifier']!=""){
				$url_key = $data['identifier'];
			}else{
				$url_key = $data['title'];
			}
			
			$root = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format(Mage::getModel('cmspro/category')->getCurrentRoot()->getName()));
			$root = strtolower($root);
			$url_key = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($url_key));
			$url_key = strtolower($url_key);
			$rq_path = $root."/".$url_key;
			$rq_path1 = trim($rq_path, '-').$suffix;
			
			// Luu vao CSDL
	  		$model = Mage::getModel('cmspro/news');
			$data['status']=$data['active'];
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if (!$newId) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}
				
				// Save data	

		$links = $this->getRequest()->getPost('links');
		
		//var_dump( Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']));die;
		
		Mage::getModel('cmspro/relation')->setRelatedProducts($newId, Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']));				
		
				$model->save();
				
				// Update URL:
				$current_news = Mage::getModel('cmspro/news')->load($model->getId());
				$url_rewrite = array();
		     	$url_rewrite['request_path'] = $rq_path1;
		     	$url_rewrite['target_path'] = 'cmspro/view/details/id/'.$model->getId();
		     	$url_rewrite['id_path'] = $rq_path1;
		     	$url_rewrite['is_system'] = 1;
		     	$url_rewrite['options'] = 0;
				
		     	$url_model1 = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToFilter('request_path',$rq_path1);
		     	// if($url_model1->count()>0){
		     		// $rq_path2 = $rq_path.rand(100,99999).$suffix;
		     		// $url_rewrite['request_path'] = $rq_path2;
		     		// $url_rewrite['id_path'] = $rq_path2;
		     	// }
				$url_model = Mage::getModel('core/url_rewrite');
		     	if($current_news->getUrlRewriteId()=="0") { 
					$url_model->setData($url_rewrite);
					$url_model->save();
					$model->setUrlRewriteId($url_model->getId());
					$model->save();
				}else{
					$url_model->setData($url_rewrite)->setId($current_news->getUrlRewriteId());
					$url_model->save();
				}
											
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cmspro')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cmspro')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('cmspro/news');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function reindexAction(){
    	try{
    		$news = Mage::getModel('cmspro/news');
    		$news->reindexNews();
    		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('All News were reindexed Successful'));
    	}catch(Exception $e){
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occured, please try again'));
    	}
    	$this->_redirect('*/*/index'); 
    }	
	
    public function massDeleteAction() {
        $categoryIds = $this->getRequest()->getParam('news');
        if(!is_array($categoryIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $category = Mage::getModel('cmspro/news')->load($categoryId);
                    $category->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($categoryIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $categoryIds = $this->getRequest()->getParam('news');
        if(!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $category = Mage::getSingleton('cmspro/news')
                        ->load($categoryId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($categoryIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'cmspro.csv';
        $content    = $this->getLayout()->createBlock('cmspro/adminhtml_news_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'cmspro.xml';
        $content    = $this->getLayout()->createBlock('cmspro/adminhtml_news_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}