<?php

use core\routing\Route;
use core\http\Middleware;

Middleware::route('test', function() { 

    new Route(['GET' => '/'], ['HomeController' => 'index']);
});

new Route(['POST' => '/test/[id]'], ['HomeController' => 'data']);
new Route(['POST' => '/test'], ['HomeController' => 'data2']);
new Route(['GET' => '/test/[id]'], ['HomeController' => 'test']);