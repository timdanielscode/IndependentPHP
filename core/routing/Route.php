<?php 
/**
 * Setting routes
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

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
     * Checking type 
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
     * Removing get paramters from request uri type of get
     * 
     * @param string $uri uri
     */ 
    private function getParamters($uri) {

        if($this->_request->getMethod() === 'GET' && str_contains($uri, '?') === true) {

            return substr($uri, 0, strpos($uri, "?"));
        }
    }

    /**
     * Checking request uri path
     * 
     * @param array $path uri path name
     * @param array $class class name, method name
     */ 
    private function checkPath($path, $class) {

        if($path === $this->_request->getUri() || $path === $this->getParamters($this->_request->getUri())) {

            $this->_path = $path;
            $this->checkClass($class);
        } else {
            $this->checkRouteKeys($path, $class);
        }
    }

    /**
     * Checking route keys
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
     * Getting route key key
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
     * Replace route key key with uri key
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
     * Setting route key value
     * 
     * @param string $routeKey route key path value
     */ 
    private function setRouteKeyValue($routeKey) {

        $this->_routeKeyValue = trim($routeKey, '[]');
    }

    /**
     * Checking if class exists
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
     * Checking method exists
     * 
     * @param string $name method name
     */ 
    private function checkMethod($name) {

        if(method_exists('app\controllers\\' . $this->_class, $name)) {

            $this->instance('app\controllers\\' . $this->_class, $name);
        } 
    }

    /**
     * Create instance
     * 
     * @param string $class class name
     * @param string $method method name
     */
    private function instance($class, $method) {

        $instance = new $class();
    
        if($this->_request->getMethod() === 'POST') {

            return $this->typeOfPost($instance, $method);
        } else if($this->_request->getMethod() === 'GET') {
            return $this->typeOfGet($instance, $method);
        } 
    }

    /**
     * Running instance method type request of get
     * 
     * @param object $instance class instance
     * @param string $method method name
     */
    private function typeOfGet($instance, $method) {

        if(!empty($this->_uriRouteKeyValue) && $this->_uriRouteKeyValue !== null) {

            return $instance->$method([$this->_routeKeyValue => $this->_uriRouteKeyValue]);
        } 

        return $instance->$method();
    }

    /**
     * Running instance method type request of post
     * 
     * @param object $instance class instance
     * @param string $method method name
     */
    private function typeOfPost($instance, $method) {

        if(!empty($this->_uriRouteKeyValue) && $this->_uriRouteKeyValue !== null) {

            return $instance->$method(array_merge($this->_request->get(), [$this->_routeKeyValue => $this->_uriRouteKeyValue]));
        } 

        return $instance->$method($this->_request->get());
    }
}