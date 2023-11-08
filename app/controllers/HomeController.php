<?php

namespace app\controllers;

class HomeController extends Controller {

    public function index() {

        return $this->view("home/index");
    }
}