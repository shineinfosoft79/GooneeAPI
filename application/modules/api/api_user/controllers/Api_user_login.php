<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/aws/aws-autoloader.php';
require_once APPPATH.'third_party/stripe-php/init.php'; 

Class Api_user_login extends MX_Controller{

	public function __construct() {
		$this->load->model('api_user_login_mdl');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	## user login
	public function user_login(){
		try {
			## Login fields validation
			$data = $this->validation_user_login(); 
			## Get user Data
			$user_result = $this->api_user_login_mdl->user_login(['email_phone' => $data['email_phone']]);
			$f_l_name = explode(' ',$user_result['name']);

			$user_result['fname'] = $f_l_name[0];
 			$user_result['lname'] = $f_l_name[1];

			if( empty($user_result) || $user_result['password'] != md5($data['password']) ){
				$this->api_handler->api_response("401", "invalid_password", array(), array());
			}else if( $user_result['active'] != 1 ){
				$this->api_handler->api_response("401", "Account is deleted or deactive", array(), array());
			}else{
				## Token and access update
				$user_result['remember_token'] = _random_key();


				if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
					$user_result['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
				}elseif(substr($user_result['profileImg'],0,4) == "http"){
					$user_result['profileImg'] = $user_result['profileImg'];
				}else{
					$user_result['profileImg'] = base_url($user_result['profileImg']);
				}

				$this->api_user_login_mdl->user_update_token($user_result);
				
				if($user_result['user_type'] == 'tutor')
				{
					if($user_result['stripe_account_id']=='')
					{
						//Stripe Connect for Tutor
						$Stripe_data = $this->CreateTutorStripeAccount($user_result);
						$user_result['is_stripe_connect'] = false;
						$stripe_account_id = $Stripe_data['id'];
						$update_data = $user_result;
						$update_data['stripe_account_id'] = $stripe_account_id;
						$update_token = $this->api_user_login_mdl->update_token_tutor($update_data);
						$redirect_url = $this->connectStripeAccount($Stripe_data['id']);
						$user_result['stripe_connect_url'] = $redirect_url['url'];
					}
					else
					{
						$user_result['is_stripe_connect'] = true;
						$user_result['stripe_connect_url '] = "";

					}
					
				}
				else
				{
					$user_result['is_stripe_connect'] = false;
					$user_result['stripe_connect_url '] = "";
				}
				
				$this->api_handler->api_response("200", "login", array('auth_token'=>$user_result['remember_token']), $user_result);
			}

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	protected function validation_user_login(){
		
		$config = array(
			array(
				'field' => 'email_phone',
				'label' => 'email_phone',
				'rules' => 'required'
			),
			array(
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			),

		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function user_verify(){
		try {
			## Login fields validation
			$data = $this->validation_user_varify();
			## Get user Data
			$user_result = $this->api_user_login_mdl->user_login(['email_phone' => $data['email_phone']]);

			if(empty($user_result)){
				$this->api_handler->api_response("401", "user_not_found", array(), array());
			}else{				
				$this->api_handler->api_response("200", "User_found", array(), $user_result);
			}
		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
		
	}

	protected function validation_user_varify(){
		
		$config = array(
			array(
				'field' => 'email_phone',
				'label' => 'email_phone',
				'rules' => 'required'
			)

		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function user_reset_password(){
		try {
			## fields validation
			$data = $this->validation_reset_password();
			## Get user Data
			$user_result = $this->api_user_login_mdl->user_reset_pass($data);

			if($user_result != true){
				$this->api_handler->api_response("401", "user_not_reset", array(), array());
			}else{				
				$this->api_handler->api_response("200", "User_reset_success", array(), $user_result);
			}

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_reset_password(){
		
		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			),
			array(
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			)

		);
		return $this->api_handler->api_validation($config,"post",false);
	}



	public function user_register(){
		try {
			##  fields validation
			$data = $this->validation_user_register();

			$userData =  array(
								'name' => $data['fname'].' '.$data['lname'],
								'username'=> $data['fname'].''.$data['lname'],
								'mobile' => $data['mobile'],
								'email' => $data['email'],
								'password' => MD5($data['password'])
								 );

			$inserted_id = $this->api_user_login_mdl->user_insert($userData);

			$userData['inserted_id'] = $inserted_id;

			if($inserted_id==0){
				$this->api_handler->api_response("401", "user_not_insert", array(), array());
			}else{				
				$this->api_handler->api_response("200", "User_insert", array(), $userData);
			}

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_user_register(){
		
		$config = array(
			array(
				'field' => 'fname',
				'label' => 'fname',
				'rules' => 'required'
			),
			array(
				'field' => 'lname',
				'label' => 'lname',
				'rules' => 'required'
			),
			array(
				'field' => 'mobile',
				'label' => 'mobile',
				'rules' => 'required'
			),
			array(
				'field' => 'email',
				'label' => 'email',
				'rules' => 'required'
			),
			array(
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			),
			array(
				'field' => 'user_type',
				'label' => 'user_type',
				'rules' => 'required'
			)
		);

		return $this->api_handler->api_validation($config,"post",false);
	
	}


	## user logout
	public function user_logout(){
		try {
			## Validate 
			$data = $this->validation_user_logout();
			## get user
			$user_result = $this->api_user_login_mdl->user_logout($data);
			$this->api_handler->api_response("200", "logout", array(), array());

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_user_logout(){

		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'user id',
				'rules' => 'required|integer'
			)
		);
		return $this->api_handler->api_validation($config,"post");
	}

	public function send_otp(){
		try {
			## Validate 
			$data = $this->validation_send_otp();
			// var_dump($data);exit;

			$otp = rand(1111,9999);
			$msg = 'Your Goonee Registration OTP is:'.$otp;

			$this->sendSMS($msg,$data['mobile']);

			$result['otp'] = $otp;

			$this->api_handler->api_response("200", "send_otp", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_send_otp(){

		$config = array(
			array(
				'field' => 'mobile',
				'label' => 'mobile',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	public function sendSMS($msg,$phone){
        $params = array(
            'credentials'=> array(
            'key'=>'AKIAUWKH5ASIRJXAO2TM',
            'secret'=>'nLWEQUCVs+10U9rq3Dja66EMxCavzdGVCSzALnGg',
            ),
            'region'=>'us-west-1',
            'version'=>'latest'
        ); 

    	$phoneNo = str_replace('-','',$phone);
        $phoneNo = str_replace('+91','',$phoneNo);
        $phoneNo = substr($phoneNo, -10);

 
        //echo $phoneNo;die();
        $sns = new \Aws\Sns\SnsClient($params);
        $args = array(
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                       'DataType' => 'String',
                       'StringValue' => 'Goonee'
                ]
             ],
            "SMSType"=>"Transactional",
            "Message"=>$msg,
            "PhoneNumber"=>"+91".$phoneNo,
            //"PhoneNumber"=>"+919033901431",
        );
        $result = $sns->publish($args); 
         //       _P($args);
       //_P($result);exit;


        return $result;

    }

    public function profileImgUpdate(){
    	try {
			## Upload directory : assets/upload/profile

    		$data = $this->validation_update_profile();
    		$result['profileImg'] = "";

			if(!empty($_FILES['profileimg']['name'])){

					$file_name = $_FILES['profileimg']['name'] ;
					$file_ext = substr($file_name, strripos($file_name, '.'));
					$newfilename = $data['id'].'_'.rand(111,999) . $file_ext;
					$file_size = $_FILES['profileimg']['size'];
					$file_tmp = $_FILES['profileimg']['tmp_name'];
					$file_type = $_FILES['profileimg']['type'];
					$file_ext=strtolower($file_ext);
					$expensions= array(".jpeg",".jpg",".png");
					
					if(in_array($file_ext,$expensions)=== FALSE){
						$response['status']='error';
						$response['message']='extension not allowed, please choose a JPEG, JPG or PNG file.';
					}
					if(!empty($_FILES['profileimg']['name'])){
						move_uploaded_file($file_tmp,FCPATH."assets/upload/profile/".$newfilename);
						$data['profileimg'] = $newfilename;


						$profile_full_url =  base_url()."assets/upload/profile/".$newfilename;
						$profile_updated_url = "assets/upload/profile/".$newfilename;

						$result['profileImg'] = $profile_full_url;

						$update_data = ['id'=>$data['id'],'profileImg '=>$profile_updated_url];

						$this->api_user_login_mdl->user_profile_image($update_data);

						$response['message']='Image updated.';

					}else{
						$response['status']='error';
						$response['message']='Image file name is empty.';
					}
			}else{
					$response['status']='error';
					$response['message']='Image file is empty.';
			}


			$this->api_handler->api_response("200", $response['message'],$result, array());


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

        protected function validation_update_profile(){

		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

    public function compareOldPassword(){
    	try {
			## fields validation
			$data = $this->validation_compare_OldPassword();

			$data['password'] = md5($data['password']);

			$result = $this->api_user_login_mdl->user_password_check($data);

			if($result == 0){
				$this->api_handler->api_response("404", "password_not_match", array(), array());
			}else{
				$this->api_handler->api_response("200", "password_match", array(), array());
			}
		

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

    protected function validation_compare_OldPassword(){

		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			),
			array(
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

    public function updateAccount(){
    	try {
			## fields validation
			$data = $this->validation_account();
			if($data['fname']){
			  $data['name'] = $data['fname'].' '.$data['lname'];
			}

			unset($data['fname'],$data['lname']);

			$result = $this->api_user_login_mdl->user_update($data);

			$this->api_handler->api_response("200", "acocunt_update", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

    protected function validation_account(){

		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


    public function facebook_login(){
		try {
			## Login fields validation
			$data = $this->validation_facebook_login();
			## Get user Data.
			$data['name'] = $data['fname'].' '.$data['lname'];
			unset($data['fname']);
			unset($data['lname']);
			$data['social_provider_type'] = '1';// for facebook login

			$user_result = $this->api_user_login_mdl->user_insert($data);

			if($user_result != true){
				$this->api_handler->api_response("401", "user_not_insert", array(), array());
			}else{				
				$this->api_handler->api_response("200", "login", array(), $data);
			}

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_facebook_login(){
		
		$config = array(
			array(
				'field' => 'email',
				'label' => 'email',
				'rules' => 'required'
			),
			array(
				'field' => 'fname',
				'label' => 'fname',
				'rules' => 'required'
			),
			array(
				'field' => 'lname',
				'label' => 'lname',
				'rules' => 'required'
			),
			array(
				'field' => 'username',
				'label' => 'username',
				'rules' => 'required'
			),
			array(
				'field' => 'profileImg',
				'label' => 'profileImg',
				'rules' => 'required'
			),
			array(
				'field' => 'remember_token',
				'label' => 'remember_token',
				'rules' => 'required'
			),
			array(
				'field' => 'profileImg',
				'label' => 'profileImg',
				'rules' => 'required'
			),
			array(
				'field' => 'social_provider_uid',
				'label' => 'social_provider_uid',
				'rules' => 'required'
			),

		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	public function get_user_detail(){
    	try {
			## fields validation
			$data = $this->validation_user_detail();
		//	print_r($data['id']);exit;

			$user_result = $this->api_user_login_mdl->get_user_detail($data);
			$f_l_name = explode(' ',$user_result['name']);

			$user_result['fname'] = $f_l_name[0];
 			$user_result['lname'] = $f_l_name[1];
			if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
				$user_result['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
			}elseif(substr($user_result['profileImg'],0,4) == "http"){
				$user_result['profileImg'] = $user_result['profileImg'];
			}else{
				$user_result['profileImg'] = base_url($user_result['profileImg']);
			}

			if(isset($data['user_id'])){
				$connection = $this->api_user_login_mdl->get_connection_detail($data);
				$user_result['is_connected'] = $connection;
			}

			$this->api_handler->api_response("200", "user_get", array(), $user_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

    protected function validation_user_detail(){

		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			),
			array(
				'field' => 'user_id',
				'label' => 'user_id',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"get",false);
	}


	public function student_profile(){
    	try {
			## fields validation
			$data = $this->validation_user_tutor_detail();

			$user_result = $this->api_user_login_mdl->get_student_detail($data);
			$f_l_name = explode(' ',$user_result['name']);

			$user_result['interesting_topic_name'] = [];

			if($user_result['interst_topic']){
				$topics = explode(",",$user_result['interst_topic']);
				$user_result['interesting_topic_name'] = $this->api_user_login_mdl->get_topics($topics);
			}

			$user_result['fname'] = $f_l_name[0];
 			$user_result['lname'] = $f_l_name[1];
			
			if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
				$user_result['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
			}elseif(substr($user_result['profileImg'],0,4) == "http"){
				$user_result['profileImg'] = $user_result['profileImg'];
			}else{
				$user_result['profileImg'] = base_url($user_result['profileImg']);
			}
			$this->api_handler->api_response("200", "get", array(), $user_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

   	public function tutor_profile(){
    	try {
			## fields validation
			$data = $this->validation_user_tutor_detail();

			$user_result = $this->api_user_login_mdl->get_student_detail($data);
			$f_l_name = explode(' ',$user_result['name']);

			$user_result['interesting_topic_name'] = [];

			if($user_result['interst_topic']){
				$topics = explode(",",$user_result['interst_topic']);
				$user_result['interesting_topic_name'] = $this->api_user_login_mdl->get_topics($topics);
			}

			$user_result['charges_detail'] = $this->api_user_login_mdl->get_charegs($data);

			$user_result['webinar_detail'] = $this->api_user_login_mdl->webinar_detail($data);

			$user_result['course_detail'] = $this->api_user_login_mdl->course_detail($data);



			$user_result['fname'] = $f_l_name[0];
 			$user_result['lname'] = $f_l_name[1];

			 if(isset($data['lid']))
			 {
				 $connection_info = $this->api_user_login_mdl->get_connection_info($data);
				 $user_result['blocked'] = $connection_info['blocked'];
				 $user_result['report'] = $connection_info['report'];
			 }
			 else
			 {
				 $user_result['blocked'] = false;
				 $user_result['report'] = false;
			 }

			if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
				$user_result['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
			}elseif(substr($user_result['profileImg'],0,4) == "http"){
				$user_result['profileImg'] = $user_result['profileImg'];
			}else{
				$user_result['profileImg'] = base_url($user_result['profileImg']);
			}

			$this->api_handler->api_response("200", "get", array(), $user_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

    protected function validation_user_tutor_detail(){

		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	public function CreateTutorStripeAccount($result)
	{
		try{
			//set stripe secret key and publishable key
		$stripe_keys = array(
			"secret_key"      => "sk_test_51LASsOHERU9ThZl6qr85CdKOk74042cu5EVn3WAQBCjSFHJXAgyqwbAr8WebtsR4sJQd1Q0ezwjbaoVnVpZGGjjP0031SSM1iQ",
			"publishable_key" => "pk_test_51LASsOHERU9ThZl6dp3gcjOJk18ofM2pK8OomqQ9jEbBCvFP9aBpXPOtaHUXzitFAsGz2unLQUbeFDE7ykvOP28t00ufqODIQs"
			);
			$stripe = new \Stripe\StripeClient($stripe_keys['secret_key']); 
			$Account_create = $stripe->accounts->create([
				'type' => 'custom',
				'country' => 'US',
				'email' => $result['email'],
				'capabilities' => [
				  'card_payments' => ['requested' => true],
				  'transfers' => ['requested' => true],
				],
			  ]);
			return $Account_create->jsonSerialize();
		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	function connectStripeAccount($stripekey)
	{
		try{
			//set stripe secret key and publishable key
			$strip_keys = array(
				"secret_key"      => "sk_test_51LASsOHERU9ThZl6qr85CdKOk74042cu5EVn3WAQBCjSFHJXAgyqwbAr8WebtsR4sJQd1Q0ezwjbaoVnVpZGGjjP0031SSM1iQ",
				"publishable_key" => "pk_test_51LASsOHERU9ThZl6dp3gcjOJk18ofM2pK8OomqQ9jEbBCvFP9aBpXPOtaHUXzitFAsGz2unLQUbeFDE7ykvOP28t00ufqODIQs"
				);
			$stripe = new \Stripe\StripeClient($strip_keys['secret_key']); 
			
			$connection = $stripe->accountLinks->create([
				'account' => $stripekey,
				'refresh_url' => "https://gooneelive.com",
				'return_url' => "https://gooneelive.com/?stripe_return = true",
				'type' => 'account_onboarding',
			  ]);

			return $connection->jsonSerialize(); 
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			// Invalid parameters were supplied to Stripe's API
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		  } catch (\Stripe\Exception\AuthenticationException $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		  } catch (\Stripe\Exception\ApiConnectionException $e) {
			// Network communication with Stripe failed
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		  } catch (\Stripe\Exception\ApiErrorException $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
		
	}

	public function updateStripeConnection()
	{
		try {
			## Login fields validation
			$data = $this->validation_updateStripeConnection();
			## Get user Data.
			if($data['stripe_return'] == 'false')
			{
				$update_data['stripe_account_id'] = NULL;
				$update_data['id'] = $data['uid'];
				$update_token = $this->api_user_login_mdl->update_token_null_tutor($update_data);
			}
			
			$this->api_handler->api_response("200", "Stripe Account Connected", array(), array());

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	protected function validation_updateStripeConnection(){

		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			),
			array(
				'field' => 'stripe_return',
				'label' => 'stripe_return',
				'rules' => 'required'
			),
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
}

?>