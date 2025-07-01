<?php
namespace Frases\View;
ini_set('display_errors', 1);
error_reporting(E_ALL);


class ErrorView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public static function show($e) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "inc/headMain.php";
        echo "<body class=\"errorPage\">";
        echo "<header class=\"errorPage-header\">";
        include "inc/menuError.php"; 
        echo "</header>";
        echo "<div class=\"errorContenidor\">";
        echo "<img id=\"imgError\" src=\"images/ERROR.png\" alt=\"Error\">";
        echo "<h2>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</h2>";
        echo "</div>";
        echo "<footer>";
        include "inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
}