# Proyecto MVC de Gestión de Frases, Autores y Temas + Landing Page

Este proyecto es una aplicación web desarrollada siguiendo el patrón **Modelo-Vista-Controlador (MVC)**. Está centrado en la gestión de frases célebres, sus autores y temas relacionados, e incluye además una landing page interactiva con varias funcionalidades avanzadas como eventos, contacto, análisis de inversiones y más.

## Estructura del Proyecto

La aplicación se ha organizado alrededor de **tres objetos de negocio principales**: `Author`, `Theme` y `Phrase`, cada uno con su propio modelo, vista y controlador. También incluye un sistema para procesar y cargar frases desde un archivo XML.

### Lógica del MVC

- **Modelos (`model`)**: Gestionan la lógica de acceso a datos y operaciones CRUD sobre cada entidad. También incluyen métodos personalizados según las necesidades del proyecto.
- **Controladores (`controller`)**: Controlan la lógica de la aplicación y la navegación entre vistas. Procesan formularios, validan datos y gestionan errores.
- **Vistas (`view`)**: Contienen las interfaces de usuario. Cada entidad tiene vistas para mostrar, editar y crear elementos, con formularios y validaciones.

### Estructura XML

El proyecto permite **procesar un archivo XML (`frases.xml`)** para crear y poblar automáticamente las tablas necesarias en una base de datos específica. Esto incluye:

- Creación/eliminación de la base de datos.
- Procesamiento y guardado de frases, autores y temas.
- Redirección a la página de frases al finalizar el proceso.

## Funcionalidades de la Landing Page

- **Formulario de contacto**: Guarda los mensajes en un archivo XML.
- **Registro e inicio de sesión**:
  - Con confirmación por correo (activación de cuenta).
- **Calendario interactivo**:
  - Muestra todos los eventos disponibles por día.
- **Gestión de eventos**:
  - Crear, modificar, eliminar y buscar eventos desde una barra de búsqueda.
- **Análisis de inversiones**:
  - Se conecta con [Inversis](https://www.inversis.com/inversiones/productos/cotizaciones-nacionales&pathMenu=2_3_0&esLH=N) para mostrar comparativas de variaciones actuales.

## Gestión de la Base de Datos

El proyecto incluye una clase `PDODatabase` y otra clase `Database` para gestionar múltiples conexiones a bases de datos. Ambas clases implementan el patrón **Singleton**, y permiten trabajar con:

- `PDO` (por defecto en `PDODatabase` y opcional en `Database`)
- `MySQLi` (opcional)
- `SQL Server` (soporte preparado)

Características destacadas:

- Detección y control automático del sistema gestor (`mysql`, `pdo`)
- Métodos reutilizables para consultas (`query`, `execute`, `lastInsertId`)
- Validación de parámetros y gestión de errores robusta
- Configuración externa mediante archivos XML

Esto permite trabajar de forma segura con múltiples bases de datos, como es el caso de este proyecto que utiliza **una base de datos principal** y **otra específica para frases**.

---
Desarrollado por Gabriela Sandoval – M7 DAW 2024-25