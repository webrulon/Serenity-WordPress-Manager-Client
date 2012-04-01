<?php
/*
Plugin Name: Serenity WordPress Manager
Plugin URI: http://localhost/
Description: Easily manage multiple WordPress installations from one interface.
Version: 1.0.0
Author: Kevin Murek
Author URI: http://localhost/
License: GPLv2 or later
*/

define('SWPM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SWPM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('IN_SWPM', true);
define('SWPM_CLIENT_VERSION', '1.0.0');

include_once(SWPM_PLUGIN_DIR . 'functions.php');
include_once(SWPM_PLUGIN_DIR . 'includes/class.helper.php');
include_once(SWPM_PLUGIN_DIR . 'includes/class.core.php');
include_once(SWPM_PLUGIN_DIR . 'includes/class.admin.php');

/* EOF */