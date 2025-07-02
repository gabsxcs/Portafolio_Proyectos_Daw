# Proyecto de Animaciones CSS

Este proyecto contiene una serie de ejercicios creativos usando **CSS puro** para aplicar animaciones visuales atractivas. Está dividido en varias secciones, cada una con una animación diferente implementada con `@keyframes`, `transforms`, `transitions` y propiedades modernas de CSS.

---

## Ejercicio 1  – Animación Marina

Representa una escena submarina animada con:

- **Peces nadando**: animaciones `nadar1` y `nadar2` simulan movimiento horizontal, junto con `flotarArribaAbajo` para un efecto ondulante.
- **Pez globo flotando**: usa `pezGlobo-flota` y `flotarArribaAbajo` para moverse en un patrón oscilante.
- **Burbujas**: animadas con `flotarBurbujas`, suben desde el fondo hasta desaparecer.
- **Submarino flotante**: animado con `moverSubmarino` para atravesar la pantalla + `flotarArribaAbajo`.

---

## Ejercicio 2 – Zoom con texto emergente

Un contenedor de imagen que al pasar el ratón:

- Aplica un **zoom extremo** con `transform: scale(7)` y desenfoque con `filter: blur()`.
- Muestra una **caja de texto emergente** con animación de escala y rotación usando `transform: scale(0) rotate(-360deg)` → `scale(1)`.

---

##  Ejercicio 3 – Carrusel con miniaturas

Un **slider de imágenes** controlado por hover sobre miniaturas:

- Las miniaturas aplican `:has()` para detectar hover y trasladar (`translateX`) el contenedor `.slides`.
- Efecto de navegación sin JavaScript, completamente en CSS.

---

## Ejercicio 4 – Vinilos y notas musicales

Escena con fondo degradado y vinilos que:

- **Notas musicales** flotan y desaparecen con `flotarYDesaparecer`.
- **Vinilos** suben con rotación (`caer` + `girar`) simulando un efecto de elevación y giro infinito.

---

## Ejercicio 4 – Carrusel 3D

Un carrusel giratorio con imágenes en 3D:

- Usa `transform-style: preserve-3d` y `rotateY()` para rotar el carrusel.
- Al pasar el mouse, se **pausa la rotación** gracias a `animation-play-state: paused`.
- Cada tarjeta `.card` se posiciona con `translateZ` para dar profundidad.

---

## Tecnologías usadas

- HTML5
- CSS3 (transforms, animations, keyframes)
- Layouts con Flexbox
- Selectores avanzados (`:has`, `nth-child`, etc.)

---

Desarrollado por *Gabriela Sandoval Castillo – M9 DAW 2024-25*
