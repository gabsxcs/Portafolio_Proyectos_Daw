<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class RegistroController {
    
    public function __construct() {}
    
    public function show() {
        $vRegistro = new RegistroView();
        $vRegistro->show();
    }
    
    public function form($params) {
        $errors = [];
        
        $frm_nombre = isset($_POST['nombre']) ? $this->sanitize_input($_POST['nombre']) : '';
        $frm_apellido = isset($_POST['apellido']) ? $this->sanitize_input($_POST['apellido']) : '';
        $frm_fechaNacimiento = isset($_POST['fechaNacimiento']) ? $this->sanitize_input($_POST['fechaNacimiento']) : '';
        $frm_genero = isset($_POST['genero']) ? $this->sanitize_input($_POST['genero']) : '';
        $frm_correo = isset($_POST['correo']) ? $this->sanitize_input($_POST['correo']) : '';
        $frm_contrasena = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';
        $frm_contrasenaRepetida = isset($_POST['contraseñaRepetida']) ? $_POST['contraseñaRepetida'] : '';
        $frm_tipoDocumento = isset($_POST['tipoDocumento']) ? $this->sanitize_input($_POST['tipoDocumento']) : '';
        $frm_numeroDocumento = isset($_POST['numeroDocumento']) ? $this->sanitize_input($_POST['numeroDocumento']) : '';
        $frm_direccion = isset($_POST['direccion']) ? $this->sanitize_input($_POST['direccion']) : null;
        $frm_codigoPostal = isset($_POST['codigoPostal']) ? $this->sanitize_input($_POST['codigoPostal']) : null;
        $frm_provincia = isset($_POST['provincia']) ? $this->sanitize_input($_POST['provincia']) : null;
        $frm_telefono = isset($_POST['telefono']) ? $this->sanitize_input($_POST['telefono']) : null;
        $frm_fotoUsuario = isset($_FILES['imagen']) ? $_FILES['imagen'] : '';
        $frm_noEsUnRobot = isset($_POST['noEsUnRobot']) ? $_POST['noEsUnRobot'] : false;
        
        
        $errors['nombre'] = $this->verificarNombre($frm_nombre);
        $errors['apellido'] = $this->verificarApellido($frm_apellido);
        $errors['fechaNacimiento'] = $this->verificarEdad($frm_fechaNacimiento);
        $errors['genero'] = $this->verificarGenero($frm_genero);
        $errors['correo'] = $this->verificarCorreo($frm_correo);
        $errors['contraseña'] = $this->verificarContrasena($frm_contrasena, $frm_contrasenaRepetida);
        $errors['tipoDocumento'] = $this->verificarDocumento($frm_tipoDocumento, $frm_numeroDocumento);
        $rutaImagen = '';
        $errors['direccion'] = $this->verificarDireccion($frm_direccion);
        $errors['codigoPostal'] = $this->verificarCodigoPostal($frm_codigoPostal);
        $errors['provincia'] = $this->verificarProvincia($frm_provincia);
        $errors['telefono'] = $this->verificarTelefono($frm_telefono);
        $errors['fotoUsuario'] = $this->verificarImagen($frm_fotoUsuario, $rutaImagen);
        $errors['noEsUnRobot'] = $this->verificarNoEsUnRobot($frm_noEsUnRobot);
        
        
        if (empty(array_filter($errors))) {
            $contrasenaHash = password_hash($frm_contrasena, PASSWORD_DEFAULT);
            $usuarioModel = new UsuariModel();
            
            $usuarioData = [
                'nombre' => $frm_nombre,
                'apellido' => $frm_apellido,
                'fechaNacimiento' => $frm_fechaNacimiento,
                'genero' => $frm_genero,
                'email' => $frm_correo,
                'contrasena' => $contrasenaHash,
                'tipoDocumento' => $frm_tipoDocumento, 
                'numeroDocumento' => $frm_numeroDocumento,
                'direccion' => $frm_direccion,
                'codigoPostal' => $frm_codigoPostal, 
                'provincia' => $frm_provincia,
                'telefono' => $frm_telefono, 
                'fotoUsuario' => $rutaImagen,
                'esRobot' => $frm_noEsUnRobot
            ];
            
            $usuarioModel->set($usuarioData);
            $usuarioId = $usuarioModel->getUserIdByEmail($frm_correo);
            
            echo "<!DOCTYPE html>
                    <html lang=\"en\">
                    <head>
                        <meta charset=\"UTF-8\">
                        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                        <title>Email Confirmation</title>
                        <link rel=\"stylesheet\" href=\"../css/estilo.css\">
                    </head>";
            echo "<body class=\"emailPage\">
                <div class=\"container\">
                    <h1>¡Gracias por registrarte!</h1>
                    <p>Para confirmar tu registro, por favor haz clic en el siguiente enlace:</p>";
            echo "<a href=\"index.php?Registro/confirmRegistration&id=" . urlencode($usuarioId) . "\">Confirmar Registro</a>";
            echo "</div>
                  </body>
                  </html>";
            return;
        }
        
        $usuarioModel = new UsuariModel();
        $usuarioModel->errors = $errors;
        
        $vRegistro = new RegistroView();
        $vRegistro->form($usuarioModel);
    }
    
    
    public function confirmRegistration($params) {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            echo "<p>Error: No se proporcionó un ID válido.</p>";
            return;
        }
        
        $usuarioModel = new UsuariModel();
        
        $result = $usuarioModel->updateStatusById($id);
        
        echo "<!DOCTYPE html>
                    <html lang=\"en\">
                    <head>
                        <meta charset=\"UTF-8\">
                        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                        <title>Email Confirmation</title>
                        <link rel=\"stylesheet\" href=\"../css/estilo.css\">
                    </head>";
        echo "<body class=\"emailPage\">
            <div class=\"container\">
                <h1>¡Registro Completado!</h1>
                <p>Tu registro se ha completado con éxito. Por favor, oprime el siguiente botón para iniciar sesión:</p>
                <button onclick=\"window.location.href='index.php?Login/show'\">Iniciar Sesión</button>
            </div>
        </body>
        </html>";
    }
    

    
    public function sanitize_input($data) {
        if (is_array($data)) {
            return '';
        }
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
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑçÇ\s]+$/", $nombre)) {
            return "El nombre solo debe tener letras. No números ni caracteres especiales.";
        }
        return '';
    }
    
    public function verificarApellido($apellido) {
        $apellido = $this->sanitize_input($apellido);
        if (empty($apellido)) {
            return "El apellido es obligatorio.";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑçÇ\s]+$/", $apellido)) {
            return "El apellido solo debe tener letras. No números ni caracteres especiales.";
        }
        return '';
    }
    
    public function verificarEdad($fechaNacimiento) {
        $fechaNacimiento = $this->sanitize_input($fechaNacimiento);
        if (empty($fechaNacimiento)) {
            return "La fecha de nacimiento es obligatoria.";
        }
        
        $fechaPartes = explode('-', $fechaNacimiento);
        if (count($fechaPartes) !== 3) {
            return "La fecha de nacimiento no es válida.";
        }
        
        $anioNacimiento = (int)$fechaPartes[0];
        $mesNacimiento = (int)$fechaPartes[1];
        $diaNacimiento = (int)$fechaPartes[2];
        
        if (!checkdate($mesNacimiento, $diaNacimiento, $anioNacimiento)) {
            return "La fecha de nacimiento no es válida.";
        }
        
        $anioActual = (int)date('Y');
        $mesActual = (int)date('m');
        $diaActual = (int)date('d');
        
        $edad = $anioActual - $anioNacimiento;
        if ($mesActual < $mesNacimiento || ($mesActual === $mesNacimiento && $diaActual < $diaNacimiento)) {
            $edad--;
        }
        
        
        if ($edad < 18) {
            return "Debes tener al menos 18 años.";
        }
        
        if ($edad > 150) {
            return "La edad no puede ser mayor de 150 años.";
        }
        
        return '';
    }
    
    public function verificarGenero($genero) {
        $genero = $this->sanitize_input($genero);
        if (empty($genero)) {
            return "Debes seleccionar un género.";
        }
        $opcionesValidas = ['Masculino', 'Femenino', 'Otro'];
        if (!in_array($genero, $opcionesValidas)) {
            return "El género seleccionado no es válido.";
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
        
        $usuarioModel = new UsuariModel();
        if ($usuarioModel->verificarEmailExistente($correo)) {
            return "Este correo electrónico ya está registrado. Por favor, pruebe con otro.";
        }
        
        return '';
    }
    
    public function verificarContrasena($contraseña, $contraseñaRepetida) {
        $contraseña = $this->sanitize_input($contraseña);
        $contraseñaRepetida = $this->sanitize_input($contraseñaRepetida);
        
        if (empty($contraseña) || empty($contraseñaRepetida)) {
            return "Ambos campos de contraseña son obligatorios.";
        }
        if ($contraseña !== $contraseñaRepetida) {
            return "Las contraseñas no coinciden.";
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/', $contraseña)) {
            return "La contraseña debe tener entre 8 y 16 caracteres, al menos una letra minúscula, una mayúscula, un número y un carácter especial.";
        }
        return '';
    }
    
    public function verificarDocumento($tipoDocumento, $numeroDocumento) {
        $numeroDocumento = $this->sanitize_input($numeroDocumento);
        
        if ($tipoDocumento === 'dni') {
            if (!preg_match('/^\d{8}[A-Z]$/', $numeroDocumento)) {
                return "El DNI debe tener 8 números seguidos de una letra mayúscula.";
            }
        } elseif ($tipoDocumento === 'nie') {
            if (!preg_match('/^[XYZ]\d{7}[A-Z]$/', $numeroDocumento)) {
                return "El NIE debe comenzar con X, Y o Z, seguido de 7 números y una letra.";
            }
        } elseif ($tipoDocumento === 'pasaporte') {
            if (!preg_match('/^[A-Z0-9]{6,9}$/', $numeroDocumento)) {
                return "El pasaporte debe contener entre 6 y 9 caracteres alfanuméricos.";
            }
        } else {
            return "Selecciona un tipo de documento válido.";
        }
        
        $usuarioModel = new UsuariModel();
        if ($usuarioModel->verificarDocumentoExistente($numeroDocumento)) {
            return "Este número de documento ya está registrado. Por favor, pruebe con otro.";
        }
        
        return '';
    }
    
    public function verificarDireccion($direccion) {
        $direccion = $this->sanitize_input($direccion);
        
        return '';
    }
    
    
    public function verificarCodigoPostal($codigoPostal) {
        $codigoPostal = $this->sanitize_input($codigoPostal);
        if(empty($codigoPostal)){
            return '';
        }else if (!preg_match('/^\d{5}$/', $codigoPostal)) {
            return "El código postal invalido";
        }
        return '';
    }
    
    public function verificarProvincia($provincia) {
        $provincia = $this->sanitize_input($provincia);
        $provinciasEspana = [
            "Álava", "Albacete", "Alicante", "Almería", "Asturias", "Ávila", "Badajoz",
            "Barcelona", "Burgos", "Cáceres", "Cádiz", "Cantabria", "Castellón",
            "Ciudad Real", "Córdoba", "Cuenca", "Gerona", "Granada", "Guadalajara",
            "Guipúzcoa", "Huelva", "Huesca", "Islas Baleares", "Jaén", "La Coruña",
            "La Rioja", "Las Palmas", "León", "Lérida", "Lugo", "Madrid", "Málaga",
            "Murcia", "Navarra", "Orense", "Palencia", "Pontevedra", "Salamanca",
            "Segovia", "Sevilla", "Soria", "Tarragona", "Tenerife", "Teruel", "Toledo",
            "Valencia", "Valladolid", "Vizcaya", "Zamora", "Zaragoza"
        ];
        if(empty($provincia)){
            return '';
        }else if (!in_array($this->sanitize_input($provincia), $provinciasEspana)) {
            return "Escribe una provincia real de España.";
        }
        return '';
    }
    
    public function verificarTelefono($telefono) {
        $telefono = $this->sanitize_input($telefono);
        
        if(empty($telefono)){
            return "";
        } else if(!ctype_digit($telefono) || strlen($telefono) != 9) {
            return "El teléfono debe tener 9 dígitos. No letras ni caracteres especiales.";
        }
        
        return '';
    }
    
    
    public function verificarImagen($imagen, &$rutaNombreImagen) {
        if (empty($imagen)) {
            return "Debes subir una foto";
        }
        
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $mimeTypesPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        
        // 2MB de tamaño
        $tamañoMaximo = 2 * 1024 * 1024;
        
        if ($imagen['error'] !== UPLOAD_ERR_OK) {
            return "Error al subir la imagen.";
        }
        
        if ($imagen['size'] > $tamañoMaximo) {
            return "La imagen debe ser menor de 2 MB.";
        }
        
        $partes = explode('.', $imagen['name']);
        $extension = strtolower($partes[1]);
        
        if (!in_array($extension, $extensionesPermitidas)) {
            return "La imagen debe estar en formato JPG, JPEG o PNG";
        }
        
        if (file_exists($imagen['tmp_name'])) {
            $mimeType = mime_content_type($imagen['tmp_name']);
        } else {
            return "No se puede acceder al archivo temporal.";
        }
        
        $mimeType = mime_content_type($imagen['tmp_name']);
        if (!in_array($mimeType, $mimeTypesPermitidos)) {
            return "El tipo de archivo no es válido.";
        }
        
        $directorioDestino = __DIR__ . '/../../imgUsuarios/';
        $nombreArchivo = uniqid('img_', true) .'.' . $extension;
        
        if (move_uploaded_file($imagen['tmp_name'], $directorioDestino . $nombreArchivo)) {
            $rutaNombreImagen = $nombreArchivo;
        } else {
            return "Error al mover el archivo.";
        }
        
        return '';
    }
    
    public function verificarNoEsUnRobot($noEsUnRobot) {
        $noEsUnRobot = $this->sanitize_input($noEsUnRobot);
        if (empty($noEsUnRobot)) {
            return "Debes confirmar que no eres un robot.";
        }
        return '';
    }
    
}

?>
