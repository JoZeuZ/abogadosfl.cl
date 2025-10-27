import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';
import { astroImageTools } from "astro-imagetools";

export default defineConfig({
  site: 'https://www.abogadosfl.cl', // URL base del sitio
  integrations: [tailwind(), astroImageTools],
  output: 'static',
  build: {
    assets: 'assets'
  }
});