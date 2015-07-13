<?php

class WSU_Museum_Theme {
	/**
	 * Setup the hooks used in the theme.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_exhibit_content_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			add_action( 'after_setup_theme', array( $this, 'setup_additional_post_thumbnails' ), 11 );
		}
	}

	/**
	 * Enqueue scripts used by the Museum theme.
	 */
	public function enqueue_scripts() {
		// Ensure the slideshow script is enqueued on individual exhibit pages.
		if ( is_singular( 'museum-exhibit' ) ) {
			wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
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
	 * Add the necessary meta boxes on the proper page view.
	 *
	 * @param string  $post_type The type of post being edited.
	 * @param WP_Post $post      The post object of the post being edited.
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( 'museum-exhibit' !== $post_type ) {
			return;
		}
		add_meta_box( 'museum_exhibit_artist', 'Artist Text', array( $this, 'display_artist_text_meta_box' ), 'museum-exhibit', 'normal', 'high' );
		add_meta_box( 'museum_gallery_content', 'Gallery Content', array( $this, 'display_gallery_content_meta_box' ), 'museum-exhibit', 'normal' );
		add_meta_box( 'museum_sidebar_content', 'Sidebar Content', array( $this, 'display_sidebar_content_meta_box' ), 'museum-exhibit', 'normal' );
	}

	public function display_sidebar_content_meta_box( $post ) {
		$content = $this->get_sidebar_content( $post->ID );

		wp_editor( $content, 'exhibit_sidebar_content' );
	}

	public function display_gallery_content_meta_box( $post ) {
		$content = $this->get_gallery_content( $post->ID );

		wp_editor( $content, 'exhibit_gallery_content' );
	}

	public function display_artist_text_meta_box( $post ) {
		$artist = $this->get_exhibit_artist( $post->ID );

		?>
		<label for="artist_text">Artist:</label>
		<input type="text" name="artist_text" value="<?php echo esc_attr( $artist ); ?>" class="widefat" />
		<p class="description">Enter the text that should display for the artist or collection name under the exhibit headline.</p>
		<?php
	}

	/**
	 * Save overlay type when the front page is saved.
	 *
	 * @param int     $post_id ID of the post currently being saved.
	 * @param WP_Post $post    Object of the post being saved.
	 */
	public function save_post( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'museum-exhibit' !== $post->post_type ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( isset( $_POST['artist_text'] ) ) {
			update_post_meta( $post_id, '_exhibit_artist_text', sanitize_text_field( $_POST['artist_text'] ) );
		}

		if ( isset( $_POST['exhibit_sidebar_content'] ) ) {
			$content = wp_kses_post( $_POST['exhibit_sidebar_content'] );
			update_post_meta( $post_id, '_exhibit_sidebar_content', $content );
		}

		if ( isset( $_POST['exhibit_gallery_content'] ) ) {
			$content = wp_kses_post( $_POST['exhibit_gallery_content'] );
			update_post_meta( $post_id, '_exhibit_gallery_content', $content );
		}

		return;
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

	public function has_thumbnail( $type ) {
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			return MultiPostThumbnails::has_post_thumbnail( get_post_type(), $type );
		}
		return false;
	}

	public function get_thumbnail( $type ) {
		if ( class_exists( 'MultiPostThumbnails' ) ) {
			return MultiPostThumbnails::get_post_thumbnail_url( get_post_type(), $type, get_the_ID(), 'spine-xlarge-size' );
		}

		return '';
	}

	public function get_sidebar_content( $post_id ) {
		$content = get_post_meta( $post_id, '_exhibit_sidebar_content', true );

		return $content;
	}

	public function get_gallery_content( $post_id ) {
		$content = get_post_meta( $post_id, '_exhibit_gallery_content', true );

		return $content;
	}

	public function get_exhibit_artist( $post_id ) {
		$artist = get_post_meta( $post_id, '_exhibit_artist_text', true );

		return $artist;
	}
}
$wsu_museum_theme = new WSU_Museum_Theme();

/**
 * Retrieve the content used for the sidebar on an exhibit.
 *
 * @return string
 */
function wsu_museum_get_sidebar_content() {
	global $wsu_museum_theme;

	return $wsu_museum_theme->get_sidebar_content( get_the_ID() );
}

/**
 * Retrieve the gallery content for an exhibit.
 *
 * @return string
 */
function wsu_museum_get_gallery_content() {
	global $wsu_museum_theme;

	return $wsu_museum_theme->get_gallery_content( get_the_ID() );
}

/**
 * Retrieve the text used to display an exhibit's artist.
 *
 * @return string
 */
function wsu_museum_get_exhibit_artist() {
	global $wsu_museum_theme;

	return $wsu_museum_theme->get_exhibit_artist( get_the_ID() );
}

function wsu_museum_get_slides() {
	global $wsu_museum_theme;

	$slide_images = array();

	if ( spine_has_featured_image() ) {
		$slide_images[] = spine_get_featured_image_src();
	}

	if ( $wsu_museum_theme->has_thumbnail( 'slide-two' ) ) {
		$slide_images[] = $wsu_museum_theme->get_thumbnail( 'slide-two' );
	}

	if ( $wsu_museum_theme->has_thumbnail( 'slide-three' ) ) {
		$slide_images[] = $wsu_museum_theme->get_thumbnail( 'slide-three' );
	}

	return $slide_images;
}