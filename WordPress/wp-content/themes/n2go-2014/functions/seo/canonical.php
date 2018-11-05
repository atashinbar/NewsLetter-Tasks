<?php

add_filter( 'wpseo_canonical', 'n2go_wpseo_canonical', 999, 1 );

function n2go_wpseo_canonical( $canonical ) {
	$canonical_base = get_field( 'n2go_canonicalBaseUrl', 'options' );

	if ( !empty( $canonical_base ) ) {
		$canonical = str_replace( preg_replace( '#^https?://#','', get_bloginfo( 'url' ) ), $canonical_base, $canonical );
	}

	return $canonical;
}