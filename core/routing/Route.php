<?php 
/**
 * Route
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

use app\controllers\http\ResponseController;
use core\http\Request;
use core\http\Response;

class Route extends Router {

    private static $_response, $_request, $_routeKeys, $_middleware;

    /**
     * Declaring Request & Response
     * 
     * @return void 
     */
    public function __construct(Request $request, Response $response) {

        self::$_request = $request;
        self::$_request->get();
        self::$_response = $response;
    } 

    /**
     * Setting route keys
     * 
     * @param string $keys optional
     * @return void
     */
    public static function setRouteKeys($keys = null) {
        
        if($keys) {
            self::$_routeKeys = $keys;
        }
    }

    /**
     * Setting route path with type of request method get 
     * 
     * @param string $path route
     * @return object Router Request Response 
     */
    public static function get($path) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {

           return $route->getRequest($path, self::$_routeKeys);
        } else {
            return $route;
        }
    }

    /**
     * Setting route path with type of request method post
     * 
     * @param string $path route
     * @return object Router Request Response
     */
    public static function post($path) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'POST') {

           return $route->postRequest($path, self::$_routeKeys);
        } else {
            return $route;
        }
    }

    /**
     * Setting crud route paths for request method type of get
     * 
     * @param string $path route
     * @param string $routeKey preffered route key
     * @return object Router Request Response
     */
    public static function crud($path, $routeKey) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {

            return $route->getRequestCrud($path, $routeKey, self::$_routeKeys);
        } else {
            return $route;
        }
    }

    /**
     * Setting route path and view with type of request method get 
     * 
     * @param string $path route
     * @param string $view path
     * @return object Router Request Response
     */    
    public static function view($path, $view) {

        $route = new Router(self::$_request, self::$_response);
        if(self::$_request->getMethod() === 'GET') {

           return $route->handleView($path, $view, self::$_routeKeys);
        } else {
            return $route;
        }
    }

    /**
     * Creating Router instance for setting up middlewares
     * 
     * @return object Route Request Response
    */
    public static function middleware($middleware) {

        if(!empty($middleware) && $middleware !== null) {
   
            self::$_middleware = $middleware;

            $route = new Router(self::$_request, self::$_response, self::$_middleware);
            return $route;
        } 
    }

    /**
     * Handling 404 status code
     * 
     * @param mixed int|string $code
     * @return void
    */
    public function uriNotFound($code) {

        if(empty($this->_path)) {

            self::$_response->set(404);
            $controller = new ResponseController();
            $controller->pageNotFound();
        } 
    }
}