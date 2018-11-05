(function ( $ )
{

	$(document).ready( function() {

		jQuery(function ($) {
			$(document.body).off('click.preview', 'a')
		});

		var $call = jQuery('.n2go_page_tab_anchors');

		$call.each(function (index) {

			$tabs = jQuery(this).find('.ui-tabs-nav li');

			$tabs.find( 'a' ).click(function (e) {
				$tabs.removeClass( 'ui-state-active' );
				$(this).closest( 'li').addClass( 'ui-state-active' );
			});

		});
	});

})( jQuery );