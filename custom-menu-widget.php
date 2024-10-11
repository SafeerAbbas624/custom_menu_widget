<?php
/*
Plugin Name: Custom Menu Widget
Description: A custom Elementor widget for creating a menu bar with dropdowns.
Version: 1.0
Author: Safeer Abbas
*/

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include the widget class
function custom_menu_widget_load() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-menu-widget.php';
}
add_action( 'elementor/widgets/widgets_registered', 'custom_menu_widget_load' );

// Enqueue styles
function custom_menu_widget_enqueue_styles() {
    wp_enqueue_style( 'custom-menu-widget-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'custom_menu_widget_enqueue_styles' );

// Register the widget
add_action('elementor/widgets/widgets_registered', function() {
    // Ensure the widget class is loaded
    if ( class_exists( 'Custom_Menu_Widget' ) ) {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Custom_Menu_Widget());
    }
});