<?php get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content n2go-blog n2go-wissen">

		<?php

		$subnavigation = get_field( 'n2go_kb_subnavigation', 'option' );

		if ( $subnavigation ) {
			$content = n2go_tab_flyout_navigation_hierarchy( $subnavigation->slug, 'n2go_features_subnavigation_nav_menu_item_output', '<div class="n2go-pageTabsNavigation"><ul>', '</ul></div>', '<li>', '</li>' );
			echo do_shortcode( '[vc_row margin_bottom="0"][vc_column width="1/1"]' . $content . '[/vc_column][/vc_row]' );
		}

		?>

		<div class="vc_row wpb_row vc_row-fluid" style="margin-bottom: 0;">
			<div class="n2go-centeredContent n2go-centeredContent-page">
				<div class="vc_col-sm-12 wpb_column vc_column_container">
					<div style="margin: 1em 0;">
						<?php echo yoast_breadcrumb( '<div class="n2go-breadcrumbs">', '</div>', false ); ?>
					</div>

					<aside class="n2go-blog_sidebar">
						<div class="n2go-panel">

							<div class="n2go-blogSidebarWidget_headline" style="margin-bottom: 0.5em;">E-Mail Marketing Blog</div>

							<form method="get" class="n2go-searchForm" id="searchform" action="<?php bloginfo('home'); ?>/" style="margin-bottom: 1em;">
								<input type="text" value="" name="s" id="s" placeholder="<?php echo get_field( 'n2go_blog_searchInputPlaceholder', 'option' ) ?>">
								<button type="submit"><div class="n2go-svg" data-svg-ref="search-glass"></div></button>
							</form>

							<ul class="n2go-blogSidebarWidgets n2go-blogSidebarWidgets-js-desktop">
								<?php dynamic_sidebar( 'post-sidebar' ); ?>
							</ul>

						</div>

					</aside>
					<main class="n2go-blog_main n2go-blog_main-full">
						<form method="get" class="n2go-searchForm n2go-searchForm-blogMobile" id="searchform" action="<?php bloginfo('home'); ?>/" style="margin-bottom: 3em;">
							<input type="text" value="" name="s" id="s" placeholder="<?php echo get_field( 'n2go_blog_searchInputPlaceholder', 'option' ) ?>">
							<button type="submit"><div class="n2go-svg" data-svg-ref="search-glass"></div></button>
						</form>

						<?php
							if ( have_posts() ) :
								// Start the Loop.
								while ( have_posts() ) : the_post();
									?>
									<div class="n2go-blogPost n2go-panel">
										<div data-distribute-items-horizontally="true" style="margin: 0 0 1em;">
											<?php

											smk_get_template_part( 'partials/shareButtons.php', array(
												'title' => get_the_title(),
												'url' => get_the_permalink()
											));

											?>
										</div>
										<h1 class="n2go-blogPost_headline"><?php the_title() ?></h1>
										<div class="n2go-blogPost_date"><?php the_date() ?></div>
										<hr />
										<div class="n2go-blogPost_content">
											<?php echo apply_filters( 'the_content', get_the_content() ); ?>
											<div data-distribute-items-horizontally="true">
												<?php

												smk_get_template_part( 'partials/shareButtons.php', array(
													'title' => get_the_title(),
													'url' => get_the_permalink()
												));

												?>
											</div>
										</div>

										<?php if ( function_exists( 'related_posts' ) ) { related_posts(); } ?>
										<?php comments_template(); ?>
									</div>

									<?php

								endwhile;

							endif;
						?>

						<ul class="n2go-blogSidebarWidgets n2go-blogSidebarWidgets-mobile n2go-blogSidebarWidgets-js-mobile n2go-panel"></ul>
					</main>
				</div>
			</div>
		</div>

	</div>

	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script type="text/javascript">
		jQuery("#submit").click(function(e){
		        var data_2;
			    jQuery.ajax({
			                type: "POST",
			                url: "/wp-content/themes/n2go-2014/functions/google_captcha.php",
			                data: jQuery('#commentform').serialize(),
			                async:false,
			                success: function(data) {
			                 if(data.nocaptcha==="true") {
			               data_2=1;
			                  } else if(data.spam==="true") {
			               data_2=1;
			                  } else {
			               data_2=0;
			                  }
			                }
			            });
			            if(data_2!=0) {
			              e.preventDefault();
			              if(data_2==1) {
			                alert(<?php echo "'" . _x( 'Please check the captcha', 'comments captcha check', 'n2go-theme' ) . "'"; ?>);
			              } else {
			                alert(<?php echo "'" . _x( 'Please Do not spam', 'comments captcha spam', 'n2go-theme' ) . "'"; ?>);
			              }
			            } else {
			                jQuery("#commentform").submit
			           }
			  });
	</script>

<?php get_footer(); ?>