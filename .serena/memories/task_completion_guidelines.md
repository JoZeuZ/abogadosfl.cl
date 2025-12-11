# Task Completion Guidelines

## After Code Changes
1. Run `npm run build` to ensure no build errors
2. Test the site with `npm run dev` and check functionality
3. For PHP changes, test form submission locally if possible
4. Verify responsive design on different screen sizes
5. Check SEO elements (meta tags, alt texts)

## Before Commit
1. Ensure all dependencies are installed
2. Run build to catch any errors
3. Test contact form functionality
4. Verify all images are in place
5. Check that reCAPTCHA keys are configured (for production)

## Deployment Checklist
1. Build production version: `npm run build`
2. Install PHP dependencies on server: `composer install --no-dev`
3. Upload files to correct directories
4. Set proper permissions on logs/ and PHP files
5. Configure SMTP settings in enviar_correo.php
6. Update reCAPTCHA keys
7. Test contact form submission
8. Verify email delivery

## Code Quality
- Use TypeScript types where possible
- Follow component naming conventions
- Keep components modular and reusable
- Use Tailwind classes consistently
- Sanitize and validate all user inputs in PHP
- Log errors appropriately