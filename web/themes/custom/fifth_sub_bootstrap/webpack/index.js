const path = require('path');

module.exports = function (env) {
  env = env || {};

  const isDev = !!env.development;
  const isProd = !!env.production;

  const config = {
    mode: isProd ? 'production' : 'development',
    devtool: 'source-map',
    entry: require('./entries/index'),
    module: require('./module/index'),
    // plugins: require('./plugins')(isDev),
    // resolve: require('./resolve'),
    // optimization: require('./optimization'),
    output: require('./output/index')(isDev),
    resolve: {
      extensions: ['.ts', '.js'],
    },
    stats: 'errors-only',
  };

  // if (isDev) {
  //   config.devServer = require('./dev-server');
  // }

  return config;
};


// module.exports = (isDev) => ({
//   filename: isDev ? 'js/[name].js' : 'js/[name]-[chunkhash:7].js',
//   path: path.resolve(__dirname, '../../dist/static/'),
//   publicPath: '/'
// });
//
//
// module.exports = {
//   mode: 'development',
//   entry: {
//     theme: ['./node_modules/bootstrap/dist/js/bootstrap.min.js', './node_modules/@popperjs/core/dist/umd/popper.min.js', '../../contrib/bootstrap_barrio/js/barrio.js'],
//     custom: './src/js/index.js',
//   },
//   output: {
//     filename: '[name].js',
//     path: path.resolve(__dirname, 'dist/js')
//   },
//   module: {
//     rules: [{
//       test: /\.scss$/,
//       use: [{
//         loader: 'sass-loader',
//         options: {
//           minimize: false;
//         }
//       }]
//     }]
//   }
// }
//
