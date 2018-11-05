<?php

function word_count( $string, $limit )
{
	$words = explode( ' ', $string );
	return implode( ' ', array_slice( $words, 0, $limit ) );
}