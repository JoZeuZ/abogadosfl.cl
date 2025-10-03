import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';

export default defineConfig({
  site: 'https://www.abogadosfl.cl', // URL base del sitio
  integrations: [tailwind()],
  output: 'static',
  build: {
    assets: 'assets'
  }
});