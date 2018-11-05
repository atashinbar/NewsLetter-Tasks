(function ( $ )
{

	$(document).ready( function() {

		jQuery(function ($) {
			$(document.body).off('click.preview', 'a')
		});

		var $call = jQuery('.n2go-slider'),
			ver = jQuery.ui && jQuery.ui.version ? jQuery.ui.version.split('.') : '1.10',
			old_version = parseInt(ver[0]) == 1 && parseInt(ver[1]) < 9;
		// if($call.hasClass('ui-widget')) $call.tabs('destroy');
		$call.each(function (index) {
			var $tabs,
				interval = jQuery(this).attr("data-interval"),
				tabs_array = [];
			//

			$tabs = jQuery(this).find('.wpb_slider_wrapper').tabs({
				show:function (event, ui) {
					wpb_prepare_tab_content(event, ui);
				},
				beforeActivate: function(event, ui) {
					ui.newPanel.index() !== 1 && ui.newPanel.find('.vc_pie_chart:not(.vc_ready)');
				},
				create: function(event, ui) {
					ui.tab.find('.progress')
						.css({
							width: '0%'
						})
						.animate(
						{
							width: '100%'
						},
							interval * 1000,
						'linear'
					);
				},
				activate:function (event, ui) {
					wpb_prepare_tab_content(event, ui);

					ui.oldTab.find('.progress').stop( true, true ).css({
						width: '0%'
					});

					ui.newTab.find('.progress')
						.css({
							width: '0%'
						})
						.animate(
							{
								width: '100%'
							},
							interval * 1000,
							'linear'
						);
				}
			}).tabs('rotate', interval * 1000, true);

			jQuery(this).find('.wpb_tab').each(function () {
				tabs_array.push(this.id);
			});

			jQuery(this).find('.ui-tabs-nav a').click(function (e) {
				e.preventDefault();
				if (jQuery.inArray(jQuery(this).attr('href'), tabs_array)) {
					if (old_version) {
						$tabs.tabs("select", jQuery(this).attr('href'));
					} else {
						$tabs.tabs("option", "active", jQuery(jQuery(this).attr('href')).index() - 1);

						$(this).closest( 'li' ).find('.progress')
							.stop( true, true )
							.css({
								width: '0%'
							})
							.animate(
							{
								width: '100%'
							},
								interval * 1000,
							'linear'
						);

					}
					return false;
				}
			});

			jQuery(this).find('.n2go-navigationArrow').click(function (e) {
				e.preventDefault();

				var index = $tabs.tabs("option", "active"),
					length = $tabs.find('.n2go-sliderTab').length;

				if (jQuery(this).hasClass('n2go-navigationArrow-right')) {
					index = (index + 1) >= length ? 0 : index + 1;
				} else {
					index = index - 1 < 0 ? length - 1 : index - 1;
				}

				$tabs.tabs("option", "active", index);
			});

		});
	});

})( jQuery );