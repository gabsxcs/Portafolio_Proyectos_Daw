<?php

class EventosView  extends  View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show() {

        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo "<main class='eventoNuevoForm'>";
        
        echo "<div class='formContenidor'>";
        echo "<h2>Agregar Evento</h2>";
        echo "<form action='#' method='post'>";
        echo "<div class='formDato'>";
        echo "<label for='nombre'>Nombre del Evento (*)</label>";
        echo "<input type='text' id='nombre' name='nombre' >";
        echo "</div>";
        
        echo "<div class='formDato'>";
        echo "<label for='descripcion'>Descripción (*)</label>";
        echo "<textarea id='descripcion' name='descripcion'></textarea>";
        echo "</div>";
        
        echo "<div class='formDato'>";
        echo "<label for='inicio'>Fecha y Hora de Inicio (*)</label>";
        echo "<input type='datetime-local' id='inicio' name='inicio' >";
        echo "</div>";
        
        echo "<div class='formDato'>";
        echo "<label for='fin'>Fecha y Hora de Fin (*)</label>";
        echo "<input type='datetime-local' id='fin' name='fin' >";
        echo "</div>";
        
        echo "<div class='formDato'>";
        echo "<label for='etiqueta'>Etiqueta del Evento (Opcional)</label>";
        echo "<select id='etiqueta' name='etiqueta' >";
        echo "<option value='' disabled selected>Selecciona una etiqueta</option>";
        echo "<option value='Reunion'>Reunión</option>";
        echo "<option value='Examen'>Examen</option>";
        echo "<option value='Taller'>Taller</option>";
        echo "<option value='Social'>Evento Social</option>";
        echo "<option value='Cumpleaños'>Cumpleaños</option>";
        echo "<option value='Otros'>Otros</option>";
        echo "</select>";
        echo "</div>";
        
        echo "<div class='formDato casillaCheck'>";
        echo "<input type='checkbox' id='diaCompleto' name='diaCompleto'>";
        echo "<label for='diaCompleto'>Todo el día</label>";
        echo "</div>";
        echo "<button type='submit'>Guardar Evento</button>";
        echo "</form>";
        echo "</div>";
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    public function form(Evento $evento) {
        
        $errorNombre = ($evento instanceof Evento && isset($evento->errors["nombre"]))
        ? "<p class=\"error\">{$evento->__get('errors')['nombre']}</p>"
        : "";
        
        $errorDescripcion = ($evento instanceof Evento && isset($evento->errors["descripcion"]))
        ? "<p class=\"error\">{$evento->__get('errors')['descripcion']}</p>"
        : "";
        
        $errorInicio = ($evento instanceof Evento && isset($evento->errors["inicio"]))
        ? "<p class=\"error\">{$evento->__get('errors')['inicio']}</p>"
        : "";
        
        $errorFin = ($evento instanceof Evento && isset($evento->errors["fin"]))
        ? "<p class=\"error\">{$evento->__get('errors')['fin']}</p>"
        : "";
        
        $errorDiaCompleto = ($evento instanceof Evento && isset($evento->errors["diaCompleto"]))
        ? "<p class=\"error\">{$evento->__get('errors')['diaCompleto']}</p>"
        : "";
        
        $errorEtiqueta = ($evento instanceof Evento && isset($evento->errors["etiqueta"]))
        ? "<p class=\"error\">{$evento->__get('errors')['etiqueta']}</p>"
        : "";
        
        $errorSesion= ($evento instanceof Evento && isset($evento->errors["sesion"]))
        ? "<p class=\"error\">{$evento->__get('errors')['sesion']}</p>"
        : "";
        
        if (is_null($evento->__get("errors"))) {
            $info = "<div class=\"mensajeExito\"><p>Evento guardado correctamente.</p></div><br />";
        } else {
            $info ="<label>(*)Campo obligatorio</label><br />";
        }
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"eventos-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo "<main class='eventoNuevoForm'>";
        
        echo "<div class='formContenidor'>";
        echo "<h2>Agregar Evento</h2>";
        echo "<form action='#' method='post'>";
        
        echo $errorSesion;
        
        echo "<div class='formDato'>";
        echo "<label for='nombre'>Nombre del Evento (*) </label>";
        echo "<input type='text' id='nombre' name='nombre' value=\"{$evento->__get('nombre')}\">";
        echo $errorNombre;
        echo "</div>";
        
        
        echo "<div class='formDato'>";
        echo "<label for='descripcion'>Descripción (*) </label>";
        echo "<textarea id='descripcion' name='descripcion'>{$evento->__get('descripcion')}</textarea>";
        echo $errorDescripcion;
        echo "</div>";
        
        
        echo "<div class='formDato'>";
        echo "<label for='inicio'>Fecha y Hora de Inicio (*) </label>";
        echo "<input type='datetime-local' id='inicio' name='inicio' value=\"{$evento->__get('inicio')}\">";
        echo $errorInicio;
        echo "</div>";
        
        
        echo "<div class='formDato'>";
        echo "<label for='fin'>Fecha y Hora de Fin (*)</label>";
        echo "<input type='datetime-local' id='fin' name='fin' value=\"{$evento->__get('fin')}\">";
        echo $errorFin;
        echo "</div>";
        
        
        echo "<div class='formDato'>";
        echo "<label for='etiqueta'>Etiqueta del Evento (Opcional)</label>";
        echo "<select id='etiqueta' name='etiqueta'>";
        echo "<option value='' disabled selected>Selecciona una etiqueta</option>";
        echo "<option value='Reunion'" . ($evento->__get('etiqueta') == 'Reunion' ? ' selected' : '') . ">Reunión</option>";
        echo "<option value='Examen'" . ($evento->__get('etiqueta') == 'Examen' ? ' selected' : '') . ">Examen</option>";
        echo "<option value='Taller'" . ($evento->__get('etiqueta') == 'Taller' ? ' selected' : '') . ">Taller</option>";
        echo "<option value='Social'" . ($evento->__get('etiqueta') == 'Social' ? ' selected' : '') . ">Evento Social</option>";
        echo "<option value='Cumpleaños'" . ($evento->__get('etiqueta') == 'Cumpleaños' ? ' selected' : '') . ">Cumpleaños</option>";
        echo "<option value='Otros'" . ($evento->__get('etiqueta') == 'Otros' ? ' selected' : '') . ">Otros</option>";
        echo "</select>";
        echo $errorEtiqueta;
        echo "</div>";
        
        
        echo "<div class='formDato casillaCheck'>";
        echo "<input type='checkbox' id='diaCompleto' name='diaCompleto' " . ($evento->__get('diaCompleto') ? 'checked' : '') . ">";
        echo "<label for='diaCompleto'>Todo el día</label>";
        echo $errorDiaCompleto;
        echo "</div>";
        
        $info;
        
        echo "<button type='submit'>Guardar Evento</button>";
        
        echo "</form>";
        echo "</div>";
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
        
    }
}
