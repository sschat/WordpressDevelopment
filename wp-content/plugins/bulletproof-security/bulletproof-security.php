<?php
/*
Plugin Name: BulletProof Security Pro
Plugin URI: http://www.ait-pro.com/aitpro-blog/2835/bulletproof-security-pro/bulletproof-security-pro-features/
Description: Website Security Protection against XSS, RFI, CSRF, CRLF, Code Injection, Base64 and SQL Injection hacking attempts. Built-in .htaccess and php.ini file editing, creation, automation, monitoring, logging, alerting and management. Extensive System, Server and Security Status Information. File Locking on the fly. Website repair and recovery tools for websites that have been hacked. 
Version: 5.1.4
Author: Edward Alexander
Author URI: http://www.ait-pro.com/
*/

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

define( 'BULLETPROOF_VERSION', '5.1.4' );

// Global configuration class file
require_once( WP_PLUGIN_DIR . '/bulletproof-security/includes/class.php' );

// Define vars and globals
global $bulletproof_security, $bpspro_version, $bpspro_edition, $bps_log, $bpspro_url;

// Current BPS Pro version
$bpspro_plugin = 'BulletProof Security Pro';
$bpspro_abbr = 'BPS Pro';
$bpspro_version = '5.1.4';
$bpspro_edition = 'Professional Edition';
$bpspro_url = 'http://api.ait-pro.com/';

// Global configuration class initialization
$bulletproof_security = new Bulletproof_Security();

// BPS functions
require_once( WP_PLUGIN_DIR . '/bulletproof-security/includes/functions.php' );
	remove_action('wp_head', 'wp_generator');
	
// Load BPS plugin textdomain - pending language translations
// load_plugin_textdomain('bulletproof-security', '', 'bulletproof-security/language');

// If in WP Dashboard or Admin Panels
if ( is_admin() ) {
    require_once( WP_PLUGIN_DIR . '/bulletproof-security/admin/includes/admin.php' );
	register_activation_hook(__FILE__, 'bulletproof_security_install');
    register_uninstall_hook(__FILE__, 'bulletproof_security_uninstall');

	add_action( 'admin_init', 'bulletproof_security_admin_init' );
    add_action( 'admin_menu', 'bulletproof_security_admin_menu' );
}

function bps_plugin_actlinks( $links, $file ){
// "Settings" link on Plugins Options Page 
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	if ( $file == $this_plugin ){
	$settings_link = '<a href="admin.php?page=bulletproof-security/admin/options.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
	add_filter( "plugin_action_links", 'bps_plugin_actlinks', 10, 2 );
?>