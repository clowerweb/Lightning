const path = require("path");
const PurgecssPlugin = require("purgecss-webpack-plugin");
const glob = require("glob-all");
const FileManagerPlugin = require("filemanager-webpack-plugin");

modern = process.env.VUE_CLI_MODERN_MODE;
production = process.env.NODE_ENV === "production";

config = {
  protocol: "http",
  host: "localhost",
  port: 8080,
  watchDir: "App/Views",
};

module.exports = {
  runtimeCompiler: true,
  publicPath: process.env.NODE_ENV === 'production' ? '/' : `${config.protocol}://${config.host}:${config.port}`,
  outputDir: 'public/assets',
  filenameHashing: false,
  productionSourceMap: false,

  css: {
    sourceMap: true,
    extract: false,
  },

  devServer: {
    https: config.https,
    host: config.host,
    port: config.port,
    clientLogLevel: "info",
    headers: { "Access-Control-Allow-Origin": "*" },
    disableHostCheck: true,
    contentBase: path.join(__dirname, config.watchDir),
    watchContentBase: true,
  },

  configureWebpack: {
    plugins: [
      new PurgecssPlugin({
        paths: glob.sync([
          path.join(__dirname, "./src/**/*.scss"),
          path.join(__dirname, "./App/Views/**/*.twig"),
          path.join(__dirname, "./src/**/*.vue"),
          path.join(__dirname, "./src/**/*.js"),
        ]),
      }),
      new FileManagerPlugin({
        onEnd: {
          // Delete unnecessary files
          delete: [
            "./public/assets/assets",
            "./public/assets/.htaccess",
            "./public/assets/favicon.ico",
            "./public/assets/index.html",
            "./public/assets/index.php",
            "./public/assets/nginx-config.txt",
          ],
        },
      }),
    ],
  }
};
