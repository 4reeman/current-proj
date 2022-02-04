const path = require('path');

module.exports = {
  module: {
    alias: {
      Entries: path.resolve(__dirname, 'webpack/entries/index'),
    }
  }

}
