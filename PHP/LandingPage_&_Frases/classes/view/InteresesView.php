<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);        


class InteresesView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show(){
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"interests-header\">";
        include "../inc/menuMain.php"; 
        echo "<div class=\"interests-content\">
        <h1>Mis Intereses</h1>
        <p>Una mirada a mis pasiones y gustos</p>
    </div>";
        echo  "</header>";
        echo "<section class=\"interests-section\">
    <div class=\"interest\">
        <h2>Música</h2>
        <p>Soy fan de BTS desde los 11 años, un gusto que me ha acompañado desde entonces. También amo la música de Taylor Swift, y tuve la increíble oportunidad de asistir a uno de sus conciertos en Londres. Lana del Rey es otra artista que admiro profundamente, y he tenido el privilegio de disfrutar de uno de sus conciertos en vivo.</p>
    </div>

    <div class=\"interest\">
        <h2>Lectura</h2>
        <p>Me encanta leer, especialmente sobre mitología griega. Actualmente, estoy fascinada por las historias de Alejandro Magno y su relación con Hefestión, así como por la historia de Aquiles y Patroclo. Además, admiro profundamente a Lilith, la diosa de la noche.</p>
    </div>

    <div class=\"interest\">
        <h2>Películas</h2>
        <p>Mis películas favoritas son la versión de <strong>Mujercitas</strong> de 2019 y la película <strong>Alejandro Magno</strong> de 2004. Ambas están llenas de personajes y tramas que resuenan profundamente conmigo.</p>
    </div>

    <div class=\"interest\">
        <h2>Libros Favoritos</h2>
        <p>Entre mis libros favoritos se encuentran <strong>La canción de Aquiles</strong> de Madeline Miller, <strong>El retrato de Dorian Gray</strong> de Oscar Wilde, <strong>Demian</strong> de Hermann Hesse, y <strong>Satanás</strong> de Mario Mendoza.</p>
    </div>
</section>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
}

?>