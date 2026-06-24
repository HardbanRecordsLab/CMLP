import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import path from 'path';
import {defineConfig} from 'vitest/config';

export default defineConfig(() => {
  return {
    root: path.resolve(__dirname, '..'),
    publicDir: path.resolve(__dirname, '../public'),
    plugins: [react(), tailwindcss()],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, '../src'),
      },
    },
    build: {
      outDir: path.resolve(__dirname, '../dist'),
      emptyOutDir: true,
      base: '/cmlp/',
    },
    test: {
      environment: 'node',
      include: ['tests/**/*.test.ts'],
      setupFiles: [path.resolve(__dirname, '../tests/setup.ts')],
    },
    server: {
      // HMR is disabled in AI Studio via DISABLE_HMR env var.
      // Do not modify-file watching is disabled to prevent flickering during agent edits.
      hmr: process.env.DISABLE_HMR !== 'true',
      // Disable file watching when DISABLE_HMR is true to save CPU during agent edits.
      watch: process.env.DISABLE_HMR === 'true' ? null : {},
    },
  };
});
