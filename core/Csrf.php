<?php
/**
 * Csrf
 * 
 * @author Tim Daniëls
 */
namespace core;

use core\Session;

class Csrf {

    /**
     * @param string $arg get | add
     * @return mixed string token | void
     */
    public static function token($arg) {

        $token = self::generate();
        return self::getOrAddToken($arg, $token);
    }

    /**
     * Generates token
     * 
     * @return string session token
     */
    private static function generate() {

        if(!Session::exists('csrf')) {

            Session::set('csrf', bin2hex(random_bytes(32)));
        } 

        return Session::get('csrf');
    }

    /**
     * @param string $type add | get
     * @param string token token
     * @return mixed string token | void
     */
    private static function getOrAddToken($type, $token) {

        if($type === 'get') {

            return self::getToken($token);
        } else if($type === 'add') {

            return self::addToken($token);
        } else {
            return;
        }
    }

    /**
     * @param string $token token
     * @return string token
     */
    private static function getToken($token) {

        return $token;
    }

    /**
     * @param string $token token
     * @return void
     */
    private static function addToken($token) {

        echo $token;
    }

    /**
     * Validating token
     * 
     * @param string $token token
     * @param string $postToken post token
     * @return bool
     */ 
    public static function validate($token, $postToken) {

        if($token !== $postToken) {

            self::setMessage();

            return false;
        } else {

            self::deleteToken();
            self::deleteMessage();

            return true;
        }
    }

    /**
     * Setting session token message
     * 
     * @return void
     */     
    private static function setMessage() {

        if(!Session::exists('csrf_message')) {

            Session::set('csrf_message', 'Cross site request forgery!');
        }
    }

    /**
     * Deleting session token message
     * 
     * @return void
     */     
    private static function deleteMessage() {

        if(Session::exists('csrf_message')) {

            Session::delete('csrf_message');
        }
    }

    /**
     * Deleting token
     * 
     * @return void
     */     
    private static function deleteToken() {

        if(Session::exists('csrf')) {

            Session::delete('csrf');
        }
    }
}