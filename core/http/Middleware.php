<?php

namespace core\http;

use middleware\register\Middlewares;

class Middleware {

    private $_middlewares;
    private static $_routeMiddlewares;

    public function __construct() {

        $registeredMiddlewares = new Middlewares();

        $this->_middlewares = $registeredMiddlewares->middlewares;
        self::$_routeMiddlewares = $registeredMiddlewares->routeMiddlewares;

        $this->run();
    }

    /**
     * To run route based middleware to restrict routes
     * 
     * @param mixed $middlewareAlias string alias middleware | array alias middleware and extra value
     * @param object $func Closure Object
     * @return object middleware instances
     */
    public static function route($middlewareAlias, $func) {

        $middlewares = self::$_routeMiddlewares;

        foreach($middlewares as $key => $value) {

            if($middlewareAlias === $key) {

                $class = 'middleware\\'.$value;

                if(class_exists($class)) { 
            
                    return new $class($func);
                }

            } else if(gettype($middlewareAlias) === 'array' && $key === array_keys($middlewareAlias)[0]) {       

                foreach($middlewareAlias as $key => $value) {

                    $middlewareValue = array_values($middlewareAlias);

                    $class = 'middleware\\'.$middlewares[$key];

                    if(class_exists($class)) { 
                                    
                        return new $class($func, $middlewareValue[0]);
                    }
                }
            }
        } 
    }

    /**
     * To run 'non route' middleware to perform certain checks before running routes
     */
    public function run() {

        $middlewares = $this->_middlewares;

        foreach($middlewares as $key => $value) {

            $class = 'middleware\\'.$value;

            if(class_exists($class)) { 
            
                new $class();
            }
        }
    }
}