import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import path from 'path'

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
  base: '',
  resolve: {
    alias: {
      '@/': path.resolve(__dirname, '/resources/scripts'),
      '@/Hooks': path.resolve(__dirname, '/resources/scripts/hooks'),
      '@/Components': path.resolve(__dirname, '/resources/views/components'),
      '@/Layouts': path.resolve(__dirname, '/resources/views/layouts'),
    },
  },
})
