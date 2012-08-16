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
	_e('<font color="red"><strong>'.bps_cuser_errors().'</strong></font>');
	}
}

// Pre-Downlaod check for onClick Download popup message
// Check needs to be done first before zip backup is performed - statcache problem - clearstatcache bug with file_exists
if (current_user_can('manage_options')) {
	$bpsZip = WP_CONTENT_DIR . '/bps-backup/bulletproof-security.zip';
	if (file_exists($bpsZip)) {
	$bpsZipCheck = '&radic; A BPS Pro Zip Backup File Exists\n\n';
	} else {
	$bpsZipCheck = '&Chi; A BPS Pro Zip Backup File Does NOT Exist. Click Cancel and create a Zip Backup file to download.\n\n';
	}
}
?>
</div>

<div class="wrap">
<h2 style="margin-left:104px;"><?php _e('BulletProof Security Pro ~ Zip Installation &amp; Backup'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/images/bps-pro-logo.png" style="float:left; padding:0px 8px 0px 0px; margin:-68px 0px 0px 0px;" /></div>
		<ul>
			<li><a href="#bps-tabs-1">Zip Installer &amp; Backup</a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">

<?php
// Zip and backup current version of BPS Pro - save zip to /bps-backup - create .htaccess if file does not exist 
if (isset($_POST['submit-bps-zip-backup']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_zip_backup' );
	
	$bpsBackupHtaccess = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	$filename = WP_CONTENT_DIR . "/bps-backup/bulletproof-security.zip";
	$filenameBack = WP_CONTENT_DIR . '/bps-backup/'.date("F-d-Y--H:i:s").'--bulletproof-security.zip';
	$bpsDir = WP_CONTENT_DIR . '/plugins/bulletproof-security/';

	if (file_exists($filename)) {
	rename($filename, $filenameBack);
	$strBack = str_replace(WP_CONTENT_DIR . '/bps-backup/', '', $filenameBack);
	$text = '<font color="green"><strong>Previous Zip Backup File has been renamed to:  </strong></font><strong>'."$strBack".'</strong><br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	}
		
	$bpsZipBack = new ZipArchive();
	if ($bpsZipBack->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
	$text = '<font color="red"><strong>Cannot open or create'. "$filename".'.</strong></font><br><font color="black"><strong> Either the /wp-content/bps-backup/ folder does not exist or the folder permissions are set too restrictive. The folder permissions should be 755.</strong></font><br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
    exit();
	}
	
	// initialize Iterator = directory to be processed
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$bpsDir"));
	
	// iterate over the directory - add each file found to the archive
	foreach ($iterator as $key=>$value) {
	//$zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");
	$bpsZipBack->addFile(realpath($key), str_replace(WP_CONTENT_DIR . '/plugins/bulletproof-security/', '', $key)) or die ("ERROR: Could not add the file: $key to the Zip Backup. The Zip Backup did not complete successfully. Please manually remove $key from the /bulletproof-security/ folder and try to perform another Zip Backup.");
	}

	_e('<strong>Files Zipped: </strong>' . $bpsZipBack->numFiles . "\n");
	_e('<strong> >>> Status Code: </strong>' . $bpsZipBack->status . "\n");
	//_e('<strong> >>> Status Sys: </strong>' . $zip->statusSys . "\n");
	//_e('<br><strong>Zip File Name: </strong>' . $zip->filename . "\n");

	if ($bpsZipBack->status == '0') { // 0 is good - ZIP_ER_OK  0  /* N No error */
	$text = '<font color="green"><strong>BPS Pro Zip Backup Successful.</strong></font><br><font color="black"><strong> The bulletproof-security.zip file has been saved to: /wp-content/bps-backup/. Click the Download Zip File button to download the bulletproof-security.zip file.</strong></font><br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	} else {
	$text = '<font color="red"><strong>BPS Pro Zip Backup Failed.</strong></font><br><font color="black"><strong> Perform a manual backup using FTP or your Web Host Control Panel before installing a new version of BPS Pro. Please send an email to info@ait-pro.com with the Status Code error number so that we can assist you.</strong></font><br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	$bpsZipBack->close();
	}
	
	if (!file_exists($bpsBackupHtaccess)) {
	$bps_rename_htaccess_backup = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$bps_rename_htaccess_backup_online = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	copy($bps_rename_htaccess_backup, $bps_rename_htaccess_backup_online);
	_e('<br><font color="green"><strong>The BPS Backup Folder .htaccess file was created successfully >>> /wp-content/bps-backup/.htaccess</strong></font><br>');
	}
}

// Download /wp-content/bps-backup/bulletproof-security.zip backup file
if (isset($_POST['bps-pro-zip-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_zip_downloader' );

	$bpsZip = WP_CONTENT_DIR . '/bps-backup/bulletproof-security.zip';

	if (file_exists($bpsZip)) {
	header('Content-Description: File Transfer');
	header('Content-type: application/force-download');
	header('Content-Disposition: attachment; filename="bulletproof-security.zip"');
	}
}

// Enable File Downloading for BPS Backup Folder - writes a new denyall htaccess file with the current IP address
function bpsBackupZipH() {
if (current_user_can('manage_options')) {
	
	$bps_get_IP2 = $_SERVER['REMOTE_ADDR'];
	$denyall_htaccess_file_backup = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	$bps_denyall_content_backup = "order deny,allow\ndeny from all\nallow from $bps_get_IP2";
	
	if (is_writable($denyall_htaccess_file_backup)) {
	if (!$handle = fopen($denyall_htaccess_file_backup, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$denyall_htaccess_file_backup" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_denyall_content_backup) === FALSE) {
        _e('<br><font color="red"><strong>Cannot write to file' . "$denyall_htaccess_file_backup" . '</strong></font>');
        exit;
    }
    _e('<br><font color="green"><strong>File Open, Zip and Downloading is enabled for your IP address only ===' . "$bps_get_IP2" .'</strong></font>');
    fclose($handle);
	} else {
    $text = '<br><strong>The BPS Backup folder .htaccess file >>> <font color="red"> ' . "$denyall_htaccess_file_backup" . ' </font>does not exist.</font><br>When you perform a Zip Backup a new htaccess file will be automatically created for the BPS Backup folder or you can Click <a href="admin.php?page=bulletproof-security/admin/options.php">HERE</a> to go to the B-Core Security Modes page and activate Deny All htaccess Folder Protection For The BPS Backup Folder if you do not want to perform a Zip Backup right now.</strong>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	}
	}
}

// writes current IP Address to .htaccess file on page access / launch
echo bpsBackupZipH();

// bulletproof-security.zip file upload - will only accept filename = bulletproof-security.zip
if (isset($_POST['submit-bps-zip-install']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_zip_installer' );
	
	$bpsZipFilename = 'bulletproof-security.zip';
	$bps_tmp_file = $_FILES['bps_file_zip']['tmp_name'];
	$zip_folder_path = WP_CONTENT_DIR . '/plugins/';
	$bps_uploaded_zip = str_replace('//','/', $zip_folder_path) . $_FILES['bps_file_zip']['name'];
	$bpsZipzUploadFail = $_FILES['bps_file_zip']['name'];
		
	if (!empty($_FILES)) {
	if ($_FILES['bps_file_zip']['name'] == $bpsZipFilename) {
	move_uploaded_file($bps_tmp_file, $bps_uploaded_zip);
		$text = '<font color="green"><strong>Zip File Upload Successful. </strong></font><br>Click the Install Zip Now button to install BulletProof Security Pro.<br>';
		_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
		} else {
		$text = '<font color="red"><strong>Zip File Upload Error. </strong></font><font color="black"><strong>Either a bulletproof-security.zip file has not been selected yet for upload or the file '. "$bpsZipzUploadFail" . ' is not a valid bulletproof-security.zip file. The zip uploader only allows the bulletproof-security.zip file to be uploaded.</strong></font><br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	}
	}
}

// Extracts zip files from uploaded zip and then deletes the uploaded zip file
if (isset($_POST['submit-bps-zip-install2']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_zip_installer2' );

	$bpsZip = new ZipArchive;
	if ($bpsZip->open(WP_CONTENT_DIR . '/plugins/bulletproof-security.zip') === TRUE) {
    $bpsZip->extractTo(WP_CONTENT_DIR . '/plugins/bulletproof-security/');
    $bpsZip->close();
    $text = '<font color="green"><strong>BPS Pro Zip Installation Successful. </strong></font><br>The uploaded bulletproof-security.zip file has been deleted automatically. To reinstall BPS Pro again please upload another bulletproof-security.zip file and click the Install Zip Now button.<br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	unlink(WP_CONTENT_DIR . '/plugins/bulletproof-security.zip');
	} else {
    $text = '<font color="red"><strong>BPS Pro Zip Installation Failed. </strong></font><br><font color="black"><strong>An uploaded bulletproof-security.zip file was not found. Please upload a bulletproof-security.zip file and click the Install Zip Now button. If the zip installation fails after uploading the bulletproof-security.zip file please send an email to info@ait-pro.com so that we can assist you to correct the issue or problem.</strong></font><br>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	}
}

// Pre-Downlaod check for onClick Download popup message
function bps_check_perms_install() {
	clearstatcache();
	$path = WP_CONTENT_DIR . '/bps-backup/';
	$current_perms = @substr(sprintf(".%o.", fileperms($path)), -4);
	if ($current_perms == '755.') {
	_e('&radic; The BPS Backup Folder Permission Is Set To: 755\n\n');
	} else {
	_e('&Chi; The BPS Backup folder permission MUST be set to 755 or you will see a 403 Forbidden error and NOT be able to download the zip file.\n\\n&Chi; The current BPS Backup folder permission is set to:  ');
	return $current_perms;
	}
}
?> 
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    
    <h2 style="float:left;"><?php _e('Zip Backup &amp; Download Zip'); ?></h2>
    <div id="bps-modal-content1" title="<?php _e('Zip Install, Backup &amp; Download'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Zip Backup</strong><br>Click on the Zip & Backup Files button to back up your current installation of BPS Pro. This is a full backup of the entire /bulletproof-security folder. All BPS Pro files will be zipped and backed up to /wp-content/bps-backup/bulletproof-security.zip and if you have added additional files to the /bulletproof-security folder then they will also be backed up in the bulletproof-security.zip file. Backups are zipped and are stored in the /bps-backup folder. Zip Backups are renamed and not overwritten. Each time that you perform a zip backup the previous backup zip file that you created is renamed using a date and time file naming format. <strong>Example: August-31-2011--03:54:02--bulletproof-security.zip.</strong><br><br><strong>Download Zip</strong><br>Click on the Download Zip File button to download bulletproof-security.zip file to your computer. You will see a popup window that performs 2 checks. <strong>1. </strong>If the bulletproof-security.php file exists and <strong>2. </strong>If the folder permissions are set correctly for the /bps-backup folder.<br><br><strong>Upload Zip</strong><br>Click on the Choose File or Browse button. Navigate / browse / go to the folder on your computer where the bulletproof-security.zip file is located and select it and then click the Open button. You should now see the bulletproof-security.zip file name displayed in the File Upload window. Click the Upload Zip File button and you should see this message - <strong>Zip File Upload Successful. Click the Install Now button to install BulletProof Security Pro</strong> - when the Zip file upload is completed.<br><br><strong>Install Zip</strong><br>Click the Install Zip Now button. You will see a popup warning about performing a Zip backup first before proceeding with the Zip installation. You should always perform a zip backup before performing a Zip installation for good measure. The zip installer is designed to overwrite all the existing BPS Pro plugin files and BPS Pro master files. Your currently active htaccess files and php.ini files are not affected, changed or overwritten and your website security status is not affected or changed when performing an installation. All options settings are saved to your WordPress database and are not affected and changed when performing zip installations. As long as you have performed a zip backup then you can easily download and unzip it and retrieve any BPS files that were backed up. Zip backups are not overwritten and are stored in your /bps-backup folder until you delete them.'); ?></p>
</div>

<button id="bps-open-modal1" class="bps-modal-button"><?php _e('Read Me'); ?></button>
 
<div id="bpsInstallBackup" style="border-top:1px solid #999999; margin-top:12px;">
<form name="BPS-Zip-Backup" action="admin.php?page=bulletproof-security/admin/install/installation.php" method="post" enctype="multipart/form-data">
<?php wp_nonce_field('bulletproof_security_zip_backup'); ?>
<p class="submit">
<input type="submit" name="submit-bps-zip-backup" class="button" value="<?php esc_attr_e('Zip &amp; Backup Files') ?>" />
</p>
</form>

<form name="bps-pro-zip-download" action="<?php echo get_site_url() .'/wp-content/bps-backup/bulletproof-security.zip'; ?>" method="post" enctype="multipart/form-data">
<?php wp_nonce_field('bulletproof_security_zip_downloader'); ?>
<p class="submit">
<input type="submit" name="bps-pro-zip-download" class="button" value="<?php esc_attr_e('Download Zip File') ?>" onClick="return confirm('<?php _e('Pre-Download Checks:\n\n'); 	echo $bpsZipCheck; echo bps_check_perms_install(); _e('\n\nClick OK to Download the Zip file now or click Cancel to cancel the Zip file download.'); ?>')" />
</p>
</form>
</div>
	</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h2><?php _e('Upload Zip &amp; Install Zip'); ?></h2>
<div id="bpsInstallZip" style="border-top:1px solid #999999;">

<form name="BPS-Zip-Uploader" action="admin.php?page=bulletproof-security/admin/install/installation.php" method="post" enctype="multipart/form-data">
<?php wp_nonce_field('bulletproof_security_zip_installer'); ?>
<p class="submit">
<input type="file" name="bps_file_zip" id="bps_file_zip"  />
<input type="submit" name="submit-bps-zip-install" class="button" value="<?php esc_attr_e('Upload Zip File') ?>" />
</p>
</form>

<form name="BPS-Zip-Installer" action="admin.php?page=bulletproof-security/admin/install/installation.php" method="post" enctype="multipart/form-data">
<?php wp_nonce_field('bulletproof_security_zip_installer2'); ?>
<p class="submit">
<input type="submit" name="submit-bps-zip-install2" value="<?php _e('Install Zip Now'); ?>" class="button" onClick="return confirm('<?php _e('Clicking OK will ONLY overwrite the BPS Pro plugin files and BPS Log files. Your .htaccess files and custom php.ini file will NOT be overwritten or changed in any way.\n\nIf you want to keep your old PHP Error Log or your HTTP Error Log then perform a Zip Backup before performing this installation. You can also download the Zip backup to your computer.\n\nClick OK to install BPS Pro now or click Cancel to cancel the installation.'); ?>')" />
</p>
</form>

</div>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="AITpro-link">BulletProof Security Pro <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Design">AITpro Website Design</a>
</div>
</div>
</div>