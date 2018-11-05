(function( $ )
{
	'use strict';

	var isTouchSupported = !!('ontouchstart' in window);

	$.widget( 'ui.n2goFormSelect',
	{
		defaultElement: '<select />',
		transformedElement: false,


		// Default options.
		options:
		{
			dropDownClass: 'n2go-select',
			showFirstItemInDropdown: true,
			addDropdownToBody: true,
			namespaceClass: 'n2go-css',

			subTemplate: function( option )
			{
				return '<span>' + $(option).text() + '<span class="description">' + ($(option).attr( 'data-description' ) || '') + '</span></span>';
			},
			initValue: function() { return $(this).text(); },
			valueTemplate: function() { return $(this).text(); }
		},

		_create: function()
		{
			if ( 'true' === this.element.attr( 'data-showFirstItemInDropdown' ) )
			{
				this.options.showFirstItemInDropdown = true;
			}
			

			this._transform();
			this.updateView();
			
			var $lis = this.transformedElement.find( 'ul li:not(.group)' );
			$lis.on( 'click' + this.eventNamespace, $.proxy( this._selectNewValue, this ) );

			this.transformedElement.find( 'li:first').on( 'click' + this.eventNamespace, $.proxy( this.openDropdown, this ) );


			// Set data if we use addDropdownToBody option
			this.transformedElement.find( 'ul:first' ).data( 'n2go-select', this.transformedElement ).addClass( 'n2go-selectDropdown' );
			this.transformedElement.data( 'n2go-selectDropdown', this.transformedElement.find( 'ul:first' ) );

			if ( this.options.addDropdownToBody )
			{
				this.transformedElement.find( 'ul:first' ).appendTo( 'body' ).addClass( this.options.namespaceClass );
			}

			$('html').off( 'click.n2goSelect' ).on( 'click.n2goSelect', this.closeAllDropdowns );

			if ( isTouchSupported )
			{
				if ( false === this.options.showFirstItemInDropdown )
				{
					this.element.find( 'option:first' ).remove();
					this._onChangeMobile();
				}

				this.element
					.appendTo( this.transformedElement.find( 'li:first' ) )
					.show()
					.css(
					{
						opacity: 0,
						position: 'absolute',
						width: '100%',
						height: '100%',
						left: 0,
						top: 0
					});

				this.transformedElement.find( 'li:first' ).css(
				{
					position: 'relative'
				});

				this.element.on( 'blur' + this.eventNamespace, $.proxy( this._onChangeMobile, this ) );
			}
		},

		_transform: function()
		{
			// hide the select
			this.element.hide();


			var widget = this,
			    selectedIndex = 0,
			    selectedOption = this.element.find( 'option:first' );

			if ( this.element.find( 'option:selected' ).length )
			{
				selectedOption = this.element.find( 'option:selected' );
				selectedIndex = this.element.find( 'option' ).index( selectedOption );
			}

			var ul = '<ul class="' + this.options.dropDownClass + '"><li class="n2go-select_toggle">';
			//ul += '<span>' + this.options.initValue.call( selectedOption ) + '</span>';
			ul += this.options.subTemplate.call( this, selectedOption );

			ul += '<ul style="display: none;">';

			this.element.children().each( function( index )
			{
				if ( !index && !widget.options.showFirstItemInDropdown )
				{
					// Don't do anything when you don't wanna show the first element
				}
				else
				{
					ul += widget[ this.tagName.toLowerCase() === 'option' ? '_getLIOptionChild' : '_getLIOptgroupChildren' ].call( widget, this );
				}
			});

			ul += '</ul></li></ul>';

			if ( this.element.is( ':disabled' ) )
			{
				this.disable();
			}

			var $ul = $(ul);
			
			this.transformedElement = $ul;
			this.element.after( $ul );
		},

		_getLIOptionChild: function( option )
		{
			var classes = ( $(option).attr('class') || '' ) + ( $(option).is(':selected') ? ' selected' : '' );
			return '<li class="' + classes + '">' + this.options.subTemplate.call( this, $(option) ) + '</li>';
		},

		_getLIOptgroupChildren: function( group )
		{
			var widget = this,
				li = '<li class="group"><span>' + $(group).attr('label') + '</span><ul>';

			$(group).find( 'option' ).each( function()
			{
				li += widget._getLIOptionChild.call( widget, this);
			});

			li += '</ul></li>';

			return li;
		},

		_getLIIndex: function( $listItem )
		{
			var index = 0,
			    group = $listItem.closest('.group');

			if ( group.length )
			{
				index = $listItem.closest( '.n2go-selectDropdown' ).find( 'li' ).index( $listItem ) - group.prevAll( '.group' ).length - 1;
			}
			else
			{
				index = $listItem.parent().find( 'li' ).index( $listItem ) - $listItem.prevAll( '.group' ).length;
			}

			if (! this.options.showFirstItemInDropdown )
			{
				index += 1;
			}

			return index;
		},

		_selectNewValue: function( event )
		{
			var $listItem = $(event.currentTarget),
			    $dropdown = $listItem.closest( '.n2go-selectDropdown' ),
			    $ul = $dropdown.data( 'n2go-select' ),
			    select = this.element,
			    index = this._getLIIndex( $listItem ),
			    sel;

			select[0].selectedIndex = index;

			sel = select.find( 'option:selected' );

			select.find( 'option' ).prop( 'selected', false );
			sel.prop( 'selected', true );

			$ul
				.find( 'li:first' )
				.html( this.options.subTemplate.call( this, sel ) );
			
			// Set selected
			$dropdown.find( '.selected' ).removeClass( 'selected' );
			$listItem.addClass( 'selected' );

			this.closeAllDropdowns();

			// Trigger onchange
			select.trigger( 'change' );

			this.transformedElement.removeClass( 'focused' );

			// Update validator
			if ( $.fn.validate && select.closest( 'form' ).length )
			{
				select.valid();
			}
		},

		updateView: function()
		{
			if ( this.element.is( ':disabled' ) )
			{
				this.transformedElement.addClass( 'disabled' );
			}
			else
			{
				this.transformedElement.removeClass( 'disabled' );
			}
		},

		_setProperty: function( name, bool )
		{
			this.element.prop( name, bool ).change();
			this.updateView();
		},

		disable: function()
		{
			this._setProperty( 'disabled', true );
			return this._super();
		},

		enable: function()
		{
			this._setProperty( 'disabled', false );
			return this._super();
		},

		openDropdown: function( event )
		{
			var $dropdown = this.transformedElement.data( 'n2go-selectDropdown' ),
			    $toggle = this.transformedElement.find( 'li:first' );

			if ( this.options.disabled || isTouchSupported )
			{
				return false;
			}

			// Close on second click
			if ( $toggle.hasClass( 'open' ) )
			{
				this.closeAllDropdowns( event );
			}
			// Open on first click
			else
			{
				$toggle
					.css( { 'z-index': 1200 } )
					.addClass( 'open' );

				$dropdown.css( { 'z-index': 1200 } ).show();

				this.hideAllOtherDropdowns.call( this );
			}

			if ( this.options.addDropdownToBody )
			{
				var $li = $dropdown.find("li:first");
				var maxWidth = $li.outerWidth() * 3;
				$dropdown.css(
				{
					position: 'absolute',
					top: $toggle.offset().top + $toggle.outerHeight(),
					left: $toggle.offset().left - ( maxWidth / 2 ),
					minWidth: $li.outerWidth() + 'px',
					maxWidth: maxWidth + 'px'
				});
			}

			event.preventDefault();
			event.stopImmediatePropagation();
			return false;
		},

		hideAllOtherDropdowns: function()
		{
			$('.n2go-select').not( this.transformedElement ).each( function()
			{
				var $dropdown = $(this).data( 'n2go-selectDropdown' );

				$dropdown
					.hide()
					.css( 'z-index', 0 )
					.parent()
						.css( 'z-index', 0 )
						.removeClass( 'open' );
			});
		},

		closeAllDropdowns: function()
		{
			$('.n2go-select').each( function()
			{
				$(this).data('n2go-selectDropdown').hide();
				$(this).find('li:first').removeClass('open');
			}).removeClass('focused');
		},

		_onChangeMobile: function()
		{
			var _element = this.element.get( 0 );
			var sel = $( _element.options[ _element.selectedIndex || 0 ] );

			this.element.find( 'option' ).attr( 'selected', false );
			sel.attr( 'selected', true );

			this.transformedElement.find( 'span:first' ).html( this.options.valueTemplate.call( sel ) );
		}
	});

	return {};
})( jQuery );