import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import autoprefixer from 'autoprefixer';
import * as path from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import fs from 'fs';
import initCfg from './app.config.js';

export default defineConfig(({ command, mode, ssrBuild }) => {
  const cfg = initCfg(command, mode, ssrBuild);

  const { host } = cfg;

  return {
    resolve: {
      alias: {
        '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
      },
    },

    server: {
      host,
      hmr: { host },
      https: {
        key: fs.readFileSync(`${cfg.certs}.key`),
        cert: fs.readFileSync(`${cfg.certs}.crt`),
      },
    },

    build: {
      emptyOutDir: true,
      outDir: '../dist',
      rollupOptions: {
        output: {
          entryFileNames: 'resources/assets/[name].js',
          chunkFileNames: 'resources/assets/[name]-[hash].js',
          assetFileNames: 'resources/assets/[name].[ext]',
        },
      },
    },
    // build: {
    //   emptyOutDir: true,
    //   outDir: '../dist',
    //   rollupOptions: {
    //     output: {
    //       entryFileNames: `[name].js`,
    //       chunkFileNames: `js/[name].js`,
    //       assetFileNames: (assetInfo) => {
    //         if (assetInfo.name.endsWith('.css')) {
    //           return '[name][extname]'
    //         } else if (
    //           assetInfo.name.match(/(\.(woff2?|eot|ttf|otf)|font\.svg)(\?.*)?$/)
    //         ) {
    //           return 'fonts/[name][extname]'
    //         } else if (assetInfo.name.match(/\.(jpg|png|svg)$/)) {
    //           return 'images/[name][extname]'
    //         }

    //         return 'js/[name][extname]'
    //       }
    //     }
    //   }
    // },

    plugins: [
      laravel({
        input: [
          'resources/image-editor-style.scss',
          'resources/image-editor.js',
          'resources/image-editor-admin.js',
        ],
        refresh: true,
      }),
    ],

    css: {
      preprocessorOptions: {
        scss: {
          additionalData: cfg.sassAdditionalData,
        },
      },
      postcss: {
        plugins: [autoprefixer],
      },
    },
  };
});
