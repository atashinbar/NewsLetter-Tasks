<?php get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-blog n2go-content n2go-wissen">

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

							<h1 class="n2go-blogSidebarWidget_headline" style="margin-bottom: 0.5em;"><?php _ex( 'Email Marketing Blog', 'blog / knowledge base headline', 'n2go-theme' ); ?></h1>

							<form method="get" class="n2go-searchForm n2go-searchForm-blogSidebar" id="searchform" action="<?php bloginfo( 'home' ); ?>/" style="margin-bottom: 1em;">
								<input type="text" value="" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'common search input placeholder', 'n2go-theme' ); ?>">
								<button type="submit">
									<div class="n2go-svg" data-svg-ref="search-glass"></div>
								</button>
							</form>

							<ul class="n2go-blogSidebarWidgets n2go-blogSidebarWidgets-js-desktop">
								<?php dynamic_sidebar( 'sidebar-blog' ); ?>
							</ul>

						</div>
					</aside>
					<main class="n2go-blog_main">
						<form method="get" class="n2go-searchForm n2go-searchForm-blogSidebar n2go-searchForm-blogMobile" id="searchform" action="<?php bloginfo( 'home' ); ?>/" style="margin-bottom: 2em;">
							<input type="text" value="" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'common search input placeholder', 'n2go-theme' ); ?>">
							<button type="submit">
								<div class="n2go-svg" data-svg-ref="search-glass"></div>
							</button>
						</form>