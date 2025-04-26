const path = require('path');
const which = require('which');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { VueLoaderPlugin } = require('vue-loader');
const WebpackShellPlugin = require('webpack-shell-plugin-next');

const publicPath = path.resolve(__dirname, './public');

module.exports = (context, argv) => {
  const devMode = argv.mode === 'development';

  process.env.NODE_ENV = devMode ? 'development' : 'production';

  const config = {
    mode: argv.mode,
    stats: devMode ? 'normal' : 'errors-only',
    context: path.resolve(__dirname, './resources/assets/'),
    entry: {
      admin: [
        './admin/js/app.js',
        './admin/scss/app.scss',
      ],
      client: [
        './client/js/app.js',
        './client/scss/app.scss',
      ],
      common: [
        './common/scss/app.scss',
      ],
    },
    output: {
      publicPath: '',
      path: publicPath,
      filename: devMode ? 'js/[name].js' : 'js/[name].[fullhash].js',
    },
    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: 'vue-loader',
          options: {
            loaders: {
              js: {
                loader: 'babel-loader',
                options: {
                  cacheDirectory: true,
                },
              },
            },
          },
        },
        {
          test: /\.js$/,
          loader: 'babel-loader',
          exclude: /node_modules/,
          options: {
            cacheDirectory: true,
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
              options: {
                esModule: false,
              },
            },
            {
              loader: 'css-loader',
              options: {
                url: false,
              },
            },
            'sass-loader',
          ],
        },
        {
          test: /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/,
          loader: 'file-loader',
          options: {
            name: '[path][name].[ext]',
          },
        },
      ],
    },
    resolve: {
      alias: {
        '@core': path.resolve(__dirname, 'resources/assets'),
        '@admin': path.resolve(__dirname, 'resources/assets/admin'),
        '@client': path.resolve(__dirname, 'resources/assets/client'),
        '@common': path.resolve(__dirname, 'resources/assets/common'),
        'vue$': 'vue/dist/vue.esm.js',
        ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
      },
      extensions: ['.js', '.vue', '.json', '.css'],
    },
    optimization: {
      minimizer: [
        new TerserPlugin({
          terserOptions: {
            format: {
              comments: false,
            },
          },
          extractComments: false,
          exclude: /\.min\.js$/,
        }),
        new CssMinimizerPlugin({
          minimizerOptions: {
            preset: [
              'default',
              {
                discardComments: { removeAll: true },
              },
            ],
          },
        }),
      ],
      splitChunks: {
        cacheGroups: {
          commons: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendor',
            chunks: 'all',
          },
        },
      },
    },
    plugins: [
      new CleanWebpackPlugin({
        cleanOnceBeforeBuildPatterns: [
          `${publicPath}/css`,
          `${publicPath}/fonts`,
          `${publicPath}/images`,
          `${publicPath}/js`,
          `${publicPath}/mix-manifest.json`,
        ],
      }),
      new CopyWebpackPlugin({
        patterns: [
          {
            from: 'client/images/**',
            to(file) {
              const stripCount = file.context.length + 1 + 'client/images/'.length;
              const path = file.absoluteFilename.substring(stripCount);

              return `images/client/${path}`;
            },
          },
          {
            from: 'client/fonts',
            to: `${publicPath}/fonts/`,
          },
          {
            from: 'admin/fonts',
            to: `${publicPath}/fonts/`,
          },
          {
            from: 'common/images',
            to: `${publicPath}/images/common/`,
          },
        ],
      }),
      new MiniCssExtractPlugin({
        filename: devMode ? 'css/[name].css' : 'css/[name].[fullhash].css',
        chunkFilename: 'css/[id].css',
      }),
      new VueLoaderPlugin(),
      new WebpackManifestPlugin({
        publicPath: '',
        fileName: 'mix-manifest.json',
        filter(file) {
          return /\.(js|css)$/.test(file.name);
        },
        map(file) {
          const extension = path.extname(file.name).replace(/^\./, '');

          file.name = `/${extension}/${file.name}`;
          file.path = `/${file.path}`;

          return file;
        },
      }),
    ],
  };

  if (!devMode) {
    config.plugins = config.plugins.concat([
      new webpack.LoaderOptionsPlugin({
        minimize: true,
      }),
    ]);
  }

  if (null !== which.sync('php', { nothrow: true })) {
    config.plugins.push(new WebpackShellPlugin({
      onBuildStart: {
        scripts: [
          'php artisan ziggy:generate ./resources/assets/common/js/generated/routes.js',
          'php artisan js-translations:extract -D ./resources/assets/common/js/generated/translations.js',
        ],
      },
    }));
  }

  return config;
};
