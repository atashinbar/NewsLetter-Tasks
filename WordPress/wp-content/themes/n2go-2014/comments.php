<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}


function n2go_comment( $comment, $args, $depth )
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] )
	{
		$tag = 'div';
		$add_below = 'comment';
	}
	else
	{
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo $tag ?> <?php comment_class( 'n2go-blogPostComment' ) ?> id="comment-<?php comment_ID() ?>">

		<?php if ( 'div' != $args['style'] ) : ?>
			<div id="div-comment-<?php comment_ID() ?>" class="n2go-blogPostComment_body">
		<?php endif; ?>

		<div class="n2go-blogPostComment_avatar">
			<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>

		<div class="n2go-blogPostComment_content">
			<div class="n2go-blogPostComment_author">

				<?php printf( __( '<cite class="n2go-blogPostComment_authorName">%s</cite> <span class="n2go-blogPostComment_authorSays">-</span>' ), get_comment_author_link() ); ?>

				<a id="link-blogPostComment_link-<?php comment_ID() ?>" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="nofollow" class="n2go-blogPostComment_link">
					<?php
					/* translators: 1: date, 2: time */
					printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() );
					?>
				</a>
				<?php edit_comment_link( __( '(Edit)' ), '  ', '' ); ?>
			</div>

			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="n2go-blogPostComment_awaitingModeration"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
				<br />
			<?php endif; ?>

			<?php comment_text(); ?>

			<div class="n2go-blogPostComment_replyWrapper">
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>

	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif; ?>

	<?php
}


?>

<div id="comments" class="n2go-blogPostComments">

	<?php if ( have_comments() ) : ?>

		<div class="n2go-blogPostComments_headline">
			<?php

			$numComments = get_comments_number();
			echo sprintf( _nx( '%1$s Comment', '%1$s Comments', $numComments, 'single post - comments section headline', 'n2go-theme' ), $numComments );

			?>
		</div>
		<hr>

		<ol class="n2go-blogPostComments_list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 34,
					'per_page'   => -1,
					'callback'   => 'n2go_comment'
				) );
			?>
		</ol>

		<?php if ( ! comments_open() ) : ?>
		<p class="n2go-blogPostComments_noComments"><?php _e( 'Comments are closed', 'single post - comments', 'n2go-theme' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(); ?>

</div>
