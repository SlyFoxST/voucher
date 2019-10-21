<?php

if( !defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly

// Register WPGV Item Post Type
function wpgv_voucher_product_function() {

	$labels = array(
		'name'                  => __( 'Gift Items', 'Post Type General Name', 'gift-voucher' ),
		'singular_name'         => __( 'Item', 'Post Type Singular Name', 'gift-voucher' ),
		'menu_name'             => __( 'Gift Vouchers', 'gift-voucher' ),
		'name_admin_bar'        => __( 'Item', 'gift-voucher' ),
		'archives'              => __( 'Item Archives', 'gift-voucher' ),
		'attributes'            => __( 'Item Attributes', 'gift-voucher' ),
		'parent_item_colon'     => __( 'Parent Item:', 'gift-voucher' ),
		'all_items'             => __( 'All Gift Items', 'gift-voucher' ),
		'add_new_item'          => __( 'Add New Item', 'gift-voucher' ),
		'add_new'               => __( 'Add New Item', 'gift-voucher' ),
		'new_item'              => __( 'New Item', 'gift-voucher' ),
		'edit_item'             => __( 'Edit Item', 'gift-voucher' ),
		'update_item'           => __( 'Update Item', 'gift-voucher' ),
		'view_item'             => __( 'View Item', 'gift-voucher' ),
		'view_items'            => __( 'View Items', 'gift-voucher' ),
		'search_items'          => __( 'Search Item', 'gift-voucher' ),
		'not_found'             => __( 'Not found', 'gift-voucher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gift-voucher' ),
		'featured_image'        => __( 'Featured Image', 'gift-voucher' ),
		'set_featured_image'    => __( 'Set Item image', 'gift-voucher' ),
		'remove_featured_image' => __( 'Remove featured image', 'gift-voucher' ),
		'use_featured_image'    => __( 'Use as featured image', 'gift-voucher' ),
		'insert_into_item'      => __( 'Insert into Item', 'gift-voucher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Item', 'gift-voucher' ),
		'items_list'            => __( 'Items list', 'gift-voucher' ),
		'items_list_navigation' => __( 'Items list navigation', 'gift-voucher' ),
		'filter_items_list'     => __( 'Filter Items list', 'gift-voucher' ),
	);
	$args = array(
		'label'                 => __( 'Item', 'gift-voucher' ),
		'description'           => __( 'Create your store Items as a product', 'gift-voucher' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'taxonomies'            => array( 'wpgv_voucher_category' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-tickets-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'rewrite'               => false,
		'capability_type'       => 'post',
	);
	register_post_type( 'wpgv_voucher_product', $args );

}
add_action( 'init', 'wpgv_voucher_product_function', 0 );

// Register WPGV Item Taxonomy
function wpgv_voucher_category_function() {

	$labels = array(
		'name'                       => __( 'Item Categories', 'Taxonomy General Name', 'gift-voucher' ),
		'singular_name'              => __( 'Item Category', 'Taxonomy Singular Name', 'gift-voucher' ),
		'menu_name'                  => __( 'Item Category', 'gift-voucher' ),
		'all_items'                  => __( 'All Item Categories', 'gift-voucher' ),
		'parent_item'                => __( 'Parent Category', 'gift-voucher' ),
		'parent_item_colon'          => __( 'Parent Category:', 'gift-voucher' ),
		'new_item_name'              => __( 'New Category Name', 'gift-voucher' ),
		'add_new_item'               => __( 'Add New Category', 'gift-voucher' ),
		'edit_item'                  => __( 'Edit Category', 'gift-voucher' ),
		'update_item'                => __( 'Update Category', 'gift-voucher' ),
		'view_item'                  => __( 'View Category', 'gift-voucher' ),
		'separate_items_with_commas' => __( 'Separate Categories with commas', 'gift-voucher' ),
		'add_or_remove_items'        => __( 'Add or remove Categories', 'gift-voucher' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'gift-voucher' ),
		'popular_items'              => __( 'Popular Categories', 'gift-voucher' ),
		'search_items'               => __( 'Search Categories', 'gift-voucher' ),
		'not_found'                  => __( 'Not Found', 'gift-voucher' ),
		'no_terms'                   => __( 'No Categories', 'gift-voucher' ),
		'items_list'                 => __( 'Categories list', 'gift-voucher' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'gift-voucher' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => false,
	);
	register_taxonomy( 'wpgv_voucher_category', array( 'wpgv_voucher_product' ), $args );

}
add_action( 'init', 'wpgv_voucher_category_function', 0 );