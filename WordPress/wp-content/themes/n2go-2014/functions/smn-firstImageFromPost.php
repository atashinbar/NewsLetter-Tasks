<?php

function firstImageSrcFromPost( $id, $size = 'thumbnail' )
{
	$files = get_children( 'post_parent=' . $id . '&post_type=attachment&post_mime_type=image&order=desc' );

	if ( $files )
	{
		$keys = array_reverse( array_keys( $files ) );

		$j = 0;
		$num = $keys[ $j ];

		return wp_get_attachment_image_src( $num, $size, true );
	}

	return false;
}