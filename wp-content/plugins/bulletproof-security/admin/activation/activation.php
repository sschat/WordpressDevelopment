<?php
// Direct calls to this file are Forbidden when core files are not present
if ( !function_exists('add_action') ){
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
 
if ( !current_user_can('manage_options') ){ 
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
?>

<div id="message" class="updated" style="border:1px solid #999999; margin-left:104px; margin-top:9px;">

<?php
// S-Monitor First Launch Notification - displayed in BPS Only
echo bps_first_launch_internal_notice();

// S-Monitor HUD - Heads Up Display - Warnings and Error messages - displayed in BPS Only
echo bps_HUD_bps_only();

// S-Monitor Upgrade Notification - displayed in BPS Only
echo bpsPro_update_bps_only_nag();

// S-Monitor BPS Security Status for Root and wp-admin (wp-admin only if problem) - displayed in BPS Only
echo bps_security_status_bps_only();
echo bps_security_status_wpadmin_bps_only();

// S-Monitor PHP Error Log Set Check - displayed in BPS Only
echo bps_smonitor_php_elog_bps();

// S-Monitor PHP Error Log - New PHP Errors - displayed in BPS Only
echo bps_smonitor_ELogModTimeDiff_bps();

// S-Monitor Check if a Php.ini file has been created or added to a File Manager slot - displayed in BPS Only
echo bps_smonitor_phpini_bps();

// S-Monitor F-Lock - Check if F-Lock options saved - F-Lock Lock / Unlock File Status & actual file permissions 404 or 400
echo bps_smonitor_flock_bps();

// Manually runs real-time BPS Pro version update check
echo bpsPro_update_checks();
// Manually runs PHP Error Log cron function - for testing ONLY
// echo bps_smonitor_ELogModTimeDiff_wp_email();

// General all purpose "Settings Saved." message for forms - /includes/class.php
if (current_user_can('manage_options')) {
if (@$_GET['settings-updated'] == true) {
_e('<font color="green"><strong><p>Settings Saved.</p></strong></font>');
	} else {
	__e('<font color="red"><strong><p>'.bps_cuser_errorrs().'</p></strong></font>');
	}
}

// If the WordPress Zip installer was used to install BPS - leaves copies of BPS in the /wp-content/uploads/ folder
// This function deletes any bulletproof-security.zip files in the uploads folder - delete performed when Get Key Request is made
// may generate a one time php error on some web hosts
function bpsRemoveZipFiles() {
	$wpUploadsDir = WP_CONTENT_DIR .'/uploads/';
	foreach (glob($wpUploadsDir.'bulletproof-security*.zip') as $filename) {
    @unlink($filename);
	}
}

// Activation - API URL, API Key and BPS Plugin Slug
if ( defined('BPS_API_KEY') ) {
	$bps_api_key = constant('BPS_API_KEY');
	} else {
	$bps_api_key = '';
}	
$bps_api_url = 'http://api.ait-pro.com/';
$plugin_slug = 'bulletproof-security';

function bps_api_init() {
	global $bps_api_key, $bps_api_host, $bps_api_port;
	$options = get_option('bulletproof_security_options_activation');  
	$options['bps_api_key'] == $options['delete_paypal_email'];
	if ( $bps_api_key )
		$bps_api_host = $bps_api_key . '.api.ait-pro.com';
	else
		$bps_api_host = $options['bps_api_key'] . '.api.ait-pro.com';

	$bps_api_port = 80;
}
add_action('init', 'bps_api_init');

function bps_get_key() {
	global $bps_api_key;
	$options = get_option('bulletproof_security_options_activation');
	$options['bps_api_key'] == $options['delete_paypal_email'];
	if ( !empty($bps_api_key) )
		return $bps_api_key;
	return $options['bps_api_key'];
}
add_action('init', 'bps_get_key');

function bps_verify_key( $key, $ip = null ) {
	global $bps_api_host, $bps_api_port, $bps_api_key;
	$blog = urlencode( get_option('home') );
	if ( $bps_api_key )
		$key = $bps_api_key;
	$response = bps_http_post("key=$key&blog=$blog", 'api.ait-pro.com', '/1.1/verify-key', $bps_api_port, $ip);
	if ( !is_array($response) || !isset($response[1]) || $response[1] != 'valid' && $response[1] != 'invalid' )
		return 'failed';
	return $response[1];
}
add_action('init', 'bps_verify_key');

// Debug / Test Mode - server-side - bps_auto_check_data()
function bps_test_mode() {
	if ( defined('BPS_TEST_MODE') && BPS_TEST_MODE )
		return true;
	return false;
}

// Returns array with headers and body response
function bps_http_post($request, $host, $path, $port = 80, $ip=null) {
	global $wp_version;

	$bps_ua = "WordPress/{$wp_version} | "; // sends WordPress/3.x.x
	$bps_ua .= 'BPS/' . constant( 'BULLETPROOF_VERSION' ); // sends BPS/5.0

	$content_length = strlen( $request );

	$http_host = $host;
	// use a specific IP if provided
	// needed by bps_check_server_connectivity()
	if ( $ip && long2ip( ip2long( $ip ) ) ) {
		$http_host = $ip;
	} else {
		$http_host = $host;
	}
	
	// use the WP HTTP class if it is available
	if ( function_exists( 'wp_remote_post' ) ) {
		$http_args = array(
			'body'			=> $request,
			'headers'		=> array(
				'Content-Type'	=> 'application/x-www-form-urlencoded; ' .
									'charset=' . get_option( 'blog_charset' ),
				'Host'			=> $host,
				'User-Agent'	=> $bps_ua
			),
			'httpversion'	=> '1.0',
			'timeout'		=> 15
		);
		$bps_url = "http://{$http_host}{$path}";
		$response = wp_remote_post( $bps_url, $http_args );
		if ( is_wp_error( $response ) )
			return '';

		return array( $response['headers'], $response['body'] );
	} else {
		$http_request  = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= 'Content-Type: application/x-www-form-urlencoded; charset=' . get_option('blog_charset') . "\r\n";
		$http_request .= "Content-Length: {$content_length}\r\n";
		$http_request .= "User-Agent: {$bps_ua}\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;
		
		$response = '';
		if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {
			fwrite( $fs, $http_request );

			while ( !feof( $fs ) )
				$response .= fgets( $fs, 1160 ); // One TCP-IP packet
			fclose( $fs );
			$response = explode( "\r\n\r\n", $response, 2 );
		}
		return $response;
	}
}
add_action('init', 'bps_http_post');

// Http_post check connectivity - IP & Port
function bps_check_server_connectivity() {
	global $bps_api_host, $bps_api_port, $bps_api_key;
	
	$test_host = 'api.ait-pro.com';
	
	if ( !function_exists('fsockopen') || !function_exists('gethostbynamel') )
		return array();
	
	$ips = gethostbynamel($test_host);
	if ( !$ips || !is_array($ips) || !count($ips) )
		return array();
		
	$servers = array();
	foreach ( $ips as $ip ) {
		$response = bps_verify_key( bps_get_key(), $ip );
		// even if the key is invalid, at least we know we have connectivity
		if ( $response == 'valid' || $response == 'invalid' )
			$servers[$ip] = true;
		else
			$servers[$ip] = false;
	}

	return $servers;
}
add_action('init', 'bps_check_server_connectivity');

//function bps_microtime() {
//	$mtime = explode( ' ', microtime() );
//	return $mtime[1] + $mtime[0];
//}

//function bps_cmp_time( $a, $b ) {
//	return $a['time'] > $b['time'] ? -1 : 1;
//}

function bps_auto_check_data( $bpsData ) {
	global $bps_api_host, $bps_api_port;

	$bpsInput = $bpsData;
	$bpsInput['user_ip']    = $_SERVER['REMOTE_ADDR'];
	$bpsInput['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$bpsInput['referrer']   = $_SERVER['HTTP_REFERER'];
	$bpsInput['blog']       = get_option('home');
	$bpsInput['blog_lang']  = get_locale();

	if ( bps_test_mode() )
		$bpsInput['is_test'] = 'true';
		
	foreach ($_POST as $key => $value ) {
		if ( is_string($value) )
			$bpsInput["POST_{$key}"] = $value;
	}

	$ignore = array( 'HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW' );

	foreach ( $_SERVER as $key => $value ) {
		if ( !in_array( $key, $ignore ) && is_string($value) )
			$bpsInput["$key"] = $value;
		else
			$bpsInput["$key"] = '';
	}

	$query_string = '';
	foreach ( $bpsInput as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';
		
	$bpsData['data_submitted'] = $bpsInput;

	$response = bps_http_post($query_string, $bps_api_host, '/1.1/bps-check', $bps_api_port);
	$bpsData['bps_result'] = $response[1];
	if ( 'true' == $response[1] ) {
		return 'true';
	}
	
	if ( 'true' != $response[1] && 'false' != $response[1] ) {
		return 'null';
	}
	return $bpsData;
}
add_action('init', 'bps_auto_check_data');

function bps_conf() {
	global $bps_api_key;
	$options = get_option('bulletproof_security_options_activation');
	$options['bps_api_key'] == $options['delete_paypal_email'];  
	if ( isset($_POST['Submit-SaveKey']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		$home_url = parse_url( get_bloginfo('url') );

		if ( empty($key) ) {
			$key_status = 'empty';
			$ms[] = 'new_key_empty';
			delete_option('bps_api_key');
		} elseif ( empty($home_url['host']) ) {
			$key_status = 'empty';
			$ms[] = 'bad_home_url';
		} else {
			$key_status = bps_verify_key( $key );
		} 

		if ( $key_status == 'valid' ) {
			update_option('bps_api_key', $key);
			$ms[] = 'new_key_valid';
		} else if ( $key_status == 'invalid' ) {
			$ms[] = 'new_key_invalid';
		} else if ( $key_status == 'failed' ) {
			$ms[] = 'new_key_failed';
		} 

	} elseif ( isset($_POST['check']) ) {
		bps_get_server_connectivity(0);
	}

	if ( empty( $key_status) ||  $key_status != 'valid' ) {
		$key = $options['bps_api_key'];
		
		if ( empty( $key ) ) {
			if ( empty( $key_status ) || $key_status != 'failed' ) {
				if ( bps_verify_key( '888tttxxxx444' ) == 'failed' )
					$ms[] = 'no_connection';
				else
					$ms[] = 'key_empty';
			} 
			$key_status = 'empty';
		} else {
			$key_status = bps_verify_key( $key );
		} 
		if ( $key_status == 'valid' ) {
			$ms[] = 'key_valid';
		} else if ( $key_status == 'invalid' ) {
			delete_option('bps_api_key');
			$ms[] = 'key_empty';
		} else if ( !empty($key) && $key_status == 'failed' ) {
			$ms[] = 'key_failed';
		}
	} 

	$messages = array(
		'key_failed' => array('text' => __('')),
		'no_connection' => array('text' => __('')),
	);
}
add_action('init', 'bps_conf');

// BPS Pro version check
function bps_check_for_plugin_update($checked_data) {
	global $bps_api_url, $plugin_slug;
	$options = get_option('bulletproof_security_options_activation'); 
	$options['bps_api_key'] == $options['delete_paypal_email'];
	$options['bps_pro_key'] == $options['bps_api_key'];
	
	if (empty($checked_data->checked))
		return $checked_data;
	
	$request_args = array(
		'slug' => $plugin_slug,
		'version' => $checked_data->checked[$plugin_slug .'/'. $plugin_slug .'.php'],
		'bpskey' => $options['bps_pro_key'],  
	);
	
	$request_string = bps_prepare_request('basic_check', $request_args);
	
	// Start checking for an update
	$raw_response = wp_remote_post($bps_api_url, $request_string);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);
	
	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$plugin_slug .'/'. $plugin_slug .'.php'] = $response;
	
	return $checked_data;
}

// API Call
function bps_plugin_api_call($action, $args) {
	global $plugin_slug, $bps_api_url;
	$options = get_option('bulletproof_security_options_activation');
	$options['bps_api_key'] == $options['delete_paypal_email'];
	$options['bps_pro_key'] == $options['bps_api_key'];
	
	if ($args->slug != $plugin_slug)
		return false;
		
	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$plugin_slug .'/'. $plugin_slug .'.php'];
	$args->version = $current_version;
	$args->bpskey = $options['bps_pro_key'];
		
	$request_string = bps_prepare_request($action, $args);
	
	$request = wp_remote_post($bps_api_url, $request_string);
	
	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('Error Code 1: An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('Error Code 2: Plugins API request failed.'), $request['body']);
	}
	
	return $res;
}

// Get BPS Pro Activation Key form - Send BPS Key Request
if (isset($_POST['Submit-GetKey']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_pro_get_key' );
	global $bps_api_url, $plugin_slug;
	$options = get_option('bulletproof_security_options_activation');
	echo bpsRemoveZipFiles();

	$request_args = array(
		'slug' => $plugin_slug,
		'version' => BULLETPROOF_VERSION,
		'bpskey' => $options['bps_pro_activation'],  
	);
	
	$request_string = bps_prepare_request('bps_get_key', $request_args);
	$raw_response = wp_remote_post($bps_api_url, $request_string);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
		$response = unserialize($raw_response['body']);
	if ($options['bps_pro_activation'] != '') {
	_e('<font color="green"><strong>BPS Pro Activation Key Request Successful! Your Activation Key has been sent to your PayPal email address.</strong></font><br><strong>You should receive your Activation Key within 3 minutes. If you do not receive your BPS Pro Activation Key within 15 minutes please send an email to info@ait-pro.com to get your BPS Pro Activation Key. Thank you.</strong>');
	} else {
	_e('<font color="red"><strong>Error: BPS Pro Activation Key Request was unsuccessful.</strong></font><br><strong>Did you enter a valid PayPal email address and click the Save button before clicking the Get Key button?<br>If this was not the cause of the error then please send an email to info@ait-pro.com to get your BPS Pro Activation Key. Thank you.</strong><br>');
	}
	}
}

// Get BPS Pro Activation Key form - Prepare the action and args
function bps_prepare_request($action, $args) {
	global $wp_version;
	return array(
		'body' => array(
		'action' => $action, 
		'request' => serialize($args)
		),
		'user-agent' => get_bloginfo('url')
	);	
}	
?>
</div>

<div class="wrap">
<h2 style="margin-left:104px;"><?php _e('BulletProof Security Pro ~ Activation'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/images/bps-pro-logo.png" style="float:left; padding:0px 8px 0px 0px; margin:-68px 0px 0px 0px;" /></div>
		<ul>
			<li><a href="#bps-tabs-1">BPS Pro Activation</a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">
<h2><?php _e('BulletProof Security Pro Activation'); ?></h2>

<div id="bpsActivation" style="border-top:1px solid #999999;">
<h3><?php _e('1. Enter the BPS Pro Download Key that was emailed to you and click the Save button.<br>'); 
		_e('2. Click the Get Key button to get your BPS Pro Activation Key. Your Activation Key will be emailed to your PayPal email address.<br>'); 
		_e('3. Enter your BPS Pro Activation Key and click the Save Key button.'); ?></h3>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <form name="bpsProActivation" action="options.php" method="post">
	<?php settings_fields('bulletproof_security_options_activation'); ?>
	<?php $options = get_option('bulletproof_security_options_activation'); ?>
	<p class="submit">
    <label for="proActivate"><strong><?php _e('1. Enter BPS Pro Download Key and click Save'); ?></strong></label><br />
    <input type="text" name="bulletproof_security_options_activation[bps_pro_activation]" value="<?php echo $options['bps_pro_activation']; ?>" class="regular-text-short" />
    <input type="hidden" name="bpsProKeyCheck" value="<?php echo $options['bps_pro_activation']; ?>" />
	<input type="submit" name="Submit-ProActivate" class="button" value="<?php esc_attr_e('Save') ?>" /></p>
</form>

<form name="bpsProGetKey" action="admin.php?page=bulletproof-security/admin/activation/activation.php" method="post">
<?php wp_nonce_field('bulletproof_security_pro_get_key'); ?>
	<p class="submit">
    <label for="proActivate"><strong><?php _e('2. Get BPS Pro Activation Key:'); ?></strong></label>
	<input type="submit" name="Submit-GetKey" class="button" value="<?php esc_attr_e('Get Key') ?>" /></p>
</form>

<form name="bpsProActivation2" action="options.php" method="post">
	<?php settings_fields('bulletproof_security_options_activation'); ?>
	<?php $options = get_option('bulletproof_security_options_activation'); ?>
	<p class="submit">
    <label for="proActivate"><strong><?php _e('3. Enter BPS Pro Activation Key and click Save Key'); ?></strong></label><br />
    <input type="text" name="bulletproof_security_options_activation[delete_paypal_email]" value="<?php echo $options['delete_paypal_email']; ?>" class="regular-text-short" />
    <input type="hidden" name="bpsProKeyCheck2" value="<?php echo $options['bps_api_key']; ?>" />
	<input type="submit" name="Submit-SaveKey" class="button" value="<?php esc_attr_e('Save Key') ?>" /></p>
</form>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"></td>
    </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
</div>
            
<div id="AITpro-link">BulletProof Security Pro <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Design">AITpro Website Design</a>
</div>
</div>
</div>