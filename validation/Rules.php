<?php
/**
 * Rules
 * 
 * @author Tim Daniëls
 */
namespace validation;

use core\validation\Validate;
use app\controllers\Controller;
use core\Session;

class Rules {

    public $errors;

    /**
     * You can add the validation rule methods right here
     * 
     * https://indy-php.com/docs/validation
     * 
     * https://indy-php.com/docs/
     * Chain input method on instance variable and add html input name as argument
     * Chain as method to input and add alias as argument
     * Chain rules method to as method and add array of validation rules as argument
     * 
     * Set property $this->errors to instance errors property ($this->errors = $validation->errors)
     * Where $validation = instance Validate
     * return $this
     */


    /**
     * Validating validation rules
     * On fail, returning view with extracted validation error rules and if exists request data
     * 
     * @return mixed bool | void
     */
    public function validated($request = null) {

        if(empty($this->errors) ) { return true; } else {

          if(!empty($request) && $request !== null) {
            
              $data['data'] = $request; 
          }

          $controller = new Controller();
          $data['rules'] = $this->errors;

          return $controller->view(Session::get('view'), $data) . exit();
        }
    }
}