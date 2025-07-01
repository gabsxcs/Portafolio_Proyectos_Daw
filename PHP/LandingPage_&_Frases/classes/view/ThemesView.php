<?php
class ThemesView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show($themes, $paginaActual, $totalPaginas, $busqueda) {
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
        echo "<main>";
        echo "<div class=\"botonesFrasesContenedor\">
                 <a href=\"?Phrases/show\"><button>Frases</button></a>
                 <a href=\"?Author/show\"><button>Autores</button></a>
                 <a href=\"?Themes/show\"><button>Temas</button></a>
                 <a href=\"?Themes/showCrear\"><button>Crear Nuevo Tema</button></a>
                 <a href=\"?Xml/BorrarTablas\"><button>Recargar</button></a>
            </div>";
        
        echo "<div class=\"contenedorBusqueda\">
            <form method=\"GET\" action=\"\" class='formBusqueda'>       
                <input type=\"hidden\" name=\"Themes/show\">
                <input type=\"text\" name=\"search\" class=\"inputBuscar\" placeholder=\"Buscar por tema\" value=\"" . (isset($_GET['search']) ? $_GET['search'] : '') . "\" />
                <button type='submit' class='btnBuscar'>Buscar</button>
                <button type='button' class='btnResetear' onclick=\"window.location.href='?Themes/show'\">Restablecer</button>
            </form>
          </div>";
        
        echo "<div class=\"contenedorAutores\">
            <table class=\"tablaAutores\">
                <thead>
                    <tr>
                        <th>Nom del Tema</th>
                        <th>Num</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($themes as $theme) {
            echo "<tr>
                <td><a href=\"?Themes/showThemeDetalle&id={$theme->__get('id_tema')}\">{$theme->__get('nom')}</a></td>
                <td>{$theme->__get('numFrases')}</td>
                <td class=\"casillaBotonesFrases\">
                    <a href=\"?Themes/showEditar&id={$theme->__get('id_tema')}\"><button class=\"botonEditar\">Editar</button></a>
                    <a href=\"?Themes/delete&id={$theme->__get('id_tema')}\" onclick=\"return confirm('¿Seguro que deseas eliminar este tema?');\"><button class=\"botonEliminar\">Eliminar</button></a>
                    <a href=\"?Phrases/showCrear&id={$theme->__get('id_tema')}\"><button class=\"botonCrear\">Crear</button></a>
                </td>
            </tr>";
        }
        
        echo "      </tbody>
            </table>
        </div>";
        echo "<div class=\"paginacion\">";
        if ($paginaActual > 1) {
            echo "<a href=\"?Themes/show&page=" . ($paginaActual - 1) . "&search=" . urlencode($busqueda) . "\">Anterior</a>";
        }
        
        echo " | Página $paginaActual de $totalPaginas | ";
        
        if ($paginaActual < $totalPaginas) {
            echo "<a href=\"?Themes/show&page=" . ($paginaActual + 1) . "&search=" . urlencode($busqueda) . "\">Siguiente</a>";
        }
        echo "</div>";
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    public function showCrearTema(){
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo '<main>
                <h1>Crear Tema</h1>
                <div class="formEditar">
                    <form action="?Themes/form" method="post">
                        <div class="campoEditar">
                            <label for="temaEditar">Tema (*)</label>
                            <input type="text" id="temaEditar" name="temaEditar" required>
                        </div>
            
                        <button id="btnEditarFrase" type="submit">Enviar</button>
                    </form>
                </div>
            </main>';
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
        
    }
    
    public function formCrearTema($errors = [], Theme $tema = null) {
        $errorTema = isset($errors["temaEditar"]) ? "<p class='error'>{$errors['temaEditar']}</p>" : "";
        $errorGeneral = isset($errors["general"]) ? "<p class='error'>{$errors["general"]}</p>" : "";
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo "<main>
            <h1>Crear Tema</h1>
            <div class=\"formEditar\">
                <form action=\"?Themes/form\" method=\"post\">
                    <div class=\"campoCrear\">
                        <label for=\"temaCrear\">Tema (*)</label>
                        <input type=\"text\" id=\"temaCrear\" name=\"temaEditar\" value=\"{$tema->__get("nombre")}\" required>
                        $errorTema
                    </div>
                    $errorGeneral
                    <button id=\"btnCrearTema\" type=\"submit\">Crear Tema</button>
                </form>
            </div>
        </main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    public function showEditarTema(Theme $theme){
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo '<main>
            <h1>Editar Tema</h1>
            <div class="formEditar">
                <form action="?Themes/form" method="post">
                    <input type="hidden" name="id_tema" value="' . $theme->__get("id_tema") . '">
                    <div class="campoEditar">
                        <label for="temaEditar">Tema (*)</label>
                        <input type="text" id="temaEditar" name="temaEditar" value="' . $theme->__get("nom") . '" required>
                    </div>
                            
                    <button id="btnEditarFrase" type="submit">Guardar Cambios</button>
                </form>
            </div>
        </main>';
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
        
    }
    
    public function formEditarTema($errors = [], $tema = null) {
        $errorTema = isset($errors["temaEditar"]) ? "<p class='error'>{$errors['temaEditar']}</p>" : "";
        $errorGeneral = isset($errors["general"]) ? "<p class='error'>{$errors["general"]}</p>" : "";
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formCrearTema">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo "<main>
            <h1>Editar Tema</h1>
            <div class=\"formCrear\">
                <form action=\"?Themes/form\" method=\"post\">
                    <div class=\"campoCrear\">
                        <label for=\"temaCrear\">Tema (*)</label>
                        <input type=\"text\" id=\"temaCrear\" name=\"temaEditar\" value=\"{$tema}\" required>
                        $errorTema
                    </div>
                    $errorGeneral
                    <button id=\"btnCrearTema\" type=\"submit\">Crear Tema</button>
                </form>
            </div>
        </main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    public function showDetail($theme, $phrases) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body class=\"autor-body\">";
        echo "<header class=\"frases-header\">";
        include "../inc/menuMain.php";
        echo "<div class=\"frases-content\">
        <h1>Frases sobre \"{$theme->__get('nom')}\"</h1>
        <p>Una recopilación de frases relacionadas con el tema.</p>
    </div>";
        echo "</header>";
        echo "<main>";
        
        echo "<div class=\"contenedorAutoresFrases\">";
        
        if (empty($phrases)) {
            echo "<div class='mensajeNoFrases'>
            <p>No hay frases registradas para este tema.</p>
        </div>";
        } else {
            echo "<table class='tablaAutores'>";
            echo "<thead>
            <tr>
                <th>Frase</th>
                <th>Autor</th>
            </tr>
        </thead>";
            echo "<tbody>";
            foreach ($phrases as $phrase) {
                $texto = $phrase->__get('texto');
                $autor = $phrase->__get('autor') ?: "Autor desconocido";
                echo "<tr>
                <td>{$texto}</td>
                <td>{$autor}</td>
            </tr>";
            }
            echo "</tbody></table>";
        }
        
        echo "</div>";
        
        echo "<div class=\"accionesTemaDetalle\">";
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
}
?>
