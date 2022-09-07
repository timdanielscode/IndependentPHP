<?php
/**
 * Middleware creating the middleware instances  
 * 
 * @author Tim Daniëls
 */

namespace core;

class Middleware {

    public static $middlewares;

    public function __construct($middlewares = null) {

        self::$middlewares = $middlewares;
    }

    /**
     * Creating instances of middlewares
     * 
     * @param mixed $middlewares string|array alias | alias & extra middleware value
     * @param object $func Route Request Response
     * @return object middleware
     */
    public static function run($middleware, $func) {

        $middlewares = self::$middlewares;

        foreach($middlewares as $key => $value) {
               
            if($key === $middleware) {
                    
                $class = 'middleware\\'.$value;
        
                if(class_exists($class)) { 
            
                    return new $class($func);
                }
        
            } else if(gettype($middleware) === 'array' && $key === array_keys($middleware)[0]) {
                    
                $middlewareValue = array_values($middleware);
                $class = 'middleware\\'.$value;
        
                if(class_exists($class)) { 
                        
                    return new $class($func, $middlewareValue[0]);
                }
        
            } else { return; }
        }
    }
}