import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/scripts/app.tsx',
      ssr: 'resources/scripts/ssr.tsx',
    }),
    react(),
  ],
  ssr: {
    noExternal: ['@inertiajs/server'],
  },
  resolve: {
    alias: {
      '@/': '/resources/scripts',
      '@/Components': '/resources/views/components',
      '@/Layouts': '/resources/views/layouts',
    },
  },
})
