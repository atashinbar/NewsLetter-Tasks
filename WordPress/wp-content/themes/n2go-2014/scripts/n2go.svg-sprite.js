(function () {

	document.ready( function () {
		if ( window.SVGSpriteService ) {
			var spriteService = SVGSpriteService.getInstance();

			window.SVGSpriteService.settings.svgElementSelector = '.n2go-svg';
			window.SVGSpriteService.settings.svgElementContentClassName = 'n2go-svg_content';

			spriteService.initSVGElements();
		}
	} );

})();