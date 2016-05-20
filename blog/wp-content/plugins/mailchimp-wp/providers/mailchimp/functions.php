<?php

function mailchimp_object( $settings ) {
	static $object = null;

	if ( is_null( $object ) ) {
		$object = mailchimp_object_create( $settings );
	}

	return $object;
}

function mailchimp_object_create( $settings ) {

	if( ! class_exists( 'MailChimp' ) ) {
		require_once $settings[ 'plugin_dir' ] . "providers/mailchimp/MailChimp.php";
	}

	$eoi_free = 'mailchimp' === K::get_var( 'provider', $settings ) ;
	$suggested = array();
	foreach ( $settings[ 'fca_eoi_last_3_forms' ] as $fca_eoi_previous_form ) {
		try {
			if( K::get_var( 'mailchimp_list_id', $fca_eoi_previous_form[ 'fca_eoi' ] ) ) {
				$suggested[ 'mailchimp_api_key' ] = $fca_eoi_previous_form[ 'fca_eoi' ][ 'mailchimp_api_key' ];
				$suggested[ 'mailchimp_list_id' ] = $fca_eoi_previous_form[ 'fca_eoi' ][ 'mailchimp_list_id' ];
				if( ! $eoi_free ) {
					$suggested[ 'mailchimp_double_opt_in' ] = K::get_var(
						'mailchimp_double_opt_in'
						, $fca_eoi_previous_form[ 'fca_eoi' ]
					);
				}
				break;
			}
		} catch ( Exception $e ) {}
	}

	$eoi_form_meta = K::get_var( 'eoi_form_meta', $settings, array() );
	$api_key = K::get_var(
		'mailchimp_api_key'
		, $suggested
		, K::get_var( 'mailchimp_api_key', $eoi_form_meta, '' )
	);

	if( $api_key ) {
		return new MailChimp( $api_key );
	} else {
		return false;
	}
}

function mailchimp_get_lists( $settings ) {

	$helper = mailchimp_object( $settings );

	// Return an empty array if the object is not a valid MailChimp Api instance
	if ( empty( $helper ) ) {
		return array();
	}

	$lists = $helper->call( 'lists/list' );
	$lists = K::get_var( 'data', $lists, array() );

	return $lists;
}

function mailchimp_ajax_get_lists() {

	// Validate the API key
	$api_key = K::get_var( 'mailchimp_api_key', $_POST );
	$lists_formatted = array( '' => 'Not set' );

	// Make call and add lists if any
	if ( preg_match( '/[a-z0-9]+-[a-z0-9]+/', $api_key ) ) {

		global $dh_easy_opt_ins_plugin;
		$settings = $dh_easy_opt_ins_plugin->settings;
		if( ! class_exists( 'MailChimp' ) ) {
			require_once $settings[ 'plugin_dir' ] . "providers/mailchimp/MailChimp.php";
		}

		$helper = new MailChimp( $api_key );
		$lists = $helper->call( 'lists/list' );
		$lists = K::get_var( 'data', $lists, array() );

		foreach ( $lists as $list ) {
			$lists_formatted[ $list[ 'id' ] ] = $list[ 'name' ];
		}
	}

	// Output response and exit
	echo json_encode( $lists_formatted );
	exit;
}

function mailchimp_add_user( $settings, $user_data, $list_id ) {

	$helper = mailchimp_object( $settings );

	if ( empty( $helper ) ) {
		return false;
	}

	$eoi_form_meta = K::get_var( 'eoi_form_meta', $settings, array() );
	$double_opt_in = K::get_var( 'mailchimp_double_opt_in', $eoi_form_meta, 'true' );
	$double_opt_in = 'true' === $double_opt_in;

	// Subscribe user
	$subscribed = $helper->call( 'lists/subscribe', array(
		'id'                => $list_id,
		'email'             => array( 'email' => K::get_var( 'email', $user_data ) ),
		'merge_vars'        => array( 'FNAME' => K::get_var( 'name', $user_data ) ),
		'double_optin'      => $double_opt_in,
		'replace_interests' => false,
		'send_welcome'      => false,
		'update_existing'   => true,
	) );

	// Return true if added, otherwise false
	if( 'error' === K::get_var( 'status', $subscribed ) ) {
		return false;
	} else {
		return true;
	}
}

function mailchimp_admin_notices( $errors ) {

	/* Provider errors can be added here */

	return $errors;
}

function mailchimp_string( $def_str ) {

	$strings = array(
		'Form integration' => __( 'MailChimp Integration' ),
	);

	return K::get_var( $def_str, $strings, $def_str );
}

function mailchimp_integration( $settings ) {

	// Detect free version (has mailchimp only)
	$eoi_free = 'mailchimp' === K::get_var( 'provider', $settings );

	global $post;
	$fca_eoi = get_post_meta( $post->ID, 'fca_eoi', true );
	$screen = get_current_screen();

	// Hack for mailchimp upgrade
	$fca_eoi[ 'mailchimp_list_id' ] = K::get_var(
		'mailchimp_list_id'
		, $fca_eoi
		, K::get_var( 'list_id' , $fca_eoi )
	);
	if( K::get_var( 'list_id' , $fca_eoi ) ) {
		$fca_eoi[ 'provider' ] = 'mailchimp';
	}
	// End of hack

	// Remember old Mailcihmp settings if we are in a new form
	$suggested = array();
	if ( 'add' === $screen->action ) {
		$fca_eoi_last_3_forms = $settings[ 'fca_eoi_last_3_forms' ];
		foreach ( $fca_eoi_last_3_forms as $fca_eoi_previous_form ) {
			try {
				if( K::get_var( 'mailchimp_list_id', $fca_eoi_previous_form[ 'fca_eoi' ] ) ) {
					$suggested[ 'mailchimp_api_key' ] = $fca_eoi_previous_form[ 'fca_eoi' ][ 'mailchimp_api_key' ];
					$suggested[ 'mailchimp_list_id' ] = $fca_eoi_previous_form[ 'fca_eoi' ][ 'mailchimp_list_id' ];
					$suggested[ 'mailchimp_double_opt_in' ] = K::get_var(
						'mailchimp_double_opt_in'
						, $fca_eoi_previous_form[ 'fca_eoi' ]
					);
					break;
				}
			} catch ( Exception $e ) {}
		}
	}

	// Prepare the lists for K
	$lists = mailchimp_get_lists( $settings );
	$lists_formatted = array( '' => 'Not set' );
	foreach ( $lists as $list ) {
		$lists_formatted[ $list[ 'id' ] ] = $list[ 'name' ];
	}

	K::fieldset( mailchimp_string( 'Form integration' ) ,
		array(
			array( 'input', 'fca_eoi[mailchimp_api_key]',
				array( 
					'class' => 'regular-text',
					'value' => K::get_var( 'mailchimp_api_key', $suggested, '' )
						? K::get_var( 'mailchimp_api_key', $suggested, '' )
						: K::get_var( 'mailchimp_api_key', $fca_eoi, '' )
					,
				),
				array( 'format' => '<p><label>API Key<br />:input</label><br /><em><a tabindex="-1" href="http://admin.mailchimp.com/account/api" target="_blank">[Get my MailChimp API Key]</a></em></p>' ),
			),
			array( 'select', 'fca_eoi[mailchimp_double_opt_in]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array( 
					'format' => $eoi_free
						? '<!---->'
						: '<p id="mailchimp_double_opt_in_wrapper"><label>Double opt-in<br />:select</label></p>'
					,
					'options' => $eoi_free
						? array(
							'true' => 'Yes',
						)
						: array(
							'false' => 'No',
							'true' => 'Yes',
						)
					,
					'selected' => K::get_var( 'mailchimp_double_opt_in', $suggested, '' )
						? K::get_var( 'mailchimp_double_opt_in', $suggested, '' )
						: K::get_var( 'mailchimp_double_opt_in', $fca_eoi, '' )
					,
					'default' => 'true',
				),
			),
			array( 'select', 'fca_eoi[mailchimp_list_id]',
				array(
					'class' => 'select2',
					'style' => 'width: 27em;',
				),
				array(
					'format' => '<p id="mailchimp_list_id_wrapper"><label>List to subscribe to<br />:select</label></p>',
					'options' => $lists_formatted,
					'selected' => K::get_var( 'mailchimp_list_id', $suggested, '' )
						? K::get_var( 'mailchimp_list_id', $suggested, '' )
						: K::get_var( 'mailchimp_list_id', $fca_eoi, '' )
					,
				),
			),
		),
		array(
			'id' => 'fca_eoi_fieldset_form_mailchimp_integration',
		)
	);
}
