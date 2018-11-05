(function ( $ )
{
	$(document).ready(function() {

		if ( !window.SwipeService ) {
			return;
		}

		var swipeService = window.SwipeService.getInstance();
		var allSliders = document.querySelectorAll( '.n2go-slider' );

		for ( var i = 0; i < allSliders.length; i++ ) {
			var slider = allSliders[i];

			var tabsWrapper = slider.querySelector( '.wpb_slider_wrapper' );
			var tabs = tabsWrapper.querySelectorAll( '.n2go-sliderTab' );

			if ( tabs.length < 2 ) {
				return;
			}

			var $tabsWrapper = $( tabsWrapper );

			var _validFlick;
			var inTransition = false;

			var currentIndex;
			var currentTab;
			var futureTab;

			var tabWidth;

			swipeService.bind( tabsWrapper, {
				start: function(coords, timing, event) {
					var $navigation = $( event.target ).closest( '.n2go-sliderNavigation' );

					if ( $navigation.length ) {
						return false;
					}

					if ( inTransition ) {
						return false;
					}

					_validFlick = true;

					$tabsWrapper.tabs( 'pause' );
					currentIndex = $tabsWrapper.tabs( 'option', 'active' );
					currentTab = tabs[ currentIndex ];
					tabWidth = currentTab.clientWidth;

					$( currentTab ).css({
						display: 'block',
						'z-index': 51
					});

					return true;
				},

				move: function( coords ) {
					if ( inTransition ) {
						return;
					}

					if ( !futureTab ) {
						if ( coords.relative.x > 0 ) {

							var prevIndex = currentIndex - 1;
							if ( prevIndex < 0 ) {
								prevIndex = tabs.length - 1;
							}

							futureTab = tabs[ prevIndex ];

						} else if ( coords.relative.x < 0 ) {

							var nextIndex = currentIndex + 1;
							if ( nextIndex > tabs.length - 1 ) {
								nextIndex = 0;
							}

							futureTab = tabs[ nextIndex ];
						}

						$( futureTab ).css({
							display: 'block',
							'z-index': 50
						});
					}

					var direction = coords.relative.x > 0 ? -1 : 1;

					TweenLite.set(currentTab, { css: { x: coords.relative.x }, '-webkit-user-select': 'none' });
					TweenLite.set(futureTab, { css: { x: coords.relative.x + ( direction * tabWidth ) }, '-webkit-user-select': 'none' });
				},

				end: function( coords, timing ) {

					if ( inTransition ) {
						return;
					}

					var direction = coords.relative.x > 0 ? -1 : 1;
					var updateIndex = false;

					if ( isValidFlick(coords, timing, _validFlick) || Math.abs( coords.relative.x ) > tabWidth / 2 ) {
						currentTabX = - direction * tabWidth;
						futureTabX = 0;
						updateIndex = true;
					} else {
						currentTabX = 0;
						futureTabX = direction * tabWidth;
					}

					inTransition = true;

					var timeline = new TimelineLite({
						onComplete: function() {
							if ( updateIndex ) {
								currentIndex += 1 * direction;
								currentIndex = currentIndex % tabs.length;

								if (currentIndex < 0) {
									currentIndex = tabs.length + currentIndex;
								}

								$tabsWrapper.tabs( 'option', 'active', currentIndex );
							}

							$tabsWrapper.tabs( 'rotate', parseInt( slider.getAttribute('data-interval') ) * 1000, true );
							inTransition = false;
						}
					});

					var tabsToAnimate = [ currentTab ];

					timeline.to( currentTab, 0.5, {
						css: {
							x: currentTabX
						}
					}, 0 );

					if ( futureTab ) {
						timeline.to( futureTab, 0.5, {
							css: {
								x: futureTabX
							}
						}, 0 );

						if ( !updateIndex ) {
							timeline.set( futureTab, {
								'display': 'none'
							}, 'final');
						}

						tabsToAnimate.push( futureTab );
					}

					timeline.set( tabsToAnimate, {
						clearProps: 'x,-webkit-user-select,z-index'
					}, 'final');

					futureTab = null;
				},

				cancel: function() {
					_validFlick = false;

					var tabsToAnimate = [ currentTab ];

					if ( futureTab ) {
						tabsToAnimate.push( futureTab );

						TweenLite.set( futureTab, {
							'display': 'none'
						});
					}

					TweenLite.set( tabsToAnimate, {
						clearProps: 'x,-webkit-user-select,z-index'
					});

					futureTab = null;

					$tabsWrapper.tabs( 'rotate', parseInt( slider.getAttribute('data-interval') ) * 1000, true );
				}
			}, [ 'touch' ] );
		}

		function isValidFlick( coords, timing, _validFlick ) {
			// The maximum vertical delta for a swipe should be less than 75px.
			var MAX_VERTICAL_DISTANCE = 75;
			// Vertical distance should not be more than a fraction of the horizontal distance.
			var MAX_VERTICAL_RATIO = 0.3;
			// At least a 30px lateral motion is necessary for a swipe.
			var MIN_HORIZONTAL_DISTANCE = 30;

			// Check that it's within the coordinates.
			// Absolute vertical distance must be within tolerances.
			// Horizontal distance, we take the current X - the starting X.
			// This is negative for leftward swipes and positive for rightward swipes.
			// After multiplying by the direction (-1 for left, +1 for right), legal swipes
			// (ie. same direction as the directive wants) will have a positive delta and
			// illegal ones a negative delta.
			// Therefore this delta must be positive, and larger than the minimum.
			if (!coords.relative.x || !timing.relative) return false;

			var deltaY = Math.abs( coords.relative.y );
			var deltaX = Math.abs( coords.relative.x );
			return _validFlick && // Short circuit for already-invalidated swipes.
				timing.relative < 300 &&
				deltaY < MAX_VERTICAL_DISTANCE &&
				deltaX > 0 &&
				deltaX > MIN_HORIZONTAL_DISTANCE &&
				deltaY / deltaX < MAX_VERTICAL_RATIO;
		}

	});

})( jQuery );