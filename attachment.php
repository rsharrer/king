<?php
/**
 * The template for displaying image attachments
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage King
 * @since King 1.0
 */
get_header(); ?>
<?php ult_content_before(); ?>
<div id="primary" class="site-content">

	<?php ult_content_top(); ?>
	<div id="content" role="main">	

		<?php while ( have_posts() ) : the_post(); ?>

			<?php ult_entry_before(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<?php ult_entry_top(); ?>

					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<div class="entry-attachment">
							<div class="attachment">
								<?php $next_attachment_url = get_attachment_link(); ?>
								<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
									<?php echo wp_get_attachment_image( $post->ID, 'full' ); ?>
									<?php if ( ! empty( $post->post_excerpt ) ) : ?>
										<div class="entry-caption">
											<?php the_excerpt(); ?>
										</div>
									<?php endif; ?>
								</a>								
							</div><!-- .attachment -->
						</div><!-- .entry-attachment -->
						<div class="entry-description">
							<?php the_content(); ?>
						</div><!-- .entry-description -->
					</div><!-- .entry-content -->

				<?php ult_entry_bottom(); ?>
				</article><!-- #post -->

				<?php ult_entry_after(); ?>
			
			<?php comments_template( '', true ); ?>			

		<?php endwhile; // end of the loop. ?>
	
	</div><!-- #content -->
	<?php ult_content_bottom(); ?>	

</div><!-- #primary -->
<?php ult_content_after(); ?>
<?php get_footer(); ?>