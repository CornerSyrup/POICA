const path = require("path");

module.exports = {
  entry: {
    apply: path.join(path.resolve(__dirname, "src", "script"), "apply.tsx"), // for apply dashboard
    singin: path.join(path.resolve(__dirname, "src", "script"), "signin.tsx"), // sign in page
  },
  output: {
    filename: "[name].bundle.js",
    path: path.resolve(__dirname, "www", "js"),
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
      {
        test: /\.(pug)$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].php",
            },
          },
          {
            loader: "extract-loader",
          },
          {
            loader: "html-loader",
          },
          {
            loader: "pug-html-loader",
            options: {
              pretty: true,
            },
          },
        ],
      },
    ],
  },
  target: "web",
  externals: ["react"],
};
