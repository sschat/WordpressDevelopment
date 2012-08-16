<?php
// Direct calls to this file are Forbidden when core files are not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

/*	The Copyright, AITpro Software Products License Information and Credit Where Credit Is Due information below must remain
	intact or all BulletProof Security Pro warranties, guarantees, liabilities are void.
	
	Copyright (C) 2011 Edward Alexander, AIT-pro.com. All rights reserved.

	AITpro Software Products License Information:
	BY DOWNLOADING, INSTALLING, COPYING, ACCESSING, OR USING BulletProof Security Pro YOU AGREE TO THE TERMS OF THIS AGREEMENT. 
	IF YOU 	ARE ACCEPTING THESE TERMS ON BEHALF OF ANOTHER PERSON OR A COMPANY OR OTHER LEGAL ENTITY, YOU REPRESENT AND WARRANT
	THAT YOU HAVE FULL AUTHORITY TO BIND THAT PERSON, COMPANY, OR LEGAL ENTITY TO THESE TERMS. IF YOU DO NOT AGREE TO THESE TERMS,
	* DO NOT DOWNLOAD, INSTALL, COPY, ACCESS, OR USE BulletProof Security Pro; AND
	* PROMPTLY RETURN BulletProof Security Pro TO THE PARTY FROM WHOM YOU ACQUIRED IT. IF YOU DOWNLOADED BulletProof Security Pro
	FROM THE AITPRO WEBSITE, CONTACT AITPRO FOR A REFUND IF APPLICABLE.
	
	AITpro Software Products License Information continued:
	You agree to keep the AITpro Software Products License for BulletProof Security Pro, unmodified or altered in any way,
	with the original copy of BulletProof Security Pro that you have and any and all copies or partial copies of BulletProof
	Security Pro that You make. 

	Credit Where Credit Is Due:
	Bonus Code:
	The following bonus code scripts, snippets or example code do not make up the core coding of BulletProof Security Pro 
	and are not included in the price of BulletProof Security Pro as they are free code scripts, snippets or example code 
	and are added as Bonus Code features to BulletProof Security Pro. Bonus Code has been adapted, modified and recoded 
	to work for WordPress and BPS Pro where necessary.
	Maintenance Mode countdown timer code - Dynamic Countdown script - © Dynamic Drive
	DB String Finder code - AnyWhereInDB - author Nafis Ahmad
	DB Table Cleaner/Remover code - Copyright (c) 2009 Lester "GaMerZ" Chan
*/

function bulletproof_security_admin_init() {
	// whitelist BPS DB options 
	register_setting('bulletproof_security_options', 'bulletproof_security_options', 'bulletproof_security_options_validate');
	register_setting('bulletproof_security_options2', 'bulletproof_security_options2', 'bulletproof_security_options_validate2');
	register_setting('bulletproof_security_options_maint', 'bulletproof_security_options_maint', 'bulletproof_security_options_validate_maint');
	register_setting('bulletproof_security_options_mynotes', 'bulletproof_security_options_mynotes', 'bulletproof_security_options_validate_mynotes');
	register_setting('bulletproof_security_options_flock', 'bulletproof_security_options_flock', 'bulletproof_security_options_validate_flock');
	register_setting('bulletproof_security_options_activation', 'bulletproof_security_options_activation', 'bulletproof_security_options_validate_activation');
	register_setting('bulletproof_security_options_monitor', 'bulletproof_security_options_monitor', 'bulletproof_security_options_validate_monitor');
	register_setting('bulletproof_security_options_email', 'bulletproof_security_options_email', 'bulletproof_security_options_validate_email');
	register_setting('bulletproof_security_options_elog', 'bulletproof_security_options_elog', 'bulletproof_security_options_validate_elog');
		
	// Register BPS js
	wp_register_script( 'bps-js', WP_PLUGIN_URL . '/bulletproof-security/admin/js/bulletproof-security-admin.js');
	wp_register_script( 'bps-js-license', WP_PLUGIN_URL . '/bulletproof-security/admin/js/bpspro-license.jar');
				
	// Register BPS stylesheet
	wp_register_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));

	// Create BPS Backup Folder structure - suppressing errors on activation - errors displayed in HUD
	if( !is_dir (WP_CONTENT_DIR . '/bps-backup')) {
		@mkdir (WP_CONTENT_DIR . '/bps-backup/master-backups', 0755, true);
		@chmod (WP_CONTENT_DIR . '/bps-backup/', 0755);
		@chmod (WP_CONTENT_DIR . '/bps-backup/master-backups/', 0755);
	}
	
	// Load scripts and styles only on BPS specified pages
	add_action('load-bulletproof-security/admin/options.php', 'bulletproof_security_load_settings_page');
	add_action('load-bulletproof-security/admin/php/php-options.php', 'bulletproof_security_load_settings_page');
	add_action('load-bulletproof-security/admin/monitor/monitor.php', 'bulletproof_security_load_settings_page_monitor');
	add_action('load-bulletproof-security/admin/tools/tools.php', 'bulletproof_security_load_settings_page_tools');
	add_action('load-bulletproof-security/admin/install/installation.php', 'bulletproof_security_load_settings_page_install');
	add_action('load-bulletproof-security/admin/activation/activation.php', 'bulletproof_security_load_settings_page_activation');
	add_action('load-bulletproof-security/admin/lock/flock.php', 'bulletproof_security_load_settings_page_lock');

}

// BPS Menus
function bulletproof_security_admin_menu() {
	if (is_multisite() && !is_super_admin()) {
		$bpsSuperAdminsError = 'Only Super Admins can access BPS Pro';
		return $bpsSuperAdminsError;
		} else {
	//if (function_exists('add_menu_page')){
	add_menu_page(__('BulletProof Pro Security Settings', 'bulletproof-security'), __('BPS Pro', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/options.php', '', plugins_url('bulletproof-security/admin/images/bps-icon-small.png'));
	add_submenu_page('bulletproof-security/admin/options.php', __('B-Core ~ BPS Pro htaccess Core', 'bulletproof-security'), __('B-Core', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/options.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('P-Security ~ BPS Pro php.ini Security and Performance', 'bulletproof-security'), __('P-Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/php/php-options.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('S-Monitor ~ BPS Pro Security Monitor and Alerting', 'bulletproof-security'), __('S-Monitor', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/monitor/monitor.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('Pro-Tools ~ BPS Pro Tools', 'bulletproof-security'), __('Pro-Tools', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/tools/tools.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('F-Lock ~ BPS Pro File Lock', 'bulletproof-security'), __('F-Lock', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/lock/flock.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('BPS Pro Activation', 'bulletproof-security'), __('Activation', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/activation/activation.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('Installation and Backup', 'bulletproof-security'), __('Install / Backup', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/install/installation.php' );
}}
$bpspro_url = 'http://api.ait-pro.com/';

// Loads Settings for H-Core and P-Security - Enqueue BPS scripts and styles
function bulletproof_security_load_settings_page() {
	global $bulletproof_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('bps-js');
	wp_enqueue_script('bps-js-license');
	  	
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));
}

// Loads Settings for S-Monitor - Enqueue BPS scripts and styles
function bulletproof_security_load_settings_page_monitor() {
	global $bulletproof_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('bps-js');
	wp_enqueue_script('bps-js-license');
	   	
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));
}

// Loads Settings for Pro-Tools - Enqueue BPS scripts and styles
function bulletproof_security_load_settings_page_tools() {
	global $bulletproof_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('bps-js');
	wp_enqueue_script('bps-js-license');
	   	
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));
}

// Loads Settings for F-Lock - Enqueue BPS scripts and styles
function bulletproof_security_load_settings_page_lock() {
	global $bulletproof_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('bps-js');
	wp_enqueue_script('bps-js-license');
	   	
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));
}

// Loads Settings for BPS Pro Installation and Backup - Enqueue BPS scripts and styles
function bulletproof_security_load_settings_page_install() {
	global $bulletproof_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('bps-js');
	wp_enqueue_script('bps-js-license');
	   	
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));
}

// Loads Settings for BPS Pro Activation - Enqueue BPS scripts and styles
function bulletproof_security_load_settings_page_activation() {
	global $bulletproof_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('bps-js');
	   	
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin.css'));
}

function bulletproof_security_install() {
	global $bulletproof_security;
	$previous_install = get_option('bulletproof_security_options');
	if ( $previous_install ) {
	if ( version_compare($previous_install['version'], '5.1.4', '<') )
	remove_role('denied');
	}
}

// unregister_setting( $option_group, $option_name, $sanitize_callback );

function bulletproof_security_uninstall() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php');
	$options = get_option('bulletproof_security_options');
	delete_option('bulletproof_security_options');
}

// Validate BPS options - PHP.ini File Manager 
function bulletproof_security_options_validate($input) {  
	$options = get_option('bulletproof_security_options');  
	$options['bpsinifiles_input_1'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_1']));
	$options['bpsinifiles_input_2'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_2']));
	$options['bpsinifiles_input_3'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_3']));
	$options['bpsinifiles_input_4'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_4']));
	$options['bpsinifiles_input_5'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_5']));
	$options['bpsinifiles_input_6'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_6']));
	$options['bpsinifiles_input_7'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_7']));
	$options['bpsinifiles_input_8'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_8']));
	$options['bpsinifiles_input_9'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_9']));
	$options['bpsinifiles_input_10'] = trim(wp_filter_nohtml_kses($input['bpsinifiles_input_10']));
	$options['bpsinifiles_input_1_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_1_label']);
	$options['bpsinifiles_input_2_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_2_label']); 
	$options['bpsinifiles_input_3_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_3_label']); 
	$options['bpsinifiles_input_4_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_4_label']);
	$options['bpsinifiles_input_5_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_5_label']);
	$options['bpsinifiles_input_6_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_6_label']); 
	$options['bpsinifiles_input_7_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_7_label']); 
	$options['bpsinifiles_input_8_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_8_label']);
	$options['bpsinifiles_input_9_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_9_label']);
	$options['bpsinifiles_input_10_label'] = wp_filter_nohtml_kses($input['bpsinifiles_input_10_label']);
		
	return $options;  
}

// Validate BPS options - Error Log Folder Location 
function bulletproof_security_options_validate2($input) {  
	$options = get_option('bulletproof_security_options2');  
	$options['bps_error_log_location'] = trim(wp_filter_nohtml_kses($input['bps_error_log_location']));
		
	return $options;  
}

// Validate BPS options - Error Log Last Modified Time 
function bulletproof_security_options_validate_elog($input) {  
	$options = get_option('bulletproof_security_options_elog');  
	$options['bps_error_log_date_mod'] = wp_filter_nohtml_kses($input['bps_error_log_date_mod']);
		
	return $options;  
}

// Validate BPS options - Maintenance Mode Form 
function bulletproof_security_options_validate_maint($input) {  
	$options = get_option('bulletproof_security_options_maint');  
	$options['bps-site-title'] = wp_filter_nohtml_kses($input['bps-site-title']);
	$options['bps-message-1'] = wp_filter_nohtml_kses($input['bps-message-1']);
	$options['bps-message-2'] = wp_filter_nohtml_kses($input['bps-message-2']);
	$options['bps-start-date'] = wp_filter_nohtml_kses($input['bps-start-date']);
	$options['bps-start-time'] = wp_filter_nohtml_kses($input['bps-start-time']);
	$options['bps-end-date'] = wp_filter_nohtml_kses($input['bps-end-date']);
	$options['bps-end-time'] = wp_filter_nohtml_kses($input['bps-end-time']);
	$options['bps-popup-message'] = wp_filter_nohtml_kses($input['bps-popup-message']);
	$options['bps-retry-after'] = wp_filter_nohtml_kses($input['bps-retry-after']);
	$options['bps-background-image'] = wp_filter_nohtml_kses($input['bps-background-image']);
		
	return $options;  
}

// Validate BPS options - BPS "My Notes" settings 
function bulletproof_security_options_validate_mynotes($input) {  
	$options = get_option('bulletproof_security_options_mynotes');  
	$options['bps_my_notes'] = htmlspecialchars($input['bps_my_notes']);
		
	return $options;  
}

// Validate BPS options - BPS F-Lock settings 
function bulletproof_security_options_validate_flock($input) {  
	$options = get_option('bulletproof_security_options_flock');  
	$options['bps_lock_root_htaccess'] = wp_filter_nohtml_kses($input['bps_lock_root_htaccess']);
	$options['bps_lock_wpconfig'] = wp_filter_nohtml_kses($input['bps_lock_wpconfig']);
	$options['bps_lock_index_php'] = wp_filter_nohtml_kses($input['bps_lock_index_php']);
	$options['bps_lock_wpblog_header'] = wp_filter_nohtml_kses($input['bps_lock_wpblog_header']);
	$options['bps_lock_root_htaccess_dr'] = wp_filter_nohtml_kses($input['bps_lock_root_htaccess_dr']);
	$options['bps_lock_index_php_dr'] = wp_filter_nohtml_kses($input['bps_lock_index_php_dr']);
	$options['bps_lock_root_htaccess_gwiod'] = wp_filter_nohtml_kses($input['bps_lock_root_htaccess_gwiod']);
	$options['bps_lock_index_php_gwiod'] = wp_filter_nohtml_kses($input['bps_lock_index_php_gwiod']);
		
	return $options;  
}

// Validate BPS options - BPS Pro Activation 
function bulletproof_security_options_validate_activation($input) {  
	$options = get_option('bulletproof_security_options_activation');  
	$options['bps_pro_activation'] = trim(wp_filter_nohtml_kses($input['bps_pro_activation']));
	$options['delete_paypal_email'] = trim(wp_filter_nohtml_kses($input['delete_paypal_email']));
	$options['bps_pro_key'] = trim(wp_filter_nohtml_kses($input['bps_pro_key']));
	$options['bps_api_key'] = trim(wp_filter_nohtml_kses($input['bps_api_key']));
			
	return $options;  
}

// Validate BPS options - S-Monitor BPS Pro Monitoring and Alerting Options 
function bulletproof_security_options_validate_monitor($input) {  
	$options = get_option('bulletproof_security_options_monitor');  
	$options['bps_first_launch'] = wp_filter_nohtml_kses($input['bps_first_launch']);
	$options['bps_upgrade_notice'] = wp_filter_nohtml_kses($input['bps_upgrade_notice']);
	$options['bps_security_status'] = wp_filter_nohtml_kses($input['bps_security_status']);
	$options['bps_HUD_alerts'] = wp_filter_nohtml_kses($input['bps_HUD_alerts']);
	$options['bps_PHP_ELogLoc_set'] = wp_filter_nohtml_kses($input['bps_PHP_ELogLoc_set']);
	$options['bps_PHP_ELog_error'] = wp_filter_nohtml_kses($input['bps_PHP_ELog_error']);
	$options['bps_phpini_created'] = wp_filter_nohtml_kses($input['bps_phpini_created']);
	$options['bps_flock_status'] = wp_filter_nohtml_kses($input['bps_flock_status']);
		
	return $options;  
}

// Validate BPS options - S-Monitor Email Alerts 
function bulletproof_security_options_validate_email($input) {  
	$options = get_option('bulletproof_security_options_email');  
	$options['bps_error_log_email'] = wp_filter_nohtml_kses($input['bps_error_log_email']);
	$options['bps_upgrade_email'] = wp_filter_nohtml_kses($input['bps_upgrade_email']);
	$options['bps_send_email_to'] = trim(wp_filter_nohtml_kses($input['bps_send_email_to']));
	$options['bps_send_email_from'] = trim(wp_filter_nohtml_kses($input['bps_send_email_from']));
	$options['bps_send_email_cc'] = trim(wp_filter_nohtml_kses($input['bps_send_email_cc']));
	$options['bps_send_email_bcc'] = trim(wp_filter_nohtml_kses($input['bps_send_email_bcc']));
		
	return $options;  
}
?>