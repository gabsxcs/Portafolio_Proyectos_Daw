<?php

session_start(); 


error_reporting(E_ALL);
ini_set('display_errors', 1);

$nombre= $apellidos = $telefono= $correo= $mensaje="";

$nombreError = $apellidoError = $telefonoError = $correoError = $mensajeError = ''; 

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
    if (!empty($apellidos)) {
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellidos)) {
            return "Los apellidos solo deben tener letras. No números ni caracteres especiales.";
        } 
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

function verificarMensaje($mensaje) {
    $mensaje = sanitize_input($mensaje);
    if (!empty($mensaje) && strlen($mensaje) > 280) {
        return "El mensaje no puede exceder de 280 caracteres.";
    }
    return '';
}

date_default_timezone_set("Europe/Madrid");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? ''; 
    $mensaje = $_POST['mensaje'] ?? '';
    
    
    $nombreError = verificarNombre($nombre);
    $apellidoError = verificarApellido($apellidos);
    $telefonoError = verificarTelefono($telefono);
    $correoError = verificarCorreo($correo);
    $mensajeError = verificarMensaje($mensaje);

    if (empty($nombreError) && empty($correoError) && empty($mensajeError) && empty($telefonoError) && empty($apellidoError)) { 
    //ESCRIBIR EN EL ARXIU XML
    $arxiuXml = "datosFormulario.xml";
    
    if (!file_exists($arxiuXml)) {
        
        $xmlText = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Contactos>\n</Contactos>";
        file_put_contents($arxiuXml, $xmlText); 
    }
    
    
    $xmlText = file_get_contents($arxiuXml);
    
   
    $posicio = strrpos($xmlText, '</Contactos>');
    
    if ($posicio !== false) {
      
        $xmlText = substr($xmlText, 0, $posicio);
    }  
    
    
    $nouContact = "  <Contacto>\n";
    $nouContact .= "    <Nombre>$nombre</Nombre>\n";
    $nouContact .= "    <Apellidos>$apellidos</Apellidos>\n";
    $nouContact .= "    <Telefono>$telefono</Telefono>\n";
    $nouContact .= "    <CorreoElectronico>$correo</CorreoElectronico>\n";
    $nouContact .= "    <Mensaje>$mensaje</Mensaje>\n";
    $nouContact .= "    <Fecha>" . date("Y-m-d H:i:s") . "</Fecha>\n";
    $nouContact .= "  </Contacto>";
    

    $xmlText .= $nouContact . "\n</Contactos>";

    file_put_contents($arxiuXml, $xmlText);
    
    $mensajeExito = "Gracias por tu mensaje, te contactaré pronto. :) ";  
   }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Landing page personal">
    <title>Mi Landing Page</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="index-header">
        <nav>
            <div class="logo">Gabriela</div>
            <ul>
            	<li><a href="index.php">Gabriela</a></li>
                <li><a href="#contact">Contacto</a></li>
                <li><a href="educacion.php">Educación</a></li>
                <li><a href="intereses.php">Intereses</a></li>   
                <li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="inversiones.php">Inversiones</a>
                    <?php else: ?>
                        <a href="login.php?redirect=inversiones.php">Inversiones</a>
                    <?php endif; ?>
            	</li> 
                 <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="login.php?logout=true">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a> </li>
                <?php endif; ?> 
             </ul>
        </nav>
       	<section class="hero">
        	<div class="hero-content">
                <div class="hero-text">
                    <h1>Gabriela Sandoval Castillo</h1>
                    <p>Estudiante de Desarrollo de Aplicaciones Web</p>
                    <a href="#contact" class="btn">Contáctame</a>
                </div>
                <div class="hero-image">
                    <div class="image-background">
                        <img src="../images/imgHero.png" alt="Gabriela">
                    </div>
                </div>
    		</div>
		</section>
    </header>

   <section id="about" class="section">
    <h2>Sobre Mí</h2>
    <p class="about-text">
    Mi nombre es Gabriela Sandoval Castillo. Nací el 25 de Noviembre en Bogotá, Colombia. Actualmente tengo 18 años y vivo con mi padre en Mataró, España. Aunque antes viví dos años en Barcelona.
	Estudio un ciclo superior de desarollo de aplicaciones web en el instituto Thos i Codina en Mataró, y mi deseo es estudiar la carrera de Informatica en la universidad.
    </p>
</section>
	
	<section id="skills"  class="section">
    <h2>Lenguajes Conocidos</h2>
    <div class="skills-container">
        <div class="skill">
            <i class="fab fa-java"></i>
            <h3>Java</h3>
        </div>
        <div class="skill">
            <i class="fab fa-js"></i>
            <h3>JavaScript</h3>
        </div>
        <div class="skill">
            <i class="fas fa-database"></i>
            <h3>MySQL</h3>
        </div>
        <div class="skill">
            <i class="fab fa-css3-alt"></i>
            <h3>CSS</h3>
        </div>
        <div class="skill">
            <i class="fab fa-html5"></i>
            <h3>HTML</h3>
        </div>
        <div class="skill">
            <i class="fab fa-php"></i>
            <h3>PHP</h3>
        </div>
    </div>
	</section>
	
    <section id="contact" class="section">
    <h2>Contacto</h2>
    <form action="" method="post">
        <input type="text"  name="nombre" placeholder="<?php echo !empty($nombreError) ? $nombreError : 'Nombre'; ?>" 
        		value="<?php echo empty($nombreError) ? htmlspecialchars($nombre) : ''; ?>" id="nombreUsuari" class="<?php echo !empty($nombreError) ? 'error' : ''; ?>">
        
        <input type="text" name="apellidos"  placeholder="<?php echo !empty($apellidoError) ? $apellidoError : 'Apellidos'; ?>" 
               	value="<?php echo empty($apellidoError) ? htmlspecialchars($apellidos) : ''; ?>"  id="apellidoUsuario" class="<?php echo !empty($apellidoError) ? 'error' : ''; ?>">

        <input type="text" name="telefono" placeholder="<?php echo !empty($telefonoError) ? $telefonoError : 'Teléfono'; ?>" 
      		 	value="<?php echo empty($telefonoError) ? htmlspecialchars($telefono) : ''; ?>" class="<?php echo !empty($telefonoError) ? 'error' : ''; ?>">

        <input type="email"  name="correo" placeholder="<?php echo !empty($correoError) ? $correoError : 'Correo Electrónico'; ?>" 
               value="<?php echo empty($correoError) ? htmlspecialchars($correo) : ''; ?>" class="<?php echo !empty($correoError) ? 'error' : ''; ?>">

        <textarea name="mensaje"  placeholder="<?php echo !empty($mensajeError) ? $mensajeError : 'Deja un mensaje (max 280 car.)'; ?>" 
                  class="<?php echo !empty($mensajeError) ? 'error' : ''; ?>"><?php echo empty($mensajeError) ? htmlspecialchars($mensaje) : ''; ?></textarea>

        <button type="submit" class="btn">Enviar</button>
    </form>   

    <?php if (!empty($mensajeExito)): ?>
        <p><?php echo "<h3 id=mensajeExito> $mensajeExito </h3>" ?></p>
    <?php endif; ?>
</section>


    <footer>
        <p>&copy; 2024 Gabriela Sandoval Castillo. Todos los derechos reservados.</p>
        <ul class="socials">
        <li><a href="https://www.linkedin.com/in/tu-perfil" target="_blank"><i class="fab fa-linkedin"></i></a></li>
        <li><a href="https://www.instagram.com/gabsxcs.s/" target="_blank"><i class="fab fa-instagram"></i></a></li>
    </ul>
    </footer>
</body>
</html>


