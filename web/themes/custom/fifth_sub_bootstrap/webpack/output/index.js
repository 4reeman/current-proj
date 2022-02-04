const path = require('path');
module.exports = {
  module: {
    rules: [
      require('./components/javascript'),
      require('./components/styles')
    ]
  }
}
// з src тягнемо в  dist - кладемо
