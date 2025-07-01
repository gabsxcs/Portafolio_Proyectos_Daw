<?php
namespace Evento\Vista;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            padding: 40px;
        }

        .error-container {
            border: 1px solid #f5c6cb;
            background-color: #f1b0b7;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }

        h1 {
            font-size: 24pt;
            margin-bottom: 10px;
        }

        p {
            font-size: 14pt;
        }

        .footer {
            margin-top: 20px;
            font-size: 10pt;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Error 400</h1>
        <p><?php echo htmlspecialchars($mensajeError); ?></p>
        <div class="footer">Por favor, contacta con atención al cliente de Ticketella si el problema continua.</div>
    </div>
</body>
</html>
