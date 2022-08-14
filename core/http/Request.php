<?php
/**
 * Request
 * 
 * @author Tim Daniëls
 */
namespace core\http;

class Request {

    private $_data = [], $_postData = [], $_getData = [];

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
     * @return array POST/GET variables
     */       
    public function get() {

        $data = [];

        if($this->getMethod() === 'POST') {

            foreach($_POST as $key => $value) {

                $key = htmlspecialchars($key);
                $this->_postData[$key] = $value;
            }
        }
        if(!empty($_GET) && $_GET !== null) {

            foreach($_GET as $key => $value) {

                $key = htmlspecialchars($key);
                $this->_getData[$key] = $value;
            }
        }

        $this->_data = array_merge($this->_postData, $this->_getData);
        return array_filter($this->_data);
    }
}