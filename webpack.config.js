const path = require("path");

module.exports = {
  entry: {
    signin: path.join(path.resolve(__dirname, "src", "script"), "signin.tsx"), // sign in page
  },
  output: {
    filename: "[name].js",
    path: path.resolve(__dirname, "www", "view"),
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
      {
        test: /\.(less)$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].css",
            },
          },
          {
            loader: "less-loader",
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: [".tsx", ".ts", ".js", ".pug", ".less"],
  },
  target: "web",
  externals: {
    React: "react",
  },
};
