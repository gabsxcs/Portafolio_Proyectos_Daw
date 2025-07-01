<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


class InversionesView extends View {
    public function __construct() {
        parent::__construct();
    }
    
    public function show($cotizaciones, $cotizacionesAnteriores) {
        if (empty($cotizaciones)) {
            echo "No se pudieron obtener las cotizaciones.";
            return;
        }
        
        echo "<!DOCTYPE html><html lang=\"es\">";
        include "../inc/headMain.php";
        echo "<body>";
        echo "<header class=\"inversiones-header\">";
        include "../inc/menuMain.php";
        echo "<div class=\"inversiones-content\">
            <h1>Inversiones</h1>
            <p>Cotizaciones de Acciones en Tiempo Real</p>
          </div>
         </header>";
        echo "<div class=\"containerTabla\">
            <button onclick=\"window.location.href=window.location.href.split('?')[0]+'?Inversiones/show&refresh=true'\">Actualizar Cotizaciones</button>
            <table id=\"cotizaciones\">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ticker</th>
                        <th>Mercado</th>
                        <th>Última Cotización</th>
                        <th>Divisa</th>
                        <th>Variación</th>
                        <th>Variación (%)</th>
                        <th>Volumen</th>
                        <th>Mínimo</th>
                        <th>Máximo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($cotizaciones as $index => $coti) {
            $ultimaCotizacion = $this->obtenerValorNumerico($coti['ultima_coti']);
            $flecha = "";
            $flechaClase = "";
            
            if (isset($cotizacionesAnteriores[$index])) {
                $anteriorCotizacion = $this->obtenerValorNumerico($cotizacionesAnteriores[$index]['ultima_coti']);
                
                if ($ultimaCotizacion < $anteriorCotizacion) {
                    $flecha = "&#8595;";
                    $flechaClase = "flechaBaja";
                } elseif ($ultimaCotizacion > $anteriorCotizacion) {
                    $flecha = "&#8593;";
                    $flechaClase = "flechaSube";
                } else {
                    $flecha = "&#x3D;";
                    $flechaClase = "flechaIgual";
                }
            }
            
            $variacion = $this->obtenerValorNumerico($coti['variacio']);
            $variacionPercent = $this->obtenerValorNumerico($coti['variacio_percent']);
            $claseVariacion = ($variacion < 0 || $variacionPercent < 0) ? 'baja' : 'sube';
            
            echo "<tr>
                    <td>" . htmlspecialchars($coti['nom']) . "</td>
                    <td>" . htmlspecialchars($coti['ticker']) . "</td>
                    <td>" . htmlspecialchars($coti['mercat']) . "</td>
                    <td>" . htmlspecialchars($coti['ultima_coti']) . " <span class=\"flecha $flechaClase\">$flecha</span></td>
                    <td>" . htmlspecialchars($coti['divisa']) . "</td>
                    <td class=\"$claseVariacion\">" . htmlspecialchars($coti['variacio']) . "</td>
                    <td class=\"$claseVariacion\">" . htmlspecialchars($coti['variacio_percent']) . "%</td>
                    <td>" . htmlspecialchars($coti['volum']) . "</td>
                    <td>" . htmlspecialchars($coti['mínim']) . "</td>
                    <td>" . htmlspecialchars($coti['màxim']) . "</td>
                    <td>" . htmlspecialchars($coti['data']) . "</td>
                    <td>" . htmlspecialchars($coti['hora']) . "</td>
                  </tr>";
        }
        
        echo "</tbody></table></div>";
        echo "<footer>";
        include "../inc/footer.php";
        echo "</footer>";
        echo "</body></html>";
    }
    
    private function obtenerValorNumerico($valor) {
        return floatval(preg_replace('/[^0-9.-]/', '', $valor));
    
    }
}
