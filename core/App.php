<?php
/**
 * Application
 * 
 * @author Tim Daniëls
 */

namespace core;

use core\http\Middleware;
use core\http\Response;

class App {
 
    public function __construct() {

        $middleware = new Middleware();
    }
  
    public function run() {

        require_once '../routes/routes.php';

        $response = new Response();
        $response->check(404);
    }
}