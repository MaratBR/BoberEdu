const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

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


mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[chunkhash].js',//replace with your path
    },
    plugins: [
        new BundleAnalyzerPlugin()
    ]
});


mix.ts('resources/js/app.ts', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');
mix.version();

