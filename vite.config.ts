import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.tsx',
      ssr: 'resources/js/ssr.tsx',
      refresh: true,
    }),
    react(),
  ],
  ssr: {
    noExternal: ['@inertiajs/server'],
  },
  resolve: {
    alias: {
      src: path.resolve(__dirname, 'resources/js'),
    },
  },
})
