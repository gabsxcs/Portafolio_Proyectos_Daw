# Proyecto MVC con Doctrine – Gestión de Frases Célebres

Este proyecto es una aplicación web construida con el patrón **Modelo-Vista-Controlador (MVC)** y utilizando **Doctrine ORM** para la gestión de la base de datos. La aplicación permite administrar frases célebres, autores y temas, y cuenta con una interfaz navegable desde una landing page.

## Tecnologías utilizadas

- **PHP** (POO)
- **Doctrine ORM** (mapeo objeto-relacional)
- **MySQL**
- Patrón **MVC** personalizado
- Filtros de entrada y sanitización propia

---

## ¿Qué funcionalidades incluye?

- CRUD completo de **Autores**, **Temas** y **Frases**.
- Vistas para mostrar, editar, crear y eliminar cada entidad.
- Buscador con filtros y paginación para cada módulo.
- Asociación entre frases, autores y temas.
- Cálculo de número de frases por autor.
- Eliminación en cascada (autor → frases → temas relacionados).
- Controlador frontal (`FrontController`) que enruta dinámicamente las peticiones HTTP (GET/POST).
- Integración con Doctrine para:
  - Consultas con QueryBuilder y DQL.
  - Repositorios personalizados.
  - Inyección del `EntityManager`.

---

## Estructura general del proyecto

```
/src
  ├── Controller/       # Controladores MVC (AuthorController, ThemeController, etc.)
  ├── Entity/           # Entidades mapeadas con anotaciones Doctrine
  ├── Repository/       # Repositorios personalizados para acceso a datos
  ├── View/             # Vistas HTML + PHP (mostrar, crear, editar)
  └── Core/
        └── Http.php    # Enrutador que maneja las peticiones y llama a los controladores

/config
  └── cli-config.php    # Configuración de Doctrine

/index.php         # Punto de entrada (dispatcher + error handling)
```

---

## Cómo funciona la lógica de routing

El enrutamiento se realiza de forma manual a través de un **FrontController**, que analiza la URL y lanza el controlador y método correspondiente. Por ejemplo:

```
?Author/show           → AuthorController::show()
?Phrase/showCrear      → PhraseController::showCrear()
?Theme/delete&id=4     → ThemeController::delete()
```

El controlador `Http` se encarga de cargar dinámicamente los controladores e inyectar el **EntityManager de Doctrine** en ellos.

---

## Ejemplo de entidad con Doctrine

```php
/**
 * @Entity(repositoryClass="Frases\Repository\AuthorRepository")
 * @Table(name="tbl_Authors")
 */
class Author {
    /** @Id @GeneratedValue @Column(type="integer") */
    private $id;

    /** @Column(type="string", length=100, unique=true) */
    private $name;

    /** @Column(type="string", length=500, nullable=true) */
    private $description;

    /** 
     * @OneToMany(targetEntity="Frases\Entity\Phrase", mappedBy="author") 
     */
    private $phrases;
}
```

---

## Repositorios personalizados

Cada entidad tiene su repositorio con métodos como:

- `getAllAuthors()`
- `getAuthorAndCountFrases()`
- `getFilteredAuthors($search, $offset, $limit)`
- `deleteAllFrasesTemasByAuthor($id)`

---

## Vistas disponibles

- `show()`: Tabla con resultados, búsqueda y paginación.
- `showCrear() / formCrear()`: Formulario para crear con gestión de errores.
- `showEditar() / formEditar()`: Formulario para edición con errores.
- `showAuthorDetalle()`: Página de detalle del autor y sus frases.
- Vistas análogas para temas y frases.

---

## Notas adicionales

- Se usa sanitización personalizada para entradas (`sanitize_input`).
- Las rutas siguen un esquema REST básico pero en formato tipo `GET ?Controller/accion`.
- Los formularios son procesados por el método `form()` del controlador correspondiente.

---

Desarrollado por Gabriela Sandoval – M7 DAW 2024-25