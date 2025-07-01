<?php

class ContactoController extends Controller{
    
    public function __construct() {}
    
    public function show() {
        $vContacta = new ContactoView();
        $vContacta->show();
    }
    
    public function form($params) {
        $errors = [];
        $frm_nom = "";
        $frm_email = "";
        $frm_assumpte = "";
        $frm_missatge = "";
        $frm_telefon = "";
        
        $nombreError = $this->verificarNombre($params["nombre"] ?? '');
        if ($nombreError) {
            $errors["nombre"] = $nombreError;
        } else {
            $frm_nom = $this->sanitize_input($params["nombre"]);
        }
        
        $correoError = $this->verificarCorreo($params["email"] ?? '');
        if ($correoError) {
            $errors["email"] = $correoError;
        } else {
            $frm_email = strtolower($this->sanitize_input($params["email"]));
        }
        
        if (isset($params["telefono"])) {
            $telefonoError = $this->verificarTelefono($params["telefono"]);
            if ($telefonoError) {
                $errors["telefono"] = $telefonoError;
            } else {
                $frm_telefon = $this->sanitize_input($params["telefono"]);
            }
        }
        
        
        if (empty($params["asunto"])) {
            $errors["asunto"] = "El asunto es obligatorio";
        } else {
            $frm_assumpte = $this->sanitize_input($params["asunto"]);
        }
        
        
        if (empty($params["mensaje"])) {
            $errors["mensaje"] = "El mensaje es obligatorio";
        } else {
            $mensajeError = $this->verificarMensaje($params["mensaje"]);
            if ($mensajeError) {
                $errors["mensaje"] = $mensajeError;
            } else {
                $frm_missatge = $this->sanitize_input($params["mensaje"]);
            }
        }
        
        
        $nouContacte = new Contacto($frm_nom, $frm_email, $frm_assumpte, $frm_missatge);
        
        
        if (!empty($frm_telefon)) {
            $nouContacte->__set("telefono", $frm_telefon);
        }
        
        if (!empty($errors)) {
            $nouContacte->__set("errors", $errors);
        } else {
            $mContacte = new ContactoModel();
            $mContacte->set($nouContacte);
            $nouContacte = new Contacto("", "", "", "");
        }
        
        $vContacta = new ContactoView();
        $vContacta->form($nouContacte);
    }
    
    
    public function sanitize_input($data) {
        $data = $data ?? '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    
    public function verificarNombre($nombre) {
        $nombre = $this->sanitize_input($nombre);
        if (empty($nombre)) {
            return "El nombre es obligatorio.";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            return "El nombre solo debe tener letras. No números ni caracteres especiales.";
        }
        return '';
    }
    
    public function verificarApellido($apellidos) {
        $apellidos = $this->sanitize_input($apellidos);
        if (!empty($apellidos)) {
            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellidos)) {
                return "Los apellidos solo deben tener letras. No números ni caracteres especiales.";
            }
        }
        return '';
    }
    
    public function verificarCorreo($correo) {
        $correo = $this->sanitize_input($correo);
        if (empty($correo)) {
            return "El correo es obligatorio.";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return "Correo no válido.";
        }
        return '';
    }
    
    public function verificarTelefono($telefono) {
        $telefono = $this->sanitize_input($telefono);
        if (!empty($telefono)) {
            if (!ctype_digit($telefono) || strlen($telefono) != 9) {
                return "El teléfono debe tener 9 dígitos. No letras ni caracteres especiales.";
            }
        }
        return '';
    }
    
    public function verificarMensaje($mensaje) {
        $mensaje = $this->sanitize_input($mensaje);
        if (!empty($mensaje) && strlen($mensaje) > 280) {
            return "El mensaje no puede exceder de 280 caracteres.";
        }
        return '';
    }
}

?>
