<?php

class WSU_Museum_Theme {
	public function __construct() {
		add_action( 'init', array( $this, 'register_exhibit_content_type' ) );
	}

	public function register_exhibit_content_type() {
		register_post_type( 'museum-exhibit', array(
			'labels'            => array(
				'name'                => __( 'Exhibits', 'spine' ),
				'singular_name'       => __( 'Exhibit', 'spine' ),
				'all_items'           => __( 'Exhibits', 'spine' ),
				'new_item'            => __( 'New Exhibit', 'spine' ),
				'add_new'             => __( 'Add New', 'spine' ),
				'add_new_item'        => __( 'Add New Exhibit', 'spine' ),
				'edit_item'           => __( 'Edit Exhibit', 'spine' ),
				'view_item'           => __( 'View Exhibit', 'spine' ),
				'search_items'        => __( 'Search Exhibits', 'spine' ),
				'not_found'           => __( 'No Exhibits found', 'spine' ),
				'not_found_in_trash'  => __( 'No Exhibits found in trash', 'spine' ),
				'parent_item_colon'   => __( 'Parent Exhibit', 'spine' ),
				'menu_name'           => __( 'Exhibits', 'spine' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'supports'          => array( 'title', 'editor', 'thumbnail' ),
			'has_archive'       => true,
			'rewrite'           => array( 'exhibit' ),
			'query_var'         => true,
			'menu_icon'         => 'dashicons-images-alt',
		) );
	}
}
new WSU_Museum_Theme();