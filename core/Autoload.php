<?php

/**
 * To 'auto' include classes to not include every class manually
 */ 
spl_autoload_register(function($class) {

    if(file_exists("../" . str_replace("\\", "/", $class) . '.php')) {

        include_once "../" . str_replace("\\", "/", $class) . '.php';
    } 
});