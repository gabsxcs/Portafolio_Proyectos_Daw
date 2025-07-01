<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use Evento\Entity\Localitzacio;

class LocalitzacioController
{
    private $localitzacioRepository;

    public function __construct(EntityManager $entityManager) {
        $this->localitzacioRepository = $entityManager->getRepository(Localitzacio::class);
    }

    /**
     * Metodo que si hay id regresa la localizacion con ese id, y si no hay id entonces regresa una lista con todas las localizacion
     */
    public function read($id = null){
        if ($id) {
            $localitzacio = $this->localitzacioRepository->find($id);
            return $localitzacio ? $this->serialize($localitzacio) : ['error' => 'Localització no trobada'];
        }

        return array_map([$this, 'serialize'], $this->localitzacioRepository->findAll());
    }


     /**
     * Crea una nueva localizacion a partir de los datos recibidos
     * @param  $data son los datos que recibe de la localizacion a crear
     * @return array un mensaje de éxito o error
     */
    public function create(array $data)  {
        $localitzacio = new Localitzacio();
        $localitzacio->setName($data['name'] ?? '');
        $localitzacio->setAddress($data['address'] ?? '');
        $localitzacio->setCity($data['city'] ?? '');
        $localitzacio->setCapacity($data['capacity'] ?? 0);

        $this->localitzacioRepository->create($localitzacio);

        return ['message' => 'Localització creada', 'id' => $localitzacio->getId()];
    }


    /**
     * Actualiza los datos de una localizacion existente
     * @param $id id de la localizacion a actualizar
     * @param  $data Nuevos datos de la localizacion
     * @return array un mensaje de éxito o error
     */
    public function update($id, array $data) {

        //verifica que la localizacion exista
        $localitzacio = $this->localitzacioRepository->find($id);
        if (!$localitzacio) return ['error' => 'Localització no trobada'];

        //solo se actualizan los campos que se envian
        $localitzacio->setName($data['name'] ?? $localitzacio->getName());
        $localitzacio->setAddress($data['address'] ?? $localitzacio->getAddress());
        $localitzacio->setCity($data['city'] ?? $localitzacio->getCity());
        $localitzacio->setCapacity($data['capacity'] ?? $localitzacio->getCapacity());

        $this->localitzacioRepository->update();

        return ['message' => 'Localització actualitzada'];
    }

    /**
     * Elimina una localizacion por su id.
     * 
     * @param $id id de la localizacion
     * @return array un mensaje de éxito o error
     */
    public function delete($id){
        $localitzacio = $this->localitzacioRepository->find($id);
        if (!$localitzacio) return ['error' => 'Localització no trobada'];

        $this->localitzacioRepository->delete($localitzacio);

        return ['message' => 'Localització eliminada'];
    }


    /**
     * Convierte un objeto localizacion en un array para mostrarlo como JSON 
     * 
     * @param Localitzacio $localitzacio localizacion a serializar
     *
     */
    private function serialize(Localitzacio $localitzacio){
        return [
            'id' => $localitzacio->getId(),
            'name' => $localitzacio->getName(),
            'address' => $localitzacio->getAddress(),
            'city' => $localitzacio->getCity(),
            'capacity' => $localitzacio->getCapacity(),
        ];
    }
}