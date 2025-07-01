<?php
namespace Evento\Service;

use Mpdf\Mpdf;
use Evento\Entity\Entrada;
use Picqer\Barcode\BarcodeGeneratorPNG;
class PdfGenerador{
    private string $tempDir;

    public function __construct(string $tempDir){
        $this->tempDir = $tempDir;
    }

    /**
     * Genera el pdf de una entrada, junto con su qr, barcode y una marca de agua. Ademas de que está protegido el documento con contraseña
     * @param Entrada $entrada
     */
    public function generarEntradaPdf(Entrada $entrada){
        $qrCodePath = $this->generarCodigoQR($entrada);

        $mpdf = new Mpdf([
            'tempDir' => $this->tempDir,
            'mode' => 'utf-8',
            'format' => 'A4' ,
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        $mpdf->SetProtection([], '2025@Thos');
        $marcaDeAgua = './img/logo.png'; 
        $mpdf->SetWatermarkImage($marcaDeAgua, 0.1, 0.01, 45); 

        $mpdf->showWatermarkImage = true;

        $barcodePath = $this->generarCodigoBarras($entrada);
        $html = $this->generarHtmlForEntrada($entrada, $qrCodePath, $barcodePath);


        $mpdf->WriteHTML($html);

        $mpdf->Output('entrada_'.$entrada->getCodigoReferencia().'.pdf', 'I');
        
        if (file_exists($qrCodePath)) {
            unlink($qrCodePath);
        }
        if (file_exists($barcodePath)) {
            unlink($barcodePath);
        }
    }

    /**
     * Genera un codigo qr que al leerlo te muestra la misma entrada (url con el num ref)
     * @param Entrada $entrada
     * @return string
     */
    private function generarCodigoQR(Entrada $entrada){
        $qrDir = $this->tempDir . '/qr';
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }
         
        $qrCodePath = $qrDir . '/qr_' . $entrada->getCodigoReferencia() . '.png';
        
        $qrUrl = 'http://localhost/EntradaConcierto/index.php?numRef=' . $entrada->getCodigoReferencia();
        
        require_once 'vendor/phpqrcode/qrlib.php';
        
        \QRcode::png($qrUrl, $qrCodePath, 'L', 4, 2);
        
        return $qrCodePath;
    }

    /**
     * Genera un codigo de barrar que al leerlo se ve el num ref de la entrada
     * @param Entrada $entrada
     * @return string
     */
    private function generarCodigoBarras(Entrada $entrada){
        $barcodeDir = $this->tempDir . '/barcode';
        if (!is_dir($barcodeDir)) {
            mkdir($barcodeDir, 0755, true);
        }

        $barcodePath = $barcodeDir . '/barcode_' . $entrada->getCodigoReferencia() . '.png';

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($entrada->getCodigoReferencia(), $generator::TYPE_CODE_128);

        file_put_contents($barcodePath, $barcode);

        return $barcodePath;
    }


    /**
     * Busca el template(view) para generar la entrada con su qr y barcode
     * @param Entrada $entrada
     * @param string $qrCodePath
     * @param string $barcodePath
     * @return string
     */
    private function generarHtmlForEntrada(Entrada $entrada, string $qrCodePath, string $barcodePath){
        $evento = $entrada->getEvento();
        $categoria = $entrada->getCategoria();

        $fecha = strtoupper($evento->getFecha()->format('M d Y'));
        $hora = $evento->getHora()->format('h:i A');
        $precio = number_format($categoria->getValor(), 0);

        ob_start(); 
        
        include __DIR__ . '/../Evento/Vista/EntradaView.php';


        $html = ob_get_clean();

        return $html;
    }

    /**
     * En caso de haber un error, muestra un pdf diciendote el error que ha habido
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
}
?>
