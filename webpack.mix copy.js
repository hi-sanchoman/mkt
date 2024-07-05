const path = require('path')
const mix = require('laravel-mix')
const cssImport = require('postcss-import')
const cssNesting = require('postcss-nesting')
require('laravel-mix-clean')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/js/app.js', 'public/js')
  .vue()
  .postCss('resources/css/app.css', 'public/css', [
    // prettier-ignore
    cssImport(),
    cssNesting(),
    require('tailwindcss'),
  ])
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      alias: {
        vue$: 'vue/dist/vue.runtime.esm.js',
        '@': path.resolve('resources/js'),
      },
      fallback: {
        // "path": require.resolve("path-browserify"),
        // "stream": require.resolve("stream-browserify"),
        // "http": require.resolve("stream-http"),
        // "crypto": require.resolve("crypto-browserify"),
        // "zlib": require.resolve("browserify-zlib")
        //   path: false,
        //   stream: false,
        //   http: false,
        // crypto: false,
        // zlib: false,
        //   fs: false
      },
    },
  })
  .version()
  .sourceMaps()

// mix.clean({
//   cleanOnceBeforeBuildPatterns: [
//     'js/*.map',
//     'css/*.map',
//   ]
// });

if (!mix.inProduction()) {
  mix.version()
  mix.webpackConfig({
    optimization: {
      minimize: true,
    },
  })
  mix
    .webpackConfig({
      devtool: 'source-map',
    })
    .sourceMaps()
} else {
  mix.clean({
    cleanOnceBeforeBuildPatterns: ['js/*.map', 'css/*.map'],
  })
  mix.version()
  mix.webpackConfig({
    optimization: {
      minimize: true,
    },
  })
}
