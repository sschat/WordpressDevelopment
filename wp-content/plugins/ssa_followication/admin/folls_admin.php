<?php
$PLUGIN = 'FOLLOWICATION';
$PLUGIN_SHORT = 'ssa_folls';

$option_group = $PLUGIN_SHORT . '_theme_option_group';
$option_name = $PLUGIN_SHORT . '_theme_options';

// Load stylesheet and jscript
add_action('admin_init', 'folls_admin_add_init');

function folls_admin_add_init() {

    wp_enqueue_style("follsCss", WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . "/folls-options.css", false, "1.0", "all");

    wp_enqueue_script("follsScript", WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . "/folls-options.js", false, "1.0");
}

// Create custom settings menu
add_action('admin_menu', 'folls_admin_create_menu');

function folls_admin_create_menu() {
    //global $themename;
    //create new top-level menu
    add_options_page(__('FOLLOWICATION Options'), __('FOLLOWICATION'), 'manage_options', basename(__FILE__), 'ssa_folls_settings_page');
}

// Register settings
add_action('admin_init', 'register_folls_settings');

function register_folls_settings() {
    global $themename, $shortname, $version, $ssa_folls_options, $option_group, $option_name;
    //register our settings
    register_setting($option_group, $option_name);
}

// Create theme options˙

global $ssa_folls_options;

$behaviours = array(
    1 => 'none',
    2 => 'sticky',
    3 => 'fade'
);

$location = array(
    1 => 'top',
    2 => 'bottom',
    3 => 'sidebar'
);


$ssa_folls_options = array(
    array("name" => __('General', $PLUGIN_SHORT),
        "type" => "section"),
    array("name" => __('Setup your followication.', $PLUGIN_SHORT),
        "type" => "section-desc"),
    array("type" => "open"),
    array("name" => __("New comment", $PLUGIN_SHORT),
        "desc" => "Notify the user when there is a new comment to his own post ",
        "id" => "tri_comments",
        "type" => "checkbox",
        "std" => ""),
    array("type" => "close"),
    array("name" => __('The looks', $PLUGIN_SHORT),
        "type" => "section"),
    array("name" => __('Set up the notification center.', $PLUGIN_SHORT),
        "type" => "section-desc"),
    array("type" => "open"),
    array("name" => __('Show notifications?', $PLUGIN_SHORT),
        "desc" => "turn this on to enable Notifications",
        "id" => "folls",
        "type" => "checkbox",
        "std" => ""),
    array("name" => __('Behaviour', $PLUGIN_SHORT),
        "desc" => "The way a notification behaves ",
        "id" => "folls_b",
        "type" => "select",
        "options" => $behaviours,
        "std" => "fade"),
    array("name" => __('Loaction', $PLUGIN_SHORT),
        "desc" => "Where does it pop up?",
        "id" => "folls_l",
        "type" => "select",
        "options" => $location,
        "std" => "top"),
    array("type" => "close")
);

function ssa_folls_settings_page() {
    global $themename, $shortname, $version, $ssa_folls_options, $option_group, $option_name;
    ?>

    <div class="wrap">
        <div class="options_wrap">
    <?php screen_icon(); ?><h2><?php echo $PLUGIN; ?> <?php _e('Plugin Options', $PLUGIN_SHORT); ?></h2>
            <p class="top-notice"><?php _e('Customize the notifications ', $PLUGIN_SHORT); ?></p>
    <?php if (isset($_POST['reset'])): ?>
        <?php
        // Delete Settings
        global $wpdb, $themename, $shortname, $version, $ssa_folls_options, $option_group, $option_name;
        delete_option($option_name);
        wp_cache_flush();
        ?>
                <div class="updated fade"><p><strong><?php _e($PLUGIN . ' options reset.'); ?></strong></p></div>

    <?php elseif (isset($_REQUEST['updated'])): ?>
                <div class="updated fade"><p><strong><?php _e($PLUGIN . ' options saved.'); ?></strong></p></div>
    <?php endif; ?>

            <form method="post" action="options.php">

    <?php settings_fields($option_group); ?>

    <?php $options = get_option($option_name); ?>

    <?php
    foreach ($ssa_folls_options as $value) {
        if (isset($value['id'])) {
            $valueid = $value['id'];
        }

        switch ($value['type']) {

            case "section":
                ?>

                            <div class="section_wrap">

                                <h3 class="section_title"><?php echo $value['name']; ?>

                        <?php
                        break;

                    case "section-desc":
                        ?>

                                    <span><?php echo $value['name']; ?></span></h3>

                                <div class="section_body">

                            <?php
                            break;

                        case 'text':
                            ?>

                                    <div class="options_input options_text">

                                        <div class="options_desc"><?php echo $value['desc']; ?></div>

                                        <span class="labels"><label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label></span>

                                        <input name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="<?php echo $value['type']; ?>" value="<?php if (isset($options[$valueid])) {
                    esc_attr_e($options[$valueid]);
                } else {
                    esc_attr_e($value['std']);
                } ?>" />

                                    </div>

                <?php
                break;

            case 'textarea':
                ?>

                                    <div class="options_input options_textarea">

                                        <div class="options_desc"><?php echo $value['desc']; ?></div>

                                        <span class="labels"><label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label></span>

                                        <textarea name="<?php echo $option_name . '[' . $valueid . ']'; ?>" type="<?php echo $option_name . '[' . $valueid . ']'; ?>" cols="" rows=""><?php if (isset($options[$valueid])) {
                        esc_attr_e($options[$valueid]);
                    } else {
                        esc_attr_e($value['std']);
                    } ?></textarea>

                                    </div>

                <?php
                break;

            case 'select':
                ?>

                                    <div class="options_input options_select">

                                        <div class="options_desc"><?php echo $value['desc']; ?></div>

                                        <span class="labels"><label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label></span>

                                        <select name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>">

                <?php foreach ($value['options'] as $option) { ?>

                                                <option <?php if ($options[$valueid] == $option) {
                        echo 'selected="selected"';
                    } ?>><?php echo $option; ?></option><?php } ?>

                                        </select>

                                    </div>

                                    <?php
                                    break;

                                case "radio":
                                    ?>

                                    <div class="options_input options_select">

                                        <div class="options_desc"><?php echo $value['desc']; ?></div>

                                        <span class="labels"><label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label></span>

                                            <?php
                                            foreach ($value['options'] as $key => $option) {
                                                if (isset($options[$valueid])) {
                                                    if ($key == $options[$valueid]) {
                                                        $checked = "checked=\"checked\"";
                                                    } else {
                                                        $checked = "";
                                                    }
                                                } else {
                                                    if ($key == $value['std']) {
                                                        $checked = "checked=\"checked\"";
                                                    } else {
                                                        $checked = "";
                                                    }
                                                }
                                                ?>

                                            <input type="radio" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" name="<?php echo $option_name . '[' . $valueid . ']'; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />

                <?php } ?>

                                    </div>

                                        <?php
                                        break;

                                    case "checkbox":
                                        ?>

                                    <div class="options_input options_checkbox">

                                        <div class="options_desc"><?php echo $value['desc']; ?></div>

                                        <?php if (isset($options[$valueid])) {
                                            $checked = "checked=\"checked\"";
                                        } else {
                                            $checked = "";
                                        } ?>

                                        <input type="checkbox" name="<?php echo $option_name . '[' . $valueid . ']'; ?>" id="<?php echo $option_name . '[' . $valueid . ']'; ?>" value="true" <?php echo $checked; ?> />

                                        <label for="<?php echo $option_name . '[' . $valueid . ']'; ?>"><?php echo $value['name']; ?></label>

                                    </div>

                                    <?php
                                    break;

                                case "close":
                                    ?>

                                </div><!--#section_body-->

                            </div><!--#section_wrap-->

                                        <?php
                                        break;
                                }
                            }
                            ?>

                <span class="submit">
                    <input class="button button-primary" type="submit" name="save" value="<?php _e('Save All Changes', $PLUGIN_SHORT) ?>" />
                </span>
            </form>

            <form method="post" action="">

                <span class="button-right" class="submit">

                    <input class="button button-secondary" type="submit" name="reset" value="<?php _e('Reset/Delete Settings', $PLUGIN_SHORT) ?>" />
                    <input type="hidden" name="action" value="reset" />
                    <span><?php _e('Caution: All entries will be deleted from database. Press when starting afresh or completely removing the theme.', $PLUGIN_SHORT) ?></span>

                </span>

            </form>
        </div><!--#options-wrap-->
    </div>

            <?php
            }

            