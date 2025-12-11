# Guía de Estilos - Proyecto Abogados FL

## 🎨 Sistema de Diseño

### Paleta de Colores
- **Primary**: `#1f2c3d` (Azul oscuro corporativo)
- **Accent**: `#c5a47e` (Dorado elegante)
- **Light**: `#f8f9fa` (Gris claro de fondo)
- **White**: `#ffffff`
- **Text**: `#333333`
- **Text Light**: `#666666`

### Tipografía
- **Heading**: 'Playfair Display', serif (600 weight)
- **Body**: 'Lato', sans-serif (300-600 weights)
- **Line-height**: 1.6 para body, 1.2 para headings

### Sombras
- **Card**: `0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)`
- **Hover**: `0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)`

## 🏗️ Arquitectura CSS

### Variables CSS Globales
```css
:root {
  --color-primary: #1f2c3d;
  --color-accent: #c5a47e;
  --color-white: #ffffff;
  --color-light: #f8f9fa;
  --color-text: #333333;
  --color-text-light: #666666;

  --font-heading: 'Playfair Display', serif;
  --font-body: 'Lato', sans-serif;

  --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
```

### Tailwind Config
```javascript
colors: {
  primary: '#1f2c3d',
  accent: '#c5a47e',
  light: '#f8f9fa',
},
fontFamily: {
  heading: ['Playfair Display', 'serif'],
  body: ['Lato', 'sans-serif'],
}
```

## 📱 Componentes Base

### Botones
- **Primary**: Fondo accent, texto white, hover con transformación
- **Outline**: Transparente, borde accent, hover fondo accent
- **Padding**: 12px 30px
- **Border-radius**: 5px
- **Transition**: all 0.3s ease

### Contenedores
- **Container**: max-width 1200px, margin auto, padding 0 1rem
- **Section padding**: 80px 0 (60px en mobile)

### Animaciones
- **Fade-in**: opacity 0 → 1, translateY 30px → 0
- **Duration**: 0.6s ease
- **Intersection Observer**: threshold 0.1

## 🎯 Patrones de Implementación

### Estructura de Componentes
1. **HTML semántico** con clases descriptivas
2. **Estilos scoped** en cada componente Astro
3. **Variables CSS** para consistencia
4. **Tailwind utilities** para layouts rápidos
5. **CSS global** en Layout.astro para componentes base

### Convenciones de Nombres
- **Componentes**: PascalCase (Hero.astro, Contact.astro)
- **Clases CSS**: kebab-case (.hero-title, .btn-primary)
- **Variables CSS**: --kebab-case (--color-primary)
- **IDs**: camelCase (heroContent, contactForm)

### Responsive Design
- **Mobile-first** approach
- **Breakpoints**: 768px para tablet/desktop
- **Container padding**: 1rem → 1.5rem en mobile

## 🚀 Optimizaciones

### Performance
- **CSS crítico** en Layout.astro
- **Variables CSS** para evitar repetición
- **Transiciones suaves** pero no excesivas
- **Imágenes optimizadas** (SVGs donde sea posible)

### Accesibilidad
- **Contraste adecuado** (colores corporativos probados)
- **Focus states** implícitos en botones
- **Texto legible** (line-height 1.6)
- **Semántica HTML** correcta

## ⚠️ Limitaciones Técnicas

### Tailwind CSS en Astro
- **No se usan @layer directives** debido a conflictos con Tailwind en Astro
- **Estilos globales** definidos directamente en Layout.astro con `is:global`
- **Especificidad controlada** por orden de declaración y importancia
- **Componentes scoped** para estilos específicos

### Solución Implementada
```css
<style is:global>
  @import "tailwindcss";
  /* Estilos globales aquí */
</style>
```

## 🎨 Sistema CSS Global Extendido (global.css)

### CSS Custom Properties Completas
```css
:root {
  /* Colores extendidos */
  --color-primary: #1f2c3d;
  --color-primary-dark: #16212e;
  --color-primary-light: #2a3d50;
  --color-accent: #c5a47e;
  --color-accent-dark: #b8956d;
  --color-accent-light: #d4b887;
  --color-success: #28a745;
  --color-error: #dc3545;
  --color-warning: #ffc107;
  
  /* Espaciado sistemático */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;
  --spacing-2xl: 3rem;
  --spacing-3xl: 4rem;
  
  /* Sombras por nivel */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  
  /* Transiciones */
  --transition-fast: 150ms ease;
  --transition-base: 300ms ease;
  --transition-slow: 500ms ease;
}
```

### Utility Classes Implementadas
- `.fade-in`, `.slide-in-left`, `.slide-in-right`, `.slide-in-up`, `.slide-in-down`
- `.text-accent`, `.bg-accent`
- `.btn`, `.btn-primary`, `.btn-outline`, `.btn-lg`
- `.card` (con shadow y hover)
- `.container` (max-width 1200px, padding responsive)
- `.section-padding` (5rem desktop, 3rem mobile)

### Animaciones @keyframes
- `fadeIn`: opacity + translateY
- `slideInLeft/Right/Up/Down`: translateX/Y + opacity
- `pulse`: scale animation
- `bounce`: rebote vertical
- `scaleIn`: scale desde 0.9

### Responsive Typography con clamp()
```css
h1 { font-size: clamp(2.5rem, 5vw, 4rem); }
h2 { font-size: clamp(2rem, 4vw, 3rem); }
h3 { font-size: clamp(1.5rem, 3vw, 2rem); }
```

## 🔧 Mantenimiento

### Agregar nuevos colores
1. Definir en `:root` variables
2. Agregar a Tailwind config si se usa
3. Documentar en esta guía

### Nuevos componentes
1. Seguir patrón de estilos scoped
2. Usar variables CSS existentes
3. Mantener consistencia visual
4. Agregar animaciones fade-in si aplica

### Modificaciones
- **No modificar** variables globales sin revisar impacto
- **Probar responsive** en múltiples dispositivos
- **Verificar contraste** de colores nuevos
- **Documentar cambios** en esta guía