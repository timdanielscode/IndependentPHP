<?php

namespace app\controllers;

use database\DB;

class HomeController extends Controller {

    public function index() {

        echo 'index method vanuit controller';

        return $this->view("home/index");
    }

    public function data($request) {

        print_r($request);
    }
}