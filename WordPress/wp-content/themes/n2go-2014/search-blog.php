<?php

$args = array( 'post_type' => 'post' );
$args = array_merge( $args, $wp_query->query );
query_posts( $args );


get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content n2go-blog">

		<div class="n2go-centeredContent n2go-centeredContent-page">
			<aside class="n2go-blog_sidebar">

				<a id="link-blog_title" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="n2go-blog_title" style="margin-bottom: 1em;"><?php echo get_field( 'n2go_blog_title', 'option' ) ?></a>

				<form method="get" class="n2go-searchForm n2go-searchForm-blogSidebar" id="searchform" action="<?php bloginfo('home'); ?>/" style="margin-bottom: 3em;">
					<input type="hidden" name="search-type" value="blog">
					<input type="text" value="" name="s" id="s" placeholder="<?php echo get_field( 'n2go_blog_searchInputPlaceholder', 'option' ) ?>">
					<button type="submit">&nbsp;</button>
				</form>

				<ul class="n2go-blogSidebarWidgets n2go-blogSidebarWidgets-js-desktop">
					<?php dynamic_sidebar( 'sidebar-blog' ); ?>
				</ul>

			</aside>
			<main class="n2go-blog_main">
				<h1 class="n2go-blog_mobileTitle" style="margin-bottom: 1em;"><a id="link-blog_mobileTitle"href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><?php echo get_field( 'n2go_blog_title', 'option' ) ?></a></h1>

				<form method="get" class="n2go-searchForm n2go-searchForm-blogSidebar n2go-searchForm-blogMobile" id="searchform" action="<?php bloginfo('home'); ?>/" style="margin-bottom: 3em;">
					<input type="hidden" name="search-type" value="blog">
					<input type="text" value="" name="s" id="s" placeholder="<?php echo get_field( 'n2go_blog_searchInputPlaceholder', 'option' ) ?>">
					<button type="submit">&nbsp;</button>
				</form>

				<?php
					if ( have_posts() ) :

						global $wp_query;
						$total_results = $wp_query->found_posts;

						?><h2 class="n2go-blogPostTeaser_headline"><b><?php printf( get_field( 'n2go_blog_searchResultsFound', 'option' ), $total_results, get_search_query() ); ?></b></h2><?php

						// Start the Loop.
						while ( have_posts() ) : the_post();

							global $post;

							?>
							<a id="link-blogPostTeaser-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-blogPostTeaser">
								<h1 class="n2go-blogPostTeaser_headline"><?php the_title() ?></h1>
								<div class="n2go-blogPostTeaser_publishedOn"><?php echo sprintf( get_field( 'n2go_blog_publishedOn', 'option' ), get_the_date() ); ?></div>
								<?php

								$imageSize = 'blog-teaser';
								$imageOutput = false;

								if ( (function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) )
								{
									$imageOutput = get_the_post_thumbnail( $post->ID, $imageSize );
								}
								else
								{
									$firstImage = firstImageSrcFromPost( $post->ID, $imageSize );

									if ( $firstImage )
									{
										$imageOutput = '<img src="' . $firstImage[0] . '" width="' . $firstImage[1] . '" height="' . $firstImage[2] . '" />';
									}
								}

								if ( $imageOutput != false )
								{
									echo '<div class="n2go-blogPostTeaser_imageWrapper">' . $imageOutput . '</div>';
								}

								?>
								<div class="n2go-blogPostTeaser_text"><?php echo word_count( get_the_excerpt(), 30 ); ?>... </div>
								<div style="text-align: right; margin-top: 0.5em;">
									<span class="n2go-button n2go-button-small"><?php echo get_field( 'n2go_blog_readMore', 'option' ) ?></span>
								</div>
							</a>
							<?php
							//echo apply_filters( 'the_content', get_the_content() );

						endwhile;

					else:

						?>

						<h2 class="n2go-blogPostTeaser_headline" style="margin-bottom:5rem;"><b><?php printf( get_field( 'n2go_blog_searchNoResultsFound', 'option' ), get_search_query() ); ?></b></h2>

						<?php

					endif;

					smn_pagination();
				?>

				<ul class="n2go-blogSidebarWidgets n2go-blogSidebarWidgets-mobile n2go-blogSidebarWidgets-js-mobile"></ul>
			</main>
		</div>

	</div>

<?php get_footer(); ?>