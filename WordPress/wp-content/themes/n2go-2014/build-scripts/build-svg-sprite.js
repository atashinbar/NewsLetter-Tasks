var fs = require( 'fs' );
var glob = require( 'glob-all' );
var path = require( 'path' );
var SVGO = require( 'svgo' );
var SVGSprite = require( 'machinepack-svg-sprite' );
var preprocess = require( 'preprocess' );

var svgo = new SVGO();

var basePath = path.join( './', 'sources/svgs/' );

var templateFilepath = path.join( './', 'build-scripts/file-templates/smn.svg-sprite-service.js' );
var outputFilepath = path.join( './', 'scripts/n2go.svg-sprite-service.js' );

var files = glob.sync( [
	'**/*.svg'
], {
	cwd: basePath
} );

var svgs = [];

files.forEach( function ( file ) {
	var filepath = path.join( basePath, file );

	if ( fs.lstatSync( filepath ).isFile() ) {
		var fileContent = fs.readFileSync( filepath ).toString();

		svgo.optimize( fileContent, function ( result ) {

			var svg = {
				id:      path.basename( file, path.extname( file ) ),
				content: result.data
			};

			svgs.push( svg );

		} );
	}
} );

SVGSprite.createSprite( {
	svgs: svgs
} ).exec( {
	// An unexpected error occurred.
	error:   function () {
	},
	// OK.
	success: function ( result ) {
		preprocess.preprocessFileSync( templateFilepath, outputFilepath, { SPRITE_CONTENT: result.toString() } );
		console.log( "Regenerated " + outputFilepath );
	}
} );