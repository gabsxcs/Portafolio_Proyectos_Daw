<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use \Evento\Entity\Esdeveniment;
use \Evento\Entity\Localitzacio;

class EsdevenimentController {
    private $esdevenimentRepository;
    private $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->esdevenimentRepository = $entityManager->getRepository(Esdeveniment::class);
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

        $eventos = $this->esdevenimentRepository->findByDay($fecha);

        header('Content-Type: application/xml; charset=utf-8');
        $xml = new \SimpleXMLElement('<eventos/>');

        if (empty($eventos)) {
            $xml->addChild('mensaje', 'No se han encontrado eventos para esta fecha. Pruebe con otra fecha.');
        } else {
            foreach ($eventos as $evento) {
                $eventoXml = $xml->addChild('evento');
                $eventoXml->addChild('id', $evento->getId());
                $eventoXml->addChild('titulo', htmlspecialchars($evento->getTitle()));
                $eventoXml->addChild('descripcion', htmlspecialchars($evento->getDescription()));
                $eventoXml->addChild('tipo', htmlspecialchars($evento->getType()));
                $eventoXml->addChild('fechaInicio', $evento->getStartTime()->format('Y-m-d'));
                $eventoXml->addChild('horaInicio', $evento->getStartTime()->format('H:i'));
                $eventoXml->addChild('artista', htmlspecialchars($evento->getArtist() ?? ''));

                $ubicacion = $evento->getVenue();
                if ($ubicacion) {
                    $ubicacionXml = $eventoXml->addChild('ubicacion');
                    $ubicacionXml->addChild('nombre', htmlspecialchars($ubicacion->getName()));
                    $ubicacionXml->addChild('direccion', htmlspecialchars($ubicacion->getAddress()));
                }
            }
        }

        echo $xml->asXML();
    }

    /**
     * Crea un nuevo evento a partir de los datos recibidos
     * @param  $data son los datos que recibe del evento a crear
     * @return array un mensaje de éxito o error
     */
    public function create(array $data): array
    {
        $esdeveniment = new Esdeveniment();
        
        //el titulo es obligatorio
        if (!isset($data['title']) || empty($data['title'])) {
            return ['error' => 'El título del evento es obligatorio'];
        }
        
        $esdeveniment->setTitle($data['title']);
        
        $esdeveniment->setDescription($data['description'] ?? '');
        
        // fecha de inicio obligatoria
        if (!isset($data['startTime']) || empty($data['startTime'])) {
            return ['error' => 'La fecha de inicio es obligatoria'];
        }
        try {
            $startTime = new \DateTime($data['startTime']);
            $esdeveniment->setStartTime($startTime);
        } catch (\Exception $e) {
            return ['error' => 'Formato de fecha de inicio no válido'];
        }
        
        if (isset($data['endTime']) && !empty($data['endTime'])) {
            try {
                $endTime = new \DateTime($data['endTime']);
                $esdeveniment->setEndTime($endTime);
            } catch (\Exception $e) {
                return ['error' => 'Formato de fecha de fin no válido'];
            }
        }

        $esdeveniment->setType($data['type'] ?? '');
        
        if (isset($data['venue_id'])) {
            $venue = $this->entityManager->getRepository(Localitzacio::class)->find($data['venue_id']);
            if ($venue) {
                $esdeveniment->setVenue($venue);
            } else {
                return ['error' => "Localización con ID {$data['venue_id']} no encontrada"];
            }
        }

        if (isset($data['image'])) {
            $esdeveniment->setImage($data['image']);
        }
        
        if (isset($data['artist'])) {
            $esdeveniment->setArtist($data['artist']);
        }

        $this->entityManager->persist($esdeveniment);
        $this->entityManager->flush();

        return ['message' => 'Evento creado', 'id' => $esdeveniment->getId()];
    }

    /**
     * Metodo que si hay id regresa el evento con ese id, y si no hay id entonces regresa una lista con todos los eventos
     */
    public function read(?int $id){
        if ($id !== null) {
            $esdeveniment = $this->esdevenimentRepository->find($id);

            if (!$esdeveniment) {
                return ['error' => "Evento con ID $id no encontrado"];
            }

            return $this->serializeEsdeveniment($esdeveniment);
        }

        // Si hay un id entonces se devuelven todos los eventos
        $esdeveniments = $this->esdevenimentRepository->findAll();
        $result = [];

        foreach ($esdeveniments as $esdeveniment) {
            $result[] = $this->serializeEsdeveniment($esdeveniment);
        }

        return $result;
    }

    /**
     * Actualiza un evento existente
     * 
     * @param int $id ID del evento a actualizar
     * @param array $data Los nuevos datos del evento
     * @return array El evento actualizado
     */
    public function update(int $id, array $data): array
    {
        $esdeveniment = $this->esdevenimentRepository->find($id);

        //verifica que el evento exista
        if (!$esdeveniment) {
            return ['error' => "Evento con ID $id no encontrado"];
        }

       //solo se actualizan los campos que se envian
        if (isset($data['title']) && !empty($data['title'])) {
            $esdeveniment->setTitle($data['title']);
        }
        
        if (isset($data['description'])) {
            $esdeveniment->setDescription($data['description']);
        }
        
        if (isset($data['startTime']) && !empty($data['startTime'])) {
            try {
                $startTime = new \DateTime($data['startTime']);
                $esdeveniment->setStartTime($startTime);
            } catch (\Exception $e) {
                return ['error' => 'Formato de fecha de inicio no válido'];
            }
        }
        
        if (isset($data['endTime']) && !empty($data['endTime'])) {
            try {
                $endTime = new \DateTime($data['endTime']);
                $esdeveniment->setEndTime($endTime);
            } catch (\Exception $e) {
                return ['error' => 'Formato de fecha de fin no válido'];
            }
        }
        
        if (isset($data['type'])) {
            $esdeveniment->setType($data['type']);
        }
        
        if (isset($data['venue_id'])) {
            $venue = $this->entityManager->getRepository(Localitzacio::class)->find($data['venue_id']);
            if ($venue) {
                $esdeveniment->setVenue($venue);
            } else {
                return ['error' => "Localización con ID {$data['venue_id']} no encontrada"];
            }
        }
        
        if (isset($data['image'])) {
            $esdeveniment->setImage($data['image']);
        }
        
        if (isset($data['artist'])) {
            $esdeveniment->setArtist($data['artist']);
        }

        $this->entityManager->flush();

        return ['message' => 'Evento actualizado'];
    }

    /**
     * Elimina un evento
     * 
     * @param int $id id del evento a eliminar
     * @return array un mensaje de éxito o error
     */
    public function delete(int $id){
        $esdeveniment = $this->esdevenimentRepository->find($id);

        if (!$esdeveniment) {
            return ['error' => "Evento con ID $id no encontrado"];
        }

        $this->entityManager->remove($esdeveniment);
        $this->entityManager->flush();

        return ['message' => "Evento con id: $id eliminado correctamente"];
    }

    /**
     * Convierte un objeto Usuari en un array para mostrarlo como JSON 
     * 
     * @param Esdeveniment $esdeveniment El evento a serializar
     * @return array Representación del usuario
     */
    private function serializeEsdeveniment(Esdeveniment $esdeveniment): array
    {
        $venue = $esdeveniment->getVenue();
        
        return [
            'id' => $esdeveniment->getId(),
            'title' => $esdeveniment->getTitle(),
            'description' => $esdeveniment->getDescription(),
            'startTime' => $esdeveniment->getStartTime()->format('Y-m-d H:i:s'),
            'endTime' => $esdeveniment->getEndTime() ? $esdeveniment->getEndTime()->format('Y-m-d H:i:s') : null,
            'type' => $esdeveniment->getType(),
            'venue' => $venue ? [
                'id' => $venue->getId(),
                'name' => $venue->getName(),
                'address' => $venue->getAddress()
            ] : null,
            'image' => $esdeveniment->getImage(),
            'artist' => $esdeveniment->getArtist()
        ];
    }
}