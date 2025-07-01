<?php
namespace Frases\Controller;

use Doctrine\DBAL\Exception;
use Frases\Entity\Author;
use Frases\Entity\Phrase;
use Frases\Entity\Theme;
use Frases\View\PhrasesView;

class PhrasesController {
    private $phrasesRepository;
    private $authorRepository;
    private $themesRepository;
    private $entityManager;
    
    public function __construct($entityManager) {
       
        $this->entityManager = $entityManager;
        $this->authorRepository = $this->entityManager->getRepository(Author::class);
        $this->phrasesRepository = $this->entityManager->getRepository(Phrase::class);
        $this->themesRepository = $this->entityManager->getRepository(Theme::class);

    }
     
    
    public function show() {
        $limite = 10;
        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($paginaActual - 1) * $limite;
        
        $busqueda = isset($_GET['search']) ? $_GET['search'] : '';
        
        $phrases = $this->phrasesRepository->getFilteredPhrases($busqueda, $offset, $limite); 
        
        $totalPhrases = $this->phrasesRepository->getTotalPhrases($busqueda);
        
        $totalPaginas = ceil($totalPhrases / $limite);
        
        $view = new PhrasesView();
        $view->show($phrases, $paginaActual, $totalPaginas, $busqueda);
    }
    
    
   
    public function showEdit() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        $phrase = $this->phrasesRepository->getPhraseById($id);
        
        if (!$phrase) {
            throw new Exception("Error: Frase no encontrada.");
        }
        
        $authors = $this->authorRepository->getAllAuthors();
        $themes = $this->themesRepository->getAllThemes();
        
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
       
        
        $authors = $this->authorRepository->getAllAuthors();
        $themes = $this->themesRepository->getAllThemes();
        
        //var_dump($authors);
        
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
        $id_tema = isset($params["id_tema"]) ? (int)$params["id_tema"] : null; 
    
        
        if (empty($texto)) {
            $errors["texto"] = "La frase es obligatoria.";
        }
    
        if (empty($id_autor)) {
            $errors["id_autor"] = "El autor es obligatorio.";
        }
    
        if (empty($id_tema)) {
            $errors["temas"] = "El tema es obligatorio.";
        }
    
        $authors = $this->authorRepository->getAllAuthors();
        $themes = $this->themesRepository->getAllThemes();
    
        $phrase = null;
        if ($id_frase) {
            $phrase = $this->phrasesRepository->getPhraseById($id_frase);
            if (!$phrase) {
                $errors["general"] = "Frase no encontrada.";
            }
        } else {
            $phrase = new Phrase();
        }
    
        if (empty($errors)) {
            $phrase->setTexto($texto);
            $phrase->setAuthor($this->authorRepository->find($id_autor));
    
            $phrase->getThemes()->clear();
    
            $theme = $this->themesRepository->find($id_tema);
            if ($theme) {
                $phrase->addTheme($theme); 
            } else {
                $errors["temas"] = "El tema especificado no es válido.";
            }
    
            if (!empty($errors)) {
                if ($id_frase) {
                    $view->formEditar($phrase, $authors, $themes, $errors);
                } else {
                    $view->formCrear($authors, $themes, $errors, $phrase);
                }
                return;
            }
    
            if ($id_frase) {
               // echo "Este es el id tema: " . $id_tema;
                $resultado = $this->phrasesRepository->updatePhrase($phrase);
                if (!$resultado) {
                    $errors["general"] = "Error al actualizar la frase.";
                    $view->formEditar($phrase, $authors, $themes, $errors);
                    return;
                }
            } else {
                $resultado = $this->phrasesRepository->createPhrase($phrase);
                if (!$resultado) {
                    $errors["general"] = "Error al crear la frase.";
                    $view->formCrear($authors, $themes, $errors, $phrase);
                    return;
                }
            }
    
            if (empty($errors)) {
                header("Location: Index.php?Phrases/show");
                exit();
            }
        }
    }
    
    
    // Eliminar frase
    public function delete() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        $this->phrasesRepository->deletePhrase($id);
        header("Location: Index.php?Phrases/show");
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
