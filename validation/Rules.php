<?php
/**
 * Rules
 * 
 * @author Tim Daniëls
 */
namespace validation;

use core\validation\Validate;

class Rules {

    public $errors;

    /**
     * You can add the validation rule methods right here
     * 
     * https://indy-php.com/docs/validation
     * 
     * Create instance of Validate 
     * Chain input method on instance variable and add html input name as argument
     * Chain as method to input and add alias as argument
     * Chain rules method to as method and add array of validation rules as argument
     * 
     * Set property $this->errors to instance errors property ($this->errors = $validation->errors)
     * Where $validation = instance Validate
     * return $this
     */

    /**
     * @return bool
    */     
    public function validated() {

        if(empty($this->errors) ) {

            return true;
        }
    }
}