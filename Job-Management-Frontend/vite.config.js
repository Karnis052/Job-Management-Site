import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  // server:{
  //   port: 3000,
  //   proxy:{
  //     '/api':{
  //       target: 'http://localhost:5000',
  //       changeOrigin:true,
  //       rewrite: (path) => path.replace(/^\/api/, ''),
  //     },
  //   },
  // },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})


// "server": "json-server --watch src/jobs.json --port 5000"
//put in package.json script 
