<?php
/*
Plugin Name: StartUp Sections
Description: Le plugin pour activer le Custom Post Sections
Author: Yann Caplain
Version: 0.1.0
*/

//GitHub Plugin Updater
function startup_reloaded_sections_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-sections',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-sections',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-sections/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-sections',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-sections/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

add_action( 'init', 'startup_reloaded_sections_updater' );

//CPT
function startup_reloaded_sections() {
	$labels = array(
		'name'                => 'Sections',
		'singular_name'       => 'Section',
		'menu_name'           => 'Sections',
		'name_admin_bar'      => 'Sections',
		'parent_item_colon'   => 'Parent Item:',
		'all_items'           => 'All Items',
		'add_new_item'        => 'Add New Item',
		'add_new'             => 'Add New',
		'new_item'            => 'New Item',
		'edit_item'           => 'Edit Item',
		'update_item'         => 'Update Item',
		'view_item'           => 'View Item',
		'search_items'        => 'Search Item',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash'
	);
	$args = array(
		'label'               => 'sections',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-editor-table',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
        'capability_type'     => array('section','sections'),
        'map_meta_cap'        => true
	);
	register_post_type( 'sections', $args );

}

add_action( 'init', 'startup_reloaded_sections', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_sections_rewrite_flush() {
    startup_reloaded_sections();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_sections_rewrite_flush' );

// Capabilities
function startup_reloaded_sections_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_section' );
	$role_admin->add_cap( 'read_section' );
	$role_admin->add_cap( 'delete_section' );
	$role_admin->add_cap( 'edit_others_sections' );
	$role_admin->add_cap( 'publish_sections' );
	$role_admin->add_cap( 'edit_sections' );
	$role_admin->add_cap( 'read_private_sections' );
	$role_admin->add_cap( 'delete_sections' );
	$role_admin->add_cap( 'delete_private_sections' );
	$role_admin->add_cap( 'delete_published_sections' );
	$role_admin->add_cap( 'delete_others_sections' );
	$role_admin->add_cap( 'edit_private_sections' );
	$role_admin->add_cap( 'edit_published_sections' );
}

register_activation_hook( __FILE__, 'startup_reloaded_sections_caps' );

// Metaboxes

function startup_reloaded_sections_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_sections_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Section details', 'cmb2' ),
		'object_types'  => array( 'sections' )
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Display title', 'cmb2' ),
		'id'               => $prefix . 'title',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Content position', 'cmb2' ),
		'id'               => $prefix . 'position',
		'type'             => 'select',
		'show_option_none' => false,
        'default'          => 'center',
		'options'          => array(
			'left' => __( 'Left', 'cmb2' ),
			'center'   => __( 'Center', 'cmb2' ),
			'right'     => __( 'Right', 'cmb2' )
		)
	) );
    
     $cmb_box->add_field( array(
		'name'             => __( 'Effect', 'cmb2' ),
		'id'               => $prefix . 'effect',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => array(
			'light' => __( 'Light', 'cmb2' ),
			'dark'   => __( 'Dark', 'cmb2' ),
			'trame-01'     => __( 'Trame 1', 'cmb2' ),
            'trame-02'     => __( 'Trame 2', 'cmb2' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Background color', 'cmb2' ),
        'id'      => $prefix . 'background_color',
        'type'    => 'colorpicker',
        'default' => '#fff'
    ) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Background image position', 'cmb2' ),
		'id'               => $prefix . 'background_position',
		'type'             => 'select',
        'default'          => 'center',
		'options'          => array(
			'top' => __( 'Top', 'cmb2' ),
			'center'   => __( 'Center', 'cmb2' ),
			'bottom'     => __( 'Bottom', 'cmb2' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Video', 'cmb2' ),
        'desc'             => __( 'YouTube url for background video. Always use in first slide only to prevent CPU load.', 'cmb2' ),
        'id'      => $prefix . 'background_video',
        'type'    => 'text'
    ) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Padding', 'cmb2' ),
        'desc'             => __( 'Padding in px', 'cmb2' ),
		'id'         => $prefix . 'padding',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Boxed', 'cmb2' ),
        'desc'             => __( 'Put the text inside a box', 'cmb2' ),
		'id'               => $prefix . 'boxed',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Parallax', 'cmb2' ),
        'desc'             => __( 'Add parallax effect to the background', 'cmb2' ),
		'id'               => $prefix . 'parallax',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button text', 'cmb2' ),
		'id'         => $prefix . 'button_text',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button url', 'cmb2' ),
		'id'         => $prefix . 'button_url',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button target', 'cmb2' ),
        'desc'             => __( '_blank', 'cmb2' ),
		'id'               => $prefix . 'blank',
		'type'             => 'checkbox'
	) );
}

add_action( 'cmb2_init', 'startup_reloaded_sections_meta' );
?>