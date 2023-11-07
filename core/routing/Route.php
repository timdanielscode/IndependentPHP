<?php 
/**
 * Setting routes
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use core\http\Request;

class Route {

    private $_path, $_pathParts, $_class, $_request;

    public function __construct($path, $class) {

        $this->_request = new Request();
        $this->checkPath($path, $class);
    }

    /**
     * Checking request uri path
     * 
     * @param array $path uri path name
     * @param array $class class name, method name
     */ 
    private function checkPath($path, $class) {

        if($path[0] === $this->_request->getUri()) {

            $this->_path = $path[0];
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
        preg_match($regex, $path[0], $match);

        if(!empty($match) && $match !== null) {

            $this->getRouteKeyKey($path[0], $match, $class);
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

                $this->_pathParts[$key] = $uriParts[$key];
                $routePathUriKey = implode('/', $this->_pathParts);

                return $this->checkPath([$routePathUriKey], $class);
            }
        }
    }

    /**
     * Checking if class exists
     * 
     * @param array $class class name, method name
     */ 
    private function checkClass($class) {

        if(file_exists('../app/controllers/' . key($class) . '.php') === true) {

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

            return $instance->$method($this->_request->get());
        }

        $instance->$method();
    }
}