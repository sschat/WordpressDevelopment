<?php
// Direct calls to this file are Forbidden when wp core files are not present
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

// Pending BPS Class for future versions of BPS Pro
// BPS Class vars 
if ( !class_exists('Bulletproof_Security') ) :
	class Bulletproof_Security {
	var $hook 		= 'bulletproof-security';
	var $filename	= 'bulletproof-security/bulletproof-security.php';
	var $longname	= 'BulletProof Security Settings';
	var $shortname	= 'BulletProof Security';
	var $optionname = 'BulletProof';
	var $options;
	var $errors;
}
$bpspro_url = 'http://api.ait-pro.com/';

function bulletproof_save_options() {
	return update_option('bulletproof_security', $this->options);
}

function bulletproof_set_error($code = '', $error = '', $data = '') {
	if ( empty($code) )
		$this->errors = new WP_Error();
	elseif ( is_a($code, 'WP_Error') )
		$this->errors = $code;
	elseif ( is_a($this->errors, 'WP_Error') )
		$this->errors->add($code, $error, $data);
	else
		$this->errors = new WP_Error($code, $error, $data);
}

function bulletproof_get_error($code = '') {
	if ( is_a($this->errors, 'WP_Error') )
	return $this->errors->get_error_message($code);
	return false;
	}

function bps_cuser_errorrs() {
	$bps_settings_updated = '';
	return $bps_settings_updated;
	}

function __e() {
	$language = '';
	$translation = '';
	$text = '';
	return $text;
}
endif;
?>