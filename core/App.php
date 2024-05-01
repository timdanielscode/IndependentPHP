<?php

namespace core;

use core\http\Middleware;
use core\http\Response;

class App {
 
    public function __construct() {

        $middleware = new Middleware();
    }
  
    /** 
     * To include the routes file to 'run the application'
     */  
    public function run() {

        require_once '../routes/routes.php';

        $response = new Response();
        $response->check(404);
    }
}