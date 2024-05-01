<?php

namespace core;

use core\Session;

class Csrf {

    /**
     * To set a csrf token value
     */
    public static function token() {

        if(Session::exists('csrf') === false) {

            Session::set('csrf', bin2hex(random_bytes(32)));
        }

        echo Session::get('csrf');
    }

    /**
     * To get setted csrf token value
     */
    public static function get() {

        return Session::get('csrf');
    }
}