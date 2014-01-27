<?php

/**
 * gives all meta related to the user
 * @param  int $user_id
 * @return array
 */
function get_all_user_meta( $user_id ) {
	global $wpdb;
	$query = "SELECT * FROM " . $wpdb->prefix . "usermeta WHERE user_id = $user_id";
	$rows = $wpdb->get_results( $query );
	foreach ($rows as $key => $row) {
		$metas[$row->meta_key] = $row->meta_value;
	}
	if(is_array($metas)) {
		return $metas;
	}
	return false;
}
 
/**
* stop Events Manager Pro requiring booking related fields
* when user admin is editing user (e.g. changing user role)
* @param bool $result
* @param string $field
* @param string $value
* @param EM_Form $EM_Form
* @return bool
*/
function empFormValidateField($result, $field, $value, $EM_Form) {
    // if field has validation error and user is a user admin, ignore error
    if (!$result && current_user_can('edit_users')) {
        $result = true;
        array_pop($EM_Form->errors);
    }
 
    return $result;
}
add_filter('emp_form_validate_field', 'empFormValidateField', 10, 4);