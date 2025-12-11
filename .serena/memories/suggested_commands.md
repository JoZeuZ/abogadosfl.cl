# Suggested Commands

## Development
- `npm run dev`: Start development server (http://localhost:4321/)
- `npm run build`: Build for production (genera carpeta dist/)
- `npm run preview`: Preview production build locally
- `npm install`: Install Node.js dependencies

## Estado Actual (Diciembre 2025)
✅ Servidor funcionando sin errores
✅ TypeScript strict mode - todos los errores resueltos
✅ CSS válido - sin errores de sintaxis
✅ Configuración de site URL completada

## PHP/Production
- `composer install`: Install PHP dependencies (PHPMailer)
- `composer install --no-dev --optimize-autoloader`: Production install

## System Commands (Windows)
- `dir`: List directory contents
- `cd <path>`: Change directory
- `type <file>`: Display file contents
- `findstr <pattern> <file>`: Search for pattern in file
- `git status`: Check git status
- `git add .`: Stage all changes
- `git commit -m "message"`: Commit changes
- `git push`: Push to repository

## Deployment
- Build with `npm run build`
- Upload dist/ folder to server via FTP
- Run `composer install` on server
- Set permissions: `chmod 755 logs/`, `chmod 644 *.php`

## Linting/Formatting
- No specific linters configured, but TypeScript strict mode enforces code quality
- Use Astro's built-in checking with `@astrojs/check`