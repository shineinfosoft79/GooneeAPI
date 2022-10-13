<?php

Class Api_user_login_mdl extends CI_Model{

	protected $table = 'users';
	
	public function __construct() {
		$this->table = 'users';
	}

	public function user_login($data){
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('mobile',$data['email_phone']);
		$this->db->or_where('email',$data['email_phone']);
		if( $result = $this->db->get()->result_array() ){ return $result[0]; }
		else{ return []; }
	}

	public function get_login_data($data){
		$this->db->select("u.u_id,u.u_fullname,u.u_email,u.u_role_id,rl.role_name,u.u_room_id,r.r_name,u.u_online_status,u.u_access_token");
		$this->db->from($this->table.' u');
		$this->db->join('room_master r', 'u.u_room_id = r.r_id','LEFT');
		$this->db->join('role rl', 'rl.role_id = u.u_role_id');
		$this->db->where($data);
		if( $result = $this->db->get()->result_array() ){ return $result[0]; }
		else{ return []; }
	}

	public function user_insert($data){
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function user_reset_pass($data){
		$this->db->where('id', $data['id']);
    	$this->db->update('users', array('password' => MD5($data['password'])));
    	return true;
	}

	public function user_update_token($data){
		$this->db->where('id', $data['id']);
    	$this->db->update('users', array('remember_token' => $data['remember_token']));
    	//echo $this->db->last_query();exit;
    	return true;
	}
	//Tutor Stripe Token 
	public function update_token_tutor($data){
		$this->db->where('id', $data['id']);
    	$this->db->update('users', array('stripe_account_id' => $data['stripe_account_id']));
    	//echo $this->db->last_query();exit;
    	return true;
	}
	//Tutor Stripe Token 
	public function update_token_null_tutor($data){
		$this->db->where('id', $data['id']);
    	$this->db->update('users', array('stripe_account_id' => NULL));
    	//echo $this->db->last_query();exit;
    	return true;
	}

	public function update_login_data($data){
		$update = array('u_online_status' => 'true' ,
				'u_access_token' => $data['u_access_token'],
				);
		$this->db->where('u_id', $data['u_id']);
	    $this->db->update($this->table, $update);
	    return;
	}

	public function user_logout($data){
		//Modules::run("api_user/api_user/update_device_token",$data,"","");
		$update = array('u_online_status' => 'false' ,
				'u_access_token' => ''
				);
		$this->db->where('id', $data['u_id']);
	    $this->db->update($this->table, $update);
	    return;
	}

	public function user_update($data){
		//_P($data);exit;
		$this->db->where('id', $data['id']);
	    $this->db->update($this->table, $data);
		 return;
	    //echo $this->db->last_query();exit;
	}

	public function user_password_check($data){
		$this->db->select("id");
		$this->db->from($this->table);
		$this->db->where('id',$data['id']);
		$this->db->where('password',$data['password']);
		$result = $this->db->get()->num_rows();
		return $result;
	}

	public function user_profile_image($data){
		$this->db->where('id', $data['id']);
	    $this->db->update($this->table, $data);
	}
	
	public function get_user_detail($data){
	//	print_r($data['id']);exit;
		$this->db->select("*");
		$this->db->from($this->table.' u');
		$this->db->where('u.id', $data['id']);
		if( $result = $this->db->get()->result_array() ){ return $result[0]; }
		else{ return []; }
	}

	public function get_student_detail($data){
		$this->db->select("*");
		$this->db->from($this->table.' u');
		$this->db->where('u.id', $data['uid']);
		if( $result = $this->db->get()->result_array() ){ return $result[0]; }
		else{ return []; }
	}

	public function get_topics($topic){
		$this->db->select("*");
		$this->db->from('interst_topic');
		$this->db->where_in('id',$topic);
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_charegs($data){
		$this->db->select("*");
		$this->db->from('one2one');
		$this->db->where_in('created_by',$data['uid']);
		if( $result = $this->db->get()->result_array() ){ return $result[0]; }
		else{ return []; }
	}

	public function webinar_detail($data){
		$this->db->select("title,id,thumb_img,created_date");
		$this->db->from('schudule');
		$this->db->where_in('created_by',$data['uid']);
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function course_detail($data){
		$this->db->select("title,id,thumb,created_date,fees");
		$this->db->from('course');
		$this->db->where_in('created_by',$data['uid']);
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_connection_detail($data){
		$this->db->select("*");
		$this->db->from('connection');
		$this->db->where('userId',$data['id']);
		$this->db->where('requestBy',$data['user_id']);
		if( $result = $this->db->get()->row() )
		{
			if(isset($result) && $result->approved=="true" && $result->connection_date!=null){
				return "Connected";
			} else if(isset($result) && $result->approved=="false" && $result->connection_date==null){
				return "Requested";
			} else{
				return "notFound";
			}
		}
		else{ 
			return "notFound"; 
		}
	}
	public function get_connection_info($data)
	{
		$this->db->select("*");
		$this->db->from('connection');
		$this->db->where('userId',$data['lid']);
		$this->db->where('requestBy',$data['uid']);
		// $this->db->or_where('userId',$data['uid']);
		// $this->db->where('requestBy',$data['lid']);
		return $this->db->get()->row_array();
	}
}
?>