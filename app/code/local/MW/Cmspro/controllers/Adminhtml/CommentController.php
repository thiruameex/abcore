<?php
class MW_Cmspro_Adminhtml_CommentController extends Mage_Adminhtml_Controller_Action{
	
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('cmspro/comment')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
	
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function saveAction(){
		if ($data = $this->getRequest()->getPost()) {
			try{
				$model = Mage::getModel('cmspro/comment');		
					$model->setData($data)
						->setId($this->getRequest()->getParam('id'));
						
				$model->save();
				
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
	
	public function editAction(){
	
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('cmspro/comment')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				
				$model->setData($data);
			}
			
			
			Mage::register('comment_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('comment/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Comment Manager'), Mage::helper('adminhtml')->__('ToolTip Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Review News'), Mage::helper('adminhtml')->__('ToolTip News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('cmspro/adminhtml_comment_edit'))
				->_addLeft($this->getLayout()->createBlock('cmspro/adminhtml_comment_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cmspro')->__('Comment does not exist'));
			$this->_redirect('*/*/');
		}
	}
	public function newAction() {
		$this->_forward('edit');
	}
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('cmspro/comment');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Comment was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $commentIds = $this->getRequest()->getParam('comment');
        if(!is_array($commentIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $comment = Mage::getModel('cmspro/comment')->load($commentId);
                    $comment->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($commentIds)
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
        $commentIds = $this->getRequest()->getParam('comment');
        if(!is_array($commentIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $comment = Mage::getSingleton('cmspro/comment')
                        ->load($commentId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($commentIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'comment.csv';
        $content    = $this->getLayout()->createBlock('cmspro/adminhtml_comment_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'comment.xml';
        $content    = $this->getLayout()->createBlock('cmspro/adminhtml_comment_grid')
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