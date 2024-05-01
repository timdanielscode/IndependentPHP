<?php 

namespace core\http;

use core\http\Request;
use core\http\Response;

class Route {

    public $_path, $_pathParts, $_routeKeyValue, $_uriRouteKeyValue, $_class, $_request, $_response;

    public function __construct($path, $class) {

        $this->_request = new Request();
        $this->_response = new Response();

        $this->checkType(key($path), $path[key($path)], $class);
        $this->_response->getPath($this->_path);
    }

    /**
     * To check type of request method matches
     * 
     * @param string $type request method type
     * @param string $path uri path name
     * @param array $class class name, method name
     */ 
    private function checkType($type, $path, $class) {

        if($type === $this->_request->getMethod() ) {

            $this->checkPath($path, $class);
        }
    }

    /**
     * To remove get parameters from uri
     * 
     * @param string $uri uri
     */ 
    private function checkGetParameters($uri) {

        if(str_contains($uri, '?') === true) {

            return substr($uri, 0, strpos($uri, "?"));
        }
    }

    /**
     * To check if route path matches request uri
     * 
     * @param array $path uri path name
     * @param array $class class name, method name
     */ 
    private function checkPath($path, $class) {

        if($path === $this->_request->getUri() || $path === $this->checkGetParameters($this->_request->getUri())) {

            $this->_path = $path;
            $this->checkClass($class);
        } else {
            $this->checkRouteKeys($path, $class);
        }
    }

    /**
     * To check route path contains a route key
     * 
     * @param array $path uri path name
     * @param array $class class name, method name
     */ 
    private function checkRouteKeys($path, $class) {

        $regex = "/\[.*\]/";
        preg_match($regex, $path, $match);

        if(!empty($match) && $match !== null) {

            $this->getRouteKeyKey($path, $match, $class);
        }
    }

    /**
     * To get the route key key value
     * 
     * @param string $path uri path name
     * @param array $routeKey path uri key key
     * @param array $class class name, method name
     */ 
    private function getRouteKeyKey($path, $routeKey, $class) {

        $this->_pathParts = explode('/', $path);

        foreach($this->_pathParts as $key => $value) {
           
            if($value === $routeKey[0]) {

                return $this->replaceRouteKey($key, $class);
            }
        }
    }

    /**
     * To replace the route key value with the value on the same string position/key position as the route key from request uri
     * 
     * @param string $routeKeyKey uri path key key
     * @param array $class class name, method name
     */ 
    private function replaceRouteKey($routeKeyKey, $class) {

        $uriParts = explode('/', $this->_request->getUri());

        foreach($uriParts as $key => $value) {

            if($routeKeyKey === $key) {

                $this->_uriRouteKeyValue = $uriParts[$key];
                $this->setRouteKeyValue($this->_pathParts[$key]);
                $this->_pathParts[$key] = $this->_uriRouteKeyValue;
                $routePathUriKey = implode('/', $this->_pathParts);

                return $this->checkPath($routePathUriKey, $class);
            }
        }
    }

    /**
     * To set the route key value
     * 
     * @param string $routeKey route key path value
     */ 
    private function setRouteKeyValue($routeKey) {

        $this->_routeKeyValue = trim($routeKey, '[]');
    }

    /**
     * To check if the controller exists
     * 
     * @param array $class class name, method name
     */ 
    private function checkClass($class) {

        if(class_exists('app\controllers\\' . key($class) ) === true) {
          
            $this->_class = key($class);
            $this->checkMethod($class[key($class)]);
        }
    }

    /**
     * To check if the controller method exists
     * 
     * @param string $name method name
     */ 
    private function checkMethod($name) {

        if(method_exists('app\controllers\\' . $this->_class, $name)) {

            $this->instance('app\controllers\\' . $this->_class, $name);
        } 
    }

    /**
     * To run the controller method
     * 
     * @param string $class class name
     * @param string $method method name
     */
    private function instance($class, $method) {

        $instance = new $class();
    
        if(!empty($this->_uriRouteKeyValue) && $this->_uriRouteKeyValue !== null) {

            return $instance->$method(array_merge($this->_request->get(), [$this->_routeKeyValue => $this->_uriRouteKeyValue])) . exit();
        } 

        return $instance->$method($this->_request->get()) . exit();
    }
}