/*!
 * Checkbox replacement based on jQuery UI Widget
 * Author: office@slicemenice.de
 * Licensed under the MIT license
 *
 *  Requires UI version 1.9+
 */

;(function ( $, window, document, undefined ) {

	$.widget( 'smn.checkboxReplacement', {

		options: {
			template     : '<div class="checkbox"></div>',
			wrapperClass : 'checkbox',
			checkedClass : 'checked',
			disabledClass: 'disabled'
		},

		_create: function () {
			var widget = this;

			widget._trigger( 'willBeInitialized' );

			var $parent = widget.element.parent();

			var wrapperClasses = widget.options.wrapperClass;
			if ( false === $.isArray( wrapperClasses ) ) {
				wrapperClasses = [ widget.options.wrapperClass ];
			}

			var parentHasAllWrapperClasses = true;

			$( wrapperClasses ).each( function( index, classname ) {
				parentHasAllWrapperClasses = $parent.hasClass( classname );

				if ( false === parentHasAllWrapperClasses ) {
					// break for-each
					return false;
				}
			});

			// only wrap the checkbox, if it is not already wrapped with an element containing the wrapper class(es)
			if ( false === parentHasAllWrapperClasses ) {
				widget.wrapper = $( widget.options.template );
				widget.wrapper.insertBefore( widget.element );
				widget.element.appendTo( widget.wrapper );
			} else {
				widget.wrapper = $parent;
			}

			widget._disabled = false;
			widget._checked = false;

			widget._registerEventListeners();

			var disabled = widget.element.attr( 'disabled' );

			if ( typeof disabled !== typeof undefined && disabled !== false ) {
				widget.disabled( true );
			}

			var checked = widget.element.attr( 'checked' );

			if ( typeof checked !== typeof undefined && checked !== false ) {
				widget.checked( true );
			}

			widget._trigger( 'hasBeenInitialized' );
		},

		getWrapper: function() {
			var widget = this;
			return widget.wrapper;
		},

		destroy: function () {
			widget._trigger( 'willBeDestroyed' );


			widget._trigger( 'hasBeenDestroyed' );
		},

		disabled: function ( state ) {
			var widget = this;

			// no value passed, act as a getter.
			if ( state === undefined ) {
				return widget._disabled;
			}

			var fn = ( state === true ) ? 'addClass' : 'removeClass';

			widget.wrapper[ fn ]( widget.options.disabledClass );
			widget._disabled = state;
		},

		checked: function ( state ) {
			var widget = this;

			// no value passed, act as a getter.
			if ( state === undefined ) {
				return widget._checked;
			}

			var fn = ( state === true ) ? 'addClass' : 'removeClass';

			widget.wrapper[ fn ]( widget.options.checkedClass );
			widget._checked = state;
		},

		// Respond to any changes the user makes to the option method
		_setOption: function ( key, value ) {
			switch ( key ) {
				default:
					this.options[ key ] = value;
					break;
			}

			this._super( '_setOption', key, value );
		},

		_registerEventListeners: function () {
			var widget = this;

			widget.element.on( 'change', widget._onChange.bind( widget ) );

			if ( widget.element.parents( 'label' ).length == 0 ) {
				widget.wrapper.on( 'click', widget._onClick.bind( widget ) );
			}
		},

		_onChange: function () {
			var widget = this;
			widget.checked( widget.element.prop( 'checked' ) );
		},

		_onClick: function ( event ) {
			var widget = this;
			var source = event.target || event.srcElement;

			if ( source !== widget.element[ 0 ] ) {
				widget.element.prop( 'checked', !widget.element.prop( 'checked' ) );
			}

			widget.checked( widget.element.prop( 'checked' ) );
		}
	} );

})( jQuery, window, document );