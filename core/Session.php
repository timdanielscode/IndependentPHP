<?php

namespace core;

class Session {

    /**
     * To check if session exists
     * 
     * @param string $name session
     * @return bool
     */
    public static function exists($name) {

        if(isset($_SESSION[$name])) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * To set a session  
     * 
     * @param string $name session
     * @param string $value session
     * @return string session value
     */    
    public static function set($name, $value) {
  
        return $_SESSION[$name] = $value;
    }

    /**
     * To get the session value
     * 
     * @param string $name session
     * @return string session name
     */     
    public static function get($name) {

        return $_SESSION[$name];
    }    

    /**
     * To unset a session 
     * 
     * @param string $name session
     */     
    public static function delete($name) {
        
        if(self::exists($name)) {
            
            unset($_SESSION[$name]);
        }
    }
}   