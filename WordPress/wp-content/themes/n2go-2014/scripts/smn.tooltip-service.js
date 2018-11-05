window.TooltipService = (function () {

	var instance;

	function init() {
		var allTooltips = [];

		return {
			createTooltip:    createTooltip,
			hideOpenTooltips: hideOpenTooltips
		};

		function createTooltip( tooltipToggle, tooltipHTML, onOpen, onClose, options ) {
			var tooltipElement;
			options = options || {};

			tooltipToggle.tooltipData = {};

			var tooltip = {
				close: closeTooltip,
				open:  openTooltip
			};

			function openTooltip() {
				hideOpenTooltips();

				var div = document.createElement( 'div' );
				div.insertAdjacentHTML( 'beforeend', tooltipHTML );

				var tooltipElement = div.children.length > 1 ? div.childNodes : div.firstChild;

				if ( onOpen ) {
					tooltipElement = onOpen( tooltipElement, tooltipToggle );
				}

				return tooltipElement;
			}

			function closeTooltip() {
				if ( tooltipElement && onClose ) {
					onClose( tooltipElement, tooltipToggle );
					tooltipElement = undefined;
				}

				var index = allTooltips.indexOf( tooltip );
				if ( index > -1 ) {
					allTooltips.splice( index, 1 );
				}
			}

			allTooltips.push( tooltip );

			if ( Modernizr.touch === false ) {
				switch ( options.trigger ) {
					case 'click':
						tooltipToggle.addEventListener( 'click', onMouseClick );
						tooltipToggle.addEventListener( 'mouseleave', onMouseLeave );
						break;

					default:
						tooltipToggle.addEventListener( 'mouseenter', onMouseEnter );
						tooltipToggle.addEventListener( 'mouseleave', onMouseLeave );
				}
			} else {
				tooltipToggle.addEventListener( 'click', onTouchClick );
			}


			function onMouseClick( event ) {
				event.stopPropagation();

				if ( !tooltipElement ) {
					tooltipElement = tooltip.open();
					tooltipElement.addEventListener( 'mouseleave', onMouseLeave );
				}
			}

			function onMouseEnter( event ) {
				event.stopPropagation();

				var leaveIntent = tooltipToggle.tooltipData.leaveIntent;
				if ( leaveIntent ) {
					clearTimeout( leaveIntent );
					tooltipToggle.tooltipData.leaveIntent = undefined;
				}

				tooltipToggle.tooltipData.hoverIntent = setTimeout( function () {
					if ( !tooltipElement ) {
						tooltipElement = tooltip.open();
						tooltipElement.addEventListener( 'mouseleave', onMouseLeave );
					}
				}, TooltipService.defaults.hoverIntentTimeout );
			}

			function onMouseLeave( event ) {
				event.stopPropagation();

				var hoverIntent = tooltipToggle.tooltipData.hoverIntent;
				if ( hoverIntent ) {
					clearTimeout( hoverIntent );
					tooltipToggle.tooltipData.hoverIntent = undefined;
				}

				tooltipToggle.tooltipData.leaveIntent = setTimeout( function () {
					var toElement = event.toElement || event.relatedTarget;
					if ( checkLeave( tooltipToggle, tooltipElement ? tooltipElement : null, toElement ) ) {
						tooltip.close();
					}
				}, TooltipService.defaults.hoverIntentTimeout );
			}

			function onTouchClick( event ) {
				event.stopPropagation();

				// Prevent link on first touch
				if ( event.target === this || event.target.parentNode === this ) {
					event.preventDefault();
				}

				tooltipElement = tooltip.open();

				// Hide dropdown on touch outside
				document.addEventListener( 'click', closeTooltip, true );

				function closeTooltip( event ) {
					if ( tooltipElement != event.target && isChild( tooltipElement, event.target ) === false ) {
						event.stopPropagation();
						tooltip.close();
						document.removeEventListener( 'click', closeTooltip, true );
					}
				}
			}

			return tooltip;
		}

		function hideOpenTooltips() {
			for ( var i = 0; i < allTooltips.length; i++ ) {
				var tooltip = allTooltips[ i ];
				tooltip.close();
			}

			allTooltips = [];
		}

		function checkLeave( element, tooltip, toElement ) {
			var result = true;

			if ( element == toElement ||
				isChild( element, toElement ) ) {
				result = false;
			}

			if ( tooltip ) {
				if ( tooltip == toElement ||
					isChild( tooltip, toElement ) ) {
					result = false;
				}
			}

			return result;
		}

		function isChild( parentElement, possibleChild ) {
			var allElements = parentElement.getElementsByTagName( '*' );
			var result = false;

			for ( var i = 0, max = allElements.length; i < max; i++ ) {
				var child = allElements[ i ];
				if ( child === possibleChild ) {
					result = true;
					break;
				}
			}

			return result;
		}
	}

	return {

		getInstance: function () {
			if ( !instance ) {
				instance = init();
			}

			return instance;
		},

		defaults: {
			hoverIntentTimeout: 250
		}

	};

})();