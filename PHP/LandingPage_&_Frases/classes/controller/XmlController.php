<?php

class XmlController {
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function show() {
        $usuarioAutenticado = isset($_SESSION['username']);
        
        $view = new XmlView();
        $view->show($usuarioAutenticado);
    }
    
    
  
    
    //Importar la informacion del xml a la base de datos
    public function importarXml() {
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?Login/show");
            exit;
        }
        
        try {;
            
            $xmlProcessor = new XmlModel();
            $xmlProcessor->crearTablas();
            $xmlProcessor->processXML("../Xml/frases.xml");
            
            header("Location: ?Phrases/show");
            exit();
        } catch (Exception $e) {
            echo "Error al importar: " . $e->getMessage();
        }
    }
    
    
    //Metodo para borrar todas las tablas
    public function borrarTablas() {
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?Login/show");
            exit;
        }
        
        try {
            $xmlProcessor = new XmlModel();
            $xmlProcessor->eliminarTablas();
        } catch (Exception $e) {
            throw new Exception("Error al borrar las tablas: " . $e->getMessage());
        }
    }
    
    
    
}
