<?php
if( !defined('ABSPATH') ) die();
class RicbastbarBackend{
    private $options;
	private $theme;
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ricbast_load_transl') );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_init', array( $this, 'ricbast_default_options' ) );
    }
	public function ricbast_load_transl()
	{
		load_plugin_textdomain('ricbastbar', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
	}
	public function add_plugin_page()
	{
		add_options_page(
			'Settings Admin', 
			'Simply Sticky Sidebar', 
			'manage_options', 
			'sss-stickysidebar-settings', 
			array( $this, 'create_admin_page' )
		);
	}
	public function create_admin_page()
	{
		$this->options = get_option( 'ricbast_option_name');
		?>
		<div class="wrap">
			<h2><?php _e('Simply Sticky Sidebar', 'ricbastbar'); ?></h2>       
			<form method="post" action="options.php">
			<?php
				settings_fields( 'ricbast_option_group' );   
				do_settings_sections( 'sss-stickysidebar-settings' );
				submit_button(); 
			?>
			</form>
			</div>
		<?php
	}
	public function page_init()
	{   
		global $id, $title, $callback, $page;     
		register_setting(
			'ricbast_option_group',
			'ricbast_option_name',
			array( $this, 'sanitize' )
		);
		add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() );
		add_settings_section(
			'setting_section_id',
			__("Simply Sticky Sidebar Options", 'ricbastbar'),
			array( $this, 'print_section_info' ),
			'sss-stickysidebar-settings'
		);
		add_settings_field(
			'ricbast_class_selector',
			__("Sticky Class", 'ricbastbar'),
			array( $this, 'ricbast_class_selector_callback' ),
			'sss-stickysidebar-settings',
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_class_content_selector',
			__("Container Class", 'ricbastbar'),
			array( $this, 'ricbast_class_content_selector_callback' ),
			'sss-stickysidebar-settings',
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_margin_top', 
			__("Additional top margin", 'ricbastbar'),
			array( $this, 'ricbast_margin_top_callback' ), 
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_margin_bot', 
			__("Additional bottom margin", 'ricbastbar'),
			array( $this, 'ricbast_margin_bot_callback' ), 
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_min_width', 
			__("Disable if screen width is smaller than", 'ricbastbar'),
			array( $this, 'ricbast_min_width_callback' ), 
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_update_sidebar_height', 
			__("Update sidebar height", 'ricbastbar'),
			array( $this, 'ricbast_update_sidebar_height_callback' ), 
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_front_home', 
			__("Disable at", 'ricbastbar'),
			array( $this, 'ricbast_enable_callback' ), 
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_blog', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_page', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_tag', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_category', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_single', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_archive', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_enable_at_pages', 
			'',
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_enable_at_posts', 
			'',
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'ricbast_disable_at_search', 
			__("Disable at", 'ricbastbar'),
			'sss-stickysidebar-settings', 
			'setting_section_id'
		);
	}
/**
* Sanitize each setting field as needed
*
* @param array $input Contains all settings fields as array keys
*/
	public function sanitize( $input ){
		$new_input = array();
		if( isset( $input['ricbast_class_selector'] ) )
			$new_input['ricbast_class_selector'] = sanitize_text_field( $input['ricbast_class_selector'] );
		if( isset( $input['ricbast_class_content_selector'] ) )
			$new_input['ricbast_class_content_selector'] = sanitize_text_field( $input['ricbast_class_content_selector'] );
		if( isset( $input['ricbast_margin_top'] ) )
			$new_input['ricbast_margin_top'] = absint( $input['ricbast_margin_top'] );
		if( isset( $input['ricbast_margin_bot'] ) )
			$new_input['ricbast_margin_bot'] = absint( $input['ricbast_margin_bot'] );
		if( isset( $input['ricbast_update_sidebar_height'] ) )
			$new_input['ricbast_update_sidebar_height'] = sanitize_text_field( $input['ricbast_update_sidebar_height'] );
		if( isset( $input['ricbast_min_width'] ) )
			$new_input['ricbast_min_width'] = absint( $input['ricbast_min_width'] );
		if( isset( $input['ricbast_disable_at_front_home'] ) )
			$new_input['ricbast_disable_at_front_home'] = sanitize_text_field( $input['ricbast_disable_at_front_home'] );
		if( isset( $input['ricbast_disable_at_blog'] ) )
			$new_input['ricbast_disable_at_blog'] = sanitize_text_field( $input['ricbast_disable_at_blog'] );
		if( isset( $input['ricbast_disable_at_page'] ) )
			$new_input['ricbast_disable_at_page'] = sanitize_text_field( $input['ricbast_disable_at_page'] );
		if( isset( $input['ricbast_disable_at_tag'] ) )
			$new_input['ricbast_disable_at_tag'] = sanitize_text_field( $input['ricbast_disable_at_tag'] );
		if( isset( $input['ricbast_disable_at_category'] ) )
			$new_input['ricbast_disable_at_category'] = sanitize_text_field( $input['ricbast_disable_at_category'] );
		if( isset( $input['ricbast_disable_at_single'] ) )
			$new_input['ricbast_disable_at_single'] = sanitize_text_field( $input['ricbast_disable_at_single'] );			
		if( isset( $input['ricbast_disable_at_archive'] ) )
			$new_input['ricbast_disable_at_archive'] = sanitize_text_field( $input['ricbast_disable_at_archive'] );
		if( isset( $input['ricbast_enable_at_pages'] ) )
			$new_input['ricbast_enable_at_pages'] = sanitize_text_field( $input['ricbast_enable_at_pages'] );
		if( isset( $input['ricbast_enable_at_posts'] ) )
			$new_input['ricbast_enable_at_posts'] = sanitize_text_field( $input['ricbast_enable_at_posts'] );
		if( isset( $input['ricbast_disable_at_search'] ) )
			$new_input['ricbast_disable_at_search'] = sanitize_text_field( $input['ricbast_disable_at_search'] );		
		return $new_input;
	}
	public function ricbast_default_options() {
		global $options;
		$default = array(
				'ricbast_class_selector' => '#sidebar',
				'ricbast_class_content_selector' => '',
				'ricbast_margin_top' => '90',
				'ricbast_margin_bot' => '0',
				'ricbast_min_width' => '0',
				'ricbast_update_sidebar_height' => '',
				'ricbast_enable_at_pages' => false,
				'ricbast_enable_at_posts' => false
			);
		if ( get_option('ricbast_option_name') == false ) {
			update_option( 'ricbast_option_name', $default );
		}
	}
	public function print_section_info()
	{
		echo __("Add floating sticky sidebar to any WordPress theme.", 'ricbastbar');
    }
	public function ricbast_class_selector_callback()
	{
		printf(
			'<input type="text" size="26" id="ricbast_class_selector" name="ricbast_option_name[ricbast_class_selector]" value="%s" /> ',  
			isset( $this->options['ricbast_class_selector'] ) ? esc_attr( $this->options['ricbast_class_selector']) : '' 
		);
		 echo '<span class="description">';
		 echo __("Sidebar element CSS class or id", 'ricbastbar');
		 echo '</span>';
	}
	public function ricbast_class_content_selector_callback()
	{
		printf(
			'<input type="text" size="26" id="ricbast_class_content_selector" name="ricbast_option_name[ricbast_class_content_selector]" value="%s" /> ',  
			isset( $this->options['ricbast_class_content_selector'] ) ? esc_attr( $this->options['ricbast_class_content_selector']) : '' 
		); 
		 echo '<span class="description">';
		 _e("Container element class or id. It must be element that contains both sidebar and content. If left blank script will try to guess. Usually it's #main or #main-content", 'ricbastbar');
		 echo '</span>';
	}
	public function ricbast_margin_top_callback()
	{
		printf(
		'<p class="description">'
		);
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="ricbast_margin_top" name="ricbast_option_name[ricbast_margin_top]" value="%s" />',
			isset( $this->options['ricbast_margin_top'] ) ? esc_attr( $this->options['ricbast_margin_top']) : '90'
		);
		echo __("px.", 'ricbastbar');
		echo '</p>';
	}
	public function ricbast_margin_bot_callback(){
		printf(
		'<p class="description">'
		);
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="ricbast_margin_bot" name="ricbast_option_name[ricbast_margin_bot]" value="%s" />',
			isset( $this->options['ricbast_margin_bot'] ) ? esc_attr( $this->options['ricbast_margin_bot']) : '0'
		);
		echo __("px.", 'ricbastbar');
		echo '</p>';
	}
	public function ricbast_min_width_callback(){
		printf(
		'<p class="description">'
		);
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="ricbast_min_width" name="ricbast_option_name[ricbast_min_width]" value="%s" />',
			isset( $this->options['ricbast_min_width'] ) ? esc_attr( $this->options['ricbast_min_width']) : '753'
		);
		_e("px.", 'ricbastbar');
		echo '</p>';
	}
	public function ricbast_update_sidebar_height_callback()
	{
		printf(
		'<select id="ricbast_update_sidebar_height" name="ricbast_option_name[ricbast_update_sidebar_height]" selected="%s">',
			isset( $this->options['ricbast_update_sidebar_height'] ) ? esc_attr( $this->options['ricbast_update_sidebar_height']) : '' 
		);
		if ($this->options['ricbast_update_sidebar_height'] == 'true') {
		printf(
		'<option name="true" value="true" selected>true</option>
		<option name="false" value="">false</option>
		</select>'
		);	
		}
		if ($this->options['ricbast_update_sidebar_height'] == 'false') {
		printf(
		'<option name="true" value="true">true</option>
		<option name="false" value="" selected >false</option>
		</select>'
		);	
		}
		if ($this->options['ricbast_update_sidebar_height'] == '') {
		printf(
		'<option name="true" value="true">true</option>
		<option name="false" value="" selected >false</option>
		</select>'
		);	
		}	
		echo '<span class="description">';
		_e("Troubleshooting option, try this if your sidebar loses its background color...", 'ricbastbar');
		echo '</span>';	
	} 
	public function ricbast_enable_callback()
	{
		_e('<span>front page </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_front_home]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_front_home',
			checked( isset( $this->options['ricbast_disable_at_front_home'] ), true, false ) 
		) ;
		_e('<span>blog page </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_blog]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_blog',
			checked( isset( $this->options['ricbast_disable_at_blog'] ), true, false ) 
		);
		_e('<span>pages </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_page]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_page',
			checked( isset( $this->options['ricbast_disable_at_page'] ), true, false ) 
		);
		_e('<span>tags </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_tag]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_tag',
			checked( isset( $this->options['ricbast_disable_at_tag'] ), true, false ) 
		);
		_e('<span>categories </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_category]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_category',
			checked( isset( $this->options['ricbast_disable_at_category'] ), true, false ) 
		);
		_e('<span>posts </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_single]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_single',
			checked( isset( $this->options['ricbast_disable_at_single'] ), true, false ) 
		);
		_e('<span>archives </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_archive]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_archive',
			checked( isset( $this->options['ricbast_disable_at_archive'] ), true, false ) 
		);
		_e('<span>search </span>', 'ricbastbar');
		printf(
			'<input id="%1$s" name="ricbast_option_name[ricbast_disable_at_search]" type="checkbox" %2$s /> ',
			'ricbast_disable_at_search',
			checked( isset( $this->options['ricbast_disable_at_search'] ), true, false ) 
		);
		if  (isset ( $this->options['ricbast_disable_at_page'] ) == true )  {
			echo '<p> </p> <hr />';
			_e('<span class="">Except for this pages: </span>', 'ricbastbar');
			printf(
				'<input type="text" size="26" id="ricbast_enable_at_pages" name="ricbast_option_name[ricbast_enable_at_pages]" value="%s" /> ',  
				isset( $this->options['ricbast_enable_at_pages'] ) ? esc_attr( $this->options['ricbast_enable_at_pages']) : '' 
			); 
		 	_e('<span class="description">Comma separated list of pages to enable. It should be page name, id or slug. Example: about-us, 1134, Contact Us. Leave blank if you realy want to disable sticky sidebar for all pages.</span>', 'ricbastbar');
		}
		if  (isset ( $this->options['ricbast_disable_at_single'] ) == true )  {
			echo '<p> </p> <hr />';
			_e('<span class="">Except for this posts: </span>', 'ricbastbar');
			printf(
				'<input type="text" size="26" id="ricbast_enable_at_posts" name="ricbast_option_name[ricbast_enable_at_posts]" value="%s" /> ',  
				isset( $this->options['ricbast_enable_at_posts'] ) ? esc_attr( $this->options['ricbast_enable_at_posts']) : '' 
			); 
		 	_e('<span class="description">Comma separated list of posts to enable. It should be post name, id or slug. Example: about-us, 1134, Contact Us. Leave blank if you realy want to disable sticky sidebar for all posts.</span>', 'ricbastbar');
		}
	}
}