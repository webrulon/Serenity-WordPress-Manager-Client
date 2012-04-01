<?php

if (!defined('IN_SWPM'))
{
	die();
}

class swpm_client_dashboard_widgets
{
	public function __construct()
	{
		add_action('swpm_dashboard_setup', array(&$this, 'swpm_dashboard_setup'));
	}

	public function swpm_dashboard_setup()
	{
		wp_add_dashboard_widget('test-widget-1', 'Test Widget 1', array(&$this, 'swpm_test_widget_1'));
		wp_add_dashboard_widget('test-widget-2', 'Test Widget 2', array(&$this, 'swpm_test_widget_2'));

		global $wp_meta_boxes;

		$test_widget_2 = $wp_meta_boxes['dashboard']['normal']['core']['test-widget-2'];
		unset($wp_meta_boxes['dashboard']['normal']['core']['test-widget-2']);
		$wp_meta_boxes['dashboard']['side']['core']['test-widget-2'] = $test_widget_2;
	}

	public function swpm_test_widget_1()
	{
		echo 'Test Widget 1';
	}

	public function swpm_test_widget_2()
	{
		echo 'Test Widget 2';
	}
}

$swpm_client_dashboard_widgets = new swpm_client_dashboard_widgets();

function swpm_dashboard_setup()
{
	global $wp_registered_widgets, $wp_registered_widget_controls, $wp_dashboard_control_callbacks;
	$wp_dashboard_control_callbacks = array();
	$screen = get_current_screen();

	$update = false;
	$widget_options = get_option('swpm_dashboard_widget_options');
	if (!$widget_options || !is_array($widget_options))
	{
		$widget_options = array();
	}

	/* Register Widgets and Controls */

	// Hook to register new widgets
	// Filter widget order
	if (is_network_admin())
	{
		do_action('swpm_network_dashboard_setup');
		$dashboard_widgets = apply_filters('swpm_network_dashboard_widgets', array());
	}
	elseif (is_user_admin())
	{
		do_action('swpm_user_dashboard_setup');
		$dashboard_widgets = apply_filters('swpm_user_dashboard_widgets', array());
	}
	else
	{
		do_action('swpm_dashboard_setup');
		$dashboard_widgets = apply_filters('swpm_dashboard_widgets', array());
	}

	foreach ($dashboard_widgets as $widget_id)
	{
		$name = empty($wp_registered_widgets[$widget_id]['all_link']) ? $wp_registered_widgets[$widget_id]['name'] : $wp_registered_widgets[$widget_id]['name'] . " <a href='{$wp_registered_widgets[$widget_id]['all_link']}' class='edit-box open-box'>" . __('View all') . '</a>';
		wp_add_dashboard_widget($widget_id, $name, $wp_registered_widgets[$widget_id]['callback'], $wp_registered_widget_controls[$widget_id]['callback']);
	}

	if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['widget_id']))
	{
		ob_start(); // hack - but the same hack wp-admin/widgets.php uses
		wp_dashboard_trigger_widget_control($_POST['widget_id']);
		ob_end_clean();
		wp_redirect(remove_query_arg('edit'));
		exit;
	}

	if ($update)
	{
		update_option('swpm_dashboard_widget_options', $widget_options);
	}

	do_action('do_meta_boxes', $screen->id, 'normal', '');
	do_action('do_meta_boxes', $screen->id, 'side', '');
}

/*add_action('swpm_dashboard_setup', '_swpm_dashboard_setup');
function _swpm_dashboard_setup()
{
	wp_add_dashboard_widget('test-widget-1', 'Test Widget 1', 'swpm_test_widget_1');
	wp_add_dashboard_widget('test-widget-2', 'Test Widget 2', 'swpm_test_widget_2');

	global $wp_meta_boxes;

	$test_widget_2 = $wp_meta_boxes['dashboard']['normal']['core']['test-widget-2'];
	unset($wp_meta_boxes['dashboard']['normal']['core']['test-widget-2']);
	$wp_meta_boxes['dashboard']['side']['core']['test-widget-2'] = $test_widget_2;
}

function swpm_test_widget_1()
{
	echo 'Test Widget 1';
}

function swpm_test_widget_2()
{
	echo 'Test Widget 2';
}*/

/* EOF */