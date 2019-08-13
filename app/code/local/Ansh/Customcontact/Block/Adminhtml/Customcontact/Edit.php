<?php

class Ansh_Customcontact_Block_Adminhtml_Customcontact_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'customcontact';
        $this->_controller = 'adminhtml_customcontact';

        $this->_updateButton('save', 'label', Mage::helper('customcontact')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('customcontact')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('customcontact_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'customcontact_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'customcontact_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('customcontact_data') && Mage::registry('customcontact_data')->getId()) {
            return Mage::helper('customcontact')->__("Edit Contact '%s'", $this->htmlEscape(Mage::registry('customcontact_data')->getName()));
        } else {
            return Mage::helper('customcontact')->__('Add Contact');
        }
    }

}
