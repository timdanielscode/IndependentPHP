<?php

namespace core\http;

use validation\request\Rules;

class Request {

    private $_postData = [], $_getData = [], $_postValues = [], $_getValues = [];

    /** 
     * To get the type of request method
     * 
     * @return global REQUEST_METHOD
     */    
    public function getMethod() {

        return $_SERVER['REQUEST_METHOD'];
    }

    /** 
     * To get the request uri
     * 
     * @return global REQUEST_URI
     */    
    public function getUri() {

        return $_SERVER['REQUEST_URI'];
    }
 
    /** 
     * To get post and get superglobal values
     * 
     * @return array POST/GET variables
     */       
    public function get() {

        $this->setPostData($_POST);
        $this->setGetData($_GET);

        return array_merge($this->_getData, $this->_postData);
    }

    /**
     * To check type of superglobal post names are set
     * 
     * @param array $post superglobal
     */
    private function setPostData($post) {

        if(!empty($post) && $post !== null) {

            foreach($post as $name => $value) {

                if(isset($post[$name]) === true) {
    
                    $this->checkTypeOfGlobal($value, $name, 'POST');
                }
            }
        }
    }

    /**
     * To check type of superglobal get names are set
     * 
     * @param array $get superglobal
     */
    private function setGetData($get) {

        if(!empty($get) && $get !== null) {

            foreach($get as $name => $value) {

                if(isset($get[$name]) === true) {

                    $this->checkTypeOfGlobal($value, $name, 'GET');
                }
            }
        }
    }

    /**
     * To check type of superglobal
     * 
     * @param mixed $value array|string get value
     * @param string $name name get 
     * @param string $type type of global
     */
    private function checkTypeOfGlobal($value, $name, $type) {

        if($type === 'GET') {

            return $this->checkTypeGet($value, $name);
        } 
            
        return $this->checkTypePost($value, $name);
    }


    /**
     * To check superglobal value type for superglobal type of get
     * 
     * @param mixed $value array|string get value
     * @param string $name name get 
     */
    private function checkTypeGet($value, $name) {

        if(gettype($value) === 'array') {

            return $this->setGetValues($value, $name);
        }

        $this->setGetValue($value, $name);
    }

    /**
     * To check superglobal value type for superglobal type of post
     * 
     * @param mixed $value array|string post value
     * @param string $name name post 
     */
    private function checkTypePost($value, $name) {

        if(gettype($value) === 'array') {

            return $this->setPostValues($value, $name);
        }

        $this->setPostValue($value, $name);
    }

    /**
     * To set type of get superglobal values
     * 
     * @param string $value get value
     * @param string $name name get 
     */
    private function setGetValue($value, $name) {

        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);

        $this->_getData[$name] = $value;
    }

    /**
     * To set type of post superglobal values
     * 
     * @param string $value post value
     * @param string $name name post 
     */
    private function setPostValue($value, $name) {

        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);

        $this->_postData[$name] = $value;
    }

    /**
     * To set type of get superglobal values
     * 
     * @param array $values get values
     * @param string $name name get 
     */
    private function setGetValues($values, $name) {

        $name = htmlspecialchars($name);

        foreach($values as $value) {

            $value = htmlspecialchars($value);
            $this->_getValues[] = $value;
        }

        $this->_getData[$name] = $this->_getValues;
    }

    /**
     * To set type of post superglobal values
     * 
     * @param array $values post values
     * @param string $name name post 
     */
    private function setPostValues($values, $name) {

        $name = htmlspecialchars($name);

        foreach($values as $value) {

            $value = htmlspecialchars($value);
            $this->_postValues[] = $value;
        }

        $this->_postData[$name] = $this->_postValues;
    }
}