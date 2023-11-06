<?php 
/**
 * Routing functionality
 * 
 * @author Tim Daniëls
 */

namespace core\routing;

class Routing {

    private $_type, $_path, $_class;

    /**
     * Checking request type method and if type matches request method, setting _type property
     * 
     * @param string $type type of request
     * @return object Routing 
     */ 
    public function type($type) {

        if($type === $_SERVER['REQUEST_METHOD']) {

            $this->_type = $type;
        }

        return $this;
    }

    /**
     * Setting uri, controller and method
     * 
     * @param string $path uri path
     * @return object Routing 
     */ 
    public function uri($path, $class) {

        if(!empty($this->_type) && $this->_type !== null) {

            $this->checkPath($path, $class);
        }

        return $this;
    }

    /**
     * Checking path and if matches request uri, setting _path property
     * 
     * @param string $name path name
     */ 
    private function checkPath($path, $class) {

        if($path === $_SERVER['REQUEST_URI']) {

            $this->_path = $path;
            $this->checkClass(key($class), $class[key($class)]);
        }
    }

    /**
     * Checking class and if exists, setting _class property
     * 
     * @param string $class controller name
     * @param string $method method name
     */ 
    private function checkClass($class, $method) {

        if(file_exists('../app/controllers/' . $class . '.php') === true) {

            $this->_class = 'app\controllers\\' . $class;
            $this->checkMethod($method);
        }
    }

    /**
     * Checking method exists
     * 
     * @param string $name path name
     */ 
    private function checkMethod($method) {

        if(method_exists($this->_class, $method)) {

            $this->instance($method);
        } 
    }

    /**
     * Creating instances 
     * 
     * @param string $name path name
     */ 
    private function instance($method) {
        
        $instance = new $this->_class;
        return $instance->$method();
    }
}