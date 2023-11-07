<?php
                
namespace middleware;
                
class TestMiddleware {
                
    public function __construct($run) {    
        
        return $run();
    }          
}  