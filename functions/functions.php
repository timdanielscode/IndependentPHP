<?php
/**
 * Functions
 * 
 * @author Tim Daniëls
 */

/** 
 * @param string $path header
 * @return void
 */     
function redirect($path) {
    
    if($path) {
        header('location: '.$path);
    } 
}

/** 
 * @param string $name _POST|_GET
 * @return bool
 */     
function submitted($name) {

    if(!empty($name) && isset($_POST[$name]) || !empty($name) && isset($_GET[$name])) {
        
        return true;
    } 
}

/** 
 * @param string $name optional
 * @return global _POST
 */  
function post($name = null) {

    if(!empty($name) && isset($_POST[$name])) {

        $post = htmlspecialchars($_POST[$name]);
        return $post;
    } 
}

/** 
 * @param string $name optional
 * @return global _GET
 */  
function get($name) {

    if(!empty($name) && isset($_GET[$name])) {

        $get = htmlentities($_GET[$name], ENT_QUOTES, 'UTF-8');
        return $get;
    } 
}

/** 
 * @param string $path file
 * @return void
 */
function involve($path) {

    require_once $path;
}

