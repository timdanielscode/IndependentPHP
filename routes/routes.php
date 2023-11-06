<?php

use core\routing\Route;

Route::set(['GET' => '/'], ['HomeController' => 'index']);
Route::set(['POST' => '/test'], ['HomeController' => 'data']);
