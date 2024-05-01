<?php

/** 
 * To redirect to another page
 * 
 * @param string $path location path
 */     
function redirect($path) {
    
    header('location: '.$path);
}