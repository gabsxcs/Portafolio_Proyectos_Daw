<?php

class InversionesController {
    private $model;
    private $view;
    
    public function __construct() {
        $this->model = new InversionesModel();
        $this->view = new InversionesView();
    }
    
    public function show() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?Login/show");
            exit;
        }
        
        $cotizacionesActuales = $this->model->getCotizaciones();
        
        if (isset($_GET['refresh'])) {
            $_SESSION['cotizacionesAnteriores'] = $_SESSION['cotizaciones'] ?? [];
            $_SESSION['cotizaciones'] = $cotizacionesActuales;
        }
        
        $cotizaciones = $_SESSION['cotizaciones'] ?? $cotizacionesActuales;
        $cotizacionesAnteriores = $_SESSION['cotizacionesAnteriores'] ?? [];
        
        $this->view->show($cotizaciones, $cotizacionesAnteriores);
    }
}


?>
