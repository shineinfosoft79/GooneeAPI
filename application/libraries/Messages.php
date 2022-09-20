<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages{

	public function __construct(){

		
		
	}

	public function getMessage( $id ){

		$messages = array(

			## Login/Profile
			'login_success' => 'Login successfully!',

			'account_inactive' => 'Your account is inactive! Please contact to support team.',
			'account_expired' => 'Your account is expired! Please contact to support team.',
			'invalid_password' => 'Wrong password. Try again.',
			'account_not_found' => 'Account Not Found',
			'profile_edit' => 'Profile is updated successfully.',
			'change_password' => 'Password has been changed successfully.',
			'invalid_old_password' => 'You enered invalid password.',
			'forgot_password' => 'Reset password link is sent to your email address.',
			'invalid_email' => 'This email address is not registered with us.',
			'invalid_username' => 'This username is not registered with us.',
			'token_expired' => 'Reset password link is expired you need to forgot your password again.',
			'wrong_capcha' => 'The capcha code does not match.',

			## Settings > Users

			'user_add' => 'User added successfully.',
			'user_add_err' => 'User not added.',
			'user_edit' => 'User detail updated successfully.',
			'user_edit_err' => 'User detail not updated.',
			'user_delete' => 'User(s) deleted successfully.',
			'user_status_change' => 'User status changed successfully.',

			##export
			'export_daily_record' => 'Export Daily Records successfully.',

			## File uploads

			'document_upload' => 'document uploaded successfully',

			## Grant / Privileges
			
			'no_access' => 'You have no grant to perform this action!',

			## Something Wrong
			
			'something_wrong' => 'Something want wrong, Please try again!',

			## university 

			'university_add' => 'University added successfully.',
			'university_edit' => 'University detail updated successfully.',
			'university_delete' => 'University deleted successfully.',
			'university_status_change' => 'University status changed successfully.',

			## Primary Skills
			
			'primary_skills_add' => 'Primary skills added successfully.',
			'primary_skills_edit' => 'Primary skills detail updated successfully.',
			'primary_skills_delete' => 'Primary skills deleted successfully.',
			'primary_skills_status_change' => 'Primary skills status changed successfully.',

			## Help
			'help_add' => 'Help Questoin added successfully.',
			'help_edit' => 'Help Questoin detail updated successfully.',
			'help_delete' => 'Help Questoin deleted successfully.',
			'help_status_change' => 'Help Questoin status changed successfully.',

			## Feedback
			'feedback_delete' => 'Feedback deleted successfully.',

			## Secondary Skills
			'secondary_skills_add' => 'Secondary skills added successfully.',
			'secondary_skills_edit' => 'Secondary skills detail updated successfully.',
			'secondary_skills_delete' => 'Secondary skills deleted successfully.',
			'secondary_skills_status_change' => 'Secondary skills status changed successfully.',

			## Payment type
			
			'payment_type_add' => 'Payment type added successfully.',
			'payment_type_edit' => 'Payment type detail updated successfully.',
			'payment_type_delete' => 'Payment type deleted successfully.',
			'payment_type_status_change' => 'Payment type status changed successfully.',
			
			'project_deleted' => 'Project deleted successfully.',
			'project_edit' => 'Project updated successfully.',
			'deals_deleted' => 'Deals deleted successfully.',
			'deals_edit' => 'Deals updated successfully.'
		);

		$return = "";
		if( isset($messages[$id]) ){
			$return = $messages[$id];
		}
		else{
			$return = $messages['something_wrong'];
		}

		return $return;

	}

	public function message( $message_id, $type = NULL, $text_only = false ){

		$message_text = $this->getMessage($message_id);
		
		if($type == "s") {
            $type_class = "alert-success";
            $type_label = "Success";
        }else if($type == "i") {
            $type_class = "alert-info";
            $type_label = "Info";
        }else if($type == "w") {
            $type_class = "alert-warning";
            $type_label = "Warning";
        }else if($type == "d") {
            $type_class = "alert-danger";
            $type_label = "Danger";
        }else{
            $type_class = "alert-info";
            $type_label = "Info";
        }

        if( $text_only == false ){

        	$message_content = '
	    		<div class="alert '.$type_class.'" role="alert">
		    		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    			<span aria-hidden="true">&times;</span>
		  			</button>
				  <h4 class="alert-heading">'.$type_label.'!</h4>
				  <p>'.$message_text.'</p>
				</div>
	        ';

        }
        else{

        	$message_content = $message_text;

        }

        return $message_content;

	}

}
