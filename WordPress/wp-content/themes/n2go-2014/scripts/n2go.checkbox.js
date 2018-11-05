(function ( $ ) {

	$( document ).ready( function () {

		$( '.n2go-checkbox-round input' ).checkboxReplacement( {
			template     : '<div class="n2go-checkbox n2go-checkbox-round"><div class="n2go-svg" data-svg-ref="checkmark"></div></div>',
			wrapperClass : [ 'n2go-checkbox', 'n2go-checkbox-round' ],
			checkedClass : 'n2go-checkbox-isChecked',
			disabledClass: 'n2go-checkbox-isDisabled',
			hasBeenInitialized: function() {
				var wrapper = $(this ).checkboxReplacement( 'getWrapper' );

				// TODO: refactor SVG Sprite Service
				if ( window.SVGSpriteService ) {
					var spriteService = SVGSpriteService.getInstance();

					window.SVGSpriteService.settings.svgElementSelector = '.n2go-svg';
					window.SVGSpriteService.settings.svgElementContentClassName = 'n2go-svg_content';

					spriteService.initSVGElements( wrapper[0] );
				}
			}
		} );

	} );

})( jQuery );