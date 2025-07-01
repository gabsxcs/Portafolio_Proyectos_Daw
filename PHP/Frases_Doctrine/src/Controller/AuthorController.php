<?php
namespace Frases\Controller;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Frases\Entity\Author;
use Frases\Entity\Phrase;
use Frases\View\AuthorView;


class AuthorController {
    
    private $entityManager;
    private $authorRepository;
    private $phrasesRepository;
    
    public function __construct($entityManager) {
       
        
        $this->entityManager = $entityManager;
        $this->authorRepository = $this->entityManager->getRepository(Author::class);
        $this->phrasesRepository = $this->entityManager->getRepository(Phrase::class);
    }
    
    public function show() {
        $limit = 10;
        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($paginaActual - 1) * $limit;
        
        $busqueda = $_GET['search'] ?? '';
        
        $authors = $this->authorRepository->getFilteredAuthors($busqueda, $offset, $limit);
        $totalAuthors = $this->authorRepository->getTotalAuthors($busqueda);
        $totalPaginas = ceil($totalAuthors / $limit);
        
        //print_r($authors);
        
        $view = new AuthorView();
        $view->show($authors, $paginaActual, $totalPaginas, $busqueda);
        
        
    }
    
    public function showEditar() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) throw new Exception("Error: El ID no es válido.");
        
        $author = $this->authorRepository->getAuthorById($id);
        $view = new AuthorView();
        $view->showEditarAutor($author);
    }
    
    public function showCrear() {
        $view = new AuthorView();
        $view->showCrearAutor();
    }
    
    public function showAuthorDetalle() {
        $idAutor = (int)($_GET['id'] ?? 0);
        if (!$idAutor) die("Error: No se proporcionó un ID de autor.");
        
        $author = $this->authorRepository->getAuthorById($idAutor);
        $phrases = $this->phrasesRepository->findBy(['author' => $author]);
        
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
        
        $id = isset($params["id_autor"]) ? (int)$params["id_autor"] : null;
        $nombre = $this->sanitize_input($params["nombre"] ?? "");
        $descripcion = $this->sanitize_input($params["descripcion"] ?? "");
        
        if (empty($nombre)) $errores["nombre"] = "El nombre del autor es obligatorio.";
        if (empty($descripcion)) $errores["descripcion"] = "La descripción es obligatoria.";
        
        
        $author = $id ? $this->authorRepository->getAuthorById($id) : new Author();
        
        if (!$author) {
            $errores["general"] = "Autor no encontrado.";
            $view->formEditar(null, $errores);
            return;
        }
        
        $author->setName($nombre);
        $author->setDescription($descripcion);
        
        if (!empty($errores)) {
            $id ? $view->formEditar($author, $errores) : $view->formCrear($author, $errores);
            return;
        }
        
        try {
            if ($id) {
                $this->authorRepository->updateAuthor($author);
            } else {
                $this->authorRepository->createAuthor($author);
            }
            
            header("Location: ?Author/show");
            exit();
        } catch (\Exception $e) {
            $errores["general"] = "Error al guardar el autor: " . $e->getMessage();
            $id ? $view->formEditar($author, $errores) : $view->formCrear($author, $errores);
        }
    }
    
    /**
     * Elimina un author
     */
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) throw new Exception("Error: El ID no es válido.");
        
        $author = $this->authorRepository->getAuthorById($id);
        if ($author) {
            $this->authorRepository->deleteAuthor($author);
        }
        
        header("Location: Index.php?Author/show");
        exit();
    }
    
    private function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data ?? '')));
    }
}
