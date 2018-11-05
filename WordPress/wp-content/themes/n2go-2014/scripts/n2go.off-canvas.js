(function( $ )
{
	'use strict';

	$(document).ready( function() {

		var isShowingNavigation = false;
		var isShowingLanguageSelector = false;

		var navigationView = document.querySelector( '[data-view=navigation]' );
		var languageSelectorView = document.querySelector( '[data-view="language-selector"]' );
		var mainView = document.querySelector( '[data-view=main]' );
		var mainViewFixedElements = document.querySelectorAll( '[data-view-element],[data-rel-view=main]' );

		var offCanvasNavigationToggle = document.querySelector( '.n2go-offCanvasNavigationToggle' );
		var offCanvasLanguageSelectorToggle = document.querySelector( '.n2go-offCanvasLanguageSelectorToggle' );

		var offCanvasService = OffCanvasService.getInstance();

		offCanvasService.config.views.fixedState.className = 'n2go-offCanvasView-isFixed';
		offCanvasService.config.fixedViewElement.fixedState.className = 'n2go-offCanvasFixedViewElement-isFixed';

		offCanvasService.registerView( 'main', mainView, mainViewFixedElements );
		offCanvasService.registerView( 'navigation', navigationView );
		offCanvasService.registerView( 'languageSelector', languageSelectorView );

		var updateMinSize = function() {
			$( mainView ).add( navigationView ).add( languageSelectorView ).css({
				minHeight: window.innerHeight + 'px'
			} ).add( mainViewFixedElements ).css({
				minWidth: window.innerWidth + 'px'
			} );

			$( navigationView ).add( languageSelectorView ).css({
				minWidth: 270 + 'px',
				maxWidth: 270 + 'px'
			});

			$( languageSelectorView ).css({
				'margin-left': window.innerWidth - 270 + 'px'
			});
		};

		updateMinSize();
		$( window ).on( 'resize', function() {
			updateMinSize();

			if ( window.innerWidth > 1199 ) {
				if ( isShowingNavigation ) {
					offCanvasService.popView();
					isShowingNavigation = false;
				}

				if ( isShowingLanguageSelector ) {
					offCanvasService.popView();
					isShowingLanguageSelector = false;
				}
			}
		} );

		offCanvasService.pushView( 'main' );

		offCanvasService.animatePop = function( newStack, activeView, nextView, onCompleteCallback ) {
			var timeline = new TimelineLite({
				onComplete: onCompleteCallback
			});

			timeline
				.to( nextView.allElements, 0.5, {
					css: {
						x: 0
					}
				})
				.set( nextView.allElements, {
					clearProps:"x"
				}, "final");
		};

		offCanvasService.animatePush = function( newStack, activeView, nextView, onCompleteCallback ) {
			var timeline = new TimelineLite({
				onComplete: onCompleteCallback
			});

			var direction = 1;

			if ( nextView.identifier === 'languageSelector' ) {
				direction = -1;
			}

			timeline
				.to( activeView.allElements, 0.5, {
					css: {
						x: 270 * direction
					}
				}, "+=0.05");
		};

		var eventName = ('ontouchstart' in document.documentElement) ? 'touchstart' : 'click';

		$( mainView ).add( mainViewFixedElements ).on( eventName + '.offCanvasNavigation', function() {
			if ( isShowingNavigation || isShowingLanguageSelector ) {
				offCanvasService.popView();
				isShowingNavigation = isShowingLanguageSelector = false;

				return false;
			}

			return true;
		} );

		$( offCanvasNavigationToggle ).on( eventName + '.offCanvasNavigation', function() {
			if ( !isShowingNavigation ) {
				offCanvasService.pushView( 'navigation' );
				isShowingNavigation = true;
			}

			return false;
		} );

		$( offCanvasLanguageSelectorToggle ).on( eventName + '.offCanvasNavigation', function() {
			if ( !isShowingLanguageSelector ) {
				offCanvasService.pushView( 'languageSelector' );
				isShowingLanguageSelector = true;
			}

			return false;
		} );

	});

})( jQuery );