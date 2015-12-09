<?php
/*
Plugin Name: StartUp CPT Sections
Description: Le plugin pour activer le Custom Post Sections
Author: Yann Caplain
Version: 0.1.0
Text Domain: startup-cpt-sections
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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

//add_action( 'init', 'startup_reloaded_sections_updater' );

//CPT
function startup_reloaded_sections() {
	$labels = array(
        'name'                       => _x( 'Sections', 'Taxonomy General Name', 'startup-cpt-sections' ),
		'singular_name'              => _x( 'Section', 'Taxonomy Singular Name', 'startup-cpt-sections' ),
		'menu_name'                  => __( 'Sections', 'startup-cpt-sections' ),
        'name_admin_bar'             => __( 'Sections', 'startup-cpt-sections' ),
		'all_items'                  => __( 'All Items', 'startup-cpt-sections' ),
		'parent_item'                => __( 'Parent Item', 'startup-cpt-sections' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-cpt-sections' ),
		'new_item_name'              => __( 'New Item Name', 'startup-cpt-sections' ),
		'add_new_item'               => __( 'Add New Item', 'startup-cpt-sections' ),
		'edit_item'                  => __( 'Edit Item', 'startup-cpt-sections' ),
		'update_item'                => __( 'Update Item', 'startup-cpt-sections' ),
		'view_item'                  => __( 'View Item', 'startup-cpt-sections' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-cpt-sections' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-cpt-sections' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-sections' ),
		'popular_items'              => __( 'Popular Items', 'startup-cpt-sections' ),
		'search_items'               => __( 'Search Items', 'startup-cpt-sections' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-sections' )
	);
	$args = array(
        'label'               => __( 'sections', 'startup-cpt-sections' ),
        'description'         => __( '', 'startup-cpt-home' ),
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
		'title'         => __( 'Section details', 'startup-cpt-sections' ),
		'object_types'  => array( 'sections' )
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Display title', 'startup-cpt-sections' ),
		'id'               => $prefix . 'title',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Content position', 'startup-cpt-sections' ),
		'id'               => $prefix . 'position',
		'type'             => 'select',
		'show_option_none' => false,
        'default'          => 'center',
		'options'          => array(
			'left' => __( 'Left', 'startup-cpt-sections' ),
			'center'   => __( 'Center', 'startup-cpt-sections' ),
			'right'     => __( 'Right', 'startup-cpt-sections' )
		)
	) );
    
     $cmb_box->add_field( array(
		'name'             => __( 'Effect', 'startup-cpt-sections' ),
		'id'               => $prefix . 'effect',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => array(
			'light' => __( 'Light', 'startup-cpt-sections' ),
			'dark'   => __( 'Dark', 'startup-cpt-sections' ),
			'trame-01'     => __( 'Trame 1', 'startup-cpt-sections' ),
            'trame-02'     => __( 'Trame 2', 'startup-cpt-sections' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Background color', 'startup-cpt-sections' ),
        'id'      => $prefix . 'background_color',
        'type'    => 'colorpicker',
        'default' => '#fff'
    ) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Text color', 'startup-cpt-sections' ),
        'id'      => $prefix . 'color',
        'type'    => 'colorpicker',
        'default' => ''
    ) );
    
    $cmb_box->add_field( array(
		'name' => __( 'Background image', 'startup-cpt-sections' ),
		'id'   => $prefix . 'background',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Background image position', 'startup-cpt-sections' ),
		'id'               => $prefix . 'background_position',
		'type'             => 'select',
        'default'          => 'center',
		'options'          => array(
			'top' => __( 'Top', 'startup-cpt-sections' ),
			'center'   => __( 'Center', 'startup-cpt-sections' ),
			'bottom'     => __( 'Bottom', 'startup-cpt-sections' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Video', 'startup-cpt-sections' ),
        'desc'             => __( 'YouTube url for background video.', 'startup-cpt-sections' ),
        'id'      => $prefix . 'background_video',
        'type'    => 'text'
    ) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Padding', 'startup-cpt-sections' ),
        'desc'             => __( 'Padding in px', 'startup-cpt-sections' ),
		'id'         => $prefix . 'padding',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Boxed', 'startup-cpt-sections' ),
        'desc'             => __( 'Put the text inside a box', 'startup-cpt-sections' ),
		'id'               => $prefix . 'boxed',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Parallax', 'startup-cpt-sections' ),
        'desc'             => __( 'Add parallax effect to the background', 'startup-cpt-sections' ),
		'id'               => $prefix . 'parallax',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button text', 'startup-cpt-sections' ),
		'id'         => $prefix . 'button_text',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button url', 'startup-cpt-sections' ),
		'id'         => $prefix . 'button_url',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button target', 'startup-cpt-sections' ),
        'desc'             => __( '_blank', 'startup-cpt-sections' ),
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
                                <p class="boxed"><?php echo do_shortcode($section->post_content) ?></p>
                            <?php } else{ ?>
                                <?php if ( $title ){ ?><h2><?php echo $section->post_title ?></h2><?php } ?>
                                <p><?php echo do_shortcode($section->post_content) ?></p>
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

// Shortcode UI
/**
 * Detecion de Shortcake. Identique dans tous les plugins.
 */
if ( !function_exists( 'shortcode_ui_detection' ) ) {
    function shortcode_ui_detection() {
        if ( !function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
            add_action( 'admin_notices', 'shortcode_ui_notice' );
        }
    }

    function shortcode_ui_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            echo '<div class="error message"><p>Shortcode UI plugin must be active to use fast shortcodes.</p></div>';
        }
    }

add_action( 'init', 'shortcode_ui_detection' );
}

function startup_cpt_sections_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'section',
        array(
            'label' => esc_html__( 'Sections', 'startup-cpt-sections' ),
            'listItemImage' => 'dashicons-editor-table',
            'attrs' => array(
                array(
                    'label' => 'Background',
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
                array(
                    'label'       => esc_html__( 'ID', 'startup-cpt-sections' ),
                    'attr'        => 'id',
					'type' => 'post_select',
					'query' => array( 'post_type' => 'sections' ),
					'multiple' => false,
                ),
            ),
        )
    );
};

if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_sections_shortcode_ui');
}

// Enqueue scripts and styles.
function startup_cpt_sections_scripts() {
    wp_enqueue_style( 'startup-cpt-sections-style', plugins_url( '/css/startup-cpt-sections.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_sections_scripts' );
?>