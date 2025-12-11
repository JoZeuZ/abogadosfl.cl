# Code Style and Conventions

## TypeScript
- Strict type checking enabled (extends astro/tsconfigs/strict)
- JSX import source: react
- JSX syntax: react-jsx

## Naming Conventions
- Components: PascalCase (e.g., Header.astro, Contact.astro)
- Files: PascalCase for components, camelCase for others
- CSS classes: Tailwind utility classes, custom colors use primary/accent/light

## File Structure
- src/components/: Reusable Astro components
- src/layouts/: Layout templates
- src/pages/: Route pages
- src/styles/: Global CSS
- public/: Static assets

## Astro Specific
- Components use .astro extension
- Frontmatter for component logic
- HTML-like syntax with JSX expressions
- Static output configuration

## PHP
- enviar_correo.php: Contact form handler
- Uses PHPMailer for email sending
- Input validation and sanitization
- reCAPTCHA verification

## CSS
- Tailwind utility-first approach
- Custom color palette defined in tailwind.config.mjs
- Responsive design with mobile-first approach