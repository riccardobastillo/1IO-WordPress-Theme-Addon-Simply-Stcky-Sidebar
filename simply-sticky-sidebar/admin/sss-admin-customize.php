<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'customize_register', 'sss_simply_customize_register',100 );
//IT adds the comments in archives checkbox to the customize
function sss_simply_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'simply_sticky_sidebar_sec', array(
		'title' => __( 'Simply Sticky Sidebar','ricbastbar' ),
		'panel'   => 'fl-content',
		'priority' => 2
    ) );
	$wp_customize->add_setting( 'ricbast_disable_at_title',
		array( 'default' => false,
			'sanitize_callback' => 'esc_attr',
			'transport'   => 'refresh'
		)
	);	
	$wp_customize->add_control( new Sss_Disable_Title_Control( $wp_customize,'sss_disable_title', array(
		'settings' => 'ricbast_disable_at_title',
		'section' => 'simply_sticky_sidebar_sec',
		'label' => __( 'Disable on','ricbastbar' ),
	) ) );	
	foreach( array(
		'ricbast_disable_at_front_home' => __( 'Front page','ricbastbar' ),
		'ricbast_disable_at_blog' => __( 'Blog page','ricbastbar' ),
		'ricbast_disable_at_page' => __( 'Pages','ricbastbar' ), 
		'ricbast_disable_at_tag' => __( 'Tag archives','ricbastbar' ),
		'ricbast_disable_at_category' => __( 'Category archives','ricbastbar' ),
		'ricbast_disable_at_single' => __( 'Single posts','ricbastbar' ),
		'ricbast_disable_at_archive' => __( 'Archives','ricbastbar' ),
		'ricbast_disable_at_search' => __( 'Search','ricbastbar' )
	) as $k => $v ){
		$wp_customize->add_setting( $k,
			array( 'default' => false,
				'sanitize_callback' => 'esc_attr',
				'transport'   => 'refresh'
			)
		);
		$wp_customize->add_control( $k.'_ctrl', array(
			'label' => $v,
			'type' => 'checkbox',
			'settings' => $k,
			'section' => 'simply_sticky_sidebar_sec'
		) );
	}
	$wp_customize->add_setting( 'sss_disable_under_width',
		array( 'default' => false,
			'sanitize_callback' => 'esc_attr',
			'transport'   => 'refresh'
		)
	);	
	$wp_customize->add_control( 'ricbast_min_width', array(
		'label' => __( 'Disable if screen width is smaller than (px)','ricbastbar' ),
		'type' => 'number',
		'settings' => 'sss_disable_under_width',
		'section' => 'simply_sticky_sidebar_sec'
	) );	
}
//Full size control to restore the original customize window sizes
class Sss_Disable_Title_Control extends WP_Customize_Control{
	public function render_content(){
	?>
	<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
	<?php
	}
}