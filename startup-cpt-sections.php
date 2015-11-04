<?php
/*
Plugin Name: StartUp CPT Sections
Description: Le plugin pour activer le Custom Post Sections
Author: Yann Caplain
Version: 0.1.0
Text Domain: startup-cpt-sections
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
        'name'    => __( 'Text color', 'cmb2' ),
        'id'      => $prefix . 'color',
        'type'    => 'colorpicker',
        'default' => ''
    ) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Background image', 'cmb2' ),
		'id'   => $prefix . 'background',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
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
        'desc'             => __( 'YouTube url for background video.', 'cmb2' ),
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

add_action( 'cmb2_admin_init', 'startup_reloaded_sections_meta' );

// Shortcode
function startup_reloaded_sections_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'id' => 'none',
        ), $atts);
    
	// Code
    if ($atts['id'] != "none"){
    // Si attribut
        $section = get_post( $atts['id'] );
        $title = get_post_meta( $section->ID, '_startup_reloaded_sections_title', true );
        $position = get_post_meta( $section->ID, '_startup_reloaded_sections_position', true );
        $effect = get_post_meta( $section->ID, '_startup_reloaded_sections_effect', true );
        $background_color = get_post_meta( $section->ID, '_startup_reloaded_sections_background_color', true );
        $color = get_post_meta( $section->ID, '_startup_reloaded_sections_color', true );
        $background = wp_get_attachment_image_src( get_post_meta( $section->ID, '_startup_reloaded_sections_background_id', 1 ), 'large' );
        $background_position = get_post_meta( $section->ID, '_startup_reloaded_sections_background_position', true );
        $background_video = get_post_meta( $section->ID, '_startup_reloaded_sections_background_video', true );
        $padding = get_post_meta( $section->ID, '_startup_reloaded_sections_padding', true );
        $boxed = get_post_meta( $section->ID, '_startup_reloaded_sections_boxed', true );
        $parallax = get_post_meta( $section->ID, '_startup_reloaded_sections_parallax', true );
        $button_text = get_post_meta( $section->ID, '_startup_reloaded_sections_button_text', true );
        $button_url = get_post_meta( $section->ID, '_startup_reloaded_sections_button_url', true );
        $blank = get_post_meta( $section->ID, '_startup_reloaded_sections_blank', true );
        ob_start(); ?>
            <section id="section-<?php echo $atts['id'] ?>" class="section <?php echo $position ?>"  style="<?php if ( $color ){ echo 'color:' . $color . ';'; }; if ( $background && $parallax == '' ){  echo 'background: url(' . $background[0] . '); background-size:cover; background-position: center ' . $background_position . ';';} elseif ( $background_color && $parallax == '' ) { echo 'background: ' . $background_color . ';';} ?>" <?php if ( $parallax ){ echo 'data-parallax="scroll" data-image-src="' . $background[0] . '"'; } ?>>
                <div class="effect <?php echo $effect; ?>" <?php if (!$background_video) { ?>style="<?php if ( $padding ){ echo 'padding-top:' . $padding . 'px;padding-bottom:' . $padding . 'px;'; } ?>"<?php } ?>>
                    <?php if ( $background_video ) {?><div class="video" style="<?php if ( $padding ){ echo 'padding-top:' . $padding . 'px;padding-bottom:' . $padding . 'px;'; } ?>"><?php } ?>
                        <div class="container">
                            <?php if ( $boxed ){ ?>
                                <?php if ( $title ){ ?><h2 class="boxed"><?php echo $section->post_title ?></h2><br /><?php } ?>
                                <p class="boxed"><?php echo $section->post_content ?></p>
                            <?php } else{ ?>
                                <?php if ( $title ){ ?><h2><?php echo $section->post_title ?></h2><?php } ?>
                                <p><?php echo $section->post_content ?></p>
                            <?php } ?>

                             <?php if ( $button_text ) { ?>
                                <br />
                                <a class="btn btn-custom btn-lg" href="<?php echo $button_url ?>"<?php if ( $blank ) { echo ' target="_blank"'; }?>>
                                    <?php echo $button_text ?>
                                </a>
                            <?php } ?>
                        </div>
                    <?php if ( $background_video ) {?></div><?php } ?>
                </div>
            </section>
            <?php if ( $background_video ) {?>
                <div class="player" id="section-background-video-<?php echo $atts['id'] ?>" data-property="{videoURL:'http://youtu.be/<?php echo $background_video ?>', containment:'#section-<?php echo $atts['id'] ?> .video', mute:true, loop:true, opacity:1, showControls:false}"></div>
            <?php } ?>
        <?php return ob_get_clean();  
    } else {
    // Si pas d'attribut
        return 'Vous devez renseigner l\'ID de la section dans le shortcode';
    }
}
add_shortcode( 'section', 'startup_reloaded_sections_shortcode' );
?>