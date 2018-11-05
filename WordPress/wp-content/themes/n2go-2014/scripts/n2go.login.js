(function ($) {
	var apiUrl = 'https://api.newsletter2go.com/';
	var frontendUrl = 'https://ui.newsletter2go.com/index.php';
	function openPwDialog() {
		window.myprompt(
			'<div class="n2go-loginForm_headline">' + WordPress.LoginForm.messages.PasswordFormHeadline + '</div>' +
			'<p style="width: 400px; text-align: left">' + WordPress.LoginForm.messages.PasswordFormDescription + '</p><br />' +
			'<input type="text" name="email" id="email" style="width:100%" value="' + $("#mail").val() + '" placeholder="' + WordPress.LoginForm.messages.PasswordFormEmailInputLabel + '"><br />',
			{'verify': true, 'textYes': WordPress.LoginForm.messages.PasswordFormRequestNewPasswordButtonLabel, 'textNo': WordPress.LoginForm.messages.PasswordFormCancelButtonLabel, 'check': true},
			function () {
				successApi = false;
				requestLimitApi = false;
				errorApi = false;
				var email = $('#email').val();
				if (email != '') {
					$.post(apiUrl + 'users/resetpasswordrequest', JSON.stringify({email: email}),  function() {}, "json").always(function (data) {
						if (data.status == 200) {
							obj = data;
						} else {
							obj = data.responseJSON;
						}
						if (typeof(obj.code) !== 'undefined') {
							if (obj.code == 10030) {
								//$.jGrowl(WordPress.LoginForm.messages.PasswordFormRequestLimitMessage);
								requestLimitApi = true;
							} else {
								//$.jGrowl(WordPress.LoginForm.messages.PasswordFormErrorMessage);
								errorApi = true;
							}
						} else {
							//$.jGrowl(WordPress.LoginForm.messages.PasswordFormSuccessMessage);
							successApi = true;
						}

						$.post(WordPress.LoginForm.messages.PasswordFormActionUrl, {email: email}, function (data) {
							obj = window.eval(data);
							if (obj['error'] == 'false' || successApi) {
								$.jGrowl(WordPress.LoginForm.messages.PasswordFormSuccessMessage);
							}
							else {
								if (obj['error'].indexOf("509") == 0) {
									$.jGrowl(WordPress.LoginForm.messages.PasswordFormRequestLimitMessage);
								}
								else {
									if (!requestLimitApi) {
										$.jGrowl(WordPress.LoginForm.messages.PasswordFormErrorMessage);
									} else {
										$.jGrowl(WordPress.LoginForm.messages.PasswordFormRequestLimitMessage);
									}
								}
							}
							window.mykill();
						}, "json");
					});
				}
				else
					$.jGrowl(WordPress.LoginForm.messages.PasswordFormEmailRequiredMessage);
			}, function () {
			});
	}

	$(document).ready(function () {
		uiLogin = true;
		if ( typeof  WordPress.LoginForm === 'string' )
		{
			var WordPressLoginFormString = WordPress.LoginForm.replace(/&quot;/g, '"');
			WordPress.LoginForm = $.parseJSON(WordPressLoginFormString);
		}

		$('#loginForm').submit( function (event) {

			var username = $('#loginForm #mail').val();
			var password = $('#loginForm #password').val();

			if ($(this).data("prevent") != "1"){
				loginNewAppWithData(username, password, function (text) {
					var date = new Date();
					var dateString = date.toISOString();
					var language = WordPress.LoginForm.messages.PasswordFormActionUrl.replace("https://app.newsletter2go.com/", "").replace('/user/index/resetpasswordrequest', '');
					window.dataLayer.push({'event': 'gaTriggerEvent','gaEventCategory': 'LOGIN_PROCESS','gaEventAction': 'Login_OldAPP','gaEventLabel': language + "-" + dateString});
					jQuery('#loginForm .n2go-button').click();
				});
				$(this).data("prevent", "1");
				event.preventDefault();
				return false;
			}

		});



		$('.n2go-js-forgotPassword').click( function( event ) {
			openPwDialog();
			event.preventDefault();
		});

		var hasLoginFailed = ( ( location.search.split('login=')[1] || '' ).split('&')[0] ) === 'failed';
		if ( hasLoginFailed === true ) {
			$('.n2go-js-loginFailed').show();
		}

	});

	function loginNewAppWithData(username, password, errorCallback) {

		$.ajax({ method: 'POST',
			url: apiUrl + 'oauth/v2/token',
			data: {'username': username, 'password': password, 'grant_type':'https://nl2go.com/jwt'},
			beforeSend: function(xhr) {
				xhr.setRequestHeader("Authorization", "Basic " + btoa('sg7aa53n_ALsmBR_HH4Fc3_LfrZWGH5_9kU82fkhMvp3' + ":" + '1'));
			},
			success: function(text) {
				var accId = text.account_id;
				var token = text.access_token;
				var date = new Date();
				var dateString = date.toISOString();
				var language = WordPress.LoginForm.messages.PasswordFormActionUrl.replace("https://app.newsletter2go.com/", "").replace('/user/index/resetpasswordrequest', '');
				
				window.dataLayer.push({'event': 'gaTriggerEvent','gaEventCategory': 'LOGIN_PROCESS','gaEventAction': 'Login_NewAPP','gaEventLabel': language + "-" + dateString});
				var form = $('<form action="' + frontendUrl + '" method="post">' +
					'<input type="text" name="account_id" value="' + accId + '" />' +
					'<input type="text" name="token" value="' + token + '" />' +
					'<input type="text" name="username" value="' + username + '" />' +
					'</form>');
				$('body').append(form);
				form.submit();

				/*
				 response.data.account_id
				 localStorage.appUser = {username: 'something', apiToken: 'token', accountId: ''}
				 */
			},
			error: errorCallback
		
		});
	}

})(jQuery);