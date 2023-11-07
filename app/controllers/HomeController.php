<?php

namespace app\controllers;

use database\DB;

class HomeController extends Controller {

    public function index() {

        $data['ids'] = [1,2,3,4,5];

        return $this->view("home/index", $data);
    }

    public function test($reqeust) {

        print_r($reqeust);
    }

    public function data($request) {

        print_r($request);
    }

    public function data2($request) {

        print_r($request);
    }
}