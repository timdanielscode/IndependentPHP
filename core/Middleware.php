<?php
/**
 * Middleware creating the middleware instances  
 * 
 * @author Tim Daniëls
 */

namespace core;

class Middleware {

    public static $middlewares = [];

    /**
     * Declaring middlewares
     * 
     * @param array $middleware middlewares
     * @return object middleware
     */    
    public function __construct($middlewares = null) {

        self::$middlewares = $middlewares;
    }

    /**
     * Creating instances of route middlewares
     * 
     * @param mixed $middlewares string|array alias | alias & extra middleware value
     * @param object $func Route Request Response
     * @return object middleware
     */
    public static function run($middleware, $func) {

        $middlewares = self::$middlewares;

        foreach($middlewares as $key => $value) {

            if($middleware === $key) {

                $class = 'middleware\\'.$value;

                if(class_exists($class)) { 
            
                    return new $class($func);
                }

            } else if(gettype($middleware) === 'array' && $key === array_keys($middleware)[0]) {       

                foreach($middleware as $key => $value) {

                    $middlewareValue = array_values($middleware);

                    $class = 'middleware\\'.$middlewares[$key];

                    if(class_exists($class)) { 
                                    
                        return new $class($func, $middlewareValue[0]);
                    }
                }
            }
        } 
    }
}