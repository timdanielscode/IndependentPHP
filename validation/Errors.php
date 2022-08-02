<?php
/**
 * Errors
 * 
 * @author Tim Daniëls
 */
namespace validation;

class Errors {

    /**
     * Getting the validation rules
     * 
     * @param array $errors validation rules
     * @param string $name html input name
     * @return string validation errror
     */    
    public static function get($errors, $name) {

        if(!empty($errors && $errors !== null && !empty($name) && $name !== null)) {

            foreach($errors as $error) {
                if(array_key_exists($name, $error)) {
                    return $error[$name];
                } 
            }     
        } 
    }
}