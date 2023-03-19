<?php
/**
 * Application
 * 
 * @author Tim Daniëls
 */

namespace core;

use core\http\Request;
use core\http\Response;
use core\routing\Route;

class App {

    public $route;
    public $request;
    public $response;

    /**
     * Declaring Request, Response and Route
     * 
     * @return void
     */    
    public function __construct() {

        $this->request = new Request();
        $this->response = new Response();
        $this->route = new Route($this->request, $this->response);
    }

    /**
     * Setting/registering middlewares
     * 
     * @param array $middlewares alias & filename 
     * @return object Middleware
     */
    public function middleware($middlewares = null) {

        new Middleware($middlewares);
    }

    /**
     * @return void 
     */    
    public function run() {

        require_once '../routes/routes.php';
        $this->route->uriNotFound(404);
    }
}