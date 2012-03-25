<?php

class swpm_client_admin extends swpm_client_core
{
	public function __construct()
	{
		parent::__construct();

		add_action('admin_menu', array(&$this, 'admin_menu'));

		add_action('load-toplevel_page_swpm-client', array(&$this, 'load_swpm_client'));
		add_action('load-swpm-client', array(&$this, 'load_swpm_client'));
	}

	public function admin_menu()
	{
		add_menu_page('Serenity WP Manager', 'Serenity WP Manager', 'activate_plugins', 'swpm-client', array(&$this, 'swpm_client_page'));
		add_submenu_page('swpm-client', 'Settings', 'Settings', 'activate_plugins', 'swpm-client-settings', array(&$this, 'swpm_client_settings_page'));
	}

	public function swpm_client_page()
	{
		if (!function_exists('swpm_dashboard_setup'))
		{
			include(SWPM_PLUGIN_DIR . 'includes/class.dashboard.php');
		}

		if (!function_exists('wp_dashboard'))
		{
			include(ABSPATH . 'wp-admin/includes/dashboard.php');
		}
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2>Serenity WP Manager Dashboard</h2>

<div id="dashboard-widgets-wrap">
	<?php wp_dashboard(); ?>
	<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->
<?php
	}

	public function load_swpm_client()
	{
		if (!function_exists('swpm_dashboard_setup'))
		{
			include(SWPM_PLUGIN_DIR . 'includes/class.dashboard.php');
		}

		if (!function_exists('wp_dashboard'))
		{
			include(ABSPATH . 'wp-admin/includes/dashboard.php');
		}

		swpm_dashboard_setup();

		wp_enqueue_script('dashboard');

		add_screen_option('layout_columns', array('max' => 4, 'default' => 2));
	}

	public function swpm_client_settings_page()
	{
?>
<div class="wrap">
	<h2>Settings</h2>
</div>
<?php
	}
}

$swpm_client_admin = new swpm_client_admin();

/* EOF */