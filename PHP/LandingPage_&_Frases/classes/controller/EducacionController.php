<?php

class EducacionController {
    
    private $lang;
    
    public function __construct() {
        $this->lang = '';
    }
    
    public function show() {
        
        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
            setcookie('lang', $lang, time() + (86400 * 30), "/"); 
            $page = isset($_GET['page']) ? $_GET['page'] : 'educacion';
            header("Location: index.php?Educacion/show");
            exit;
        }
        
        $educacionModel = new EducacionModel();
        
        $langs = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es';
        
        $this->lang = $educacionModel->cargarLenguaje($langs);
        
        $view = new EducacionView($this->lang);
        $view->show();
    }
}
?>
