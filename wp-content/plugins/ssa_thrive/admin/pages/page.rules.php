<script type="text/javascript">
	jQuery(document).ready(function($) {

		$("ul.tabs").tabs("div.panes > div");

	});

</script>
<div class="wrap">
	<div style="background:#ECECEC;border:1px solid #CCC;padding:10px;margin:15px 0; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
		<div id="icon-users" class="icon32">
			<br/>
		</div>
		<h2>Thrive your community</h2>
	</div>
	<!-- the tabs -->
	<ul class="tabs">
		<li>
			<a href="#">Example 1</a>
		</li>
		<li>
			<a href="#">Example 2</a>
		</li>
		<li>
			<a href="#">Contact</a>
		</li>
	</ul>
	<!-- tab "panes" -->
	<div class="panes">
		<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
			<h3>Example 1: Show content only when user is logged in</h3>
			<h4>Step 1</h4>
			<ul>
				<li>
					Create a new rule
				</li>
				<li>
					Set the <strong>object</strong> as "login"
				</li>
				<li>
					Provide a basic <strong>user Message</strong> about this rule to get people to understand what you want them to do
				</li>
				<li>
					Save new rule and remember the rule ID
				</li>
			</ul>
			<h4>Step 2: Modify your post</h4>
			<ul>
				<li>
					Enclose your secret content with the shortcode [thrive rule="x" descr="zzz"] 
				</li>
				<li>
					<strong>x is the ID of your new rule</strong>, and <strong>zzz is a description of WHAT the user gets when unlocked</strong>
				</li>
                                                                                <li>
					eg: <i>[thrive rule="1" descr="nice tutorial waiting for you here!"]Your tutorial video plus your embedded code[/thrive]</i>
				</li>
				<li>
					Save your post, and test
				</li>
				<li>
					Multiple rules? [thrive rule="1,2" descr="nice description on what to expect"]  (Rule 1 AND 2 need to be passed by user)
				</li>
			</ul>
		</div>
		<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
			<h3>Example 2: Show content only when user made a comment</h3>
			<h4>Step 1</h4>
			<ul>
				<li>
					Create a new rule
				</li>
				<li>
					Set the <strong>object</strong> as "comment"
				</li>
				<li>
					Provide a nice <strong>user Message</strong> to get people to understand what you want them to do
				</li>
				<li>
					AND tell them also what they get in return! eg: <i>"Speak your mind! And we see ours as well</i>
				</li>
				<li>
					Set the <strong>amount</strong> to how many comments you want them have before you open up your content
				</li>
				<li>
					The field <strong>Days</strong> you can fill if you want some time limit for this comment. This might prevent users having old comments and still enjoy your fresh content
				</li>
				<li>
					Save new rule and remember the rule ID
				</li>
			</ul>
			<h4>Step 2: Modify your post</h4>
			<ul>
				<li>
					Enclose your secret content with the shortcode [thrive rule="x"] <strong>x is the ID of your new rule</strong>
				</li>
				<li>
					eg: <i>[thrive rules="1"]Your tutorial video plus your embedded code[/thrive]</i>
				</li>
				<li>
					Save your post, and test
				</li>
				<li>
					Multiple rules? [thrive rule="1,2"]  (Rule 1 AND 2 need to be passed by user)
				</li>
			</ul>
		</div>
		<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
			<h3>Any issues or questions?</h3>
			Contact me at <strong>sander@silverspringactivities.nl</strong>
		</div>
	</div>
	<?php
global $wpdb;

if ($_POST['submit-rule']) 
{

    $wpdb->query($wpdb->prepare(
    "INSERT INTO {$wpdb->prefix}thrive_rules
    (  rule_id, descr, amount, object, object_id, role, days, price_id, message )
    VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s )
    ON DUPLICATE KEY
    UPDATE
    
    descr=%s,
    amount=%s,
    object=%s,
    object_id=%s,
    role=%s,
    days=%s,
    price_id=%s,
    message=%s"
    , $_POST['id'], $_POST['descr'], $_POST['amount'], $_POST['object'], $_POST['object_id'], $_POST['role'], $_POST['days'], $_POST['shortcode'], $_POST['message'], $_POST['descr'], $_POST['amount'], $_POST['object'], $_POST['object_id'], $_POST['role'], $_POST['days'], $_POST['shortcode'], $_POST['message']));


}

if ($_GET['action'] == 'delete') {
if (!$_GET['rule']) {
echo "<h3>Hmm, iets fout gegaan?</h3>";
} else {

$x = $wpdb->query($wpdb->prepare(
"DELETE FROM {$wpdb->prefix}thrive_rules
WHERE rule_id = '%s'", $_GET['rule']
));
} // einde rule
}
if ($_GET['action'] == 'edit') {
if (!$_GET['rule']) {
echo "<h3>Hmm, iets fout gegaan?</h3>";
}
}
if ($_GET['action'] && $_GET['action'] != 'delete') {

if ($_GET['rule']) {
$sql = 'SELECT * FROM ' . $wpdb->prefix . 'thrive_rules where rule_id=' . $_GET['rule'] . ';';

$data = $wpdb->get_row($sql);

}
	?>

	<form id="add-rule" method="post" action="<?= get_admin_url();?>admin.php?page=page.rules">
		<table class="form-table" >
			<tr valign="top">
				<th scope="row"><label for="descr">Description</label></th>
				<td>				<textarea id="descr" name="descr" cols="40" rows="5"><?= $data->descr ?></textarea><span class="description">Rule description</span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="message">User Message</label></th>
				<td>				<textarea id="message" name="message" cols="40" rows="5"><?= $data->message ?></textarea><span class="description">What will the user see in his box?</span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="amount">Amount</label></th>
				<td>
				<input type="text" value="<?= $data->amount ?>" class="regular-text" id="amount" name="amount" />
				<span class="description">How many objects should exist</span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="object">Object</label></th>
				<td>
				<select id="object" name="object">
					<option value=""><?php   echo esc_attr(__('Select Type'));?></option>
					<?php

                    foreach ($this->objects as $object) {
                        $option = '<option value="' . $object . '"' . ($data -> object == $object ? "selected" : "") . '>';
                        $option .= $object;
                        $option .= '</option>';
                        echo $option;
                    }
					?>
				</select><span class="description">What object (post, comment...)</span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="object">Object ID</label></th>
				<td>
				<select id="object_id" name="object_id">
					<option value=""><?php   echo esc_attr(__('Select Post'));?></option>
					<?php
                    //get all categories
                    $args = array('orderby' => 'post_date', 'order' => 'DESC', 'post_type' => 'post', 'post_status' => 'publish');
                    $posts_array = get_posts($args);
                    foreach ($posts_array as $post) {
                        $option = '<option value="' . $post -> ID . '"' . ($data -> object_id == $post -> ID ? "selected" : "") . '>';
                        $option .= $post -> post_title;
                        $option .= '</option>';
                        echo $option;
                    }
					?>
				</select><span class="description">Belonging to a specific Post or Page?</span></td>
			</tr>
			<tr valign="top">
                <th scope="row"><label for="object">Role</label></th>
                <td>
                <select id="role" name="role">
                    <option value=""><?php   echo esc_attr(__('Select role'));?></option>
                    <?php
                    //get all categories
                     global $wp_roles;
                    $roles = $wp_roles->get_names();
                    
                    foreach ($roles as $role) {
                        $option = '<option value="' . $role . '"' . ($data -> role == $role ? "selected" : "") . '>';
                        $option .= $role;
                        $option .= '</option>';
                        echo $option;
                    }
     
                    ?>
                </select><span class="description">What role can unlock this content?</span></td>
                
            </tr>
			<tr valign="top">
				<th scope="row"><label for="days">Days</label></th>
				<td>
				<input type="text" value="<?= $data->days ?>" class="regular-text" id="days" name="days" />
				<span class="description">Should this be within a timeframe?</span></td>
			</tr>
		</table>
		<input type="hidden" value="<?= $data->rule_id ?>" id="id" name="id"  />
		<input class="button-primary" type="submit" name="submit-rule" value="<?= $_GET['action'] ?>" />
		<a class="button-secondary" href="?page=page.rules" title="No Rule">Exit</a>
	</form>
	<?
    // einde action
    } else {

    //Our class extends the WP_List_Table class, so we need to make sure that it's there
    if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }
    require_once( THRIVE_DIR . '/admin/lists/list.rules.php' );

    //Prepare Table of elements
    $wp_rule_table = new Rule_Table();
    $wp_rule_table->prepare_items();

    //Table of elements
?>

<form id="rules-filter" method="get">
<!-- For plugins, we also need to ensure that the form posts back to our current page -->
<input type="hidden" name="page" value="<?php echo $_REQUEST['page']
	?>" />
	<!-- Now we can render the completed list table -->
	<?php $wp_rule_table->display() ?>
	</form> <a class="button-secondary" href="?page=page.rules&action=insert" title="New Rule">New Rule</a>
	<? }// or edit stuff?>
</div>