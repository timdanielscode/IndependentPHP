<?php 
/**
 * Run application
 * 
 * @author Tim Daniëls
 */

require_once '../core/Autoload.php';
require_once '../functions/functions.php';
require_once '../config/config.php';

use core\App;

$app = new App();

/**
 * Register Middlewares
 * 
 * In routes.php
 * 
 * Route::middleware("alias")->run(function() { 
 * 
 *    Your restricted route
 * });
 * 
 * (Optional)
 * 
 * Insert extra value to middleware 
 * 
 * Route::middleware(["alias" => "value"])->run(function() { 
 * 
 *    Your restricted route
 * });
 * 
 */  
$app->middleware(
    
    [
        // 'alias' => 'middleware',
    ]
);

$app->run();


