<?php 
/**
 * Setting routes
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use core\http\Request;

class Route {

    private $_type, $_path, $_class, $_request;

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