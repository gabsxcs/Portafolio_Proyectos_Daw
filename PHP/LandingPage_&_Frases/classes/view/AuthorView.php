<?php
class AuthorView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show($authors, $paginaActual, $totalPaginas, $busqueda) {
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
                 <a href=\"?Author/showCrear\"><button>Crear nuevo autor</button></a>
                 <a href=\"?Xml/BorrarTablas\"><button>Recargar</button></a>
            </div>";
        
        echo "<div class=\"contenedorBusqueda\">
        <form method=\"get\" action=\"\" class=\"formBusqueda\">
            <input type=\"hidden\" name=\"Author/show\">
            <input type=\"text\" name=\"search\" class=\"inputBuscar\" placeholder=\"Buscar por autor\" value=\"" . (isset($_GET['search']) ? $_GET['search'] : '') . "\" />
            <button type=\"submit\" class=\"btnBuscar\">Buscar</button>
            <button type=\"button\" class=\"btnResetear\" onclick=\"window.location.href='?Author/show'\">Restablecer</button>
        </form>
    </div>";
        
        echo "<div class=\"contenedorAutores\">
            <table class=\"tablaAutores\">
                <thead>
                    <tr>
                        <th>Nombre del Autor</th>
                        <th>Descripción</th>
                        <th>Número de Frases</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($authors as $author) {
            echo "<tr>
                <td><a href=\"?Author/showAuthorDetalle&id={$author->__get('id_autor')}\">{$author->__get('nombre')}</a></td>
                <td>{$author->__get('descripcion')}</td>
                <td>{$author->__get('numFrases')}</td>
                <td class=\"casillaBotonesFrases\">
                    <a href=\"?Author/showEditar&id={$author->__get('id_autor')}\">
                        <button class=\"botonEditar\">Editar</button>
                    </a>
                    <a href=\"?Author/delete&id={$author->__get('id_autor')}\"
                       onclick=\"return confirm('¿Seguro que deseas eliminar este autor?');\">
                        <button class=\"botonEliminar\">Eliminar</button>
                    </a>
                    <a href=\"?Phrases/showCrear&idAutor={$author->__get('id_autor')}\">
                        <button class=\"botonCrear\">Crear</button>
                    </a>
                </td>
              </tr>";
        }
        
        echo "  </tbody>
            </table>
          </div>";
        
        echo "<div class=\"paginacion\">";
        if ($paginaActual > 1) {
            echo "<a href=\"?Author/show&page=" . ($paginaActual - 1) . "&search=" . urlencode($busqueda) . "\">Anterior</a>";
        }
        
        echo " | Página $paginaActual de $totalPaginas | ";
        
        if ($paginaActual < $totalPaginas) {
            echo "<a href=\"?Author/show&page=" . ($paginaActual + 1) . "&search=" . urlencode($busqueda) . "\">Siguiente</a>";
        }
        echo "</div>";
        
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    
    
    public function showEditarAutor(Author $author) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo '<main>
            <h1>Editar Autor</h1>
            <div class="formEditar">
                <form action="?Author/form" method="post">
                    <input type="hidden" name="id_autor" value="' . $author->__get('id_autor') . '">
                        
                    <div class="campoEditar">
                        <label for="nombre">Autor (*)</label>
                        <input type="text" id="nombre" name="nombre" value="' . $author->__get('nombre') . '" required>
                    </div>
                            
                    <div class="campoEditar">
                        <label for="descripcion">Descripción (*)</label>
                        <textarea id="descripcion" name="descripcion" required>' . $author->__get('descripcion') . '</textarea>
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
    
    
    
    public function formEditar(Author $author, $errores = []) {
        $errorNombre = isset($errores["nombre"]) ? "<p class='error'>{$errores["nombre"]}</p>" : "";
        $errorDescripcion = isset($errores["descripcion"]) ? "<p class='error'>{$errores["descripcion"]}</p>" : "";
        $errorGeneral = isset($errores["general"]) ? "<p class='error'>{$errores["general"]}</p>" : "";
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo '<main>
            <h1>Editar Autor</h1>
            <div class="formEditar">
                <form action="?Author/form" method="post">
                    <input type="hidden" name="id_autor" value="'. $author->__get('id_autor') .'">
                        
                    <div class="campoEditar">
                        <label for="autorEditar">Autor (*)</label>
                        <input type="text" id="autorEditar" name="nombre" value="'. $author->__get('nombre') .'" required>
                        ' . $errorNombre . '
                    </div>
                            
                    <div class="campoEditar">
                        <label for="descripcionEditar">Descripción (*)</label>
                        <textarea id="descripcionEditar" name="descripcion" required>'. $author->__get('descripcion') .'</textarea>
                        ' . $errorDescripcion . '
                    </div>' .
                         $errorGeneral   . '
                    <button id="btnEditarFrase" type="submit">Enviar</button>
                </form>
            </div>
        </main>';
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    public function showCrearAutor(){
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo '<body id="formEditarFrases">';
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo '<main>
            <h1>Editar Autor</h1>
            <div class="formEditar">
                <form action="?Author/form" method="post">
                    <div class="campoCrear">
                        <label for="autorCrear">Autor (*)</label>
                        <input type="text" id="autorCrear" name="nombre" required>
                    </div>
            
                    <div class="campoCrear">
                        <label for="descripcionCrear">Descripción (*)</label>
                        <textarea id="descripcionCrear" name="descripcion" required></textarea>
                    </div>
            
                    <button id="btnCrearAutor" type="submit">Crear Autor</button>
                </form>
            </div>
        </main>';
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    
    public function formCrear(Author $author, $errores = []) {
        $errorNombre = isset($errores["nombre"]) ? "<p class='error'>{$errores["nombre"]}</p>" : "";
        $errorDescripcion = isset($errores["descripcion"]) ? "<p class='error'>{$errores["descripcion"]}</p>" : "";
        $errorGeneral = isset($errores["general"]) ? "<p class='error'>{$errores["general"]}</p>" : "";
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body id=\"formEditarFrases\">
            <header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo    "</header>
            <main>
                <h1>Editar Autor</h1>
                <div class=\"formEditar\">
                    <form action=\"?Author/form\" method=\"post\">
                        <div class=\"campoCrear\">
                            <label for=\"autorCrear\">Autor (*)</label>
                            <input type=\"text\" id=\"autorCrear\" name=\"nombre\"
                                   value=\"" . htmlspecialchars($author->__get('nombre')) . "\" required>
                            $errorNombre
                        </div>
                        
                        <div class=\"campoCrear\">
                            <label for=\"descripcionCrear\">Descripción (*)</label>
                            <textarea id=\"descripcionCrear\" name=\"descripcion\" required>"
                            . htmlspecialchars($author->__get('descripcion')) . "</textarea>
                            $errorDescripcion
                        </div>
                        $errorGeneral
                        <button id=\"btnCrearAutor\" type=\"submit\">Crear Autor</button>
                    </form>
                </div>
            </main>
            <footer>";
                            include "../inc/footer.php";
                            echo    "</footer>
          </body>
        </html>";
    }
    
      
    public function showDetail($author, $phrases) {
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body class=\"autor-body\">";
        echo "<header class=\"frases-header\">";
        include "../inc/menuMain.php";
        echo "<div class=\"frases-content\">
        <h1>Frases de {$author->__get('nombre')}</h1>
        <p>{$author->__get('descripcion')}</p>
    </div>";
        echo "</header>";
        echo "<main>";
        
        echo "<div class=\"contenedorAutoresFrases\">";
        if (empty($phrases)) {
            echo "<div class='mensajeNoFrases'>
            <p>No hay frases registradas para este autor.</p>
          </div>";
        }else {
            echo "<table class='tablaAutores'>";
            echo "<thead>
                <tr>
                    <th>Frase</th>
                    <th>Tema</th>
                </tr>
              </thead>";
            echo "<tbody>";
            foreach ($phrases as $phrase) {
                $texto = $phrase->__get('texto');
                $temas = $phrase->__get('temas') ?: "Sin tema"; 
                echo "<tr>
                    <td>{$texto}</td>
                    <td>{$temas}</td>
                  </tr>";
            }
            echo "</tbody></table>";
        }
        
        echo "</div>";
        echo "<div>";
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
}
?>
