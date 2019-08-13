<?php

class Ansh_Customcontact_Block_Adminhtml_Customcontact_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('customcontact_form', array('legend' => Mage::helper('customcontact')->__('Contact information')));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('customcontact')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('customcontact')->__('Email'),
            'class' => 'required-entry validate-email',
            'required' => true,
            'name' => 'email',
        ));

        $fieldset->addField('mobile_number', 'text', array(
            'label' => Mage::helper('customcontact')->__('Mobile Number'),
            'class' => 'validate-phoneLax',
            'name' => 'mobile_number',
        ));

        $fieldset->addField('queries', 'textarea', array(
            'label' => Mage::helper('customcontact')->__('Query/Note'),
            'required' => true,
            'class' => 'required-entry',
            'style' => 'width:500px; height:150px;',
            'name' => 'queries',
        ));


        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('customcontact')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('customcontact')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('customcontact')->__('Disabled'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getCustomcontactData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCustomcontactData());
            Mage::getSingleton('adminhtml/session')->setCustomcontactData(null);
        } elseif (Mage::registry('customcontact_data')) {
            $form->setValues(Mage::registry('customcontact_data')->getData());
        }
        return parent::_prepareForm();
    }

}
