<?php

class Ansh_Customcontact_Adminhtml_CustomcontactController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('customcontact/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Custom Contacts'), Mage::helper('adminhtml')->__('Custom Contacts')
            );

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('customcontact/customcontact')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('customcontact_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('customcontact/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Custom Contacts'), Mage::helper('adminhtml')->__('Custom Contacts'));
          
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('customcontact/adminhtml_customcontact_edit'))
                    ->_addLeft($this->getLayout()->createBlock('customcontact/adminhtml_customcontact_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('customcontact')->__('Contact does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {

        if ($data = $this->getRequest()->getPost()) {

            $model = Mage::getModel('customcontact/customcontact');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('customcontact')->__('Contact was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('customcontact')->__('Unable to find contact to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('customcontact/customcontact');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Contact was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $customContactIds = $this->getRequest()->getParam('customcontact');
        if (!is_array($customContactIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select contact(s)'));
        } else {
            try {
                foreach ($customContactIds as $customContactId) {
                    $customcontact = Mage::getModel('customcontact/customcontact')->load($customContactId);
                    $customcontact->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($customContactIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $customContactIds = $this->getRequest()->getParam('customcontact');
        if (!is_array($customContactIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($customContactIds as $customContactId) {
                    $customcontact = Mage::getSingleton('customcontact/customcontact')
                            ->load($customContactId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($customContactIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'customcontact.csv';
        $content = $this->getLayout()->createBlock('ansh_customcontact_block_adminhtml_customcontact_grid');
        $this->_prepareDownloadResponse($fileName, $content->getCsvFile());
    }

    public function exportXmlAction() {
        $fileName = 'customcontact.xml';
        $content = $this->getLayout()->createBlock('ansh_customcontact_block_adminhtml_customcontact_grid');        
        $this->_prepareDownloadResponse($fileName, $content->getXml());
    }

    public function exportExcelAction() {
        $fileName = 'customcontact.xls';
        $content = $this->getLayout()->createBlock('ansh_customcontact_block_adminhtml_customcontact_grid');       
        $this->_prepareDownloadResponse($fileName, $content->getExcelFile());
    }

}
