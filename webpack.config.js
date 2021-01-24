const path = require("path");

module.exports = {
  entry: {
    signin: path.join(path.resolve(__dirname, "src", "script"), "signin.tsx"), // sign in page
    signup: path.join(path.resolve(__dirname, "src", "script"), "signup.tsx"), // sign up page
    setting: path.join(path.resolve(__dirname, "src", "script"), "setting.tsx"), // setting page
    landing: path.join(path.resolve(__dirname, "src", "page"), "landing.pug"), // landing page
    sign: path.join(path.resolve(__dirname, "src", "script"), "sign.tsx"), // sign page
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
    "react": "React",
    "react-dom": "ReactDOM",
    "react-router-dom": "ReactRouterDOM",
  },
  watch: true,
  watchOptions: {
    aggregateTimeout: 5000,
    poll: 2500
  }
};
