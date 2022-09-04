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
     * @param array $middlewares class names
     * @param object Route Request Response
     * @return void
     */
    public function __construct($middleware, $func) {

        $class = 'middleware\\'.$middleware;

        if(class_exists($class)) { 

            new $class($func);
        }
    }
}