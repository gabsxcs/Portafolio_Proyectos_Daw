# Proyecto de Generador de Entradas de Conciertos en PDF

Este proyecto permite la **generación  de entradas de conciertos en formato PDF** usando **mPDF**, e incluye:

- Código QR único por entrada.  
- Código de barras con el número de referencia.  
- Diseño visual para cada entrada.  

---

## 🛠 Tecnologías Utilizadas

- **PHP**
- **[Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)** – Mapeo objeto-relacional para manejo de base de datos.
- **[mPDF](https://mpdf.github.io/)** – Generación de PDFs.
- **[PHP QR Code](https://github.com/kazuhikoarase/qrcode-generator)** – Generación de códigos QR.
- **[Picqer/php-barcode-generator](https://github.com/picqer/php-barcode-generator)** – Generación de códigos de barras.

---

## 🧪 Ejemplos de Generación desde el Navegador

🔹 Generar **PDF** de una entrada por número de referencia:

``` 
/index.php?numRef=100005
```

🔹 Generar **XML** con entradas por fecha:

```
/index.php?data=2025-06-15
```

---



## ⚠️ Problemas con la Generación de QR o Código de Barras

Si al generar una entrada en PDF el **código QR** y/o el **código de barras** no se muestran correctamente y aparece una ❌ (X) en su lugar, probablemente se deba a un **problema de permisos en los directorios temporales** que utiliza `mPDF`.

### 🔧 Solución sugerida

Otorga permisos de lectura, escritura y ejecución a las carpetas necesarias. Puedes hacerlo ejecutando los siguientes comandos:

```bash
sudo chmod -R 757 tmp/
sudo chmod -R 757 tmp/mpdf
chmod 757 tmp/mpdf/qr/
chmod 757 tmp/mpdf/barcode/
chmod 757 tmp/mpdf/mpdf/


---
Desarrollado por Gabriela Sandoval – M7 DAW 2024-25