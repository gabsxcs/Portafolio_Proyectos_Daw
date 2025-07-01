<?php

class ContactoView extends View{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show() {
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"contacto-header\">";
        include "../inc/menuMain.php";
        echo "<div class=\"contacto-content\">
        <h1>Contacto</h1>
        <p>Envíanos un mensaje si deseas que nos pongamos en contacto</p>
        </div>";
        echo "</header>";
        echo "<main>";
        echo "<section class=\"contacto-section\">";
        echo "<div class=\"left-content\"><form action=\"\" method=\"post\">
        <h1>Formulari de contacte</h1><br/>
        <label for=\"name\">Tu nombre (*): </label>
            <input type=\"text\" id=\"nombre\" name=\"nombre\" minlength=\"4\" maxlength=\"40\" size=\"10\" /><br />
		<label for=\"email\">Correo electrónico (*): </label>
            <input type=\"text\" id=\"email\" name=\"email\" minlength=\"10\" maxlength=\"60\" size=\"10\" /><br />
		<label for=\"telefon\">Teléfono: </label>
            <input type=\"text\" id=\"telefono\" name=\"telefono\" minlength=\"9\" maxlength=\"14\" /> <br />
		<label for=\"asunto\">Asunto (*): </label>
            <input type=\"text\" id=\"asunto\" name=\"asunto\" minlength=\"5\" maxlength=\"60\" /> <br />
		<label for=\"mensaje\">Mensaje (*): </label><br />
				<textarea id=\"mensaje\" name=\"mensaje\" rows=\"8\" cols=\"80\"></textarea><br />
        <label>(*)Camp obligatori.</label><br />
            
		<button class=\"btn\" name=\"boto\" value=\"Envia\">Envia les dades</button>
		</form></div>";
        echo "</section></main></body></html>";
    }
    
    public function form(Contacto $contacte) {
        
        $errorNombre  = ($contacte instanceof Contacto && isset($contacte->errors["nombre"]))
        ? "class=\"error\" placeholder=\"{$contacte->__get("errors")["nombre"]}\""
        : " value=\"{$contacte->__get("nom")}\"";
        $errorMail = ($contacte instanceof Contacto && isset($contacte->errors["email"]))
        ? "class=\"error\" placeholder=\"{$contacte->__get("errors")["email"]}\""
        : " value=\"{$contacte->__get("email")}\"";
        $errorTelf = ($contacte instanceof Contacto && isset($contacte->errors["telefono"]))
        ? "class=\"error\" placeholder=\"{$contacte->__get("errors")["telefono"]}\""
        : " value=\"{$contacte->__get("telefon")}\"";
        $errorAsun = ($contacte instanceof Contacto && isset($contacte->errors["asunto"]))
        ? "class=\"error\" placeholder=\"{$contacte->__get("errors")["asunto"]}\""
        : " value=\"{$contacte->__get("assumpte")}\"";
        $errorMes = ($contacte instanceof Contacto && isset($contacte->errors["mensaje"]))
        ? "class=\"error\" placeholder=\"{$contacte->__get("errors")["mensaje"]}\""
        : "";
        $frm_mensaje = ($contacte instanceof Contacto) ? $contacte->__get("mensaje") : "";
        
        if (is_null($contacte->__get("errors"))) {
            $info = "<div class=\"mensajeExito\"><p>Dades enviades correctament.</p></div><br />";
        } else {
            $info ="<label>(*)Camp obligatori.</label><br />";
        }
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"contacto-header\">";
        include "../inc/menuMain.php";
        echo "<div class=\"contacto-content\">
        <h1>Contacto</h1>
        <p>Envíanos un mensaje si deseas que nos pongamos en contacto</p>
        </div>";
        echo "</header>";
        echo "<main>";
        echo "<section class=\"contacto-section\">";
        echo "<div class=\"left-content\"><form action=\"\" method=\"post\">
        <h1>Formulari de contacte</h1><br/>
        <label for=\"name\">Tu nombre (*): </label>
            <input type=\"text\" id=\"nombre\" name=\"nombre\" minlength=\"4\" maxlength=\"40\" size=\"10\" $errorNombre/><br />
		<label for=\"email\">Correo electrónico (*): </label>
            <input type=\"text\" id=\"email\" name=\"email\" minlength=\"10\" maxlength=\"60\" size=\"10\" $errorMail/><br />
		<label for=\"telefono\">Teléfono: </label>
            <input type=\"text\" id=\"telefono\" name=\"telefono\" minlength=\"9\" maxlength=\"14\" $errorTelf/> <br />
		<label for=\"asunto\">Asunto (*): </label>
            <input type=\"text\" id=\"asunto\" name=\"asunto\" minlength=\"5\" maxlength=\"60\" $errorAsun/> <br />
		<label for=\"mensaje\">Mensaje (*): </label><br />
				<textarea id=\"mensaje\" name=\"mensaje\" rows=\"8\" cols=\"80\" $errorMes>$frm_mensaje</textarea><br />
        $info
        
		<button class=\"btn\" name=\"boto\" value=\"Envia\">Envia les dades</button>
		</form></div>";
        echo "</section></main></body></html>";
    }
}




