<?php
/**
 * Request
 * 
 * @author Tim Daniëls
 */
namespace core\http;

class Request {

    /** 
     * Getting REQUEST_METHOD
     * 
     * @return global REQUEST_METHOD
     */    
    public function getMethod() {

        return $_SERVER['REQUEST_METHOD'];
    }

    /** 
     * Getting REQUEST_URI
     * 
     * @return global REQUEST_URI
     */    
    public function getUri() {

        return $_SERVER['REQUEST_URI'];
    }
 
    /** 
     * Getting POST & GET superglobals
     * 
     * @param array $param optional POST|GET variables
     * @return array POST|GET variables
     */       
    public function get($param = null) {

        $data = [];

        if($this->getMethod() === 'POST') {
      
            foreach($_POST as $key => $value) {

                $value = htmlspecialchars($value);
                $data[$key] = $value;

                if($param) {
                    if($key == $param) {

                        $param = $value;
                        return $param;
                    } 
                }
            }
        }

        if($this->getMethod() === 'GET') {
            foreach($_GET as $key => $value) {

                $key = htmlspecialchars($key);
                $data[$key] = $value;

                if($param) {
                    if($key == $param) {

                        $param = $value;
                        return $param;
                    } 
                }
            }
        }
        return $data;
    }
}