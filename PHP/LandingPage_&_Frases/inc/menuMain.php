<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <div class="logo">Gabriela</div>
    <ul>
        <li><a href="index.php">Gabriela</a></li>
        <li><a href="index.php?Contacto/show">Contacto</a></li>
        <li><a href="index.php?Educacion/show">Educación</a></li> 
        <li><a href="index.php?Intereses/show">Intereses</a></li>
        <li><a href="index.php?Calendario/show">Calendario</a></li>
        <li><a href="index.php?Xml/show">Frases</a></li>
        <li>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="index.php?Inversiones/show">Inversiones</a>
            <?php else: ?>
                <a href="index.php?Login/show">Inversiones</a>
            <?php endif; ?>
        </li>
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href="index.php?Login/Logout">Cerrar Sesión</a></li>
        <?php else: ?>
            <li><a href="index.php?Login/show">Iniciar Sesión</a></li>
        <?php endif; ?> 
        <li><a href="index.php?Registro/show">Registro</a></li>
    </ul>
</nav>
