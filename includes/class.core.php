<?php

if (!defined('IN_SWPM'))
{
	die();
}

if (!class_exists('IXR_Client'))
{
	include(ABSPATH . WPINC . '/class-IXR.php');
}

if (!class_exists('WP_HTTP_IXR_Client'))
{
	include(ABSPATH . WPINC . '/class-wp-http-ixr-client.php');
}

class swpm_client_core
{
	public function __construct()
	{
	}
}

/* EOF */