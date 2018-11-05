(function ( $ ) {

	var $teasers;

	function update() {
		$teasers.each( function ( index, element ) {
			var $element = $( element );

			$element.css( {
				height: ''
			} );

			$element.find( '.n2go-teaser_headline' ).each( function () {
				$( this ).dotdotdot( {
					ellipsis: '...'
				} );
			} );
		} );

		$teasers.each( function ( index, element ) {
			var $element = $( element );

			if (window.innerWidth > 579) {
				if (index % 2 === 0) {
					// compare height of this element and the next element
					$nextElement = $teasers.eq( index + 1 );

					if ($element.length && $nextElement.length) {
						var maxHeight = Math.max( $element.outerHeight(), $nextElement.outerHeight(), 0 );

						$element.outerHeight( maxHeight );
						$nextElement.outerHeight( maxHeight );
					}
				}
			}
		} );
	}

	$( window ).load( function () {
		$teasers = $( '.n2go-teaser' ).not( '.n2go-teaser-large' );

		$( window ).on( 'resize.teaser', update );
		update();
	} );

})( jQuery );