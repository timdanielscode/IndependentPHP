<?php

namespace app\controllers;

use database\DB;

class HomeController extends Controller {

    public function index() {

        $data['ids'] = [1,2,3,4,5];

        return $this->view("home/index", $data);
    }

    public function data($request) {

        print_r($request);
    }
}