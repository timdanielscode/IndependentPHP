<?php
/**
 * Response
 * 
 * @author Tim Daniëls
 */
namespace core\http;

use app\controllers\Controller;

class Response extends Controller {

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