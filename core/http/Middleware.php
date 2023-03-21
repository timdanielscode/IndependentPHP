<?php
/**
 * Middleware
 * 
 * @author Tim Daniëls
 */

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
     * Creating instances of route based middlewares
     * 
     * @param mixed $middlewareAlias alias middleware | alias middleware and extra value
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
     * Creating instances of middlewares
     * 
     * @return void 
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