<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_messages{

	public function __construct(){
		
	}

	public function message( $id ){

		$messages = array(

			'send_otp'=>'OTP successful',
			'cat_get' => 'Categories Get successfully.',
			'help' => 'Email send to our support team.',
			'acocunt_update' => 'Account setting update',
			'password_match' =>'Password Matched',
			'password_not_match' =>'Password Not Matched',
			'remove'=>'Removed',

			'cat_topic'=>'topic get successfully',
			'updated' =>'Update successfully',
			'remove' => "Removed successfully",
			'checkout' => 'Success',

			'Mail_send' => 'Mail Send successfully! We will contact you soon as possible.',

			'add_review' =>'Review added Successfully',

			'add_schedule'=>'Scheduled set successfully',
			'get'=>'Get Successfully',
			'insert_1to1'=>'Added Successfully',
			'insert_course'=>'Course Added Successfully', 

			'edit_webinar'=>'Webinar Edited Successfully',
			'edit_course'=>'Course Edited Successfully',
			'edit_one2one'=>'Edit One To One Successfully',
			

			## Login/Profile
			'somthing_wrong' => 'Somthing Wrong!',
			'record_added' => 'Record Added Successfully',
			'invalid_password' => 'Invalid Credentials.',
			'user_get'=>'User Get successfully',
			'update_user_register'=>"Update User Successfully",
			'delete_user_register'=>"Delete User Successfully",
			'export_pdf' => "export PDF Successfully",
			'send_pdf' => "Send PDF Successfully",
            'send_pdf_fail' => "Sending Fail",
			'get_room' => "Get Room Successfully",
			'add_user_group' => "Add Group Successfully",
			'delete_group' => "Delete Group Successfully",
			'update_user_status' => "Update Status Successfully",
			'check_record' => 'Check Record Successfully',
			'update_record' => 'Update Record Successfully',
			'Invelid_Report' => "Invalid Report",


			'account_deleted' => 'Your account has been deleted. Please contact our support team.',
			'account_deactivated' => 'Your account has been deactivated. Please contact our support team.',
			'login' => 'Login successfully.',
			'logout' => 'Logged out.',
			'email_exist' => 'Email is already registered.',
			'email_not_exist' => 'Email ID could not be found.',
			'email_verify' => 'OTP sent.',
			'image_upload' => 'Image Upload Successfully',
			'image_upload_fail' => 'Image Upload failed',
			
			'user_register' => 'Registration successful.',
			'user_not_exist' => "User doesn't exists!",
			
			'profile_edit' => 'Profile updated.',
			'profile_photo' => 'Photo updated.',
			'profile_photo_err' => 'Profile could not be updated. Please try again later.',
			'profile_completed' => 'Profile completed!',
			'settings_update' => 'Settings updated.',
			'forgot_password' => 'Your password has been reset, Please Check Your Mail.',
			'forgot_password1' => 'Your password has been reset, Please Contact Michelle Newsom',
			'data_not_exist' =>'This user doesn`t exist.',
			'reset_password' => 'Your password has been reset.',

			'change_password' => 'Password changed successfully!',
			'university_not_exist' => 'Please enter a valid NYU, Princeton, or Richmond ID. Other universities coming soon!',

			##scheduled
			'add_scheduled' => "Add Scheduled Successfully",
			'get_scheduled' => "Get Scheduled Successfully",
			'add_feedback' =>"Add Feedback Successfully",
			'delete_feedback' =>'Delete Feedback Successfully',
			## General Message
			'something_wrong' => 'Something went wrong, Please try again!',
			'success' => 'Successfully fetch data!',
			'data_not_found' => 'Data not found!',
			'session_expired' => 'Session Expired!',
			'authentication_fail' => 'Authentication fail!',
			'export_daily_record' => 'Download Sample Report Successfully',
			'import_daily_record' => 'Import Report Successfully',
			'valid_import_daily_record' => 'Empty Sheet Not Allowed',

			'import_d_record' => "import data Successfully",
			'import_d_not_found' => "Report date not found",
			'Invalid_Report' => "Invalid Report",

		);

		$return = "";
		if( isset($messages[$id]) ){
			$return = $messages[$id];
		}
		else{
			$return = $id; //$messages['something_wrong'];
		}

		return $return;

	}

}
