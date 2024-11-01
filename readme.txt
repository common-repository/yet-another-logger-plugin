=== Yet Another Logger Plugin ===
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=5QUG426XZWQSJ&lc=US&item_name=WP%20OpenSearch%20Plugin&item_number=1%2e0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: logging,debug,firephp,query,post meta,email
Requires at least: 3.0
Tested up to: 3.0.5
Stable tag: 1.0

Provides logging and debugging data via e-mail and FirePHP.

== Description ==
This plugin provides an API to send logging and debugging data via FirePHP or 
via e-mail.

It provides 3 logging functions (wp_yalp_info, wp_yalp_warning, wp_yalp_error) for 3 log levels: error, warning, info.
You can call these functions in your code (after plugin initialization) to send your data to the logging system.
These functions receive 2 parameters: the first one, mandatory, is the object that you want to log (string, array, etc.) and
the second optional plugin is a label to identify your log data.

The log data is automatically sent via the FirePHP system if the current user is
the administrator or his IP address is enabled to receive it. If configured, the
plugin also sends the log data via e-mail to the website administrator.

This plugin also provides some optional automatic logs for database queries, e-mail 
and posts meta.


== Installation ==

1. Unpack the plugin into `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Customize plugin behaviour in the settings page


== Screenshots ==

1. Main settings form for the customization of the plugin behaviour.


== Changelog ==

= 1.0 =
* Logging API via FirePHP library
* Automatic e-mail signalation
* Automatic logs for database queries, e-mails, post meta, etc.
* IP address whitelist

