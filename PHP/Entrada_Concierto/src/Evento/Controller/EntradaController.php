<?php

namespace Evento\Controller;
use Evento\Service\PdfGenerador;
use Doctrine\ORM\EntityManager;  

class EntradaController {
    private $entradaRepository;
    private $pdfGenerador;

    public function __construct(EntityManager $entityManager, PdfGenerador $pdfGenerador) {
        $this->entradaRepository = $entityManager->getRepository(\Evento\Entity\Entrada::class); 
        $this->pdfGenerador = $pdfGenerador;
    }

    /**
     * Llama los metodos para generar un pdf con la entrada, o si el num ref no existe, muestra un pdf motrando el error
     */
    public function generarPdfEntrada() {
        $numRef = $_GET['numRef'] ?? null;

        if (!$numRef) {
            http_response_code(400);
            return; 
        }

        $entrada = $this->entradaRepository->findEntradaConDatosRelacionados($numRef);

        if ($entrada) {
            $this->pdfGenerador->generarEntradaPdf($entrada);
        } else {
            $this->pdfGenerador->generarErrorPdf('La entrada con referencia "' . htmlspecialchars($numRef) . '" no fue encontrada.');
        }
    }
    
}

