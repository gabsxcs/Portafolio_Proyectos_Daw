<?php
namespace Evento\Vista;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .ticket {
            width: 100%;
            max-height: 65mm;
            background-image: url('./img/<?php echo $esdeveniment->getImage()?>');
            background-size: cover;
            color: white;
            position: relative;
        }

        .contenidoTicket {
            width: 60%;
            float: left;
            padding: 3mm;
            box-sizing: border-box;
            max-height: 70mm;
        }

        .ticketInfo {
            width: 25%;
            float: right;
            padding: 3mm;
            box-sizing: border-box;
            background-color: #f8f0e8;
            color: #333;
            border-left: 0.5mm dashed #888;
            height: 70mm;
            text-align: center;
        }

        .eventoLabel {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.8);
        }

        .nombreTour {
            font-size: 20pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 1mm 0;
            letter-spacing: 0.5mm;
        }

        .nombreArtista {
            font-size: 14pt;
            margin-bottom: 5mm;
        }

        .fecha {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 3mm;
            margin-top: 3mm;
        }

        .detalles {
            margin-top: 36mm;
            border-collapse: separate;
        }

        .detalles td {
            font-size: 8pt;
            font-weight: 500;
            color: #fff;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            padding: 3mm;
            border: 0.3mm solid rgba(255,255,255,0.3);
            box-shadow: 0 0 1mm rgba(0, 0, 0, 0.2);
            border-radius: 10mm;
            text-transform: uppercase;
            letter-spacing: 0.2mm;
            white-space: nowrap;
            vertical-align: middle;
        }

        .qr-code {
            width: 20mm;
            height: 20mm;
            background-color: white;
            margin: 0 auto 5mm auto;
            text-align: center;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }

        .infoLabel {
            font-size: 8pt;
            color: rgba(0, 0, 0, 0.7);
            margin-top: 3mm;
        }

        .infoValor {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .barcode {
            height: 10mm;
            margin-top: 3mm;
            text-align: center;
        }

        .barcode img {
            height: 100%;
        }

        .infoAsientos {
            margin-top: 5mm;
        }

        .tablaInfoEntrada {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .tablaInfoEntrada td {
            text-align: center;
            padding: 2mm;
            border: none;
        }

        .infoLabel {
            font-size: 8pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 1mm;
        }

        .infoValor {
            font-size: 12pt;
            font-weight: normal;
            color: #333;
        }
        .numeroBarcode {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 3mm; 
            text-align: center; 
            color: #333; 
            letter-spacing: 2mm; 
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="contenidoTicket">
            <div class="eventoLabel"><?php echo htmlspecialchars($esdeveniment->getType()); ?></div>
            <h1 class="nombreTour"><?php echo htmlspecialchars($esdeveniment->getTitle()); ?></h1>
            <div class="nombreArtista"><?php echo htmlspecialchars($esdeveniment->getArtist()); ?></div>

            <div class="fechaEvento">
                <div class="fecha"><?php echo htmlspecialchars($fecha); ?></div>
                <table class="detalles" cellspacing="2mm" cellpadding="0">
                    <tr>
                        <td class="ubicacion">
                            <?php echo htmlspecialchars($localitzacio->getName()); ?>
                            <span style="font-size: 6pt; display: block; margin-top: 1mm;">
                                <?php echo htmlspecialchars($localitzacio->getCity()); ?>
                            </span>
                        </td>
                        <td class="hora"><?php echo htmlspecialchars($hora); ?></td>
                        <td class="precio">€<?php echo htmlspecialchars($precio); ?></td>
                        <td class="logo" style="border: none;">
                            <img style="width: 22mm;" src="./img/logo.png" alt="Logo">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="ticketInfo">
            <div class="qr-code">
                <img src="<?php echo htmlspecialchars($qrCodePath); ?>" alt="QR Code">
            </div>

            <div class="infoAsientos">
                <table class="tablaInfoEntrada" cellspacing="2mm" cellpadding="0">
                    <tr>
                        <td>
                            <div class="infoLabel">Asiento</div>
                            <div class="infoValor"><?php echo htmlspecialchars($seient->getNumber()); ?></div>
                        </td>
                        <td>
                            <div class="infoLabel">Fila</div>
                            <div class="infoValor"><?php echo htmlspecialchars($seient->getFila()); ?></div>
                        </td>
                        <td>
                            <div class="infoLabel">Tipo</div>
                            <div class="infoValor"><?php echo htmlspecialchars($seient->getType()); ?></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="barcode">
                <img src="<?php echo htmlspecialchars($barcodePath); ?>" alt="Código de barras">
                <div class="numeroBarcode">
                    <?php echo htmlspecialchars($ticket->getCode()); ?>
                </div>
            </div>

        </div>
    </div>
</body>
</html>