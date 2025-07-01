# API de Microservicios para Gestión de Eventos

Este proyecto implementa una API RESTful basada en microservicios para gestionar eventos, entradas, usuarios, asientos, localizaciones y compras. Es ideal para plataformas de venta de entradas o la organización de conciertos, eventos deportivos, teatro, etc.

## Funcionalidades principales

- Autenticación con token JWT para proteger rutas sensibles.
- Gestión de eventos (crear, editar, eliminar, consultar).
- Generación de archivos XML con eventos por fecha.
- Gestión de tickets con generación de PDF.
- Administración de localizaciones y asientos.
- Gestión de compras asociadas a usuarios.
- Gestión de usuarios y administradores.

## Microservicios disponibles

| Servicio        | Ruta base                                 | Acciones                  |
|----------------|--------------------------------------------|---------------------------|
| Autenticación  | `/auth`                                    | Login, perfil             |
| Eventos        | `/esdeveniments`                           | CRUD, exportación XML     |
| Tickets        | `/tickets`                                 | CRUD, PDF por referencia  |
| Localizaciones | `/localitzacions`                          | CRUD                      |
| Compras        | `/compres`                                 | CRUD, por usuario         |
| Asientos       | `/seients`                                 | CRUD, por fila/tipo       |
| Usuarios       | `/usuaris`                                 | CRUD                      |
| Administradores| `/admin`                                   | CRUD                      |

## Pruebas con Postman

Puedes probar todas las rutas con la siguiente colección:

🔗 [Colección de Postman](https://gold-firefly-407045.postman.co/workspace/My-Workspace~3f5e3eb7-c4a8-4904-8ec4-5f84d96fe2bd/collection/42588618-d0887037-cf88-4fce-acd1-9564e417508e)

> Recuerda añadir el token en las peticiones protegidas. Puedes asignarlo a nivel de colección con la variable `{{authToken}}`.

## Notas técnicas

- El proyecto utiliza **Doctrine** para la gestión de la base de datos.
- Es necesario comentar los **inserts** en `index.php` después de inicializar la base de datos para evitar errores.
- La API funciona en entorno local con la URL base: `http://localhost/MicroServeis/index.php`

---

Desarrollado por *Gabriela Sandoval Castillo – M7 DAW 2024-25*
