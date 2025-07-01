<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define("__ROOT__", __DIR__ . "/../");

require_once __ROOT__ . '/classes/core/Autoloader.php';

require_once __DIR__ . '/../classes/view/ErrorView.php';

try {
    $autoloader = new Autoloader();
    $autoloader->register();
    
    FrontController::dispatch();
} catch (Exception $e) {
    ErrorView::show($e);
}



?>
