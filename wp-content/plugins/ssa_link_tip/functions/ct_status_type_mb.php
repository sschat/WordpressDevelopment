<?php

   
$prefix = 'mylink_';

$meta_box = array(
	'id' => 'mylink',
	'title' => 'Info Link velden',
	'page' => 'mylink',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Link Type',
			'desc' => ' ',
			'id' => $prefix . 'type',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Link Title',
			'desc' => ' ',
			'id' => $prefix . 'title',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Link Author',
			'desc' => ' ',
			'id' => $prefix . 'author',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Link Description',
			'desc' => ' ',
			'id' => $prefix . 'descr',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Link Details',
			'desc' => ' ',
			'id' => $prefix . 'details',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Link Code',
			'desc' => ' ',
			'id' => $prefix . 'code',
			'type' => 'textarea',
			'std' => ''
		),            
//		),
//		array(
//			'name' => 'Select box',
//			'id' => $prefix . 'select',
//			'type' => 'select',
//			'options' => array('Option 1', 'Option 2', 'Option 3')
//		),
//		array(
//			'name' => 'Radio',
//			'id' => $prefix . 'radio',
//			'type' => 'radio',
//			'options' => array(
//				array('name' => 'Name 1', 'value' => 'Value 1'),
//				array('name' => 'Name 2', 'value' => 'Value 2')
//			)
//		),
//		array(
//			'name' => 'Checkbox',
//			'id' => $prefix . 'checkbox',
//			'type' => 'checkbox'
//		)
	)
);

add_action('admin_menu', 'mylink_add_box');







// Add meta box
function mylink_add_box() {
	global $meta_box;

	add_meta_box($meta_box['id'], $meta_box['title'], 'mylink_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}

// Callback function to show fields in meta box
function mylink_show_box() {
	global $meta_box, $post;





	// Use nonce for verification
	echo '<input type="hidden" name="mylink_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);

		echo '<tr>',
				'<th style="width:20%"><label style="font-weight:bold; font-size=10px" for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
		switch ($field['type']) {
			case 'text':
				echo '<span style="font-size:10px;">', $field['desc'], '</span><br />',
                                    '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />';
				break;
			case 'textarea':
				echo '<span style="font-size:10px;">', $field['desc'], '</span><br />',
                                    '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'] , '</textarea>' ;
				break;
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>';
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
				}
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
				break;
		}
		echo 	'<td>',
			'</tr>';
	}

	echo '</table>';
}

add_action('save_post', 'mylink_save_data');

// Save data from meta box
function mylink_save_data($post_id) {
	global $meta_box;

	// verify nonce
	if (!wp_verify_nonce($_POST['mylink_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	foreach ($meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];

		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}