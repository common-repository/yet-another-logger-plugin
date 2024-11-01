<?php
/*
 Plugin Name: Yet Another Logger Plugin
 Plugin URI: http://longjohndesign.blogspot.com/2011/02/yet-another-logger-plugin-wordpress.html
 Description: Provides logging and debugging data via e-mail and FirePHP. Log data is automatically sent to the administrator and to an optional list of enabled IP address (via FirePHP). 
 Version: 1.0
 Author: LongJohn
 Author URI: http://longjohndesign.blogspot.com/
 */


add_action ( 'init', 'wp_yalp_init' );
function wp_yalp_init()
{
	require_once (dirname(__FILE__).'/logger.php');
	WP_YALP_Logger::enableAutomaticLogs();

	if (is_admin())
	{
		require_once (dirname(__FILE__).'/settings.php');
		wp_yalp_init_settings_page();
	}
}













