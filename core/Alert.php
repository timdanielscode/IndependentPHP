<?php 

namespace core;

class Alert {

    private static $_type;

    /**
     * To set type of alert message (success or failed)
     * 
     * @param string $type success | failed
     */
    public static function message($type) {

        if(!empty($type) && $type !== null) {

            return self::check($type);
        }
    }

    /**
     * To check type of alert message
     * 
     * @param string $type success | failed
     */    
    private static function check($type) {

        if($type === 'success') {

            self::$_type = $type;
            return self::createSuccess();

        } else if($type === 'failed') {

            self::$_type = $type;
            return self::createFailed();
        } 
    }

    /**
     * To show a div with the session value inside where setted name of session is set to success 
     */      
    private static function createSuccess() {

        if(Session::exists(self::$_type)) {

            echo '<div class="message-container success"><span class="success-message">' . Session::get(self::$_type) . '</span></div>';
            Session::delete(self::$_type);
        }
    }

    /**
     * To show a div with the session value inside where setted name of session is set to failed 
     */       
    private static function createFailed() {

        if(Session::exists(self::$_type)) {

            echo '<div class="message-container failed"><span class="failed-message">' . Session::get(self::$_type) . '</span></div>';
            Session::delete(self::$_type);
        }
    }
}