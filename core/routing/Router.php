<?php
/**
 * Router
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use app\controllers\http\ResponseController;

class Router extends RouteBinder {

    private $_uri, $_path, $request, $request_vals, $_pathRouteKeyKeys, $_error;
    private $_partsPath = [];
    private $_routeBinder;

    /**
     * Declaring Request & Response
     * 
     * @return void
     */
    public function __construct($request, $response) {
    
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Setting route path by comparing route path with current request uri value on
     * request method type of get
     * 
     * @param string $path route
     * @param array $routeKeys optional
     * @return object Router Request Response
     */
    public function getRequest($path, $routeKeys = null) {

        if($routeKeys) {
            
            $checkKeys = implode('|', $routeKeys);
            if(preg_match("($checkKeys)", $path) === 1) { 

                $this->setRouteKeyKeys($path, $routeKeys);
                $this->_routeBinder = new RouteBinder();
                $this->_routeBinder->setPath($this->_partsPath, $this->_pathRouteKeyKeys, $this->request->getUri());
                $path = $this->_routeBinder->getPath();  
            } 
        }
        if(strtok($this->request->getUri(), '?') == $path || strtok($this->request->getUri(), '?') . "/" == $path) {

            if($this->request->getMethod() === 'GET') {
                $this->_path = $path;
            } 
        } 
        return $this;
    }

    /**
     * Setting route path by comparing route path with current request uri value on
     * request method type of post
     * 
     * @param string $path route
     * @param array $routeKeys optional
     * @return object Router Request Response
     */
    public function postRequest($path, $routeKeys = null) {

        if($routeKeys) {
           
            $checkKeys = implode('|', $routeKeys);
            if(preg_match("($checkKeys)", $path) === 1) { 
                
                $this->setRouteKeyKeys($path, $routeKeys);
                $this->_routeBinder = new RouteBinder();
                $this->_routeBinder->setPath($this->_partsPath, $this->_pathRouteKeyKeys, $this->request->getUri()); 
                $path = $this->_routeBinder->getPath();      
            } 
        }
        if($this->uri() == $path || $this->uri() . "/" == $path) {

            if($this->request->getMethod() === 'POST') {

                if($this->_routeBinder) {
                    $this->_routeBinder->postRequestVariables();
                }
                $this->_path = $path;  
            } 
        } 
        return $this;
    }

    /**
     * Adding the route key keys in property
     * Created so route key string position can be compared
     * 
     * @param string $path route
     * @param array $routeKeys
     * @return void
     */
    public function setRouteKeyKeys($path, $routeKeys) {

        $this->_partsPath = explode("/", $path);

        foreach($routeKeys as $routeKey) {

            $this->_pathRouteKeyKeys[] = array_search('['.$routeKey.']', $this->_partsPath);
        }
    }

    /**
     * @param string $class name
     * @param string $method optional 'action'
     * @return object Controller
     */    
    public function add($class, $method = null) {  

        if($this->uri() == $this->_path || $this->uri() . "/" == $this->_path) {
            
            $namespaceClass = $this->namespace($class);
            if(class_exists($namespaceClass)) { 

                $instance = new $namespaceClass;
                if($method) {
                    if(method_exists($namespaceClass, $method)) {
                        
                        $this->request_vals = $this->request->get();
                       if($this->_routeBinder) {
                        
                           if($this->_routeBinder->getRequestVariables() !== null) {
                                $this->request_vals = array_merge($this->request_vals, $this->_routeBinder->getRequestVariables());
                           }    
                        }
                        return $instance->$method($this->request_vals) . exit();
                    } 
                } else {
                    return $instance . exit(); 
                }
            } 
        } 
    }

    /**
     * @param string $path route
     * @param string $view name
     * @param array $routeKeys optional
     * @return object Controller
     */ 
    public function handleView($path, $view, $routeKeys = null) {

        if($routeKeys) {
           
            $checkKeys = implode('|', $routeKeys);
            if(preg_match("($checkKeys)", $path) === 1) { 
                
                $this->setRouteKeyKeys($path, $routeKeys);
                $this->_routeBinder = new RouteBinder();
                $this->_routeBinder->setPath($this->_partsPath, $this->_pathRouteKeyKeys, $this->request->getUri()); 
                $path = $this->_routeBinder->getPath();      
            } 
        }
     
        if(strtok($this->request->getUri(), '?') == $path || strtok($this->request->getUri(), '?') . "/" == $path) {
            
            if($this->request->getMethod() === 'GET') {

                $this->_path = $path;
                $controller = $this->namespace("Controller");
                $instance = new $controller;
                
                return $instance->view($view) . exit();
            } 
        } 
    }

    /**
     * @return string _uri requset uri
     */   
    public function uri() {
        
        $this->_uri = $this->request->getUri();
        $this->_uri = strtok($this->_uri, '?');
            
        return $this->_uri; 
    }
    
    /**
     * @param string $class name
     * @return string namespace name
     */      
    private function namespace($class) {

        $namespace = 'app\controllers\\' . $class;       
        return $namespace;
    }    
}
