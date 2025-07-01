<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use Evento\Entity\Usuari;

class UsuariController {
    
    private $usuariRepository;

    public function __construct(EntityManager $entityManager) {
        $this->usuariRepository = $entityManager->getRepository(Usuari::class);
    }

    /**
     * Metodo que si hay id regresa el usuario con ese id, y si no hay id entonces regresa una lista con todos los usuarios
     */
    public function read($id = null){
        if ($id) {
            $user = $this->usuariRepository->find($id);

            //si no usuario con ese id entonces regresa un mensaje de error
            return $user ? $this->serialize($user) : ['error' => 'Usuari no trobat'];
        }

        //y si no hay id entonces regresa todos los usuarios existentes
        return array_map([$this, 'serialize'], $this->usuariRepository->findAll());
    }

    /**
     * Crea un nuevo usuario a partir de los datos recibidos
     * @param  $data son los datos que recibe del usuario a crear
     * @return array un mensaje de éxito o error
     */
    public function create(array $data)  {

        $usuari = new Usuari();
        $usuari->setName($data['name'] ?? '');
        $usuari->setEmail($data['email'] ?? '');
        $usuari->setPhone($data['phone'] ?? '');
        
        // se valida que la contraseña este presente en esos datos, porque es obligatoria
        if (!isset($data['contrasenya']) || empty($data['contrasenya'])) {
            return ['error' => 'La contraseña es obligatoria'];
        }
        $usuari->setContrasenya($data['contrasenya']);
        
        $usuari->setCreatedAt(new \DateTime()); // es para la fecha de creacion

        $this->usuariRepository->create($usuari);

        return ['message' => 'Usuari creat', 'id' => $usuari->getId()];
    }

    /**
     * Actualiza los datos de un usuario existente
     * @param $id id del usuario a actualizar
     * @param  $data Nuevos datos del usuaerio
     * @return array un mensaje de éxito o error
     */
    public function update($id, array $data){

        //verifica que el usuario existe
        $usuari = $this->usuariRepository->find($id);
        if (!$usuari) return ['error' => 'Usuari no trobat'];

        //solo se actualizan los campos que se envian
        $usuari->setName($data['name'] ?? $usuari->getName());
        $usuari->setEmail($data['email'] ?? $usuari->getEmail());
        $usuari->setPhone($data['phone'] ?? $usuari->getPhone());
        
        //solo se actualzia si hay una nueva
        if (isset($data['contrasenya']) && !empty($data['contrasenya'])) {
            $usuari->setContrasenya($data['contrasenya']);
        }

        $this->usuariRepository->update();

        return ['message' => 'Usuari actualitzat'];
    }

     /**
     * Elimina un usuario por su id.
     * 
     * @param $id id del usuario
     * @return array un mensaje de éxito o error
     */
    public function delete($id){
        $usuari = $this->usuariRepository->find($id);
        if (!$usuari) return ['error' => 'Usuari no trobat'];

        $this->usuariRepository->delete($usuari);

        return ['message' => 'Usuari eliminat'];
    }

    /**
     * Convierte un objeto Usuari en un array para mostrarlo como JSON 
     * 
     * @param Usuari $usuari Usuario a serializar
     * @return array Representación del usuario
     */
    private function serialize(Usuari $usuari){
        return [
            'id' => $usuari->getId(),
            'name' => $usuari->getName(),
            'email' => $usuari->getEmail(),
            'phone' => $usuari->getPhone(),
            'createdAt' => $usuari->getCreatedAt()?->format('Y-m-d H:i:s'),
            
        ];
    }
}