const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const devMode = process.env.NODE_ENV !== "production";

module.exports = {
  entry: {
    pulsesPlayerStyles: path.resolve(__dirname, './pulses-audio-player/styles.js'),
    pulsesPlayerScripts: path.resolve(__dirname, './pulses-audio-player/scripts.js')
  },
   output: {
    path: path.resolve(__dirname, './dist'),
    filename: '[name].bundle.js',
    assetModuleFilename: 'assets/[name][ext]'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        // exclude: /node_modules/,
        use: ['babel-loader'],
      },
      {
        test: /\.(scss|css)$/,
        // exclude: [/node_modules/],
        use: [
          devMode&&this.test ? "style-loader" : MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader'
        ],
      },
    ],
  },
  plugins: [].concat(devMode ? [new MiniCssExtractPlugin({ filename: '[name].css'})] : []),
};
