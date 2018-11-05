window.SwipeService = (function () {

	var instance;

	function init() {

		// The total distance in any direction before we make the call on swipe vs. scroll.
		var MOVE_BUFFER_RADIUS = 10;

		var POINTER_EVENTS = {
			mouse: {
				start: 'mousedown',
				move:  'mousemove',
				end:   'mouseup'
			},
			touch: {
				start:  'touchstart',
				move:   'touchmove',
				end:    'touchend',
				cancel: 'touchcancel'
			}
		};

		function getCoordinates( event ) {
			var originalEvent = event.originalEvent || event;
			var touches = originalEvent.touches && originalEvent.touches.length ? originalEvent.touches : [ originalEvent ];
			var e = (originalEvent.changedTouches && originalEvent.changedTouches[ 0 ]) || touches[ 0 ];

			return {
				x: e.clientX,
				y: e.clientY
			};
		}

		function getEvents( pointerTypes, eventType ) {
			var res = [];

			pointerTypes.forEach( function ( pointerType ) {
				var eventName = POINTER_EVENTS[ pointerType ][ eventType ];
				if ( eventName ) {
					res.push( eventName );
				}
			} );

			return res.join( ' ' );
		}

		function addEventListenerForMultipleEvents( element, events, fn ) {
			var events = events.split( ' ' );

			for ( var i = 0, iLen = events.length; i < iLen; i++ ) {
				element.addEventListener( events[ i ], fn, false );
			}
		}

		return {
			bind: function ( element, eventHandlers, pointerTypes ) {
				// Absolute total movement, used to control swipe vs. scroll.
				var totalX, totalY;
				// Coordinates of the start position.
				var startCoords;
				// Last event's position.
				var lastPos;
				// Whether a swipe is active.
				var active = false;

				var startTime;

				pointerTypes = pointerTypes || [ 'mouse', 'touch' ];

				addEventListenerForMultipleEvents( element, getEvents( pointerTypes, 'start' ), function ( event ) {
					startCoords = getCoordinates( event );
					startTime = new Date().getTime();
					totalX = 0;
					totalY = 0;
					lastPos = startCoords;

					var coords = {
						absolute: startCoords,
						relative: {
							x: 0,
							y: 0
						}
					};

					var timing = {
						absolute: startTime,
						relative: 0
					};

					active = eventHandlers[ 'start' ] && eventHandlers[ 'start' ]( coords, timing, event );
				} );

				var events = getEvents( pointerTypes, 'cancel' );

				if ( events ) {
					addEventListenerForMultipleEvents( element, events, function ( event ) {
						active = false;
						eventHandlers[ 'cancel' ] && eventHandlers[ 'cancel' ]( event );
					} );
				}

				addEventListenerForMultipleEvents( element, getEvents( pointerTypes, 'move' ), function ( event ) {
					if ( !active ) return;

					// Android will send a touchcancel if it thinks we're starting to scroll.
					// So when the total distance (+ or - or both) exceeds 10px in either direction,
					// we either:
					// - On totalX > totalY, we send preventDefault() and treat this as a swipe.
					// - On totalY > totalX, we let the browser handle it as a scroll.

					if ( !startCoords ) return;
					var coords = getCoordinates( event );

					var relativeCoords = {
						x: coords.x - startCoords.x,
						y: coords.y - startCoords.y
					};

					totalX += Math.abs( relativeCoords.x );
					totalY += Math.abs( relativeCoords.y );

					lastPos = coords;

					if ( totalX < MOVE_BUFFER_RADIUS && totalY < MOVE_BUFFER_RADIUS ) {
						return;
					}

					// One of totalX or totalY has exceeded the buffer, so decide on swipe vs. scroll.
					if ( totalY > totalX ) {
						// Allow native scrolling to take over.
						active = false;
						eventHandlers[ 'cancel' ] && eventHandlers[ 'cancel' ]( event );
						return;
					} else {
						// Prevent the browser from scrolling.
						event.preventDefault();

						var eventCoords = {
							absolute: coords,
							relative: relativeCoords
						};

						var currentTime = new Date().getTime();

						var timing = {
							absolute: currentTime,
							relative: currentTime - startTime
						};

						eventHandlers[ 'move' ] && eventHandlers[ 'move' ]( eventCoords, timing, event );
					}
				} );

				addEventListenerForMultipleEvents( element, getEvents( pointerTypes, 'end' ), function ( event ) {
					if ( !active ) return;
					active = false;

					var coords = getCoordinates( event );

					var relativeCoords = {
						x: coords.x - startCoords.x,
						y: coords.y - startCoords.y
					};

					var eventCoords = {
						absolute: coords,
						relative: relativeCoords
					};

					var currentTime = new Date().getTime();

					var timing = {
						absolute: currentTime,
						relative: currentTime - startTime
					};

					eventHandlers[ 'end' ] && eventHandlers[ 'end' ]( eventCoords, timing, event );
				} );
			}
		};

	}

	return {

		getInstance: function () {
			if ( !instance ) {
				instance = init();
			}

			return instance;
		}

	};

})();