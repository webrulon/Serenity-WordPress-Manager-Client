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
?>
<div class="wrap">
	<h2>Serenity WordPress Manager</h2>
</div>
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