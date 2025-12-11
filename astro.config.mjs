import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';

// https://astro.build/config
export default defineConfig({
  site: 'https://www.abogadosfl.cl',
  integrations: [tailwind()],
  output: 'static',
  build: {
    assets: 'assets',
    inlineStylesheets: 'auto',
    format: 'file',
  },
  vite: {
    build: {
      cssCodeSplit: true,
      rollupOptions: {
        output: {
          entryFileNames: 'assets/[name].[hash].js',
          chunkFileNames: 'assets/[name].[hash].js',
          assetFileNames: 'assets/[name].[hash][extname]'
        }
      }
    },
    server: {
      host: true
    }
  },
  compressHTML: true,
  scopedStyleStrategy: 'class',
  trailingSlash: 'never'
});
