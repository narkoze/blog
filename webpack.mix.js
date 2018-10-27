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

mix.js('resources/js/blog.js', 'public/js')

mix.sass('resources/sass/blog.scss', 'public/css', {
  includePaths: [
    'node_modules/bulma/sass',
    'node_modules/flag-icon-css/sass',
  ]
})

mix.inProduction() ? mix.version() : mix.browserSync({ proxy: 'localhost:8000' })
