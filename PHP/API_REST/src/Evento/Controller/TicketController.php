<?php

namespace Evento\Controller;
use Evento\Service\PdfGenerador;
use Doctrine\ORM\EntityManager;  
use Evento\Entity\Compra;
use Evento\Entity\Esdeveniment;
use Evento\Entity\Seient;
use Evento\Entity\Ticket;

class TicketController {
    private $entradaRepository;
    private $entityManager;
    private $pdfGenerador;

    public function __construct(EntityManager $entityManager, PdfGenerador $pdfGenerador=null) {
        $this->entityManager = $entityManager;
        $this->entradaRepository = $entityManager->getRepository(Ticket::class); 
        $this->pdfGenerador = $pdfGenerador;
    }

    /**
     * Llama los metodos para generar un pdf con la entrada, o si el num ref no existe, muestra un pdf motrando el error
     */
    public function generarPdfEntrada() {
        $ref = $_GET['ref'] ?? null;

        if (!$ref) {
            http_response_code(400);
            return; 
        }

        $entrada = $this->entradaRepository->findByCode($ref);

        if ($entrada) {
            $this->pdfGenerador->generarTicketPdf($entrada);
        } else {
            $this->pdfGenerador->generarErrorPdf('La entrada con referencia "' . htmlspecialchars($ref) . '" no fue encontrada.');
        }
    }

    /**
     * Genera un pdf en blanco
     */
    public function generarPdfBlanco() {
        return $this->pdfGenerador->generarPdfBlanco();
    }
    
     /**
     *
     * Metodo que si hay id regresa el ticket con ese id, y si no hay id entonces regresa una lista con todos los tickets
     *
     * @param $id id del ticket a leer 
     * @return array El ticket o lista de tickets
     */
    public function read(?int $id){
        if ($id !== null) {
            $ticket = $this->entradaRepository->find($id);

            //si no hay ningun ticket con ese id, entonces regresa el mensaje de error
            if (!$ticket) {
                return ['error' => "Ticket con ID $id no encontrado"];
            }

            return $this->serializeTicket($ticket);
        }

        // Si no hay ticket, se regresan todos los tickets existentes
        $tickets = $this->entradaRepository->findAll();
        $result = [];

        foreach ($tickets as $ticket) {
            $result[] = $this->serializeTicket($ticket);
        }

        return $result;
    }

    /**
     * Metodo que regresa tickets por su código de referencia
     * 
     * @param  $code Código de referencia del ticket
     * @return array El ticket encontrado
     */
    public function readByRef(string $code){
        $ticket = $this->entradaRepository->findByCode($code);

        if (!$ticket) {
            return ['error' => "Ticket con código $code no encontrado"];
        }

        return $this->serializeTicket($ticket);
    }

    /**
     * Busca tickets por su estado
     * 
     * @param string $status Estado de los tickets a buscar
     * @return array Lista de tickets con el estado especificado
     */
    public function readByStatus(string $status){
        $tickets = $this->entradaRepository->findByStatus($status);
        $result = [];

        foreach ($tickets as $ticket) {
            $result[] = $this->serializeTicket($ticket);
        }

        return $result;
    }

    /**
     * Crea un nuevo ticket
     * 
     * @param $data los datos del ticket a crear
     * @return array el ticket creado
     */
    public function create(array $data){
        $ticket = new Ticket();
        
        // el codigo es obligatorio para crear el ticket
        if (!isset($data['code']) || empty($data['code'])) {
            return ['error' => 'El código del ticket es obligatorio'];
        }

        $ticket->setCode($data['code']);
        $ticket->setPrice(isset($data['price']) ? (float)$data['price'] : 0.0);
        $ticket->setStatus($data['status'] ?? 'active');
        
        // Establecer evento si viene establecido en los datos, y si no existe ese evento regresa con un mensaje error
        if (isset($data['event_id'])) {
            $event = $this->entityManager->getRepository(Esdeveniment::class)->find($data['event_id']);
            if ($event) {
                $ticket->setEvent($event);
            } else {
                return ['error' => "Evento con ID {$data['event_id']} no encontrado"];
            }
        }
        
        // Establecer asiento si viene establecido en los datos, y si no exoste este asiento entonces regresa un mensaje de error
        if (isset($data['seat_id'])) {
            $seat = $this->entityManager->getRepository(Seient::class)->find($data['seat_id']);
            if ($seat) {
                $ticket->setSeat($seat);
            } else {
                return ['error' => "Asiento con ID {$data['seat_id']} no encontrado"];
            }
        }
        
        // Establecer compra si viene establecida en los datos, y si no existe regresa un mensaje de error
        if (isset($data['purchase_id'])) {
            $purchase = $this->entityManager->getRepository(Compra::class)->find($data['purchase_id']);
            if ($purchase) {
                $ticket->setPurchase($purchase);
            } else {
                return ['error' => "Compra con ID {$data['purchase_id']} no encontrada"];
            }
        }

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return ['message' => 'Ticket creado', 'id' => $ticket->getId()];
    }
    
    /**
     * Actualiza un ticket existente
     * 
     * @param  $id id del ticket a actualizar
     * @param  $data los nuevos datos del ticket
     * @return array el ticket actualizado
     */
    public function update(int $id, array $data){

        $ticket = $this->entradaRepository->find($id);

        if (!$ticket) {
            return ['error' => "Ticket con ID $id no encontrado"];
        }


        //solo se actualizan los datos que han sido proporcionados
        
        if (isset($data['code']) && !empty($data['code'])) {
            $ticket->setCode($data['code']);
        }
        
        if (isset($data['price'])) {
            $ticket->setPrice((float)$data['price']);
        }
        
        if (isset($data['status'])) {
            $ticket->setStatus($data['status']);
        }
        
        if (isset($data['event_id'])) {
            $event = $this->entityManager->getRepository(Esdeveniment::class)->find($data['event_id']);
            if ($event) {
                $ticket->setEvent($event);
            } else {
                return ['error' => "Evento con ID {$data['event_id']} no encontrado"];
            }
        }
        
        if (isset($data['seat_id'])) {
            $seat = $this->entityManager->getRepository(Seient::class)->find($data['seat_id']);
            if ($seat) {
                $ticket->setSeat($seat);
            } else {
                return ['error' => "Asiento con ID {$data['seat_id']} no encontrado"];
            }
        }
        
        if (isset($data['purchase_id'])) {
            $purchase = $this->entityManager->getRepository(Compra::class)->find($data['purchase_id']);
            if ($purchase) {
                $ticket->setPurchase($purchase);
            } else {
                return ['error' => "Compra con ID {$data['purchase_id']} no encontrada"];
            }
        }

        $this->entityManager->flush();

        return ['message' => 'Ticket actualizado'];
    }

    /**
     * Elimina un ticket
     * 
     * @param int $id id del ticket a eliminar
     * @return array Mensaje de confirmación
     */
    public function delete(int $id){
        $ticket = $this->entradaRepository->find($id);

        if (!$ticket) {
            return ['error' => "Ticket con ID $id no encontrado"];
        }

        $this->entityManager->remove($ticket);
        $this->entityManager->flush();

        return ['message' => "Ticket con ID $id eliminado correctamente"];
    }

    /**
     * Convierte un objeto Ticket en un array para mostrarlo como JSON 
     * 
     * @param Ticket $ticket El ticket a serializar
     * @return array El ticket serializado
     */
    private function serializeTicket(Ticket $ticket){
        $event = $ticket->getEvent();
        $seat = $ticket->getSeat();
        $purchase = $ticket->getPurchase();
        
        return [
            'id' => $ticket->getId(),
            'code' => $ticket->getCode(),
            'price' => $ticket->getPrice(),
            'status' => $ticket->getStatus(),
            'event' => $event ? [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'startTime' => $event->getStartTime()->format('Y-m-d H:i:s')
            ] : null,
            'seat' => $seat ? [
                'id' => $seat->getId(),
            ] : null,
            'purchase' => $purchase ? [
                'id' => $purchase->getId(),
            ] : null
        ];
    }
}