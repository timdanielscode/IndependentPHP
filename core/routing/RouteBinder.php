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
     * @param array $sessionRouteKeys
     * @return void 
     */
    public function setPath($path, $routeKeyKeys, $uri, $routeKey, $sessionRouteKeys) {

        $uri = strtok($uri, '?');
        $this->_partsUri = explode("/", $uri);

        foreach($routeKeyKeys as $routeKeyKey) {

            if(!empty($this->_partsUri[$routeKeyKey])) {

                $this->_uriRouteKeyValues = $this->_partsUri[$routeKeyKey];
                $this->_pathRouteKeyValues = $path[$routeKeyKey];
                $path[$routeKeyKey] = $this->_uriRouteKeyValues;
                $this->_pathRouteKeyValues = trim($this->_pathRouteKeyValues, "[]");
                $this->_requestVariables[$this->_pathRouteKeyValues] =  $this->_uriRouteKeyValues;

                $this->sessionBasedRouteKey($routeKey, $sessionRouteKeys, $this->_uriRouteKeyValues);
                $this->_path = implode("/", $path);
            }
        }
    }

    /**
     * Return 404 response and 404 view when uri route key value does not match
     * the session value of the route key value of a session route key based path
     * 
     * @example route path: /profile/['username'] match: /profile/session value of setted session username
     * @param array $routeKey
     * @param array $sessionRouteKeys
     * @param array $uriRouteKeyValue 
     * @return object Response 404 
     */
    public function sessionBasedRouteKey($routeKey, $sessionRouteKeys, $uriRouteKeyValue) {

        if(in_array($routeKey, $sessionRouteKeys) === true) {

            if(Session::get($routeKey) !== $uriRouteKeyValue) {

                return Response::statusCode(404)->view("/404/404") . exit();
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