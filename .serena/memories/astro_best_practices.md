# Mejores Prácticas de Astro

Este documento resume las mejores prácticas para el desarrollo con Astro, basadas en la documentación oficial.

## 1. Optimización de Imágenes

Para un rendimiento óptimo y para evitar el "Cumulative Layout Shift" (CLS), Astro recomienda usar su sistema de manejo de assets integrado.

- **Acción:** Mover las imágenes del directorio `public/` a `src/assets/`.
- **Implementación:** Utilizar el componente `<Image />` de `astro:assets` para renderizar las imágenes. Este componente se encarga automáticamente de la optimización, la generación de formatos modernos (como WebP), y la inclusión de atributos `width` y `height`.

**Ejemplo:**

```astro
---
// Antes (Imagen no optimizada desde /public)
---
<img src="/images/mi-imagen.png" alt="Descripción" />

---
// Después (Imagen optimizada desde /src/assets)
import { Image } from 'astro:assets';
import miImagen from '../assets/images/mi-imagen.png';
---
<Image src={miImagen} alt="Descripción" />
```

## 2. Variables de Entorno

Para manejar de forma segura las claves de API y otras credenciales, se debe utilizar el sistema de variables de entorno de Astro.

- **Acción:** Crear un archivo `.env` en la raíz del proyecto y añadirlo a `.gitignore`.
- **Variables del Lado del Cliente:** Para que una variable sea accesible en el navegador (código del cliente), su nombre debe tener el prefijo `PUBLIC_`.
- **Implementación:** Acceder a las variables en los componentes de Astro a través de `import.meta.env`.

**Ejemplo para la clave de sitio de reCAPTCHA:**

1.  **En el archivo `.env`:**
    ```
    PUBLIC_RECAPTCHA_SITE_KEY='tu_clave_publica_de_recaptcha'
    ```

2.  **En el componente `Contact.astro`:**
    ```astro
    ---
    const recaptchaSiteKey = import.meta.env.PUBLIC_RECAPTCHA_SITE_KEY;
    ---
    <div class="g-recaptcha" data-sitekey={recaptchaSiteKey}></div>
    ```
