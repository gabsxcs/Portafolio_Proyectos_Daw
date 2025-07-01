<?php

class MantenimientoView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show($params) {
        
        $eventos = $params['eventos'];
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body class=\"mantenimiento-body\">";
        echo "<header class=\"mantenimiento-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo "<main class=\"main-mantenimiento\">";
        
        echo "<div class='botonAñadirContenedor'>";
        echo "<button class=\"botonAñadir\" onclick=\"window.location.href='?Eventos/show'\">Nuevo evento</button>";
        echo "<div class=\"contenedorBusqueda\">";
        echo "<form method='GET' action='' class='formBusqueda'>";
        echo "<input type='hidden' name='Mantenimiento/show'>";
        echo "<input type='text' name='buscar' placeholder='Buscar por título o etiqueta' value='" . htmlspecialchars($params['buscar'] ?? '') . "' class='inputBuscar'>";
        echo "<button type='submit' class='btnBuscar'>Buscar</button>";
        echo "<button type='button' class='btnResetear' onclick=\"window.location.href='?Mantenimiento/show'\">Restablecer</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        
        echo "<h1>Lista de Eventos</h1>";
        
        if (empty($eventos)) {
            echo "<div class=\"noEventosContenedor\">";
            echo "<p class='noEventos'>No hay eventos registrados. Crea un nuevo evento para verlo aquí. :)</p>";
            echo "</div>";
        } else {
            echo "<div class='listaMantenimiento'>";
            
            $i = 0;
            
            foreach ($eventos as $event) {
                $clasePijama = ($i % 2 == 0) ? "pijama1" : "pijama2";
                echo "<div class='evento $clasePijama'>";
                
                echo "<div class='infoEvento'>";
                echo "<p class='eventoTitulo'>";
                echo "<strong>" . htmlspecialchars($event['titulo']) . "</strong>";
                if (!empty($event['etiqueta'])) {
                    echo " - <span class='eventoEtiqueta'>" . htmlspecialchars($event['etiqueta']) . "</span>";
                }
                echo "</p>";
                
                echo "<p>" . htmlspecialchars($event['descripcion']) . "</p>";
                
                echo "<p class='fechaEvento'><em>Inicio:</em> " . date("d/m/Y H:i", strtotime($event['fechaHoraInicio'])) .
                " - <em>Fin:</em> " . date("d/m/Y H:i", strtotime($event['fechaHoraFin'])) . "</p>";
                echo "</div>";
                
                echo "<div class='botones'>";
                echo "<a href='?Editar/show&id=" . $event['id'] . "' class='btnEditar'>Editar</a>";
                echo "<a href='?Mantenimiento/eliminar&id=" . $event['id'] . "' class='btnEliminar'onclick=\"return confirm('¿Estás seguro de que deseas eliminar este evento?');\">Eliminar</a>";
                
                echo "</div>";
                
                echo "</div>";
                
                $i++;
            }
            
            echo "</div>";
            
        }
        
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
        
    }
}

?>
