<?php

class AuthorController {
    
    private $authorModel;
    private $phrasesModel;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?Xml/show");
            exit();
        }
        
        $this->authorModel = new AuthorModel();
        $this->phrasesModel = new PhrasesModel();
    }
    
    public function show() {
        $limite = 10;
        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($paginaActual - 1) * $limite;
        
        $busqueda = isset($_GET['search']) ? $_GET['search'] : '';
        
        $authors = $this->authorModel->getFilteredAuthors($busqueda, $offset, $limite);
        
        $totalAuthors = $this->authorModel->getTotalAuthors($busqueda);
        $totalPaginas = ceil($totalAuthors / $limite);
        //var_dump($authors);
        $view = new AuthorView();
        $view->show($authors, $paginaActual, $totalPaginas, $busqueda);
    }
    
    
    public function showEditar() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        
        $author = $this->authorModel->getAuthorById($id);
        
        $view = new AuthorView();
        $view->showEditarAutor($author);
    }
    
    public function showCrear() {
        
        $view = new AuthorView();
        $view->showCrearAutor();
    }
    
    
    //este show es de la pagina especifica del autor
    public function showAuthorDetalle() {
        if (!isset($_GET['id'])) {
            die("Error: No se proporcionó un ID de autor.");
        }
        
        $idAutor = (int)$_GET['id'];
        
        $author = $this->authorModel->getAuthorById($idAutor);
        
        $phrases = $this->phrasesModel->getPhrasesByAuthorId($idAutor);
        
        $view = new AuthorView();
        $view->showDetail($author, $phrases);
    }
    
    
    /**
     * En este metodo form manejo una logica para que dependendo si hay se ibtine un id, entonces es porque el usuario
     * ha abierto un formulario de editar un autor. Y si no hay id es porque es un formulario de crear.
     * @param  $params
     */
    public function form($params) {
       
        $view = new AuthorView();
        $errores = [];
        
        $id_autor = isset($params["id_autor"]) ? trim($params["id_autor"]) : null;
        $nombre = $this->sanitize_input($params["nombre"] ?? "");
        $descripcion = $this->sanitize_input($params["descripcion"] ?? "");
        
        if (empty($nombre)) {
            $errores["nombre"] = "El nombre del autor es obligatorio.";
        }
        
        if (empty($descripcion)) {
            $errores["descripcion"] = "La descripción es obligatoria.";
        }
        
        $author = new Author($id_autor, $nombre, $descripcion, null, 0);
        
        if (!empty($errores)) {
            if ($id_autor) {
                $view->formEditar($author, $errores); 
            } else {
                $view->formCrear($author, $errores); 
            }
            return;
        }
        
        if ($id_autor) {
            $resultado = $this->authorModel->updateAuthor($author);
            if (!$resultado) {
                $errores["general"] = "Error al actualizar el autor.";
                $view->formEditar($author, $errores);
                return;
            }
        } else {
            $resultado = $this->authorModel->createAuthor($author);
            if (!$resultado) {
                $errores["general"] = "Error al crear el autor.";
                $view->formCrear($author, $errores);
                return;
            }
        }
        
        header("Location: ?Author/show");
        exit();
    }
    
    
    
    public function delete($id) {
        var_dump($_GET); 
        
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id']; 
        
        $this->authorModel->deleteAuthor($id);
        
        header("Location: index.php?Author/show");
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