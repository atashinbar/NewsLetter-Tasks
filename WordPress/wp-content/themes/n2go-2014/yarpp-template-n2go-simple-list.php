<?php
/*
YARPP Template: N2Go Simple List
*/

?>
<?php if ( have_posts() ) : ?>
	<div class="n2go-relatedPosts n2go-relatedPosts-list">
		<ol>
			<?php while ( have_posts() ) : the_post(); ?>
				<li>
					<?php
						$title = get_the_title();

						$pos = strpos($title, ' ', 40);

						if (strlen($title) > 65)
					    	$title = substr($title,0,$pos ) . ' [...]';
					?>
					<a id="link-relatedPost-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>"><?php echo $title; ?></a>
				</li>
			<?php endwhile; ?>
		</ol>
	</div>
<?php endif; ?>