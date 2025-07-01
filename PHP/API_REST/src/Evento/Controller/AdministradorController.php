<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use Evento\Entity\Administrador;

class AdministradorController {
    private $administradorRepository;

    public function __construct(EntityManager $entityManager) {
        $this->administradorRepository = $entityManager->getRepository(Administrador::class);
    }


    /**
     * Metodo que si hay id regresa el admin con ese id, y si no hay id entonces regresa una lista con todos  los admin
     */
    public function read($id = null) {
        if ($id) {
            $admin = $this->administradorRepository->find($id);
            return $admin ? $this->serialize($admin) : ['error' => 'Administrador no encontrado'];
        }

        return array_map([$this, 'serialize'], $this->administradorRepository->findAll());
    }

    public function create(array $data) {

        //el nombre es obligatorio
        if (!isset($data['name']) || empty($data['name'])) {
            return ['error' => 'El name es obligatorio'];
        }
        
        if (!isset($data['email']) || empty($data['email'])) {
            return ['error' => 'El correo es obligatorio'];
        }
        
        if (!isset($data['contrasenya']) || empty($data['contrasenya'])) {
            return ['error' => 'La contrasenya es obligatoria'];
        }

        // verificar si el correo ya existe
        if ($this->administradorRepository->findByEmail($data['email'])) {
            return ['error' => 'El correo ya está registrado'];
        }

        $admin = new Administrador();
        $admin->setName($data['name']);
        $admin->setEmail($data['email']);
        $admin->setContrasenya($data['contrasenya']);

        $this->administradorRepository->create($admin);

        return [
            'message' => 'Administrador creado exitosamente',
            'id' => $admin->getId(),
            'email' => $admin->getEmail()
        ];
    }

    /**
     * Actualiza los datos de un admin existente
     * @param $id id del admin a actualizar
     * @param  $data Nuevos datos del admin
     * @return array un mensaje de éxito o error
     */
    public function update($id, array $data) {

        //verificar que el admin exista
        $admin = $this->administradorRepository->find($id);
        if (!$admin) {
            return ['error' => 'Administrador no encontrado'];
        }

        if (isset($data['name']) && !empty($data['name'])) {
            $admin->setName($data['name']);
        }

        if (isset($data['email']) && !empty($data['email'])) {
            // Verificar si el nuevo correo ya existe 
            $existingAdmin = $this->administradorRepository->findByEmail($data['email']);
            if ($existingAdmin && $existingAdmin->getId() !== $admin->getId()) {
                return ['error' => 'El correo ya está registrado por otro administrador'];
            }
            $admin->setEmail($data['email']);
        }

        if (isset($data['contrasenya']) && !empty($data['contrasenya'])) {
            $admin->setcontrasenya($data['contrasenya']); 
        }

        $this->administradorRepository->update();

        return ['message' => 'Administrador actualizado exitosamente'];
    }

    /**
     * Elimina un administrador por su id.
     * 
     * @param $id id del admin
     * @return array un mensaje de éxito o error
     */
    public function delete($id) {
        $admin = $this->administradorRepository->find($id);
        if (!$admin) {
            return ['error' => 'Administrador no encontrado'];
        }

        $this->administradorRepository->delete($admin);

        return ['message' => 'Administrador eliminado exitosamente'];
    }

    /**
     * Convierte un objeto Administrador en un array para mostrarlo como JSON 
     * 
     * @param Administrador $admin
     * @return array Representacion del admin
     */
    private function serialize(Administrador $admin) {
        return [
            'id' => $admin->getId(),
            'name' => $admin->getName(),
            'email' => $admin->getEmail(),
        ];
    }
}