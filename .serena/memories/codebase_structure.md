# Codebase Structure

## Root Level
- `astro.config.mjs`: Astro configuration with Tailwind integration
- `package.json`: Node.js dependencies and scripts
- `tailwind.config.mjs`: Tailwind configuration with custom theme
- `tsconfig.json`: TypeScript configuration
- `composer.json`: PHP dependencies (PHPMailer)
- `enviar_correo.php`: Contact form PHP handler
- `README.md`: Comprehensive documentation

## src/
- `components/`: Astro components
  - `Header.astro`: Navigation header
  - `Hero.astro`: Hero section
  - `About.astro`: About section
  - `StatsBar.astro`: Statistics bar
  - `Services.astro`: Legal services
  - `Team.astro`: Team members
  - `Testimonials.astro`: Client testimonials
  - `News.astro`: News/blog section
  - `Contact.astro`: Contact form
  - `Footer.astro`: Site footer
  - `Welcome.astro`: Welcome section
- `layouts/`: Layout templates
  - `Layout.astro`: Main layout
- `pages/`: Route pages
  - `index.astro`: Homepage
- `styles/`: Stylesheets
  - `global.css`: Global styles
- `assets/`: Astro assets (SVG files)

## public/
- `gracias.html`: Success page for contact form
- `error.html`: Error page for contact form
- `images/`: Static images (placeholders documented)

## vendor/
- Composer autoload and PHPMailer library