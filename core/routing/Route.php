<?php 
/**
 * Setting routes
 * 
 * @author Tim Daniëls
 */
namespace core\routing;

class Route {

    public static function set($type) {

        $routing = new Routing();
        return $routing->type($type);
    }
}