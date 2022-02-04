
module.exports = (isDev) => ({
  filename: isDev ? '[name].css' : '[name]-[chunkhash:7].css',
  path: path.resolve(__dirname, '../../../dist/css/'),
  publicPath: '/'
});
