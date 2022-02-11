const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const devMode = process.env.NODE_ENV !== "production";

module.exports = {
  entry: {
    styles: path.resolve(__dirname, './src/styles'),
    scripts: path.resolve(__dirname, './src/scripts')
  },
   output: {
    path: path.resolve(__dirname, './dist'),
    filename: '[name].bundle.js',
    assetModuleFilename: 'assets/[name][ext]'
  },
  module: {
    rules: [
      // JavaScript
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
      // Images
      {
        test: /\.(?:ico|gif|png|jpg|jpeg)$/i,
        // exclude: [/node_modules/],
        type: 'asset/resource',
      },
      // Fonts and SVGs
      {
        test: /\.(woff(2)?|eot|ttf|otf|svg|)$/,
        // exclude: [/node_modules/],
        type: 'asset/inline',
      },
    ],
  },
  plugins: [].concat(devMode ? [new MiniCssExtractPlugin({ filename: '[name].css'})] : []),
};
