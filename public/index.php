<?php 
/**
 * Run application
 */

require_once '../core/Autoload.php';
require_once '../functions/functions.php';
require_once '../config/config.php';

$app = new core\App();
$app->run();