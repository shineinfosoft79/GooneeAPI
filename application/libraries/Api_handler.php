<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * iPromot api_handler Class
 * This class used as auto_load libraries
 **/
class Api_handler{

	protected $ci;

	function __construct($config = array()) {

		$this->ci =& get_instance();
		$this->ci->load->helper(array('general','form','url'));
		$this->ci->load->library(array('general','form_validation','curl'));

	}

	public function api_response($status,$status_key,$setting_data = array(),$data = array()){
		
		$msg = $this->ci->api_messages->message($status_key); 

		if($status=="" && $msg ==""){
			throw new Exception("API status code and message is required");
		}

		if(!is_integer($status) && !is_string($msg)){
			throw new Exception("API status code or message is not invalid.");
		}

		$default_setting = array(
			"total_data_count"=> count($data),
			"current_data_count"=> count($data),
			"current_page"=> "0",
			"previous_page"=> "0",
			"next_page"=> "0"
		);

		$setting = array(
			//"profile_image_url" => USER_PROFILE_IMAGE_URI,
			//"profile_image_url" => USER_PROFILE_IMAGE_COM_URI,
		);

		if( !empty($setting_data) ) {
			$setting = array_merge($setting, $setting_data);
		}

		// if( isset($data['v_profile_image']) && empty($data['v_profile_image']) ){
		// 	$data['v_profile_image'] = 'default_image.png';
		// }

		$this->http_response_code(200); //$status
		header('Content-Type: application/json');
		$_status = true;
		if( (int)$status != 200 ){ $_status = false; }
		$json = array('status' => $_status, 'status_code'=>$status, "status_key" => $status_key, 'message'=>$msg,'setting'=>$setting,'data'=>$data);

		echo json_encode($json);
		die();

	}

	public function api_validation($config, $method, $token_validation = true){
		if($token_validation === true){
			$this->token_validation();
		}
		$data = array();

		if($method == "post"){
			## if request from "from-data"
			$data = $this->ci->input->post(null,true);
			if( empty($data) ){
				## if request from "raw" json data
				$data = json_decode(file_get_contents('php://input'), true);
			}
		}elseif($method == "get"){
			$data = $this->ci->input->get(null,true);
		}else{
			$get = $this->ci->input->get(null,true);
			$post = $this->ci->input->post(null,true);
			$data = array_merge($post,$get);
		}
		
		if( empty($config) || empty($data) ){
			$this->api_response("400","data_not_found",array(),array());
		}

		$this->ci->form_validation->set_data($data);
		$this->ci->form_validation->set_rules($config);

		if ($this->ci->form_validation->run() == FALSE) {

			$error_array = $this->ci->form_validation->error_array();
			foreach ($error_array as $ekey => $eitem){
				$this->api_response("400",$eitem,$setting = array(),$data = array());
			}

		}
		return $data;
	}

	public function token_validation(){
		$header = $this->get_header_param();
		if( !isset($header['u_access_token']) || empty($header['u_access_token']) ){
			if( !isset($header['Authorization']) || empty($header['Authorization']) ){
				$this->api_response("401","authentication_fail",$setting = array(),$data = array());
			}else{
				$header['u_access_token'] = str_replace("Bearer ", '', $header['Authorization']);
			}
		}
		
		$where = ['u_access_token' => $header['u_access_token']];
		if( isset($_POST['u_id']) && !empty($_POST['u_id']) ) $where['u_id'] = $_POST['u_id'];

		$this->ci->db->select('u_id');
		$this->ci->db->from('user_master');
		$this->ci->db->where($where);
		if( $this->ci->db->count_all_results() <= 0 ){ 
			$this->api_response("401","session_expired",$setting = array(),$data = array());
		}
	}

	protected function get_header_param(){
		$header = array();
		foreach (getallheaders() as $name => $value) {
			$header[$name] = $value;
		}
		return $header;
	}

	protected function http_response_code($code = NULL) {

		if ($code !== NULL) {

			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
					break;
			}

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

			header($protocol . ' ' . $code . ' ' . $text);

			$GLOBALS['http_response_code'] = $code;

		} else {

			$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

		}

		return $code;

	}


	public function api_caller($param=array(), $method='post', $call_url=null,$header = array()){

		$call_url = ROOT_URI.$call_url;

		//=== Set header ===//
		if(!empty($header)){
			foreach ($header as $hkey => $hvalue){
				$this->ci->curl->http_header($hkey,$hvalue);
			}
		}

		//=== cURL Call ===//
		$data = $this->ci->curl->_simple_call($method,$call_url,$param);
		

		// check here for responce is not an json
		$data = json_decode($data,1);

		if(!isset($data['status'])){

			$data = array();
			$data['status'] = '500';
			$data['message'] = 'Internal Server Error';
			$data = json_decode(json_encode($data),0);

		}

		return $data;

	}

}
