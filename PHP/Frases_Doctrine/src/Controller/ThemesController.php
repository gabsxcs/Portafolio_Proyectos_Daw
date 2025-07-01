<?php
namespace Frases\Controller;
use Doctrine\DBAL\Exception;
use Frases\Entity\Phrase;
use Frases\Entity\Theme;
use Frases\View\ThemesView;

class ThemesController {
    
    private $phrasesRepository;
    private $themesRepository;
    private $entityManager;
    
    public function __construct($entityManager) {
       
        $this->entityManager = $entityManager;
        $this->phrasesRepository = $this->entityManager->getRepository(Phrase::class);
        $this->themesRepository = $this->entityManager->getRepository(Theme::class);
    }
    
    public function show() {
       $limite = 10;
        $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($paginaActual < 1) $paginaActual = 1;
        
        $offset = ($paginaActual - 1) * $limite;
        
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        
        $themes = $this->themesRepository->getThemesWithLimitAndSearch($searchTerm, $offset, $limite);
        
        $totalThemes = $this->themesRepository->getTotalThemes($searchTerm);
        $totalPages = ceil($totalThemes / $limite);
        
        $view = new ThemesView();
        $view->show($themes, $paginaActual, $totalPages, $searchTerm);
        //var_dump($themes);
       
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
        
        $theme = $this->themesRepository->getThemeById($id);
        
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
        
        $theme = $this->themesRepository->getThemeById($idTema);
        $phrases = $this->phrasesRepository->getPhrasesByThemeId($idTema);
        
        $view = new ThemesView();
        $view->showDetail($theme, $phrases);
    }
    
    
    
    
    /**
     * En este metodo form manejo una logica para que dependendo si hay se ibtine un id, entonces es porque el usuario
     * ha abierto un formulario de editar un tema. Y si no hay id es porque es un formulario de crear.
     * @param  $params
     */
    public function form($params) {
        var_dump($params); 
        $view = new ThemesView();
        $errors = [];
        
        $id_tema = isset($params["id_tema"]) && is_numeric($params["id_tema"]) ? (int)$params["id_tema"] : null;
        $nombreTema = $this->sanitize_input($params["temaEditar"] ?? "");
        
        echo "Nombre del tema después de sanitize_input: " . $nombreTema . "<br>";
        
        if (empty($nombreTema)) {
            $errors["temaEditar"] = "El tema es obligatorio.";
            
        }
        
      ///  echo "Nombre del tema después de sanitize_input: " . $nombreTema . "<br>";
        
        
        $theme = new Theme();
        
        $theme->setName($nombreTema);
        
        if (!empty($errors)) {
            if ($id_tema) {
                $view->formEditarTema($errors, $theme); 
            } else {
                $view->formCrearTema($errors, $theme); 
            }
            return;
        }
        
        if ($id_tema) {
            $theme = $this->themesRepository->getThemeById($id_tema);
            if (!$theme) {
                $errors["general"] = "El tema no existe.";
                $view->formEditarTema($errors, null);
                return;
            }
            
            $theme->setName($nombreTema);
            
            $resultado = $this->themesRepository->updateTheme($theme);
            
            if (!$resultado) {
                $errors["general"] = "Error al actualizar el tema.";
                $view->formEditarTema($errors, $theme);
                return;
            }
        } else {
            $resultado = $this->themesRepository->createTheme($theme);
            if (!$resultado) {
                $errors["general"] = "Error al crear el tema.";
                $view->formCrearTema($errors, $theme);
                return;
            }
        }
        
        header("Location: Index.php?Themes/show");
        exit();
    }
    
    
    //eliminar un tema
    public function delete($id) {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("Error: El ID no es válido.");
        }
        
        $id = (int)$_GET['id'];
        
        $this->themesRepository->deleteTheme($id);
        
        header("Location: Index.php?Themes/show");
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
