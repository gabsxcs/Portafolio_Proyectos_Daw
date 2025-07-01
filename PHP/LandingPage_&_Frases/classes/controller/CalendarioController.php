<?php

class CalendarioController extends Controller{
    
    private $eventoModel; 
    
    public function __construct() {}
    
    public function show($params) {
        $this->eventoModel = new EventoModel();
        
        $mes = isset($params[0]) ? (int)$params[0] : (int)date('m');
        $año = isset($params[1]) ? (int)$params[1] : (int)date('Y');
        
        if ($mes < 1 || $mes > 12) {
            $mes = (int)date('m');
        }
        
        $fechaInicio = "$año-$mes-01 00:00:00";
        $fechaFin = "$año-$mes-31 23:59:59";
        $eventos = $this->eventoModel->getBetweenDates($fechaInicio, $fechaFin);
        
        $viewParams = [
            'año' => $año,
            'mes' => $mes,
            'eventos' => $eventos, 
        ];
        
        $vCalendario = new CalendarioView();
        $vCalendario->show($viewParams);
    }
    
}

