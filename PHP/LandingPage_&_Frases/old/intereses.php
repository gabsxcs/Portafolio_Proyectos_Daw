<?php
session_start();


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Intereses</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<header class="interests-header">
    <nav>
        <div class="logo">Gabriela</div>
        <ul>
            <li><a href="index.php">Gabriela</a></li>
            <li><a href="index.php#contact">Contacto</a></li>
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
                <li><a href="login.php">Iniciar Sesión</a></li>
            <?php endif; ?> 
        </ul>
    </nav>
    <div class="interests-content">
        <h1>Mis Intereses</h1>
        <p>Una mirada a mis pasiones y gustos</p>
    </div>
</header>

<section class="interests-section">
    <div class="interest">
        <h2>Música</h2>
        <p>Soy fan de BTS desde los 11 años, un gusto que me ha acompañado desde entonces. También amo la música de Taylor Swift, y tuve la increíble oportunidad de asistir a uno de sus conciertos en Londres. Lana del Rey es otra artista que admiro profundamente, y he tenido el privilegio de disfrutar de uno de sus conciertos en vivo.</p>
    </div>

    <div class="interest">
        <h2>Lectura</h2>
        <p>Me encanta leer, especialmente sobre mitología griega. Actualmente, estoy fascinada por las historias de Alejandro Magno y su relación con Hefestión, así como por la historia de Aquiles y Patroclo. Además, admiro profundamente a Lilith, la diosa de la noche.</p>
    </div>

    <div class="interest">
        <h2>Películas</h2>
        <p>Mis películas favoritas son la versión de <strong>Mujercitas</strong> de 2019 y la película <strong>Alejandro Magno</strong> de 2004. Ambas están llenas de personajes y tramas que resuenan profundamente conmigo.</p>
    </div>

    <div class="interest">
        <h2>Libros Favoritos</h2>
        <p>Entre mis libros favoritos se encuentran <strong>La canción de Aquiles</strong> de Madeline Miller, <strong>El retrato de Dorian Gray</strong> de Oscar Wilde, <strong>Demian</strong> de Hermann Hesse, y <strong>Satanás</strong> de Mario Mendoza.</p>
    </div>
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
