<?php
session_start(); 


// Verificar si el usuario ha iniciado sesión, y si no ha iniciado sesion lo lleva a la pagina de login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


function obtenerCotizaciones() {
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
                'nom' => arreglarElemento($columna[1]),
                'ticker' => arreglarElemento($columna[2]),
                'mercat' => arreglarElemento($columna[3]),
                'ultima_coti' => arreglarElemento($columna[5]),
                'divisa' => arreglarElemento($columna[6]),
                'variacio' => arreglarElemento($columna[7]),
                'variacio_percent' => arreglarElemento($columna[8]),
                'volum' => arreglarElemento($columna[9]),
                'mínim' => arreglarElemento($columna[10]),
                'màxim' => arreglarElemento($columna[11]),
                'data' => recortarDato(arreglarElemento($columna[12])),
                'hora' => recortarDato(arreglarElemento($columna[13])),
            ];
        }
    }
    return $tablaInformacion;
}

function arreglarElemento($elemento) {
    return trim(strip_tags($elemento));
}

function recortarDato($elemento) {
    $posPrimerNumero = strcspn($elemento, '0123456789');
    return substr($elemento, $posPrimerNumero, 5);
}

function obtenerValorNumerico($valor) {
    return floatval(preg_replace('/[^0-9.-]/', '', $valor));
}


if (!isset($_SESSION['cotizaciones']) || isset($_GET['refresh'])) {
   
    if (isset($_SESSION['cotizaciones'])) {
        $_SESSION['cotizacionesAnteriores'] = $_SESSION['cotizaciones'];
    }
    $_SESSION['cotizaciones'] = obtenerCotizaciones();
}

$cotizaciones = $_SESSION['cotizaciones'];
$cotizacionesAnteriores = $_SESSION['cotizacionesAnteriores'] ?? []; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inversiones</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<header class="inversiones-header">
    <nav>
        <div class="logo">Gabriela</div>
        <ul>
            <li><a href="index.php">Gabriela</a></li>
            <li><a href="index.php#contact">Contacto</a></li>
            <li><a href="educacion.php">Educación</a></li>
            <li><a href="intereses.php">Intereses</a></li>
            <li><a href="inversiones.php">Inversiones</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="login.php?logout=true">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
            <?php endif; ?> 
        </ul>
    </nav>
    <div class="inversiones-content">
        <h1>Inversiones</h1>
        <p>Cotizaciones de Acciones en Tiempo Real</p>
    </div>
</header>

<div class="containerTabla">
    <button onclick="window.location.href='?refresh=true'">Actualizar Cotizaciones</button>
    <table id="cotizaciones">
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
        <tbody>
            <?php foreach ($cotizaciones as $index => $coti): ?>
                <?php
                
                $ultimaCotizacion = obtenerValorNumerico($coti['ultima_coti']);

                
                $flecha = "";
                $flechaClase = ""; 
                if (isset($cotizacionesAnteriores[$index])) {
                    $anteriorCotizacion = obtenerValorNumerico($cotizacionesAnteriores[$index]['ultima_coti']);
                    
                    if ($ultimaCotizacion < $anteriorCotizacion) {
                        $flecha = "&#8595;"; // Flecha hacia abajo si el valor ha bajado
                        $flechaClase = "flechaBaja";
                    } elseif ($ultimaCotizacion > $anteriorCotizacion) {
                        $flecha = "&#8593;"; // Flecha hacia arriba si el valor ha subido
                        $flechaClase = "flechaSube"; 
                    } else {
                        $flecha = "&#x3D;"; // Igual si el valor es el mismo
                        $flechaClase = "flechaIgual"; 
                    }
                }

                $variacion = obtenerValorNumerico($coti['variacio']);
                $variacionPercent = obtenerValorNumerico($coti['variacio_percent']);
                $claseVariacion = ($variacion < 0 || $variacionPercent < 0) ? 'baja' : 'sube';
                ?>
                <tr>
                    <td><?= htmlspecialchars($coti['nom']) ?></td>
                    <td><?= htmlspecialchars($coti['ticker']) ?></td>
                    <td><?= htmlspecialchars($coti['mercat']) ?></td>
                    <td><?= htmlspecialchars($coti['ultima_coti']) ?> <span class="flecha <?= $flechaClase ?>"><?= $flecha ?></span></td>
                    <td><?= htmlspecialchars($coti['divisa']) ?></td>
                    <td class="<?= $claseVariacion ?>"><?= htmlspecialchars($coti['variacio']) ?></td>
                    <td class="<?= $claseVariacion ?>"><?= htmlspecialchars($coti['variacio_percent']) ?>%</td>
                    <td><?= htmlspecialchars($coti['volum']) ?></td>
                    <td><?= htmlspecialchars($coti['mínim']) ?></td>
                    <td><?= htmlspecialchars($coti['màxim']) ?></td>
                    <td><?= htmlspecialchars($coti['data']) ?></td>
                    <td><?= htmlspecialchars($coti['hora']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<footer>
    <p>&copy; 2024 Gabriela Sandoval Castillo. Todos los derechos reservados.</p>
    <ul class="socials">
        <li><a href="https://www.linkedin.com/in/tu-perfil" target="_blank"><i class="fab fa-linkedin"></i></a></li>
        <li><a href="https://www.instagram.com/gabsxcs.s/" target="_blank"><i class="fab fa-instagram"></i></a></li>
    </ul>
</footer>

</body>
</html>