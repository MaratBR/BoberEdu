const path = require('path');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = {
    output: {
        chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
    },
    plugins: [
        new BundleAnalyzerPlugin()
    ],
    resolve: {
        modules: [
            path.resolve(__dirname, 'resources'),
            'node_modules'
        ],
        extensions: ['.js', '.json', '.vue', '.sass', '.scss', '.ts'],
        alias: {
            '@styles': path.resolve(__dirname, 'resources/sass'),
            '@': path.resolve(__dirname, 'resources/sass'),
            'vue$': 'vue/dist/vue.esm.js',
        }
    }
}
