<?php
/**
 * Teacher Profile Form
 *
 * Renders the ACF frontend form for teacher profile editing.
 *
 * @package MKH_Teacher_Addon
 */

$user_id = get_current_user_id();

// Check if form was submitted successfully
$success_message = '';
if ( isset( $_GET['updated'] ) && 'true' === $_GET['updated'] ) {
	$success_message = '<div class="stm-lms-message stm-lms-message__success">' . esc_html__( 'Profile updated successfully!', 'mkh-teacher-addon' ) . '</div>';
}
?>

<div class="stm-lms-account-settings">
	<h2 class="stm-lms-account-settings__title">
		<?php esc_html_e( 'Edit Teacher Profile', 'mkh-teacher-addon' ); ?>
	</h2>
	<p class="stm-lms-account-settings__description">
		<?php esc_html_e( 'Update your public instructor profile, teaching experience, qualifications, media, and other information visible to students.', 'mkh-teacher-addon' ); ?>
	</p>

	<?php echo $success_message; ?>

	<div class="mkh-teacher-profile-form">
		<?php
		acf_form(
			array(
				'post_id'       => "user_{$user_id}",
				'field_groups'  => array( 'group_mkh_teacher_profile' ),
				'form'          => true,
				'return'        => home_url( '/edit-profile/?updated=true' ),
				'submit_value'  => esc_html__( 'Save Profile', 'mkh-teacher-addon' ),
				'updated_message' => false,
				'html_submit_button' => '<input type="submit" class="stm-lms-btn stm-lms-btn_primary" value="%s" />',
				'html_submit_spinner' => '<span class="acf-spinner"></span>',
				'uploader'      => 'basic',
			)
		);
		?>
	</div>
</div>

<style>
	.mkh-teacher-profile-form .acf-form {
		background: #fff;
		padding: 30px;
		border-radius: 8px;
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	}

	.mkh-teacher-profile-form .acf-field {
		margin-bottom: 20px;
	}

	.mkh-teacher-profile-form .acf-label label {
		font-weight: 600;
		color: #273044;
		font-size: 14px;
	}

	.mkh-teacher-profile-form .acf-label p {
		font-size: 13px;
		color: #757575;
		margin-top: 5px;
	}

	.mkh-teacher-profile-form input[type="text"],
	.mkh-teacher-profile-form input[type="number"],
	.mkh-teacher-profile-form input[type="url"],
	.mkh-teacher-profile-form textarea,
	.mkh-teacher-profile-form select {
		width: 100%;
		padding: 12px 15px;
		border: 1px solid #e0e0e0;
		border-radius: 4px;
		font-size: 14px;
		color: #273044;
	}

	.mkh-teacher-profile-form input[type="text"]:focus,
	.mkh-teacher-profile-form input[type="number"]:focus,
	.mkh-teacher-profile-form input[type="url"]:focus,
	.mkh-teacher-profile-form textarea:focus,
	.mkh-teacher-profile-form select:focus {
		border-color:  #273044 ;
		outline: none;
	}

	.mkh-teacher-profile-form .acf-button {
		background:  #273044 ;
		color: #fff;
		padding: 12px 30px;
		border: none;
		border-radius: 4px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: background 0.3s;
	}

	.mkh-teacher-profile-form .acf-button:hover {
		background: #2a8fd9;
	}

	.mkh-teacher-profile-form .acf-tab-button {
		background: #f5f5f5;
		border: none;
		padding: 10px 20px;
		margin-right: 5px;
		border-radius: 4px;
		cursor: pointer;
		font-size: 14px;
		color: #273044;
	}

	.mkh-teacher-profile-form .acf-tab-button.active {
		background:  #273044 ;
		color: #fff;
	}

	.mkh-teacher-profile-form .acf-repeater .acf-row {
		background: #f9f9f9;
		padding: 20px;
		margin-bottom: 10px;
		border-radius: 4px;
		border: 1px solid #e0e0e0;
	}

	.mkh-teacher-profile-form .acf-repeater .acf-row-handle {
		background:  #273044 ;
	}

	.mkh-teacher-profile-form .acf-checkbox-list input[type="checkbox"] {
		margin-right: 8px;
	}

	.mkh-teacher-profile-form .acf-checkbox-list label {
		display: inline-block;
		margin-right: 20px;
		font-size: 14px;
		color: #273044;
	}

	.stm-lms-account-settings__title {
		font-size: 24px;
		font-weight: 700;
		color: #273044;
		margin-bottom: 10px;
	}

	.stm-lms-account-settings__description {
		font-size: 14px;
		color: #757575;
		margin-bottom: 30px;
	}

	.stm-lms-message__success {
		background: #4caf50;
		color: #fff;
		padding: 15px;
		border-radius: 4px;
		margin-bottom: 20px;
	}

	.stm-lms-message__error {
		background: #f44336;
		color: #fff;
		padding: 15px;
		border-radius: 4px;
		margin-bottom: 20px;
	}
</style>
