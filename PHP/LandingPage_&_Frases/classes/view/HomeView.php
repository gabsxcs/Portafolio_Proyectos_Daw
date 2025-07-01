<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);        


class HomeView extends View {
    
    public function __construct() {
        parent::__construct();
    }

    public function show() {
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php"; 
        echo "<body>";
        echo "<header class=\"index-header\">";
        include "../inc/menuMain.php"; 
        echo "<section class=\"hero\">
        	<div class=\"hero-content\">
                <div class=\"hero-text\">
                    <h1>Gabriela Sandoval Castillo</h1>
                    <p>Estudiante de Desarrollo de Aplicaciones Web</p>
                    <a href=\"index.php?Contacto/show\" class=\"btn\">Contáctame</a>
                </div>
                <div class=\"hero-image\">
                    <div class=\"image-background\">
                        <img src=\"../images/imgHero.png\" alt=\"Gabriela\">
                    </div>
                </div>
    		</div>
		</section>";
        echo "</header>";
        echo "<main>";
        echo "<section id=\"about\" class=\"section\">
                <h2>Sobre Mí</h2>
                <p class=\"about-text\">
                Mi nombre es Gabriela Sandoval Castillo. Nací el 25 de Noviembre en Bogotá, Colombia. Actualmente tengo 18 años y vivo con mi padre en Mataró, España. Aunque antes viví dos años en Barcelona.
            	Estudio un ciclo superior de desarollo de aplicaciones web en el instituto Thos i Codina en Mataró, y mi deseo es estudiar la carrera de Informatica en la universidad.
                </p>
            </section>
            	
            	<section id=\"skills\"  class=\"section\">
                <h2>Lenguajes Conocidos</h2>
                <div class=\"skills-container\">
                    <div class=\"skill\">
                        <i class=\"fab fa-java\"></i>
                        <h3>Java</h3>
                    </div>
                    <div class=\"skill\">
                        <i class=\"fab fa-js\"></i>
                        <h3>JavaScript</h3>
                    </div>
                    <div class=\"skill\">
                        <i class=\"fas fa-database\"></i>
                        <h3>MySQL</h3>
                    </div>
                    <div class=\"skill\">
                        <i class=\"fab fa-css3-alt\"></i>
                        <h3>CSS</h3>
                    </div>
                    <div class=\"skill\">
                        <i class=\"fab fa-html5\"></i>
                        <h3>HTML</h3>
                    </div>
                    <div class=\"skill\">
                        <i class=\"fab fa-php\"></i>
                        <h3>PHP</h3>
                    </div>
                </div>
            	</section>";
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php"; 
        echo "</footer>";
        echo "</body></html>";
    }
    
    
}
