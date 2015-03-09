<?php

/*
Plugin Name: Alternative CSS
Plugin URI: http://tommicarleman.net
Description: A simple way to add global CSS (new item under Settings->General)
Version: 0.1
Author: Tommi Carleman
Author URI: http://tommicarleman.net
License: GPL2
*/

namespace Tommica\Altcss;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

// Add menu item
add_action( 'admin_menu', __NAMESPACE__ . '\\add_admin_menu' );

// Init settings
add_action( 'admin_init', __NAMESPACE__ . '\\settings_init' );

// Enable codemirror
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\add_codemirror' );

// Render CSS
add_action( 'wp_enqueue_scripts', __NAMESPACE__.'\\render_head_css', PHP_INT_MAX );

/*
 * Adds codemirror to the css field
 */
function add_codemirror( $hook ) {
	wp_enqueue_style( 'altcss-codemirror-css', plugins_url( 'lib/codemirror.css', __FILE__ ) );
	wp_enqueue_style( 'altcss-codemirror-css-mbo', plugins_url( 'lib/codemirror-mbo.css', __FILE__ ) );
	wp_enqueue_script( 'altcss-codemirror', plugins_url( 'lib/codemirror-compressed.js', __FILE__ ) );
	wp_enqueue_script( 'altcss-codemirror-init', plugins_url( 'lib/init.js', __FILE__ ), array(), '1.0.0', true );
}

/*
 * Add a sub menu to the tools section
 */
function add_admin_menu() {
	add_submenu_page( 'tools.php', 'Altcss', 'Altcss', 'manage_options', 'altcss', __NAMESPACE__ . '\\options_page' );
}

/*
 * Add setting fields
 */
function settings_init() {
	register_setting( 'pluginPage', 'altcss_settings' );

	add_settings_section(
		'altcss_pluginPage_section',
		__( '', 'altcss' ),
		__NAMESPACE__ . '\\settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'altcss_textarea_field_0',
		__( 'Add your css here', 'altcss' ),
		__NAMESPACE__ . '\\textarea_field_0_render',
		'pluginPage',
		'altcss_pluginPage_section'
	);
}

/*
 * Render the css edit field
 */
function textarea_field_0_render() {
	$options = get_option( 'altcss_settings' );
	?>
	<textarea id="extracss" name='altcss_settings[altcss_textarea_field_0]'><?php echo $options['altcss_textarea_field_0']; ?></textarea>
<?php
}

/*
 * Render the CSS in every pages head
 */
function render_head_css() {
	$options = get_option( 'altcss_settings' );

	wp_enqueue_style( 'extracss-head', plugins_url( 'lib/blank.css', __FILE__ ) );
	wp_add_inline_style('extracss-head', $options['altcss_textarea_field_0']);
}

/*
 * Title
 */
function settings_section_callback() {
	echo __( '', 'altcss' );
}

/*
 * Render the options page
 */
function options_page() {
	?>
	<form action='options.php' method='post'>

		<h2>Altcss</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
<?php
}

?>