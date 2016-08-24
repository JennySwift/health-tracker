var webpack = require('webpack'),
    path = require('path');

module.exports = {
    //where our application source code lives
    // context: __dirname + "/app",
    //the first file that webpack should load (the same way way configure package.json with a main file)
    entry: {
        app: './resources/assets/js/app.js',
        //I think for this, angular needs to be installed with npm (npm install --save angular)
        // vendor: ['angular']
    },
    //where our bundle will go when webpack is done
    //this will put todo.bundle.js into /public/scripts
    output: {
        path: __dirname + "/public/build/js",
        filename: "bundle.js",
        //For source maps after I started using vue-loader
        devtoolModuleFilenameTemplate: function (info) {
            if (info.resource.match(/\.vue$/)) {
                $filename = info.allLoaders.match(/type=script/)
                    ? info.resourcePath : 'generated';
            }
            else {
                $filename = info.resourcePath;
            }
            return $filename;
        }
    },
    plugins: [
        // new webpack.optimize.CommonsChunkPlugin(/* chunkName= */"vendor", /* filename= */"vendor.bundle.js")
    ],
    devtool: 'eval-source-map',


    //For vue-loader
    module: {
        // `loaders` is an array of loaders to use.
        // here we are only configuring vue-loader
        loaders: [
            {
                test: /\.vue$/, // a regex for matching all files that end in `.vue`
                loader: 'vue'   // loader to use for matched files
            },
            {
                // use babel-loader for *.js files
                test: /\.js$/,
                loader: 'babel',
                // important: exclude files in node_modules
                // otherwise it's going to be really slow!
                exclude: /node_modules/
            }
        ]
    },
    babel: {
        presets: ['es2015']
    }
};