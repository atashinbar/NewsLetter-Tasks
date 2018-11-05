(function ( $ ) {
	$( document ).ready( function () {
		var sidebarWidgetsDesktop = document.querySelector( '.n2go-blogSidebarWidgets-js-desktop' );
		var sidebarWidgetsMobile = document.querySelector( '.n2go-blogSidebarWidgets-js-mobile' );

		var isShowingWidgetsOnDesktop = true;

		window.addEventListener( 'resize', update );
		update();

		function update() {
			if ( !sidebarWidgetsDesktop || !sidebarWidgetsMobile ) {
				return;
			}

			if ( window.innerWidth <= 964 && isShowingWidgetsOnDesktop === false ||
				window.innerWidth > 964 && isShowingWidgetsOnDesktop === true ) {
				return;
			}

			if ( window.innerWidth <= 964 && isShowingWidgetsOnDesktop === true ) {
				while ( sidebarWidgetsDesktop.childNodes.length > 0 ) {
					sidebarWidgetsMobile.appendChild( sidebarWidgetsDesktop.childNodes[ 0 ] );
				}
			} else if ( window.innerWidth > 964 && isShowingWidgetsOnDesktop === false ) {
				while ( sidebarWidgetsMobile.childNodes.length > 0 ) {
					sidebarWidgetsDesktop.appendChild( sidebarWidgetsMobile.childNodes[ 0 ] );
				}
			}

			isShowingWidgetsOnDesktop = !isShowingWidgetsOnDesktop;
		}
	} );

})( jQuery );