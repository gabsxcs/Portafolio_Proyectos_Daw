<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();



$defaultLang = 'es';
$langs = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : $defaultLang;


$langFile = "../langs/vars_{$langs}.php"; 
if (file_exists($langFile)) {
    $lang = include($langFile);
} else {
    $lang = include("../langs/vars_{$defaultLang}.php"); 
}


if (isset($_GET['lang'])) {
    $langs = $_GET['lang']; 
    setcookie('lang', $langs, time() + (86400 * 30), "/"); 
    header("Location: educacion.php"); 
    exit;
}


?>
<!DOCTYPE html>
<html lang="<?php echo $langs; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['title']; ?></title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<header class="education-header">
    <nav>
        <div class="logo"></div>
        <ul>
            <li><a href="index.php"><?php echo $lang['nombre']; ?></a></li>
            <li><a href="index.php#contact"><?php echo $lang['contacto']; ?></a></li>
            <li><a href="#educationSec"><?php echo $lang['educacion']; ?></a></li>
            <li><a href="intereses.php"><?php echo $lang['intereses']; ?></a></li>
            <li>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="inversiones.php"><?php echo $lang['inversiones']; ?></a>
                <?php else: ?>
                    <a href="login.php?redirect=inversiones.php"><?php echo $lang['inversiones']; ?></a>
                <?php endif; ?>
        	</li> 
            <?php if (isset($_SESSION['username'])): ?>
                 <li><a href="login.php?logout=true"><?php echo $lang['logout']; ?></a></li>
            <?php else: ?>
                <li><a href="login.php"><?php echo $lang['login']; ?></a></li>
            <?php endif; ?> 
            <li>
                <a href="?lang=es">Español</a> | 
                <a href="?lang=en">English</a> |
                <a href="?lang=gr">Ελληνικά</a>
            </li>
        </ul>
    </nav>
    <div class="education-content">
        <h1><?php echo $lang['educacionTitle']; ?></h1>
        <p><?php echo $lang['educacionDescripcion']; ?></p>
    </div>
</header>

<section id="educationSec" class="education-section">
    <div class="school school-left">
        <div class="school-image">
            <img src="../images/colegioBosques.png" alt="Colegio 1">
        </div>
        <div class="school-text">
            <h2><?php echo $lang['escuela1Title']; ?></h2>
            <p><?php echo $lang['escuela1Text']; ?></p>
        </div>
    </div>

    <div class="school school-right">
        <div class="school-image">
            <img src="../images/insVerdaguer.png" alt="Colegio 2">
        </div>
        <div class="school-text">
            <h2><?php echo $lang['escuela2Title']; ?></h2>
            <p><?php echo $lang['escuela2Text']; ?></p>
        </div>
    </div>

    <div class="school school-left">
        <div class="school-image">
            <img src="../images/insLaia.png" alt="Colegio 3">
        </div>
        <div class="school-text">
            <h2><?php echo $lang['escuela3Title']; ?></h2>
            <p><?php echo $lang['escuela3Text']; ?></p>
        </div>
    </div>

    <div class="school school-right">
        <div class="school-image">
            <img src="../images/insThos.png" alt="Colegio 4">
        </div>
        <div class="school-text">
            <h2><?php echo $lang['escuela4Title']; ?></h2>
            <p><?php echo $lang['escuela4Text']; ?></p>
        </div>
    </div>
</section>

<footer>
    <p><?php echo $lang['footer']; ?></p>
    <ul class="socials">
        <li><a href="https://www.linkedin.com/in/tu-perfil" target="_blank"><i class="fab fa-linkedin"></i></a></li>
        <li><a href="https://www.instagram.com/gabsxcs.s/" target="_blank"><i class="fab fa-instagram"></i></a></li>
    </ul>
</footer>

</body>
</html>
