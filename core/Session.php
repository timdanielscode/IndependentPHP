<?php
/**
 * Session
 * 
 * @author Tim Daniëls
 */
namespace core;

class Session {

    /**
     * For checking if session exists by name 
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
     * For setting session  
     * 
     * @param string $name session
     * @param string $value session
     * @return global _SESSION name
     */    
    public static function set($name, $value) {
        
        return $_SESSION[$name] = $value;
    }

    /**
     * To get the session value
     * 
     * @param string $name session
     * @return global _SESSION name
     */     
    public static function get($name) {

        return $_SESSION[$name];
    }    

    /**
     * Unsets session by name 
     * 
     * @param string $name session
     * @return void
     */     
    public static function delete($name) {
        
        if(self::exists($name)) {
            
            unset($_SESSION[$name]);
        }
    }
}   