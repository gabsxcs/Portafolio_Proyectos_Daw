<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define("__ROOT__", __DIR__ . "/");

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/phpqrcode/qrlib.php';

use Evento\Controller\EntradaController;
use Evento\Controller\EventoController;
use Evento\Service\PdfGenerador;
use Evento\Entity\Evento;
use Doctrine\ORM\EntityManager;
use Evento\Entity\Categoria;
use Evento\Entity\Entrada;
use Evento\Entity\Compra;

/*
 IMPORTANTE: Ejemplos de como funcionan los params
 Para generar pdf(ejemplo):
/index.php?numRef=100005

Para generar xml(ejemplo):
/index.php?data=2025-06-15
 
 */

$entityManager = require __DIR__ . '/src/bootstrap.php';

try{
        
    $eventosData = [
        [
            'nombre' => 'BTS World Tour 2025',
            'fecha' => '2025-06-15',
            'hora' => '20:00:00',
            'lugar' => 'Estadi Olímpic',
            'direccion' => 'Barcelona, España',
            'descripcion' => 'El grupo de K-pop más famoso del mundo regresa del servicio militar con su nueva gira mundial',
            'imagen' => 'bts.png',
            'artista' => 'BTS',
        ],
        [
            'nombre' => 'The Eras Tour',
            'fecha' => '2025-06-15',
            'hora' => '19:30:00',
            'lugar' => 'Wembley Stadium',
            'direccion' => 'London, Reino Unido',
            'descripcion' => 'Taylor Swift presenta un recorrido por todos los álbumes de su carrera',
            'imagen' => 'taylor.png',
            'artista' => 'Taylor Swift',
        ],
        [
            'nombre' => 'Oceans blvd Live',
            'fecha' => '2025-06-15',
            'hora' => '21:00:00',
            'lugar' => 'Stade de France',
            'direccion' => 'París, Francia',
            'descripcion' => 'Lana Del Rey en gira presentando su ultimo album ',
            'imagen' => 'lana.png',
            'artista' => 'Lana Del Rey',
        ],
        [
            'nombre' => 'Red Moon in Venus Tour',
            'fecha' => '2025-07-10',
            'hora' => '20:30:00',
            'lugar' => 'Madison Square Garden',
            'direccion' => 'New York, Estados Unidos',
            'descripcion' => 'Kali Uchis presentando su álbum en vivo',
            'imagen' => 'kali.png',
            'artista' => 'Kali Uchis',
        ],
        [
            'nombre' => 'Secret of Us Tour',
            'fecha' => '2025-08-05',
            'hora' => '19:00:00',
            'lugar' => 'Ziggo Dome',
            'direccion' => 'Amsterdam, Países Bajos',
            'descripcion' => 'Gracie Abrams en gira mundial de su ultimo album',
            'imagen' => 'gracie.png',
            'artista' => 'Gracie Abrams',
        ],
        [
            'nombre' => 'Sweat Tour',
            'fecha' => '2025-09-12',
            'hora' => '20:00:00',
            'lugar' => 'O2 Arena',
            'direccion' => 'London, Reino Unido',
            'descripcion' => 'Troye Sivan presenta su más reciente álbum con su amiga',
            'imagen' => 'troye.png',
            'artista' => 'Troye Sivan',
        ],
    ];

    $eventos = [];

    foreach ($eventosData as $data) {
        $evento = new Evento();
        $evento->setNombre($data['nombre']);
        $evento->setFecha(new DateTime($data['fecha']));
        $evento->setHora(new DateTime($data['hora']));
        $evento->setLugar($data['lugar']);
        $evento->setDireccion($data['direccion']);
        $evento->setDescripcion($data['descripcion']);
        $evento->setImagen($data['imagen']);
        $evento->setArtista($data['artista']);
        $entityManager->persist($evento);
        $eventos[] = $evento;
    }

    $categoriasData = [
        ['nombre' => 'VIP', 'descripcion' => 'Acceso a soundcheck y meet & greet', 'valor' => 820, 'evento' => 0],
        ['nombre' => 'Platinum', 'descripcion' => 'Asientos preferenciales y merchandising exclusivo', 'valor' => 600, 'evento' => 0],
        ['nombre' => 'General A', 'descripcion' => 'Zona general con buena visibilidad', 'valor' => 200, 'evento' => 0],
        ['nombre' => 'General B', 'descripcion' => 'Zona general con visibilidad estándar', 'valor' => 100, 'evento' => 0],

        ['nombre' => 'VIP Package', 'descripcion' => 'Incluye asiento preferencial, merchandising exclusivo y entrada temprana', 'valor' => 700, 'evento' => 1],
        ['nombre' => 'Platinum', 'descripcion' => 'Asientos con excelente visibilidad', 'valor' => 600, 'evento' => 1],
        ['nombre' => 'Sección Oro', 'descripcion' => 'Buena ubicación con visibilidad clara', 'valor' => 300, 'evento' => 1],
        ['nombre' => 'Sección Plata', 'descripcion' => 'Asientos con visibilidad estándar', 'valor' => 200, 'evento' => 1],

        ['nombre' => 'Premium Experience', 'descripcion' => 'Primera fila y paquete de merchandising', 'valor' => 400, 'evento' => 2],
        ['nombre' => 'Gold Circle', 'descripcion' => 'Zona cercana al escenario', 'valor' => 200, 'evento' => 2],
        ['nombre' => 'Grada', 'descripcion' => 'Ubicación con buena visibilidad', 'valor' => 100, 'evento' => 2],
        ['nombre' => 'General', 'descripcion' => 'Acceso general al concierto', 'valor' => 70, 'evento' => 2],

        ['nombre' => 'Meet & Greet', 'descripcion' => 'Incluye encuentro con la artista y ubicación premium', 'valor' => 300, 'evento' => 3],
        ['nombre' => 'Zona VIP', 'descripcion' => 'Área exclusiva cercana al escenario', 'valor' => 250, 'evento' => 3],
        ['nombre' => 'Grada', 'descripcion' => 'Buena ubicación', 'valor' => 120, 'evento' => 3],
        ['nombre' => 'General', 'descripcion' => 'Acceso general al concierto', 'valor' => 70, 'evento' => 3],

        ['nombre' => 'VIP Experience', 'descripcion' => 'Soundcheck exclusivo y merchandising', 'valor' => 300, 'evento' => 4],
        ['nombre' => 'Grada', 'descripcion' => 'Asientos con visibilidad privilegiada', 'valor' => 120, 'evento' => 4],
        ['nombre' => 'General', 'descripcion' => 'Acceso general al evento', 'valor' => 60, 'evento' => 4],

        ['nombre' => 'Meet & Greet', 'descripcion' => 'Incluye foto con el artista y asiento premium', 'valor' => 400, 'evento' => 5],
        ['nombre' => 'Premium', 'descripcion' => 'Asientos preferenciales', 'valor' => 120, 'evento' => 5],
        ['nombre' => 'General', 'descripcion' => 'Acceso general al concierto', 'valor' => 70, 'evento' => 5],
    ];

    $categorias = [];

    foreach ($categoriasData as $data) {
        $categoria = new Categoria();
        $categoria->setNombre($data['nombre']);
        $categoria->setDescripcion($data['descripcion']);
        $categoria->setValor($data['valor']);
        $categoria->setEvento($eventos[$data['evento']]);
        $entityManager->persist($categoria);
        $categorias[] = $categoria;
    }

    $entradasData = [
        ['codigo' => 100001, 'evento' => 0, 'seccion' => 'A', 'fila' => 1, 'asiento' => 5, 'categoria' => 0, 'estado' => 'disponible'],
        ['codigo' => 100002, 'evento' => 0, 'seccion' => 'A', 'fila' => 1, 'asiento' => 6, 'categoria' => 0, 'estado' => 'disponible'],
        ['codigo' => 100003, 'evento' => 0, 'seccion' => 'B', 'fila' => 3, 'asiento' => 10, 'categoria' => 1, 'estado' => 'disponible'],
        ['codigo' => 100004, 'evento' => 0, 'seccion' => 'B', 'fila' => 3, 'asiento' => 11, 'categoria' => 1, 'estado' => 'disponible'],
        ['codigo' => 100005, 'evento' => 0, 'seccion' => 'C', 'fila' => 5, 'asiento' => 8, 'categoria' => 2, 'estado' => 'disponible'],

        ['codigo' => 200001, 'evento' => 1, 'seccion' => 'Diamond', 'fila' => 1, 'asiento' => 3, 'categoria' => 4, 'estado' => 'disponible'],
        ['codigo' => 200002, 'evento' => 1, 'seccion' => 'Diamond', 'fila' => 1, 'asiento' => 4, 'categoria' => 4, 'estado' => 'disponible'],
        ['codigo' => 200003, 'evento' => 1, 'seccion' => 'Platinum', 'fila' => 2, 'asiento' => 7, 'categoria' => 5, 'estado' => 'disponible'],
        ['codigo' => 200004, 'evento' => 1, 'seccion' => 'Platinum', 'fila' => 2, 'asiento' => 8, 'categoria' => 5, 'estado' => 'disponible'],
        ['codigo' => 200005, 'evento' => 1, 'seccion' => 'Gold', 'fila' => 4, 'asiento' => 12, 'categoria' => 6, 'estado' => 'disponible'],

        ['codigo' => 300001, 'evento' => 2, 'seccion' => 'Premium', 'fila' => 1, 'asiento' => 7, 'categoria' => 8, 'estado' => 'disponible'],
        ['codigo' => 300002, 'evento' => 2, 'seccion' => 'Premium', 'fila' => 1, 'asiento' => 8, 'categoria' => 8, 'estado' => 'disponible'],
        ['codigo' => 300003, 'evento' => 2, 'seccion' => 'Gold', 'fila' => 3, 'asiento' => 5, 'categoria' => 9, 'estado' => 'disponible'],
        ['codigo' => 300004, 'evento' => 2, 'seccion' => 'Gold', 'fila' => 3, 'asiento' => 6, 'categoria' => 9, 'estado' => 'disponible'],
        ['codigo' => 300005, 'evento' => 2, 'seccion' => 'Grada', 'fila' => 5, 'asiento' => 10, 'categoria' => 10, 'estado' => 'disponible'],

        ['codigo' => 400001, 'evento' => 3, 'seccion' => 'VIP', 'fila' => 1, 'asiento' => 5, 'categoria' => 12, 'estado' => 'disponible'],
        ['codigo' => 400002, 'evento' => 3, 'seccion' => 'VIP', 'fila' => 1, 'asiento' => 6, 'categoria' => 12, 'estado' => 'disponible'],
        ['codigo' => 400003, 'evento' => 3, 'seccion' => 'Premium', 'fila' => 2, 'asiento' => 9, 'categoria' => 13, 'estado' => 'disponible'],
        ['codigo' => 400004, 'evento' => 3, 'seccion' => 'Premium', 'fila' => 2, 'asiento' => 10, 'categoria' => 13, 'estado' => 'disponible'],
        ['codigo' => 400005, 'evento' => 3, 'seccion' => 'Grada', 'fila' => 5, 'asiento' => 7, 'categoria' => 14, 'estado' => 'disponible'],

        ['codigo' => 500001, 'evento' => 4, 'seccion' => 'VIP', 'fila' => 1, 'asiento' => 3, 'categoria' => 16, 'estado' => 'disponible'],
        ['codigo' => 500002, 'evento' => 4, 'seccion' => 'VIP', 'fila' => 1, 'asiento' => 4, 'categoria' => 16, 'estado' => 'disponible'],
        ['codigo' => 500003, 'evento' => 4, 'seccion' => 'Grada', 'fila' => 3, 'asiento' => 8, 'categoria' => 17, 'estado' => 'disponible'],
        ['codigo' => 500004, 'evento' => 4, 'seccion' => 'Grada', 'fila' => 3, 'asiento' => 9, 'categoria' => 17, 'estado' => 'disponible'],
        ['codigo' => 500005, 'evento' => 4, 'seccion' => 'Grada', 'fila' => 4, 'asiento' => 5, 'categoria' => 17, 'estado' => 'disponible'],

        ['codigo' => 600001, 'evento' => 5, 'seccion' => 'VIP', 'fila' => 1, 'asiento' => 5, 'categoria' => 19, 'estado' => 'disponible'],
        ['codigo' => 600002, 'evento' => 5, 'seccion' => 'VIP', 'fila' => 1, 'asiento' => 6, 'categoria' => 19, 'estado' => 'disponible'],
        ['codigo' => 600003, 'evento' => 5, 'seccion' => 'Premium', 'fila' => 2, 'asiento' => 3, 'categoria' => 20, 'estado' => 'disponible'],
        ['codigo' => 600004, 'evento' => 5, 'seccion' => 'Premium', 'fila' => 2, 'asiento' => 4, 'categoria' => 20, 'estado' => 'disponible'],
        ['codigo' => 600005, 'evento' => 5, 'seccion' => 'Premium', 'fila' => 3, 'asiento' => 7, 'categoria' => 20, 'estado' => 'disponible'],
    ];

    $entradas = [];
    foreach ($entradasData as $data) {
        $entrada = new Entrada();
        $entrada->setCodigoReferencia($data['codigo']);
        $entrada->setEvento($eventos[$data['evento']]);
        $entrada->setSeccion($data['seccion']);
        $entrada->setFila($data['fila']);
        $entrada->setAsientoNumero($data['asiento']);
        $entrada->setCategoria($categorias[$data['categoria']]);
        $entrada->setEstado($data['estado']);
        
        $entityManager->persist($entrada);
        $entradas[] = $entrada;
    }

    $comprasData = [
        [
            'nombre' => 'Pepito perez',
            'email' => 'pepito.perez@email.com',
            'tarjeta' => '4532123456789012',
            'fecha' => '2024-05-10 14:30:45',
            'total' => 1460
        ],
        [
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@email.com',
            'tarjeta' => '5412345678901234',
            'fecha' => '2024-05-11 10:15:22',
            'total' => 900
        ],
        [
            'nombre' => 'Ana Martin',
            'email' => 'ana.martin@email.com',
            'tarjeta' => '3712345678901234',
            'fecha' => '2024-05-12 16:45:30',
            'total' => 300
        ],
        [
            'nombre' => 'Maria perez',
            'email' => 'maria.perez@email.fr',
            'tarjeta' => '4921568749321567',
            'fecha' => '2024-05-13 09:22:15',
            'total' => 370
        ],
        [
            'nombre' => 'Dylan Sprouse',
            'email' => 'dylan.sprouse@email.co.uk',
            'tarjeta' => '5687123498761234',
            'fecha' => '2024-05-14 11:05:40',
            'total' => 420
        ],
        [
            'nombre' => 'Emma Stone',
            'email' => 'emma.stone@email.com',
            'tarjeta' => '4111567891234567',
            'fecha' => '2024-05-15 13:40:18',
            'total' => 240
        ],
        [
            'nombre' => 'Amy March',
            'email' => 'amy.march@email.de',
            'tarjeta' => '3715678941236547',
            'fecha' => '2024-05-16 15:10:35',
            'total' => 1200
        ],
        [
            'nombre' => 'Colin Farrell',
            'email' => 'collin.farrell@email.es',
            'tarjeta' => '5412367894561238',
            'fecha' => '2024-05-17 17:25:52',
            'total' => 500
        ],
        [
            'nombre' => 'Oliver Stone',
            'email' => 'oliver.stone@email.com',
            'tarjeta' => '6011453278965412',
            'fecha' => '2024-05-18 10:30:15',
            'total' => 170
        ],
        [
            'nombre' => 'Florence Pugh',
            'email' => 'florence.pugh@email.se',
            'tarjeta' => '4532789456123078',
            'fecha' => '2024-05-19 12:15:28',
            'total' => 190
        ]
    ];

    $compras = [];
    foreach ($comprasData as $data) {
        $compra = new Compra();
        $compra->setNombreComprador($data['nombre']);
        $compra->setEmailComprador($data['email']);
        $compra->setNumeroTarjeta($data['tarjeta']);
        $compra->setFechaCompra(new DateTime($data['fecha']));
        $compra->setTotalCompra($data['total']);
        $entityManager->persist($compra);
        $compras[] = $compra;
    }

    $compraEntradaData = [
        [0, 0], [0, 1],
        
        [1, 5], [1, 6],

        [2, 10], [2, 11],
        
        [3, 15], [3, 16],
        
        [4, 20], [4, 21],
        
        [5, 25], [5, 26],
        
        [6, 2], [6, 3],
        
        [7, 7], [7, 8],
        
        [8, 12], [8, 13],
        
        [9, 17], [9, 18]
    ];
    
    foreach ($compraEntradaData as $relacion) {
        $compra = $compras[$relacion[0]];
        $entrada = $entradas[$relacion[1]];
        
        $compra->addEntrada($entrada);
        $entrada->setEstado('activa');
        
        $entityManager->persist($compra);
        $entityManager->persist($entrada);
    }


    $entityManager->flush();
    echo "Datos insertados correctamente.<br>";


} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$pdfGenerador = new PdfGenerador(__DIR__ . '/tmp/mpdf');


$numRef = $_GET['numRef'] ?? null;
$data = $_GET['data'] ?? null;

/**
 * Depende del param que haya en la url genera una entrada o un xml con los eventos de esa fecha especificada
 */
if ($numRef !== null) {
    $entradaController = new EntradaController($entityManager, $pdfGenerador);
    $entradaController->generarPdfEntrada();
} elseif ($data !== null) {
    $eventoController = new EventoController($entityManager);
    $eventoController->generarXmlDelDia();
} else {
    http_response_code(400);
    echo "Para generar una entrada en pdf usa <code>?numRef=CODIGO</code> o usa <code>?data=YYYY-MM-DD</code> si quieres una lista de evento en un dia determinado en XML.";
}
