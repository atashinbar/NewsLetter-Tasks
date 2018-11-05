<?php

$facebook_text = _x( 'share', 'share button', 'n2go-theme' );

if ( function_exists( 'pssc_facebook' ) ) {
	$facebook_count = pssc_facebook();
	$facebook_text = sprintf( _nx( '%s share', '%s shares', $facebook_count, 'share button - with count', 'n2go-theme' ), $facebook_count );
}

$twitter_text = _x( 'tweet', 'share button', 'n2go-theme' );

/*if ( function_exists( 'pssc_twitter' ) ) {
	$twitter_count = pssc_twitter();
	$twitter_text = sprintf( _nx( '%s tweet', '%s tweets', $twitter_count, 'share button - with count', 'n2go-theme' ), $twitter_count );
}*/

$googleplus_text = _x( 'share', 'share button', 'n2go-theme' );

if ( function_exists( 'pssc_gplus' ) ) {
	$googleplus_count = pssc_gplus();
	$googleplus_text = sprintf( _nx( '%s share', '%s shares', $googleplus_count, 'share button - with count', 'n2go-theme' ), $googleplus_count );
}

$linkedin_text = _x( 'share', 'share button', 'n2go-theme' );

if ( function_exists( 'pssc_linkedin' ) ) {
	$linkedin_count = pssc_linkedin();
	$linkedin_text = sprintf( _nx( '%s share', '%s shares', $linkedin_count, 'share button - with count', 'n2go-theme' ), $linkedin_count );
}

$mail_text = _x( 'mail', 'share button', 'n2go-theme' );

?>


<a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $this->url ) ?>" class="n2go-shareButton n2go-shareButton-facebook">
	<div class="n2go-shareButton_icon">
		<div class="n2go-svg" data-svg-ref="facebook-letter"></div>
	</div>
	<div class="n2go-shareButton_text"><?php echo $facebook_text ?></div>
</a>

<a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode( $this->title ) ?>&url=<?php echo rawurlencode( $this->url ) ?>" class="n2go-shareButton n2go-shareButton-twitter">
	<div class="n2go-shareButton_icon">
		<div class="n2go-svg" data-svg-ref="twitter"></div>
	</div>
	<div class="n2go-shareButton_text"><?php echo $twitter_text ?></div>
</a>

<a href="https://plus.google.com/share?url=<?php echo rawurlencode( $this->url ) ?>" class="n2go-shareButton n2go-shareButton-googleplus">
	<div class="n2go-shareButton_icon">
		<div class="n2go-svg" data-svg-ref="google-plus"></div>
	</div>
	<div class="n2go-shareButton_text"><?php echo $googleplus_text ?></div>
</a>

<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( $this->url ) ?>&title=<?php echo rawurlencode( $this->title ) ?>&source=<?php echo rawurlencode( 'Newsletter2Go' ) ?>" class="n2go-shareButton n2go-shareButton-linkedin">
	<div class="n2go-shareButton_icon">
		<div class="n2go-svg" data-svg-ref="linkedin"></div>
	</div>
	<div class="n2go-shareButton_text"><?php echo $linkedin_text ?></div>
</a>

<a href="mailto:?subject=<?php echo rawurlencode( $this->title ) ?>&amp;body=<?php echo rawurlencode( $this->url ) ?>" class="n2go-shareButton">
	<div class="n2go-shareButton_icon">
		<div class="n2go-svg" data-svg-ref="mail"></div>
	</div>
	<div class="n2go-shareButton_text"><?php echo $mail_text ?></div>
</a>