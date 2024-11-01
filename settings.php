<?php


function wp_yalp_init_settings_page()
{
	add_filter ( 'plugin_action_links', 'wp_yalp_action_links', 10, 2 );
	add_action ( 'admin_menu', 'wp_yalp_admin_menu' );
	
	if (isset($_POST['wp_yalp_save']))
	{
		wp_yalp_save_options();
	} elseif(isset($_GET['wp_yalp_reset']))
	{
		wp_yalp_reset_options();
	}
}



function wp_yalp_admin_menu()
{
	add_options_page('Yet Another Logger Plugin', 'Yet Another Logger Plugin', 10, 'wp-yalp', 'wp_yalp_options_page');
}


function wp_yalp_options_page()
{
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Yet Another Logger Plugin</h2>
		<?php if (isset($_GET['wp_yalp_updated']) and $_GET['wp_yalp_updated']):?>
			<div class="updated">
				<p><strong><?php _e('Settings saved.');?></strong></p>
			</div>
		<?php endif; ?>
		<?php if (isset($_GET['wp_yalp_resetted']) and $_GET['wp_yalp_resetted']):?>
			<div class="updated">
				<p><strong><?php _e('Settings resetted.');?></strong></p>
			</div>
		<?php endif; ?>
		<div class="metabox-holder has-right-sidebar">
			<div class="inner-sidebar">
				<div style="position:relative;" class="meta-box-sortabless ui-sortable" id="side-sortables">
					<div class="postbox" id="dm_donations">
						<h3 class="hndle"><span><?php _e('Make a donation'); ?></span></h3>
						<div class="inside">
							<p style="text-align:center;"><strong><?php _e('Thanks for your support!');?></strong></p>
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="text-align:center;">
								<input type="text" name="amount" value="2.00" style="width:50px;text-align:right;"/>$
								<input type="hidden" name="cmd" value="_donations"/>
								<input type="hidden" name="business" value="5QUG426XZWQSJ"/>
								<input type="hidden" name="lc" value="US"/>
								<input type="hidden" name="item_name" value="Yet Another Logger Plugin"/>
								<input type="hidden" name="item_number" value="1.0"/>
								<input type="hidden" name="currency_code" value="USD"/>
								<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted"/>
								<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="vertical-align:middle;"/>
								<img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1"/>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<form method="post" action="options-general.php?page=wp-yalp">
				<div class="has-sidebar sm-padded">
					<div class="has-sidebar-content" id="post-body-content">
						<div class="meta-box-sortabless">
							<div class="postbox">
								<h3 class="hndle"><?php _e('General Configuration'); ?></h3>
								<div class="inside">
									<table class="form-table" style="clear:none;">
										<tbody>
											<?php $enabled_ip = WP_YALP_Logger::getConfig('enabled_ip');?>
											<tr valign="top">
												<th scope="row"><label for="enabled_ip"><?php _e('Enabled IP Adresses'); ?>:</label></th>
												<td>
													<input type="text" id="enabled_ip" name="enabled_ip" class="regular-text" value="<?php echo is_array($enabled_ip) ? implode(',', $enabled_ip) : $enabled_ip; ?>"/><br/>
													<span class="description"><?php _e('One or more IP adresses (comma separated) that are enabled to receive the FirePHP log data.'); ?></span>
												</td>
											</tr>
											<?php $email_log_levels = WP_YALP_Logger::getConfig('email_log_levels', array());?>
											<tr valign="top">
												<th scope="row"><label><?php _e('E-Mail Log Levels'); ?>:</label></th>
												<td>
													<label><input type="checkbox" id="email_log_levels_error" name="email_log_levels[]" class="regular-text code" value="error" <?php echo in_array('error', $email_log_levels) ? 'checked="checked"' : ''; ?>/> Error</label>
													<label><input type="checkbox" id="email_log_levels_warning" name="email_log_levels[]" class="regular-text code" value="warning" <?php echo in_array('warning', $email_log_levels) ? 'checked="checked"' : ''; ?>/> Warning</label>
													<label><input type="checkbox" id="email_log_levels_info" name="email_log_levels[]" class="regular-text code" value="info" <?php echo in_array('info', $email_log_levels) ? 'checked="checked"' : ''; ?>/> Info</label>
													<br/><span class="description"><?php _e('Send an e-mail to the website administrator (' . get_bloginfo('admin_email') . ') for the selected log levels. Enabling Info level will produce a large amount of e-mails.');?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="postbox">
								<h3 class="hndle"><?php _e('Automatic Logs'); ?></h3>
								<div class="inside">
									<table class="form-table" style="clear:none;">
										<tbody>
											<?php $automatic_logs = WP_YALP_Logger::getConfig('automatic_logs', array());?>
											<tr valign="top">
												<th scope="row"><label for="automatic_logs_query"><?php _e('Database query'); ?>:</label></th>
												<td>
													<input type="checkbox" id="automatic_logs_query" name="automatic_logs[]" class="regular-text code" value="query" <?php echo in_array('query', $automatic_logs) ? 'checked="checked"' : ''; ?>/>
													<?php _e('Send log message when a database query is executed.');?>
												</td>
											</tr>
											<tr valign="top">
												<th scope="row"><label for="automatic_logs_email"><?php _e('E-Mail'); ?>:</label></th>
												<td>
													<input type="checkbox" id="automatic_logs_email" name="automatic_logs[]" class="regular-text code" value="email" <?php echo in_array('email', $automatic_logs) ? 'checked="checked"' : ''; ?>/>
													<?php _e('Send log message when an e-mail is sent via the wp_mail function.');?>
												</td>
											</tr>
											<tr valign="top">
												<th scope="row"><label for="automatic_logs_post_meta"><?php _e('Post Meta'); ?>:</label></th>
												<td>
													<input type="checkbox" id="automatic_logs_post_meta" name="automatic_logs[]" class="regular-text code" value="post_meta" <?php echo in_array('post_meta', $automatic_logs) ? 'checked="checked"' : ''; ?>/>
													<?php _e('Send post meta data for single pages and posts.');?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<p>
								<input type="submit" value="<?php esc_attr_e('Save Changes') ?>" class="button-primary" name="wp_yalp_save"/>
								<a class="button" href="options-general.php?page=wp-yalp&wp_yalp_reset=1"><?php _e('Reset') ?></a>
							</p>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
}


function wp_yalp_action_links($links, $file)
{
	static $this_plugin;
	
	if( empty($this_plugin) ) $this_plugin = plugin_basename(dirname(__FILE__));
	

	if ( $file == "{$this_plugin}/{$this_plugin}.php" ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=wp-yalp' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}


function wp_yalp_save_options()
{
	$config = array();
	$options = array('enabled_ip', 'email_log_levels', 'automatic_logs');
	foreach($options as $option)
	{
		switch ($option)
		{
			case 'email_log_levels':
			case 'automatic_logs':
				if (!isset($_POST[$option]) or !is_array($_POST[$option]))
				{
					$config[$option] = array();
				} else {
					$config[$option] = $_POST[$option];
				}
				break;
			case 'enabled_ip':
				if (!strlen($_POST[$option]))
				{
					$config[$option] = '';
				} else 
				{
					$enabled_ip = explode(',', trim($_POST[$option], ' ,'));
					for ($i = 0; $i < count($enabled_ip); $i++)
					{
						$enabled_ip[$i] = trim($enabled_ip[$i]);
					}
					$config[$option] = $enabled_ip;
				}
		}
	}
	WP_YALP_Logger::setConfig($config);
	header('Location: options-general.php?page=wp-yalp&wp_yalp_updated=1');
	exit();
}



function wp_yalp_reset_options()
{
	$config = array(
		'enabled_ip' => '',
		'email_log_levels' => array(),
		'automatic_logs' => array()
	);
	WP_YALP_Logger::setConfig($config);
	header('Location: options-general.php?page=wp-yalp&wp_yalp_resetted=1');
	exit();
}





