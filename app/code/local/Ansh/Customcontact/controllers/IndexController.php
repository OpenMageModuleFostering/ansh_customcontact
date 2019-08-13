<?php

class Ansh_Customcontact_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function createContactAction() {

        $data = array(
            'name' => $this->getRequest()->getParam('name'),
            'email' => $this->getRequest()->getParam('email'),
            'mobile_number' => $this->getRequest()->getParam('mobile_number'),
            'queries' => $this->getRequest()->getParam('queries'),
            'created_time' => strtotime('now'),
            'update_time' => strtotime('now')
        );


        Mage::getSingleton('customer/session')->setPostData($data);

        $model = Mage::getModel('customcontact/customcontact')->setData($data); //addData
        try {
            $insertId = $model->save()->getId();
            if ($insertId) {
                $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
                $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

                //Send email to admin
                $templateData = array(
                    'mobile_number' => $data['mobile_number'],
                    'queries' => $data['queries']
                );
                $adminTemplateId = 'customcontact_email_setting_email_admin_template';
                $emailTo = Mage::getStoreConfig('trans_email/ident_general/email');
                $adminEmailTemplate = Mage::getModel('core/email_template')->loadDefault($adminTemplateId);
                $adminEmailTemplate->setSenderName($senderName);
                $adminEmailTemplate->setSenderEmail($senderEmail);
                $adminEmailTemplate->send($emailTo, $data, $templateData);

                //Send email to customer
                $customerTemplateId = 'customcontact_email_setting_email_customers_template';
                $recepientEmail = $this->getRequest()->getParam('email');
                $customerEmailTemplate = Mage::getModel('core/email_template')->loadDefault($customerTemplateId);
                $customerEmailTemplate->setSenderName($senderName);
                $customerEmailTemplate->setSenderEmail($senderEmail);
                $customerEmailTemplate->send($recepientEmail, array('name' => $data['name']));
            }
            $successMessage = $this->__("Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us!!!");
            Mage::getSingleton('core/session')->addSuccess($successMessage);
        } catch (Exception $e) {
            $errorMessage = $this->__($e->getMessage());
            Mage::getSingleton('core/session')->addError($errorMessage);
        }

        $this->_redirect('customcontact/');
    }

}
