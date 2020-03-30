const Encore = require('@symfony/webpack-encore');
const path = require('path');
const getEzConfig = require('./ez.webpack.config.js');
const eZConfigManager = require('./ez.webpack.config.manager.js');
const eZConfig = getEzConfig(Encore);
const customConfigs = require('./ez.webpack.custom.configs.js');

Encore.reset();
Encore.setOutputPath('public/assets/build')
    .setPublicPath('/assets/build')
    .enableSassLoader()
    .enableReactPreset()
    .enableSingleRuntimeChunk();

eZConfigManager.add({
    eZConfig,
    entryName: 'ezplatform-admin-ui-layout-js',
    newItems: [
        path.resolve(__dirname, './web/assets/js/hotjar.js'),
    ],
});

Encore.addEntry('demo', [
    path.resolve(__dirname, './web/assets/scss/demo.scss'),
    path.resolve(__dirname, './web/assets/js/blocks/placesMapLoader.js'),
    path.resolve(__dirname, './web/assets/js/main.js'),
]);

const projectConfig = Encore.getWebpackConfig();
module.exports = [ eZConfig, ...customConfigs, projectConfig ];
