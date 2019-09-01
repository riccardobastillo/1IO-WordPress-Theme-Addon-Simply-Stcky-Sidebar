<?php     
	/*
	Plugin Name: Simply Sticky Sidebar 
	Description: Add-on to make a sticky sidebar for Simply Theme
	Version: 1.0.0
	Author: Jose Mortellaro
	Author URI: https://josemortellaro.com/
	Text Domain: ricbastbar
	Domain Path: /languages
	License: GPLv2 or later
	*/
if( !defined('ABSPATH') ) die();
class RicbastbarFrontend{
	public function __construct(){
		add_action( 'wp_enqueue_scripts', array( $this, 'ricbastbar_disable_at' ), 99 );
	}
	public function ricbastbar_script() {
		$ricbast_options = $this->sss_get_options();
		if ( is_admin_bar_showing() ) {
			$aditionalmargintop = $ricbast_options['ricbast_margin_top'] + 32;
			} else {					
			$aditionalmargintop = $ricbast_options['ricbast_margin_top'];
			}
		wp_register_script('ricbastbar', untrailingslashit( plugins_url( '', __FILE__ ) ).'/js/theia-sticky-sidebar.js', array('jquery'), '1.2.3', true );
		wp_enqueue_script( 'ricbastbar' );
		$ricbast_translation_array = array( 
			'ricbast_string' => $ricbast_options['ricbast_class_selector'] ,
			'ricbast_content_string' => $ricbast_options['ricbast_class_content_selector'] ,
			'ricbast_margin_top_string' => $aditionalmargintop,
			'ricbast_margin_bot_string' => $ricbast_options['ricbast_margin_bot'],
			'ricbast_update_sidebar_height_string' => $ricbast_options['ricbast_update_sidebar_height'],
			'ricbast_min_width_string' => $ricbast_options['ricbast_min_width']
		);
		wp_localize_script( 'ricbastbar', 'ricbast_name', $ricbast_translation_array );
	}
	public function sss_get_options(){
		$ricbast_options = get_option( 'ricbast_option_name' );
		$theme = wp_get_theme();
		if( is_object( $theme ) && 'fl-automator' === $theme->get( 'TextDomain' ) ){
			$ricbast_options = array();
			foreach( array(
				'ricbast_disable_at_front_home',
				'ricbast_disable_at_blog',
				'ricbast_disable_at_page', 
				'ricbast_disable_at_tag',
				'ricbast_disable_at_category',
				'ricbast_disable_at_single',
				'ricbast_disable_at_archive',
				'ricbast_disable_at_search'
			) as $key){
				if( '1' === get_theme_mod( $key ) ){
					$ricbast_options[$key] = true;
				}
			}

			$ricbast_options['ricbast_margin_top']  = 90;
			$ricbast_options['ricbast_margin_bot']  = 0;
			$ricbast_options['ricbast_update_sidebar_height']  = '';			
			$ricbast_options['ricbast_class_selector'] = '.fl-sidebar';
			$ricbast_options['ricbast_class_content_selector'] = '';
			$ricbast_options['ricbast_min_width'] = esc_attr( get_theme_mod( 'ricbast_min_width' ) );
			return $ricbast_options;
		}
		return get_option( 'ricbast_option_name' );
		
	}
	public function ricbastbar_disable_at() {
		$ricbast_options = $this->sss_get_options();	
		$ricbast_disable_at_front_home = isset($ricbast_options['ricbast_disable_at_front_home']);
		$ricbast_disable_at_blog = isset($ricbast_options['ricbast_disable_at_blog']);
		$ricbast_disable_at_page = isset($ricbast_options['ricbast_disable_at_page']);
		$ricbast_disable_at_tag = isset($ricbast_options['ricbast_disable_at_tag']);
		$ricbast_disable_at_category = isset($ricbast_options['ricbast_disable_at_category']);
		$ricbast_disable_at_single = isset($ricbast_options['ricbast_disable_at_single']);
		$ricbast_disable_at_archive = isset($ricbast_options['ricbast_disable_at_archive']);
		$ricbast_disable_at_search = isset($ricbast_options['ricbast_disable_at_search']);
		$ricbast_enable_at_pages = isset($ricbast_options['ricbast_enable_at_pages']) ? $ricbast_options['ricbast_enable_at_pages'] : '';
		$ricbast_enable_at_posts = isset($ricbast_options['ricbast_enable_at_posts']) ? $ricbast_options['ricbast_enable_at_posts'] : '';
		$ricbast_enable_at_pages_exp = array_map('trim', explode(',', $ricbast_enable_at_pages));
		$ricbast_enable_at_posts_exp = array_map('trim', explode(',', $ricbast_enable_at_posts));
		if ( is_front_page() && is_home() ) { // Default homepage
			if ( $ricbast_disable_at_front_home == false ) { 
				$this->ricbastbar_script();
			};
		} elseif ( is_front_page()){ //Static homepage
			if ( $ricbast_disable_at_front_home == false ) { 
				$this->ricbastbar_script();
			};
		} elseif ( is_home()){ //Blog page
			if ( $ricbast_disable_at_blog == false ) { 
				$this->ricbastbar_script();
			};
		} elseif ( is_page() ){ //Single page
			if ( $ricbast_disable_at_page == false ) { 
				$this->ricbastbar_script();
			};
			if ( is_page( $ricbast_enable_at_pages_exp  )  ){ 
			$this->ricbastbar_script();
			}
		} elseif ( is_tag()){ //Tag page
			if ( $ricbast_disable_at_tag == false ) { 
				$this->ricbastbar_script();
			};
		} elseif ( is_category()){ //Category page
			if ( $ricbast_disable_at_category == false ) { 
				$this->ricbastbar_script();
			};
		} elseif ( is_single()){ //Single post
			if ( $ricbast_disable_at_single == false ) { 
				$this->ricbastbar_script();
			};
			if ( is_single( $ricbast_enable_at_posts_exp  )  ){ 
				$this->ricbastbar_script();
			}
		} elseif ( is_archive()){ //Archive
			if ( $ricbast_disable_at_archive == false ) { 
				$this->ricbastbar_script();
			};
		} elseif ( is_search()){ //Search
			if ( $ricbast_disable_at_search == false ) { 
				$this->ricbastbar_script();
			};
		}
	}	
}
if( is_admin() ){
	$theme = wp_get_theme();
	if( 'fl-automator' !== $theme->get( 'TextDomain' ) ){
		require untrailingslashit( dirname( __FILE__ ) ).'/admin/sss-admin.php';
		new RicbastbarBackend();
	}	
}
else{
	new RicbastbarFrontend();
}
add_action( 'after_setup_theme', 'ricbast_after_setup_theme' );
//It include the PHP functions for the customize previw
function ricbast_after_setup_theme(){
	if( is_customize_preview() ){
		load_plugin_textdomain('ricbastbar', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
		require untrailingslashit( dirname( __FILE__ ) ).'/admin/sss-admin-customize.php';
	}
}