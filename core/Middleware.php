<?php
/**
 * Middleware
 * 
 * @author Tim Daniëls
 */

namespace core;

class Middleware {

    /**
     * Creating instances of middlewares
     * 
     * @param mixed $middleware class name | and extra value
     * @param object $func closure function Route Request Response
     * @return void
     */
    public function __construct($middleware, $func) {

        if(gettype($middleware) === 'string') {

            $class = 'middleware\\'.$middleware;

            if(class_exists($class)) { 
    
                new $class($func);
            }

        } else if(gettype($middleware) === 'array') {

            $className = array_keys($middleware);
            $middlewareValue = array_values($middleware);
            $class = 'middleware\\'.$className[0];

            if(class_exists($class)) { 
    
                new $class($func, $middlewareValue[0]);
            }

        } else { return; }
    }
}