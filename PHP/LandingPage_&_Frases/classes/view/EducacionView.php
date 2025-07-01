<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);        

require_once __DIR__ . '/View.php';

class EducacionView extends View {
    
    private $lang;
    
    public function __construct($lang) {
        parent::__construct();
        $this->lang = $lang; 
    }
    
    public function show(){
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"education-header\">";
        echo "<nav>
                <div class=\"logo\">Gabriela</div>
                <ul> 
        <li><a href=\"index.php\"> {$this->lang['nombre']} </a></li>
        <li><a href=\"index.php?Contacta/show\"> {$this->lang['contacto']} </a></li>
        <li><a href=\"index.php?Educacion/show\"> {$this->lang['educacion']}</a></li>
        <li><a href=\"index.php?Intereses/show\"> {$this->lang['intereses']}</a></li>
        <li>";
        if (isset($_SESSION['username'])) {
            echo '<a href="index.php?Inversiones/show">' . $this->lang['inversiones'] . '</a>';
        } else {
            echo '<a href="index.php?Login/show">' . $this->lang['inversiones'] . '</a>';
        }
        echo '</li>';
        if (isset($_SESSION['username'])) {
            echo '<li><a href="index.php?Login/Logout">' . $this->lang['logout'] . '</a></li>';
        } else {
            echo '<li><a href="index.php?Login/show">' . $this->lang['login'] . '</a></li>';
        }
        
       echo " 
        <li>
            <!-- Cambiar idioma -->
            <a href=\"index.php?Educacion/show&lang=es\">Español</a> | 
            <a href=\"index.php?Educacion/show&lang=en\">English</a> |
            <a href=\"index.php?Educacion/show&lang=gr\">Ελληνικά</a>
        </li>
    </ul>
</nav>";
        echo "<div class=\"education-content\">
        <h1>{$this->lang['educacionTitle']}</h1>
        <p>{$this->lang['educacionDescripcion']}</p>
                </div>";
        echo  "</header>";
        echo "<section id=\"educationSec\" class=\"education-section\">";
        echo "<div class=\"school school-left\">
                    <div class=\"school-image\">
                        <img src=\"../images/colegioBosques.png\" alt=\"Colegio 1\">
                    </div>
                    <div class=\"school-text\">
                        <h2>{$this->lang['escuela1Title']}</h2>
                        <p>{$this->lang['escuela1Text']}</p>
                    </div>
                </div>
                <div class=\"school school-right\">
                    <div class=\"school-image\">
                        <img src=\"../images/insVerdaguer.png\" alt=\"Colegio 2\">
                    </div>
                    <div class=\"school-text\">
                        <h2>{$this->lang['escuela2Title']}</h2>
                        <p>{$this->lang['escuela2Text']}</p>
                    </div>
                </div>
                <div class=\"school school-left\">
                    <div class=\"school-image\">
                        <img src=\"../images/insLaia.png\" alt=\"Colegio 3\">
                    </div>
                    <div class=\"school-text\">
                        <h2>{$this->lang['escuela3Title']}</h2>
                        <p>{$this->lang['escuela3Text']}</p>
                    </div>
                </div>
                <div class=\"school school-right\">
                    <div class=\"school-image\">
                        <img src=\"../images/insThos.png\" alt=\"Colegio 4\">
                    </div>
                    <div class=\"school-text\">
                        <h2>{$this->lang['escuela4Title']}</h2>
                        <p>{$this->lang['escuela4Text']}</p>
                    </div>
                </div>";
        echo "</section>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
}

?>