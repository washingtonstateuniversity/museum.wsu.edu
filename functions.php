<?php

class WSU_Museum_Theme {
	/**
	 * Setup the hooks used in the theme.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_exhibit_content_type' ) );

		if ( class_exists( 'MultiPostThumbnails' ) ) {
			add_action( 'after_setup_theme', array( $this, 'setup_additional_post_thumbnails' ), 11 );
		}
	}

	/**
	 * Register the content type to handle museum exhibits.
	 */
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
			'rewrite'           => array( 'slug' => 'exhibit' ),
			'query_var'         => true,
			'menu_icon'         => 'dashicons-images-alt',
		) );
	}

	/**
	 * Add support for additional post thumbnails to be used when generating the
	 * slider at the top of museum exhibits. Requires that the Multiple Post
	 * Thumbnail plugin be enabled.
	 */
	public function setup_additional_post_thumbnails() {
		$slide_two_args = array(
			'post_type' => 'museum-exhibit',
			'label' => 'Slide Two',
			'id' => 'slide-two',
		);

		$slide_three_args = array(
			'post_type' => 'museum-exhibit',
			'label' => 'Slide Three',
			'id' => 'slide-three',
		);

		new MultiPostThumbnails( $slide_two_args );
		new MultiPostThumbnails( $slide_three_args );
	}
}
new WSU_Museum_Theme();