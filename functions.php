<?php

register_activation_hook(plugin_dir_path(__FILE__) . '/swpm_client.php', 'swpm_client_activate');
function swpm_client_activate()
{
	global $wpdb;

	// Create tables and data
	$table_name = $wpdb->prefix . 'site_groups';
	$sql = "CREATE TABLE $table_name (
		ID mediumint(9) unsigned NOT NULL auto_increment,
		group_name varchar(255) NOT NULL default '',
		PRIMARY KEY (ID)
	);";
	$wpdb->query($sql);

	$table_name = $wpdb->prefix . 'sites';
	$sql = "CREATE TABLE $table_name (
		ID int(11) unsigned NOT NULL auto_increment,
		site_url varchar(255) NOT NULL default '',
		site_user varchar(255) NOT NULL default '',
		site_key varchar(255) NOT NULL default '',
		PRIMARY KEY (ID)
	);";
	$wpdb->query($sql);

	// Set version
	add_option('swpm_client_version', SWPM_CLIENT_VERSION, '', 'no');
}

register_deactivation_hook(plugin_dir_path(__FILE__) . '/swpm_client.php', 'swpm_client_deactivate');
function swpm_client_deactivate()
{
	global $wpdb;

	$wpdb->query('DROP TABLE ' . $wpdb->prefix . 'site_groups');
	$wpdb->query('DROP TABLE ' . $wpdb->prefix . 'sites');

	delete_option('swpm_client_version');
}

add_action('plugins_loaded', 'swpm_client_update');
function swpm_client_update()
{
	global $wpdb;

	$version = get_option('swpm_client_version');

	if (false !== $version && $version != SWPM_CLIENT_VERSION)
	{
		// Update
		switch ($version)
		{
			default:
			break;
		}
	}
}

/* EOF */