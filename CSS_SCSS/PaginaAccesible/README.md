# Mitología Griega – Página Web Accesible

Este proyecto es una página web dedicada a la mitología griega, desarrollada con un enfoque centrado en la **accesibilidad web**, cumpliendo con los criterios del módulo **M9 – UF3**. Se han aplicado técnicas de diseño inclusivo para asegurar una experiencia sin barreras a todos los usuarios, incluidos aquellos con discapacidades.

---

## ♿ Principales características de accesibilidad

- **Estructura semántica** con etiquetas HTML5 (`<header>`, `<main>`, `<nav>`, etc.).
- **Navegación accesible**:
  - Menú consistente con roles ARIA (`role="navigation"`).
  - Enlaces “saltar a contenido”.
  - Títulos descriptivos y `aria-current="page"` en el menú.
- **Soporte completo de teclado** (`tabindex`, orden lógico).
- **Imágenes**:
  - `alt` descriptivos en informativas.
  - `aria-hidden="true"` en decorativas.
  - `aria-describedby` para descripciones extendidas.
- **Formularios accesibles**:
  - Etiquetas `<label>` vinculadas.
  - Campos requeridos con `aria-required="true"`.
  - Mensajes de error con `role="alert"`.
  - Agrupación con `<fieldset>` y `<legend>`.
- **Tablas accesibles**:
  - Encabezados con `scope`, descripciones con `aria-describedby`.
  - `<caption>` oculto para lectores con `sr-only`.
- **Contraste AAA** y foco visible.
- Atributos `lang` para contenido multilingüe.

---

## Páginas destacadas

| Página   | Elementos clave |
|----------|------------------|
| **Inicio**   | Galería accesible, encabezados semánticos, roles WAI-ARIA |
| **Dioses**   | Tarjetas navegables con teclado, imágenes con descripciones |
| **Héroes**   | Tabla accesible, citas en griego (`lang="el"`), mapa interactivo |
| **Contacto** | Formulario validado con ARIA, `optgroup`, agrupación de campos |

---

## 🛠 Herramientas utilizadas

- Validación: **WAVE**, **AXE DevTools**
- Lectores de pantalla: **NVDA**, **ChromeVox**


---

Desarrollado por *Gabriela Sandoval Castillo – M9 DAW 2024-25*
