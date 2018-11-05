<?php

function registrationForm_floatingRight_shortcode()
{
    ob_start();
		<div class="area gray register-right">

			<div class="topic-small">
				Kostenlos registrieren <br>
				und sofort ausprobieren.
			</div>


			<form id="registerForm" action="/de/registrierung" method="POST">

				<span class="text_bold">E-Mail:</span>
				<br>

				<input class="registerinput span4" type="text" value="" id="mail" name="mail">
				<br>

				<span class="text_bold">Passwort:</span>
				<br>

				<input class="registerinput span4" type="password" value="" id="password" name="password">
				<br>

				<span class="text_bold">Passwort wiederholen:</span>
				<br>

				<input class="registerinput span4" type="password" value="" id="password_reply" name="password_reply">
				<br>

				<input type="checkbox" value="1" name="terms" id="cbterms">
					Ich akzeptiere die <a target="_blank" href="/de/agb" style="text-decoration:underline;">AGBs</a>
				<br>

				<br>
				<button type="submit" id="submit" name="submit" value="" class="cta">Kostenlos registrieren</button>

				<input type="hidden" name="captcha" value="123456">

			</form>

			<span class="text_bold">Bei Fragen sind wir gerne für Sie da:</span>
			<br>
			<b>Kostenlose Hotline: 0800 778 776 77</b>

		</div>
    ?>

    <?php

    return ob_get_clean();
}

add_shortcode( 'all_features', 'registrationForm_floatingRight_shortcode' );