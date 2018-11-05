window.OffCanvasService = (function () {

	var instance;

	function init() {
		var stack = [];
		var views = {};

		var isNodeList = function ( nodes ) {
			var stringRepr = Object.prototype.toString.call( nodes );

			return typeof nodes === 'object' &&
				/^\[object (HTMLCollection|NodeList|Object)\]$/.test( stringRepr ) &&
				nodes.hasOwnProperty( 'length' ) &&
				(nodes.length === 0 || (typeof nodes[ 0 ] === "object" && nodes[ 0 ].nodeType > 0));
		};

		var addClass = function ( elements, className ) {
			if ( !elements ) {
				return;
			}

			if ( isNodeList( elements ) ) {
				elements = Array.prototype.slice.call( elements ); // converts NodeList to Array
			} else {
				elements = [ elements ];
			}

			elements.forEach( function ( el ) {
				if ( el.classList )
					el.classList.add( className );
				else
					el.className += ' ' + className;
			} );
		};

		var removeClass = function ( elements, className ) {
			if ( !elements ) {
				return;
			}

			if ( isNodeList( elements ) ) {
				elements = Array.prototype.slice.call( elements ); // converts NodeList to Array
			} else {
				elements = [ elements ];
			}

			elements.forEach( function ( el ) {
				if ( el.classList )
					el.classList.remove( className );
				else if ( el.className )
					el.className = el.className.replace( new RegExp( '(^|\\b)' + className.split( ' ' ).join( '|' ) + '(\\b|$)', 'gi' ), ' ' );
			} );
		};

		return {
			config: {
				views:            {
					fixedState: {
						className: 'isFixed'
					}
				},
				fixedViewElement: {
					fixedState: {
						className: 'isFixed'
					}
				}
			},

			animatePush: function ( newStack, activeView, nextView, onCompleteCallback ) {
				// noop
			},

			animatePop: function ( newStack, activeView, nextView, onCompleteCallback ) {
				// noop
			},

			registerView: function ( identifier, element, fixedElements ) {
				views[ identifier ] = {
					allElements:   [ element ].concat( fixedElements || [] ),
					element:       element,
					fixedElements: fixedElements,
					identifier:    identifier,
					scrollLeft:    0,
					scrollTop:     0,
					tween:         null
				};
			},

			popView: function () {
				var service = this;

				var activeView = stack.length ? stack.pop() : null;
				var nextView = stack.length ? stack[ stack.length - 1 ] : null;

				activeView.scrollLeft = document.body.scrollLeft;
				activeView.scrollTop = document.body.scrollTop;

				addClass( activeView.element, this.config.views.fixedState.className );
				addClass( activeView.fixedElements, this.config.fixedViewElement.fixedState.className );

				this.animatePop( stack, activeView, nextView, function () {
					removeClass( nextView.element, service.config.views.fixedState.className );
					removeClass( nextView.fixedElements, service.config.fixedViewElement.fixedState.className );

					document.body.scrollLeft = nextView.scrollLeft;
					document.body.scrollTop = nextView.scrollTop;

					activeView.element.style.display = 'none';
				} );
			},

			pushView: function ( nextViewIdentifier ) {
				var activeView = stack.length ? stack[ stack.length - 1 ] : null;

				var nextView = views[ nextViewIdentifier ];

				if ( stack.indexOf( nextView ) > -1 ) {
					console.log( 'View is already on the stack' );
					return false;
				}

				if ( activeView ) {
					activeView.scrollLeft = document.body.scrollLeft;
					activeView.scrollTop = document.body.scrollTop;
				}

				if ( activeView ) {
					addClass( activeView.element, this.config.views.fixedState.className );
					addClass( activeView.fixedElements, this.config.fixedViewElement.fixedState.className );
				}

				nextView.element.style.display = 'block';

				removeClass( nextView.element, this.config.views.fixedState.className );
				removeClass( nextView.fixedElements, this.config.fixedViewElement.fixedState.className );

				if ( activeView ) {
					activeView.element.scrollLeft = activeView.scrollLeft;
					activeView.element.scrollTop = activeView.scrollTop;
				}

				stack.push( nextView );

				if ( stack.length > 1 ) {
					this.animatePush( stack, activeView, nextView, function () {
						document.body.scrollLeft = nextView.scrollLeft;
						document.body.scrollTop = nextView.scrollTop;
					} );
				}
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