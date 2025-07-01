<?php

class ThemesController {
    
    private $themeModel;
    private $phrasesModel;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?Xml/show");
            exit();
        }
        
        $this->themeModel = new ThemesModel();
        $this->phrasesModel = new PhrasesModel();
    }
    
    public function show() {
        $limite = 10;
        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($paginaActual < 1) $paginaActual = 1;
        
        $offset = ($paginaActual - 1) * $limite;
        
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        
        $themes = $this->themeModel->getThemesWithLimitAndSearch($searchTerm, $offset, $limite);
        
        $totalThemes = $this->themeModel->getTotalThemes($searchTerm);
        $totalPages = ceil($totalThemes / $limite);
        
        $view = new ThemesView();
        $view->show($themes, $paginaActual, $totalPages, $searchTerm);
    }
    
    
    
    public function showCrear() {
        $view = new ThemesView();
        $view->showCrearTema();
    }
    
    public function showEditar() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        
        $theme = $this->themeModel->getThemeById($id);
        
        $view = new ThemesView();
        $view->showEditarTema($theme);
    }
    
    /**
     * Muestra una pagina del tema con una lista de frases relacionadas
     */
    public function showThemeDetalle() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die("Error: No se proporcionó un ID de tema válido.");
        }
        
        $idTema = (int)$_GET['id'];
        
        $theme = $this->themeModel->getThemeById($idTema);
        $phrases = $this->phrasesModel->getPhrasesByThemeId($idTema);
        
        $view = new ThemesView();
        $view->showDetail($theme, $phrases);
    }
    
    
    
    
    /**
     * En este metodo form manejo una logica para que dependendo si hay se ibtine un id, entonces es porque el usuario
     * ha abierto un formulario de editar un tema. Y si no hay id es porque es un formulario de crear.
     * @param  $params
     */
    public function form($params) {
        $view = new ThemesView();
        $errors = [];
        
        $id_tema = isset($params["id_tema"]) && is_numeric($params["id_tema"]) ? (int)$params["id_tema"] : null;
        $nombreTema = $this->sanitize_input($params["temaEditar"] ?? "");
        
        if (empty($nombreTema)) {
            $errors["temaEditar"] = "El tema es obligatorio.";
        }
        
        $theme = new Theme($nombreTema, $id_tema);
        
        
        if (!empty($errors)) {
            if ($id_tema) {
                $view->formEditarTema($errors, $theme); 
            } else {
                $view->formCrearTema($errors, $theme); 
            }
            return;
        }
        
        if ($id_tema) {
            $resultado = $this->themeModel->updateTheme($theme);
            if (!$resultado) {
                $errors["general"] = "Error al actualizar el tema.";
                $view->formEditarTema($errors, $theme);
                return;
            }
        } else {
            $resultado = $this->themeModel->createTheme($theme);
            if (!$resultado) {
                $errors["general"] = "Error al crear el tema.";
                $view->formCrearTema($errors, $theme);
                return;
            }
        }
        
        header("Location: index.php?Themes/show");
        exit();
    }
    
    
    
    public function delete($id) {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        
        $this->themeModel->deleteTheme($id);
        
        header("Location: index.php?Themes/show");
        exit();
    }
    
    public function sanitize_input($data) {
        $data = $data ?? '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
?>
