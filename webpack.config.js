const path = require("path");

const UglifyJsPlugin = require("uglifyjs-webpack-plugin");

module.exports = (env, argv) => {
    const client = path.resolve(__dirname, "src", "client", "script");
    const production = argv.mode === "production";

    return {
        entry: {
            sign: path.join(client, "sign.tsx"), // sign page
            student: path.join(client, "student.tsx"), // student page
            teacher: path.join(client, "teacher.tsx"), // teacher page
        },
        output: {
            filename: "[name].js",
            path: path.resolve(__dirname, "www", "view"),
        },
        optimization: {
            minimize: production,
            minimizer: [
                new UglifyJsPlugin({
                    test: /\.js$/,
                }),
            ],
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
                                pretty: !production,
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
            react: "React",
            "react-dom": "ReactDOM",
            "react-router-dom": "ReactRouterDOM",
        },
        watchOptions: {
            aggregateTimeout: 2000,
            poll: 1000,
        },
    };
};
