<?php

namespace app\controllers;

use database\DB;

class HomeController extends Controller {

    public function index() {

        return $this->view("home/index");
    }
}