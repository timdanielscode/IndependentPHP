<?php
/**
 * Middlewares
 * 
 * Register here both middleware and route middleware inside the properties below
 * Middlewares (not route based) will run before the application starts
 * The route middleware will run in between routing and can be used to restrict routes
 * 
 * @author Tim Daniëls
 */

namespace middleware\register;

class Middlewares {

    public $middlewares = [

        "test"      =>      "TestMiddleware2",
    
    ];

    public $routeMiddlewares = [

        "test"     =>      "TestMiddleware",

    ];
}

