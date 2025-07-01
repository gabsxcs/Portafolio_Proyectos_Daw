<?php
class InversionesModel {
    
    public function __construct() {
    }
    
    public function getCotizaciones() {
        $link = "https://www.inversis.com/inversiones/productos/cotizaciones-nacionales&pathMenu=2_3_0&esLH=N";
        $html = file_get_contents($link);
        
        if ($html === false) {
            return [];
        }
        
        $etiquetaTabla = '<table id="tabCotizaciones"';
        $etiquetaTablaFin = '</table>';
        
        $posInicial = strpos($html, $etiquetaTabla);
        if ($posInicial === false) {
            return [];
        }
        
        $posFinal = strpos($html, $etiquetaTablaFin, $posInicial);
        if ($posFinal === false) {
            return [];
        }
        
        $tablaContenido = substr($html, $posInicial, $posFinal - $posInicial + strlen($etiquetaTablaFin));
        $posTbodyInicial = strpos($tablaContenido, '<tbody>');
        $posTbodyFinal = strpos($tablaContenido, '</tbody>', $posTbodyInicial);
        if ($posTbodyInicial === false || $posTbodyFinal === false) {
            return [];
        }
        
        $tbodyContenido = substr($tablaContenido, $posTbodyInicial, $posTbodyFinal - $posTbodyInicial + strlen('</tbody>'));
        $tbodyContenido = preg_replace('/<td[^>]*>/', '<td>', $tbodyContenido);
        $tbodyContenido = preg_replace('/\s+/', ' ', $tbodyContenido);
        
        $filaTabla = explode('<tr', $tbodyContenido);
        $tablaInformacion = [];
        
        foreach ($filaTabla as $fila) {
            $columna = explode('<td>', $fila);
            if (count($columna) >= 13) {
                $tablaInformacion[] = [
                    'nom' => $this->arreglarElemento($columna[1]),
                    'ticker' => $this->arreglarElemento($columna[2]),
                    'mercat' => $this->arreglarElemento($columna[3]),
                    'ultima_coti' => $this->arreglarElemento($columna[5]),
                    'divisa' => $this->arreglarElemento($columna[6]),
                    'variacio' => $this->arreglarElemento($columna[7]),
                    'variacio_percent' => $this->arreglarElemento($columna[8]),
                    'volum' => $this->arreglarElemento($columna[9]),
                    'mínim' => $this->arreglarElemento($columna[10]),
                    'màxim' => $this->arreglarElemento($columna[11]),
                    'data' => $this->recortarDato($this->arreglarElemento($columna[12])),
                    'hora' => $this->recortarDato($this->arreglarElemento($columna[13])),
                ];
            }
        }
        return $tablaInformacion;
    }
    
    private function arreglarElemento($elemento) {
        return trim(strip_tags($elemento));
    }
    
    private function recortarDato($elemento) {
        $posPrimerNumero = strcspn($elemento, '0123456789');
        return substr($elemento, $posPrimerNumero, 5);
    }
}
?>
