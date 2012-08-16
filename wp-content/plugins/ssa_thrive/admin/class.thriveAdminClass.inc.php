<?php

class thriveAdminClass {

    /**
     * A public variable
     *
     * @objects array stores the possibilities for the rules
     *
     */
    public $objects = array("post", "comment", "login", "role");

    /**
     *
     * upon class instantiation triggers the complete menu structure
     * @return void
     *
     * */
    public function __construct() {

        add_action('widgets_init', array($this, 'widgets_init'));

        add_action('admin_menu', array($this, 'thrivemenu'));

        add_action('save_post', array($this, 'detect_shortcode'));

    }

    public function widgets_init() {
        register_widget('alert_box');
    }

    /**
     *
     * Build up the admin menu structure
     * @return void
     *
     **/
    public function thrivemenu() {

        $rules_pages = add_menu_page('Thrive', 'Thrive', 'manage_options', 'page.rules', array($this, 'thriverules_page'));
        $setting_page = add_submenu_page('page.rules', 'Settings', 'Settings', 'manage_options', 'page.main', array($this, 'thrivemain_page'));
        //call register settings function
        add_action('admin_init', array($this, 'thrive_settings_init'));

        // Admin head action only when this page is called
        add_action('admin_enqueue_scripts', array($this, 'thrive_admin_scripts'));

    }

    /**
     *
     * function will ONLY be called for the setting page
     * use for loading scripts etc
     * @return void
     *
     **/
    public function thrive_admin_scripts($hook) {

        if ($hook == "thrive_page_page.main") {

            wp_enqueue_script("color_picker_js", THRIVE_URL . '/admin/jpicker-1.1.6/jpicker-1.1.6.min.js');
            wp_enqueue_script("jquery_tools_js", THRIVE_URL . '/admin/js/jquery.tools.min.js');

            wp_enqueue_style("color_picker_css", THRIVE_URL . '/admin/jpicker-1.1.6/css/jpicker-1.1.6.min.css');
            wp_enqueue_style("tabs_css", THRIVE_URL . '/admin/css/admin.css');
        }

        if ($hook == "toplevel_page_page.rules") {

            // wp_enqueue_script("jquery");
            wp_enqueue_script("jquery_tools_js", THRIVE_URL . '/admin/js/jquery.tools.min.js');
            wp_enqueue_style("tabs_css", THRIVE_URL . '/admin/css/admin.css');
        }

    }

    /**
     *
     * Register the indiviuel settings for the admin pages. Add yours here and define it in a new function
     * @return void
     *
     **/
    public function thrive_settings_init() {

        register_setting('thrive_options', 'thrive_options');
        register_setting('thrive_used_posts', 'thrive_used_posts');

        add_settings_section('thrive_main', 'Thrive Settings', array($this, 'thrive_settings_text'), 'page.main');
        add_settings_field('thrive_show_archive', 'Show in Archive?', array($this, 'thrive_show_archive'), 'page.main', 'thrive_main', array('label_for' => 'thrive_options[show_archive]'));
        add_settings_field('thrive_min_words', 'Minimal word count', array($this, 'thrive_min_words'), 'page.main', 'thrive_main', array('label_for' => 'thrive_options[min_words]'));
        add_settings_field('thrive_approved', 'Approved?', array($this, 'thrive_approved'), 'page.main', 'thrive_main', array('label_for' => 'thrive_options[approved]'));

        add_settings_section('thrive_box', 'Thrive Message Box', array($this, 'thrive_settings_text'), 'page.main');
        add_settings_field('icons', 'Icons', array($this, 'thrive_icons'), 'page.main', 'thrive_box', array('label_for' => 'thrive_options[icons]'));
        add_settings_field('width', 'Box Width', array($this, 'thrive_width'), 'page.main', 'thrive_box', array('label_for' => 'thrive_options[width]'));
        add_settings_field('bg_color', 'Background Color', array($this, 'thrive_bg_color'), 'page.main', 'thrive_box', array('label_for' => 'thrive_options[bg_color]'));
        add_settings_field('font_color', 'Font Color', array($this, 'thrive_font_color'), 'page.main', 'thrive_box', array('label_for' => 'thrive_options[font_color]'));
        add_settings_field('border_radius', 'Border radius', array($this, 'thrive_border_radius'), 'page.main', 'thrive_box', array('label_for' => 'thrive_options[border_radius]'));

        add_settings_section('thrive_alert', 'Thrive Alert Box', array($this, 'thrive_settings_text'), 'page.main');
        add_settings_field('show_alert_box', 'Show Alert Box?', array($this, 'thrive_show_alert_box'), 'page.main', 'thrive_alert', array('label_for' => 'thrive_options[show_alert_box]'));
        add_settings_field('alert_level', 'User alert Level', array($this, 'thrive_alert_level'), 'page.main', 'thrive_alert', array('label_for' => 'thrive_options[alert_level]'));

    }

    /**
     *
     * Title text for the setting page
     *
     **/
    public function thrive_settings_text() {
        echo '<p>Customize the behaviour of your Thrive</p>';
    }

    /**
     *
     * Option for showing content only in "single" page
     *
     **/
    public function thrive_show_archive() {

        $options = get_option('thrive_options');
        echo "<input id='thrive_show_archive' name='thrive_options[show_archive]' type='checkbox' value='1' " . ($options['show_archive'] ? 'checked' : '') . "/>
        <span class='description'>Normally content is only shown in a single post or page'. Enable this, and it shows in archive / lists</span> ";

    }

    /**
     *
     * Option for showing content only with approved comments / posts
     *
     **/
    public function thrive_approved() {

        $options = get_option('thrive_options');
        echo "<input id='thrive_show_archive' name='thrive_options[approved]' type='checkbox' value='1' " . ($options['approved'] ? 'checked' : '') . "/>
        <span class='description'>Should the users comments / posts first be approved by Admin? (this slows down the user experience)</span> ";

    }

    /**
     *
     * Option for content only when a minimum count of words is reached
     *
     **/
    public function thrive_min_words() {
        $options = get_option('thrive_options');

        echo "<input id='thrive_min_words' name='thrive_options[min_words]' type='text' value='" . $options['min_words'] . "' />
        <span class='description'>Posts / Comments are only valid when they have more then this count of words</span> ";
    }

    /**
     *
     * Option for styling: Icons in Box on or off
     *
     **/
    public function thrive_icons() {
        $options = get_option('thrive_options');

        echo "<input id='thrive_icons' name='thrive_options[icons]' type='checkbox' value='1' " . ($options['icons'] ? 'checked' : '') . "/>
        <span class='description'>Disable the little icons?</span> ";
    }

    /**
     *
     * Option for styling: Box Width
     *
     **/
    public function thrive_width() {
        $options = get_option('thrive_options');

        echo "<input id='thrive_width' name='thrive_options[width]' type='range' value='" . $options['width'] . "' min='200' max='600' step='5'/>
        <span class='description'>Choose the width of the box (default: 400)</span> ";
    }

    /**
     *
     * Option for styling: Background color
     *
     **/
    public function thrive_bg_color() {
        $options = get_option('thrive_options');
        echo "<input id='thrive_bg_color' class='color_picker' name='thrive_options[bg_color]' type='text' value='" . $options['bg_color'] . "'/>
        <span class='description'>Choose your background color</span> ";
    }

    /**
     *
     * Option for styling: Font color
     *
     **/
    public function thrive_font_color() {
        $options = get_option('thrive_options');
        echo "<input id='thrive_font_color' class='color_picker' name='thrive_options[font_color]' type='text' value='" . $options['font_color'] . "'/>
        <span class='description'>Choose your font color</span> ";
    }

    /**
     *
     * Option for styling: Border Radius
     *
     **/
    public function thrive_border_radius() {
        $options = get_option('thrive_options');
        echo "<input id='thrive_border_radius' name='thrive_options[border_radius]' type='range' value='" . $options['border_radius'] . "'  min='0' max='15' />
        <span class='description'>Border radius (empty to disable)</span> ";
    }

    /**
     *
     * Option for showing the alert box to the user
     *
     **/
    public function thrive_show_alert_box() {

        $options = get_option('thrive_options');
        echo "<input id='show_alert_box' name='thrive_options[show_alert_box]' type='checkbox' value='1' " . ($options['show_alert_box'] ? 'checked' : '') . "/>
        <span class='description'>Show the user what is unlocked or not</span> ";

    }

    /**
     *
     * Option for level of Alert (how many rules are still open)
     *
     **/
    public function thrive_alert_level() {
        $options = get_option('thrive_options');
        echo "<input id='alert_level' name='thrive_options[alert_level]' type='range' value='" . $options['alert_level'] . "'  min='0' max='5' />
        <span class='description'>Alert level (0 to show only the unlocked ones)</span> ";
    }

    /**
     *
     * Validating the input (disabled for now)
     *
     **/
    public function thrive_options_validate($input) {
        $options = get_option('thrive_options');

        // clean up
        $options['msgbx_color'] = trim($input['msgbx_color']);

        return $options;

        // use as example, but not really used
        if (!preg_match('/^[a-z0-9]{32}$/i', $options['msgbx_color'])) {
            $options['msgbx_color'] = '';
        }
        return $options;
    }

    /**
     *
     * add some checking when a post is saved
     * see if the shortcode is used, if so, save it
     *
     *
     */
    public function detect_shortcode($post_id) {

        $options = (array)get_option('thrive_used_posts');
        if ($options[$post_id])
            unset($options[$post_id]);

        $post_id = wp_is_post_revision($post_id) ? wp_is_post_revision($post_id) : $post_id;
        $post = get_post($post_id);

        $re2 = '(\\[thrive)(.*?)';
        # Word 1
        $re7 = "rule\\=.([,0-9 ]*).";

        if ($c = preg_match_all("/" . $re2 . $re7 . "/is", $post -> post_content, $matches)) {

            //thrive found?
            if ($matches[1][0]) {

                //what rules are used and strip it?
                $rules = explode(',', $matches[3][0]);
                array_walk($rules, array($this, 'trim_value'));
                $rules = implode(',', $rules);

                // add this to our list of posts using the shortcode
                $options[$post_id] = $rules;

            }

        }

        update_option('thrive_used_posts', $options);

        // clear the user status, to regenerate the alertbox
        // todo: clear every row? or maybe a slimmer solution
        global $wpdb;
        $wpdb -> query($wpdb -> prepare("DELETE FROM {$wpdb->prefix}thrive_users;"));

    }

    private function trim_value(&$value) {

        $value = trim($value);

    }

    /**
     *
     * Building the actual pages via html
     * @return void
     *
     **/
    public function thrivemain_page() {

        include (THRIVE_DIR . 'admin/pages/page.main.php');
    }

    public function thriverules_page() {

        include (THRIVE_DIR . 'admin/pages/page.rules.php');
    }

    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
