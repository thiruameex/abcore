<?php
class MW_Cmspro_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('category/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

    public function addAction()
    {
        Mage::getSingleton('admin/session')->unsActiveTabId();
        $this->_forward('edit');
    }
    
	public function editAction() {
		
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('cmspro/category')->setData(array());
		
		if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cmspro')->__('This page no longer exists'));
                $this->_redirect('*/*/');
                return;
            }
        }
        
        //$this->_title($id ? $model->getTitle() : $this->__('Add Category'));
		
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}
		
		Mage::register('cmsprocategory_data', $model);

		$this->loadLayout();
		$this->_setActiveMenu('category/items');

        $this->_initAction()
            ->_addBreadcrumb($id ? Mage::helper('cmspro')->__('Edit Category') : Mage::helper('cmspro')->__('Add Category'), $id ? Mage::helper('cmspro')->__('Edit Category') : Mage::helper('cmspro')->__('Add Category'));

		$this->_addContent($this->getLayout()->createBlock('cmspro/adminhtml_category_edit'))
			->_addLeft($this->getLayout()->createBlock('cmspro/adminhtml_category_edit_tabs'));

		$this->renderLayout();
	}
 
	public function newAction() {
		$this->_forward('edit');
	}

    
	public function saveAction() {
		if ($this->getRequest()->getPost()) {
			$data = $this->getRequest()->getPost();
			if(isset($_FILES['images']['name']) && $_FILES['images']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('images');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					/* Set the file upload mode false -> get the file directly in the specified folder true -> get the file in the product like folders ; (file.jpg will go in something like /media/f/i/file.jpg)*/
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS.'categories'.DS ;
					$uploader->save($path, $_FILES['images']['name'] );
					
				} catch (Exception $e) {
		      
		        }
		        //this way the name is saved in DB
	  			$data['images'] = 'categories/'.$_FILES['images']['name'];
				
			}else{
			
				if(isset($data['images']['delete']) && $data['images']['delete']==1){ 
					$data['images']=""; 
				}else{
					unset($data['images']);
				}
			}
			
			// Luu vao CSDL
	  		$model = Mage::getModel('cmspro/category');
			$model->setData($data)->setId($this->getRequest()->getParam('id'));
			
			try {
				if (!$this->getRequest()->getParam('id')) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}
				
				// Save data				
				$model->save();
						     	
				// Update level and root_path for current category and its child
				$collections = Mage::getModel('cmspro/category')->getCollection();
				$current_cat = Mage::getModel('cmspro/category')->load($model->getId());
				$new_level = 1; $root_path1 = "";
				$old_level = $current_cat->getLevel();
				//if parent_id is exist:
		  		if($current_cat->getParentId()!="0"){
		  			$parent = Mage::getModel('cmspro/category')->load($current_cat->getParentId());
		  			$new_level = intval($parent->getLevel()) + 1;
					$root_path1 = $parent->getRootPath();
		  		}

				$root_path = $root_path1.$model->getId()."/" ;
		  		$diff_level = $new_level - intval($old_level);
				
		  		$current_cat = Mage::getModel('cmspro/category')->load($model->getId());

				if($current_cat->getRootPath()==""){
					$current_cat->setRootPath($root_path);
					$sql = "UPDATE ".$collections->getTable('category')." 
			  				SET  
								`level` = '".$new_level."',
								`root_path` = '".$root_path."'
							WHERE
								`category_id` = ".$model->getId();
							
				}else{
			  		$sql = "UPDATE ".$collections->getTable('category')." 
			  				SET  
								`level` = ".$diff_level." +`level` ,
								`root_path` = REPLACE(`root_path`,'".$current_cat->getRootPath()."','".$root_path."')
							WHERE 
								`root_path` LIKE '".$current_cat->getRootPath()."%'	
							";
				}
				
				$conn = Mage::getModel('core/resource')->getConnection('core_write');
				$conn->query($sql);
				
				// Update URL Rewrite
				$suffix = Mage::getStoreConfig('mw_cmspro/info/category_suffix') ? Mage::getStoreConfig('mw_cmspro/info/category_suffix'):".html"; 
		  		$url = "";
				$rq_path = "";
				$identifier = isset($data['identifier']) ? $data['identifier'] : "";
				if($identifier!=""){
					$rq_path = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format(Mage::getModel('cmspro/category')->getCurrentRoot()->getName()));
					$rq_path = strtolower($rq_path);
					$identifier = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($identifier));
					$identifier = strtolower($identifier);				
					$rq_path = $rq_path."/".$identifier;
				}else{
					//echo $current_cat->getRootPath();exit;
					$paths = explode('/',trim($current_cat->getRootPath()));
					foreach($paths as $key=>$parent){
							if($parent!=""){
								$parent = Mage::getModel('cmspro/category')->load($parent);
								if($parent->getName()!=""){
									$url = "";
									if($parent->getLevel()!='1' || $parent->getParentId()!='0'){
										$url = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($parent->getName()));
										$url = strtolower($url);
										$rq_path .= "/".$url;
									}else{
										$url = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format(Mage::getModel('cmspro/category')->getCurrentRoot()->getName()));
										$url = strtolower($url);
										$rq_path .=$url;
									}
								}
							}
						}
				}
				$rq_path1 = trim($rq_path).$suffix;
				
		     	$url_rewrite['request_path'] = $rq_path1;
		     	$url_rewrite['target_path'] = 'cmspro/category/view/id/'.$model->getId();
		     	$url_rewrite['id_path'] = $rq_path1;
		     	$url_rewrite['is_system'] = 0;
		     	$url_rewrite['options'] = 0;

				$current_cat = Mage::getModel('cmspro/category')->load($model->getId());
		     	if($current_cat->getUrlRewriteId()=="0") {
		     		//checking dulicate request path when add news url
			     	$url_model1 = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToFilter('request_path',$rq_path1);
			     	if($url_model1->count()>0){
			     		$rq_path2 = $rq_path.rand(100,999999).$suffix;
			     		$url_rewrite['request_path'] = $rq_path2;
			     		$url_rewrite['id_path'] = $rq_path2;
			     	} 
			     	
			     	$url_model = Mage::getModel('core/url_rewrite');
					$url_model->setData($url_rewrite);
					$url_model->save();
					$model->setUrlRewriteId($url_model->getId());
					$model->save();
				}else{
					$url_model1 = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToFilter('request_path',$rq_path1);
					$dulicate = false;
					foreach($url_model1 as $u){
						if($u->getUrlRewriteId()!=$current_cat->getUrlRewriteId()) $dulicate = true;
					}
					if($dulicate){
			     		$rq_path2 = $rq_path.rand(100,999999).$suffix;
			     		$url_rewrite['request_path'] = $rq_path2;
			     		$url_rewrite['id_path'] = $rq_path2;
			     	}
			     	$url_model = Mage::getModel('core/url_rewrite')->load($current_cat->getUrlRewriteId());
					$url_model->setData($url_rewrite)->setId($current_cat->getUrlRewriteId());
					$url_model->save();
				}

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cmspro')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/index');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cmspro')->__('Unable to find item to save'));
        $this->_redirect('*/*/index');
	}
 
	 public function deleteAction() {
	 	//echo($this->getRequest()->getParam('id')); exit;
		if( $this->getRequest()->getParam('id') > 0 ) {
			if($this->getRequest()->getParam('id') != 1){
				try {
					$root_path = Mage::getModel('cmspro/category')->load($this->getRequest()->getParam('id'))->getRootPath();
					$collections = Mage::getModel('cmspro/category')->getCollection();
					$collections->getSelect()->where("main_table.root_path LIKE '".$root_path."%'");
					
					foreach($collections as $cat){
						// Delete url_rewrite:
						Mage::getModel('core/url_rewrite')->setId($cat->getUrlRewriteId())->delete();
						
						// Delete store category:
						$delStore = "DELETE FROM `".$collections->getTable('category_store')."` WHERE `category_id`='".$cat->getCategoryId()."'";
						$conn = Mage::getSingleton('core/resource')->getConnection('core_write');
						$conn->query($delStore);
						
						// Delete category:
						Mage::getModel('cmspro/category')->setId($cat->getCategoryId())->delete();
					}
					
						 
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
					$this->_redirect('*/*/index');
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('edit', array('id' => $this->getRequest()->getParam('id')));
				}
			}else{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('You can not delete root category!'));
				$this->_redirect('edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/index');
	}

    public function massDeleteAction() {
        $categoryIds = $this->getRequest()->getParam('category');
        if(!is_array($categoryIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                	$current_cat = Mage::getModel('cmspro/category')->load($categoryId);
                	if($current_cat){
	                    $root_path = $current_cat->getRootPath();
						$collections = Mage::getModel('cmspro/category')->getCollection();
						$collections->getSelect()->where("main_table.root_path LIKE '".$root_path."%'");
						
						foreach($collections as $cat){
							// Delete url_rewrite:
							Mage::getModel('core/url_rewrite')->setId($cat->getUrlRewriteId())->delete();
							
							// Delete store category:
							$delStore = "DELETE FROM `".$collections->getTable('category_store')."` WHERE `category_id`='".$cat->getCategoryId()."'";
							$conn = Mage::getSingleton('core/resource')->getConnection('core_write');
							$conn->query($delStore);
							
							// Delete category:
							Mage::getModel('cmspro/category')->setId($cat->getCategoryId())->delete();
						}
                	}
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

    public function setOrderAction() {
        $params = $this->getRequest()->getParam('items');
        if(!$params) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
            	$params = explode('|',$params);
                foreach ($params as $param) {
					//echo $this->getRequest()->getPost('order'.$categoryId); exit;
					$param = explode('-',$param);
					if(sizeof($param)>1){
						$model = Mage::getModel('cmspro/category');
						$model->setData(array('order_cat'=>$param[1]))->setId($param[0]);
						$model->save();
					}
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($params)
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
        $categoryIds = $this->getRequest()->getParam('category');
        if(!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $category = Mage::getSingleton('cmspro/category')
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
        $content    = $this->getLayout()->createBlock('cmspro/adminhtml_category_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'cmspro.xml';
        $content    = $this->getLayout()->createBlock('cmspro/adminhtml_category_grid')
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
    
	public function reindexAction(){
    	try{
    		$category = Mage::getModel('cmspro/category');
    		$category->reindexCategory();
    		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('All Categories were reindexed Successful'));
    	}catch(Exception $e){
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occured, please try again'));
    	}
    	$this->_redirect('*/*/index'); 
    }
}