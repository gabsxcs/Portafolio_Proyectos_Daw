<?php
use Frases\Controller\FrontController;
use Frases\View\ErrorView;

ini_set('display_errors', 1);
error_reporting(E_ALL);

define("__ROOT__", __DIR__ . "/");

$cr = include("config/cli-config.php");

//desde aqui le paso el entity manager a http para que se lo pase a los contralodres cada que se usen
\Frases\Core\Http::setEntityManager($entityManager);

require_once __DIR__ . '/src/View/ErrorView.php';

try {
    FrontController::dispatch();
} catch (Exception $e) {
    ErrorView::show($e);
}

