<?php

namespace validation;

use core\validation\Validate;

class Rules {

    public $errors;

    /**
     * To check for failed validation errors
     * 
     * @return mixed bool
     */
    public function validated() {

        if(empty($this->errors) ) {

            return true;
        }
    }
}

