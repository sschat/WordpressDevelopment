<?php



/*
 *
 * Class for alert_box Widget
 *
 *
 */

class alert_box extends wp_widget {

    function alert_box() {
        $this->wp_widget('alert_box', 'Thrive Alert box');
    }

    function form($settings) {
        $settings = wp_parse_args((array) $settings, array('title' => '', 'post_title' => '', 'comments_title' => ''));
        $header = strip_tags($settings['header']);
        
        ?>
        <p><label for="<?php echo $this->get_field_id('header'); ?>">Header <input id="<?php echo $this->get_field_id('header'); ?>" name="<?php echo $this->get_field_name('header'); ?>" type="text" value="<?php echo attribute_escape($header); ?>" /></label></p>
       
        <?
    }

    function widget($args, $settings) {
        extract($args, EXTR_SKIP);

        global $wpdb;

        echo $before_widget;

        $header = empty($settings['header']) ? '&nbsp;' : apply_filters('widget_title', $settings['header']);

        if (!empty($header)) {
            echo $before_title . $header . $after_title;
        };

         echo '<div class="thriveAlertBox" >     
         
         <div id="tabbed_box_1" class="tabbed_box">
        
        <div class="tabbed_area">
        </div>
        </div>
        </div>';
        

        echo $after_widget;
    }

    function update($new_settings, $old_settings) {
        $settings = $old_settings;
        $settings['header'] = strip_tags($new_settings['header']);
        
        return $settings;
    }

}