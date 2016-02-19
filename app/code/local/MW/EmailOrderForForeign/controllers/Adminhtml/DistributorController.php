<?php

class MW_EmailOrderForForeign_Adminhtml_DistributorController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('distributor_id');
        $model = Mage::getModel('emailorderforforeign/distributor')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            if ($model->getId()) {
                Mage::register('distributor_data', $model);
            }
            $this->loadLayout();
            $this->_setActiveMenu('emailorderforforeign/distributor');

//            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
//            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('emailorderforforeign/adminhtml_distributor_edit'))
                    ->_addLeft($this->getLayout()->createBlock('emailorderforforeign/adminhtml_distributor_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('emailorderforforeign')->__('Department does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('distributor_id') > 0) {
            try {
                $model = Mage::getModel('emailorderforforeign/distributor');

                $model->setId($this->getRequest()->getParam('distributor_id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The department has been deleted successfully'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('distributor_id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            //echo '<pre>';var_dump($data);die();	
            $model = Mage::getModel('emailorderforforeign/distributor');

            $model->setData($data)
                    ->setId($this->getRequest()->getParam('distributor_id'));

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emailorderforforeign')->__('Mapping has been saved successfully'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('distributor_id' => $model->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('emailorderforforeign')->__('Unable to find department to save'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $distributorIds = $this->getRequest()->getParam('distributor_ids');
        if (!is_array($distributorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($distributorIds as $distributorId) {
                    $distributor = Mage::getModel('emailorderforforeign/distributor')->load($distributorId);
                    $distributor->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) has been deleted successfully', count($distributorIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}

?>
