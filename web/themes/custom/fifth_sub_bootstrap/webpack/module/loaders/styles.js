const path = require('path');
module.exports = {
  test: /\.s[ac]ss$/i,
  use: [
    "style-loader",
    "css-loader",
    {
      loader: "sass-loader",
      options: {
        additionalData: "$env: " + process.env.NODE_ENV + ";",
      },
    },
  ],
};

