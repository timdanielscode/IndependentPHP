<?php 
/**
 * Run application
 * 
 * @author Tim Daniëls
 */

require_once '../core/Autoload.php';
require_once '../functions/functions.php';
require_once '../config/config.php';

use core\App;

$app = new App();
$app->run();


