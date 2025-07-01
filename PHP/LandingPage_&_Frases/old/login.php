<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}


$usuarios = [
    "gugshyt@gmail.com" => "invitado2023",
    "gabysandovalcastillo@gmail.com" => "gabriela25",
    "gabsxc.ss@gmail.com" => "gabi25",
];

$errorUsername = ""; 
$errorPassword = ""; 
$username = ''; 

function sanitize_input($data) {
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);
    
    if (!isset($usuarios[$username])) {
        $errorUsername = "Correo incorrecto";
    } elseif ($usuarios[$username] !== $password) {
        $errorPassword = "Contraseña incorrecta"; 
    } else {
        $_SESSION['username'] = $username;
        
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
        header("Location: " . $redirect);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<header class="login-header">
    <nav>
        <div class="logo">Gabriela</div>
        <ul>
            <li><a href="index.php">Gabriela</a></li>
            <li><a href="index.php#contact">Contacto</a></li>
            <li><a href="educacion.php">Educación</a></li>
            <li><a href="intereses.php">Intereses</a></li>
        </ul>
    </nav>
 
<div id="loginForm">
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <label for="username">Correo Electronico: </label>
          <input type="email" id="username" name="username" required 
            <?php echo "value='" . htmlspecialchars($username) . "'"; 
            if ($errorUsername) echo " style='border-color:red;'"; ?> 
            placeholder="<?php echo $errorUsername; ?>">
            
        <label for="password">Contraseña:</label>
         <input type="password" id="password" name="password" required 
            <?php   if ($errorPassword) echo " style='border-color:red;'";  ?> placeholder="<?php echo $errorPassword; ?>">

        <div id="buttonLogin">
         	<button type="submit" class="loginButtons" id="loginButton">Iniciar Sesión</button>
         	<button type="button" onclick="location.href='registro.php';" class="loginButtons" id="registroButton">Registrarse</button>
        </div>
    </form>

</div>
    
</header>


<footer>
    <p>&copy; 2024 Gabriela Sandoval Castillo. Todos los derechos reservados.</p>
    <ul class="socials">
        <li><a href="https://www.linkedin.com/in/tu-perfil" target="_blank"><i class="fab fa-linkedin"></i></a></li>
        <li><a href="https://www.instagram.com/gabsxcs.s/" target="_blank"><i class="fab fa-instagram"></i></a></li>
    </ul>
</footer>

</body>
</html>