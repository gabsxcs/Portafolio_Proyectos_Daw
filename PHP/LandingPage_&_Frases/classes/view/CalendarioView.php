<?php

class CalendarioView extends View {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function show($params) {
        
        $año = $params['año'];
        $mes = $params['mes'];
        $eventos = $params['eventos'];
        
        $meses = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];
        
        $fechaActual = new DateTime("$año-$mes-01");
        $mesName = $meses[$fechaActual->format('F')] . " " . $fechaActual->format('Y');
        
        $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $primerDiaSemana = (int)$fechaActual->format('N');
        $diasEnMes = (int)$fechaActual->format('t');
        $prevMesDias = (int)(clone $fechaActual)->modify('-1 month')->format('t');
        
        $diasConEventos = [];
        
        foreach ($eventos as $event) {
            $fechaEvento = date("Y-m-d", strtotime($event['fechaHoraInicio']));
            $diaEvento = (int)date("j", strtotime($fechaEvento));
            $diasConEventos[$diaEvento] = true;
        }
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"calendario-header\">";
        include "../inc/menuMain.php";
        echo "</header>";
        echo "<main class=\"main-calendar\">";
        echo "<div class=\"calendar\">";
        echo "<div class=\"calendar-header\">";
        
        $prevMes = $mes == 1 ? 12 : $mes - 1;
        $prevAño = $mes == 1 ? $año - 1 : $año;
        $nextMes = $mes == 12 ? 1 : $mes + 1;
        $nextAño = $mes == 12 ? $año + 1 : $año;
        
        echo "<a href=\"?Calendario/show/$prevMes/$prevAño\">&#9664;</a>";
        echo "<span>" . htmlspecialchars($mesName) . "</span>";
        echo "<a href=\"?Calendario/show/$nextMes/$nextAño\">&#9654;</a>";
        
        echo "</div>";
        
        echo "<div class=\"calendar-grid\">";
        
        foreach ($diasSemana as $dia) {
            echo "<div class='dia'>$dia</div>";
        }
        
        $diaContador = 1;
        
        for ($i = 1; $i < $primerDiaSemana; $i++) {
            $prevDia = $prevMesDias - ($primerDiaSemana - $i - 1);
            echo "<div class='fechaInactiva'>$prevDia</div>";
            $diaContador++;
        }
        
        $usuarioLogueado = isset($_SESSION['username']);
        
        for ($i = 1; $i <= $diasEnMes; $i++) {
            $esHoy = (int)date('Y') === $año && (int)date('n') === $mes && (int)date('j') === $i;
            
            $tieneEvento = isset($diasConEventos[$i]);
            
            if ($esHoy) {
                $clase = 'diaActual';
            } elseif ($usuarioLogueado && $tieneEvento) {
                $clase = 'diaConEvento';
            } else {
                $clase = 'fecha';
            }
            
            echo "<div class='$clase'>$i</div>";
            $diaContador++;
        }
        
        while ($diaContador <= 42) {
            echo "<div class='fechaInactiva'>" . ($diaContador - $diasEnMes) . "</div>";
            $diaContador++;
        }
        
        echo "</div>";
        echo "</div>";
        
        echo "<div class=\"eventosLista\">";
        echo "<div class=\"eventosCabecera\">";
        echo "<button onclick=\"window.location.href='?Mantenimiento/show'\">Ver todos</button>";
        echo "<h2>Eventos Próximos</h2>";
        echo "<button onclick=\"window.location.href='?Eventos/show'\">Añadir</button>";
        echo "</div>";
        echo "<ul>";
        
        if (!$usuarioLogueado) {
            echo "<li class=\"noEventos\">Inicia sesión para ver los próximos eventos</li>";
        } else if (empty($eventos)) {
            echo "<li class=\"noEventos\">No hay eventos por ahora</li>";
        } else {
            foreach ($eventos as $event) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($event['titulo']) . "</strong><br>";
                echo htmlspecialchars($event['descripcion']) . "<br>";
                if (!empty($event['etiqueta'])) {
                    echo "<p class='eventoEtiqueta'>Etiqueta: " . htmlspecialchars($event['etiqueta']) . "</p>";
                }
                echo "<small>Inicio: " . date("d/m/Y H:i", strtotime($event['fechaHoraInicio'])) . " - Fin: " . date("d/m/Y H:i", strtotime($event['fechaHoraFin'])) . "</small>";
                echo "</li>";
            }
        }
        
        echo "</ul>";
        echo "</div>";
        
        echo "</main>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }

}
