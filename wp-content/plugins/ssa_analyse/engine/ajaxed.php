<?php


// AJAX the requests
function get_results_a() {

        $date_start =     $_POST['date_start'];
        $date_end =     $_POST['date_end'];
        $s =$_POST['s'];

         // get some data in //
        $results = get_results(1, $date_start, $date_end, 10, true);
        if ( $results )
        {
                $return = '';
                foreach ( $results as $row ){

                        $return .= '<tr  class="reports">';
                        $return .= '<td  class="reports">' . $row->post_id . '</td>';
                        $return .= '<td  class="reports">' . $row->url . '</td>';
                        $return .= '<td  class="reports">' . $row->count . '</td>';
                        $return .= '</tr>';


                        }
        } else  {

                 $return = '<h2>Geen records gevonden. Klopt dat?</h2>';
                
        }


        
        echo $return;

        die();


}
add_action('wp_ajax_get_results_a', 'get_results_a');
add_action('wp_ajax_nopriv_get_results_a', '');//for users that are not logged in.
