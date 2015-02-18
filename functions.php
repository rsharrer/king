<?php
// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $site_width ) )
	$site_width = 625;
/**
 * Ultimate setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Ultimate supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Ultimate 1.0
 */
function ultimate_setup() {
	/*
	 * Makes Ultimate available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Ultimate, use a find and replace
	 * to change 'ultimate' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ultimate', get_template_directory() . '/languages' );
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'video', 'quote', 'status', 'gallery', 'audio') );
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menu( 'primary', __( 'Primary Menu', 'ultimate' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu', 'ultimate' ) );
	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );
	// Default RSS feed links
	add_theme_support( 'automatic-feed-links' );
	
	// Support post_thumbnail
	add_theme_support( 'the_post_thumbnail' );
	
	// Default custom header
	add_theme_support( 'custom-header' );
		
	// Woocommerce Support
	add_theme_support( 'woocommerce' );
	
	// Add support to <title> tag
	add_theme_support( "title-tag" );
	
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'ultimate_setup' );
/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since Ultimate 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function ultimate_get_font_url() {
	$font_url = '';
	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'ultimate' ) ) {
		$subsets = 'latin,latin-ext';
		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'ultimate' );
		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';
		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}
	return $font_url;
}
/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Ultimate 1.0
 */
function ultimate_scripts_styles() {
	global $wp_styles;
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_enqueue_style( 'ultimate-fonts', get_template_directory_uri().'/css/entypo.css');
	wp_enqueue_style( 'ultimate-fonts', get_template_directory_uri().'/css/font-awesome.css');
	// Loads our main stylesheet.
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'ultimate-bootstrap', get_template_directory_uri().'/css/bootstrap-grids.css');
		
	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'ultimate-ie', get_template_directory_uri() . '/css/ie.css', array( 'ultimate-style' ), '20121010' );
	
	wp_enqueue_script('jQuery');
	$wp_styles->add_data( 'ultimate-ie', 'conditional', 'lt IE 9' );
    wp_enqueue_script('jquery.bootstrap.min', get_template_directory_uri() . '/js/jquery.bootstrap.min.js', array('jquery'),'1.0.0',true);
    //wp_enqueue_script('jquery.bootstrap.min');
	
	// Load Masonry Javascript
	$masonry_blog_layout = get_theme_mod('blog_masonry_layout');
	$blog_layout = get_theme_mod('blog_layout');
	if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
		if ( $masonry_blog_layout ) :
			if ( is_home() || is_front_page() || is_archive() || is_search() ) :
				wp_enqueue_script('jquery-masonry');
				add_action('wp_footer', 'ultimate_masonry_blog');
			endif;
		endif;
	endif;

	// Slick SLider
	wp_enqueue_style( 'slick-slider', get_template_directory_uri().'/css/slick/slick.css');
	wp_register_script( 'slick-slider-script', get_template_directory_uri() . '/js/jquery.slick.min.js' );
    wp_enqueue_script( 'slick-slider-script' );

    // Justified Grid Gallery
    wp_enqueue_style( 'ultimate_justified_gallery', get_template_directory_uri().'/css/justifiedGallery.min.css');
    wp_register_script( 'ultimate_justified_gallery_script', get_template_directory_uri() . '/js/jquery.justifiedGallery.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'ultimate_justified_gallery_script' );

    // Smooth Scroll
	wp_register_script( 'smooth-scroll-script', get_template_directory_uri() . '/js/jquery.smoothScroll.min.js' );
	$smooth_scroll = get_theme_mod( 'smooth_scroll' );
   	if($smooth_scroll) {
   		wp_enqueue_script( 'smooth-scroll-script' );
	}

	// Lightbox - Colorbox
	wp_enqueue_style( 'ultimate_colorbox', get_template_directory_uri().'/css/colorbox/colorbox.css');
	wp_register_script( 'ultimate_colorbox_script', get_template_directory_uri() . '/js/jquery.colorbox.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'ultimate_colorbox_script' );
	
    wp_register_script('jquery.functions', get_template_directory_uri() . '/js/functions.js', array('jquery'),'1.0.0',true);
    wp_enqueue_script('jquery.functions');
	
}
add_action( 'wp_enqueue_scripts', 'ultimate_scripts_styles' );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses ultimate_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Ultimate 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function ultimate_mce_css( $mce_css ) {
	$font_url = ultimate_get_font_url();
	if ( empty( $font_url ) )
		return $mce_css;
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';
	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );
	return $mce_css;
}
add_filter( 'mce_css', 'ultimate_mce_css' );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Ultimate 1.0
 */
function ultimate_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'ultimate_page_menu_args' );




/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Ultimate 1.0
 */
function ultimate_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'ultimate' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 1', 'ultimate' ),
		'id' => 'sidebar-footer-1',
		'description' => __( 'Appears in footer sidebar widget area at first position.', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 2', 'ultimate' ),
		'id' => 'sidebar-footer-2',
		'description' => __( 'Appears in footer sidebar widget area at second position.', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 3', 'ultimate' ),
		'id' => 'sidebar-footer-3',
		'description' => __( 'Appears in footer sidebar widget area at third position.', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 4', 'ultimate' ),
		'id' => 'sidebar-footer-4',
		'description' => __( 'Appears in footer sidebar widget area at fourth position.', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Front Page Main Widget Area', 'ultimate' ),
		'id' => 'sidebar-front-main',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'ultimate' ),
		'id' => 'sidebar-front-1',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'ultimate' ),
		'id' => 'sidebar-front-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Third Front Page Widget Area', 'ultimate' ),
		'id' => 'sidebar-front-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'ultimate_widgets_init' );


if ( ! function_exists( 'ultimate_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own ultimate_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Ultimate 1.0
 */
function ultimate_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'ultimate' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'ultimate' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'ultimate' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'ultimate' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'ultimate' ); ?></p>
			<?php endif; ?>
			<div class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'ultimate' ), '<p class="edit-link">', '</p>' ); ?>
			</div><!-- .comment-content -->
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'ultimate' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;



if ( ! function_exists( 'ultimate_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own ultimate_entry_meta() to override in a child theme.
 *
 * @since Ultimate 1.0
 */
function ultimate_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ' ', 'ultimate' ) );
	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ' ', 'ultimate' ) );
	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'ultimate' ), get_the_author() ) ),
		get_the_author()
	);
	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'ultimate' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'ultimate' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'ultimate' );
	}
	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;




/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Ultimate 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function ultimate_body_class( $classes ) {

	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';

		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) :
		if ( empty( $background_color ) ) :
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) ) :
			$classes[] = 'custom-background-white';
		endif;
	endif;

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'ultimate-fonts', 'queue' ) ) :
		$classes[] = 'custom-font-enabled';
	endif;

	if ( ! is_multi_author() ) :
		$classes[] = 'single-author';
	endif;

	// Site Layout
	$site_layout = get_theme_mod('site_layout');
	if ( $site_layout ) :
		$classes[] = get_theme_mod('site_layout');
	endif;


	// Blog Layout
	$blog_layout = get_theme_mod('blog_layout');
	if ($blog_layout) :
		$classes[] = get_theme_mod('blog_layout');
	endif;

	if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
		$classes[] = 'blog-grid';
	endif;

	// Enable Masonry Layout
	$masonry_layout = get_theme_mod('blog_masonry_layout');
	if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
		if ($masonry_layout) :
			$classes[] = 'blog-masonry';
		endif;
	endif;

	// Is not singular
	if ( ! is_singular() ) :
		$classes[] = 'not-singular';
	endif;

	// Sidebar Position
	$sidebar_position = get_theme_mod('sidebar_position');
	if ($sidebar_position == 'right-sidebar') :
		$classes[] = 'right-sidebar';
	elseif ($sidebar_position == 'left-sidebar') :
		$classes[] = 'left-sidebar';
	elseif ($sidebar_position == 'no-sidebar') :
		$classes[] = 'no-sidebar';	
	endif;

	return $classes;
}
add_filter( 'body_class', 'ultimate_body_class' );


/**
 * Adjust content width in certain contexts.
 * site_width
 * Adjusts  value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Ultimate 1.0
 */
function ultimate_site_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $site_width;
		$site_width = 960;
	}
}
add_action( 'template_redirect', 'ultimate_site_width' );


/**
 * Include Javascript Snippet For Masonry Blog
 *
 * @since Ultimate 1.0
 */
function ultimate_masonry_blog() { 
?>
	<script type="text/javascript">
		(function($) {
			"use strict";
			function blog_masonry() {
				jQuery('.blog-masonry #content').imagesLoaded(function () {
					jQuery('.blog-masonry #content').masonry({
						columnWidth: '.post',
						itemSelector: '.post',
						transitionDuration: 0
					});
				});
			}
			$(document).ready(function() { blog_masonry(); });
			jQuery(window).load(function(){
				setTimeout(function(){
					jQuery('.blog-masonry #content').masonry('reload');
				},1000);
				
			});			
			//$(window).on('resize',function() { blog_masonry(); });
		})(jQuery);
	</script>
<?php
}


/**
 * Include Scroll To Top Feature
 *
 * @since Ultimate 1.0
 */
function ultimate_scroll_to_top() {
?>
	<script type="text/javascript">
		jQuery(function() {
		  jQuery('a[href*=#]:not([href=#])').click(function() {
		    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		      var target = jQuery(this.hash);
		      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
		      if (target.length) {
		        jQuery('html,body').animate({
		          scrollTop: target.offset().top
		        }, 1000);
		        return false;
		      }
		    }
		  });
		});
	</script>
	<a class="ult-scroll-top" href="#page"><span class="ent entarrow-up6"></span></a>
	<!--End Smooth Scroll-->
<?php
}
$scroll_to_top = get_theme_mod( 'scroll_to_top' );
if($scroll_to_top) {
	add_action('wp_footer', 'ultimate_scroll_to_top');
}

require_once('inc/customizer/customizer.php');
require_once('inc/customizer/customizer-style.php');

require_once('inc/hooks/ultimate-theme-hooks.php');

require_once('admin/meta.php');
require_once('admin/megamenu-admin-walker.php');

require_once('inc/ultimate-breadcrumbs.php');
require_once('inc/ultimate-menu-walker.php');
require_once('inc/ultimate-pagination.php');
require_once('inc/ultimate-post-meta.php');
require_once('inc/ultimate-post-gallery.php');
require_once('inc/ultimate-widget.php');




/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Ultimate 1.0
 */
function ultimate_customize_preview_js() {
	wp_enqueue_script( 'ultimate-customizer', get_template_directory_uri() . '/inc/customizer/assets/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}
add_action( 'customize_preview_init', 'ultimate_customize_preview_js' );

/**
 * Enqueue script for custom customize control.
 */
function custom_customize_enqueue() {
	echo '<style type="text/css">
			li#customize-control-favicon-img .thumbnail-image img {
				max-width: 18px;
				text-align: center;
				margin: 10px auto;
				display: block;
			}
		  </style>';
}
add_action( 'customize_controls_enqueue_scripts', 'custom_customize_enqueue' );






// Temporary

function wpt_register_css() {
   // wp_register_style( 'bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css' );
   // wp_enqueue_style( 'bootstrap.min' );

    wp_register_style( 'pratik.css', get_template_directory_uri() . '/css/pratik.css' );
    wp_enqueue_style( 'pratik.css' );

    wp_register_style( 'supriya.css', get_template_directory_uri() . '/css/supriya.css' );
    wp_enqueue_style( 'supriya.css' );
}
add_action( 'wp_enqueue_scripts', 'wpt_register_css' );



// Post Meta
if ( ! function_exists( 'ultimate_post_meta' ) ) :
	function ultimate_post_meta() {

		global $post;
		if (! $post)
			return false;
		ob_start();
		ob_end_clean();
		$html = '';

		if( get_theme_mod( 'blog_author_meta' )) :
			$html .= '<span class="post-meta-item">';
			$html .= __('By ','ultimate'); 
			$html .= '<span class="vcard author"><a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" title="Posts by '. get_the_author() .'" rel="author">'. get_the_author() .'</a></span>'; 
			$html .= '</span>'; // .post-meta-item
		endif;

		if( get_theme_mod( 'blog_date_meta' )) :
			$archive_year  = get_the_time('Y');
			$archive_month = get_the_time('m');
			$html .= '<span class="post-meta-item">';
			$html .= '<span class="post-meta-date"><a href="'. get_month_link( $archive_year, $archive_month ) .'">'. get_the_date('d M, Y') .'</a></span>';
			$html .= '</span>'; // .post-meta-item
		endif;

		if( get_theme_mod( 'blog_category_meta' )) :
			$categories_list = get_the_category_list( __( ' ', 'ultimate' ) );		
			if( $categories_list ) :
				$html .=  '<span class="post-meta-item">';
	        	$html .=  '<span class="post-meta-category">'. get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'ultimate' ) ) .'</span>';
	        	$html .=  '</span>'; // .post-meta-item
			endif;
		endif;

		if( get_theme_mod( 'blog_tag_meta' )) :
			$tag_list = get_the_tag_list( __( ' ', 'ultimate' ) );		
			if( $tag_list ) :
				$html .=  '<span class="post-meta-item">';
	        	$html .=  '<span class="post-meta-category">'. get_the_tag_list('',', ', '') .'</span>';
	        	$html .=  '</span>'; // .post-meta-item
			endif;
		endif;

		if( get_theme_mod( 'blog_comment_meta' )) :
			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : 
				$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
				if ( $num_comments == 0 ) {
					$comments = __('Leave a Comment', 'ultimate' );
				} elseif ( $num_comments > 1 ) {
					$comments = $num_comments . __(' Comments', 'ultimate' );
				} else {
					$comments = __('1 Comment', 'ultimate' );
				}
				$html .=  '<span class="post-meta-item">';
	            $html .=  '<span class="post-meta-comment"><a href="'. get_comments_link() .'" title="Comment on '. get_the_title() .'">'. $comments .'</a></span>'; 
	            $html .=  '</span>'; // .post-meta-item
	        endif;
		endif;		

		if( get_theme_mod( 'blog_link_meta' )) :
			if ( !is_single() ) :
				$html .=  '<span class="post-meta-item">';
				$html .=  '<span class="post-meta-link"><a href="'. get_the_permalink() .'" rel="bookmark">'.__('Read More...','ultimate') .'</a></span>';
				$html .=  '</span>'; // .post-meta-item
			endif;
		endif;
        
        if( is_user_logged_in() ):
        		$html .=  '<span class="post-meta-item">';
	            $html .=  '<span class="post-meta-edit"><a class="post-edit-link" href="'. get_edit_post_link() .'">'. __( 'Edit', 'ultimate' ) .'</a></span>';
	        	$html .=  '</span>'; // .post-meta-item
		endif;

		if ($html != '') :
			echo '<div class="entry-summary-meta">';
			echo '<div class="post-meta">';
			echo $html;
			echo '</div>';
			echo '</div>';
		endif;
	}
endif;


// Custom Excerpt Length
if ( ! function_exists( 'ultimate_excerpt_length' ) ) :
	function ultimate_excerpt_length( $length ) {
		$excerpt_length = get_theme_mod( 'post_excerpt_length' );
		return $excerpt_length;
	}
	add_filter( 'excerpt_length', 'ultimate_excerpt_length', 999 );
endif;


// Append Post Class
function ultimate_post_class( $classes ) {

	global $post;
	$blog_layout = get_theme_mod('blog_layout');

	if ( !is_singular() ) :	
		if ($blog_layout == 'grid-2') {
			$classes[] = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
		} else if ($blog_layout == 'grid-3') {
			$classes[] = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
		} else if ($blog_layout == 'grid-4') {
			$classes[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
		} else {
			$classes[] = '';
		}
	endif;

	return $classes;
}
add_filter( 'post_class', 'ultimate_post_class' );

// Add new image Size for Medium Image Blog
add_image_size( 'medium-image-blog', 330, 215, true ); // (cropped)
add_filter( 'image_size_names_choose', 'ultimate_image_sizes' );
function ultimate_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'medium-image-blog' => __( 'Medium Blog Image', 'ultimate' ),
    ) );
}



// Retrive video from post
if ( ! function_exists( 'ultimate_post_video' ) ) :
function ultimate_post_video() {

	global $post;
	if (! $post)
		return false;
	ob_start();
	ob_end_clean();

	$html = '';

	if ( preg_match('/\[(\[?)(video)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/', $post->post_content, $matches)) {
		$html .= do_shortcode($matches[0]);	
	}
	elseif ( preg_match('/<iframe.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches)) {
		$html .= '<iframe class="ultiamte-iframe" width="1280" height="720" src="';
		$html .= $matches[1];
		$html .= '" frameborder="0" allowfullscreen></iframe>';
	}
	elseif ( 
			preg_match('#https?://wordpress.tv/.*#i', $post->post_content, $matches) ||
			preg_match('#http://(www\.)?youtube\.com/watch.*#i', $post->post_content, $matches) ||
			preg_match('#https://(www\.)?youtube\.com/watch.*#i', $post->post_content, $matches) ||
			preg_match('#http://(www\.)?youtube\.com/playlist.*#i', $post->post_content, $matches) ||
			preg_match('#https://(www\.)?youtube\.com/playlist.*#i', $post->post_content, $matches) ||
			preg_match('#http://youtu\.be/.*#i', $post->post_content, $matches) ||
			preg_match('#https://youtu\.be/.*#i', $post->post_content, $matches) ||
			preg_match('#http://blip.tv/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(.+\.)?vimeo\.com/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.)?dailymotion\.com/.*#i', $post->post_content, $matches) ||
			preg_match('#http://dai.ly/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.)?funnyordie\.com/videos/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.)?hulu\.com/watch/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.|embed\.)?ted\.com/talks/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://vine.co/v/.*#i', $post->post_content, $matches) 
		) {
			$embedurl = $matches[0];
			if (!empty($embedurl)) {
			       $var = apply_filters('the_content', "[embed]" . $embedurl . "[/embed]");
			}
			$html .= '<div class="blog-oembed">';
			$html .= $var;
			$html .= '</div>';
	}
	else {
		return false;
	}
	return $html;
}
endif;



if ( ! function_exists( 'ultimate_post_audio' ) ) :
	function ultimate_post_audio() { // for audio post type - grab

		global $post;
		if (! $post)
			return false;
		ob_start();
		ob_end_clean();

		$html = '';

		if ( preg_match( '/<iframe.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ) ) {
			$html .= '<iframe class="ultiamte-audio-iframe" width="100%" height="350" src="';
			$html .= $matches[1];
			$html .= '" scrolling="no" frameborder="no"></iframe>';
		}
		elseif ( 
				preg_match('#https?://(www\.)?mixcloud\.com/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://(www\.)?rdio\.com/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://rd\.io/x/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://(www\.)?soundcloud\.com/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://(open|play)\.spotify\.com/.*#i', $post->post_content, $matches)
			) {
				$embedurl = $matches[0];
				if (!empty($embedurl)) {
				       $var = apply_filters('the_content', "[embed]" . $embedurl . "[/embed]");
				}
				$html .= '<div class="blog-oembed">';
				$html .= $var;
				$html .= '</div>';
		}
		else {
			return false;
		}
		return $html;
	}

endif;


if ( ! function_exists( 'ultimate_post_social' ) ) :
	function ultimate_post_social() { // for social media embeds

		global $post;
		if (! $post)
			return false;
		ob_start();
		ob_end_clean();

		$html = '';

		if ( preg_match('#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i', $post->post_content, $matches) ) {
				$embedurl = $matches[0];
				if (!empty($embedurl)) {
				       $var = apply_filters('the_content', "[embed]" . $embedurl . "[/embed]");
				}
				$html .= '<div class="blog-oembed">';
				$html .= $var;
				$html .= '</div>';
		}
		else {
			return false;
		}
		return $html;
	}

endif;


// Sidebar Position
$sidebar_pos = get_theme_mod('sidebar_position');
if ($sidebar_pos != 'no-sidebar') :
	add_action('ult_content_after','get_sidebar');
endif;

// Fevicom Image
if ( ! function_exists( 'ultimate_favicon' ) ) :
	function ultimate_favicon() {
		$favicom_image = get_theme_mod( 'favicon-img' );
		if ($favicom_image)
		echo '<link rel="icon" href="'. get_theme_mod( 'favicon-img' ) .'" type="image/x-png"/>';
	}
	add_action('ult_head_bottom', 'ultimate_favicon');
endif;

// Custom CSS
if ( ! function_exists( 'ultimate_custom_css' ) ) :
	function ultimate_custom_css() {
		$custom_css = get_theme_mod( 'custom_css' );
		if ($custom_css)
		echo '<style type="text/css">'. $custom_css .'</style>';
	}
	add_action('wp_head', 'ultimate_custom_css');
endif;

// Custom Script
if ( ! function_exists( 'ultimate_custom_script' ) ) :
	function ultimate_custom_script() {
		$custom_script = get_theme_mod( 'custom_script' );
		if ($custom_script)
		echo $custom_script;
	}
	add_action('wp_footer', 'ultimate_custom_script');
endif;

// Next / Previous post link on single page
if ( ! function_exists( 'ultimate_single_post_navigation' ) ) :
	function ultimate_single_post_navigation() { ?> 
		<?php if(is_single()) : ?>
			<nav class="nav-single clear">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'ultimate' ); ?></h3>
			<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ultimate' ) . '</span> %title' ); ?></span>
			<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ultimate' ) . '</span>' ); ?></span>
			</nav><!-- .nav-single -->
		<?php endif;
	}
	add_action('ult_entry_after', 'ultimate_single_post_navigation');
endif;

// Header Text on Archive Pages
if ( ! function_exists( 'ultimate_archive_header_text' ) ) :
	function ultimate_archive_header_text() { ?>
		<?php if(is_archive()) : ?>

			<?php if(is_date()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'ultimate' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'ultimate' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ultimate' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'ultimate' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ultimate' ) ) . '</span>' );
						else :
							_e( 'Archives', 'ultimate' );
						endif;
					?></h1>
				</header><!-- .archive-header -->

			<?php elseif(is_category()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'ultimate' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
					<?php if ( category_description() ) : // Show an optional category description ?>
						<div class="archive-meta"><?php echo category_description(); ?></div>
					<?php endif; ?>
				</header><!-- .archive-header -->

			<?php elseif(is_tag()) : ?>

					<header class="archive-header">
						<h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', 'ultimate' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
						<?php if ( tag_description() ) : // Show an optional tag description ?>
							<div class="archive-meta"><?php echo tag_description(); ?></div>
						<?php endif; ?>
					</header><!-- .archive-header -->

			<?php elseif(is_author()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( 'Author Archives: %s', 'ultimate' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
				</header><!-- .archive-header -->

				<?php
				// If a user has filled out their description, show a bio on their entries.
				if ( get_the_author_meta( 'description' ) ) : ?>
					<div class="author-info">
						<div class="author-avatar">
							<?php
								$author_bio_avatar_size = apply_filters( 'ultimate_author_bio_avatar_size', 68 );
								echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
							?>
						</div><!-- .author-avatar -->
						<div class="author-description">
							<h2><?php printf( __( 'About %s', 'ultimate' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
						</div><!-- .author-description	-->
					</div><!-- .author-info -->
				<?php endif; ?>

			<?php endif; ?>

		<?php endif;
	}	
	add_action('ult_content_top', 'ultimate_archive_header_text');
endif;

// Pagination Position 
if ( ! function_exists( 'ultimate_pagination_position' ) ) :
	function ultimate_pagination_position() { ?>
		<?php if(is_archive()) : ?>
			<?php ultimate_pagination(); ?>
		<?php endif;
	}	
	add_action('ult_content_bottom', 'ultimate_pagination_position');
endif;

// Header Layout
if ( ! function_exists( 'ultimate_header_layout' ) ) :
	function ultimate_header_layout() { 
		$header_layout = get_theme_mod('header_layout');
		if($header_layout == 'header_2'){
			get_header('style2');
		} 
		else if($header_layout == 'header_3'){
			get_header('style3');
		} 
		else {
			get_header('style1');
		}
	}	
	add_action('ult_header_bottom', 'ultimate_header_layout');
endif;

// Title & Breadcrumb Bar
if ( ! function_exists( 'ultimate_title_breadcrumb_bar' ) ) :
	function ultimate_title_breadcrumb_bar() { ?>

		<?php
			global $post;
			$meta_value = get_post_meta( $post->ID, 'meta-breadcrumb', true );
			if($meta_value != 'false') :
				if(!is_home()) : ?>

					<div class="ultimate-page-header">
						<div class="ultimate-row">
							<div class="ultimate-container imd-pagetitle-container">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left ultimate-title">
									<?php
										if(is_404()){
											$title = '404 - Page Not Found!';
										} elseif(is_search()){
											$title = 'Search Results -';
										} elseif(is_archive()){
											$title = 'Archives';
										} else {
											if( is_home() && get_option('page_for_posts') ) {
												$blog_page_id = get_option('page_for_posts');
												$title = get_page($blog_page_id)->post_title;
											} else {
												$title = $post->post_title;
											}
										}
										echo '<div class="ultimate-breadcrumb-title">';
										echo '<h3>'.$title.'</h3>';
										echo '</div>';
									?>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right ultimate-breadcrumb">
									<?php
										if( function_exists('ultimate_breadcrumb')) {
											ultimate_breadcrumb();
										}
									?>
								</div>
							</div><!-- .ultimate-container --> 
						</div><!-- .ultimate-row --> 
					</div><!-- .ultimate-page-header --> 

				<?php endif; ?>
			<?php endif; ?>
		<?php 
	}	
	add_action('ult_header_after', 'ultimate_title_breadcrumb_bar');
endif;
?>