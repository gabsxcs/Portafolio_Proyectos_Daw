<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class RegistroView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show() {
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/HeadRegistro.php";
        echo "<body class=\"registroPagina\">";
        echo "<header class=\"registro-header\">";
        include "../inc/menuRegistro.php";
        echo "</header><main>";
        echo "<div class=\"formContenidor\">";
        echo "<form action=\"\" method=\"post\" id=\"formRegistro\" enctype=\"multipart/form-data\">";
        
        // Paso 1: Información Básica
        echo "<div class=\"formPaso activo\" id=\"paso1\">";
        echo "<h2>Información Básica</h2>";
        echo "<input type=\"text\" name=\"nombre\" placeholder=\"Nombre\">";
        echo "<input type=\"text\" name=\"apellido\" placeholder=\"Apellidos\">";
        echo "<input type=\"date\" name=\"fechaNacimiento\" placeholder=\"Fecha de Nacimiento\">";
        echo "<select name=\"genero\">";
        echo "  <option value=\"\">Seleccionar Género</option>";
        echo "  <option value=\"Masculino\">Masculino</option>";
        echo "  <option value=\"Femenino\">Femenino</option>";
        echo "  <option value=\"Otro\">Otro</option>";
        echo "</select>";
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(1)\">Siguiente</button>";
        echo "</div>";
        
        // Paso 2: Credenciales
        echo "<div class=\"formPaso\" id=\"paso2\">";
        echo "<h2>Credenciales</h2>";
        echo "<input type=\"email\" name=\"correo\" placeholder=\"Correo Electrónico\">";
        echo "<input type=\"password\" name=\"contraseña\" placeholder=\"Contraseña\">";
        echo "<input type=\"password\" name=\"contraseñaRepetida\" placeholder=\"Repetir Contraseña\">";
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(2)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(2)\">Atrás</button>";
        echo "</div>";
        
        // Paso 3: Documento de Identidad
        echo "<div class=\"formPaso\" id=\"paso3\">";
        echo "<h2>Documento de Identidad</h2>";
        echo "<select name=\"tipoDocumento\">";
        echo "  <option value=\"\">Tipo de Documento</option>";
        echo "  <option value=\"dni\">DNI</option>";
        echo "  <option value=\"nie\">NIE</option>";
        echo "  <option value=\"pasaporte\">Pasaporte</option>";
        echo "</select>";
        echo "<input type=\"text\" name=\"numeroDocumento\" placeholder=\"Número de Documento\">";
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(3)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(3)\">Atrás</button>";
        echo "</div>";
        
        // Paso 4: Detalles de Contacto
        echo "<div class=\"formPaso\" id=\"paso4\">";
        echo "<h2>Detalles de Contacto</h2>";
        echo "<input type=\"text\" name=\"direccion\" placeholder=\"Dirección (Opcional)\">";
        echo "<input type=\"text\" name=\"codigoPostal\" placeholder=\"Código Postal (Opcional)\">";
        echo "<input type=\"text\" name=\"provincia\" placeholder=\"Provincia (Opcional)\">";
        echo "<input type=\"tel\" name=\"telefono\" placeholder=\"Teléfono (Opcional)\">";
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(4)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(4)\">Atrás</button>";
        echo "</div>";
        
        // Paso 5: Subir Imagen
        echo "<div class=\"formPaso\" id=\"paso5\">";
        echo "<h2>Subir Imagen</h2>";
        echo "<p>(Máximo 2MB, formatos aceptados: JPG, PNG, GIF)</p>";
        echo "<input type=\"file\" name=\"imagen\" accept=\"image/jpeg, image/png, image/gif\">";
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(5)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(5)\">Atrás</button>";
        echo "</div>";
        
        // Paso 6: Verificación
        echo "<div class=\"formPaso\" id=\"paso6\">";
        echo "<h2>Verificación</h2>";
        echo "<div class=\"verificacionRobot\">";
        echo "<label><input type=\"checkbox\" id=\"robotCheckbox\" name=\"noEsUnRobot\" value=\"1\"> No soy un robot</label>";
        echo "<img src=\"../images/robot.png\" alt=\"Imagen de robot\" class=\"robotImg\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"botonEnviar\">Enviar</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(6)\">Atrás</button>";
        echo "</div>";
        
        echo "</form>";
        echo "</div>";
        echo "</main>";
        echo "<footer>";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    public function form(UsuariModel $usuario) {
        
        $errorNombre = isset($usuario->errors["nombre"])
        ? "<p class=\"error\">{$usuario->errors["nombre"]}</p>"
        : "";
        $nombreValor = htmlspecialchars($usuario->__get("nombre") ?? '');
        
        $errorApellido = isset($usuario->errors["apellido"])
        ? "<p class=\"error\">{$usuario->errors["apellido"]}</p>"
        : "";
        $apellidoValor = htmlspecialchars($usuario->__get("apellido") ?? '');
        
        $errorEdat = isset($usuario->errors["fechaNacimiento"])
        ? "<p class=\"error\">{$usuario->errors["fechaNacimiento"]}</p>"
        : "";
        $edatValor = htmlspecialchars($usuario->__get("fechaNacimiento") ?? '');
        
        $errorGenero = isset($usuario->errors["genero"])
        ? "<p class=\"error\">{$usuario->errors["genero"]}</p>"
        : "";
        
        $errorCorreo = isset($usuario->errors["correo"])
        ? "<p class=\"error\">{$usuario->errors["correo"]}</p>"
        : "";
        $correoValor = htmlspecialchars($usuario->__get("correo") ?? '');
        
        $errorContraseña = isset($usuario->errors["contraseña"])
        ? "<p class=\"error\">{$usuario->errors["contraseña"]}</p>"
        : "";
        
        $errorTipoDocumento = isset($usuario->errors["tipoDocumento"])
        ? "<p class=\"error\">{$usuario->errors["tipoDocumento"]}</p>"
        : "";
        $tipoDocumentoValor = htmlspecialchars($usuario->__get("tipoDocumento") ?? '');
        
        $numeroDocumentoValor = htmlspecialchars($usuario->__get("numeroDocumento") ?? '');
        
        $errorDireccion = isset($usuario->errors["direccion"])
        ? "<p class=\"error\">{$usuario->errors["direccion"]}</p>"
        : "";
        $direccionValor = htmlspecialchars($usuario->__get("direccion") ?? '');
        
        $errorCodigoPostal = isset($usuario->errors["codigoPostal"])
        ? "<p class=\"error\">{$usuario->errors["codigoPostal"]}</p>"
        : "";
        $codigoPostalValor = htmlspecialchars($usuario->__get("codigoPostal") ?? '');
        
        $errorProvincia = isset($usuario->errors["provincia"])
        ? "<p class=\"error\">{$usuario->errors["provincia"]}</p>"
        : "";
        $provinciaValor = htmlspecialchars($usuario->__get("provincia") ?? '');
        
        $errorTelefono = isset($usuario->errors["telefono"])
        ? "<p class=\"error\">{$usuario->errors["telefono"]}</p>"
        : "";
        $telefonoValor = htmlspecialchars($usuario->__get("telefono") ?? '');
        
        
        $errorImagen = isset($usuario->errors["fotoUsuario"])
        ? "<p class=\"error\">{$usuario->errors["fotoUsuario"]}</p>"
        : "";
        
        $errorContraseñaRepetida = isset($usuario->errors["contraseñaRepetida"])
        ? "<p class=\"error\">{$usuario->errors["contraseñaRepetida"]}</p>"
        : "";
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/HeadRegistro.php";
        echo "<body class=\"registroPagina\">";
        echo "<header class=\"registro-header\">";
        include "../inc/menuRegistro.php";
        echo "</header><main>";
        echo "<div class=\"formContenidor\">";
        echo "<form action=\"\" method=\"post\" id=\"formRegistro\" enctype=\"multipart/form-data\">";
        echo "<div class=\"formPaso activo\" id=\"formularioCompleto\">";
        
        echo "<div class=\"formPaso activo\" id=\"paso1\">";
        echo "<h2>Información Básica</h2>";
        echo "<input type=\"text\" name=\"nombre\" placeholder=\"Nombre\" value=\"$nombreValor\">";
        echo $errorNombre;
        echo "<input type=\"text\" name=\"apellido\" placeholder=\"Apellidos\" value=\"$apellidoValor\">";
        echo $errorApellido;
        echo "<input type=\"date\" name=\"fechaNacimiento\" placeholder=\"Fecha de Nacimiento\" value=\"$edatValor\">"; 
        echo $errorEdat;
        echo "<select name=\"genero\">";
        echo "  <option value=\"\">Seleccionar Género</option>";
        echo "  <option value=\"Masculino\">Masculino</option>";
        echo "  <option value=\"Femenino\">Femenino</option>";
        echo "  <option value=\"Otro\">Otro</option>";
        echo "</select>";
        echo $errorGenero;
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(1)\">Siguiente</button>";
        echo "</div>";
        
        echo "<div class=\"formPaso\" id=\"paso2\">";
        echo "<h2>Credenciales</h2>";
        echo "<input type=\"email\" name=\"correo\" placeholder=\"Correo Electrónico\" value=\"$correoValor\">";
        echo $errorCorreo;
        echo "<input type=\"password\" name=\"contraseña\" placeholder=\"Contraseña\">";
        echo $errorContraseña;
        echo "<input type=\"password\" name=\"contraseñaRepetida\" placeholder=\"Repetir Contraseña\" value=\"\">";
        echo $errorContraseñaRepetida;
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(2)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(2)\">Atrás</button>";
        echo "</div>";
        
        echo "<div class=\"formPaso\" id=\"paso3\">";
        echo "<h2>Documento de Identidad</h2>";
        echo "<select name=\"tipoDocumento\">";
        echo "    <option value=\"\">Tipo de Documento</option>";
        echo "    <option value=\"dni\" " . ($tipoDocumentoValor == "dni" ? "selected" : "") . ">DNI</option>";
        echo "    <option value=\"nie\" " . ($tipoDocumentoValor == "nie" ? "selected" : "") . ">NIE</option>";
        echo "    <option value=\"pasaporte\" " . ($tipoDocumentoValor == "pasaporte" ? "selected" : "") . ">Pasaporte</option>";
        echo "</select>";
        echo $errorTipoDocumento;
        echo "<input type=\"text\" name=\"numeroDocumento\" placeholder=\"Número de Documento\" value=\"$numeroDocumentoValor\">";
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(3)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(3)\">Atrás</button>";
        echo "</div>";
        
        
        echo "<div class=\"formPaso\" id=\"paso4\">";
        echo "<h2>Detalles de Contacto</h2>";
        echo "<input type=\"text\" name=\"direccion\" placeholder=\"Dirección (Opcional)\" value=\"$direccionValor\">";
        echo $errorDireccion;
        echo "<input type=\"text\" name=\"codigoPostal\" placeholder=\"Código Postal (Opcional)\" value=\"$codigoPostalValor\">";
        echo $errorCodigoPostal;
        echo "<input type=\"text\" name=\"provincia\" placeholder=\"Provincia (Opcional)\" value=\"$provinciaValor\">";
        echo $errorProvincia;
        echo "<input type=\"tel\" name=\"telefono\" placeholder=\"Teléfono (Opcional)\" value=\"$telefonoValor\">";
        echo $errorTelefono;
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(4)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(4)\">Atrás</button>";
        echo "</div>";
        
        echo "<div class=\"formPaso\" id=\"paso5\">";
        echo "<h2>Subir Imagen</h2>";
        echo "<p>(Máximo 2MB, formatos aceptados: JPG, PNG, GIF)</p>";
        echo "<input type=\"file\" name=\"imagen\" accept=\"image/jpeg, image/png, image/gif\">";
        echo $errorImagen;
        echo "<button type=\"button\" class=\"botonSiguiente\" onclick=\"nextStep(5)\">Siguiente</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(5)\">Atrás</button>";
        echo "</div>";
        
        echo "<div class=\"formPaso\" id=\"paso6\">";
        echo "<h2>Verificación</h2>";
        echo "<div class=\"verificacionRobot\">";
        echo "<label><input type=\"checkbox\" id=\"robotCheckbox\" name=\"noEsUnRobot\" value=\"1\"> No soy un robot</label>";
        echo "<img src=\"../images/robot.png\" alt=\"Imagen de robot\" class=\"robotImg\">";
        echo "</div>";
        echo "<button type=\"submit\" class=\"botonEnviar\">Enviar</button>";
        echo "<button type=\"button\" class=\"botonAtras\" onclick=\"prevStep(6)\">Atrás</button>";
        echo "</div>";
        
        echo "</form>";
        echo "</div>";
        echo "</main>";
        echo "<footer>";
        echo "</footer>";
        echo "</body></html>";
    }
}

?>
