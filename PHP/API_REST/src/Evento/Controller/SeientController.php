<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use Evento\Entity\Seient;
use Evento\Entity\Localitzacio;

class SeientController
{
    private $seientRepository;
    private $localitzacioRepository;
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->seientRepository = $entityManager->getRepository(Seient::class);
        $this->localitzacioRepository = $entityManager->getRepository(Localitzacio::class);
    }


    /**
     * Metodo que si hay id regresa el asiento con ese id, y si no hay id entonces regresa una lista con todos los asientos
     *
     */
    public function read($id = null) {
        if ($id) {
            $seient = $this->seientRepository->find($id);
            return $seient ? $this->serialize($seient) : ['error' => 'Seient no trobat'];
        }

        return array_map([$this, 'serialize'], $this->seientRepository->findAll());
    }


     /**
     * Crea un nuevo asiento con los datos proporcionados.
     * 
     * @param  $data los datos del nuevo asiento
     * @return array un mensaje de éxito o error
     */
    public function create(array $data) {
        
        // verificar que haya una localizacion
        if (!isset($data['venueId'])) {
            return ['error' => 'Es requereix ID de la localització'];
        }
        
        //se busca la localizacion por el id, y si no hay entonces se regresa mensaje de error
        $venue = $this->localitzacioRepository->find($data['venueId']);
        if (!$venue) {
            return ['error' => 'Localització no trobada'];
        }
        
        //se crea el nuevo asiento
        $seient = new Seient();
        $seient->setFila($data['fila'] ?? '');
        $seient->setNumber($data['number'] ?? 0);
        $seient->setType($data['type'] ?? 'standard');
        $seient->setVenue($venue);

        $this->seientRepository->create($seient);

        return ['message' => 'Seient creat', 'id' => $seient->getId()];
    }


    /**
     * Actualiza los datos de un asiento existente.
     * 
     * @param $id  id del asiento a actualizar
     * @param $data los nuevos datos del asiento
     * @return array un mensaje de éxito o error
     */
    public function update($id, array $data) {

        //verifica que el asiento exista, y sino, mensade error
        $seient = $this->seientRepository->find($id);
        if (!$seient) return ['error' => 'Seient no trobat'];


        //se actualizan solo los campos que fueron enviados
        if (isset($data['fila'])) {
            $seient->setFila($data['fila']);
        }
        
        if (isset($data['number'])) {
            $seient->setNumber($data['number']);
        }
        
        if (isset($data['type'])) {
            $seient->setType($data['type']);
        }
        
        // si se envia una nueva actualizacion, se verifica que existan antes de actualziarla
        if (isset($data['venueId'])) {
            $venue = $this->localitzacioRepository->find($data['venueId']);
            if (!$venue) {
                return ['error' => 'Localització no trobada'];
            }
            $seient->setVenue($venue);
        }

        $this->seientRepository->update();

        return ['message' => 'Seient actualitzat'];
    }

    /**
     * Elimina un asiento por su ID.
     * 
     * @param $id id del asiento
     * @return array un mensaje de éxito o error
     */
    public function delete($id) {
        $seient = $this->seientRepository->find($id);
        if (!$seient) return ['error' => 'Seient no trobat'];

        $this->seientRepository->delete($seient);

        return ['message' => 'Seient eliminat'];
    }
    
    /**
     * Obtiene todos los asientos de una localitzacion
     * 
     * @param  $venueId id de la localización
     * @return array lista de asientos de la localización
     */
    public function getByVenue($venueId) {
        $seients = $this->seientRepository->findByVenue($venueId);
        return array_map([$this, 'serialize'], $seients);
    }
    
    /**
     * Obtiene los asientos por tipo
     * 
     * @param $type Tipo de asiento
     * @return array una lista de asientos del tipo especificado
     */
    public function getByTipo($type) {
        $seients = $this->seientRepository->findByType($type);
        return array_map([$this, 'serialize'], $seients);
    }
    
    /**
     * Obtiene los asientos por fila
     * 
     * @param $fila fila de asientos
     * @return array una lista de asientos en la fila especificada
     */
    public function getByFila($fila) {
        $seients = $this->seientRepository->findByFila($fila);
        return array_map([$this, 'serialize'], $seients);
    }

     

    /**
     * Convierte un objeto Seient en un array para mostrarlo como JSON 
     * @param Seient $seient
     */
    private function serialize(Seient $seient) {
        $data = [
            'id' => $seient->getId(),
            'fila' => $seient->getFila(),
            'number' => $seient->getNumber(),
            'type' => $seient->getType(),
        ];
        
        // se incluye info de la localizacion
        if ($seient->getVenue()) {
            $data['venue'] = [
                'id' => $seient->getVenue()->getId(),
                'name' => $seient->getVenue()->getName(),
                'city' => $seient->getVenue()->getCity()
            ];
        }
        
        return $data;
    }
}