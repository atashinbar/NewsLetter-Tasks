			<div class="n2go-footer">
				<div class="n2go-centeredContent n2go-centeredContent-page">
					<div class="n2go-grid" data-num-items-per-row="5">
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-1"><?php dynamic_sidebar( 'footer-title-1' ); ?><?php dynamic_sidebar( 'footer-section-1' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-2"><?php dynamic_sidebar( 'footer-title-2' ); ?><?php dynamic_sidebar( 'footer-section-2' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-3"><?php dynamic_sidebar( 'footer-title-3' ); ?><?php dynamic_sidebar( 'footer-section-3' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-4"><?php dynamic_sidebar( 'footer-title-4' ); ?><?php dynamic_sidebar( 'footer-section-4' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-5"><?php dynamic_sidebar( 'footer-title-5' ); ?><?php dynamic_sidebar( 'footer-section-5' ); ?></div>
							<div class="n2go-footer_socials">
								<a id="link-footer_facebook" target="_blank" href="<?php echo get_field( 'footer_facebook_url', 'option' ) ?>" class="n2go-footer_socialIcon">
									<div class="n2go-svg" data-svg-ref="facebook-round"></div>
								</a>

								<a id="link-footer_youtube" target="_blank" href="<?php echo get_field( 'footer_youtube_url', 'option' ) ?>" class="n2go-footer_socialIcon">
									<div class="n2go-svg" data-svg-ref="youtube-round"></div>
								</a>

								<a id="link-footer_twitter" target="_blank" href="<?php echo get_field( 'footer_twitter_url', 'option' ) ?>" class="n2go-footer_socialIcon">
									<div class="n2go-svg" data-svg-ref="twitter-round"></div>
								</a>

								<?php if ( get_field( 'footer_show_xing_link', 'option' ) && !empty( get_field( 'footer_xing_url', 'option' ) ) ) : ?>
								<a id="link-footer_xing" target="_blank" href="<?php echo get_field( 'footer_xing_url', 'option' ) ?>" class="n2go-footer_socialIcon">
									<div class="n2go-svg" data-svg-ref="xing-round"></div>
								</a>
								<?php endif; ?>

								<?php if ( !empty( get_field( 'footer_linkedin_url', 'option' ) ) ) : ?>
								<a id="link-footer_linkedin" target="_blank" href="<?php echo get_field( 'footer_linkedin_url', 'option' ) ?>" class="n2go-footer_socialIcon">
									<div class="n2go-svg" data-svg-ref="linkedin-round"></div>
								</a>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<hr>

					<div class="n2go-grid" data-num-items-per-row="4">
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-6"><?php dynamic_sidebar( 'footer-title-6-9' ); ?><?php dynamic_sidebar( 'footer-section-6' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-7"><?php dynamic_sidebar( 'footer-section-7' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-8"><?php dynamic_sidebar( 'footer-section-8' ); ?></div>
						</div>
						<div class="n2go-gridItem">
							<div class="n2go-footerSection footer-section-9"><?php dynamic_sidebar( 'footer-section-9' ); ?></div>
						</div>
					</div>
				</div>
			</div>

			<div class="n2go-footer_bottom">
				<div class="n2go-centeredContent n2go-centeredContent-page">
					<div class="n2go-footer_bottom_inner">
						<div class="n2go-footer_bottomLeft"><?php dynamic_sidebar( 'footer-bottom-left' ); ?></div>
						<div class="n2go-footer_bottomRight"><?php dynamic_sidebar( 'footer-bottom-right' ); ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>