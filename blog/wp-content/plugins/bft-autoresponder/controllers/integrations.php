<?php
class BFTIntegrations {
	// currently integrates with contact form 7
	static function contact_form() {
		global $wpdb;		
		
		$shortcode_atts = '';
		if(!empty($_POST['checked_by_default'])) {
			$shortcode_atts .= ' checked="true" ';
		}
		if(!empty($_POST['required'])) {
			$shortcode_atts .= ' required="true" ';
		}
		if(!empty($_POST['classes'])) {
			$shortcode_atts .= ' css_classes="'.$_POST['classes'].'" ';
		}
		if(!empty($_POST['html_id'])) {
			$shortcode_atts .= ' html_id="'.$_POST['html_id'].'" ';
		}
		
		// change your-name and your-email to custom field names
		if(!empty($_POST['change_defaults'])) {
			update_option('bft_cf7_name_field', $_POST['cf7_name_field']);
			update_option('bft_cf7_email_field', $_POST['cf7_email_field']);
		}
		
		// load default field names
		$custom_name_field_name = get_option('bft_cf7_name_field');
		$name_name = !empty($custom_name_field_name) ? $custom_name_field_name : 'your-name'; 
		$custom_email_field_name = get_option('bft_cf7_email_field');
		$email_name = !empty($custom_email_field_name) ? $custom_email_field_name : 'your-email';
		
		require(BFT_PATH."/views/integration-contact-form.html.php");
	}
	
	// signup user from contact form 7 or jetpack
	// $data - $_POST data
	static function signup($data, $user) {
		global $wpdb;
		
		$data = $_POST;
		if(empty($data['bft_int_signup'])) return true;
				
		bft_subscribe($user['email'], $user['name'], true);
	}
	
		// Ninja forms integration
	static function ninja() {
		global $wpdb;		
		
		if(!empty($_POST['ok']) and check_admin_referer('bft_ninja')) {
			// save integration
			$integration = array("form_id" => $_POST['form_id'], "fields"=>array());
			$integration['fields']['email'] = $_POST['field_email'];
			$integration['fields']['name'] = $_POST['field_name'];
			$integration['fields']['checkbox'] = $_POST['field_checkbox'];
			
			/*foreach($fields as $field) {
				$integration['fields']['field_' . $field->id] = $_POST['field_'.$field->id];
			}*/
			
			update_option('bft_ninja_integration', $integration);
		} 
		
	
		// unserialize current integration
		$integration = get_option('bft_ninja_integration');
		
		// if another form is selected by post, it overwrites the integration
		$selected_form_id = 0;
		if(!empty($integration['form_id'])) $selected_form_id = $integration['form_id'];
		if(isset($_POST['form_id'])) $selected_form_id = $_POST['form_id'];
		
		// select ninja forms
		$forms = ninja_forms_get_all_forms();		
		
		// form selected? get fields
		$ninja_fields = array();		
		if($selected_form_id) {
			$ninja_fields = ninja_forms_get_fields_by_form_id( $selected_form_id );			
		}
		// print_r($ninja_fields);
		require(BFT_PATH."/views/integration-ninja-form.html.php");
	}
	
	// integrate ninja form signup
	static function ninja_signup() {
		global $ninja_forms_processing, $wpdb;
		$form_id = $ninja_forms_processing->get_form_ID();
		$ninja_fields = $ninja_forms_processing->get_all_fields();
		
		if( !is_array( $ninja_fields ) ) return false;
		
		//  is it integrated?
		$integration = get_option('bft_ninja_integration');
		if(empty($integration['form_id']) or $integration['form_id'] != $form_id) return false;
		
		// is checkbox required?
		if(!empty($integration['fields']['checkbox'])) {
			$integrate = false;
			foreach($ninja_fields as $id => $value) {
				if($id == $integration['fields']['checkbox'] and $value == 'checked') $integrate = true;
			}
			if(!$integrate) return false;			
		}
		
		$email = $name = '';
		foreach($integration['fields'] as $key => $field_id) {
			// fill email
			if($key == 'email') {
				foreach($ninja_fields as $id => $ninja) {
					if($id == $field_id) $email = $ninja;
				}
			}
			
			// fill name
			if($key == 'name') {
				foreach($ninja_fields as $id => $ninja) {
					if($id == $field_id) $name = $ninja;
				}
			}
			
			// skip name, email and checkbox at this point
			if($key == 'name' or $key == 'email' or $key == 'checkbox') continue;			
		}
		
		// subscribe
		if(empty($email)) return false;
		
		bft_subscribe($email, $name);
	} // end ninja_signup
}