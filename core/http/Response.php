<?php
/**
 * Response
 * 
 * @author Tim Daniëls
 */
namespace core\http;

use app\controllers\Controller;

class Response extends Controller {

    private static $_path;

    /**
     * Getting route path 
     * 
     * @param string $path route path
     */ 
    public function getPath($path) {

        self::$_path[] = $path;
    }

    /**
     * Checking on empty route path values
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

            return $this->view('404/404');
        }
    }

    /** 
     * Setting status codes
     * Mainly created to set status code in Route class
     * 
     * @param mixed int|string $code
     * @return int status code 
     */    
    public function set($code) {

        return http_response_code($code);
    }

    /** 
     * Setting status code
     * Created so views can be chained in Controller
     * 
     * @param mixed int|string $code
     * @return object Response
     */ 
    public static function statusCode($code) {

        http_response_code($code);
        $inst = new Response();

        return $inst;
    }
}