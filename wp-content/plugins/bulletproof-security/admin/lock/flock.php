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

// Obsolete file cleanup / deletion
echo bpsRemoveObs();

// Manually runs real-time BPS Pro version update check
echo bpsPro_update_checks();
// Manually runs PHP Error Log cron function - for testing ONLY
// echo bps_smonitor_ELogModTimeDiff_wp_email();

// General all purpose "Settings Saved." message for forms - /includes/class.php
if (current_user_can('manage_options')) {
if (@$_GET['settings-updated'] == true) {
_e('<font color="green"><strong><p>Settings Saved.</p></strong></font>');
	} else {
	_e('<font color="red"><strong>'.bps_cuser_errors().'</strong></font>');
	}
}
?>
</div>

<div class="wrap">
<h2 style="margin-left:104px;"><?php _e('BulletProof Security Pro ~ File Lock'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/images/bps-pro-logo.png" style="float:left; padding:0px 8px 0px 0px; margin:-68px 0px 0px 0px;" /></div>
		<ul>
			<li><a href="#bps-tabs-1">File Lock</a></li>
			<li><a href="#bps-tabs-2">Help &amp; FAQ</a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">
<h2><?php _e('File Lock'); ?></h2>
<h3><?php _e('Lock / AutoLock Mission Critical Files Help Info'); ?>  <button id="bps-open-modal1" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content1" title="<?php _e('F-Lock'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>What is AutoLock?</strong><br>AutoLock is automated file locking. When first installing BPS Pro you need only click on the Save Options button to lock all of your files together at one time. Whatever Lock or Unlock file options are selected for each of your files is automatically applied to your files just by accessing the F-Lock page. Manual control of individual file permissions is done by choosing the file you want to Lock or Unlock, selecting Lock or Unlock for that file and then clicking the Save Options button. AutoLock is used for 2 reasons - to make locking files quick and easy and to display real time file permissions status.<br><br><strong>IMPORTANT - Web Host Server API Info - CGI or DSO Info</strong><br>BPS Pro checks your Web Hosts Server API and your Web Hosts Server API is displayed in green font right below this Read Me Help button. Most people will have php run as CGI on their Web Hosts and you should see that your Server API is CGI. If your Web Host is using DSO / Apache mod_php instead CGI then file locking and unlocking does not pertain to you. Set your file permissions to DSO - 644 File Permissions and then turn off the S-Monitor <strong>F-Lock: Check File Lock / Unlock Status</strong> checking option. DSO file permissions are handled in a completely different way then CGI file permissions. The majority of Web Hosts will be using CGI so the rest of this help file pertains to anyone who sees that their Server API is CGI.<br><br><strong>Why Lock Mission Critical Files?</strong><br>Hackers specifically target these files in Mass Code Injection attacks on web hosts. This is done by exploiting the Group Permissions on files located in Root folders for hosts that use CGI. By locking these files with Read Only 400 and 404 permissions the Group Permissions are removed and these files are protected against Mass Code Injection attacks. For hosts using DSO - 644 file permissions are secure because php file security is handled using a different type of file security.<br><br><strong>Will Locking Files With Read Only Permissions Break WordPress?</strong><br>No. 400 and 404 files permissions have been tested on several different web hosts using suPHP and suEXEC with CGI and WordPress performs normally. DSO file permissions should be the standard 644 file permissions. BPS is checking and displaying your Server API. If you see CGI displayed as your Server API then you can use the regular File Lock and Unlock options. The regular file Lock options should work fine on most if not all web hosts. The majority of web hosts these days are using suPHP or suEXEC and a CGI php handler. If you see that your Server API is DSO then ONLY use the DSO 644 permissions options. There is no need to have an unlock for DSO. 644 permissions are secure for DSO because of the different way that the DSO mod_php Apache module handles php files and security and WordPress and plugins can write to files with 644 permissions. If you see something other than CGI or DSO displayed as your Server API then check with your web host web host to find out what the most restrictive file permissions that you can use are or you can just experiment. If you are experimenting be prepared to FTP to your website and manually change the file permission back to what it was if you see 500 errors. Incorrect file permissions could cause you website to display 500 errors and be down. It is possible but not likely that the Server API could display another interface name such as continuity, embed, isapi, litespeed or other interface names instead of cli or cgi.<br><br><strong>Will Locking Files With Read Only Permissions Break Plugins?</strong><br>Locking the files will not interfere with a plugins normal operation but if a plugin needs to write to any of these locked files then the file will temporarily need to be unlocked so that a plugin can write to it. If you are using the B-Core File Editor to edit your root .htaccess file you will need to unlock the root .htaccess file so that BPS can write to it. A Lock / Unlock button has been added to the BPS .htaccess File Editor page. F-Lock allows you to quickly Lock and Unlock files on the fly without having to use FTP or your Control Panel.<br><br><strong>What is GWIOD - Lock / Unlock?</strong><br>GWIOD is short for Giving WordPress Its Own Directory. People who are using this type of WordPress site set up will have an additional .htaccess file and index.php file in their Site Root folder. This will allow them to lock both their Site Root .htaccess file and index.php file as well as the .htaccess file and index.php files that exist in their actual WordPress installation folder. If you are not using this type of WordPress set up then these files will either show up as duplicates of your already locked or unlocked files or could generate error messages. If you are not using this type of WordPress set up and you are seeing error messages then turn this check off by selecting the <strong>Turn Off Checking &amp; Alerts</strong> dropdown list option. If the file does not really exist then you will see <strong>...file does not exist</strong> under Permissions &amp; Status for that file.<br><br><strong>What is DR - Lock / Unlock?</strong><br>DR is short for Document Root. This allows you to lock and unlock an .htaccess file or index.php file in your Document Root folder. If your WordPress installation is already in your Document Root folder then the DR Permissions & Status information will just be duplicated permissions and status information about your already locked or unlocked files. If you have multiple WordPress sites installed and some are subfolder installations and one is a WordPress Document Root installation then you will be able to lock and unlock the Document Root files from any of your WordPress subfolder websites. If you have a single WordPress installation installed in your Document Root folder then these files will show up as duplicates of your already locked or unlocked files. If you are seeing error messages about these files not being locked because they do not really exist then turn this check off by selecting the <strong>Turn Off Checking &amp; Alerts</strong> dropdown list option. If the file does not really exist then you will see <strong>...file does not exist</strong> under Permissions &amp; Status for that file. Another possible scenario is that if you have an HTML site in your Document Root folder then you could lock the .htaccess file for that HTML site.<br><br><strong>The Wrong Permissions and Status Table Is Displayed</strong><br>If BPS detects CGI then you will see the <strong>CGI Permissions & Status Table</strong> displayed and you should be able to set permissions to 400 and 404. If BPS detects DSO then you will see the <strong>DSO Permissions & Status Table</strong> and should only be able to set permissions to 644. BPS looks at the Server API name that your host has configured to display. If your host is using CGI but they are using another interface name then BPS will not be able to detect that your Server is using CGI and will display the DSO Permissions and Status Table. Please let us know about this by sending us an email. We can then add additional coding to BPS to create an exception for your particular host.<br><br><strong>Help links are provided on the Help & FAQ page.</strong><br><br><strong>BPS Pro 5.2 will have the added capability of allowing you to choose and add any additional files that you want to lock down and monitor.</strong>'); ?></p>
</div>

<?php
// Detect the SAPI - php_sapi_name returns lower case string by default
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == 'cgi' || substr($sapi_type, 0, 9) == 'litespeed' || substr($sapi_type, 0, 7) == 'caudium' || substr($sapi_type, 0, 8) == 'webjames' || substr($sapi_type, 0, 3) == 'tux' || substr($sapi_type, 0, 5) == 'roxen' || substr($sapi_type, 0, 6) == 'thttpd' || substr($sapi_type, 0, 6) == 'phttpd' || substr($sapi_type, 0, 10) == 'continuity' || substr($sapi_type, 0, 6) == 'pi3web' || substr($sapi_type, 0, 6) == 'milter') {
	_e('<font color="green"><strong>Server API: '.$sapi_type.' - Your Host Server is using CGI. Use the CGI File Lock and Unlock options.</strong></font>');
	} else {
    _e('<font color="green"><strong>Server API: '.$sapi_type.' - Your Host Server is using DSO. ONLY use the DSO - 644 File Permission options.</strong></font>');
}
	
// Strato Hosting uses CGI but does not allow file permissions to be set to 404 for htaccess files - display warning if Strato
$bpsSAHostF = $_SERVER['SERVER_ADDR'];
$bpsHostNameF = $_SERVER['SERVER_NAME']; 
$bpsGetDNSF = @dns_get_record($bpsHostNameF, DNS_NS);
	$bpsTargetNSF = $bpsGetDNSF[0]['target'];
	if ($bpsTargetNSF != '') {
	preg_match('/[^.]+\.[^.]+$/', $bpsTargetNSF, $bpsTmatchesF);
	$bpsNSHostSubjectF = $bpsTmatchesF[0];
	}
	
	if ($bpsTargetNSF == '') {
	@dns_get_record($bpsHostNameF, DNS_ALL, $authns, $addtl);
	$bpsTargetF = $authns[0]['target'];
	if ($bpsTargetF != '') {
	preg_match('/[^.]+\.[^.]+$/', $bpsTargetF, $bpsTmatchesF);
	$bpsNSHostSubjectF = $bpsTmatchesF[0];
	}
	}
	
	if ($bpsTargetF && $bpsTargetNSF == '') {
	@dns_get_record($bpsHostNameF, DNS_ANY, $authns, $addtl);
	$bpsTargetF = $authns[0]['target'];
	preg_match('/[^.]+\.[^.]+$/', $bpsTargetF, $bpsTmatchesF);
	$bpsNSHostSubjectF = $bpsTmatchesF[0];
	}

//if ($bpsNSHostSubjectF == 'domaincontrol.com') {
if ($bpsNSHostSubjectF == 'rzone.de') {
_e('<br><font color="red"><strong>Your Web Host is Strato.  Strato Hosting does not allow you to lock your Root .htaccess file with 404 permissions.  Please set the Lock / Unlock Root .htaccess File, DR - Lock / Unlock DR .htaccess File and GWIOD - Lock / Unlock Root .htaccess File F-Lock options to - Turn Off Checking &amp; Alerts.</strong></font>');
_e('<br><font color="green"><strong>All of your other WP files can be locked on Strato Hosting.</strong></font>');
}


// CGI and DSO Dropdown List Form Options - CHMOD files
function bps_flock_pro() {
$options = get_option('bulletproof_security_options_flock');
	$bpsRootHtaccess = ABSPATH . '.htaccess';
	$bpsWpConfig = ABSPATH . 'wp-config.php';
	$bpsIndexPhp = ABSPATH . 'index.php';
	$bpsWpBlogHeader = ABSPATH . 'wp-blog-header.php';
	$bpsRootHtaccessDR = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
	$bpsIndexPhpDR = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
	$bpsRootHtaccessGWIOD = dirname(ABSPATH) . '/.htaccess';
	$bpsIndexPhpGWIOD = dirname(ABSPATH) . '/index.php';
	
if (current_user_can('manage_options')) {

if ($options['bps_lock_root_htaccess'] == 'yes') { 	
	if (file_exists($bpsRootHtaccess)) {
	chmod($bpsRootHtaccess, 0404);
	}}

if ($options['bps_lock_root_htaccess'] == 'no') { 	
	if (file_exists($bpsRootHtaccess)) {
	chmod($bpsRootHtaccess, 0644);
	}}
	
if ($options['bps_lock_root_htaccess'] == 'dso') { 	
	if (file_exists($bpsRootHtaccess)) {
	chmod($bpsRootHtaccess, 0644);
	}}

if ($options['bps_lock_wpconfig'] == 'yes') { 	
	if (file_exists($bpsWpConfig)) {
	chmod($bpsWpConfig, 0400);
	}}

if ($options['bps_lock_wpconfig'] == 'no') { 	
	if (file_exists($bpsWpConfig)) {
	chmod($bpsWpConfig, 0644);
	}}

if ($options['bps_lock_wpconfig'] == 'dso') { 	
	if (file_exists($bpsWpConfig)) {
	chmod($bpsWpConfig, 0644);
	}}
	
if ($options['bps_lock_index_php'] == 'yes') { 	
	if (file_exists($bpsIndexPhp)) {
	chmod($bpsIndexPhp, 0400);
	}}

if ($options['bps_lock_index_php'] == 'no') { 	
	if (file_exists($bpsIndexPhp)) {
	chmod($bpsIndexPhp, 0644);
	}}

if ($options['bps_lock_index_php'] == 'dso') { 	
	if (file_exists($bpsIndexPhp)) {
	chmod($bpsIndexPhp, 0644);
	}}
	
if ($options['bps_lock_wpblog_header'] == 'yes') { 	
	if (file_exists($bpsWpBlogHeader)) {
	chmod($bpsWpBlogHeader, 0400);
	}}

if ($options['bps_lock_wpblog_header'] == 'no') { 	
	if (file_exists($bpsWpBlogHeader)) {
	chmod($bpsWpBlogHeader, 0644);
	}}

if ($options['bps_lock_wpblog_header'] == 'dso') { 	
	if (file_exists($bpsWpBlogHeader)) {
	chmod($bpsWpBlogHeader, 0644);
	}}	

if ($options['bps_lock_root_htaccess_dr'] == 'yes') { 	
	if (file_exists($bpsRootHtaccessDR)) {
	chmod($bpsRootHtaccessDR, 0404);
	}}

if ($options['bps_lock_root_htaccess_dr'] == 'no') { 	
	if (file_exists($bpsRootHtaccessDR)) {
	chmod($bpsRootHtaccessDR, 0644);
	}}

if ($options['bps_lock_root_htaccess_dr'] == 'dso') { 	
	if (file_exists($bpsRootHtaccessDR)) {
	chmod($bpsRootHtaccessDR, 0644);
	}}

if ($options['bps_lock_index_php_dr'] == 'yes') { 	
	if (file_exists($bpsIndexPhpDR)) {
	chmod($bpsIndexPhpDR, 0400);
	}}

if ($options['bps_lock_index_php_dr'] == 'no') { 	
	if (file_exists($bpsIndexPhpDR)) {
	chmod($bpsIndexPhpDR, 0644);
	}}

if ($options['bps_lock_index_php_dr'] == 'dso') { 	
	if (file_exists($bpsIndexPhpDR)) {
	chmod($bpsIndexPhpDR, 0644);
	}}

if ($options['bps_lock_root_htaccess_gwiod'] == 'yes') { 	
	if (file_exists($bpsRootHtaccessGWIOD)) {
	chmod($bpsRootHtaccessGWIOD, 0404);
	}}

if ($options['bps_lock_root_htaccess_gwiod'] == 'no') { 	
	if (file_exists($bpsRootHtaccessGWIOD)) {
	chmod($bpsRootHtaccessGWIOD, 0644);
	}}

if ($options['bps_lock_root_htaccess_gwiod'] == 'dso') { 	
	if (file_exists($bpsRootHtaccessGWIOD)) {
	chmod($bpsRootHtaccessGWIOD, 0644);
	}}


if ($options['bps_lock_index_php_gwiod'] == 'yes') { 	
	if (file_exists($bpsIndexPhpGWIOD)) {
	chmod($bpsIndexPhpGWIOD, 0400);
	}}

if ($options['bps_lock_index_php_gwiod'] == 'no') { 	
	if (file_exists($bpsIndexPhpGWIOD)) {
	chmod($bpsIndexPhpGWIOD, 0644);
	}}
	
if ($options['bps_lock_index_php_gwiod'] == 'dso') { 	
	if (file_exists($bpsIndexPhpGWIOD)) {
	chmod($bpsIndexPhpGWIOD, 0644);
	}}
}}

// CGI Permissions and Status Table functions - Get file permissions and display status only if SAPI is CGI
function bps_flock_pro_statusRH() {
	clearstatcache();
	$file = ABSPATH . '.htaccess';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_root_htaccess'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_root_htaccess'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>Site Root .htaccess file does not exist.</strong></font><br>');
	}
	if ($perms == '404.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPC() {
	clearstatcache();
	$file = ABSPATH . 'wp-config.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_wpconfig'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_wpconfig'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>The wp-config.php file does not exist.</strong></font><br>');
	}	
	if ($perms == '400.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPI() {
	clearstatcache();
	$file = ABSPATH . 'index.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_index_php'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_index_php'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>The WP Site Root index.php file does not exist.</strong></font><br>');
	}		
	if ($perms == '400.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPBH() {
	clearstatcache();
	$file = ABSPATH . 'wp-blog-header.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_wpblog_header'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_wpblog_header'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>The wp-blog-header.php file does not exist.</strong></font><br>');
	}
	if ($perms == '400.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusRH_DR() {
	clearstatcache();
	$file = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');
	
	if ($options['bps_lock_root_htaccess_dr'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_root_htaccess_dr'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>DR - Root .htaccess file does not exist.</strong></font><br>');
	}
	if ($perms == '404.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPI_DR() {
	clearstatcache();
	$file = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_index_php_dr'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_index_php_dr'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>DR - Root index.php file does not exist.</strong></font><br>');
	}
	if ($perms == '400.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusRH_GWIOD() {
	clearstatcache();
	$file = dirname(ABSPATH) . '/.htaccess';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');
	
	if ($options['bps_lock_root_htaccess_gwiod'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_root_htaccess_gwiod'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>GWIOD - Root .htaccess file does not exist.</strong></font><br>');
	}
	if ($perms == '404.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPI_GWIOD() {
	clearstatcache();
	$file = dirname(ABSPATH) . '/index.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');
	
	if ($options['bps_lock_index_php_gwiod'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_index_php_gwiod'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>GWIOD - Root index.php file does not exist.</strong></font><br>');
	}
	if ($perms == '400.') {
	_e('<font color="green"><strong>'.str_replace('.', '', $perms).' - Locked - Read Only</strong></font><br>');
	} else {
	_e('<font color="red"><strong>'.str_replace('.', '', $perms).' - Unlocked or Not Locked</strong></font><br>'); 
	}
	}
}

// DSO Permissions and Status Table functions - displays only if DSO SAPI is detected
function bps_flock_pro_statusRH_DSO() {
	clearstatcache();
	$file = ABSPATH . '.htaccess';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_root_htaccess'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_root_htaccess'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>Site Root .htaccess file does not exist.</strong></font><br>');
	}
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPC_DSO() {
	clearstatcache();
	$file = ABSPATH . 'wp-config.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_wpconfig'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_wpconfig'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>The wp-config.php file does not exist.</strong></font><br>');
	}		
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');  
	}
	}
}

function bps_flock_pro_statusWPI_DSO() {
	clearstatcache();
	$file = ABSPATH . 'index.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_index_php'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_index_php'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>The WP Site Root index.php file does not exist.</strong></font><br>');
	}	
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusWPBH_DSO() {
	clearstatcache();
	$file = ABSPATH . 'wp-blog-header.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_wpblog_header'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_wpblog_header'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>The wp-blog-header.php file does not exist.</strong></font><br>');
	}
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusRH_DR_DSO() {
	clearstatcache();
	$file = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');
	
	if ($options['bps_lock_root_htaccess_dr'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_root_htaccess_dr'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>DR - Root .htaccess file does not exist.</strong></font><br>');
	}
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');  
	}
	}
}

function bps_flock_pro_statusWPI_DR_DSO() {
	clearstatcache();
	$file = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');	

	if ($options['bps_lock_index_php_dr'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_index_php_dr'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>DR - Root index.php file does not exist.</strong></font><br>');
	}
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>'); 
	}
	}
}

function bps_flock_pro_statusRH_GWIOD_DSO() {
	clearstatcache();
	$file = dirname(ABSPATH) . '/.htaccess';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');
	
	if ($options['bps_lock_root_htaccess_gwiod'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_root_htaccess_gwiod'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>GWIOD - Root .htaccess file does not exist.</strong></font><br>');
	}
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');  
	}
	}
}

function bps_flock_pro_statusWPI_GWIOD_DSO() {
	clearstatcache();
	$file = dirname(ABSPATH) . '/index.php';
	$perms = @substr(sprintf(".%o.", fileperms($file)), -4);
	$options = get_option('bulletproof_security_options_flock');
	
	if ($options['bps_lock_index_php_gwiod'] == 'off') { 	
	_e('<font color="black"><strong>Turned Off</strong></font><br>');
	}
	elseif ($options['bps_lock_index_php_gwiod'] == 'yes' || 'no' || '') { 	
	if (!file_exists($file)) {
	_e('<font color="black"><strong>GWIOD - Root index.php file does not exist.</strong></font><br>');
	}
	if ($perms == '644.') {
	_e('<font color="green"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>');
	} else {
	_e('<font color="red"><strong>DSO - '.str_replace('.', '', $perms).' File Permissions</strong></font><br>'); 
	}
	}
}

// CGI and DSO File Permissions and Status Tables - File Last Modified Time
function bps_flock_modTimeRH() {
$file = ABSPATH . '.htaccess';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeWPC() {
$file = ABSPATH . 'wp-config.php';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeWPI() {
$file = ABSPATH . 'index.php';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeWPBH() {
$file = ABSPATH . 'wp-blog-header.php';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeRH_DR() {
$file = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeWPI_DR() {
$file = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeRH_GWIOD() {
$file = dirname(ABSPATH) . '/.htaccess';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
function bps_flock_modTimeWPI_GWIOD() {
$file = dirname(ABSPATH) . '/index.php';
	if (file_exists($file)) {
	$last_modified = date ("M d Y H:i:s.", filemtime($file));
	return $last_modified;
	}
}
?>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="bps-table_cell_help">
    <form name="bpsFlock" action="options.php" method="post">
    <?php settings_fields('bulletproof_security_options_flock'); ?>
	<?php $options = get_option('bulletproof_security_options_flock'); ?>
<strong><label for="bps-flock"><?php _e('Lock / Unlock Root .htaccess File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_root_htaccess]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_root_htaccess']); ?>><?php _e('CGI - Lock Root .htaccess File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_root_htaccess']); ?>><?php _e('CGI - Unlock Root .htaccess File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_root_htaccess']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_root_htaccess']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('Lock / Unlock wp-config.php File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_wpconfig]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_wpconfig']); ?>><?php _e('CGI - Lock wp-config.php File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_wpconfig']); ?>><?php _e('CGI - Unlock wp-config.php File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_wpconfig']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_wpconfig']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('Lock / Unlock WP Root index.php File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_index_php]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_index_php']); ?>><?php _e('CGI - Lock WP Root index.php File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_index_php']); ?>><?php _e('CGI - Unlock WP Root index.php File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_index_php']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_index_php']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('Lock / Unlock wp-blog-header.php File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_wpblog_header]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_wpblog_header']); ?>><?php _e('CGI - Lock wp-blog-header.php File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_wpblog_header']); ?>><?php _e('CGI - Unlock wp-blog-header.php File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_wpblog_header']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_wpblog_header']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('DR - Lock / Unlock DR .htaccess File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_root_htaccess_dr]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_root_htaccess_dr']); ?>><?php _e('CGI - Lock DR Root .htaccess File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_root_htaccess_dr']); ?>><?php _e('CGI - Unlock DR Root .htaccess File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_root_htaccess_dr']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_root_htaccess_dr']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('DR - Lock / Unlock WP DR index.php File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_index_php_dr]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_index_php_dr']); ?>><?php _e('CGI - Lock DR WP index.php File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_index_php_dr']); ?>><?php _e('CGI - Unlock DR WP index.php File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_index_php_dr']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_index_php_dr']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('GWIOD - Lock / Unlock Root .htaccess File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_root_htaccess_gwiod]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_root_htaccess_gwiod']); ?>><?php _e('CGI - Lock GWIOD Root .htaccess File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_root_htaccess_gwiod']); ?>><?php _e('CGI - Unlock GWIOD Root .htaccess File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_root_htaccess_gwiod']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_root_htaccess_gwiod']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-flock"><?php _e('GWIOD - Lock / Unlock WP Root index.php File'); ?></label></strong><br />
<select name="bulletproof_security_options_flock[bps_lock_index_php_gwiod]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_lock_index_php_gwiod']); ?>><?php _e('CGI - Lock GWIOD WP index.php File'); ?></option>
<option value="no"<?php selected('no', $options['bps_lock_index_php_gwiod']); ?>><?php _e('CGI - Unlock GWIOD WP index.php File'); ?></option>
<option value="dso"<?php selected('dso', $options['bps_lock_index_php_gwiod']); ?>><?php _e('DSO - 644 File Permission'); ?></option>
<option value="off"<?php selected('off', $options['bps_lock_index_php_gwiod']); ?>><?php _e('Turn Off Checking &amp; Alerts'); ?></option>
</select><br /><br />
<input type="hidden" name="bpsFL" value="bps-FL" />
<?php bps_flock_pro(); ?>
<p class="submit">
<input type="submit" name="bpsFlockSubmit" class="button" value="<?php esc_attr_e('Save Options') ?>" /></p>
</form>	
    </td>
    <td width="50%" valign="top" class="bps-table_cell_help">
    
    <?php 
	$bpsRootHtaccess = ABSPATH . '.htaccess';
	$bpsWpConfig = ABSPATH . 'wp-config.php';
	$bpsIndexPhp = ABSPATH . 'index.php';
	$bpsWpBlogHeader = ABSPATH . 'wp-blog-header.php';
	$bpsRootHtaccessDR = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
	$bpsIndexPhpDR = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
	$bpsRootHtaccessGWIOD = dirname(ABSPATH) . '/.htaccess';
	$bpsIndexPhpGWIOD = dirname(ABSPATH) . '/index.php';
	?>
	<?php if (substr($sapi_type, 0, 3) == 'cgi' || substr($sapi_type, 0, 9) == 'litespeed' || substr($sapi_type, 0, 7) == 'caudium' || substr($sapi_type, 0, 8) == 'webjames' || substr($sapi_type, 0, 3) == 'tux' || substr($sapi_type, 0, 5) == 'roxen' || substr($sapi_type, 0, 6) == 'thttpd' || substr($sapi_type, 0, 6) == 'phttpd' || substr($sapi_type, 0, 10) == 'continuity' || substr($sapi_type, 0, 6) == 'pi3web' || substr($sapi_type, 0, 6) == 'milter') { // If cgi display the cgi permissions and status table ?>
    <h3><?php _e('CGI Permissions &amp; Status Table'); ?></h3>
    <table class="widefat fixed" style="margin-bottom:20px;">
<thead>
	<tr>
	<th scope="col"><strong><?php _e('Filename')?></strong></th>
	<th scope="col"><strong><?php _e('Permissions &amp; Status')?></strong></th>
    <th scope="col"><strong><?php _e('Last Modified')?></strong></th>
	</tr>
</thead>
<tbody>
<tr>
	<th scope="row" style="border-bottom:none;"><?php _e('Root .htaccess')?></th>
	<td><?php echo bps_flock_pro_statusRH(); ?></td>
    <td><?php echo bps_flock_modTimeRH(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsRootHtaccess; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('wp-config.php')?></th>
	<td><?php echo bps_flock_pro_statusWPC(); ?></td>
    <td><?php echo bps_flock_modTimeWPC(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsWpConfig; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('WP index.php')?></th>
	<td><?php echo bps_flock_pro_statusWPI(); ?></td>
    <td><?php echo bps_flock_modTimeWPI(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsIndexPhp; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('wp-blog-header.php')?></th>
	<td><?php echo bps_flock_pro_statusWPBH(); ?></td>
    <td><?php echo bps_flock_modTimeWPBH(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsWpBlogHeader; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('DR - Root .htaccess')?></th>
	<td><?php echo bps_flock_pro_statusRH_DR(); ?></td>
    <td><?php echo bps_flock_modTimeRH_DR(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsRootHtaccessDR; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('DR - WP index.php')?></th>
	<td><?php echo bps_flock_pro_statusWPI_DR(); ?></td>
    <td><?php echo bps_flock_modTimeWPI_DR(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsIndexPhpDR; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('GWIOD - Root .htaccess')?></th>
	<td><?php echo bps_flock_pro_statusRH_GWIOD(); ?></td>
    <td><?php echo bps_flock_modTimeRH_GWIOD(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsRootHtaccessGWIOD; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('GWIOD - WP index.php')?></th>
	<td><?php echo bps_flock_pro_statusWPI_GWIOD(); ?></td>
    <td><?php echo bps_flock_modTimeWPI_GWIOD(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsIndexPhpGWIOD; ?></td>
    </tr>
</tbody>
</table>
<?php } else { ?>

<h3><?php _e('DSO Permissions &amp; Status Table'); ?></h3>
    <table class="widefat fixed" style="margin-bottom:20px;">
<thead>
	<tr>
	<th scope="col"><strong><?php _e('Filename')?></strong></th>
	<th scope="col"><strong><?php _e('Permissions &amp; Status')?></strong></th>
    <th scope="col"><strong><?php _e('Last Modified')?></strong></th>
	</tr>
</thead>
<tbody>
<tr>
	<th scope="row" style="border-bottom:none;"><?php _e('Root .htaccess')?></th>
	<td><?php echo bps_flock_pro_statusRH_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeRH(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsRootHtaccess; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('wp-config.php')?></th>
	<td><?php echo bps_flock_pro_statusWPC_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeWPC(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsWpConfig; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('WP index.php')?></th>
	<td><?php echo bps_flock_pro_statusWPI_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeWPI(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsIndexPhp; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('wp-blog-header.php')?></th>
	<td><?php echo bps_flock_pro_statusWPBH_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeWPBH(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsWpBlogHeader; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('DR - Root .htaccess')?></th>
	<td><?php echo bps_flock_pro_statusRH_DR_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeRH_DR(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsRootHtaccessDR; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('DR - WP index.php')?></th>
	<td><?php echo bps_flock_pro_statusWPI_DR_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeWPI_DR(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsIndexPhpDR; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('GWIOD - Root .htaccess')?></th>
	<td><?php echo bps_flock_pro_statusRH_GWIOD_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeRH_GWIOD(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsRootHtaccessGWIOD; ?></td>
    </tr>
    <tr>
	<th scope="row" style="border-bottom:none;"><?php _e('GWIOD - WP index.php')?></th>
	<td><?php echo bps_flock_pro_statusWPI_GWIOD_DSO(); ?></td>
    <td><?php echo bps_flock_modTimeWPI_GWIOD(); ?></td>
	</tr>
    <tr>
	<th scope="row" style="padding:0px;"></th>
    <td colspan="2" style="background-color:#fff; padding:0px 0px 0px 6px;"><?php echo $bpsIndexPhpGWIOD; ?></td>
    </tr>
</tbody>
</table>
<?php } ?>
	</td>
  </tr>
   <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
           
<div id="bps-tabs-2" class="bps-tab-page">
<h2><?php _e('Help &amp; FAQ'); ?></h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2841/bulletproof-security-pro/bulletproof-security-pro-overview-video-tutorial/" target="_blank"><?php _e('BPS Pro Overview Video Tutorial'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2835/bulletproof-security-pro/bulletproof-security-pro-features/" target="_blank"><?php _e('BPS Pro Features'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/3201/bulletproof-security-pro/file-lock" target="_blank"><?php _e('File Lock Help Page'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2837/bulletproof-security-pro/bulletproof-security-pro-screenshots/" target="_blank"><?php _e('BPS Pro Screenshots'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2845/bulletproof-security-pro/bulletproof-security-pro-hover-tooltips/" target="_blank"><?php _e('Read Me Help Buttons Posted As Text For Language Translation'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2843/bulletproof-security-pro/bulletproof-security-pro-questions-and-comments/" target="_blank"><?php _e('Post Questions and Comments For Assistance'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
   <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>
            
<div id="AITpro-link">BulletProof Security Pro <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Design">AITpro Website Design</a>
</div>
</div>
</div>