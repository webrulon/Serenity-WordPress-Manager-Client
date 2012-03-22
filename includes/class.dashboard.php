<?php

if (!defined('IN_SWPM'))
{
	die();
}

class swpm_client_admin_dashboard
{
	public function __construct()
	{
		global $swpm_registered_widgets, $swpm_registered_widget_controls, $swpm_dashboard_control_callbacks;

		$swpm_dashboard_control_callbacks = array();
		$screen = get_current_screen();

		$update = false;
		$widget_options = get_option('swpm_dashboard_widget_options');
		if (!$widget_options || !is_array($widget_options))
		{
			$widget_options = array();
		}

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
			$name = empty($swpm_registered_widgets[$widget_id]['all_link']) ? $swpm_registered_widgets[$widget_id]['name'] : $swpm_registered_widgets[$widget_id]['name'] . " <a href='{$swpm_registered_widgets[$widget_id]['all_link']}' class='edit-box open-box'>" . __('View all') . '</a>';
			$this->add_dashboard_widget($widget_id, $name, $swpm_registered_widgets[$widget_id]['callback'], $swpm_registered_widget_controls[$widget_id]['callback']);
		}

		if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['widget_id']))
		{
			ob_start();
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

	public function add_dashboard_widget($widget_id, $widget_name, $callback, $control_callback = null)
	{
		global $swpm_dashboard_control_callbacks;

		$screen = get_current_screen();

		if ($control_callback && current_user_can('edit_dashboard') && is_callable($control_callback))
		{
			$swpm_registered_widgets[$widget_id] = $control_callback;

			if (isset($_GET['edit']) && $widget_id == $_GET['edit'])
			{
				list($url) = explode('#', add_query_arg('edit', false), 2);
				$widget_name .= ' <span class="postbox-title-action"><a href="' . esc_url($url) . '">' . __('Cancel') . '</a></span>';
				$callback = array(&$this, '_dashboard_control_callback');
			}
			else
			{
				list($url) = explode('#', add_query_arg('edit', $widget_id), 2);
				$widget_name .= ' <span class="postbox-title-action"><a href="' . esc_url("$url#$widget_id") . '" class="edit-box open-box">' . __('Configure') . '</a></span>';
			}
		}

		if (is_blog_admin())
		{
			$side_widgets = array('dashboard_quick_press', 'dashboard_recent_drafts', 'dashboard_primary', 'dashboard_secondary');
		}
		else if (is_network_admin())
		{
			$side_widgets = array('dashboard_primary', 'dashboard_secondary');
		}
		else
		{
			$side_widgets = array();
		}

		$location = 'normal';
		if (in_array($widget_id, $side_widgets))
		{
			$location = 'side';
		}

		$priority = 'core';

		add_meta_box($widget_id, $widget_name, $callback, $screen, $location, $priority);
	}

	public function dashboard()
	{
		global $screen_layout_columns;

		$screen = get_current_screen();

		$hide2 = $hide3 = $hide4 = '';
		switch ($screen_layout_columns)
		{
			case 4:
				$width = 'width:25%;';
			break;

			case 3:
				$width = 'width:33.333333%;';
				$hide4 = 'display:none;';
			break;

			case 2:
				$width = 'width:50%;';
				$hide3 = $hide4 = 'display:none;';
			break;

			default:
				$width = 'width:100%;';
				$hide2 = $hide3 = $hide4 = 'display:none;';
		}
	?>
	<div id="dashboard-widgets" class="metabox-holder">
	<?php
		echo "\t<div id='postbox-container-1' class='postbox-container' style='$width'>\n";
		do_meta_boxes($screen->id, 'normal', '');

		echo "\t</div><div id='postbox-container-2' class='postbox-container' style='{$hide2}$width'>\n";
		do_meta_boxes($screen->id, 'side', '');

		echo "\t</div><div id='postbox-container-3' class='postbox-container' style='{$hide3}$width'>\n";
		do_meta_boxes($screen->id, 'column3', '');

		echo "\t</div><div id='postbox-container-4' class='postbox-container' style='{$hide4}$width'>\n";
		do_meta_boxes($screen->id, 'column4', '');
	?>
	</div></div>

	<form style="display:none" method="get" action="">
		<p>
	<?php
		wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
		wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
	?>
		</p>
	</form>

	<?php
	}

	private function _dashboard_control_callback($dashboard, $meta_box)
	{
		echo '<form action="" method="post" class="dashboard-widget-control-form">';
		wp_dashboard_trigger_widget_control($meta_box['id']);
		echo '<input type="hidden" name="widget_id" value="' . esc_attr($meta_box['id']) . '" />';
		submit_button(__('Submit'));
		echo '</form>';
	}
}

function swpm_add_dashboard_widget($widget_id, $widget_name, $callback, $control_callback = null)
{
	swpm_client_admin_dashboard::add_dashboard_widget($widget_id, $widget_name, $callback, $control_callback);
}

swpm_add_dashboard_widget('test', 'Test', 'swpm_dashboard_test');
function swpm_dashboard_test()
{
	echo 'test';
}

/* EOF */