<?php

class swpm_client_admin extends swpm_client_core
{
	public function __construct()
	{
		parent::__construct();

		add_action('admin_menu', array(&$this, 'admin_menu'));
	}

	public function admin_menu()
	{
		add_menu_page('Serenity WP Manager', 'Serenity WP Manager', 'activate_plugins', 'swpm-client', array(&$this, 'swpm_client_page'));
		add_submenu_page('swpm-client', 'Settings', 'Settings', 'activate_plugins', 'swpm-client-settings', array(&$this, 'swpm_client_settings_page'));
	}

	public function swpm_client_page()
	{
		if (!class_exists('swpm_client_admin_dashboard'))
		{
			include(SWPM_PLUGIN_DIR . 'includes/class.dashboard.php');
		}

		$swpm_dashboard = new swpm_client_admin_dashboard();

		add_screen_option('swpm_layout_columns', array('max' => 4, 'default' => 2));
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2>Serenity WP Manager Dashboard</h2>

<div id="dashboard-widgets-wrap">
	<?php $swpm_dashboard->dashboard(); ?>
	<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->
<?php
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