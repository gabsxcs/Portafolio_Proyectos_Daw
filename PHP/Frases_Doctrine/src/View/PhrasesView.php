<?php
namespace Frases\View;

use Frases\Entity\Phrase;

class PhrasesView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show($phrases, $paginaActual, $totalPaginas, $busqueda) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "inc/headMain.php";
        echo "<body class=\"autor-body\">";
        echo "<header class=\"frases-header\">";
        include "inc/menuMain.php";
        echo "  <div class=\"frases-content\">
                    <h1>Frases de las mentes más grandes.</h1>
                    <p>Una recopilación de las frases de los pensadores más grandes de la historia</p>
                </div>";
        echo "</header>";
        echo "<main>";
        echo "  <div class=\"botonesFrasesContenedor\">
                    <a href=\"?Phrases/show\"><button>Frases</button></a>
                    <a href=\"?Author/show\"><button>Autores</button></a>
                    <a href=\"?Themes/show\"><button>Temas</button></a>
                    <a href=\"?Phrases/showCrear\"><button>Crear Nueva Frase</button></a>
                    <a href=\"?Xml/borrarTablas\"><button>Recargar</button></a>
                </div>";
        
        echo "  <div class=\"contenedorBusqueda\">
                <form method=\"get\" action=\"\" class='formBusqueda'>";
        echo "      <input type='hidden' name='Phrases/show'>
                    <input type=\"text\" name=\"search\" class\"inputBuscar\" placeholder=\"Buscar por frase, autor o tema\" value=\"" . (isset($_GET['search']) ? $_GET['search'] : '') . "\" />
                    <button type='submit' class='btnBuscar'>Buscar</button>
                    <button type='button' class='btnResetear' onclick=\"window.location.href='?Phrases/show'\">Restablecer</button>
                </form>
            </div>";
        
        echo "<div class=\"contenedorAutores\">
        <table class=\"tablaAutores\">
            <thead>
                <tr>
                    <th>Text</th>
                    <th>Autor</th>
                    <th>Tema</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
        
        foreach ($phrases as $phrase) {
            echo "<tr>
            <td>{$phrase->getTexto()}</td>
            <td><a href=\"?Author/showAuthorDetalle&id={$phrase->getAuthor()->getId()}\">{$phrase->getAuthor()->getName()}</a></td>
            <td>" . implode(', ', array_map(function($theme) { return $theme->getName(); }, $phrase->getThemes()->toArray())) . "</td>
            <td class=\"casillaBotonesFrases\">
                <a href=\"?Phrases/showEdit&id={$phrase->getId()}\">
                    <button class=\"botonEditar\">Editar</button>
                </a>
                <a href=\"?Phrases/delete&id={$phrase->getId()}\"
                   onclick=\"return confirm('¿Seguro que deseas eliminar esta frase?');\">
                    <button class=\"botonEliminar\">Eliminar</button>
                </a>
            </td>
        </tr>";
                }
        
        
        echo "      </tbody>
            </table>
        </div>";
        
        echo "<div class=\"paginacion\">";
        if ($paginaActual > 1) {
            echo "<a href=\"?Phrases/show&page=" . ($paginaActual - 1) . "&search=" . urlencode($busqueda) . "\">Anterior</a>";
        }
        
        echo " | Página $paginaActual de $totalPaginas | ";
        
        if ($paginaActual < $totalPaginas) {
            echo "<a href=\"?Phrases/show&page=" . ($paginaActual + 1) . "&search=" . urlencode($busqueda) . "\">Siguiente</a>";
        }
        echo "</div>";
        
        echo "</main>";
        echo "<footer>";
        include "inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    public function showEditarFrases(Phrase $phrase, $autores, $temasDisponibles) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "inc/menuMain.php";
        echo "</header>";
        echo "<main>
                <h1>Editar Frase</h1>
                <div class=\"formEditar\">
                    <form action=\"?Phrases/form\" method=\"post\">
                        <input type=\"hidden\" name=\"id\" value=\"{$phrase->getId()}\">
                        
                        <div class=\"campoEditar\">
                            <label for=\"fraseEditar\">Frase (*)</label>
                            <textarea id=\"fraseEditar\" name=\"texto\" required>{$phrase->getTexto()}</textarea>
                        </div>
                        
                        <div class=\"campoEditar\">
                            <label for=\"autorEditar\">Autor (*)</label>
                            <select id=\"autorEditar\" name=\"id_autor\" required>";
                    
                        foreach ($autores as $autor) {
                            $selected = ($autor->getId() == $phrase->getId()) ? "selected" : "";
                            echo "<option value=\"{$autor->getId()}\" $selected>{$autor->getName()}</option>";
                        }
                        
                        echo        "</select>
                            </div>
                            
                            <div class=\"campoEditar\">
                                <label for=\"temaFraseEditar\">Tema (*)</label>

                                <select id=\"temaFraseEditar\" name=\"id_tema\" required>";
                        
                        foreach ($temasDisponibles as $tema) {
                            $selected = ($phrase->getThemes()->contains($tema)) ? "selected" : "";
                            echo "<option value=\"{$tema->getId()}\" $selected>{$tema->getName()}</option>";
                        }
                        
                        echo  "     </select>
                                    </div>
                                    <button id=\"btnEditarFrase\" type=\"submit\">Actualizar</button>
                            </form>
                        </div>
                        </main>";
        echo "  <footer>";
        include "inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
   

    public function formEditar(Phrase $phrase, $authors, $themes, $errors=[]) {
        
        $errorAutor = isset($errors["id_autor"]) ? "<p class='error'>{$errors['id_autor']}</p>" : "";
        $errorTexto = isset($errors["texto"]) ? "<p class='error'>{$errors['texto']}</p>" : "";
        $errorTema = isset($errors["id_tema"]) ? "<p class='error'>{$errors['id_tema']}</p>" : "";
        $errorGeneral = isset($errors["general"]) ? "<p class='error'>{$errors["general"]}</p>" : "";
        
            
            echo "<!DOCTYPE html><html lang=\"es\">";
            include "inc/headMain.php";
            echo "<body id=\"formEditarFrases\">";
            echo "<header class=\"eventos-header\">";
            include "inc/menuMain.php";
            echo "</header>";
            echo "<main>
            <h1>Editar Frase</h1>
            <div class=\"formEditar\">
                <form action=\"?Phrases/form\" method=\"post\">
                    <input type=\"hidden\" name=\"id\" value=\"{$phrase->getId()}\">
                    
                    <div class=\"campoEditar\">
                        <label for=\"fraseEditar\">Frase (*)</label>
                        <textarea id=\"fraseEditar\" name=\"texto\" required>{$phrase->getTexto()}</textarea>
                        $errorTexto
                    </div>
                    
                    <div class=\"campoEditar\">
                        <label for=\"autorEditar\">Autor (*)</label>
                        <select id=\"autorEditar\" name=\"id_autor\" required>
                            <option value=\"\">Seleccione un autor</option>";
                        foreach ($authors as $author) {
                            $selected = ($author->getId() == $phrase->getId()) ? "selected" : "";
                            echo "<option value=\"{$author->getId()}\" $selected>{$author->getName()}</option>";
                        }
                        echo "</select>
                        $errorAutor
                    </div>
                    
                    <div class=\"campoEditar\">
                        <label for=\"temaEditar\">Tema (*)</label>
                        <select id=\"temaEditar\" name=\"id_tema\" required>
                            <option value=\"\">Seleccione un tema</option>";
                        foreach ($themes as $theme) {
                            $selected = ($theme->getId() == $phrase->getThemes()) ? "selected" : "";
                            echo "<option value=\"{$theme->getId()}\" $selected>{$theme->getName()}</option>";
                        }
                        echo "</select>
                        $errorTema
                    </div>
                    
                    $errorGeneral
                    
                    <button id=\"btnEditarFrase\" type=\"submit\">Actualizar</button>
                </form>
            </div>
        </main>";
                    echo "<footer>";
                    include "inc/footer.php";
                    echo "</footer>";
                    echo "</body></html>";
    }
    
    public function showCrearFrases($autores, $temasDisponibles, $id_tema=null, $id_autor=null) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "inc/menuMain.php";
        echo "</header>";
        echo "<main>
        <h1>Crear Frase</h1>
        <div class=\"formEditar\">
            <form action=\"?Phrases/form\" method=\"post\">
                <input type=\"hidden\" name=\"id\" value=\"\">
            
                <div class=\"campoEditar\">
                    <label for=\"fraseEditar\">Frase (*)</label>
                    <textarea id=\"fraseEditar\" name=\"texto\" required></textarea>
                </div>
            
                <div class=\"campoEditar\">
                    <label for=\"autorEditar\">Autor (*)</label>
                    <select id=\"autorEditar\" name=\"id_autor\" required>";
        
        foreach ($autores as $autor) {
            $selected = ($id_autor == $autor->getId()) ? 'selected' : '';
            echo "<option value=\"{$autor->getId()}\" $selected>{$autor->getName()}</option>";
        }
        
        echo "</select>
        </div>
            
        <div class=\"campoEditar\">
            <label for=\"temaFraseEditar\">Tema (*)</label>
            <select id=\"temaFraseEditar\" name=\"id_tema\" required>";
        
        foreach ($temasDisponibles as $tema) {
            $selected = ($id_tema == $tema->getId()) ? 'selected' : '';
            echo "<option value=\"{$tema->getId()}\" $selected>{$tema->getName()}</option>";
        }
        
        echo "</select>
                </div>
                <button id=\"btnEditarFrase\" type=\"submit\">Crear</button>
            </form>
        </div>
        </main>";
        echo "<footer>";
        include "inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    
    public function formCrear($autores, $temasDisponibles, $errors = [], $phrase = null) {
        
        $errorAutor = isset($errors["id_autor"]) ? "<p class='error'>{$errors['id_autor']}</p>" : "";
        $errorFrase = isset($errors["texto"]) ? "<p class='error'>{$errors['texto']}</p>" : "";
        $errorTema = isset($errors["id_tema"]) ? "<p class='error'>{$errors['id_tema']}</p>" : "";
        $errorGeneral = isset($errors["general"]) ? "<p class='error'>{$errors["general"]}</p>" : "";
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "inc/menuMain.php";
        echo "</header>";
        echo "<main>
                <h1>Crear Frase</h1>
                <div class=\"formEditar\">
                    <form action=\"?Phrases/form\" method=\"post\">
                        <input type=\"hidden\" name=\"id\" value=\"\"> 
                        
                        <div class=\"campoEditar\">";
                    $textareaValue = $phrase ? $phrase->getTexto() : "";
                    echo "<label for=\"fraseCrear\">Frase (*)</label>
                        <textarea id=\"fraseCrear\" name=\"texto\" required>$textareaValue</textarea>
                        $errorFrase
                    </div>
                    
                    <div class=\"campoEditar\">";
                        echo "<label for=\"autorCrear\">Autor (*)</label>
                        <select id=\"autorCrear\" name=\"id_autor\" required>";
                        
                        foreach ($autores as $autor) {
                            $selected = ($phrase && $phrase->getId() == $autor->getId()) ? 'selected' : '';
                            echo "<option value=\"{$autor->getId()}\" $selected>{$autor->getName()}</option>";
                        }
                        
                        echo "</select>
                        $errorAutor
                    </div>
                    
                    <div class=\"campoEditar\">";
                        echo "<label for=\"temaCrear\">Tema (*)</label>
                        <select id=\"temaCrear\" name=\"id_tema\" required>";
                        
                        foreach ($temasDisponibles as $tema) {
                            $selected = ($phrase && $phrase->getId() == $tema->getId()) ? 'selected' : '';
                            echo "<option value=\"{$tema->getId()}\" $selected>{$tema->getName()}</option>";
                        }
                        echo "</select>
                        $errorTema
                    </div>
                    $errorGeneral
                    <button id=\"btnCrearFrase\" type=\"submit\">Crear Frase</button>
                </form>
                </div>
            </main>";
            echo "<footer>";
            include "inc/footer.php";
            echo "</footer>";
            echo "</body></html>";
    }
    
    
    
    
}