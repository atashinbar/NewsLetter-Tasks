(function( $ )
{
	'use strict';

	$(document).ready( function()
	{
		$( '.n2go-languageDropdown' ).n2goFormSelect({
			dropDownClass: 'n2go-select n2go-languageDropdown_select',
			namespaceClass: 'n2go-css n2go-languageDropdown_list',
			subTemplate: function( option )
			{
				return '<a href="' + $(option).val() + '"><span class="n2go-languageDropdown_flag" style="background-image:url(' + ($(option).attr( 'data-flag-url' ) || '') + ')"></span></a>';
			}
		});

		var $grids = $( '.n2go-featuresGrid' );

		function resizeFeatureGridColumns() {

			var numColumnsPerRow = 4;

			if ( window.innerWidth <= 999 ) {
				numColumnsPerRow = 2;
			}

			if ( window.innerWidth <= 479 ) {
				numColumnsPerRow = 1;
			}

			setTimeout( function() {
				$grids.each( function() {
					var $grid = $(this);
					var $columns = $grid.find( '.n2go-featuresGrid_column' );

					$columns.css({
						height: ''
					});

					var numRows = $columns.length / numColumnsPerRow;

					if ( numColumnsPerRow > 1 ) {
						var numItemsPerRow = $columns.length / numRows;

						for ( var row = 0; row < numRows; row++ ) {
							var maxHeight = 0;

							for ( var i = 0; i < numItemsPerRow; i++ ) {
								var columnHeight = $columns.eq( row * numItemsPerRow + i ).outerHeight( true );
								maxHeight = Math.max( maxHeight, columnHeight );
							}

							for ( var i = 0; i < numItemsPerRow; i++ ) {
								$columns.eq( row * numItemsPerRow + i ).css({
									height: maxHeight + 'px'
								});
							}
						}
					}
				});

			}, 200);
		}

		$(window).on( 'resize.featureGridColumns', resizeFeatureGridColumns );
		resizeFeatureGridColumns();

	});

})( jQuery );