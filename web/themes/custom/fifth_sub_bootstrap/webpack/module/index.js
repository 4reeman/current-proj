const path = require('path');
module.exports = {
  module: {
    rules: [
      require('./loaders/bable'),
      require('./loaders/styles')
    ]
  }
}

