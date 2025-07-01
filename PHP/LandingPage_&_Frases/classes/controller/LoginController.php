<?php


class LoginController {
    
    public function __construct() {
    }
    
    public function show() {
        if (isset($_SESSION['username'])) {
            header("Location: index.php");
            exit();
        }
        $vLogin = new LoginView();
        $vLogin->show();
    }
    
    public function form($params) {
        session_start();
        $errors = [];
        $frm_email = "";
        $frm_contrasena = "";
        
       
        $emailError = $this->verificarEmail($params["email"] ?? '');
        if ($emailError) {
            $errors["email"] = $emailError;
        } else {
            $frm_email = strtolower($this->sanitize_input($params["email"]));
        }
        
        
        $contrasenaError = $this->verificarContrasena($params["password"] ?? '');
        if ($contrasenaError) {
            $errors["contrasena"] = $contrasenaError;
        } else {
            $frm_contrasena = $this->sanitize_input($params["password"]);
        }
        
        
        $usuario = new UsuariModel();
        $usuario->__set("email", $frm_email);
        $usuario->__set("contrasena", $frm_contrasena);
        
        if (!empty($errors)) {
            $usuario->__set("errors", $errors);
            $vLogin = new LoginView();
            $vLogin->form($usuario);
        } else {
            $usuarioModel = new UsuariModel();
            $usuarioVerificado = $usuarioModel->verificarUsuario($frm_email, $frm_contrasena);
            
            if ($usuarioVerificado) {
                if (isset($usuarioVerificado['error'])) {
                    //Aqui se hace una verificacion de si el usuario tiene status 0 o no
                    $errors["login"] = $usuarioVerificado['error'];
                    $usuario->__set("errors", $errors);
                    $vLogin = new LoginView();
                    $vLogin->form($usuario);
                } else {
                    $_SESSION['username'] = $usuarioVerificado['email'];
                    header("Location: index.php");
                    exit();
                }
            } else {
                $errors["login"] = "Correo electrónico o contraseña incorrectos.";
                $usuario->__set("errors", $errors);
                $vLogin = new LoginView();
                $vLogin->form($usuario);
            }
        }
    }
    
    
    
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();  
        session_destroy(); 
        header("Location: index.php");  
        exit();
    }
    
    private function verificarEmail($email) {
        if (empty($email)) {
            return "El correo electrónico es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El formato del correo electrónico es inválido.";
        }
        return null;
    }
    
    private function verificarContrasena($password) {
        if (empty($password)) {
            return "La contraseña es obligatoria.";
        }
        return null;
    }
    
    public function sanitize_input($data) {
        $data = $data ?? '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    


    
}