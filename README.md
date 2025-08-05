# Jurídica & Asociados - Sitio Web del Bufete

Un sitio web profesional y moderno para el bufete de abogados Jurídica & Asociados, desarrollado con Astro para máximo rendimiento y optimización SEO.

## 🌟 Características

- **Diseño Moderno**: Interfaz elegante con colores corporativos (azul oscuro #1f2c3d y dorado #c5a47e)
- **Optimizado para Rendimiento**: Construido con Astro para sitios estáticos ultra-rápidos
- **Responsive**: Diseño completamente adaptable a dispositivos móviles y desktop
- **Formulario de Contacto Robusto**: Sistema seguro con validación, reCAPTCHA y respaldo
- **SEO Optimizado**: Metadatos estructurados y contenido optimizado para motores de búsqueda

## 🏗️ Arquitectura

- **Frontend**: Astro + HTML/CSS + JavaScript minimal
- **Backend**: Script PHP para formulario de contacto
- **Email**: PHPMailer con autenticación SMTP
- **Seguridad**: reCAPTCHA v3 + validación y sanitización
- **Hosting**: Compatible con hosting compartido estándar

## 📋 Prerrequisitos

- Node.js 18+ 
- npm o yarn
- PHP 7.2+ (para el servidor de producción)
- Acceso SMTP para envío de emails
- Cuenta de Google reCAPTCHA

## 🚀 Instalación y Configuración

### 1. Clonar y configurar el proyecto

```bash
# Instalar dependencias
npm install

# Instalar PHPMailer (para producción)
composer install
```

### 2. Configurar variables del formulario

Editar el archivo `enviar_correo.php`:

```php
$config = [
    'smtp' => [
        'host' => 'smtp.gmail.com',           // Su servidor SMTP
        'port' => 587,
        'username' => 'info@juridica.com',    // Su email SMTP
        'password' => 'su_password_smtp',     // Su password SMTP
        'from_email' => 'info@juridica.com',
        'from_name' => 'Jurídica & Asociados',
        'to_email' => 'contacto@juridica.com' // Email donde recibir consultas
    ],
    'recaptcha' => [
        'secret_key' => 'YOUR_RECAPTCHA_SECRET_KEY' // Su clave secreta de reCAPTCHA
    ]
];
```

### 3. Configurar reCAPTCHA

1. Crear cuenta en [Google reCAPTCHA](https://www.google.com/recaptcha/)
2. Agregar su dominio y obtener las claves
3. Actualizar la clave del sitio en `src/components/Contact.astro`:

```html
<div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY"></div>
```

4. Actualizar la clave secreta en `enviar_correo.php`

## 🛠️ Desarrollo

```bash
# Iniciar servidor de desarrollo
npm run dev

# Compilar para producción
npm run build

# Vista previa de la compilación
npm run preview
```

## 📦 Despliegue

### 1. Compilar el sitio

```bash
npm run build
```

### 2. Subir archivos al servidor

Subir via FTP a la carpeta `public_html`:

```
public_html/
├── dist/              # Contenido compilado de Astro
├── enviar_correo.php  # Script del formulario
├── composer.json      # Dependencias PHP
├── vendor/           # Librerías PHP (ejecutar composer install en servidor)
└── logs/             # Carpeta para logs (crear con permisos 755)
```

### 3. Configurar permisos

```bash
chmod 755 logs/
chmod 644 enviar_correo.php
chmod 644 composer.json
```

### 4. Instalar dependencias PHP en el servidor

```bash
composer install --no-dev --optimize-autoloader
```

## 📁 Estructura del Proyecto

```
├── src/
│   ├── components/          # Componentes reutilizables
│   │   ├── Header.astro     # Navegación principal
│   │   ├── Hero.astro       # Sección hero
│   │   ├── About.astro      # Sobre nosotros
│   │   ├── StatsBar.astro   # Estadísticas
│   │   ├── Services.astro   # Servicios legales
│   │   ├── Team.astro       # Equipo de abogados
│   │   ├── Testimonials.astro # Testimonios
│   │   ├── News.astro       # Blog/noticias
│   │   ├── Contact.astro    # Formulario de contacto
│   │   └── Footer.astro     # Pie de página
│   ├── layouts/
│   │   └── Layout.astro     # Layout principal
│   └── pages/
│       └── index.astro      # Página principal
├── public/
│   ├── gracias.html         # Página de éxito
│   ├── error.html           # Página de error
│   └── images/              # Imágenes del sitio
├── enviar_correo.php        # Script del formulario
├── composer.json            # Dependencias PHP
└── package.json             # Dependencias Node.js
```

## 🎨 Personalización

### Colores Corporativos

```css
:root {
  --color-primary: #1f2c3d;    /* Azul oscuro */
  --color-accent: #c5a47e;     /* Dorado */
  --color-white: #ffffff;
  --color-light: #f8f9fa;
}
```

### Tipografías

- **Títulos**: Playfair Display (serif elegante)
- **Cuerpo**: Lato (sans-serif moderna)

### Imágenes Requeridas

Agregar estas imágenes en `public/images/`:

```
hero-bg.jpg           # Imagen de fondo del hero (1920x1080)
lawyer-portrait.jpg   # Retrato abogado para sección "sobre nosotros"
team-1.jpg           # Foto Dr. Carlos Mendoza
team-2.jpg           # Foto Dra. María Rodríguez  
team-3.jpg           # Foto Dr. Andrés López
team-4.jpg           # Foto Dra. Laura González
client-1.jpg         # Foto cliente testimonio 1
client-2.jpg         # Foto cliente testimonio 2
client-3.jpg         # Foto cliente testimonio 3
client-4.jpg         # Foto cliente testimonio 4
news-1.jpg           # Imagen artículo 1
news-2.jpg           # Imagen artículo 2
news-3.jpg           # Imagen artículo 3
pattern.png          # Patrón para fondo de estadísticas
logo.svg             # Logo principal
logo-white.svg       # Logo blanco para footer
```

## 🔧 Configuración del Servidor

### Requisitos del Hosting

- PHP 7.2+ con extensiones: `curl`, `openssl`, `mbstring`
- Soporte para Composer
- Acceso a envío de emails vía SMTP
- Permisos para crear directorios y archivos

### Variables de Entorno (Opcional)

Para mayor seguridad, puede usar variables de entorno:

```php
$config = [
    'smtp' => [
        'host' => $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com',
        'username' => $_ENV['SMTP_USER'] ?? 'info@juridica.com',
        'password' => $_ENV['SMTP_PASS'] ?? 'su_password',
        // ...
    ]
];
```

## 🔐 Seguridad

- ✅ Validación y sanitización de todos los inputs
- ✅ Protección XSS con `htmlspecialchars`
- ✅ Verificación reCAPTCHA obligatoria
- ✅ Headers de seguridad configurados
- ✅ Logs de errores activados
- ✅ Backup automático de mensajes

## 📧 Funcionalidad del Formulario

### Campos del Formulario

- Nombre completo (requerido)
- Email (requerido, validado)
- Teléfono (requerido, formato colombiano)
- Área legal de interés (select)
- Descripción del caso (requerido)
- Aceptación de políticas (checkbox requerido)
- reCAPTCHA (requerido)

### Flujo de Procesamiento

1. Validación del lado del cliente
2. Verificación reCAPTCHA
3. Sanitización de datos
4. Validación del lado del servidor
5. Backup en archivo CSV
6. Envío por email vía PHPMailer
7. Redirección a página de éxito/error

## 🐛 Resolución de Problemas

### Error: "Método no permitido"
- Verificar que el formulario use método POST
- Confirmar que el archivo PHP esté en la ruta correcta

### Error: "reCAPTCHA fallida"
- Verificar las claves de reCAPTCHA
- Confirmar que el dominio esté autorizado

### Emails no se envían
- Verificar configuración SMTP
- Revisar logs de errores en `/logs/php_errors.log`
- Confirmar que el servidor permita conexiones SMTP

### Problemas de permisos
```bash
chmod 755 logs/
chmod 644 *.php
chmod 644 *.html
```

## 📞 Soporte

Para soporte técnico o consultas sobre el desarrollo:

- **Email**: soporte@juridica.com
- **Teléfono**: +57 (1) 456-7890
- **Documentación**: Ver comentarios en el código fuente

## 📄 Licencia

Este proyecto está desarrollado exclusivamente para Jurídica & Asociados. Todos los derechos reservados.

---

*Desarrollado con ❤️ para Jurídica & Asociados - Defendemos sus derechos con excelencia y dedicación*
