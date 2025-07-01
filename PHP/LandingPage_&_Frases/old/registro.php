<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servidor = "localhost";
$usuario = "root";
$contraseña = "Colombia2005!";
$baseDeDatos = "myweb";


$conn = mysqli_connect($servidor, $usuario, $contraseña, $baseDeDatos);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

session_start();

$nombre = $apellidos = $fechaNacimiento = $genero  = $correo = $contraseña = $contraseñaRepetida = $tipoDocumento = $numeroDocumento = $direccion = $codigoPostal = $provincia = $telefono = $imagen = $noEsUnRobot = "";
$situacion = [];
$nombreError = $apellidoError = $edadError = $generoError = $situacionError = $correoError = $contraseñaError =  $contraseñaRepetidaError = $tipoDocumentoError = $numeroDocumentoError = $direccionError = $codigoPostalError = $provinciaError = $telefonoError = $imagenError = $noEsUnRobotError = "";


function sanitize_input($data) {
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function verificarNombre($nombre) {
    $nombre = sanitize_input($nombre);
    if (empty($nombre)) {
        return "El nombre es obligatorio.";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        return "El nombre solo debe tener letras. No números ni caracteres especiales.";
    }
    return '';
}

function verificarApellido($apellidos) {
    $apellidos = sanitize_input($apellidos);
    if (empty($apellidos)) {
        return "El apellido es obligatorio.";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellidos)) {
        return "El apellido solo debe tener letras. No números ni caracteres especiales.";
    }
    return '';
}

function verificarEdad($fechaNacimiento) {
    $fechaNacimiento = sanitize_input($fechaNacimiento);
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

function verificarGenero($genero) {
    $genero = sanitize_input($genero);
    if (empty($genero)) {
        return "Debes seleccionar un género.";
    }
    $opcionesValidas = ['Masculino', 'Femenino', 'Otro'];
    if (!in_array($genero, $opcionesValidas)) {
        return "El género seleccionado no es válido.";
    }
    return '';
}


function verificarSituacion($situacion) {
    if (empty($situacion) || !is_array($situacion)) {
        return "Debes seleccionar al menos una situación.";
    }
    return '';
}

function verificarCorreo($correo) {
    $correo = sanitize_input($correo);
    if (empty($correo)) {
        return "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return "Correo no válido.";
    }
    
    
    if (verificarCorreoExistente($correo)) {
        return "Este correo electrónico ya está registrado. Por favor, pruebe con otro.";
    }
    
    return '';
}

function verificarContrasena($contraseña, $contraseñaRepetida) {
    $contraseña = sanitize_input($contraseña);
    $contraseñaRepetida = sanitize_input($contraseñaRepetida);
    
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

function verificarDocumento($tipoDocumento, $numeroDocumento) {
    $numeroDocumento = sanitize_input($numeroDocumento);
    
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
    
    if (verificarDocumentoExistente($numeroDocumento)) {
        return "Este número de documento ya está registrado. Por favor, pruebe con otro.";
    }
    
    return '';
}

function verificarDireccion($direccion) {
    $direccion = sanitize_input($direccion);
    if (empty($direccion)) {
        return "La dirección es obligatoria.";
    }
    return '';
}


function verificarCodigoPostal($codigoPostal) {
    $codigoPostal = sanitize_input($codigoPostal);
    if (!preg_match('/^\d{5}$/', $codigoPostal)) {
        return "El código postal invalido";
    }
    return '';
}

function verificarProvincia($provincia) {
    $provincia = sanitize_input($provincia);
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
    if (!in_array(sanitize_input($provincia), $provinciasEspana)) {
        return "Escribe una provincia real de España.";
    }
    return '';
}

function verificarTelefono($telefono) {
    $telefono = sanitize_input($telefono);
    if (!empty($telefono)) {
        if (!ctype_digit($telefono) || strlen($telefono) != 9) {
            return "El teléfono debe tener 9 dígitos. No letras ni caracteres especiales.";
        }
    }
    return '';
}

$rutaNombreImagen = '';  //una variable para guardar la ruta de la iagen, esta variable se llenará dentro de la funcion verificarImagen

function verificarImagen($imagen, &$rutaNombreImagen) {
    if (empty($imagen)) {
        return "Debes subir una foto";
    }
    
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $mimeTypesPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
    
    // 2MB  de tamañp
    $tamañoMaximo = 2 * 1024 * 1024;
    
    
    if ($imagen['error'] !== UPLOAD_ERR_OK) {
        return "Error al subir la imagen.";
    }
    
    
    if ($imagen['size'] > $tamañoMaximo) {
        return "La imagen debe ser menor de 2 MB.";
    }
    
    // Sacar la extension del nombre
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
    
    $directorioDestino = 'imgUsuario/';
    
    if (!is_dir($directorioDestino)) {
        if (!mkdir($directorioDestino, 0777, true)) {
            return "No se pudo crear el directorio de destino.";
        }
    } else {
        chmod($directorioDestino, 0777);
    }
    
    
    
    $nombreArchivo = uniqid('img_', true) .'.' . $extension;
    
    
    if (move_uploaded_file($imagen['tmp_name'], $directorioDestino . $nombreArchivo)) {
        $rutaNombreImagen = $nombreArchivo;
    } else {
        return "Error al mover el archivo.";
    }
    
    return '';
    
}



function verificarNoEsUnRobot($noEsUnRobot) {
    $noEsUnRobot = sanitize_input($noEsUnRobot);
    if (empty($noEsUnRobot)) {
        return "Debes confirmar que no eres un robot.";
    }
    return '';
}


//Estas dos funciones son para verificar que el numero del documento o correo no existan previamente en la base de datos, ya que son datos unicos
function verificarDocumentoExistente($numeroDocumento) {
    global $conn;
    $sql = "SELECT COUNT(*) FROM registros WHERE numero_documento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $numeroDocumento);
    $stmt->execute();
    
    $count = 0;  //esta variable cuanta la cantidad de registros en la table que contienen el dato buscado
    
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

function verificarCorreoExistente($correo) {
    global $conn;
    $sql = "SELECT COUNT(*) FROM registros WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $count = 0;  //esta variable cuanta la cantidad de registros en la table que contienen el dato buscado
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $apellidos =  $_POST['apellidos'];
    $fechaNacimiento =  $_POST['fechaNacimiento'];
    $genero = $_POST['genero'];
    $situacion = isset($_POST['situacion']) ? $_POST['situacion'] : [];
    $correo =  $_POST['correo'];
    $contraseña =  $_POST['contraseña'];
    $contraseñaRepetida =  $_POST['contraseñaRepetida'];
    $tipoDocumento =  $_POST['tipoDocumento'];
    $numeroDocumento =  $_POST['numeroDocumento'];
    $direccion = $_POST['direccion'];
    $codigoPostal =  $_POST['codigoPostal'];
    $provincia =  $_POST['provincia'];
    $telefono= $_POST['telefono'];
    $imagen = $_FILES['imagen'];
    $noEsUnRobot = $_POST['noEsUnRobot'];
    
    
    $nombreError = verificarNombre($nombre);
    $apellidoError = verificarApellido($apellidos);
    $edadError = verificarEdad($fechaNacimiento);
    $generoError = verificarGenero($genero);
    $situacionError = verificarSituacion($situacion);
    $correoError = verificarCorreo($correo);
    $contraseñaError = verificarContrasena($contraseña, $contraseñaRepetida);
    $tipoDocumentoError = verificarDocumento($tipoDocumento, $numeroDocumento);
    $numeroDocumentoError = verificarDocumento($tipoDocumento, $numeroDocumento);
    $direccionError = verificarDireccion($direccion);
    $codigoPostalError = verificarCodigoPostal($codigoPostal);
    $provinciaError = verificarProvincia($provincia);
    $telefonoError = verificarTelefono($telefono);
    $imagenError = verificarImagen($imagen, $rutaNombreImagen);
    $noEsUnRobotError = verificarNoEsUnRobot($noEsUnRobot);
    
    
    if (!$nombreError && !$apellidoError && !$edadError && !$generoError && !$situacionError &&
        !$correoError && !$contraseñaError && !$tipoDocumentoError && !$direccionError &&
        !$codigoPostalError && !$provinciaError && !$telefonoError && !$imagenError && !$noEsUnRobotError) {
            
            if ($rutaNombreImagen) {
                $rutaImagen = 'imgUsuario/' . $rutaNombreImagen;
            } else {
                $rutaImagen = '';
            }
            
            $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO registros (nombre, apellidos, fecha_nacimiento, genero, situacion_actual,
                correo_electronico, contrasena, tipo_documento, numero_documento, direccion, codigo_postal,
                provincia, telefono, imagen)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssssssssss', $nombre, $apellidos, $fechaNacimiento, $genero,
                implode(',', $situacion), $correo, $contraseñaHash, $tipoDocumento, $numeroDocumento,
                $direccion, $codigoPostal, $provincia, $telefono, $rutaImagen);
            
            
            if ($stmt->execute()) {
                $mensajeEnvio = "¡Formulario enviado correctamente! Te he enviado un correo de verificación.";
            } else {
                $mensajeEnvio = '';
            }
            
            $stmt->close();
        } else {
            echo "Por favor corrige los errores en el formulario.";
        }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="/../css/estiloForm.css">
    <script src="js/script.js" defer></script>
</head>
<body class="registroPagina">

<header class="registro-header">
    <nav>
        <div class="logo">Gabriela</div>
        <ul>
            <li><a href="index.php">Gabriela</a></li>
            <li><a href="index.php#contact">Contacto</a></li>
            <li><a href="educacion.php">Educación</a></li>
            <li><a href="intereses.php">Intereses</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
        </ul>
    </nav>
   
</header>

<main>

   
        <div class="formContenidor" >
            <form action="" method="post" id="formRegistro" enctype="multipart/form-data">
               <div class="formPaso activo" id="paso1">
                    <h2>Información Básica</h2>
                    <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" >
                     <?php if ($nombreError) { echo "<p class='error'>$nombreError</p>"; } ?>
                    <input type="text" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos; ?>" >
                     <?php if ($apellidoError) { echo "<p class='error'>$apellidoError</p>"; } ?>
                    <input type="date" name="fechaNacimiento" value="<?php echo $fechaNacimiento; ?>" >
                    <?php if ($edadError) { echo "<p class='error'>$edadError</p>"; } ?>
                    <select name="genero">
                        <option value="">Seleccionar Género</option>
                        <option value="Masculino" <?php echo $genero === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo $genero === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo $genero === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                    <?php if ($generoError) { echo "<p class='error'>$generoError</p>"; } ?>
                    
                   <div class="situacionContenidor">
                        <p>Situación Actual:</p>
                        <label><input type="checkbox" name="situacion[]" value="estudiante" <?php echo in_array('estudiante', $situacion) ? 'checked' : ''; ?>> Estudiante</label>
                        <label><input type="checkbox" name="situacion[]" value="trabajador" <?php echo in_array('trabajador', $situacion) ? 'checked' : ''; ?>> Trabajador</label>
                        <label><input type="checkbox" name="situacion[]" value="autonomo" <?php echo in_array('autonomo', $situacion) ? 'checked' : ''; ?>> Autónomo</label>    
                   </div>
                   <?php if ($situacionError) { echo "<p class='error'>$situacionError</p>"; } ?>
                    <button  type="button" class="botonSiguiente" onclick="nextStep(1)">Siguiente</button>
                </div>
    
                <div class="formPaso" id="paso2">
                    <h2>Credenciales</h2>
                    <input type="email" name="correo" placeholder="Correo Electrónico" value="<?php echo $correo; ?>" >
                    <?php if ($correoError) { echo "<p class='error'>$correoError</p>"; } ?>
                    <input type="password" name="contraseña" placeholder="Contraseña" value="<?php echo $contraseña; ?>" >
                    <?php if ($contraseñaError) { echo "<p class='error'>$contraseñaError</p>"; } ?>
                    <input type="password" name="contraseñaRepetida" placeholder="Repetir Contraseña" value="<?php echo $contraseñaRepetida; ?>" >
                    <?php if ($contraseñaRepetidaError) { echo "<p class='error'>$contraseñaRepetidaError</p>"; } ?>
                    <button  type="button" class="botonSiguiente" onclick="nextStep(2)">Siguiente</button>
                    <button type="button" class="botonAtras" onclick="prevStep(2)">Atrás</button>
                </div>
    
                <div class="formPaso" id="paso3">
                    <h2>Documento de Identidad</h2>
                    <select name="tipoDocumento">
                        <option value="">Tipo de Documento</option>
                        <option value="dni" <?php echo $tipoDocumento === 'dni' ? 'selected' : ''; ?>>DNI</option>
                        <option value="nie" <?php echo $tipoDocumento === 'nie' ? 'selected' : ''; ?>>NIE</option>
                        <option value="pasaporte" <?php echo $tipoDocumento === 'pasaporte' ? 'selected' : ''; ?>>Pasaporte</option>
                    </select>
                    <?php if ($tipoDocumentoError) { echo "<p class='error'>$tipoDocumentoError</p>"; } ?>
                    <input type="text" name="numeroDocumento" placeholder="<?php echo $numeroDocumentoError ? $numeroDocumentoError : 'Número de Documento'; ?>" value="<?php echo $numeroDocumento; ?>" >
                    <button  type="button" class="botonSiguiente" onclick="nextStep(3)">Siguiente</button>
                    <button  type="button" class="botonAtras" onclick="prevStep(3)">Atrás</button>
                </div>
    
                <div class="formPaso" id="paso4">
                    <h2>Detalles de Contacto</h2>
                    <input type="text" name="direccion" placeholder="Dirección" value="<?php echo $direccion; ?>" >
                     <?php if ($direccionError) { echo "<p class='error'>$direccionError</p>"; } ?>
                     
                    <input type="text" name="codigoPostal" placeholder="Código Postal" value="<?php echo $codigoPostal; ?>" >
                     <?php if ($codigoPostalError) { echo "<p class='error'>$codigoPostalError</p>"; } ?>
                     
                    <input type="text" name="provincia" placeholder="Provincia" value="<?php echo $provincia; ?>" >
                     <?php if ($provinciaError) { echo "<p class='error'>$provinciaError</p>"; } ?>
                    
                    <input type="tel" name="telefono" placeholder="Teléfono(Opcional)" value="<?php echo $telefono; ?>" >
                     <?php if ($telefonoError) { echo "<p class='error'>$telefonoError</p>"; } ?>
                     
                    <button type="button" class="botonSiguiente" onclick="nextStep(4)">Siguiente</button>
                    <button type="button" class="botonAtras" onclick="prevStep(4)">Atrás</button>
                </div>
    
                <div class="formPaso" id="paso5">
                    <h2>Subir Imagen</h2>
                    <p>(Máximo 2MB, formatos aceptados: JPG, PNG, GIF)</p>
                    <input type="file" name="imagen" accept="image/jpeg, image/png, image/gif">
                     <?php if ($imagenError) { echo "<p class='error'>$imagenError</p>"; } ?>
                    <button type="button" class="botonSiguiente" onclick="nextStep(5)">Siguiente</button>
                    <button type="button" class="botonAtras" onclick="prevStep(5)">Atrás</button>
                </div>
    
                <div class="formPaso" id="paso6">
                    <h2>Verificación</h2>
                    <div class="verificacionRobot">
                        <label><input type="checkbox" id="robotCheckbox" name="noEsUnRobot" value="1" <?php echo $noEsUnRobot ? 'checked' : ''; ?>> No soy un robot</label>
                        <img src="img/robot.png" alt="Imagen de robot" class="robotImg">
                        <?php if ($noEsUnRobotError) { echo "<p class='error'>$noEsUnRobotError</p>"; } ?>
                    </div>
                    <button type="submit" class="botonEnviar">Enviar</button>
                    <button type="button" class="botonAtras" onclick="prevStep(6)">Atrás</button>
                </div>
                 
            </form>
        </div>
    
         <?php
            if (!empty($mensajeEnvio)) {
                echo " <div id=\"mensajeEnvio\"> <h3 class='success'>$mensajeEnvio</p> <img id=\"imagenGato\" src=\"img/gatoExito.gif\"> </div>"; 
            }
            ?>
      
</main>

<footer>
    <p>&copy; 2024 Gabriela Sandoval Castillo. Todos los derechos reservados.</p>
    <ul class="socials">
        <li><a href="https://www.linkedin.com/in/tu-perfil" target="_blank"><i class="fab fa-linkedin"></i></a></li>
        <li><a href="https://www.instagram.com/gabsxcs.s/" target="_blank"><i class="fab fa-instagram"></i></a></li>
    </ul>
</footer>

</body>
</html>