var webpack = require('webpack');
require("babel-polyfill");

var ExtractTextPlugin = require("extract-text-webpack-plugin");
var OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
var WebpackMd5Hash = require('webpack-md5-hash');
var AssetsPlugin = require('assets-webpack-plugin')
var assetsPluginInstance = new AssetsPlugin({filename: '/bin/assets.json'})
var CleanWebpackPlugin = require('clean-webpack-plugin');

var BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;


var path = require('path');



module.exports = { 
	devtool: 'eval',
	context : __dirname,
	entry: {
		app: 
		[	
			'./includes/css/style.css',
			'./includes/css/media.css',
			'./includes/less/style.less',
			'./includes/css/owl.carousel.min.css',
			'./includes/css/owl.theme.default.min.css'
		],
		//vendor: ['jquery', 'preact', 'preact-compat']
		vendor: [
			'jquery',
			'jquery-validation',  
			'owl.carousel', 
			'easy-autocomplete',
			'./includes/bootstrap/dist/js/bootstrap.min.js',
			'./includes/jquery/jquery.unveil.js'	
		],
		category: [			
			'babel-polyfill', 
			'./category.js',
		],
		product: [
			'./product.js',
			'bootstrap-tabcollapse'
		],
		defer: [
			'./includes/jquery/subscribe.js'
		]
	},


	output: {
		path: './bin',
		filename: '[name].[chunkhash].js',
		publicPath: '/bin/'
	},
	node: {
		__filename: true 
	},
	devServer: {
		contentBase : __dirname,
		inline: true,
		port: 3000
	},
	/*
  	resolve: {
    	alias: {
			react: 'preact-compat',
			'react-dom': 'preact-compat'
    	}
  	},
  	*/
	module: {
		loaders: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel',
				query: {
					presets: ['es2015', 'stage-0', 'react']

				}
			},
			{ 
				test: /\.css$/, 
				loader: ExtractTextPlugin.extract("style-loader", "css-loader!resolve-url")
			},
	      	{
		        test: /\.less$/,
		        loader: ExtractTextPlugin.extract("style-loader", "css-loader!less-loader")
	  		},
	      	{ 
		        test: /\.png$/, 
		        loader: "url-loader?limit=1024" 
	      	},
	      	{ 
		        test: /\.(jpe?g|gif)$/i, 
		        loader: "file-loader?limit=1024"
	      	},
	      	{
		        test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/, 
		        loader: 'url?limit=10000&mimetype=application/font-woff'
	      	},
	      	{
		        test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/, 
		        loader: 'url?limit=10000&mimetype=application/octet-stream'
	      	},
	      	{
		        test: /\.eot(\?v=\d+\.\d+\.\d+)?$/, 
		        loader: 'file'
	      	},
	      	{
		        test: /\.svg(\?v=\d+\.\d+\.\d+)?$/, 
		        loader: 'url?limit=1024&mimetype=image/svg+xml'
	      	},
	      	{ 	
	      		test: require.resolve("jquery"), 
	      		loader: "expose?$!expose?jQuery" 
	      	}
		]
	},
	    plugins: [
	    	new  webpack.optimize.CommonsChunkPlugin({
			  name: "vendor",

			  // filename: "vendor.js"
			  // (Give the chunk a different name)

			  minChunks: Infinity,
			  // (with more entries, this ensures that no other module
			  //  goes into the vendor chunk)
			}),
	        new ExtractTextPlugin( "[name].[chunkhash].css" ),
            new webpack.ProvidePlugin({
	            $: "jquery",
	            jQuery: "jquery"
        	}),
        	new webpack.optimize.DedupePlugin(),
        	new OptimizeCssAssetsPlugin({
        		cssProcessor: require('cssnano'), 
        		cssProcessorOptions: { 
        			discardComments: { 
        				removeAll: true 
        			}
        		}
    		}),
        	new WebpackMd5Hash(),
        	assetsPluginInstance,
        	
        	new CleanWebpackPlugin(['bin'], {
		      verbose: true,
		      dry: false
    		}),
    		new BundleAnalyzerPlugin({
				  // Can be `server`, `static` or `disabled`. 
				  // In `server` mode analyzer will start HTTP server to show bundle report. 
				  // In `static` mode single HTML file with bundle report will be generated. 
				  // In `disabled` mode you can use this plugin to just generate Webpack Stats JSON file by setting `generateStatsFile` to `true`. 
				  analyzerMode: 'static',
				  // Host that will be used in `server` mode to start HTTP server. 
				  analyzerHost: '127.0.0.1',
				  // Port that will be used in `server` mode to start HTTP server. 
				  analyzerPort: 8888,
				  // Path to bundle report file that will be generated in `static` mode. 
				  // Relative to bundles output directory. 
				  reportFilename: 'report.html',
				  // Automatically open report in default browser 
				  openAnalyzer: true,
				  // If `true`, Webpack Stats JSON file will be generated in bundles output directory 
				  generateStatsFile: false,
				  // Name of Webpack Stats JSON file that will be generated if `generateStatsFile` is `true`. 
				  // Relative to bundles output directory. 
				  statsFilename: 'stats.json',
				  // Options for `stats.toJson()` method. 
				  // For example you can exclude sources of your modules from stats file with `source: false` option. 
				  // See more options here: https://github.com/webpack/webpack/blob/webpack-1/lib/Stats.js#L21 
				  statsOptions: null,
				  // Log level. Can be 'info', 'warn', 'error' or 'silent'. 
				  logLevel: 'info'
			})
	    ]
	
};
