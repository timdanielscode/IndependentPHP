<?php 
/**
 * Setting routes
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use core\http\Request;

class Route {

    private static $_type, $_class, $_method, $_path;
    private static $_request;


    private function __construct() {

        self::$_request = new Request();
    }

    /**
     * Setting route
     * 
     * @param array $type request type and path
     * @param array $class class and method name
     */ 
    public static function set($type, $class) {

        self::$_class = key($class);
        self::$_method = $class[key($class)];

        self::checkType($type);
    }

    /**
     * Checking request type method
     * 
     * @param array $type type of request and path
     */ 
    public static function checkType($type) {

        $route = new Route();

        if(key($type) === self::$_request->getMethod()) {

            self::$_type = $type;
            self::checkPath($type[key($type)]);
        }
    }

    /**
     * Checking request uri path
     * 
     * @param string $name path name
     * @param array $class array controller, method
     */ 
    private static function checkPath($path) {

        if($path === self::$_request->getUri()) {

            self::$_path = $path;
            self::checkClass(self::$_class);
        }
    }

    /**
     * Checking if class exists
     */ 
    private static function checkClass() {

        if(file_exists('../app/controllers/' . self::$_class . '.php') === true) {

            self::checkMethod();
        }
    }

    /**
     * Checking method exists
     */ 
    private static function checkMethod() {

        if(method_exists('app\controllers\\' . self::$_class, self::$_method)) {

            self::instance('app\controllers\\' . self::$_class, self::$_method);
        } 
    }

    /**
     * Create instance
     * 
     * @param string $class class name
     * @param string $method method name
     */
    private static function instance($class, $method) {

        $instance = new $class;
    
        if(self::$_request->getMethod() === 'POST') {

            return $instance->$method(self::$_request->get());
        }

        return $instance->$method();
    }
}