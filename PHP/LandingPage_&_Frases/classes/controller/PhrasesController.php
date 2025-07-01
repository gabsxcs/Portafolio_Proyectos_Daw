<?php

class PhrasesController {
    private $phrasesModel;
    private $authorsModel;
    private $themesModel;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?Xml/show");
            exit();
        }
        
        $this->phrasesModel = new PhrasesModel();
        $this->authorsModel = new AuthorModel();
        $this->themesModel = new ThemesModel();
    }
    
    
    public function show() {
        $limite = 10;
        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($paginaActual - 1) * $limite;
        
        $busqueda = isset($_GET['search']) ? $_GET['search'] : '';
        
        $phrases = $this->phrasesModel->getFilteredPhrases($busqueda, $offset, $limite);
        
        $totalPhrases = $this->phrasesModel->getTotalPhrases($busqueda);
        
        $totalPaginas = ceil($totalPhrases / $limite);
        
        $view = new PhrasesView();
        $view->show($phrases, $paginaActual, $totalPaginas, $busqueda);
    }
    
    
   
    public function showEdit() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        $phrase = $this->phrasesModel->getPhraseById($id);
        
        if (!$phrase) {
            throw new Exception("Error: Frase no encontrada.");
        }
        
        $authors = $this->authorsModel->getAllAuthors();
        $themes = $this->themesModel->getAllThemes();
        
        $view = new PhrasesView();
        $view->showEditarFrases($phrase, $authors, $themes);
    }
    
    
    /**
     * Esto lo he hecho asi, de forma que si la solicitud para hacer una nueva frase, llega desde autor o un tema,
     * entonces el formulario aparece directamente con el tema o autor desde donde se dio a crear
     */
    public function showCrear() {
        $id_tema = isset($_GET['id']) ? $_GET['id'] : null;
        $id_autor = isset($_GET['idAutor']) ? $_GET['idAutor'] : null;
       
        
        $authors = $this->authorsModel->getAllAuthors();
        $themes = $this->themesModel->getAllThemes();
        
        
        $view = new PhrasesView();
        
        if($id_tema){
            $view->showCrearFrases($authors, $themes, $id_tema);
        } else if($id_autor){
            $view->showCrearFrases($authors, $themes, null, $id_autor);
        } else {
            $view->showCrearFrases($authors, $themes);
        }
               
    }
    
    /**
     * En este metodo form manejo una logica para que dependendo si hay se ibtine un id, entonces es porque el usuario
     * ha abierto un formulario de editar una frase. Y si no hay id es porque es un formulario de crear frase.
     * @param  $params
     */
    public function form($params) {
        $view = new PhrasesView();
        $errors = [];
        
        $id_frase = isset($params["id"]) && is_numeric($params["id"]) ? (int)$params["id"] : null;
        $texto = $this->sanitize_input($params["texto"] ?? "");
        $id_autor = $this->sanitize_input($params["id_autor"] ?? "");
        $id_tema = $this->sanitize_input($params["id_tema"] ?? "");
        
        
        if (empty($texto)) {
            $errors["texto"] = "La frase es obligatoria.";
        }
        
        if (empty($id_autor)) {
            $errors["id_autor"] = "El autor es obligatorio.";
        }
        
        if (empty($id_tema)) {
            $errors["temas"] = "El tema es obligatorio.";
        }
        
        
        $authors = $this->authorsModel->getAllAuthors();
        $themes = $this->themesModel->getAllThemes();
        
        
        $phrase = new Phrase($id_frase, $texto, $id_autor, "", $id_tema);
        

        if (!empty($errors)) {
            if ($id_frase) {
                $view->formEditar($phrase, $authors, $themes, $errors);
            } else {
                $view->formCrear($authors, $themes, $errors, $phrase);
            }
            return;
        }
        
        if ($id_frase) {
            $resultado = $this->phrasesModel->updatePhrase($phrase);
            
            if (!$resultado) {
                $errors["general"] = "Error al actualizar la frase.";
                $view->formEditar($phrase, $authors, $themes, $errors);
                return;
            }
        } else {
            $resultado = $this->phrasesModel->createPhrase($phrase);
            
            if (!$resultado) {
                $errors["general"] = "Error al crear la frase.";
                $view->formCrear($authors, $themes, $errors, $phrase);
                return;
            }
        }
        
        if (empty($errors)) {
            header("Location: index.php?Phrases/show"); 
            exit();
        }
        
    }
    
    
    
    
    // Eliminar frase
    public function delete() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        $this->phrasesModel->deletePhrase($id);
        header("Location: index.php?Phrases/show");
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
