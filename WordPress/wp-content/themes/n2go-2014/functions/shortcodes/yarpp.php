<?php

function yarpp_shortcode( $atts, $content = "" ) {
	extract( shortcode_atts(
		array(
			'post_types' => 'post', // pool options: these determine the "pool" of entities which are considered, e.g. post, page, ...
			'past_only' => false, // show only posts which were published before the reference post
			'recent' => false, // to limit to entries published recently, set to something like '15 day', '20 week', or '12 month'.
			'limit' => 5, // maximum number of results
			'template' => 'yarpp-template-n2go-simple-list.php', // either the name of a file in your active theme or the boolean false to use the builtin template,
			'post_id' => false // the post ID. If not included, it will use the current post.
		),
		$atts,
		'yarpp'
	) );

	$more_link = '';
	if($post_types == 'help_topic') {
		$more_link = '<a class="n2go-relatedPosts-moreLink" href="' . get_field('mehr_hilfebeitraege_link', 'option') . '">' . get_field('mehr_hilfebeitraege_text', 'option') . '</a>';
	} else if ($post_types == 'post'){
		$more_link = '<a class="n2go-relatedPosts-moreLink" href="' . get_field('mehr_wissensbeitraege_link', 'option') . '">' . get_field('mehr_wissensbeitraege_text', 'option') . '</a>';
	}

	return yarpp_related(
		array(
			'post_type' => array_map( 'trim', explode( ',', $post_types ) ),
			'past_only' => $past_only,
			'recent' => $recent,
			'limit' => $limit,
			'template' => $template
		),
		$post_id,
		false
	) . $more_link;
}

add_shortcode( 'yarpp', 'yarpp_shortcode' );