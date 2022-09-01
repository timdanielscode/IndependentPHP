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
     * @return void
     */
    public function __construct($middlewares) {

        foreach($middlewares as $key => $middleware) {

            $class = 'middleware\\'.$middleware;
            new $class;
        }
    }
}