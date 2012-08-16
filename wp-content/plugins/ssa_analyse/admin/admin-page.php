<?php
$PLUGIN = 'STATS';
$PLUGIN_SHORT = 'ssa_stats';

$option_group = $PLUGIN_SHORT.'_theme_option_group';
$option_name = $PLUGIN_SHORT.'_theme_options';

// Load stylesheet and jscript
add_action('admin_init', 'stats_admin_add_init');

function stats_admin_add_init() {

	wp_enqueue_style("statsCss",  WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . "/options.css", false, "1.0", "all");

        // Get the jquery UI elements
        wp_enqueue_style("jQueryUiCss",  "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/start/jquery-ui.css", false, "1.0", "all");
        wp_enqueue_script("jQueryUiScript",  "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js", false, "1.0");

        // TODO  Get the Google Chart
        //wp_enqueue_script("GoogleScript",  "https://www.google.com/jsapi", false, "1.0");
	//wp_enqueue_script("statsScript",  WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) ."/options.js", false, "1.0");

}

// Create custom settings menu
add_action('admin_menu', 'stats_admin_create_menu');

function stats_admin_create_menu() {
	global $PLUGIN;
	//create new top-level menu
	//add_options_page( __( $PLUGIN.' Options' ), __( $PLUGIN ), 'manage_options', basename(__FILE__), 'ssa_stats_settings_page' );
        add_menu_page( __( $PLUGIN.' Options' ), __( $PLUGIN ), 'manage_options', basename(__FILE__), 'ssa_stats_settings_page'  );
}

// Register settings
add_action( 'admin_init', 'register_stats_settings' );

function register_stats_settings() {
   global $themename, $shortname, $version, $ssa_stats_options, $option_group, $option_name;
  	//register our settings
	register_setting( $option_group, $option_name);
}



// Create theme options˙

global $ssa_stats_options;


function ssa_stats_settings_page() {
   global $PLUGIN, $PLUGIN_SHORT, $version, $ssa_stats_options, $option_group, $option_name;
?>

<div class="wrap">
<div class="options_wrap">
<h2><?php echo $PLUGIN; ?> <?php _e(' Options',$PLUGIN_SHORT); ?></h2>



<?
// get these values by form
$date_start = date('y-m-d', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")) );
$date_end =  date('y-m-d');
?>
<script>
	jQuery(document).ready(function($) {
		$( "#date_start" ).datepicker({
                    dateFormat: 'y-mm-dd',
                    onSelect: function(date_start, inst) {
                    
                    var date_end = $('#date_end').val();

                    $.ajax({
                        url:"/wp-admin/admin-ajax.php",
                        type:'POST',
                        data:'action=get_results_a&date_start=' + date_start + '&date_end=' + date_end + '&s=' + this.id,

                        success:function(results)
                        {
                            if(results){
                                    $('#tbody').html('').html(results).fadeIn();
                            }

                            data.addRows([
                                        ['Hello', 4]]);

                                    
                        }
                    }); // ajax
                   } // onselect
                });
                $( "#date_end" ).datepicker({ 
                    dateFormat: 'y-mm-dd' ,
                    onSelect: function(date_end, inst) {

                    var date_start = $('#date_start').val();

                    $.ajax({
                        url:"/wp-admin/admin-ajax.php",
                        type:'POST',
                        data:'action=get_results_a&date_start=' + date_start + '&date_end=' + date_end + '&s=' + this.id,

                        success:function(results)
                        {
                            if(results){
                                    $('#tbody').html('').html(results).fadeIn();
                            }
                        }
                    }); // ajax
                   } // onselect
                });





	});

         
</script>

<?
$ref =  WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . "/../reports/overzicht.php";

?>

<form class="inputDates" style="margin:20px" action="<?=$ref?>" method="post">
    <label for="date_start">Begin Datum</label>
    <input type="text" id="date_start" name="date_start" value="<?=$date_start;?>"/>
    <label for="date_end">Eind Datum</label>
    <input type="text" id="date_end" name="date_end" value="<?=$date_end;?>"/>

    <input type="submit" class="exportbutton" value="PDF &nbsp;▶" />
</form>





</div><!--#options-wrap-->



</div>

<?php }
