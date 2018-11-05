<?php

global $idCounter;
$idCounter = 0;

function n2go_add_ids_to_links_callback ($match) {
	global $idCounter;

	$tag = $match[0];
	$isButton = FALSE !== strpos( $tag, 'n2go-button' );
	$hasHref = FALSE !== strpos( $tag, 'href=' );

	if ( !$hasHref ) {
		return $tag;
	}

	$prefix = 'link-';

	if ( $isButton ) {
		$prefix = 'button-';
	}

	$replacement = str_replace( '<a', '<a id="' . $prefix . $idCounter . '"', $tag );
	$idCounter++;

	return $replacement;
}

function n2go_add_ids_to_links( $content ) {
	$pattern = "/<a [^>]*>/i";
	$content = preg_replace_callback( $pattern, 'n2go_add_ids_to_links_callback', $content );

	return $content;
}

add_filter( 'the_content', 'n2go_add_ids_to_links', 999 );