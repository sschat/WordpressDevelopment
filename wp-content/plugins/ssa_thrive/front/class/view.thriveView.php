<?php

class ThriveView {

    private $results = null;

    public function setResults($results) {

        $this -> results = $results;
    }

    private $content = null;

    public function setContent($content) {

        $this -> content = $content;
    }

    private $message_description = null;

    public function setDescription($x) {

        $this -> message_description = $x;
    }

    private $listUnlockedPosts = null;

    public function setlistUnlockedPosts($x) {

        $this -> listUnlockedPosts = $x;
    }

    public function __construct() {

        $this -> options = get_option('thrive_options');

    }

    public function makeMessageBox() {

        $content .= "<div style='clear:both; display:block; margin:10px 0;'><div class='image' id='" . $this -> object . "'></div>" . $message . "</div>";

        $ruling = '<table class="messageBoxTable">';
        foreach ($this->results as $rule => $details) {

            $ruling .= "
            <tr>
            <td><div class='image' id='" . ($details['passed'] ? "passed" : $details['object']) . "'></div></td>
            <td " . ($details['passed'] ? 'style="color:#999;"' : "") . " >{$details['message']}</td>
            <td>" . ($details['object'] == 'login' ? "" : $details['count'] . " done") . "</td>
            </tr>";

            if (!$details['passed']) {
                $counter = $counter + $details['count'];
                $amounter = $amounter + $details['amount'];
            }

        }
        $ruling .= '</table>';

        // if count is (zero), user has done nothing yet. But showing zero is less tempting then having done the first step already
        // so, we pretent that at least 1 step has been done. (the login action for example)
        // and the amount should then be raised by 1 as well
        // this way, there will always be one count

        $count = $counter;
        $amount = $amounter;

        $counted = (!$count ? 1 : $count + 1);
        $amounted = ($amount + 1);

        // Calculate the procentage
        $progress = round(($counted / $amounted) * 100);

        $this -> meter_width = $progress;
        $this -> meter_txt = $progress . "%";

        $progress_bar = '<div class="meter-wrap">
                            <div class="meter-value" style="background: #ccc url(' . THRIVE_URL . '/front/makeup/images/blue-bar.png) no-repeat top left; width: ' . $this -> meter_width . '%;">
                                <div class="meter-text">
                                    ' . $this -> meter_txt . '
                                </div>
                            </div>
                        </div>';

        $content = $this -> AddStyleBox();
        $content .= "<div style='clear:both; display:block;text-align:center;'><h3>" . $this -> message_description . "</h3></div>";

        $content .= $ruling;

        $content .= $progress_bar;
        $content = "<div class='thrive_msb_box_wrap'><div class='thrive_msb_box'>" . $content . "</div></div>";

        return $content;

    }

    private function AddStyleBox() {

        $width = $this -> options['width'] ? $this -> options['width'] . "px" : "400px";
        $bg_color = $this -> options['bg_color'] ? "#" . $this -> options['bg_color'] : "#aad4ff";
        $font_color = $this -> options['font_color'] ? "#" . $this -> options['font_color'] : "#333";
        $border_radius = $this -> options['border_radius'] ? $this -> options['border_radius'] . "px" : false;

        $css = '<style>.thrive_msb_box_wrap{';

        $css .= "width:$width;
                    background:$bg_color url( '" . THRIVE_URL . "front/makeup/images/top.png') top left repeat-x ;
                    
                    border: 1px solid #555;
                    margin: 0 auto;
                    color: $font_color!important;
                    _font:12px georgia, serif;";

        if ($border_radius) {
            $css .= "/* only for nice browser */
                border-radius: $border_radius;
                -moz-border-radius: $border_radius;
                -webkit-border-radius: $border_radius;";

        }

        $css .= '}';

        $css .= ".thrive_msb_box{
                    padding:30px; 
                    background: url( '" . THRIVE_URL . "front/makeup/images/btm.png') bottom left repeat-x ;";

        if ($border_radius) {
            $css .= "/* only for nice browser */
                border-radius: $border_radius;
                -moz-border-radius: $border_radius;
                -webkit-border-radius: $border_radius;";

        }

        $css .= '}';

        if ($this -> options['icons']) {
            $css .= ".thrive_msb_box #comment.image  { background:none!important; }";
        }

        $css .= "</style> ";

        return $css;

    }

    public function alertUser($level = 0) {

        foreach ($this -> listUnlockedPosts as $UnlockedPost => $value) {

            $count = ($value['unlocked'] ? $value['unlocked'] : 0);
            if ($count)
                $count = count(explode(',', $value['unlocked']));

            // skip this post if we dont want it to show (set by level)
            if ($level && $count > $level)
                continue;

            // get post details in
            $post_details = get_post($UnlockedPost);
            $title = $post_details -> post_title;
            $permalink = get_permalink($UnlockedPost);

            if ($count > 0) {

                $lock_count++;
                $lock_links .= "<li><a href=" . $permalink . ">" . $this -> catch_that_image($UnlockedPost) . "<p>" . $title . "</a></br>" . $count . " more rules to pass. </br>Go check it out!</p></li>";

            } else {

                $unlock_count++;
                $unlock_links .= "<li><a href=" . $permalink . ">" . $this -> catch_that_image($UnlockedPost) . "<p>" . $title . "</a> has been unlocked</p></li>";

            }

        }

        echo '
        <ul class="tabs">
            <li><a href="#" title="unlock_links" class="tab active">Unlocked';

        if ($unlock_count) {
            echo "<span class='iconBadge'>$unlock_count</span>";
        }

        echo ' </a></li>';

        if ($level <> 0) {

            echo '<li><a href="#" title="lock_links" class="tab">Locked';

            if ($lock_count) {
                echo "<div class='iconBadge'>$lock_count</div>";
            }

            echo '</a></li>';

        }

        echo '</ul>
        
        <div id="unlock_links" class="content">
            <ul>
                ' . $unlock_links . '
            </ul>
        </div>
        <div id="lock_links" class="content">
            <ul>
                ' . $lock_links . '
            </ul>
        </div>';

    }

    private function catch_that_image($post_id = 0, $width = 60, $height = 60, $img_script = '') {
        global $wpdb;
        if ($post_id > 0) {

            // select the post content from the db

            $sql = 'SELECT post_content FROM ' . $wpdb -> posts . ' WHERE id = ' . $wpdb -> escape($post_id);
            $row = $wpdb -> get_row($sql);
            $the_content = $row -> post_content;

            if (strlen($the_content)) {

                // use regex to find the src of the image

                preg_match("/<img src\=('|\")(.*)('|\") .*( |)\/>/", $the_content, $matches);
                if (!$matches) {
                    preg_match("/<img class\=\".*\" src\=('|\")(.*)('|\") .*( |)\/>/U", $the_content, $matches);
                }
                if (!$matches) {
                    preg_match("/<img class\=\".*\" title\=\".*\" src\=('|\")(.*)('|\") .*( |)\/>/U", $the_content, $matches);
                }

                $the_image = '';
                $the_image_src = $matches[2];
                $frags = preg_split("/(\"|')/", $the_image_src);
                if (count($frags)) {
                    $the_image_src = $frags[0];
                }

                // if src found, then create a new img tag

                if (strlen($the_image_src)) {
                    if (strlen($img_script)) {

                        // if the src starts with http/https, then strip out server name

                        if (preg_match("/^(http(|s):\/\/)/", $the_image_src)) {
                            $the_image_src = preg_replace("/^(http(|s):\/\/)/", '', $the_image_src);
                            $frags = split("\/", $the_image_src);
                            array_shift($frags);
                            $the_image_src = '/' . join("/", $frags);
                        }
                        $the_image = '<img class="thrive_img_post" alt="" src="' . $img_script . $the_image_src . '" />';
                    } else {
                        $the_image = '<img class="thrive_img_post" alt="" src="' . $the_image_src . '" width="' . $width . '" height="' . $height . '" />';
                    }
                }
                if ($the_image)
                    return $the_image;
            }

            //fall back
            $bloginfo = get_bloginfo('template_directory');
            $thumbURL = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), '');
            $foto = $thumbURL[0];
            if ($foto) {
                $the_image = '<img class="thrive_img_post" src="' . THRIVE_URL . 'makeup/someFile.php?src=' . $foto . '&amp;w=' . $width . '&amp;h=' . $height . '&amp;zc=1"  />';
                return $the_image;
            }

        }
    }

    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
