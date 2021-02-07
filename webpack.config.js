const path = require("path");

module.exports = {
  entry: {
    sign: path.join(path.resolve(__dirname, "src", "script"), "sign.tsx"), // sign page
    student: path.join(path.resolve(__dirname, "src", "script"), "student.tsx"), // student page
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
              name: "[name].html",
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
    "react": "React",
    "react-dom": "ReactDOM",
    "react-router-dom": "ReactRouterDOM",
  },
  watch: true,
  watchOptions: {
    aggregateTimeout: 2000,
    poll: 1000,
  }
};
