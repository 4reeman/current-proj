const path = require('path');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const devMode = process.env.NODE_ENV !== "production";

//Styles
const vendorStyle = path.resolve(__dirname, 'node_modules');
const themeStyle = path.resolve(__dirname, 'src/scss');

//Scripts
const vendorScript = path.resolve(__dirname, 'node_modules');
const parentThemeScript = path.resolve(__dirname, '../../contrib');
const themeScript = path.resolve(__dirname, 'src/scripts');


module.exports = {
  entry: {
    vendorStyle: [
      `${vendorStyle}/bootstrap/scss/bootstrap.scss`
    ],
    themeStyle: [
      `${themeStyle}/style.scss`,
      `${themeStyle}/custom/abstract/reset.scss`,
      `${themeStyle}/custom/general/ajax_preloader.scss`
    ],
    vendorScript: [
      `${vendorScript}/bootstrap/dist/js/bootstrap.js`,
      `${vendorScript}/@popperjs/core/dist/umd/popper.min.js`
    ],
    parentThemeScript: [
      `${parentThemeScript}/bootstrap_barrio/js/barrio.js`
    ],
    themeScript: [
      `${themeScript}/ajax-preloader/ajax-view-progress-bar.js`,
      `${themeScript}/header/exposed-form-items-animation.js`,
      `${themeScript}/header/sub-item-toggle.js`
    ],
  },
   output: {
    path: path.resolve(__dirname, './dist'),
    filename: './js/[name]/[name].bundle.js',
    assetModuleFilename: 'assets/[name][ext]',
    clean: true
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [["@babel/preset-env", { modules: "auto" }]]
          }
        },
      },
      {
        test: /\.(scss|css)$/,
        use: [
          devMode&&this.test ? "style-loader" : MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader'
        ]
      },
      {
        test: /\.(?:ico|gif|png|jpg|jpeg)$/i,
        exclude: [/node_modules/],
        type: 'asset/resource',
      },
      {
        test: /\.(woff(2)?|eot|ttf|otf|svg|)$/,
        // exclude: [/node_modules/],
        type: 'asset/inline',
      },
    ],
  },
  plugins: [].concat(devMode ? [new MiniCssExtractPlugin({ filename: './css/[name]/[name].css'})] : []),
};
