(function ( $ )
{

	$(document).ready( function() {

		jQuery(function ($) {
			$(document.body).off('click.preview', 'a')
		});

		var $call = jQuery('.n2go_page_tabs'),
			ver = jQuery.ui && jQuery.ui.version ? jQuery.ui.version.split('.') : '1.10',
			old_version = parseInt(ver[0]) == 1 && parseInt(ver[1]) < 9;
		// if($call.hasClass('ui-widget')) $call.tabs('destroy');
		$call.each(function (index) {
			var $tabs,
				interval = jQuery(this).attr("data-interval"),
				tabs_array = [];
			//
			$tabs = jQuery(this).find('.wpb_page_tabs_wrapper').tabs({
				show:function (event, ui) {
					wpb_prepare_tab_content(event, ui);
				},
				beforeActivate: function(event, ui) {
					ui.newPanel.index() !== 1 && ui.newPanel.find('.vc_pie_chart:not(.vc_ready)');
				},
				activate:function (event, ui) {
					wpb_prepare_tab_content(event, ui);
				}
			}).tabs('rotate', interval * 1000);

			jQuery(this).find('.wpb_tab').each(function () {
				tabs_array.push(this.id);
			});

			jQuery(this).find('.wpb_tabs_nav a').click(function (e) {
				e.preventDefault();
				if (jQuery.inArray(jQuery(this).attr('href'), tabs_array)) {
					if (old_version) {
						$tabs.tabs("select", jQuery(this).attr('href'));
					} else {
						$tabs.tabs("option", "active", jQuery(jQuery(this).attr('href')).index() - 1);
					}
					return false;
				}
			});

			jQuery(this).find('.wpb_prev_slide a, .wpb_next_slide a').click(function (e) {
				e.preventDefault();
				if (old_version) {
					var index = $tabs.tabs('option', 'selected');
					if (jQuery(this).parent().hasClass('wpb_next_slide')) {
						index++;
					}
					else {
						index--;
					}
					if (index < 0) {
						index = $tabs.tabs("length") - 1;
					}
					else if (index >= $tabs.tabs("length")) {
						index = 0;
					}
					$tabs.tabs("select", index);
				} else {
					var index = $tabs.tabs("option", "active"),
						length = $tabs.find('.wpb_tab').length;

					if (jQuery(this).parent().hasClass('wpb_next_slide')) {
						index = (index + 1) >= length ? 0 : index + 1;
					} else {
						index = index - 1 < 0 ? length - 1 : index - 1;
					}

					$tabs.tabs("option", "active", index);
				}

			});

		});
	});

})( jQuery );