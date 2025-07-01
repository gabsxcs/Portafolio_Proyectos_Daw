<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use Evento\Entity\Compra;
use Evento\Entity\Usuari;

class CompraController
{
    private $compraRepository;
    private $usuariRepository;
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->compraRepository = $entityManager->getRepository(Compra::class);
        $this->usuariRepository = $entityManager->getRepository(Usuari::class);
    }

     /**
     * Metodo que si hay id regresa la compra con ese id, y si no hay id entonces regresa una lista con todas las compras
     */
    public function read($id = null) {

        if ($id) {
            $compra = $this->compraRepository->find($id);

            //si no hay compra con ese id entonces regresa un mensaje de error
            return $compra ? $this->serialize($compra) : ['error' => 'Compra no trobada'];
        }

        return array_map([$this, 'serialize'], $this->compraRepository->findAll());
    }


    /**
     * Crea una nueva compra a partir de los datos recibidos
     * @param  $data son los datos que recibe del usuario a crear
     * @return array un mensaje de éxito o error
     */
    public function create(array $data) {


        $compra = new Compra();
        $compra->setPurchaseDate(new \DateTime($data['purchaseDate'] ?? 'now'));
        $compra->setPaymentMethod($data['paymentMethod'] ?? '');
        $compra->setTotalAmount($data['totalAmount'] ?? 0);
        
        // valida que el id del usuario exista
        if (isset($data['userId'])) {
            $user = $this->usuariRepository->find($data['userId']);
            if (!$user) {
                return ['error' => 'Usuari no trobat'];
            }
            $compra->setUser($user);
        }

        $this->compraRepository->create($compra);

        return ['message' => 'Compra creada', 'id' => $compra->getId()];
    }


    /**
     * Actualiza los datos de una compra existente
     * @param $id id de la compra a actualizar
     * @param  $data Nuevos datos de la compra
     * @return array un mensaje de éxito o error
     */
    public function update($id, array $data) {

        //verifica que el id exista
        $compra = $this->compraRepository->find($id);
        if (!$compra) return ['error' => 'Compra no trobada'];


         //solo se actualizan los campos que han sido enviados
        if (isset($data['purchaseDate'])) {
            $compra->setPurchaseDate(new \DateTime($data['purchaseDate']));
        }
        
        if (isset($data['paymentMethod'])) {
            $compra->setPaymentMethod($data['paymentMethod']);
        }
        
        if (isset($data['totalAmount'])) {
            $compra->setTotalAmount($data['totalAmount']);
        }
        
        // Actualizar usuario si se proporciona
        if (isset($data['userId'])) {
            $user = $this->usuariRepository->find($data['userId']);
            if (!$user) {
                return ['error' => 'Usuari no trobat'];
            }
            $compra->setUser($user);
        }

        $this->compraRepository->update();

        return ['message' => 'Compra actualitzada'];
    }

    /**
     * Elimina una compra por su id.
     * 
     * @param $id id de la compra
     * @return array un mensaje de éxito o error
     */
    public function delete($id) {
        $compra = $this->compraRepository->find($id);
        if (!$compra) return ['error' => 'Compra no trobada'];

        $this->compraRepository->delete($compra);

        return ['message' => 'Compra eliminada'];
    }

    /**
     * Obtener compras por usuario
     * 
     * @param int $userId ID del usuario
     * @return array Lista de compras del usuario
     */
    public function getByUser($userId) {
        $compras = $this->compraRepository->findByUser($userId);
        return array_map([$this, 'serialize'], $compras);
    }

    /**
     * Convierte un objeto Compra en un array para mostrarlo como JSON 
     * 
     * @param Compra $usuari Compra a serializar
     * @return array Representación de la compra
     */
    private function serialize(Compra $compra) {
        $data = [
            'id' => $compra->getId(),
            'purchaseDate' => $compra->getPurchaseDate()->format('Y-m-d H:i:s'),
            'paymentMethod' => $compra->getPaymentMethod(),
            'totalAmount' => $compra->getTotalAmount(),
        ];
        
        // Incluir información del usuario que ha hecho la compra
        if ($compra->getUser()) {
            $data['user'] = [
                'id' => $compra->getUser()->getId(),
                'name' => $compra->getUser()->getName(),
                'email' => $compra->getUser()->getEmail()
            ];
        }
        
        return $data;
    }
}