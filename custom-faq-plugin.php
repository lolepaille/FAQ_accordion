<?php
/*
Plugin Name: FAQ Accordion Plugin
Description: Adds FAQ and services accordions using shortcodes.
Version: 1.01
Author: Lawrence Makoona - Lide Digital Studio
*/

// Enqueue scripts and styles
function faq_accordion_enqueue() {
    wp_register_style('faq-accordion-style', plugins_url('/faq/accordion.css', __FILE__));
    wp_enqueue_style('faq-accordion-style');

    wp_register_script('faq-accordion-js', plugins_url('/faq/accordion.js', __FILE__), array('jquery-ui-accordion'), '', true);
    wp_enqueue_script('faq-accordion-js');

    // Add your custom JavaScript code to the header
    wp_enqueue_script('custom-accordion-init', plugins_url('/faq/custom-accordion-init.js', __FILE__), array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'faq_accordion_enqueue');

// FAQ Shortcode
function faq_shortcode($atts) {
    $posts = get_posts(array(
        'numberposts' => 100,
        'orderby' => 'menu_order',
        'order' => 'DSC',
        'post_type' => 'faqslist',
    ));

    $output = '<ul id="my-accordion" class="demo-accordion accordionjs">';
    foreach ($posts as $post) {
        $output .= sprintf(
            '<li><div><h3>%1$s</h3></div><div>%2$s</div></li>',
            $post->post_title,
            wpautop($post->post_content)
        );
    }
    $output .= '</ul>';
    return $output;
}
add_shortcode('faq', 'faq_shortcode');

// Services Shortcode
function services_shortcode($atts) {
    $posts = get_posts(array(
        'numberposts' => 100,
        'orderby' => 'menu_order',
        'order' => 'DSC',
        'post_type' => 'eyecareservices',
    ));

    $output = '<ul id="services-accordion" class="demo-accordion accordionjs" data-active-index="false">';
    foreach ($posts as $post) {
        $output .= sprintf(
            '<li><div><h3>%1$s</h3></div><div>%2$s</div></li>',
            $post->post_title,
            do_shortcode(wpautop($post->post_content))
        );
    }
    $output .= '</ul>';
    return $output;
}
add_shortcode('services', 'services_shortcode');

// Practice Shortcode
function practice_shortcode($atts) {
    // Check if the practice_name custom field exists
    if (function_exists('get_field') && get_field('practice_name', 'options')) {
        $practice_name = get_field('practice_name', 'options');
        return $practice_name;
    } else {
        // Custom field doesn't exist, so return nothing
        return '';
    }
}
add_shortcode('practice', 'practice_shortcode');

?>
