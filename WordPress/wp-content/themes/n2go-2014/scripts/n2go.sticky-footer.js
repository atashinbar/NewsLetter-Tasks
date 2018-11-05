(function ( $ )
{
	$(window).on( 'load.stickyFooter', function() {
		var $view = $( '[data-view=main]' );
		var $content = $view.find( '.n2go-content' );
		var $footer = $view.find( '.n2go-footer' );

		function updateFooterPosition() {
			if ( $( '[data-view=main]' ).hasClass( 'n2go-offCanvasView-isFixed' ) ) {
				return;
			}

			$footer.css({
				'margin-top': ''
			});

			var viewHeight = $view.height();
			var contentHeight = $content.outerHeight();
			var footerHeight = $footer.outerHeight();

			var marginTop = Math.max( 0, viewHeight - contentHeight - footerHeight );

			$footer.css({
				'margin-top': marginTop + 'px'
			});
		}

		updateFooterPosition();
		$(window ).on( 'resize.stickyFooter', updateFooterPosition );
	});

})( jQuery );