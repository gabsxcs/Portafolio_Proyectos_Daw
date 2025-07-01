<?php

class XmlView extends View{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show($usuarioAutenticado) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body class=\"autor-body\">";
        echo "<header class=\"frases-header\">";
        include "../inc/menuMain.php";
        echo "<div class=\"frases-content\">
            <h1>Frases de las mentes más grandes.</h1>
            <p>Una recopilación de las frases de los pensadores más grandes de la historia</p>
        </div>";
        echo "</header>";
        echo "<main class=\"main-xml\">";
        
        if (!$usuarioAutenticado) {
            echo "<div class='alerta-sesion'>
                    <p>Debe iniciar sesión para ver las frases.</p>
                    <a href='?Login/show' class='btn-iniciar'>Iniciar Sesión</a>
                  </div>";
        } else {
            echo "<div class='importacion-xml'>
                    <p>Para cargar la base de datos, haga clic en el siguiente botón.</p>
                    <a href='?Xml/importarXml'>
                        <button type='submit' class='btn-importar'>Importar Base de Datos</button>
                    </a>
                    <a href='?Phrases/show'>
                    <p>Si ya ha cargado el xml, haga click aqui</p>
                    </a>
                  </div>";
        }
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
}
