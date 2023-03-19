<?php
/**
 * Csrf
 * 
 * @author Tim Daniëls
 */
namespace core;

use core\Session;
use core\http\Request;

class Csrf {

    /**
     * Generating csrf token if not exists
     * can be used to add a token and get the token 
     * 
     * @param string $arg get|add
     * @return string token csrf
     */
    public static function token($arg) {

        if(!Session::exists('Csrf_token')) {
            Session::set('Csrf_token', bin2hex(random_bytes(32)));
        }

        $token = hash_hmac('sha256', 'hash me please!', Session::get('Csrf_token'));
        
        if($arg == 'get') {
            return $token;
        } else if($arg == 'add') {
            echo $token;
        }
    }

    /**
     * Validating the token
     * If validation fails, setting csrf session and 
     * redirecting back to current request uri global value
     * 
     * @param string $token value
     * @param string $postToken value
     * @return bool true 
     */ 
    public static function validate($token, $postToken) {

        if(hash_equals($token, $postToken) === false) {

            $request = new Request();
            Session::set('csrf', 'Cross site request forgery!');
            redirect($request->getUri()) . exit();
        } else {

            return true;
        }
    }
}