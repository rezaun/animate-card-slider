<?php
/*
Plugin Name:Amimate Card Slider 
Plugin URI :http://wordpress.org/plugins/animate-card-slider
Description: Animate Card Slider. Slide your anytype of card
Author: Rezaun Kabir
Version: 1.0
Author URI: https://rezaun.netlify.app/
License: GPLv2 or later
Requires at least: 5.2
Requires PHP: 7.2
Stable tag: 3.1.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: astt 
*/
function card_slider_register_cpt(){
    $labels=[
        'name' =>'Card Slider'
    ];

    $args = [
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'supports' =>['title', 'editor','thumbnail', 'page-attributes']
    ];

register_post_type('card-slider', $args);    
}
add_action('init','card_slider_register_cpt');



function card_slider_shortcode(){
    $args = [
        'post_type' =>'card-slider',
        'posts_per_page' => -1
    ];

    $query = new WP_Query($args);
    
    $html ='
    <script>
    jQuery(document).ready(function($){
        $(".custom-carousel").owlCarousel({
            autoWidth: true,
            loop: true
          });
          $(document).ready(function () {
            $(".custom-carousel .item").click(function () {
              $(".custom-carousel .item").not($(this)).removeClass("active");
              $(this).toggleClass("active");
            });
          });
    });
    </script>
    
    <div class="game-section">
    <div class="owl-carousel custom-carousel owl-theme">';

    while($query->have_posts()) : $query->the_post();

    $html .='<div class="item" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), 'large').')">
      <div class="item-desc">
        <h3>'.get_the_title().'</h3>
        '.wpautop(get_the_content()).'
      </div>
    </div>';

    endwhile; wp_reset_query();

    $html .= '
    </div>
    </div>
    ';

    return $html;

}
add_shortcode('card_slider', 'card_slider_shortcode');


function card_slider_plugin_assets(){

    $plugin_dir_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('bootstrap', $plugin_dir_url.'asset/css/bootstrap.min.css');
    wp_enqueue_style('owl-min', $plugin_dir_url.'asset/css/owl.carousel.min.css');
    wp_enqueue_style('owl-theme-default', $plugin_dir_url.'asset/css/owl.theme.default.css');
    wp_enqueue_style('acs-style', $plugin_dir_url.'asset/css/style.css');
    wp_enqueue_script('owl-carousel', $plugin_dir_url.'asset/js/owl.carousel.min.js', ['jquery'], '1.0.0',true);
}
add_action('wp_enqueue_scripts', 'card_slider_plugin_assets');