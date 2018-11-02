const mix = require('laravel-mix')

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
mix.extend('i18n', function (webpackConfig, ...args) {
  webpackConfig.module.rules.forEach(module => {
    if (module.loader !== 'vue-loader') return
    module.options.loaders.i18n = '@kazupon/vue-i18n-loader'
  })
})

mix.i18n().js('resources/js/blog.js', 'public/js')

mix.sass('resources/sass/blog.scss', 'public/css', {
  includePaths: [
    'node_modules/bulma/sass',
    'node_modules/flag-icon-css/sass',
    'node_modules/@fortawesome/fontawesome-free/scss',
  ]
})

mix.inProduction() ? mix.version() : mix.browserSync({ proxy: 'localhost:8000' })
