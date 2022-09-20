<?php 

Class Api_user_registration extends MX_Controller{

	public function __construct() {
		$this->load->model('api_user_registration_mdl');
	}

	## register and login user
	public function user_registration(){
		try {
			## Validate fields
			$data = $this->validation_user_registration();
			$this->validate_email($data);
			//print_r($data);exit;
			## Register user
			$this_data = $this->api_user_registration_mdl->user_registration($data);
			## User Login
			$this->api_handler->api_response("200", "user_register", array(), array("id"=>$this_data['u_id']));

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function update_user_registration(){
		try {
			## Validate fields
			$data = $this->validation_user_registration_edit();
			## Register user
			if($data['u_password'])
			{
				$data['u_password'] = md5($data['u_password']);
			}else{
				unset($data['u_password']);
			}
			$this_data = $this->api_user_registration_mdl->update_user_registration($data);
			## User Login
			$this->api_handler->api_response("200", "update_user_register", array(), array("id"=>$this_data['u_id']));

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}


	public function delete_user(){
		try {
			## Validate fields
			$data = $this->validation_delete_user();
			## Register user
			$this_data = $this->api_user_registration_mdl->delete_user_registration($data);
			## User Login
			$this->api_handler->api_response("200", "delete_user_register", array(), array("id"=>$this_data['u_id']));

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}


	## validate check email is registered or not
	private function validate_email($data){

		$this->load->model('api_user_mdl');
		## Email validation
		if( $this->api_user_mdl->account_exists(['u_email' => $data['u_email']]) ){
			$this->api_handler->api_response("409", "email_exist", array(), array());
		}
	}

	private function validation_delete_user(){
		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'user id',
				'rules' => 'required'
			),
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	private function validation_user_registration(){

		$config = array(
			array(
				'field' => 'u_fullname',
				'label' => 'user name',
				'rules' => 'required|max_length[50]'
			),
			array(
				'field' => 'u_email',
				'label' => 'email',
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'u_password',
				'label' => 'password',
				'rules' => 'required'
			),
			array(
				'field' => 'u_role_id',
				'label' => 'user role',
				'rules' => 'required'
			),
			array(
				'field' => 'u_room_id',
				'label' => 'room name',
				'rules' => 'required'
			),

		);
		return $this->api_handler->api_validation($config,"post",true);
	}

	private function validation_user_registration_edit(){

		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'user id',
				'rules' => 'required|max_length[50]'
			),
			array(
				'field' => 'u_fullname',
				'label' => 'user name',
				'rules' => 'required|max_length[50]'
			),
			array(
				'field' => 'u_email',
				'label' => 'email',
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'u_role_id',
				'label' => 'user role',
				'rules' => 'required'
			),
			array(
				'field' => 'u_room_id',
				'label' => 'room name',
				'rules' => 'required'
			),

		);
		return $this->api_handler->api_validation($config,"post",false);
	}

}

?>