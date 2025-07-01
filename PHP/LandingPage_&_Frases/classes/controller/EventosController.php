<?php
session_start();

class EventosController {
    
    public function __construct() {
    }
    
    
    public function show() {
        
        $vEventos = new EventosView();
        $vEventos->show();
    }
    
    public function form($params) {
        $errors = [];
        $frm_nombre = "";
        $frm_descripcion = "";
        $frm_inicio = "";
        $frm_fin = "";
        $frm_todoDia = false;
        $frm_etiqueta = '';
        
        if (!isset($_SESSION['username'])) {
            
            $errors["sesion"] = "Debes iniciar sesión para guardar un evento";
            $vEvento = new EventosView();
            $nuevoEvento = new Evento("", "", "", "", false, "");
            $nuevoEvento->__set("errors", $errors);
            $vEvento->form($nuevoEvento);
            return; 
            
        } else {
            if (empty($params["nombre"])) {
                $errors["nombre"] = "El nombre del evento es obligatorio.";
            } else {
                $frm_nombre = $this->sanitize_input($params["nombre"]);
            }
            
            if (empty($params["descripcion"])) {
                $errors["descripcion"] = "La descripción del evento es obligatoria.";
            } else {
                $frm_descripcion = $this->sanitize_input($params["descripcion"]);
            }
            
            
            if (empty($params["inicio"])) {
                $errors["inicio"] = "La fecha y hora de inicio es obligatoria.";
            } else {
                $frm_inicio = $params["inicio"];
            }
            
            
            if (empty($params["fin"])) {
                $errors["fin"] = "La fecha y hora de fin es obligatoria.";
            } else {
                $frm_fin = $params["fin"];
            }
            
            if (!empty($frm_inicio) && !empty($frm_fin) && !$this->verificarOrdenFechas($frm_inicio, $frm_fin)) {
                $errors["inicio"] = "La fecha de inicio debe ser antes que la fecha de fin.";
                $errors["fin"] = "La fecha de fin debe ser después de la fecha de inicio.";
            }
            
            if (isset($params["diaCompleto"])) {
                $frm_todoDia = true;
                list($frm_inicio, $frm_fin) = $this->ajustarTodoDia($frm_inicio);
            }
            
            if (isset($params["etiqueta"])) {
                $frm_etiqueta = $params["etiqueta"];
            }
            
            
            if (empty($errors)) {
                $eventoModel = new EventoModel();
                $eventoModel->create($frm_nombre, $frm_descripcion, $frm_inicio, $frm_fin, $frm_etiqueta);
                header("Location: ?Calendario/show");
                exit;
            }
        }
        
        $vEvento = new EventosView();
        $nuevoEvento = new Evento($frm_nombre, $frm_descripcion, $frm_inicio, $frm_fin, $frm_todoDia, $frm_etiqueta);
        $nuevoEvento->__set("errors", $errors);
        $vEvento->form($nuevoEvento);
        
    }
    
    
    
    public function sanitize_input($data) {
        $data = $data ?? '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    
    private function verificarOrdenFechas($inicio, $fin) {
        return strtotime($inicio) < strtotime($fin);
    }
    
    private function ajustarTodoDia($fecha) {
        $inicio = date("Y-m-d", strtotime($fecha)) . " 00:01";
        $fin = date("Y-m-d", strtotime($fecha)) . " 23:59";
        return [$inicio, $fin];
    }
}

?>