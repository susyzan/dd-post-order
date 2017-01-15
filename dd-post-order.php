<?php

/**
 * Plugin Name: Drag and Drop Post Custom Order
 * Description: Drag and drop custom post reordering plugin
 * Version: 0.1.0
 * Author: Susanna Zanatta
 * Licence: GPL2
 */


register_activation_hook( __FILE__, 'update_post_meta_on_activation' );
register_activation_hook( __FILE__, 'add_custom_post_order_option_value_on_activation' );

add_action( 'admin_init', 'add_dd_post_order_capability' );

add_action( 'admin_enqueue_scripts', 'enqueue_scripts' );
add_action( 'wp_ajax_save_order', 'save_order' );
add_action( 'wp_ajax_save_custom_order_on_off', 'update_custom_post_order_option_value' );
add_action( 'wp_ajax_update_custom_order_capability', 'update_custom_order_capability_settings' );

add_action( 'admin_menu', 'post_order_options_page' );
add_action( 'admin_menu', 'posts_submenu_page' );
add_action( 'admin_menu', 'pages_submenu_page' );
add_action( 'admin_menu', 'news_submenu_page' );
add_action( 'admin_menu', 'staff_submenu_page' );
add_action( 'admin_menu', 'tutorials_submenu_page' );
add_action( 'pre_get_posts', 'custom_post_order_query' );

add_action( 'save_post', 'update_post_meta_on_post_save' );

wp_register_sidebar_widget(
	'custom_order_widget',        // widget id
	'Custom Post Order',          // widget name
	'custom_order_sort_widget',  // callback function
	array(                  // options
		'description' => 'Sort posts in the frontend'
	)
);

/**
 *
 * Enqueue scripts to manage drag and drop and ajax
 *
 */
function enqueue_scripts(){
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'draggable', plugins_url() . '/dd-post-order/assets/draggable.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'draggable', plugins_url() . '/dd-post-order/assets/draggable.css' );
}


/**
 *
 * Add subpage to posts
 *
 */
function posts_submenu_page(){
	add_submenu_page(
		'edit.php',
		'Order posts',
		'Order Posts',
		'order_posts',
		'order-posts-page',
		'post_order_submenu_page' );
}

/**
 *
 * Add subpage to pages
 *
 */
function pages_submenu_page(){
	add_submenu_page(
		'edit.php?post_type=page',
		'Order pages',
		'Order Pages',
		'order_posts',
		'order-posts-page',
		'post_order_submenu_page' );
}

/**
 *
 * Add subpage to the news custom post
 *
 */
function news_submenu_page(){
	add_submenu_page(
		'edit.php?post_type=news',
		'Order news',
		'Order News',
		'order_posts',
		'order-posts-page',
		'post_order_submenu_page' );
}

/**
 *
 * Add subpage to the staff custom post
 *
 */
function staff_submenu_page(){
	add_submenu_page(
		'edit.php?post_type=staff',
		'Order Staff',
		'Order Staff',
		'order_posts',
		'order-posts-page',
		'post_order_submenu_page' );
}

/**
 *
 * Add subpage to the tutorials custom post
 *
 */
function tutorials_submenu_page(){
	add_submenu_page(
		'edit.php?post_type=tutorials',
		'Order tutorials',
		'Order Tutorials',
		'order_posts',
		'order-posts-page',
		'post_order_submenu_page' );
}

/**
 *
 * Add plugin option page
 *
 */
function post_order_options_page(){
	add_options_page(
		'Order Posts Manage Options',
		'Order Posts',
		'order_posts',
		'order-posts-options-page',
		'post_order_add_options_page' );
}

/**
 * @param $args
 *
 * Add widget to allow user to sort posts on the front end
 *
 */
function custom_order_sort_widget( $args ){
	echo $args[ 'before_widget' ];
	echo $args[ 'before_title' ] . 'Sort Posts' . $args[ 'after_title' ];
	require 'inc/custom-post-sort-widget.php';
	echo $args[ 'after_widget' ];
}

/**
 *
 * Include the markup for all post submenu pages with the drag and drop table
 *
 */
function post_order_submenu_page(){

	if( current_user_can( 'order_posts' ) ){

		require 'inc/order-post.php';

	}
}

/**
 *
 * Include the markup for the option page
 *
 */
function post_order_add_options_page(){

	if( current_user_can( 'order_posts' ) ){

		require 'inc/options-page-wrapper.php';

	}
}


/**
 *
 * Add custom capability to the admin on plugin activation
 *
 */
function add_dd_post_order_capability(){

	$role = get_role( 'administrator' );
	$role->add_cap( 'order_posts', true );
}

/**
 *
 * Add or remove the custom capability for all user roles based on optin page settings
 *
 */
function update_custom_order_capability_settings(){
	if( ! current_user_can( 'order_posts' ) ){
		wp_send_json_error( 'Oooops, you haven\'t got the permission to do that' );
	}

	if( isset( $_POST[ 'custom_post_order_capabilities' ] ) ){

		$roles_can_order_posts = $_POST[ 'custom_post_order_capabilities' ];


		$all_roles = new WP_Roles();


		foreach( $all_roles->roles as $key => $role ){
			if( in_array( $key, $roles_can_order_posts ) ){

				$role_add = get_role( $key );
				$role_add->add_cap( 'order_posts', true );

			} else{

				$role_remove = get_role( $key );
				$role_remove->remove_cap( 'order_posts', true );
			}
		}

	}
}


/**
 *
 * Add option on/off switch to the wp-options table in the DB so that we can turn the custom post display on and off.
 * Set as on by default on first plugin activation. Will not be updated on activation if value is already set
 *
 */
function add_custom_post_order_option_value_on_activation(){

	add_option( 'dd_custom_post_order_on_off', 'on' );

}

/**
 *
 * Get user on/off setting from the option page and update the value in the DB
 *
 */
function update_custom_post_order_option_value(){

	if( ! current_user_can( 'order_posts' ) ){
		wp_send_json_error( 'Oooops, you haven\'t got the permission to do that' );

		return;
	}

	if( isset( $_POST[ 'custom_order_on_of' ] ) && $_POST[ 'custom_order_on_of' ] == 'off' ){

		update_option( 'dd_custom_post_order_on_off', 'off' );

	} else if( isset( $_POST[ 'custom_order_on_of' ] ) && $_POST[ 'custom_order_on_of' ] == 'on' ){

		update_option( 'dd_custom_post_order_on_off', 'on' );

	}
}


/**
 *
 * This adds the post order postmeta when the plugin is activated.
 *
 */
function update_post_meta_on_activation(){

	global $wpdb;

	$sql = "SELECT ID FROM wp_posts";

	$posts = $wpdb->get_results( $sql );

	foreach( $posts as $post ){
		if( ! get_post_meta( $post->ID, 'post_order' ) ){
			add_post_meta( $post->ID, 'post_order', 0 );
		}
	}
	if( function_exists( 'register_uninstall_hook' ) ){
		register_uninstall_hook( __FILE__, 'custom_post_order_uninstall' );
	}
}


/**
 *
 * Add the post order post meta to any post on save, so we can use the custo post order
 *
 */
function update_post_meta_on_post_save(){

	if( isset( $_POST[ 'post_ID' ] ) ){

		$id = $_POST[ 'post_ID' ];

		if( ! get_post_meta( $id, 'post_order' ) ){
			add_post_meta( $id, 'post_order', 0 );
		}
	}
}


/**
 *
 * Get new order set by the user and store post meta in the database
 *
 */
function save_order(){
	if( ! current_user_can( 'order_posts' ) ){
		wp_send_json_error( 'Oooops, you haven\'t got the permission to do that' );

		return;
	}
	$ordered_ids = $_POST[ 'id_array' ];
	$position    = 1;
	foreach( $ordered_ids as $id ){
		update_post_meta( (int) $id, 'post_order', $position );
		$position ++;
	}
}

/**
 *
 * Change the wp_query object to order the posts by custom order when the display option is set to on
 *
 * @param $wp_query
 *
 */
function custom_post_order_query( $wp_query ){

	//exclude admin pages, we wat to display posts as default since admin can check the post order from the submanu page
	// check the settings to see if the user wants custom post display or WordPress default
	if( ! is_admin() && get_option( 'dd_custom_post_order_on_off', 'on' ) === 'on' ){

			// target the main query and archive pages
			if( $wp_query->is_main_query() || $wp_query->is_post_type_archive()){

				if( $_GET[ 'orderby' ]  ){
					$wp_query->set( 'orderby', $_GET[ 'orderby' ] );
					$wp_query->set( 'order', $_GET[ 'order' ] );

				} else {

					$wp_query->set( 'meta_key', 'post_order' );
					$wp_query->set( 'orderby', 'meta_value_num' );
					$wp_query->set( 'order', 'ASC' );
				}
			}
		}
}

/**
 *
 * Uninstall plugin, remove postmeta and cutom capability
 *
 */
function custom_post_order_uninstall(){

	delete_post_meta_by_key( 'post_order' );
	$wp_roles = new WP_Roles();

	foreach( $wp_roles->roles as $key => $role ){

		$role_remove = get_role( $key );
		$role_remove->remove_cap( 'order_posts' );
	}

}





