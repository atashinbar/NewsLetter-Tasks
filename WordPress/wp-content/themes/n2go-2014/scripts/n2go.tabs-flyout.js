(function () {

	// http://youmightnotneedjquery.com/#ready
	function ready( fn ) {
		if ( document.readyState != 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function addClass( el, className ) {
		if (el.classList)
			el.classList.add(className);
		else
			el.className += ' ' + className;
	}

	function removeClass( el, className ) {
		if (el.classList)
			el.classList.remove(className);
		else
			el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
	}

	function hasClass( el, className ) {
		if (el.classList)
			return el.classList.contains(className);
		else
			return ( new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className) );
	}

	ready( function () {
		var i, k;
		var navigations = document.querySelectorAll( '.n2go-pageTabsNavigation' );

		for ( i = 0; i < navigations.length; ++i ) {
			var navigation = navigations[ i ];
			var flyouts = navigation.querySelectorAll( '.n2go-flyout' );

			for ( k = 0; k < flyouts.length; ++k ) {
				var flyout = flyouts[ k ];
				var tooltipToggle = flyout.parentNode;

				var onTooltipOpen = (function ( toggle, flyout ) {
					return function ( tooltip, tooltipToggle ) {
						var tooltip = flyout;

						if ( window.innerWidth < 860 ) {
							return tooltip;
						}

						addClass( tooltipToggle, 'ui-state-active' );

						var timeline = new TimelineLite();

						timeline
							.fromTo( tooltip, 0.3, {
								display: 'block',
								opacity: 0
							}, {
								opacity: 1
							} );

						return tooltip;
					};
				})( tooltipToggle, flyout );

				var onTooltipClose = (function ( toggle, flyout ) {
					return function ( tooltip, tooltipToggle ) {
						if ( window.innerWidth < 860 ) {
							return;
						}

						var timeline = new TimelineLite( {
							onComplete: function () {
								tooltip.style.display = '';
								tooltip.style.opacity = '';

								removeClass( tooltipToggle, 'ui-state-active' );
							}
						} );

						timeline
							.to( tooltip, 0.15, {
								opacity: 0
							} );

					};
				})( tooltipToggle, flyout );

				var tooltipService = TooltipService.getInstance();
				tooltipService.createTooltip( tooltipToggle, '<div></div>', onTooltipOpen, onTooltipClose );

				tooltipToggle.addEventListener( 'click', (function( tooltipToggle, flyout ) {
					return function( event ) {
                        if ( event.target !== this && event.target !== this.firstChild ) {
                            return;
                        }

						if ( window.innerWidth >= 860 ) {
							return;
						}

						event.preventDefault();

						var targetHeight;

						if ( hasClass( tooltipToggle, 'n2go-accordion-isOpen' ) ) {
							targetHeight = 0;
							removeClass( tooltipToggle, 'n2go-accordion-isOpen' );
						} else {
							flyout.style.height = 'auto';
							targetHeight = flyout.clientHeight + 'px';
							flyout.style.height = 0;
							addClass( tooltipToggle, 'n2go-accordion-isOpen' );
						}

						var timeline = new TimelineLite();

						timeline
							.to( flyout, 0.3, {
								height: targetHeight
							} );
					}
				})( tooltipToggle, flyout ) );
			}
		}


		window.addEventListener( 'resize', function() {
			var i, k;
			var navigations = document.querySelectorAll( '.n2go-pageTabsNavigation' );

			for ( i = 0; i < navigations.length; ++i ) {
				var navigation = navigations[ i ];
				var flyouts = navigation.querySelectorAll( '.n2go-flyout' );

				for ( k = 0; k < flyouts.length; ++k ) {
					var flyout = flyouts[ k ];
					var tooltipToggle = flyout.parentNode;

					if ( window.innerWidth >= 860 ) {
						removeClass( tooltipToggle, 'n2go-accordion-isOpen' );
						flyout.style.height = '';
					}
				}
			}
		} );
	} );

})();