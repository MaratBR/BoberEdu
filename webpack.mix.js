const mix = require('laravel-mix');

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


mix.webpackConfig(require('./webpack.config'));

mix.setResourceRoot('./resources')
mix.ts('resources/js/app.ts', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');
mix.version();

