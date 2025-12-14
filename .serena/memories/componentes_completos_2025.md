# Componentes Completos del Proyecto

## Header.astro
### Estructura HTML
- `header#header.header`: Contenedor principal con position fixed
- `nav.navbar`: Navegación con flex layout
- `div.nav-brand`: Logo + texto "Bufete de FL y Asociados"
- `ul#nav-menu.nav-menu`: Lista de 7 enlaces + botón CTA mobile
  - Inicio, Nosotros, Servicios, Equipo, Testimonios, Noticias, Contacto
  - CTA mobile solo visible en móvil
- `div.nav-actions`: Botón CTA desktop + hamburger toggle
- `button#mobile-toggle.mobile-toggle`: 3 span.hamburger-line

### CSS Classes
- `.header`: Fixed top, backdrop-blur, transition transform
- `.header.scrolled`: Background opaco, shadow
- `.header.hidden`: translateY(-100%) cuando scroll down
- `.nav-menu`: Flex row desktop, sidebar mobile
- `.nav-menu.active`: translateX(0) muestra sidebar
- `.mobile-toggle`: Grid 3 filas para líneas hamburger
- `.mobile-toggle.active`: Transforma a X
- `.nav-link::after`: Underline animado con scaleX

### JavaScript
- `window.addEventListener('scroll')`: Show/hide en scroll
- `mobileToggle.addEventListener('click')`: Toggle sidebar
- `navLinks.forEach() click`: Smooth scroll + cierre menu
- `document click/keydown`: Cierre al clickear fuera o Escape

## Hero.astro
### Estructura HTML
- `section#inicio.hero`: Container principal con min-height 100vh
- `div.hero-background[data-parallax="0.5"]`: Fondo con parallax
- `div.hero-overlay`: Overlay oscuro
- `div.hero-pattern`: Patrón decorativo
- `div.hero-content`: Título + subtitle + botones
  - `h1.hero-title`: "Justicia y Excelencia" con span.text-accent
  - `p.hero-subtitle`: Descripción del bufete
  - `div.hero-actions`: 2 botones (Contactar + Ver servicios)
- `div.hero-stats`: Grid 3 stats con glassmorphism
  - Cada `.stat-item`: icon + number[data-target][data-suffix] + label
- `div.scroll-indicator`: Flecha bounce para scroll down

### CSS Classes
- `.hero`: Position relative, overflow hidden
- `.hero-background`: Background-size cover, transition transform
- `.hero-stats`: Display grid 3 cols, backdrop-filter blur(10px)
- `.stat-item`: Text center, hover translateY(-5px)
- `.scroll-indicator`: Position absolute bottom, animation bounce

### JavaScript
- `animateCounters()`: Función que anima contadores de 0 a target
  - Lee `data-target` y `data-suffix`
  - Duración 2000ms con incrementos suaves
  - Usa requestAnimationFrame
- `IntersectionObserver`: Activa counters cuando hero visible (threshold 0.5)
- `scrollIndicator.click`: Scroll suave a siguiente sección
- Parallax con `requestAnimationFrame` y `ticking` flag

## Services.astro
### Estructura HTML
- `section#servicios.services.section-padding`
- `div.section-header`: Subtitle + title + description
- `div.services-grid`: Grid de service cards (iterado desde array)
  - Cada card: `.service-card.service-card--primary|accent`
    - `.service-icon`: i con clase Font Awesome
    - `h3.service-title`: Nombre del servicio
    - `p.service-description`: Descripción breve
    - `a.service-link`: "Saber más" con flecha
- `div.services-cta`: Call to action final con botón

### CSS Classes
- `.services-grid`: Grid auto-fit minmax(min(100%, 320px), 1fr)
- `.service-card`: Padding 2rem, border-radius, shadow-md
- `.service-card::before`: Pseudo-elemento decorativo accent
- `.service-card:hover`: translateY(-10px), shadow-xl
- `.service-card--primary/accent`: Variantes de color para iconos

### Data Array (en frontmatter)
```typescript
const services = [
  { title: "Derecho Civil", icon: "fas fa-gavel", description: "...", variant: "primary" },
  // ... 6 servicios totales
];
```

## Contact.astro
### Estructura HTML
- `section#contacto.contact.section-padding`
- `div.contact-grid`: Grid 2 columnas (info + form)
  - **Columna 1**: `div.contact-info`
    - 4 `.contact-item`: ubicación, teléfono, email, horario
    - `.emergency-contact`: Atención urgencias 24/7
  - **Columna 2**: `form#contactForm.contact-form`
    - Campos: nombre, email, teléfono (form-row 2 cols)
    - Select servicio (8 opciones)
    - Textarea mensaje
    - Checkbox políticas + enlaces
    - Button submit con `.btn-loader` (spinner)
    - `p.form-note`: Nota sobre confidencialidad

### CSS Classes
- `.contact-grid`: Grid 2 cols gap 3rem, 1 col en mobile
- `.contact-item`: Flex gap 1rem con icon circular
- `.contact-form`: Max-width 600px
- `.form-row`: Grid 2 cols, 1 col en mobile
- `.form-group`: Margin bottom, label + input styling
- `.contact-submit`: Button con loader oculto por defecto
- `.btn-loader`: Position absolute, opacity 0 inicial

### JavaScript
- `form.addEventListener('submit')`: 
  - preventDefault
  - Muestra loader, oculta texto
  - Obtiene token reCAPTCHA v3
  - FormData con append('g-recaptcha-response', token)
  - Fetch POST a enviar_correo.php
  - Redirección a gracias.html o error.html

## Footer.astro
### Estructura HTML
- `footer.footer`
  - **Footer Main**: `div.footer-main`
    - `div.footer-grid`: Grid 4 columnas
      - **Col 1**: `.footer-brand`
        - Logo + nombre
        - Descripción breve
        - 4 social links (Facebook, LinkedIn, Twitter, Instagram)
      - **Col 2**: Enlaces Rápidos
        - 6 links a secciones de la página
      - **Col 3**: Servicios
        - 6 links a áreas legales
      - **Col 4**: Contacto + Newsletter
        - 4 contact items
        - `form#newsletterForm`: Input email + button
        - `.footer-certifications`: 2 badges
  - **Footer Bottom**: `div.footer-bottom`
    - Copyright text
    - 3 enlaces legales (Privacidad, Términos, Cookies)

### CSS Classes
- `.footer`: Background primary, color white
- `.footer-grid`: Grid 4 cols, gap 2rem, responsive (4→2→1)
- `.footer-brand`: Grid-column span 1 desktop, 2 en tablet
- `.social-link`: Círculo con hover scale y color accent
- `.footer-links a`: Hover con translateX(5px) + color accent
- `.newsletter-form`: Flex con input + button
- `.newsletter-btn`: Hover con background accent
- `.footer-bottom`: Border-top, flex justify-between

### JavaScript
- `newsletterForm.addEventListener('submit')`:
  - preventDefault
  - Validación email
  - Alert de éxito (placeholder para integración futura)

## Layout.astro
### Estructura
- `html[lang="es"]`
- `head`: Meta tags completos (SEO, OG, Twitter)
  - Preconnect a Google Fonts
  - Link a estilos globales, Tailwind, Font Awesome, Leaflet
  - Script Schema.org JSON-LD
- `body`
  - `<slot />`: Inyección de contenido de páginas
  - Scripts globales (animaciones, parallax, smooth scroll)

### Scripts Incluidos
1. **Intersection Observer para animaciones**:
   - Observa `.fade-in`, `.slide-in-*`
   - Añade clase `visible` cuando intersecta
   - Delay escalonado con `data-delay`

2. **Parallax Effect**:
   - Elementos con `data-parallax`
   - Scroll listener con requestAnimationFrame
   - Calcula translateY basado en scroll * speed

3. **Smooth Scroll**:
   - Click en links con href="#..."
   - `scrollTo({ behavior: 'smooth' })` con offset

## Otros Componentes Existentes
### About.astro
- Sección "Nosotros" con historia del bufete
- Grid con texto + imagen

### StatsBar.astro
- Barra de estadísticas con números impactantes
- 4 métricas con iconos

### Team.astro
- Grid de miembros del equipo
- Cards con foto, nombre, cargo, bio, redes sociales

### Testimonials.astro
- Carrusel de testimonios de clientes
- Sistema de navegación dots

### News.astro
- Grid de noticias/blog
- Cards con imagen, título, excerpt, fecha, link

### Welcome.astro
- Sección de bienvenida inicial
- Probablemente duplica funcionalidad con About

### Map.astro
- Integración con Leaflet
- Mapa interactivo con marcador de ubicación
- Archivo map-client.js para lógica del mapa

## Archivos de Configuración
### astro.config.mjs
```javascript
export default defineConfig({
  integrations: [tailwind()],
  output: 'static',
  build: {
    inlineStylesheets: 'auto'
  }
});
```

### tailwind.config.mjs
- Theme extend con colores personalizados
- primary: '#1f2c3d'
- accent: '#c5a47e'
- Fuentes: Playfair Display, Lato

### tsconfig.json
- Extends: astro/tsconfigs/strict
- jsxImportSource: react
- Paths alias configurados

## Páginas
### index.astro
- Importa Layout
- Importa todos los componentes
- Orden: Header → Hero → Welcome → About → StatsBar → Services → Team → Testimonials → News → Contact → Map → Footer

## Archivos Públicos
### gracias.html
- Página de confirmación post-envío de formulario
- Link para volver a inicio

### error.html
- Página de error en envío de formulario
- Link para reintentar

### enviar_correo.php
- Recibe POST del formulario
- Validación de campos
- Verificación reCAPTCHA
- PHPMailer para envío SMTP
- Logs en logs/contact_log.txt
- Respuestas JSON + redirecciones

## Dependencias
### Node.js (package.json)
- astro: ^5.12.8
- @astrojs/tailwind: ^6.0.2
- tailwindcss: ^3.0
- typescript: ^5.0
- leaflet: ^1.9.4

### PHP (composer.json)
- phpmailer/phpmailer: ^6.8
- PHP 7.2+ requerido

## Assets
### Imágenes
- Logo del bufete
- Imágenes de equipo
- Backgrounds para hero/sections
- Icons (192x192, 512x512 para PWA)

### Ubicación
- public/images/: Estáticos servidos directamente
- src/assets/: Procesados por Astro (optimización)
