# Chat con Conexión a Base de Datos

Este proyecto consiste en el desarrollo de una aplicación de chat destinada a ser utilizada entre alumnos dentro del aula. El sistema permite la comunicación en tiempo real entre usuarios a través de una base de datos MySQL centralizada, que gestiona la conexión y el intercambio de mensajes.

La aplicación implementa una arquitectura cliente-servidor, donde los clientes se conectan a la base de datos utilizando procedimientos almacenados. Estas funciones permiten realizar acciones como enviar y recibir mensajes, consultar la lista de usuarios conectados, así como gestionar el inicio y cierre de sesión de cada usuario.

El sistema aplica ciertas restricciones para asegurar un uso correcto y seguro del chat. Por ejemplo, solo se permite una conexión por máquina y no se puede utilizar un nick que ya esté en uso. El acceso a la base de datos se realiza mediante un usuario con permisos limitados, garantizando que solo se puedan ejecutar las acciones permitidas a través de los procedimientos definidos.

## Funcionalidades principales

- Iniciar sesión con un apodo (sin necesidad de registro)
- Ver la lista de usuarios conectados con la hora de conexión
- Enviar mensajes a otros usuarios
- Recibir y visualizar los mensajes del resto de usuarios en tiempo real (incluyendo autor y hora)
- Salir del chat y volver a entrar con otro apodo si se desea

## Arquitectura

El proyecto sigue el patrón de arquitectura MVC, separando claramente:

- **Modelo**: gestión de datos y conexión con la base de datos
- **Vista**: interfaz de usuario gráfica desarrollada con `JFrame`
- **Controlador**: lógica de la aplicación

---

Desarrollado por *Gabriela Sandoval Castillo – M3 DAW 2024-25*
