<?php
/* Template Name: Product page */

get_header(); ?>

<?php
/* Featured procucts */
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

        do_action('woocommerce_shop_loop');
            wc_get_template_part( 'content', 'product' ) ;
        endwhile;
    } else {
        echo __( 'No products found' );
    }
    echo '</ul>'; 
    echo '</div>';
    wp_reset_postdata();
  


 /* Popular products and Price High to low combination */


      $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_price',
        'order'          => 'desc'
    );
    
    $loop = new WP_Query( $args );
    echo '<div class="woocommerce">';
    echo '<ul class="products">';
    echo '<h2>Popular Products</h2>';
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
        do_action('woocommerce_shop_loop');
                wc_get_template_part( 'content', 'product' ) ;
        endwhile;
    } else {
        echo __( 'No products found' );
    }
    echo '</ul>'; 
      echo '</div>';
      wp_reset_postdata();


      /* Categories New ,old */

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

        do_action('woocommerce_shop_loop');
            wc_get_template_part( 'content', 'product' ) ;
        endwhile;
    } else {
        echo __( 'No products found' );
    }
    echo '</ul>'; 
    echo '</div>';
    wp_reset_postdata();

    ?>

<?php get_footer();



