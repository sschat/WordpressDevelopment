<!-- BEGIN COPY CODE - BPS Error logging code -->
<?php 
// Copy this logging code from BEGIN COPY CODE above to END COPY CODE below and paste it right after <?php get_header(); > in
// your Theme's 404.php template file located in your themes folder /wp-content/themes/your-theme-folder-name/404.php.

if ($_SERVER['REQUEST_METHOD'] == 'POST' || 'GET' || 'HEAD' || 'PUT' || 'DELETE' || 'TRACE' || 'TRACK' || 'DEBUG' || 'OPTIONS' || 'CONNECT' || 'PATCH') {
	$bpsProLog = ABSPATH . 'wp-content/plugins/bulletproof-security/admin/htaccess/http_error_log.txt';
	$timestamp = '['.date('m/d/Y g:i A').']';
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

	$fh = fopen($bpsProLog, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> 404 Error Logged $timestamp <<<<<<<<<<<\r\n");
	@fwrite($fh, 'REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR']."\r\n");
	@fwrite($fh, 'Host Name: '."$hostname\r\n");
	@fwrite($fh, 'HTTP_CLIENT_IP: '.$_SERVER['HTTP_CLIENT_IP']."\r\n");
	@fwrite($fh, 'HTTP_FORWARDED: '.$_SERVER['HTTP_FORWARDED']."\r\n");
	@fwrite($fh, 'HTTP_X_FORWARDED_FOR: '.$_SERVER['HTTP_X_FORWARDED_FOR']."\r\n");
	@fwrite($fh, 'HTTP_X_CLUSTER_CLIENT_IP: '.$_SERVER['HTTP_X_CLUSTER_CLIENT_IP']."\r\n");
 	@fwrite($fh, 'REQUEST_METHOD: '.$_SERVER['REQUEST_METHOD']."\r\n");
 	@fwrite($fh, 'HTTP_REFERER: '.$_SERVER['HTTP_REFERER']."\r\n");
 	@fwrite($fh, 'REQUEST_URI: '.$_SERVER['REQUEST_URI']."\r\n");
 	@fwrite($fh, 'QUERY_STRING: '.$_SERVER['QUERY_STRING']."\r\n");
	@fwrite($fh, 'HTTP_USER_AGENT: '.$_SERVER['HTTP_USER_AGENT']."\r\n");
 	fclose($fh);
	
	} else  {
	// log anything else that triggered a 404 Error
	$fh = fopen($bpsProLog, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> 404 Error Logged $timestamp <<<<<<<<<<<\r\n");
	@fwrite($fh, 'REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR']."\r\n");
	@fwrite($fh, 'Host Name: '."$hostname\r\n");
	@fwrite($fh, 'HTTP_CLIENT_IP: '.$_SERVER['HTTP_CLIENT_IP']."\r\n");
	@fwrite($fh, 'HTTP_FORWARDED: '.$_SERVER['HTTP_FORWARDED']."\r\n");
	@fwrite($fh, 'HTTP_X_FORWARDED_FOR: '.$_SERVER['HTTP_X_FORWARDED_FOR']."\r\n");
	@fwrite($fh, 'HTTP_X_CLUSTER_CLIENT_IP: '.$_SERVER['HTTP_X_CLUSTER_CLIENT_IP']."\r\n");
 	@fwrite($fh, 'REQUEST_METHOD: '.$_SERVER['REQUEST_METHOD']."\r\n");
 	@fwrite($fh, 'HTTP_REFERER: '.$_SERVER['HTTP_REFERER']."\r\n");
 	@fwrite($fh, 'REQUEST_URI: '.$_SERVER['REQUEST_URI']."\r\n");
 	@fwrite($fh, 'QUERY_STRING: '.$_SERVER['QUERY_STRING']."\r\n");
	@fwrite($fh, 'HTTP_USER_AGENT: '.$_SERVER['HTTP_USER_AGENT']."\r\n");
 	fclose($fh);
}
?>
<!-- END COPY CODE - BPS Error logging code -->