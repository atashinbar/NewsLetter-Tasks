;(function( $ )
{
	"use strict";

	$(document).ready( function()
	{
		var offset = 150;

		function smoothScrollTo( hash, offset, time ) {
			var $target = $( hash );

			if ( !$target.length ) {
				$target = $( "[name='" + hash.slice(1) + "']" );
			}

			if ($target.length)
			{
				var targetOffset = $target.offset().top - offset + "px";
				$("html,body").animate({ scrollTop: targetOffset }, time);
			}
		}

		// setup smooth page scroll
		$(document).on( "click", "a[href*=#]", function()
		{
			var href = $(this).attr( 'href' );
			if ( href.indexOf( '#tab-' ) != -1 || $(this).closest( '.n2go-pageTabsNavigation').length ) {
				offset = window.innerWidth < 860 ? 70 : 200;
			}

			if (location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "") && location.hostname === this.hostname)
			{
				smoothScrollTo( this.hash, offset, 600 );
				return false;
			}
		});

		$(window).load( function() {
			if ( location.hash.length ) {
				smoothScrollTo( location.hash, offset, 0 );
			}
		});
	});

})( jQuery );