jQuery(document).ready(function ($) {

	$('.ng-download-center').closest('.n2go-centeredContent.n2go-centeredContent-page').css({
		'max-width':'none',
		'padding' : '0',
	});

	function chunk_split(str, number) {
		var lenght,
			i,
			out = [];
		for (i = 0, lenght = str.length; i < lenght; i += number) {
			out.push(str.substr(i, number))
		}
		return out
	};

	var PluginsBox = $('.ng-plugins-sec'),
		EditionBox = $('.ng-editions-sec'),
		DownloadTag = $('.ng-download-link');

	// Set download link when page refreshed
	$.each(PluginsBox,function(){
		$(this).closest('.clearfix').find('.ng-download-link').attr('href', $(this).val());
	});

	
	// Set download link when plugins box changed
	PluginsBox.on('change', function () {
		$(this).closest('.clearfix').find('.ng-download-link').attr('href', $(this).val());
	});

	// changing the *system* (1st/left dropdown) should change the list of available *plugins* (2nd/right dropdown)
	$.each(EditionBox, function () {
		if ($(this).val() == 'All Versions') {
			var AllPluginsHtml = $(this).closest('.clearfix').find('.ng-plugins-sec').html();
		}
		$(this).on('change', function () {
			var $this = $(this),
				Edition = $this.val(),
				SysName = $this.closest('.ng-sys-information').find('.ng-sys-name').data('ng_sys_name');
			$.ajax({
				type: "post",
				url: download_center.url,   // variable defined above with an array for url and nonce 
				data: "action=GetPluginsWithAjax&nonce=" + download_center.nonce + "&SysName=" + SysName + "&Edition=" + Edition,  // Action variable defines the name of the php function which proceess ajax request based on the variable we have passed   
				success: function (count) {
					$this.closest('.clearfix').find('.ng-plugins-sec').find('optgroup').remove();
					$this.closest('.clearfix').find('.ng-plugins-sec').find('option').remove();

					if (Edition != 'All Versions') {
						var options = '';
						$.each($.parseJSON(count), function (key, value) {
							options += "<option value='" + value.url + "'>" + chunk_split(value.version, 1).join('.') + "</option>";
						});
						$this.closest('.clearfix').find('.ng-plugins-sec').html(options);
						$this.closest('.clearfix').find('.ng-download-link').attr('href', $this.closest('.clearfix').find('.ng-plugins-sec').val());
					} else {
						$this.closest('.clearfix').find('.ng-plugins-sec').html(AllPluginsHtml);
					}
				}
			});

		});
	});


})
