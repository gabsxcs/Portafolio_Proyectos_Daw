<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;

class EventoController {
    private $eventoRepository;
    
    public function __construct(EntityManager $entityManager) {
        $this->eventoRepository = $entityManager->getRepository(\Evento\Entity\Evento::class);
    }
    
    /**
     * Genera un xml con los eeventos que concuerden con el dia, y si no hay ningun evento en ese dia te muestra un mensaje avisandote
     */
    public function generarXmlDelDia() {
        if (ob_get_length()) {
            ob_end_clean();
        }
        $fechaParametro = $_GET['data'] ?? null;
        
        if ($fechaParametro) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $fechaParametro);
            if (!$fecha || $fecha->format('Y-m-d') !== $fechaParametro) {
                http_response_code(400);
                echo "Fecha no válida.";
                return;
            }
        } else {
            $fecha = new \DateTime();
        }
        
        $eventos = $this->eventoRepository->findByFecha($fecha);
        
        header('Content-Type: application/xml; charset=utf-8');
        
        $xml = new \SimpleXMLElement('<eventos/>');
        
        if (empty($eventos)) {
            $xml->addChild('mensaje', 'No se han encontrado eventos para esta fecha. Pruebe con otra fecha.');
        } else {
            foreach ($eventos as $evento) {
                $eventoXml = $xml->addChild('evento');
                $eventoXml->addChild('nombre', htmlspecialchars($evento->getNombre()));
                $eventoXml->addChild('fecha', $evento->getFecha()->format('Y-m-d'));
                $eventoXml->addChild('hora', $evento->getHora()->format('H:i'));
                $eventoXml->addChild('lugar', htmlspecialchars($evento->getLugar()));
                $eventoXml->addChild('direccion', htmlspecialchars($evento->getDireccion()));
                $eventoXml->addChild('artista', htmlspecialchars($evento->getArtista() ?? ''));
                $eventoXml->addChild('descripcion', htmlspecialchars($evento->getDescripcion() ?? ''));
            }
        }
        
        echo $xml->asXML();
    }
}
?>
