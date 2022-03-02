<?php
/* Template Name: Product page */

get_header(); ?>


<div class="products">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="filters">
            <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
              <button>Apply filter</button>
              <input type="hidden" name="action" value="myfilter">
              <select name="myproduct" id="filter_products" class="cat-list">
                <option value="all" class="allProducts">All</option>
                <option value="featured" class="cat-list_item">Featured Product</option>
                <option value="popular" class="cat-list_item">Popular Product</option>
                <option value="category" class="cat-list_item">Category Product</option>
              </select>
            </form>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row" id="response">
            </div>
          </div>
          <div class="col-md-12 default_products">

           <?php /* Featured procucts */ ?>
            <div class="row" id="response">
              <?php
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
                            //do_action('woocommerce_shop_loop');
                            wc_get_template_part( 'content', 'product' ) ;
                    echo '</div>';  
                    endwhile;
                } else {
                    echo __( 'No products found' );
                }
                echo '</ul>'; 
                echo '</div>';
                wp_reset_postdata();
                ?>
            </div>

            <?php /* Popular products and Price High to low combination */ ?>
            <div class="row" id="response">
              <?php
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
                            //do_action('woocommerce_shop_loop');
                            wc_get_template_part( 'content', 'product' ) ;
                    echo '</div>';        
                    endwhile;
                } else {
                    echo __( 'No products found' );
                }
                echo '</ul>'; 
                  echo '</div>';
                  wp_reset_postdata();
            
                ?>
            </div>
            <?php /* Categories New ,old */ ?>
            <div class="row" id="response">
              <?php
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
                            //do_action('woocommerce_shop_loop');
                            wc_get_template_part( 'content', 'product' ) ;
                    echo '</div>';  
                    endwhile;
                } else {
                    echo __( 'No products found' );
                }
                echo '</ul>'; 
                echo '</div>';
                wp_reset_postdata();
                ?>
            </div>
            </div>
        </div>
</div>

<script>
jQuery(function($){
  $('#filter').submit(function(){
      var filter = $('#filter');
      $.ajax({
          url:filter.attr('action'),
          data:filter.serialize(), // form data
          type:filter.attr('method'), // POST
          beforeSend:function(xhr){
              filter.find('button').text('Processing...'); // changing the button label
          },
          success:function(data){
              filter.find('button').text('Apply filter'); // changing the button label back
              $('#response').html(data); // insert data
          }
      });
      return false;
  });

});
</script>
<?php 
get_footer();


