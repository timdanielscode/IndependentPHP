<?php
/**
 * Middleware creating the middleware instances  
 * 
 * @author Tim Daniëls
 */

namespace core;

class Middleware {

    public static $middleware = [];
    public static $routeMiddleware = [];

    /**
     * Creating instances of middlewares
     * 
     * @param array $middleware middlewares
     * @param array $routeMiddleware route middlewares
     * @return object middleware
     */    
    public function __construct($middleware, $routeMiddleware) {

        self::$middleware = $middleware;
        self::$routeMiddleware = $routeMiddleware;

        if(!empty(self::$middleware) && self::$middleware !== null) {

            foreach(self::$middleware as $key => $value) {

                $class = 'middleware\\'.$value;
                if(class_exists($class)) { 
                    
                    new $class();
                }
            }
        }
    }

    /**
     * Creating instances of route middlewares
     * 
     * @param mixed $middlewares string|array alias | alias & extra middleware value
     * @param object $func Route Request Response
     * @return object middleware
     */
    public static function run($middleware, $func) {

        $routeMiddleware = self::$routeMiddleware;

        foreach($routeMiddleware as $key => $value) {

            if($middleware === $key) {

                $class = 'middleware\\'.$value;
            
                if(class_exists($class)) { 
            
                    return new $class($func);
                }

            } else if(gettype($middleware) === 'array' && $key === array_keys($middleware)[0]) {       

                foreach($middleware as $key => $value) {

                    $middlewareValue = array_values($middleware);
                    $class = 'middleware\\'.$routeMiddleware[$key];

                    if(class_exists($class)) { 
                                    
                        return new $class($func, $middlewareValue[0]);
                    }
                }
            }
        } 
    }
}