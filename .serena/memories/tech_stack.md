# Tech Stack Details

## Frontend
- **Astro**: v5.12.8 - Static site generator for fast, SEO-friendly websites
- **Tailwind CSS**: v3.0.0 - Utility-first CSS framework
- **TypeScript**: v5.0.0 - Type-safe JavaScript with strict configuration
- **@astrojs/tailwind**: Integration for Tailwind in Astro
- **@astrojs/check**: Type checking for Astro

## Backend
- **PHP**: 7.2+ required for production
- **PHPMailer**: v6.8 - Email sending library with SMTP support

## Configuration
- **astro.config.mjs**: Static output, Tailwind integration, site: 'https://www.abogadosfl.cl'
- **tailwind.config.mjs**: Custom colors (primary: #1f2c3d, accent: #c5a47e), fonts (Playfair Display, Lato)
- **tsconfig.json**: Extends Astro strict config, JSX react-jsx

## Estado Actual (Diciembre 2025)
✅ Todos los errores TypeScript corregidos con type assertions
✅ Servidor de desarrollo funcionando en localhost:4321
✅ Build configuration completa con site URL
✅ CSS válido sin errores de sintaxis

## Development Tools
- Node.js 18+
- npm/yarn for package management
- Composer for PHP dependencies