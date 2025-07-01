# Votaciones Municipales - Proyecto con DB4O

Este proyecto consiste en el desarrollo de una aplicación orientada a gestionar, consultar y actualizar los datos abiertos publicados por la Generalitat de Catalunya sobre los resultados de las elecciones municipales desde 1979 hasta 2019.

La fuente original de los datos es un fichero CSV proporcionado por el portal Dades Obertes Gencat. A partir de este conjunto de datos, se construye una base de datos orientada a objetos utilizando **DB4O (Database for Objects)**, permitiendo almacenar objetos Java directamente, como municipios, partidos y resultados de votación.

## Objetivo del proyecto

El objetivo principal es importar, estructurar y manipular los datos electorales para poder realizar consultas específicas y actualizaciones eficientes utilizando un modelo de datos propio y almacenamiento en DB4O. Además, se proporciona una interfaz gráfica de usuario que facilita la interacción con los datos.

## Funcionalidades principales

- Importación de los datos desde el CSV oficial.
- Almacenamiento persistente de los datos en una base de datos DB4O.
- Consultas disponibles desde la interfaz:
  - Listado de todos los partidos políticos.
  - Listado de todos los municipios.
  - Resultados de un partido en un municipio concreto.
  - Resultados de todos los partidos en un municipio.
  - Resultados de un partido en una comarca o provincia determinada.
- Actualización de resultados en caso de errores (por ejemplo, corrección de los datos del municipio de Mataró a partir de un nuevo fichero CSV).

## Arquitectura

El proyecto está estructurado siguiendo el patrón **MVC (Modelo-Vista-Controlador)**:

- **Modelo**: Clases como `Municipio`, `Partido`, `Resultado`, etc., que representan los datos reales y su estructura.
- **Vista**: Interfaz gráfica desarrollada con `JFrame` para facilitar la consulta y actualización de datos.
- **Controlador**: Encargado de gestionar la lógica de negocio, conexión con la base de datos, importación de ficheros, validaciones y ejecución de consultas.

## Tecnologías utilizadas

- **Java**
- **DB4O** para almacenamiento de objetos
- **Swing (JFrame)** para la interfaz gráfica
- **Colecciones Java** (`HashMap`, `ArrayList`, etc.) para el manejo interno de los datos

## Actualización de datos

El sistema permite actualizar los datos en caso de errores mediante varias estrategias posibles:
- Eliminar el municipio afectado y volver a insertarlo con los datos corregidos.
- Borrar únicamente la lista de resultados del municipio y añadir los nuevos.
- Comparar los nuevos resultados con los existentes y actualizar solo los que cambian.

---


Desarrollado por *Gabriela Sandoval Castillo – M3 DAW 2024-25*