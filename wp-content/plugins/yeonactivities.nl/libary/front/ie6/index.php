<?
// get rid of the IE6

function modify_header() {
	echo '<!--[if lte IE 6]><script name="ie6_script" src="' . plugins_url( 'warning.js' , __FILE__) . '"></script><script>window.onload=function(){e("' . plugins_url( "" , __FILE__) . '/")}</script><![endif]-->
	';
}
	
add_action('wp_head', 'modify_header');

