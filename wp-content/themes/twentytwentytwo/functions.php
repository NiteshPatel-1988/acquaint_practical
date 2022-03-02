<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Add styles inline.
		wp_add_inline_style( 'twentytwentytwo-style', twentytwentytwo_get_font_face_styles() );

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

if ( ! function_exists( 'twentytwentytwo_editor_styles' ) ) :

	/**
	 * Enqueue editor styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_editor_styles() {

		// Add styles inline.
		wp_add_inline_style( 'wp-block-library', twentytwentytwo_get_font_face_styles() );

	}

endif;

add_action( 'admin_init', 'twentytwentytwo_editor_styles' );


if ( ! function_exists( 'twentytwentytwo_get_font_face_styles' ) ) :

	/**
	 * Get font face styles.
	 * Called by functions twentytwentytwo_styles() and twentytwentytwo_editor_styles() above.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return string
	 */
	function twentytwentytwo_get_font_face_styles() {

		return "
		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: normal;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) . "') format('woff2');
		}

		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: italic;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Italic.ttf.woff2' ) . "') format('woff2');
		}
		";

	}

endif;

if ( ! function_exists( 'twentytwentytwo_preload_webfonts' ) ) :

	/**
	 * Preloads the main web font to improve performance.
	 *
	 * Only the main web font (font-style: normal) is preloaded here since that font is always relevant (it is used
	 * on every heading, for example). The other font is only needed if there is any applicable content in italic style,
	 * and therefore preloading it would in most cases regress performance when that font would otherwise not be loaded
	 * at all.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_preload_webfonts() {
		?>
		<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>
		<?php
	}

endif;

add_action( 'wp_head', 'twentytwentytwo_preload_webfonts' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';


/* Redirect to checkout page */

add_filter( 'woocommerce_add_to_cart_redirect', 'nitesh_redirect_checkout_add_cart' );
 
function nitesh_redirect_checkout_add_cart() {
   return wc_get_checkout_url();
}


/* Ajax Custom filter code */

add_action('wp_ajax_myfilter', 'custom_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'custom_filter_function');

function custom_filter_function(){
	producthide();
	$catSlug = $_POST['myproduct'];

	if($catSlug == 'featured'){

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 3,
			'tax_query' => array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					),
				),
			);

		$loop = new WP_Query( $args );
		echo '<h2>Featured Products</h2>';
		echo '<div class="woocommerce">';
		echo '<ul class="products">';
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();

				echo '<div class="col-md-4 response">';
						do_action('woocommerce_shop_loop');
						wc_get_template_part( 'content', 'product' ) ; 
				echo '</div>';
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		echo '</ul>'; 
		echo '</div>';
		wp_reset_postdata();

}
elseif($catSlug == 'all'){?>
     
	<script>
		jQuery(function($){
			$(".default_products #response").addClass("show");
			$(".show").css("display", "block");
		});
    </script>
<?php  }
elseif($catSlug == 'popular'){
	
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 6,
		'orderby'        => 'meta_value_num',
		'meta_key'       => '_price',
		//'meta_key'       => 'total_Sales',
		'order'          => 'desc'
	);
	
	$loop = new WP_Query( $args );
	echo '<div class="woocommerce">';
	echo '<ul class="products">';
	echo '<h2>Popular Products</h2>';
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			echo '<div class="col-md-4 response">';
					do_action('woocommerce_shop_loop');
					wc_get_template_part( 'content', 'product' ) ; 
			echo '</div>';			
		endwhile;
	} else {
		echo __( 'No products found' );
	}
	echo '</ul>'; 
	  echo '</div>';
	  wp_reset_postdata();
	  producthide();	
}
elseif($catSlug == 'category'){

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 3,
		'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => array('old','new'),
					'operator'      => 'IN'
				),
			),
		);
	

	$loop = new WP_Query( $args );
	echo '<h2>Categories </h2>';
	echo '<div class="woocommerce">';
	echo '<ul class="products">';
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();

			echo '<div class="col-md-4 response">';
			        do_action('woocommerce_shop_loop');
					wc_get_template_part( 'content', 'product' ) ; 
			echo '</div>';
		endwhile;
	} else {
		echo __( 'No products found' );
	}
	echo '</ul>'; 
	echo '</div>';
	wp_reset_postdata();
	producthide();	
}
	else{
		echo "No products found";
	}
}

function producthide(){
	?>
	<script>
		jQuery(function($){
			$(".default_products #response").addClass("hide");
			$(".hide").css("display", "none");
		});
    </script>
	<?php
}

