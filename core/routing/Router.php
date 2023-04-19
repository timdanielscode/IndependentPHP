<?php
/**
 * Router
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use app\controllers\http\ResponseController;
use core\Session;
use core\http\Middleware;

class Router extends RouteBinder {

    private $_uri, $_path, $request, $request_vals, $_pathRouteKeyKeys, $_error;
    private $_partsPath = [];
    private $_routeBinder;
    private $_middlewareAlias;
    private $_crud = false;

    /**
     * Declaring Request & Response
     * 
     * @return void
     */
    public function __construct($request, $response, $middleware = null) {

        $this->request = $request;
        $this->response = $response;

        if($middleware !== null) {

            $this->_middlewareAlias = $middleware;
        }
    }

    /**
     * Setting crud route paths for request method type of get
     * (index, create, read, edit, delete)
     * 
     * @param string $path route
     * @param array $routeKey 
     * @param array $routeKeys 
     * @param array $sessionRouteKeys
     * @return object Router Request Response
     */
    public function getRequestCrud($path, $routeKey, $routeKeys, $sessionRouteKeys = null) {

        $getCrudPathParts = ['', 'create', 'read', 'edit', 'delete'];
        
        foreach($getCrudPathParts as $getCrudPathPart) {

            $crudPath = $path . '/' . $getCrudPathPart;
            if($getCrudPathPart === '' || $getCrudPathPart === 'create' || $getCrudPathPart === 'read' || $getCrudPathPart === 'edit' || $getCrudPathPart === 'delete') {

                $crudPath = $path . '/' . $routeKey . '/' . $getCrudPathPart;
            }

            $this->_crud = true;
            $this->getRequest($crudPath, $routeKeys, $sessionRouteKeys);
        }

        return $this;
    }

    /**
     * Setting crud route paths for request method type of post
     * (store, update)
     * 
     * @param string $path route
     * @param array $routeKey 
     * @param array $routeKeys 
     * @param array $sessionRouteKeys
     * @return object Router Request Response
     */
    public function postRequestCrud($path, $routeKey, $routeKeys, $sessionRouteKeys = null) {

        $postCrudPathParts = ['store', 'update'];
        
        foreach($postCrudPathParts as $postCrudPathPart) {

            $crudPath = $path . '/' . $postCrudPathPart;
            if($postCrudPathPart === 'update') {

                $crudPath = $path . '/' . $routeKey . '/' . $postCrudPathPart;
            }

            $this->_crud = true;
            $this->postRequest($crudPath, $routeKeys, $sessionRouteKeys);
        }

        return $this;
    }

    /**
     * Setting route path by comparing route path with current request uri value on
     * request method type of get
     * 
     * @param string $path route
     * @param array $routeKeys
     * @param array $sessionRouteKeys
     * @return object Router Request Response
     */
    public function getRequest($path, $routeKeys = null, $sessionRouteKeys = null) {

        if($routeKeys !== null && $this->getRouteKeyPath($path, $routeKeys, $sessionRouteKeys) !== null) {
            
            $path = $this->getRouteKeyPath($path, $routeKeys, $sessionRouteKeys);

        }

        if($this->uri() == $path || $this->uri() . "/" == $path) {
  
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
     * @param array $routeKeys
     * @param array $sessionRouteKeys
     * @return object Router Request Response
     */
    public function postRequest($path, $routeKeys = null, $sessionRouteKeys = null) {

        if($routeKeys !== null && $this->getRouteKeyPath($path, $routeKeys, $sessionRouteKeys) !== null) {
            
            $path = $this->getRouteKeyPath($path, $routeKeys, $sessionRouteKeys);
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
     * Getting route key route path
     * 
     * @param string $path route
     * @param array $routeKeys
     * @param array $sessionRouteKeys
     * @return void
     */
    public function getRouteKeyPath($path, $routeKeys, $sessionRouteKeys) {

        foreach($routeKeys as $routeKey) {

            if(strpos($path, '['.$routeKey.']') !== false) {

                $this->setRouteKeyKeys($path, $routeKeys);
                $this->_routeBinder = new RouteBinder();
                $this->_routeBinder->setPath($this->_partsPath, $this->_pathRouteKeyKeys, $this->request->getUri(), $routeKey, $sessionRouteKeys);

                return $this->_routeBinder->getPath();
            } 
        }
    }

    /**
     * Creating instances of controller classes
     * 
     * @param string $class name
     * @param string $method optional 'action'
     * @return object Controller
     */    
    public function add($class, $method = null) {  

        if($this->uri() == $this->_path || $this->uri() . "/" == $this->_path) {

            $namespaceClass = $this->namespace($class);

            if(class_exists($namespaceClass)) { 

                $instance = new $namespaceClass;
                
                if($method || $this->_crud === true) {

                    if($this->_crud === true) {

                        $method = $this->getCrudMethod();
                    }

                    if(method_exists($namespaceClass, $method) ) {
                        
                        $this->request_vals = $this->request->get();

                        if($this->_routeBinder && !empty($this->_routeBinder->getRequestVariables()) && $this->_routeBinder->getRequestVariables() !== null) {

                            $this->request_vals = array_merge($this->request_vals, $this->_routeBinder->getRequestVariables());
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
     * Getting crud method type
     * 
     * @return string $method crud method type
     */
    public function getCrudMethod() {

        $pathParts = explode('/', $this->_path);
                        
        $lastKey = array_key_last($pathParts);
        $lastKeyValue = $pathParts[$lastKey];

        switch ($lastKeyValue) {

            case "":
                $method = "index";
            break;
            case "create":
                $method = "create";
            break;
            case "store":
                $method = "store";
            break;
            case "read":
                $method = "read";
            break;
            case "edit":
                $method = "edit";
            break;
            case "update":
                $method = "update";
            break;
            case "delete":
                $method = "delete";
            break;
        }

        return $method;
    }

    /**
     * @param string $path route
     * @param string $view name
     * @param array $routeKeys
     * @param array $sessionRouteKeys
     * @return object Controller
     */ 
    public function handleView($path, $view, $routeKeys = null, $sessionRouteKeys = null) {

        if($routeKeys !== null && $this->getRouteKeyPath($path, $routeKeys, $sessionRouteKeys) !== null) {
            
            $path = $this->getRouteKeyPath($path, $routeKeys, $sessionRouteKeys);
        }
     
        if($this->uri() === $path || $this->uri() . "/" == $path) {
            
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
        
        $uri = strtok($this->request->getUri(), '?');
        return $uri; 
    }
    
    /**
     * @param string $class name
     * @return string namespace name
     */      
    private function namespace($class) {

        $namespace = 'app\controllers\\' . $class;       
        return $namespace;
    }    

    /**
     * Passing middleware aliases
     * 
     * @param $func object closure
     * @return object Middleware 
    */       
    public function run($func) {

        if(!empty($this->_middlewareAlias) && $this->_middlewareAlias !== null) {
 
            return Middleware::route($this->_middlewareAlias, $func);
        }
    }
}
