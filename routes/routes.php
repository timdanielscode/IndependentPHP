<?php

use core\routing\Route;

new Route(['/'], ['HomeController' => 'index']);
new Route(['/test'], ['HomeController' => 'data']);
