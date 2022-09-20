<?php 

Class Api_user_forgot extends MX_Controller{

	public function __construct() {
		$this->load->model('api_user_login_mdl');
		$this->load->model('api_user_mdl');
	}
	## user forgot
	public function forgot_user(){
		try {
				## validate data
				$data = $this->validation_forgot_password();
				## select user

				$user = $this->api_user_mdl->selectUser($data);
				//print_r($user);exit;
				if( !empty($user) ){
				$set = array(
				'u_fullname' => $user['u_fullname'],
        		'u_id' => $user['u_id'],
        		'u_password' => md5($user['u_fullname']."@2019"),
				);
				$this->api_user_mdl->updatePassword($set);
				## Send autogenerate password / and also one type of verification
				$this->load->library('Email_lib');
				if($user['u_role_id']=='2')
				{
					$email_data = [
					'sendto' => 'mnewsom@surepointer.com',
					'parser_name' => "api_email_parser/email_forgot_password",
					'parse_content' => [
						'u_fullname' => 'Michelle Newsom',
						'password' =>$user['u_fullname']. "`s new passsword : " .$user['u_fullname']."@2019",
					],
					'subject' => 'Forgot Password',
					];
					$msg = 'forgot_password1';
				}else
				{
					$email_data = [
					'sendto' => $user['u_email'],
					'parser_name' => "api_email_parser/email_forgot_password",
					'parse_content' => [
						'u_fullname' =>$user['u_fullname'],
						'password' =>" Your new passsword : " .$user['u_fullname']."@2019",
					],
					'subject' => 'Forgot Password',
					];
					$msg = 'forgot_password';
				}
			    //print_r($user);exit();
				
				$this->email_lib->sendEmail($email_data);
			
				//$this->update_device_token($datas);

				$this->api_handler->api_response("200", $msg, array(), array());

				}else{
					$this->api_handler->api_response("400", "data_not_exist", array(), array());
				}

			}catch (Exception $e){
				$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
			}
	}

	public function validation_forgot_password(){
		
		$config = array(
			array(
				'field' => 'u_fullname',
				'label' => 'forgot',
				'rules' => 'required'
			),
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

}

?>