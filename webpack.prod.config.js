var webpack = require('webpack');
require("babel-polyfill");

var ExtractTextPlugin = require("extract-text-webpack-plugin");
var OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
var WebpackMd5Hash = require('webpack-md5-hash');
var AssetsPlugin = require('assets-webpack-plugin')
var assetsPluginInstance = new AssetsPlugin({filename: '/bin/assets.json'})
var CleanWebpackPlugin = require('clean-webpack-plugin');
var CompressionPlugin = require('compression-webpack-plugin');


var path = require('path');

module.exports = {
	// devtool: 'nosources-source-map',
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
				loader: "babel",
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
        new webpack.optimize.OccurenceOrderPlugin(),
        new webpack.DefinePlugin({
		      'process.env': {
		        	'NODE_ENV': JSON.stringify('production')
		      }
	    }),
	  	new webpack.optimize.DedupePlugin(),
	  	new webpack.optimize.UglifyJsPlugin({
	  		minimize: true,
	  		compress: {
        		drop_console: true,
        		unused: true,
            	dead_code: true,
            	warnings: false
  			},
  			output: {
  				beautify: false
  			}
  		}),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
		}),
  		new webpack.optimize.AggressiveMergingPlugin(),
	    new CompressionPlugin({   
	      asset: "[path].gz[query]",
	      algorithm: "gzip",
	      test: /\.js$/,
	      threshold: 10240,
	      minRatio: 0.8
	    }),
 		new OptimizeCssAssetsPlugin({cssProcessor: require('cssnano'), cssProcessorOptions: { discardComments: { removeAll: true } }}),
    	new WebpackMd5Hash(),
    	assetsPluginInstance,
    	new CleanWebpackPlugin(['bin'], {
		      verbose: true,
		      dry: false
		})
    ]
	
};
