<?php
/**
 * Plugin Name: CPJ Calendar Scheduler
 * Plugin URI: https://cpauljarvis.com
 * Description: A plugin that manages and provides user interface for making appointments thru a calendar
 * Version: 1.0.0
 * Author: Paul Jarvis
 * Author URI: https://cpauljarvis.com
 * Requires: 6.9
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

use CPJ\CalendarScheduler\Plugin;

define( 'CPJ_CAL_SCHED_PLUGIN_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'CPJ_CAL_SCHED_PLUGIN_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CPJ_CAL_SCHED_PLUGIN_SLUG', plugin_basename( __FILE__ ) );

const CPJ_CAL_SCHED_DIR         = __DIR__;
const CPJ_CAL_SCHED_PLUGIN_FILE = __FILE__;

require_once CPJ_CAL_SCHED_DIR . '/vendor/autoload.php';

if ( class_exists( 'CPJ\CalendarScheduler\Plugin' ) ) {
	$cpjCalSchedPlugin = new Plugin();
    //echo 'Plugin loaded';
} else {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>CPJ Calendar Scheduler plugin did not load</p></div>';
		}
	);
	//wp_die();
}
