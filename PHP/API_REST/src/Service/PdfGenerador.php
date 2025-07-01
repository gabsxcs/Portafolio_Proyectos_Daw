<?php
namespace Evento\Service;

use Mpdf\Mpdf;
use Evento\Entity\Ticket;
use Evento\Entity\Esdeveniment;
use Evento\Entity\Localitzacio;
use Evento\Entity\Seient;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PdfGenerador {
    private string $tempDir;
    
    public function __construct(string $tempDir) {
        $this->tempDir = $tempDir;
    }
    
    /**
     * Genera el pdf de un ticket, junto con su qr, barcode y una marca de agua. 
     * Además el documento está protegido con contraseña
     * @param Ticket $ticket
     */
    public function generarTicketPdf(Ticket $ticket) {
        $qrCodePath = $this->generarCodigoQR($ticket);
        $mpdf = new Mpdf([
            'tempDir' => $this->tempDir,
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);
        
        //$mpdf->SetProtection([], '2025@Thos');
        $marcaDeAgua = './img/logo.png';
        $mpdf->SetWatermarkImage($marcaDeAgua, 0.1, 0.01, 45);
        $mpdf->showWatermarkImage = true;
        
        $barcodePath = $this->generarCodigoBarras($ticket);
        $html = $this->generarHtmlForTicket($ticket, $qrCodePath, $barcodePath);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('ticket_' . $ticket->getCode() . '.pdf', 'I');
        
        if (file_exists($qrCodePath)) {
            unlink($qrCodePath);
        }
        if (file_exists($barcodePath)) {
            unlink($barcodePath);
        }
    }
    
    /**
     * Genera un código QR que al leerlo te muestra el mismo ticket (url con el código)
     * @param Ticket $ticket
     * @return string
     */
    private function generarCodigoQR(Ticket $ticket) {
        $qrDir = $this->tempDir . '/qr';
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }
        
        $qrCodePath =  $qrDir . '/qr_' . $ticket->getCode() . '.png';
        
        $qrUrl = 'http://localhost/MicroServeis/index.php?code=' . $ticket->getCode();
        
        require_once 'vendor/phpqrcode/qrlib.php';
        
        \QRcode::png($qrUrl, $qrCodePath, 'L', 4, 2);
        
        return $qrCodePath;
    }
    
    /**
     * Genera un código de barras que al leerlo se ve el código del ticket
     * @param Ticket $ticket
     * @return string
     */
    private function generarCodigoBarras(Ticket $ticket) {
        $barcodeDir = $this->tempDir . '/barcode';
        if (!is_dir($barcodeDir)) {
            mkdir($barcodeDir, 0755, true);
        }
        
        $barcodePath = $barcodeDir . '/barcode_' . $ticket->getCode() . '.png';
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($ticket->getCode(), $generator::TYPE_CODE_128);
        file_put_contents($barcodePath, $barcode);
        
        return $barcodePath;
    }
    
    /**
     * Busca el template(view) para generar el ticket con su QR y código de barras
     * @param Ticket $ticket
     * @param string $qrCodePath
     * @param string $barcodePath
     * @return string
     */
    private function generarHtmlForTicket(Ticket $ticket, string $qrCodePath, string $barcodePath) {
        $esdeveniment = $ticket->getEvent();
        $seient = $ticket->getSeat();
        $localitzacio = $esdeveniment->getVenue();
        
        // Formato de fecha y hora para la vista
        $fecha = strtoupper($esdeveniment->getStartTime()->format('M d Y'));
        $hora = $esdeveniment->getStartTime()->format('h:i A');
        $precio = number_format($ticket->getPrice(), 0);
        
        ob_start();
        
        include __DIR__ . '/../Evento/Vista/TicketView.php';
        $html = ob_get_clean();
        
        return $html;
    }
    
    /**
     * En caso de haber un error, muestra un pdf diciéndote el error que ha habido
     * @param string $mensajeError
     */
    public function generarErrorPdf(string $mensajeError) {
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => $this->tempDir,
            'mode' => 'utf-8',
            'format' => 'A4'
        ]);
        
        ob_start();
        include __DIR__ . '/../Evento/Vista/ErrorView.php';
        $html = ob_get_clean();
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('error.pdf', 'I');
    }

    /**
     * 
     * EN caso de no haber ningun parametro, se muestra un pdf en blanco
     * @return void
     */
    public function generarPdfBlanco() {
    $mpdf = new Mpdf([
        'tempDir' => $this->tempDir,
        'mode' => 'utf-8',
        'format' => 'A4'
    ]);
    
    $mpdf->WriteHTML('');
    $mpdf->Output('documento_blanco.pdf', 'I');
}

}