<?php

session_start(); 

class MantenimientoController{
    
    private $bd;
    
    public function __construct() {
    }
    
    
    public function show() {
        if (!isset($_SESSION['username'])) {
            header("Location: ?Login/show");
            exit();
        } else {
            $this->bd = new EventoModel();
            
            $query = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
            
            if (!empty($query)) {
                $eventos = $this->bd->buscar($query);
            } else {
                $eventos = $this->bd->getAll();
            }
            
            $vMantenimiento = new MantenimientoView();
            $vMantenimiento->show(['eventos' => $eventos, 'buscar' => $query]);
        }
    }
    
    public function eliminar() {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];  
        } 
        
        $this->bd = new EventoModel();
        $this->bd->delete($id);
        
        header("Location: ?Mantenimiento/show");
        
        exit(); 
    }
    
    
    
}

?>