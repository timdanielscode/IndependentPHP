<?php
/**
 * RouteBinder
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use routing\Router;
use core\Session;
use core\http\Response;

class RouteBinder {

    private $_path, $_partsUri, $_uriRouteKeyValues, $_pathRouteKeyValues, $_requestVariables;

    /**
     * Replaces routeKey of setted route path with uri string value on same exploded key position as routeKey
     * 
     * @example route path: /profile/['username'] match: /profile/any value
     * @param array $path route
     * @param array $routeKeyKeys route
     * @param array $string uri 
     * @param string $routeKey
     * @return void 
     */
    public function setPath($path, $routeKeyKeys, $uri, $routeKey) {

        $uri = strtok($uri, '?');
        $this->_partsUri = explode("/", $uri);

        foreach($routeKeyKeys as $routeKeyKey) {

            if(!empty($this->_partsUri[$routeKeyKey])) {

                $this->_uriRouteKeyValues = $this->_partsUri[$routeKeyKey];
                $this->_pathRouteKeyValues = $path[$routeKeyKey];
                $path[$routeKeyKey] = $this->_uriRouteKeyValues;
                $this->_pathRouteKeyValues = trim($this->_pathRouteKeyValues, "[]");
                $this->_requestVariables[$this->_pathRouteKeyValues] =  $this->_uriRouteKeyValues;

                $this->_path = implode("/", $path);
            }
        }
    }

    /**
     * Returns _path
     * 
     * @return string $_path route
     */
    public function getPath() {

        return $this->_path;
    }

    /**
     * Adding _POST values to request variable if route key value matches _POST key
     * 
     * @return void
     */
    public function postRequestVariables() {
        
        if(!empty($_POST[$this->_pathRouteKeyValues]) ) {

            $this->_requestVariables[$this->_pathRouteKeyValues] = $_POST[$this->_pathRouteKeyValues];
        }
    }

    /**
     * Getting request variables
     * 
     * @return mixed array _requestVariables $_POST values|bool
     */
    public function getRequestVariables() {

        if($this->_requestVariables !== null) {
            
            return $this->_requestVariables;
        } else {
            return false;
        }
    }
}