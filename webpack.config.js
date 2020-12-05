const path = require("path");

module.exports = {
  entry: {
    apply: path.join(path.resolve(__dirname, "src", "script"), "apply.tsx"), // for apply dashboard
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
    ],
  },
  target: "web",
  externals: ["react"],
};
