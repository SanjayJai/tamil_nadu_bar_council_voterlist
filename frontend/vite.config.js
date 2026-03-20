import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({

    base: '/', // ✅ REQUIRED (subdomain)
  plugins: [react(), tailwindcss()],

  server: {
    proxy: {
      '/advocate': {
        target: 'https://barcouncil.selvalegal.com/',
        changeOrigin: true,
        secure: false,
      },
      '/api': {
        target: 'https://barcouncil.selvalegal.com/api/',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/api/, '/api'),
      },
      '/admin': {
        target: 'https://barcouncil.selvalegal.com/',
        changeOrigin: true,
        secure: false,
      },
    },
  },

  build: {
    chunkSizeWarningLimit: 1000, // increase limit (KB)

    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes('node_modules')) {
            return 'vendor'
          }
        },
      },
    },
  },
})
