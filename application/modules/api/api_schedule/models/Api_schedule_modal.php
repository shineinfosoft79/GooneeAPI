<?php

Class Api_schedule_modal extends CI_Model{

	protected $table = 'schudule';
	
	public function __construct() {
		$this->table = 'schudule';
	}

	public function insert_schedule($data){
		$this->db->insert($this->table, $data);
		return true;
	}

	public function insert_oneToone($data){
		$this->db->insert('one2one', $data);
		return true;
	}

	public function insert_parts($data){
		$this->db->insert('course_part', $data);
		$insert_id = $this->db->insert_id();
   		return  $insert_id;
	}

	public function get_onetoone($data){
		$this->db->select('*');
	    $this->db->from('one2oneSetCall');
	    $this->db->where('id',$data['id']);
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function add_onetoone($data){
		$this->db->insert('one2oneSetCall', $data);
		$insert_id = $this->db->insert_id();
   		return  $insert_id;
	}

	public function insert_course($data){
		$this->db->insert('course', $data);
		$insert_id = $this->db->insert_id();
		//echo $this->db->last_query();exit;
   		return  $insert_id;
	}

	public function add_review($data){
		$this->db->insert('review', $data);
		$insert_id = $this->db->insert_id();
	}

	public function insert_watch($data){
		$this->db->insert('watch', $data);
		$insert_id = $this->db->insert_id();
	}

	public function get_mycall($data){
		$current_date = date('Y-m-d');
		$this->db->select('o.*,u.name,u.profileImg,(select name from users where id = o.tutorId)as tutor_name,(select profileImg from users where id = o.tutorId)as tutor_profileImg');
	    $this->db->from('one2oneSetCall o');
	    $this->db->join('users u','u.id =o.userId');
	    //$this->db->join('users u1','u.id =o.tutorId');
	    if($data['upcoming'] == true){
	    	$this->db->where('o.status !=','reject');
	    	$this->db->where('o.date >=',$current_date);
	    }
	    if($data['uid']){
	    	$this->db->where('o.tutorId',$data['uid']);
	    }
	    
	    // if($data['status'] == 'pending'){
	    // 	$this->db->where('o.status','pending');
	    // }
	    if($data['id']){
	    	$this->db->where('o.userId',$data['id']);
	    }
		$this->db->order_by('id','desc');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function watch_history($data){
		$this->db->select('c.id,c.title,c.thumb,c.video_title');
	    $this->db->from('watch w');
	    $this->db->join('course c','c.id =w.cid');
	    $this->db->where('w.uid',$data['uid']);
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function user_reset_pass($data){
		$this->db->where('id', $data['id']);
    	$this->db->update($table_name, array('password' => MD5($data['password'])));
    	return true;
	}

	public function update_onetoone($data){
		$set = array('status' => $data['status'] );
		$this->db->where('id', $data['id']);
		unset($data['id']);
    	$this->db->update('one2oneSetCall',$data);
    	return true;
	}

	public function get_user_meeting($data){
		$this->db->select('*');
	    $this->db->from('schudule');
	    $this->db->where('created_by',$data['uid']);
	    $this->db->where('id !=',$data['course_id']);
	    $this->db->order_by('id','desc');
    	$this->db->limit(2, 0); 
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_review_by_id($data){
		$this->db->select('r.*,u.name,u.profileImg');
	    $this->db->from('review r');
	    $this->db->join('users u','u.id =r.uid');
	    $this->db->where('r.cid',$data['course_id']);
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_data($data){

		$this->db->select('*');
	    $this->db->from($data['table_name']);
	    $this->db->where('id',$data['id']);
	    $query = $this->db->get();
        $result = $query->row_array();
        return $result;
	}
	public function purchaseStatus($data){

		$this->db->select('*');
	    $this->db->from('cart');
	    if($data['table_name']=="course"){
	   	$this->db->where('type','course');
	    }else{
	    $this->db->where('type<>','course');
	    }
	    $this->db->where('cid',$data['id']);
	     $this->db->where('uid',$data['uid']);
	    $query = $this->db->get();
        $result = $query->row_array();
        return $result;
	}


	public function get_one2one_byId($data){
		$this->db->select('*');
	    $this->db->from('one2one');
	    $this->db->where('created_by',$data['u_id']);
	   	$this->db->limit(1);
		$this->db->order_by('id','desc');
	    $query = $this->db->get();
        $result = $query->row_array();

        return $result;
	}

	public function delete_old_parts($id){
		$this->db->where('c_id', $id);
    	$this->db->delete('course_part');
	}

	public function remove_g($id){
		$this->db->where('user_id', $id);
    	$this->db->delete('group_users');
	}

	public function get_parts_data($id){
		$this->db->select('*');
	    $this->db->from('course_part');
	    $this->db->where('c_id',$id);
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function update_parts_data($data){

	}

	public function update_data($data){
			$this->db->where('id', $data['id']); 
			$this->db->update($data['table_name'],$data['post_data']);
	}

	public function get_my_topic($uid){
		$this->db->select('interst_topic');
	    $this->db->from('users');
	    $this->db->where('id',$uid);
	    $query = $this->db->get();
        $result = $query->row_array();
        //echo $this->db->last_query();exit;
        return $result;
	}

	public function is_purchased($uid,$sid){
		$this->db->select('*');
	    $this->db->from('cart');
	    $this->db->where('uid',$uid);
	    $this->db->where('cid',$sid);
	    $this->db->where('checkout_status','paid');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_user_cart($uid){
		$this->db->select('*');
	    $this->db->from('cart');
	    $this->db->where('uid',$uid);
	    $this->db->where('checkout_status','paid');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function up_list_schedule($data){
		$current_date = date('Y-m-d');

		$this->db->select('s.*,u.name as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
	    $this->db->where('s.date_time >=',$current_date);
		if($data['search'] !=''){
			$this->db->like('s.title',$data['search']);
		}
	    $query = $this->db->get();
        $result = $query->result_array();
   //     echo $this->db->last_query();exit;
        return $result;
	}


	public function up_c_list_schedule(){

	}

	public function cr_c_list_schedule(){
		
	}

	public function ps_c_list_schedule(){
		
	}

	public function ps_list_schedule($data){
		$current_date = date('Y-m-d');

		$this->db->select('s.*,u.name as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
	    $this->db->where('s.date_time <=',$current_date);
		if($data['search'] !=''){
			$this->db->like('s.title',$data['search']);
		}
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function cr_list_schedule($data){
		$current_date = date('Y-m-d');

		$this->db->select('s.*,u.name as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
	    $this->db->where('s.date_time',$current_date);
		if($data['search'] !=''){
			$this->db->like('s.title',$data['search']);
		}
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}



	public function u_list_schedule($data){
		$current_date = date('Y-m-d');

		$this->db->select('s.*,u.name as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
	    $this->db->where('s.date_time >=',$current_date);

	    if(!empty($data['schedule'])){
	    	$this->db->where_in('s.id', $data['schedule']);
	    }

	    if(!empty($data['cat_id'])){
	    	$this->db->where('cat_id',$data['cat_id']);
	    }
		if($data['search'] !=''){
			$this->db->like('s.title',$data['search']);
		}
	    $query = $this->db->get();
        $result = $query->result_array();
		//echo $this->db->last_query();exit;
        return $result;
	}
	public function c_list_schedule($data){
		$current_date = date('Y-m-d H:i:s');

		$this->db->select('s.*,u.name as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
	    $this->db->where('s.date_time',$current_date);

	    if(!empty($data['schedule'])){
	    	$this->db->where_in('s.id', $data['schedule']);
	    }

	    if(!empty($data['cat_id'])){
	    	$this->db->where('cat_id',$data['cat_id']);
	    }
		if($data['search'] !=''){
			$this->db->like('s.title',$data['search']);
		}
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function p_list_schedule($data){ 
		$current_date = date('Y-m-d H:i:s');

		$this->db->select('s.*,u.name as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
	    $this->db->where('s.date_time <=',$current_date);

	    if(!empty($data['schedule'])){
	    	$this->db->where_in('s.id',$data['schedule']);
	    }

	    if(!empty($data['cat_id'])){
	    	$this->db->where('cat_id',$data['cat_id']);
	    }
		if($data['search'] !=''){
			$this->db->like('s.title',$data['search']);
		}
	    $query = $this->db->get();
        $result = $query->result_array();
        //echo $this->db->last_query();exit;
        return $result;
	}

	public function list_schedule_for_calender($where){
		$this->db->select('*');
	    $this->db->from($this->table);
	   // $this->db->where('status','approve');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
	public function getMyPurchaseCorse($data){
		$this->db->select('*');
	    $this->db->from('cart');
	    $this->db->where('type','course');
	    $this->db->where('checkout_status','paid');
	    $this->db->where('uid',$data['uid']);
	    $query = $this->db->get();
		//echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
	}

	public function get_my_course($data,$data2){
		$this->db->select('*');
	    $this->db->from('course');
	    $this->db->where('id',$data['cid']);
	    if(!empty($data2['tutor_id'])){
	    	$this->db->where('created_by',$data2['tutor_id']);
	    }
		if($data2['search'] !=''){
			$this->db->like('title',$data2['search']);
		}
	    $query = $this->db->get();
		//echo $this->db->last_query();exit;
        $result = $query->row_array();
        return $result;
	}
	public function list_course_for_part($data){
		$this->db->select('p.*,c.title');
	    $this->db->from('course c');
	    $this->db->join('course_part p','p.c_id =c.id');
	    $this->db->where('c.id',$data['id']);
	    $query = $this->db->get();
		//echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
	}

	// public function list_course_for_part($data){
	// 	$this->db->select('p.*,c.title');
	//     $this->db->from('course c');
	//     $this->db->join('course_part p','p.c_id =c.id');
	//     $this->db->where('c.id',$data['id']);
	//     $query = $this->db->get();
	// 	//echo $this->db->last_query();exit;
 //        $result = $query->result_array();
 //        return $result;
	// }

	public function list_course_for_calender(){
		$this->db->select('*');
	    $this->db->from('course');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function list_one2one_for_calender($data){
		$this->db->select('*');
	    $this->db->from('one2oneSetCall');
	    $this->db->where('status','approve');
	    $this->db->where('tutorId',$data['created_by']);
	    $query = $this->db->get();
        $result = $query->result_array();
        //echo $this->db->last_query();exit;
        return $result;
	}


	public function list_course_detail($data){
		$current_date = date('Y-m-d H:i:s');

		$this->db->select('c.*,u.name as created_by_name');
	    $this->db->from('course c');
	    $this->db->join('users u','u.id =c.created_by');
	    if(!empty($data['course'])){
	    	$this->db->where_in('c.id', $data['course']);
	    }
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function list_course($data){
		$current_date = date('Y-m-d H:i:s');

		$this->db->select('c.*,u.name as created_by_name');
	    $this->db->from('course c');
	    $this->db->join('users u','u.id =c.created_by');
	    $this->db->where('c.c_type',$data['type']);
	    if(!empty($data['course'])){
	    	$this->db->where_in('c.id', $data['course']);
	    }
	    if(!empty($data['cat_id'])){
	    	$this->db->where('c.cat_id',$data['cat_id']);
	    }
	    $query = $this->db->get();
        $result = $query->result_array();
        //echo $this->db->last_query();exit;
        return $result;
	}
	public function get_chat_user_list($data)
	{
		$this->db->select("*");
		$this->db->from('group_users gu');
		$this->db->join('groups g','g.group_id =gu.group_id');
		$this->db->where('user_id',$data);
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	public function get_chat_last_message($data)
	{
		$this->db->select("*");
		$this->db->from('message');
		$this->db->where('group_id',$data);
		$this->db->order_by('date','desc');
		$this->db->limit(1,0);
		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}
	public function get_chat_messages($data)
	{
		$this->db->select("*");
		$this->db->from('message');
		$this->db->where('group_id',$data);
		$this->db->order_by('message_id','asc');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	public function get_meeting_messages($data)
	{
		$this->db->select("m.*,u.name,u.profileImg");
		$this->db->from('meeting m');
		$this->db->where('room_id',$data);
		$this->db->join('users u','u.id =m.user_id');
		$this->db->order_by('m.id','asc');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	public function get_chat_group_user($group_id,$user_id,$search)
	{
		
		$this->db->select("*");
		$this->db->from('group_users gu');
		$this->db->join('users u','u.id =gu.user_id');
		$this->db->where('gu.user_id !=',$user_id);
		$this->db->where('gu.group_id',$group_id);
		if($search !=''){
			$this->db->like('u.name',$search);
			$this->db->or_like('u.email',$search);
			$this->db->or_like('u.mobile',$search);
		}
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	/**
	 * 
	 * Get Data to find whether both are connected or not
	 * 
	 */
	function find_connection_status($group_id,$user_id)
	{
		// Code for get user_id other than Logged in user
		$this->db->select("user_id");
		$this->db->from('group_users ');
		$this->db->where('user_id !=',$user_id);
		$this->db->where('group_id',$group_id);
		$result = $this->db->get()->row_array();
		
		// Check Connection
		$query = "SELECT * FROM `connection` where `userId` = ".$result['user_id']." and `requestBy` = ".$user_id." or `userId` = ".$user_id." and `requestBy` = ".$result['user_id'];
		$result = $this->db->query($query)->result_array();
		$cnt = count($result);
		if($cnt == 2)
		{ return true; }
		else{ return false; }
	}

	//Get Webinar Count 
	public function getWebinarCount($data)
	{
		// Code for get user_id other than Logged in user
		// $this->db->select("COUNT(*) as webinar_count");
		// $this->db->from('checkout_details');
		// $this->db->where('item_type','webinar');
		// $this->db->where('payment_status','paid');
		// $this->db->where('item_id',$data["id"]);
		// $result = $this->db->get()->row_array();
		// return $result['webinar_count'];
		
		
		$this->db->select("COUNT(*) as webinar_count");
		$this->db->from('cart');
		$this->db->where('type','webinar');
		$this->db->where('checkout_status','paid');
		$this->db->where('cid',$data["id"]);
		$result = $this->db->get()->row_array();
		return $result['webinar_count'];


		
	}
	public function add_notification($data){
    	$this->db->insert('notification', $data);
    	return $this->db->insert_id();
    }

	public function get_payment_history($uid)
	{
		$this->db->select("c.item_id,c.txn_id as transaction_id, c.item_price as price,c.item_type as type,c.created as created_at");
		$this->db->where('c.u_id',$uid);
		$this->db->from('transaction c');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	public function get_webinar_name($id){
		$this->db->select('id,title,created_by');
	    $this->db->from('schudule');
	    $this->db->where('id',$id);
	    $query = $this->db->get();
        $result = $query->row_array();
        return $result;
	}
	public function get_course_name($id){
		$this->db->select('id,title,created_by');
	    $this->db->from('course');
	    $this->db->where('id',$id);
	    $query = $this->db->get();
        $result = $query->row_array();
        return $result;
	}
	public function get_one2onecall($id)
	{
		$this->db->select('id,tutorId');
	    $this->db->from('one2onesetcall');
	    $this->db->where('id',$id);
	    $query = $this->db->get();
        $result = $query->row_array();
        return $result;
	}
	public function get_tutor_details($id)
	{
		$this->db->select('id,name,profileImg');
	    $this->db->from('user');
	    $this->db->where('id',$id);
	    $query = $this->db->get();
        $result = $query->row_array();
        return $result;
	}
	// public function getCourseByUserIdAndType($data){
	// 	$current_date = date('Y-m-d H:i:s');

	// 	$this->db->select('c.*,u.name as created_by_name');
	//     $this->db->from('course c');
	//     $this->db->join('users u','u.id =c.created_by');
	//     $this->db->where('c.c_type',$data['type']);
	//     $this->db->where('c.created_by',$data['userId']);
	//     // if(!empty($data['course'])){
	//     // 	$this->db->where_in('c.id', $data['course']);
	//     // }
	//     // if(!empty($data['cat_id'])){
	//     // 	$this->db->where('c.cat_id',$data['cat_id']);
	//     // }
	//     $query = $this->db->get();
    //     $result = $query->result_array();
    //     //echo $this->db->last_query();exit;
    //     return $result;
	// }
}
?>