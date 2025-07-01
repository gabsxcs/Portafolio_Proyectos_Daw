<?php
 
ini_set('display_errors', 1);
error_reporting(E_ALL);

define("__ROOT__", __DIR__ . "/");

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/phpqrcode/qrlib.php';

use Evento\Controller\EsdevenimentController;
use Evento\Controller\FrontController;
use Evento\Controller\TicketController;
use Evento\Service\PdfGenerador;
use Evento\Entity\Administrador;
use Evento\Entity\Compra;
use Evento\Entity\Esdeveniment;
use Evento\Entity\Localitzacio;
use Evento\Entity\Seient;
use Evento\Entity\Ticket;
use Evento\Entity\Usuari;
 
/*
 IMPORTANTE: LEER EL PDF ADJUNTADO

 Al hacer el insert, se debe comentar el try catch para que no salte error por meter los datos
 
*/


$entityManager = require __DIR__ . '/src/bootstrap.php';
/*
try{
  

    //LOCALITZACIO
    $olimpicBarcelona = new Localitzacio();
    $olimpicBarcelona->setName('Estadio Olímpico Lluís Companys');
    $olimpicBarcelona->setAddress('Avinguda de l\'Estadi, 60');
    $olimpicBarcelona->setCity('Barcelona');
    $olimpicBarcelona->setCapacity(67000);
    $entityManager->persist($olimpicBarcelona);
    
    
    $bernabeu = new Localitzacio();
    $bernabeu->setName('Santiago Bernabéu');
    $bernabeu->setAddress('Avenida de Concha Espina, 1');
    $bernabeu->setCity('Madrid');
    $bernabeu->setCapacity(81044);
    $entityManager->persist($bernabeu);
    

    $wembley = new Localitzacio();
    $wembley->setName('Wembley Stadium');
    $wembley->setAddress('Wembley, London HA9 0WS');
    $wembley->setCity('London');
    $wembley->setCapacity(90000);
    $entityManager->persist($wembley);
    
    
    $metlife = new Localitzacio();
    $metlife->setName('MetLife Stadium');
    $metlife->setAddress('1 MetLife Stadium Dr, East Rutherford');
    $metlife->setCity('New Jersey');
    $metlife->setCapacity(82500);
    $entityManager->persist($metlife);
    
    
    $defenseArena = new Localitzacio();
    $defenseArena->setName('Defense Arena');
    $defenseArena->setAddress('1 Boulevard de Paris, 92000 Nanterre');
    $defenseArena->setCity('Paris');
    $defenseArena->setCapacity(40000);
    $entityManager->persist($defenseArena);
    

    //ESDEVENIMENTS
    $taylorSwift = new Esdeveniment();
    $taylorSwift->setTitle('The Eras Tour');
    $taylorSwift->setDescription('La gira más espectacular de Taylor Swift, interpretando sus eras');
    $taylorSwift->setStartTime(new DateTime('2025-07-15 20:00:00'));
    $taylorSwift->setEndTime(new DateTime('2025-07-15 23:30:00'));
    $taylorSwift->setType('Concierto');
    $taylorSwift->setVenue($wembley);
    $taylorSwift->setImage('taylor.png');
    $taylorSwift->setArtist('Taylor Swift');
    $entityManager->persist($taylorSwift);
    
  
    $bts = new Esdeveniment();
    $bts->setTitle('Permission to Dance');
    $bts->setDescription('BTS regresa con su espectacular gira mundial');
    $bts->setStartTime(new DateTime('2025-08-22 19:30:00'));
    $bts->setEndTime(new DateTime('2025-08-22 22:45:00'));
    $bts->setType('Concierto');
    $bts->setVenue($metlife);
    $bts->setImage('bts.png');
    $bts->setArtist('BTS');
    $entityManager->persist($bts);
    
    $lanaDelRey = new Esdeveniment();
    $lanaDelRey->setTitle('Blue Banisters Tour');
    $lanaDelRey->setDescription('Una velada íntima y melancólica con Lana Del Rey');
    $lanaDelRey->setStartTime(new DateTime('2025-08-22 21:00:00'));
    $lanaDelRey->setEndTime(new DateTime('2025-08-22 23:15:00'));
    $lanaDelRey->setType('Concierto');
    $lanaDelRey->setVenue($defenseArena);
    $lanaDelRey->setImage('lana.png');
    $lanaDelRey->setArtist('Lana Del Rey');
    $entityManager->persist($lanaDelRey);
    
    $troyeSivan = new Esdeveniment();
    $troyeSivan->setTitle('Sweat tour');
    $troyeSivan->setDescription('Troye Sivan de gira con su mejor amiga');
    $troyeSivan->setStartTime(new DateTime('2025-10-05 20:30:00'));
    $troyeSivan->setEndTime(new DateTime('2025-10-05 22:30:00'));
    $troyeSivan->setType('Concierto');
    $troyeSivan->setVenue($olimpicBarcelona);
    $troyeSivan->setImage('troye.png');
    $troyeSivan->setArtist('Troye Sivan');
    $entityManager->persist($troyeSivan);
    
    $kaliUchis = new Esdeveniment();
    $kaliUchis->setTitle('Red Moon in Venus Tour');
    $kaliUchis->setDescription('Kali Uchis trae su mezcla única de R&B, reggaeton y pop alternativo');
    $kaliUchis->setStartTime(new DateTime('2025-11-18 20:00:00'));
    $kaliUchis->setEndTime(new DateTime('2025-11-18 22:15:00'));
    $kaliUchis->setType('Concierto');
    $kaliUchis->setVenue($bernabeu);
    $kaliUchis->setImage('kali.png');
    $kaliUchis->setArtist('Kali Uchis');
    $entityManager->persist($kaliUchis);
    
    
    //ADMINISTRADORES
    $admin1 = new Administrador();
    $admin1->setName('Gabriela Sandoval');
    $admin1->setEmail('gabriela@gmail.com');
    $admin1->setContrasenya('admin123'); 
    $entityManager->persist($admin1);
    
    $admin2 = new Administrador();
    $admin2->setName('Toni Aguilar');
    $admin2->setEmail('toni@gmail.com');
    $admin2->setContrasenya('2025@Thos'); 
    $entityManager->persist($admin2);
    
    //USUARIOS
    $user1 = new Usuari();
    $user1->setName('Sophia Laforteza');
    $user1->setEmail('sophia@gmail.com');
    $user1->setPhone('612345678');
    $user1->setContrasenya('sophia2025');
    $user1->setCreatedAt(new DateTime('2025-01-15 10:30:00'));
    $entityManager->persist($user1);
    
    $user2 = new Usuari();
    $user2->setName('Lara Rajagopal');
    $user2->setEmail('dlara@gmail.com');
    $user2->setPhone('687654321');
    $user2->setContrasenya('lara123');
    $user2->setCreatedAt(new DateTime('2025-02-03 14:20:00'));
    $entityManager->persist($user2);
    
    $user3 = new Usuari();
    $user3->setName('Manon Bannerman');
    $user3->setEmail('manon@gmail.com');
    $user3->setPhone('645987123');
    $user3->setContrasenya('manon456'); 
    $user3->setCreatedAt(new DateTime('2025-02-20 09:15:00'));
    $entityManager->persist($user3);
    
    $user4 = new Usuari();
    $user4->setName('Daniela Avanzini');
    $user4->setEmail('daniela@gmail.com');
    $user4->setPhone('698741256');
    $user4->setContrasenya('daniela789'); 
    $user4->setCreatedAt(new DateTime('2025-03-10 16:45:00'));
    $entityManager->persist($user4);
    
    $user5 = new Usuari();
    $user5->setName('Megan Skiendiel');
    $user5->setEmail('megan@gmail.com');
    $user5->setPhone('632147896');
    $user5->setContrasenya('megan2025');
    $user5->setCreatedAt(new DateTime('2025-04-05 11:30:00'));
    $entityManager->persist($user5);

    $user5 = new Usuari();
    $user5->setName('Yoonchae Jeong');
    $user5->setEmail('yooncahe@gmail.com');
    $user5->setPhone('632147896');
    $user5->setContrasenya('yoonchae2025');
    $user5->setCreatedAt(new DateTime('2025-04-05 11:30:00'));
    $entityManager->persist($user5);
    
    //SEIENTS
    $seient_a1_1 = new Seient();
    $seient_a1_1->setFila('A1');
    $seient_a1_1->setNumber(1);
    $seient_a1_1->setType('VIP');
    $seient_a1_1->setVenue($olimpicBarcelona);
    $entityManager->persist($seient_a1_1);
    
    $seient_a1_2 = new Seient();
    $seient_a1_2->setFila('A1');
    $seient_a1_2->setNumber(2);
    $seient_a1_2->setType('VIP');
    $seient_a1_2->setVenue($olimpicBarcelona);
    $entityManager->persist($seient_a1_2);
    
    $seient_a3_1 = new Seient();
    $seient_a3_1->setFila('A3');
    $seient_a3_1->setNumber(1);
    $seient_a3_1->setType('General');
    $seient_a3_1->setVenue($olimpicBarcelona);
    $entityManager->persist($seient_a3_1);
    
    $seient_a3_2 = new Seient();
    $seient_a3_2->setFila('A3');
    $seient_a3_2->setNumber(2);
    $seient_a3_2->setType('General');
    $seient_a3_2->setVenue($olimpicBarcelona);
    $entityManager->persist($seient_a3_2);
    
    // Asientos para Santiago Bernabéu
    $seient_b1_1 = new Seient();
    $seient_b1_1->setFila('B1');
    $seient_b1_1->setNumber(1);
    $seient_b1_1->setType('VIP');
    $seient_b1_1->setVenue($bernabeu);
    $entityManager->persist($seient_b1_1);
    
    $seient_b1_2 = new Seient();
    $seient_b1_2->setFila('B1');
    $seient_b1_2->setNumber(2);
    $seient_b1_2->setType('VIP');
    $seient_b1_2->setVenue($bernabeu);
    $entityManager->persist($seient_b1_2);
    
    $seient_b3_1 = new Seient();
    $seient_b3_1->setFila('B3');
    $seient_b3_1->setNumber(1);
    $seient_b3_1->setType('General');
    $seient_b3_1->setVenue($bernabeu);
    $entityManager->persist($seient_b3_1);
    
    // Asientos para Wembley
    $seient_w1_1 = new Seient();
    $seient_w1_1->setFila('W1');
    $seient_w1_1->setNumber(1);
    $seient_w1_1->setType('VIP');
    $seient_w1_1->setVenue($wembley);
    $entityManager->persist($seient_w1_1);
    
    $seient_w1_2 = new Seient();
    $seient_w1_2->setFila('W1');
    $seient_w1_2->setNumber(2);
    $seient_w1_2->setType('VIP');
    $seient_w1_2->setVenue($wembley);
    $entityManager->persist($seient_w1_2);
    
    $seient_w1_3 = new Seient();
    $seient_w1_3->setFila('W1');
    $seient_w1_3->setNumber(3);
    $seient_w1_3->setType('VIP');
    $seient_w1_3->setVenue($wembley);
    $entityManager->persist($seient_w1_3);
    
    // Asientos para MetLife
    $seient_m2_1 = new Seient();
    $seient_m2_1->setFila('M2');
    $seient_m2_1->setNumber(1);
    $seient_m2_1->setType('VIP');
    $seient_m2_1->setVenue($metlife);
    $entityManager->persist($seient_m2_1);
    
    $seient_m3_1 = new Seient();
    $seient_m3_1->setFila('M3');
    $seient_m3_1->setNumber(1);
    $seient_m3_1->setType('General');
    $seient_m3_1->setVenue($metlife);
    $entityManager->persist($seient_m3_1);
    
    $seient_m3_2 = new Seient();
    $seient_m3_2->setFila('M3');
    $seient_m3_2->setNumber(2);
    $seient_m3_2->setType('General');
    $seient_m3_2->setVenue($metlife);
    $entityManager->persist($seient_m3_2);
    
    // Asientos para Defense Arena
    $seient_d1_1 = new Seient();
    $seient_d1_1->setFila('D1');
    $seient_d1_1->setNumber(1);
    $seient_d1_1->setType('VIP');
    $seient_d1_1->setVenue($defenseArena);
    $entityManager->persist($seient_d1_1);
    
    $seient_d1_2 = new Seient();
    $seient_d1_2->setFila('D1');
    $seient_d1_2->setNumber(2);
    $seient_d1_2->setType('VIP');
    $seient_d1_2->setVenue($defenseArena);
    $entityManager->persist($seient_d1_2);
    
    $seient_d1_3 = new Seient();
    $seient_d1_3->setFila('D1');
    $seient_d1_3->setNumber(3);
    $seient_d1_3->setType('VIP');
    $seient_d1_3->setVenue($defenseArena);
    $entityManager->persist($seient_d1_3);
    
    
    //COMPRAS

    // Compra 1 - Sophia compra tickets para Taylor
    $compra1 = new Compra();
    $compra1->setPurchaseDate(new DateTime('2025-05-10 14:30:00'));
    $compra1->setPaymentMethod('Tarjeta de Crédito');
    $compra1->setTotalAmount(250.00);
    $compra1->setUser($user1); 
    $entityManager->persist($compra1);
    
    // Compra 2 - Lara compra tickets para BTS
    $compra2 = new Compra();
    $compra2->setPurchaseDate(new DateTime('2025-05-12 16:45:00'));
    $compra2->setPaymentMethod('PayPal');
    $compra2->setTotalAmount(180.00);
    $compra2->setUser($user2);
    $entityManager->persist($compra2);
    
    // Compra 3 - Manon compra tickets  Lana Del Rey
    $compra3 = new Compra();
    $compra3->setPurchaseDate(new DateTime('2025-05-15 10:20:00'));
    $compra3->setPaymentMethod('Transferencia');
    $compra3->setTotalAmount(320.00);
    $compra3->setUser($user3); 
    $entityManager->persist($compra3);
    
    // Compra 4 - Daniela compra tickets  Troye 
    $compra4 = new Compra();
    $compra4->setPurchaseDate(new DateTime('2025-05-18 19:15:00'));
    $compra4->setPaymentMethod('Tarjeta de Débito');
    $compra4->setTotalAmount(140.00);
    $compra4->setUser($user4);
    $entityManager->persist($compra4);
    
    // Compra 5 - Megan compra tickets  Kali Uchis
    $compra5 = new Compra();
    $compra5->setPurchaseDate(new DateTime('2025-05-20 12:00:00'));
    $compra5->setPaymentMethod('Tarjeta de Crédito');
    $compra5->setTotalAmount(200.00);
    $compra5->setUser($user5); 
    $entityManager->persist($compra5);
    
    // TICKETS
    
    // Tickets  Taylor Swift en Wembley
    $ticket1 = new Ticket();
    $ticket1->setCode('TS-WEMB-001');
    $ticket1->setPrice(125.00);
    $ticket1->setStatus('Vendido');
    $ticket1->setEvent($taylorSwift);
    $ticket1->setSeat($seient_w1_1); 
    $ticket1->setPurchase($compra1);
    $entityManager->persist($ticket1);
    
    $ticket2 = new Ticket();
    $ticket2->setCode('TS-WEMB-002');
    $ticket2->setPrice(125.00);
    $ticket2->setStatus('Vendido');
    $ticket2->setEvent($taylorSwift);
    $ticket2->setSeat($seient_w1_2); 
    $ticket2->setPurchase($compra1);
    $entityManager->persist($ticket2);
    
    // Tickets  BTS en MetLife
    $ticket3 = new Ticket();
    $ticket3->setCode('BTS-METL-001');
    $ticket3->setPrice(90.00);
    $ticket3->setStatus('Vendido');
    $ticket3->setEvent($bts);
    $ticket3->setSeat($seient_m3_1); 
    $ticket3->setPurchase($compra2);
    $entityManager->persist($ticket3);
    
    $ticket4 = new Ticket();
    $ticket4->setCode('BTS-METL-002');
    $ticket4->setPrice(90.00);
    $ticket4->setStatus('Vendido');
    $ticket4->setEvent($bts);
    $ticket4->setSeat($seient_m3_2); 
    $ticket4->setPurchase($compra2);
    $entityManager->persist($ticket4);
    
    // Tickets Lana Del Rey en Defense Arena
    $ticket5 = new Ticket();
    $ticket5->setCode('LDR-DEFE-001');
    $ticket5->setPrice(160.00);
    $ticket5->setStatus('Vendido');
    $ticket5->setEvent($lanaDelRey);
    $ticket5->setSeat($seient_d1_1);
    $ticket5->setPurchase($compra3);
    $entityManager->persist($ticket5);
    
    $ticket6 = new Ticket();
    $ticket6->setCode('LDR-DEFE-002');
    $ticket6->setPrice(160.00);
    $ticket6->setStatus('Vendido');
    $ticket6->setEvent($lanaDelRey);
    $ticket6->setSeat($seient_d1_2); 
    $ticket6->setPurchase($compra3);
    $entityManager->persist($ticket6);
    
    // Tickets Troye Sivan en Barcelona
    $ticket7 = new Ticket();
    $ticket7->setCode('TS-BARC-001');
    $ticket7->setPrice(70.00);
    $ticket7->setStatus('Vendido');
    $ticket7->setEvent($troyeSivan);
    $ticket7->setSeat($seient_a3_1); 
    $ticket7->setPurchase($compra4);
    $entityManager->persist($ticket7);
    
    $ticket8 = new Ticket();
    $ticket8->setCode('TS-BARC-002');
    $ticket8->setPrice(70.00);
    $ticket8->setStatus('Vendido');
    $ticket8->setEvent($troyeSivan);
    $ticket8->setSeat($seient_a3_2); 
    $ticket8->setPurchase($compra4);
    $entityManager->persist($ticket8);
    
    // Tickets Kali Uchis en Bernabéu
    $ticket9 = new Ticket();
    $ticket9->setCode('KU-BERN-001');
    $ticket9->setPrice(100.00);
    $ticket9->setStatus('Vendido');
    $ticket9->setEvent($kaliUchis);
    $ticket9->setSeat($seient_b1_1); 
    $ticket9->setPurchase($compra5);
    $entityManager->persist($ticket9);
    
    $ticket10 = new Ticket();
    $ticket10->setCode('KU-BERN-002');
    $ticket10->setPrice(100.00);
    $ticket10->setStatus('Vendido');
    $ticket10->setEvent($kaliUchis);
    $ticket10->setSeat($seient_b1_2); 
    $ticket10->setPurchase($compra5);
    $entityManager->persist($ticket10);
    
    // Tickets sin comprar
    $ticketDisponible1 = new Ticket();
    $ticketDisponible1->setCode('TS-WEMB-003');
    $ticketDisponible1->setPrice(125.00);
    $ticketDisponible1->setStatus('Disponible');
    $ticketDisponible1->setEvent($taylorSwift);
    $ticketDisponible1->setSeat($seient_w1_3); 
    $entityManager->persist($ticketDisponible1);
    
    $ticketDisponible2 = new Ticket();
    $ticketDisponible2->setCode('BTS-METL-003');
    $ticketDisponible2->setPrice(90.00);
    $ticketDisponible2->setStatus('Disponible');
    $ticketDisponible2->setEvent($bts);
    $ticketDisponible2->setSeat($seient_m2_1); 
    $entityManager->persist($ticketDisponible2);
    
    $ticketDisponible3 = new Ticket();
    $ticketDisponible3->setCode('LDR-DEFE-003');
    $ticketDisponible3->setPrice(160.00);
    $ticketDisponible3->setStatus('Disponible');
    $ticketDisponible3->setEvent($lanaDelRey);
    $ticketDisponible3->setSeat($seient_d1_3); 
    $entityManager->persist($ticketDisponible3);

    $entityManager->flush();

    echo "Se ha incertado correctamente en la base de datos";

} catch (\Exception $e){
    echo $e;
}*/

$pdfGenerador = new PdfGenerador(__DIR__ . '/vendor/mpdf/mpdf/tmp');


$numRef = $_GET['ref'] ?? null;
$data = $_GET['data'] ?? null;

if ($numRef !== null || $data !== null || isset($_GET['blank'])) {

    $pdfGenerador = new PdfGenerador(__DIR__ . '/vendor/mpdf/mpdf/tmp');
    
    if ($numRef !== null) {
        $ticketController = new TicketController($entityManager, $pdfGenerador);
        $ticketController->generarPdfEntrada();
    } elseif ($data !== null) {
        $eventoController = new EsdevenimentController($entityManager);
        $eventoController->generarXmlDelDia();
    } elseif (isset($_GET['blank'])) {
        $ticketController = new TicketController($entityManager, $pdfGenerador);
        $ticketController->generarPdfBlanco();
    }
    exit; 
}



$controller = new FrontController($entityManager);
$controller->handleRequest();

