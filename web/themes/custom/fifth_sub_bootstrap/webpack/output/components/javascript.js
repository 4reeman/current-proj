
module.exports = function (isDev) {
  return {
    filename: isDev ? '[name].js' : '[name]-[chunkhash:7].js',
    path: path.resolve(__dirname, '../../../dist/js/'),
    publicPath: '/'
  }
};
