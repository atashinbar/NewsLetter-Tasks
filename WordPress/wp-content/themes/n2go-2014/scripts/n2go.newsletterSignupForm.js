(function ( $ ) {
	var subscribeUrl = 'https://app.newsletter2go.com/de/recipients/index/subscribe/';

	$( document ).ready( function () {
		var $newsletterSignupForms = $( '.n2go-js-newsletterSignupForm' );

		if ( typeof  WordPress.NewsletterSignupForm === 'string' ) {
			var WordPressNewsletterSignupFormString = WordPress.NewsletterSignupForm.replace( /&quot;/g, '"' );
			WordPress.NewsletterSignupForm = $.parseJSON( WordPressNewsletterSignupFormString );
		}

		$newsletterSignupForms.each( function () {
			$signupForm = $( this );

			$signupForm.on( 'submit', function () {

				$form = $( this );

				// clear any responses / messages on submit
				$form.find( '.n2go-formResponse' ).remove();

				$.ajax( {
						type: 'GET',
						url : subscribeUrl,
						data: $form.serialize(),
						dataType: 'json'
					} )
					.done( function ( data ) {

						var $response = $( '<p class="n2go-formResponse"></p>' );
						var responseMessage = '';
						var responseType = 'error';

						switch ( data.success ) {
							case 0:

								switch ( data.errorcode ) {
									case 501:
										responseMessage = WordPress.NewsletterSignupForm.messages.AlreadySubscribedError;
										break;

									case 502:
										responseMessage = WordPress.NewsletterSignupForm.messages.EmailError;
										break;

									default:
										responseMessage = WordPress.NewsletterSignupForm.messages.ServerError;
								}

								break;

							case 1:
								responseMessage = WordPress.NewsletterSignupForm.messages.SuccessMessage;
								responseType = 'success';
								break;
						}

						$response
							.addClass( 'n2go-formResponse-' + responseType )
							.text( responseMessage )
							.appendTo( $form );
					} )
					.fail( function ( data ) {
						var $response = $( '<p class="n2go-formResponse n2go-formResponse-error"></p>' );
						$response.text( WordPress.NewsletterSignupForm.messages.ServerError );
						$form.append( $response );
					} );

				return false;
			} );

		} );
	} );

})( jQuery );