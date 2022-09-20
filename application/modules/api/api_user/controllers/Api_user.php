<?php 

Class Api_user extends MX_Controller{

	public function __construct() {
		$this->load->model('Api_user_mdl');
	}
	## get user
	public function get_user(){
		try {
			## Login fields validation
			//$data = $this->validation_user();
			## Get user Data
			$user_result = $this->Api_user_mdl->get_user();

			foreach ($user_result as $key => $value) {
				if(empty($value['r_name'])){
					$user_result[$key]['r_name'] = "All ER";
				}
					if($value['u_status'] == 1){
						$user_result[$key]['u_status'] = true;
					}else{
						$user_result[$key]['u_status'] = false;
					}
					$G ="";
					if($value['u_group_status'] == 1)
					{
						$G = "Group";
						$user_result[$key]['u_fullname'] = $user_result[$key]['u_fullname']."(".$G.")";
					}else
					{
						$user_result[$key]['u_fullname'] = $user_result[$key]['u_fullname'];
					}
				
			}

			$this->api_handler->api_response("200", "user_get", array(), $user_result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	public function get_user_All(){
		try {
			## Login fields validation
			//$data = $this->validation_user();
			## Get user Data
			$user_result = $this->Api_user_mdl->get_user_All();
			$i = 1;
			foreach ($user_result as $key => $value) {

				$user_result[$key]['index'] = $i;
				if(empty($value['r_name'])){
					$user_result[$key]['r_name'] = "All ER";
				}
					if($value['u_status'] == 1){
						$user_result[$key]['u_status'] = true;
					}else{
						$user_result[$key]['u_status'] = false;
					}
					$G ="";
					if($value['u_group_status'] == 1)
					{
						$G = "Group";
						$user_result[$key]['u_fullname'] = $user_result[$key]['u_fullname']."(".$G.")";
					}else
					{
						$user_result[$key]['u_fullname'] = $user_result[$key]['u_fullname'];
					}
				$i++;
			}

			$this->api_handler->api_response("200", "user_get", array(), $user_result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	



	public function get_user_group(){
		try {
			$user_result = $this->Api_user_mdl->get_user_group();
			$i =1;
			foreach ($user_result as $key => $value) {

				$user_result[$key]['index'] = $i;
				$i++;
				# code...
			}
			$this->api_handler->api_response("200", "user_get", array(), $user_result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function search_user(){
		try {
			## Login fields validation
			$data = $this->validation_user();
			## Get user Data
			$user_result = $this->Api_user_mdl->search_user($data);
			$this->api_handler->api_response("200", "user_get", array(), $user_result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function add_user_group()
	{
		try {
			$data =  $this->validation_add_user_group();

			$set = array(
				'u_fullname' => $data['u_fullname'],
        		'u_email' => $data['u_email'],
        		'u_group_status' => '1',
        		'u_created_date' => date('Y-m-d H:i:s')
			);

			$user_result = $this->Api_user_mdl->add_user_group($set);

			$this->api_handler->api_response("200", "add_user_group", array(), $user_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}


	public function get_edit_user(){
		try {
			## Login fields validation
			$data =  $this->validation_edit_user();
			## Get edit user Data
			$user_result = $this->Api_user_mdl->get_edit_user($data);
			$this->api_handler->api_response("200", "user_get", array(), $user_result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function change_user_status()
	{
		try {
			## fields validation
			$data =  $this->validation_change_status();
			## update status
			if($data['u_status'] == 'true'){
				$data['u_status'] = "1";
			}else{
				$data['u_status'] = "0";
			}
			$this->Api_user_mdl->update_change_status($data['u_id'],$data['u_status']);
			$this->api_handler->api_response("200", "update_user_status", array(), $data);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function delete_group(){
		try {
			## Login fields validation
			$data =  $this->validation_delete_group();
			## Get edit user Data
			$user_result = $this->Api_user_mdl->delete_group($data['u_id']);
			$this->api_handler->api_response("200", "delete_group", array(), $user_result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_change_status(){

		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'id',
				'rules' => 'required'
			),
			array(
				'field' => 'u_status',
				'label' => 'user status',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);

	}

	protected function validation_delete_group(){

		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);

	}

	protected function validation_add_user_group(){

		$config = array(
			array(
				'field' => 'u_fullname',
				'label' => 'name',
				'rules' => 'required'
			),
			array(
				'field' => 'u_email',
				'label' => 'email',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);

	}

	protected function validation_user(){
		
		$config = array(
			array(
				'field' => '',
				'label' => '',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	protected function validation_edit_user(){
		
		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'user id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	public function get_friday()
	{	
			$myDate = "2019-01-15";
			$next_friday = date('Y-m-d', strtotime("next friday", strtotime($myDate)));
			echo $next_friday;

	}	
	
}


?>