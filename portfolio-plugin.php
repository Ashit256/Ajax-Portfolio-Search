<?php
/**
 * Plugin Name: Portfolio Plugin
 * Description: A custom plugin to manage portfolios.
 * Version: 1.0
 * Author: Ashit Pancholee
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   // exit;
}

// Register Custom Post Type
function portfolio_post_type() {
    $labels = array(
        'name'                  => _x('Portfolios', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Portfolio', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Portfolios', 'text_domain'),
        'name_admin_bar'        => __('Portfolio', 'text_domain'),
        'archives'              => __('Portfolio Archives', 'text_domain'),
        'attributes'            => __('Portfolio Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Portfolio:', 'text_domain'),
        'all_items'             => __('All Portfolios', 'text_domain'),
        'add_new_item'          => __('Add New Portfolio', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Portfolio', 'text_domain'),
        'edit_item'             => __('Edit Portfolio', 'text_domain'),
        'update_item'           => __('Update Portfolio', 'text_domain'),
        'view_item'             => __('View Portfolio', 'text_domain'),
        'view_items'            => __('View Portfolios', 'text_domain'),
        'search_items'          => __('Search Portfolio', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into portfolio', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this portfolio', 'text_domain'),
        'items_list'            => __('Portfolios list', 'text_domain'),
        'items_list_navigation' => __('Portfolios list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter portfolios list', 'text_domain'),
    );
    $args = array(
        'label'                 => __('Portfolio', 'text_domain'),
        'description'           => __('Post Type Description', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type('portfolio', $args);
}
add_action('init', 'portfolio_post_type', 0);

function enqueue_portfolio_scripts() {
    wp_enqueue_script('portfolio-search', plugin_dir_url(__FILE__) . 'portfolio-search.js', array('jquery'), null, true);
    wp_localize_script('portfolio-search', 'portfolioAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_portfolio_scripts');

function portfolio_ajax_search() {
    $search_query = sanitize_text_field($_POST['searchQuery']);

    $args = array(
        'post_type' => 'portfolio',
        's' => $search_query,
        'posts_per_page' => -1
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="portfolio-item">';
            echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            the_content();
            echo '</div>';
        }
    } else {
        echo '<p>No portfolios found</p>';
    }

    wp_die();
}
add_action('wp_ajax_portfolio_ajax_search', 'portfolio_ajax_search');
add_action('wp_ajax_nopriv_portfolio_ajax_search', 'portfolio_ajax_search');
?>
