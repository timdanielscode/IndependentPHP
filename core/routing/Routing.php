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
     * Setting uri
     * 
     * @param string $path uri path
     * @return object Routing 
     */ 
    public function uri($path) {

        if(!empty($this->_type) && $this->_type !== null) {

            $this->checkPath($path);
        }

        return $this;
    }

    /**
     * Setting controller
     * 
     * @param string $name controller name
     * @return object Routing 
     */ 
    public function controller($name) {

        if(!empty($this->_path) && $this->_path !== null) {

            $this->checkClass($name);
        }

        return $this;
    }

    /**
     * Setting method
     * 
     * @param string $name method name
     */ 
    public function method($name) {

        if(!empty($this->_class) && $this->_class !== null) {

            $this->checkMethod($name);
        }
    }

    /**
     * Checking path and if matches request uri, setting _path property
     * 
     * @param string $name path name
     */ 
    private function checkPath($name) {

        if($name === $_SERVER['REQUEST_URI']) {

            $this->_path = $name;
        }
    }

    /**
     * Checking class and if exists, setting _class property
     * 
     * @param string $name path name
     */ 
    private function checkClass($class) {

        if(file_exists('../app/controllers/' . $class . '.php') === true) {

            $this->_class = 'app\controllers\\' . $class;
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