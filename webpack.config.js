const path = require("path");

module.exports = {
  entry: {
    signin: path.join(path.resolve(__dirname, "src", "script"), "signin.tsx"),
  },
  output: {
    filename: "[name].bundle.js",
    path: path.resolve(__dirname, "www", "view", "js"),
  },
  module: {
    rules: [
      {
        test: /\.(ts|tsx)$/,
        use: [
          {
            loader: "ts-loader",
          },
        ],
      },
    ],
  },
  target: "web",
  externals: ["react"],
};
