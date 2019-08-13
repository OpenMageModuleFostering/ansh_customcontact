<?php

class Ansh_Customcontact_Block_Customcontact extends Mage_Core_Block_Template {

    protected $_Collection = null;

    public function getCollection() {
        if (is_null($this->_Collection)) {
            $this->_Collection = Mage::getModel('customcontact/customcontact')->getCollection();
        }
        return $this->_Collection;
    }

    public function getActionOfForm() {
        return $this->getUrl('customcontact/index/createContact');
    }

}
