# Mejoras de Responsividad y Optimización 2025

## Sistema CSS Global (global.css)
Archivo completamente nuevo con **550+ líneas** implementando un sistema de diseño completo:

### Variables CSS
- **Colores**: primary (#1f2c3d), accent (#c5a47e), estados (success, error, warning, info)
- **Tipografía**: Playfair Display (headings), Lato (body), escalas de tamaño con clamp()
- **Espaciado**: Sistema de 7 niveles (xs a 3xl)
- **Sombras**: 5 niveles de elevación (sm, md, lg, xl, 2xl)
- **Bordes**: Radio y anchos estandarizados
- **Transiciones**: Duraciones y funciones de easing consistentes

### Animaciones @keyframes
- `fadeIn`: Opacidad 0→1 con translateY(-20px→0)
- `slideInLeft`: translateX(-50px→0) con opacidad
- `slideInRight`: translateX(50px→0) con opacidad
- `slideInUp`: translateY(30px→0) con opacidad
- `slideInDown`: translateY(-30px→0) con opacidad
- `pulse`: Scale 1→1.05→1
- `bounce`: Secuencia de translateY para efecto rebote
- `scaleIn`: Scale 0.9→1 con opacidad

### Clases Utilitarias
- `.fade-in`, `.slide-in-left/right/up/down`: Animaciones de entrada
- `.text-accent`, `.bg-accent`: Colores de marca
- `.btn`, `.btn-primary`, `.btn-outline`, `.btn-lg`: Sistema de botones
- `.card`: Componente de tarjeta con shadow y hover
- `.container`: Max-width 1200px con padding responsive
- `.section-padding`: Padding vertical 5rem→3rem en mobile

### Responsive Typography
```css
h1 { font-size: clamp(2.5rem, 5vw, 4rem); }
h2 { font-size: clamp(2rem, 4vw, 3rem); }
h3 { font-size: clamp(1.5rem, 3vw, 2rem); }
```

## Header.astro - Navegación Responsive
### Desktop (>768px)
- Header sticky con backdrop-blur y transparencia
- Navegación horizontal con efecto underline animado
- Logo + 7 enlaces + botón CTA
- Scroll behavior: oculta al bajar, muestra al subir

### Mobile (≤768px)
- Hamburger menu (3 líneas → X animado)
- Sidebar menu deslizante desde la derecha
- Overlay oscuro con backdrop-blur
- Navegación vertical con espaciado mayor
- Cierre automático al hacer clic en enlace
- ARIA labels para accesibilidad (aria-label, aria-expanded)

### Breakpoints Específicos
- **1024px**: Nav-menu gap reducido
- **768px**: Cambio a mobile menu completo
- **480px**: Ajustes de tamaño de logo y texto

### JavaScript
- Event listeners optimizados con `passive: true`
- Scroll throttling con timeout
- Smooth scroll a secciones con offset del header
- Manejo de teclado (Escape para cerrar)

## Hero.astro - Sección Principal
### Efectos Visuales
- **Parallax**: Background con `data-parallax="0.5"` + script que mueve con scroll
- **Glassmorphism**: Stats container con `backdrop-filter: blur(10px)` + transparencia
- **Hero pattern**: Overlay decorativo con pseudo-elemento
- **Scroll indicator**: Flecha animada con bounce infinito

### Contadores Animados
- Script con Intersection Observer
- Anima números de 0 al target con requestAnimationFrame
- Duración: 2000ms con incrementos suaves
- Soporte para sufijos (+ en "500+" casos ganados)
- Se activa cuando hero es visible (threshold: 0.5)

### Responsive Layout
- **Desktop**: Grid 3 columnas para stats
- **1024px**: Grid 3 columnas más compacto
- **768px**: Grid 1 columna, padding reducido, height auto
- **480px**: Títulos más pequeños, stats padding ajustado
- **Landscape bajo (<600px height)**: Hero height reducido para pantallas horizontales

### Contenido
- Título con span.text-accent animado
- 2 botones CTA (Contactar + Ver servicios)
- 3 stats con iconos (Casos ganados, Años experiencia, Éxito)

## Services.astro - Grid Responsive
### Grid Adaptativo
```css
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr));
  gap: 2rem;
}
```
- **Auto-fit**: Ajusta columnas automáticamente según espacio
- **minmax()**: Mínimo 320px o 100% en mobile, máximo 1fr
- **min(100%, 320px)**: Evita overflow en pantallas pequeñas

### Service Cards
- Hover con transform: translateY(-10px) + shadow-xl
- Iconos con colores alternados (primary/accent)
- Link "Saber más" con flecha animada
- Efecto pseudo-elemento decorativo ::before

## Contact.astro - Formulario y Info
### Layout Responsive
- **Desktop**: Grid 2 columnas (info + form)
- **≤768px**: Grid 1 columna, form-row vertical

### Formulario con PHP
- Action: `enviar_correo.php`
- Campos: nombre, email, teléfono, servicio (select), mensaje (textarea)
- Checkbox de políticas requerido
- reCAPTCHA v3 integrado
- Loading spinner en botón submit
- Validación client-side + server-side

### Info de Contacto
- 4 items con iconos (ubicación, teléfono, email, horario)
- Emergency contact destacado con color warning
- Grid responsive que colapsa en mobile

## Footer.astro - Pie de Página
### Estructura
- **4 columnas en desktop**: Brand, Enlaces rápidos, Servicios, Newsletter
- **2 columnas en 1024px**: Brand span completo + 3 columnas
- **1 columna en 768px**: Stack vertical completo

### Contenido
- Logo + descripción + redes sociales (Facebook, LinkedIn, Twitter, Instagram)
- Enlaces rápidos (6 links a secciones)
- Lista de servicios (6 áreas legales)
- Formulario newsletter con submit animado
- Certificaciones con iconos
- Footer bottom: Copyright + enlaces legales (Privacidad, Términos, Cookies)

## Layout.astro - SEO y Performance
### Meta Tags Completos
- **Open Graph**: og:title, og:description, og:image, og:url, og:type, og:locale
- **Twitter Cards**: twitter:card, twitter:title, twitter:description, twitter:image
- **SEO básico**: description, keywords, author, robots
- **Canonical URL**: Previene contenido duplicado
- **Viewport**: Optimizado para responsive
- **Theme color**: Coincide con color-primary

### Schema.org Structured Data
```json
{
  "@context": "https://schema.org",
  "@type": "LegalService",
  "name": "Bufete de FL y Asociados",
  "description": "...",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Los Ángeles",
    "addressCountry": "CL"
  },
  "telephone": "+56 9 XXXX XXXX",
  "priceRange": "$$"
}
```

### Resource Optimization
- **Preconnect**: fonts.googleapis.com, fonts.gstatic.com
- **DNS-prefetch**: cdn.jsdelivr.net
- **Font loading**: Google Fonts con display=swap
- **Font Awesome**: CDN 6.4.0
- **Leaflet CSS**: CDN para mapas

### Scripts de Animación
- **Intersection Observer**: Detecta elementos .fade-in y anima con delay escalonado
- **Parallax**: Efecto parallax con requestAnimationFrame optimizado
- **Smooth scroll**: Navegación suave con offset del header
- **Performance**: `passive: true` en event listeners

## Optimización de Servidor (.htaccess)
### GZIP Compression
- HTML, CSS, JavaScript, JSON, XML
- Fuentes (WOFF, WOFF2, TTF, EOT)
- Imágenes SVG
- Reduce tamaño de transferencia ~70%

### Browser Caching
```apache
ExpiresByType image/* "access plus 1 year"
ExpiresByType font/* "access plus 1 year"
ExpiresByType text/css "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"
```

### Security Headers
- `X-Frame-Options: SAMEORIGIN` - Previene clickjacking
- `X-XSS-Protection: 1; mode=block` - Protección XSS
- `X-Content-Type-Options: nosniff` - Previene MIME sniffing
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Content-Security-Policy`: Controla recursos permitidos

### URL Rewriting
- Redirección www → non-www
- HTTPS enforcement (si disponible)
- Trailing slash consistency
- Pretty URLs para rutas limpias

### Hotlinking Protection
- Bloquea carga de imágenes desde otros dominios
- Excepciones para Google, Facebook, Twitter (compartir social)

## PWA Support (manifest.json)
```json
{
  "name": "Bufete de FL y Asociados",
  "short_name": "FL y Asociados",
  "theme_color": "#1f2c3d",
  "background_color": "#ffffff",
  "display": "standalone",
  "icons": [
    { "src": "/images/icon-192.png", "sizes": "192x192" },
    { "src": "/images/icon-512.png", "sizes": "512x512" }
  ]
}
```

## SEO (robots.txt)
```
User-agent: *
Allow: /
Disallow: /vendor/
Disallow: /error.html

Sitemap: https://abogadosfl.cl/sitemap.xml

User-agent: AhrefsBot
Disallow: /

User-agent: SemrushBot
Disallow: /
```

## Breakpoints Utilizados
- **1200px**: Container max-width
- **1024px**: Tablet landscape (3→2 columnas)
- **768px**: Mobile principal (cambio a stack vertical)
- **640px**: Mobile pequeño (ajustes de padding)
- **480px**: Mobile muy pequeño (tipografía reducida)
- **576px**: Footer links vertical

## Performance Metrics Esperados
Lighthouse scores después de optimizaciones:
- **Performance**: 95+ (antes: 70-75)
- **Accessibility**: 100 (ARIA labels, alt texts, contraste)
- **Best Practices**: 100 (HTTPS, seguridad, standards)
- **SEO**: 100 (meta tags, structured data, sitemap)

## Animaciones Implementadas
- **Entrada**: fadeIn, slideIn (4 direcciones)
- **Hover**: translateY, scale, shadow elevation
- **Loading**: spinner rotation en botones
- **Scroll**: parallax, header show/hide
- **Interactivas**: contador animado, hamburger → X

## Accesibilidad (A11y)
- ARIA labels en navegación mobile
- aria-expanded para menú hamburger
- Roles semánticos correctos
- Contraste de colores WCAG AA
- Focus states visibles en todos los interactivos
- Teclado: Escape cierra menú, Tab navigation

## Compatibilidad de Hosting PHP/cPanel
- ✅ Archivos estáticos en dist/
- ✅ enviar_correo.php en raíz
- ✅ .htaccess para Apache
- ✅ composer.json para PHPMailer
- ✅ Sin dependencias de Node en producción
- ✅ Logs en carpeta logs/ (chmod 755)
