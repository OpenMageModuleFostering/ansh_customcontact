<?php

class Ansh_Customcontact_Model_Customcontact extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('customcontact/customcontact');
    }

}
