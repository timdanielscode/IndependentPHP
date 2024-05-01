<?php

namespace core\http;

use app\controllers\Controller;

class Response extends Controller {

    private static $_path;

    /**
     * To set path of routes to check for a 404
     * 
     * @param string $path route path
     */ 
    public function getPath($path) {

        self::$_path[] = $path;
    }

    /**
     * To show a 404 view and return a 404 http status code
     * 
     * @param int $type status code type
     */ 
    public function check($type) {

        if($type === 404) {

            foreach(self::$_path as $key => $value) {

                if(!empty($value)) {
    
                    return;
                }
            }

            return self::statusCode(404)->view('404/404')->data();
        }
    }

    /** 
     * To set http response status codes
     * 
     * @param mixed int|string $code
     * @return int status code 
     */    
    public function set($code) {

        return http_response_code($code);
    }

    /** 
     * To return a http response status code
     * 
     * @param mixed int|string $code
     * @return object Response
     */ 
    public static function statusCode($code) {

        http_response_code($code);

        return new Response();
    }
}