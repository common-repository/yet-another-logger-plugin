<?php


class WP_YALP_Logger
{
	
	const CONFIG_OPTION_NAME = 'wp_yalp_config';
	
	protected static $config = null;
	
	protected static $sendingLogEmail = FALSE;
	
	
	static function getConfig($what = null, $default = FALSE)
	{
		if (is_null(self::$config))
			self::$config = get_option(self::CONFIG_OPTION_NAME);
		
		if ($what)
			return (isset(self::$config[$what]) ? self::$config[$what] : $default);
		
		return self::$config;
	}
	
	
	static function setConfig($what = null, $value = null)
	{
		if(is_array($what))
		{
			$config = $what;
			update_option(self::CONFIG_OPTION_NAME, $config);
			return;
		}
		
		if (!is_string($what))
			return;
		
		$config = self::getConfig($what);
		$config[$what] = $value;
		self::setConfig($config);
	}
	
	
	protected static function loadFirePHP()
	{
		if (!class_exists('FB'))
		{
			require_once( dirname(__FILE__) . '/FirePHPCore/fb.php' );
		}
	}
	
	
	protected static function log($function, $object, $label = null)
	{
		if (self::sendFirePHPLogs())
		{
			self::loadFirePHP();
			try {
				call_user_func(array('FB', $function), $object, $label);
			} catch (Exception $e) {}
		}
		
		if (self::sendEmailLogs($function))
		{
			$admin_email = get_bloginfo('admin_email');
			$message = is_string($object) ? $object : print_r($object, TRUE);
			
			self :: setSendingLogEmail( TRUE );
			wp_mail ( $admin_email, get_bloginfo('name').' - '.$label, $message );
			self :: setSendingLogEmail( FALSE );
		}
	}
	
	
	protected static function setSendingLogEmail($bool)
	{
		self::$sendingLogEmail = $bool;
	}
	
	
	protected static function getSendingLogEmail()
	{
		return self::$sendingLogEmail;
	}
	
	
	protected static function sendFirePHPLogs()
	{
		if (is_super_admin())
			return TRUE;
		
		$enabled_ip = self::getConfig('enabled_ip');
		if (!is_array($enabled_ip))
			$enabled_ip = array($enabled_ip);
		
		if (strlen($_SERVER['REMOTE_ADDR']) and in_array($_SERVER['REMOTE_ADDR'], $enabled_ip))
			return TRUE;
		
		return FALSE;
	}
	
	
	protected static function sendEmailLogs($level)
	{
		$email_log_levels = self :: getConfig('email_log_levels', array());
		return in_array($level, $email_log_levels);
	}
	
	
	static function info($object, $label = 'INFO')
	{
		self::log('info', $object, $label);
	}
	
	
	static function warning($object, $label = 'WARNING')
	{
		self::log('warn', $object, $label);
	}
	
	
	static function error($object, $label = 'ERROR')
	{
		self::log('error', $object, $label);
	}
	
	
	static function getAvailableAutomaticLogs()
	{
		return array(
			'query' => 'filter:query', 
			'email' => 'filter:wp_mail',
			'post_meta' => 'action:wp'
		);
	}
	
	
	static function enableAutomaticLogs()
	{
		$automatic_logs = self :: getConfig('automatic_logs', array());
		
		$available_automatic_logs = self::getAvailableAutomaticLogs();
		foreach ($available_automatic_logs as $log_type => $log_config)
		{
			if (in_array($log_type, $automatic_logs))
			{
				$log_config = explode(':', $log_config);
				self::enableAutomaticLog($log_type, $log_config);
			}
		}	
	}
	
	
	protected static function enableAutomaticLog($log_type, $log_config)
	{
		if (!$log_config)
			return;
		
		switch ($log_config[0])
		{
			case 'filter':
				add_filter($log_config[1], array(__CLASS__, "automatic_log_{$log_type}"));
				break;
			case 'action':
				add_action($log_config[1], array(__CLASS__, "automatic_log_{$log_type}"));
				break;
		}
	}
	
	
	static function automatic_log_query($query)
	{
		self::info ( $query, 'QUERY' );
		return $query;
	}
	
	
	static function automatic_log_email($data)
	{
		if (!self::getSendingLogEmail())
			self::info($data, 'E-MAIL');
		return $data;
	}
	
	
	static function automatic_log_post_meta()
	{
		if ( is_single() or is_page() )
		{
			global $post;
			self::info(get_post_meta($post->ID, null), 'POST-META');
		}
	}
	
	
}






function wp_yalp_info($object, $label = 'INFO')
{
	WP_YALP_Logger::info($object, $label);
}

function wp_yalp_warning($object, $label = 'WARNING')
{
	WP_YALP_Logger::warning($object, $label);
}

function wp_yalp_error($object, $label = 'ERROR')
{
	WP_YALP_Logger::error($object, $label);
}


