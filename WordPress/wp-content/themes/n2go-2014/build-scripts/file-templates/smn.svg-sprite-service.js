window.SVGSpriteService = (function () {

	var instance;

	var div = document.createElement( 'div' );
	div.insertAdjacentHTML( 'beforeend', '/* @echo SPRITE_CONTENT */' );

	var sprite = div.firstChild;

	function initializeSVGElement( svgElement, symbol ) {
		var svg = document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' );

		for ( var k = 0; k < symbol.childNodes.length; ++k ) {
			svg.appendChild( symbol.childNodes[ k ].cloneNode( true ) );
		}

		svg.setAttribute( 'viewBox', symbol.getAttribute( 'viewBox' ) );

		var svgContent = document.createElement( 'div' );

		if ( svgContent.classList ) {
			svgContent.classList.add( window.SVGSpriteService.settings.svgElementContentClassName );
		} else {
			svgContent.className += ' ' + window.SVGSpriteService.settings.svgElementContentClassName;
		}

		svgContent.appendChild( svg );
		svgElement.appendChild( svgContent );
	}

	function init() {
		return {
			initSVGElements: initSVGElements
		};

		function initSVGElements( root ) {
			if ( !root ) {
				root = document;
			}

			var i;
			var svgs = root.querySelectorAll( window.SVGSpriteService.settings.svgElementSelector );

			for ( i = 0; i < svgs.length; ++i ) {
				var svgElement = svgs[ i ];
				var svgIdentifier = svgElement.getAttribute( window.SVGSpriteService.settings.svgReferenceAttributeName );

				var symbolSource = sprite.querySelector( '#' + svgIdentifier );

				if ( symbolSource ) {
					initializeSVGElement( svgElement, symbolSource );
					svgElement.removeAttribute( window.SVGSpriteService.settings.svgReferenceAttributeName );
				}
			}
		}
	}

	return {

		getInstance: function () {
			if ( !instance ) {
				instance = init();
			}

			return instance;
		},

		settings: {
			svgElementSelector:         '.my-svg',
			svgReferenceAttributeName:  'data-svg-ref',
			svgElementContentClassName: 'my-svg_content'
		}

	};

})();