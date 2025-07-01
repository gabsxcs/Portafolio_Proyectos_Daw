<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/View.php';

class LoginView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show() {
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"index-header\">";
        include "../inc/menuLogIn.php";
        echo "<div id=\"loginForm\">";
        echo "    <h2>Iniciar Sesión</h2>";
        echo "<form method=\"POST\" action=\"\">";
        echo "    <label for=\"email\">Correo Electrónico: </label>";
        echo "    <input type=\"email\" id=\"email\" name=\"email\" required>";
        echo "    <label for=\"password\">Contraseña:</label>";
        echo "    <input type=\"password\" id=\"password\" name=\"password\" required>";
        echo "    <div id=\"buttonLogin\">";
        echo "        <button type=\"submit\" class=\"loginButtons\" id=\"loginButton\">Iniciar Sesión</button>";
        echo "        <button type=\"button\" onclick=\"location.href='index.php?Registro/show';\" class=\"loginButtons\" id=\"registroButton\">Registrarse</button>";
        echo "    </div>";
        echo "</form>";
        echo "</div>";
        echo "</header>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    public function form(UsuariModel $usuario) {
        
        $errorEmail = isset($usuario->errors["email"])
        ? "class=\"error\" placeholder=\"{$usuario->errors["email"]}\""
        : "value=\"" . htmlspecialchars($usuario->__get("email") ?? '') . "\"";
        $errorPassword = isset($usuario->errors["contrasena"])
        ? "class=\"error\" placeholder=\"{$usuario->errors["contrasena"]}\""
        : "";
        $loginError = isset($usuario->errors["login"]) ? "<p class=\"error-message\">{$usuario->errors['login']}</p>" : "";
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"index-header\">";
        include "../inc/menuLogIn.php";
        echo "<div id=\"loginForm\">";
        echo "    <h2>Iniciar Sesión</h2>";
        
        echo $loginError;
        
        echo "<form method=\"POST\" action=\"\">";
        echo "    <label for=\"email\">Correo Electrónico: </label>";
        echo "    <input type=\"email\" id=\"email\" name=\"email\" $errorEmail>";  
        echo "    <label for=\"password\">Contraseña:</label>";
        echo "    <input type=\"password\" id=\"password\" name=\"password\" $errorPassword>";  
        echo "    <div id=\"buttonLogin\">";
        echo "        <button type=\"submit\" class=\"loginButtons\" id=\"loginButton\">Iniciar Sesión</button>";
        echo "        <button type=\"button\" onclick=\"location.href='index.php?Registro/show';\" class=\"loginButtons\" id=\"registroButton\">Registrarse</button>";
        echo "    </div>";
        echo "</form>";
        echo "</div>";
        echo "</header>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    
}
