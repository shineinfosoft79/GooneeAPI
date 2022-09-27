<?php

Class Api_categories_mdl extends CI_Model{

	protected $table = 'categories';
	
	public function __construct() {
		$this->table = 'categories';
	}

	public function get_cat(){
		$this->db->select("*");
		$this->db->from($this->table);
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_sub_cat_1($cid){
		$this->db->select("id,name");
		$this->db->where('c_id',$cid);
		$this->db->from('sub_categories');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_topics($data){
		$this->db->select("*");
		$this->db->where('c_id',$data['c_id']);
		$this->db->where('s_cid',$data['c_s_id']);
		$this->db->from('interst_topic');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_s_expert($data){
		$this->db->select("created_by");
		$this->db->where('cat_id',$data['c_id']);
		$this->db->where_not_in('created_by', $data['myConn']);
		$this->db->from('schudule');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_c_expert($data){
		$this->db->select("created_by");
		$this->db->where('cat_id',$data['c_id']);
		$this->db->where_not_in('created_by', $data['myConn']);
		$this->db->from('course');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_expert_detail($exp){
		$this->db->select("id,name,profileImg");
		$this->db->where_in('id',$exp);
		$this->db->from('users');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_hour_charge($id){
		$this->db->select("hour_rate");
		$this->db->where('created_by',$id);
		$this->db->from('one2one');
		$this->db->limit(1);
		$this->db->order_by('id','DESC');
		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}

	public function get_sub_cat($data){
		$this->db->select("*");
		$this->db->where('c_id',$data['c_id']);
		$this->db->from('sub_categories');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_cat_detail($data){
		$this->db->select("*");
		$this->db->where('id',$data['c_id']);
		$this->db->from('categories');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_cat_course($data){
		$this->db->select("id,title,thumb,fees");
		$this->db->where('cat_id',$data['c_id']);
		$this->db->where('c_type', 'publish');
	    	$this->db->where_in('created_by', $data['myConn']);
		$this->db->from('course');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_cat_webinar($data){
		$this->db->select("s.id,s.title,s.thumb_img,s.fees,s.created_date as date_time,u.name as created_by_name");
		$this->db->where('cat_id',$data['c_id']);
		$this->db->from('schudule s');
		$this->db->where_in('s.created_by', $data['myConn']);
		$this->db->join('users u','u.id =s.created_by');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_transaction($data){
		$first_date = date('Y-m-d', strtotime( $data['startDate'] ));
		$second_date  = date('Y-m-d', strtotime( $data['endDate'] ));
		$condition = "DATE_FORMAT(c.created_date, '%Y-%m-%d') BETWEEN '$first_date' AND '$second_date' ";
		$this->db->where($condition);
		$this->db->select("c.*,u.profileImg,u.name");
		if($data['type']!=''){
		$this->db->where('c.type',$data['type']);
		}
		$this->db->where('c.checkout_status','paid');
		$this->db->where('c.uid',$data['uid']);
		$this->db->from('cart c');
		$this->db->join('users u','u.id =c.uid');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	public function get_transaction_one2one($data){
		$first_date = date('Y-m-d', strtotime( $data['startDate'] ));
		$second_date  = date('Y-m-d', strtotime( $data['endDate'] ));
		$condition = "DATE_FORMAT(c.created, '%Y-%m-%d') BETWEEN '$first_date' AND '$second_date' ";
		$this->db->where($condition);
		$this->db->select("c.*,c.item_price as price,c.item_type as type,c.created as created_date,u.profileImg,u.name");
		$this->db->where('c.u_id',$data['uid']);
		$this->db->where('c.item_type','one2one');
		$this->db->from('transaction c');
		$this->db->join('users u','u.id =c.u_id');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_c_detail($data){
		$this->db->select("c.*");
		$this->db->where('c.id',$data['cid']);
		$this->db->from('course c');
		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}

	public function get_s_detail($data){
		$this->db->select("s.*,s.thumb_img as thumb");
		$this->db->where('s.id',$data['cid']);
		$this->db->from('schudule s');
		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}
	public function get_o_detail($data){
		$this->db->select("s.*,t.name as title");
		$this->db->where('s.id',$data['item_id']);
		$this->db->from('one2oneSetCall s');
		$this->db->join('interst_topic t','t.id =s.topic');
		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}

	public function get_topic(){
		$this->db->select("*");
		$this->db->from('interst_topic');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function add_group(){
		$data['type'] = 'personal';
		$this->db->insert('groups', $data);
   		$insert_id = $this->db->insert_id();
   		return  $insert_id;
	}

	public function add_group_user($data,$gid){

		foreach ($data as $key => $value) {
			$inserted_data['user_id'] = $value;
			$inserted_data['group_id'] = $gid;
			$this->db->insert('group_users', $inserted_data);
		}
		

		return true;
	}

	public function get_tc(){
		$this->db->select("*");
		$this->db->from('t&c');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function faq(){
		$this->db->select("*");
		$this->db->from('faq');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function pp(){
		$this->db->select("*");
		$this->db->from('privacy_policy');
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_my_connection_2($data){
		$this->db->select("*");
		$this->db->where('id', $data['id']);
		$this->db->from('connection');
		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}

	public function approve_connection($data){
		$date = date('Y-m-d h:i:s', time());
		$this->db->where('id', $data['id']);
		$this->db->set('approved','true');
		$this->db->set('connection_date',$date);
		$this->db->update('connection');
		
		//Amit code
		$query = $query = "SELECT `userId`,`requestBy` FROM `connection` where `id` = ".$data['id'];
		$result = $this->db->query($query)->row_array();
		
		$this->db->where('userId', $result['requestBy']);
		$this->db->where('requestBy', $result['userId']);
		$this->db->set('approved','true');
		$this->db->set('connection_date',$date);
		$this->db->update('connection');
		return true;		

	}

	public function removed_connection($data){

		$query = $query = "SELECT `userId`,`requestBy` FROM `connection` where `id` = ".$data['id'];
		$result = $this->db->query($query)->row_array();

		$this->db->where('userId', $result['requestBy']);
		$this->db->where('requestBy', $result['userId']);
		$this->db->or_where('requestBy', $result['requestBy']);
		$this->db->where('userId', $result['userId']);
    	$this->db->delete('connection');	
    }

    public function add_connection($data){
    	$this->db->insert('connection', $data);
    	return $this->db->insert_id();
    }

    public function reinset_add_connection($data){
    	$this->db->insert('connection', $data);
    	return $this->db->insert_id();
    }

    public function add_notification($data){
    	$this->db->insert('notification', $data);
    	return $this->db->insert_id();
    }

	public function remove_notification($data){
		$this->db->where('nid', $data['nid']);
    	$this->db->delete('notification');
	}
	public function user_insert($data){
		$this->db->insert($this->table, $data);
		return true;
	}

	public function add_cart($data){
		$this->db->insert('cart', $data);
		return true;
	}

	public function show_unread_one2one($data){
		//old code

		// $this->db->select('n.id,m.message,m.user_id');
		// $this->db->from('notification n');
		// $this->db->join('message m','m.message_id = n.id');
		// $this->db->where('n.type','message');
		// $this->db->where('n.status','unread');
		// $query = $this->db->get();
		// $result = $query->result_array();
		// return $result;

		// new code(Get sender users details)
		$this->db->select('n.*,s.name,s.profileImg');
		$this->db->from('notification n');
		$this->db->join('one2oneSetCall o','o.id = n.id');
		$this->db->join('users s','s.id =o.userId');
		$this->db->where('n.uid', $data['uid']);
		$this->db->where('n.type','one2one');
		$this->db->where('n.status','unread');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function show_unread_message($data){
		//old code

		// $this->db->select('n.id,m.message,m.user_id');
		// $this->db->from('notification n');
		// $this->db->join('message m','m.message_id = n.id');
		// $this->db->where('n.type','message');
		// $this->db->where('n.status','unread');
		// $query = $this->db->get();
		// $result = $query->result_array();
		// return $result;

		// new code(Get sender users details)
		$this->db->select('n.*,u.name,u.profileImg,m.message');
		$this->db->from('notification n');
		$this->db->join('message m','m.message_id = n.id');
		$this->db->join('users u','u.id =m.user_id');
		$this->db->where('n.uid', $data['uid']);
		$this->db->where('n.type','message');
		$this->db->where('n.status','unread');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function show_unread_request($data){
		// old code 

		// $this->db->select('n.*,u.name,u.profileImg');
		// $this->db->from('notification n');
		// $this->db->join('users u','u.id =n.uid');
		// $this->db->where('n.uid', $data['uid']);
		// $this->db->where('n.type','request');
		// $this->db->where('n.status','unread');
		// $query = $this->db->get();
		// $result = $query->result_array();
		// return $result;

		//amit code

		$this->db->select('n.*,u.name,u.profileImg');
		$this->db->from('notification n');
		$this->db->join('connection c','c.id = n.id AND c.requestBy ='.$data['uid']);
		$this->db->join('users u','u.id =c.userId');
		$this->db->where('n.uid', $data['uid']);
		$this->db->where('n.type','request');
		$this->db->where('n.status','unread');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result; 
	}

	public function read_single_notification($data){
		$update = ['status'=>'read'];
		$this->db->where('nid', $data['nid']);
		$this->db->update('notification', $update);
		// echo $this->db->last_query();exit;
	}

	public function read_notification($data){
		$update = ['status'=>'read'];
		$this->db->where('type', $data['type']);
		$this->db->where('uid', $data['uid']);
		$this->db->update('notification', $update);
	}

	public function remove_cart($data){
		$update = ['checkout_status'=>'paid'];
		$this->db->where('uid', $data['uid']);
		$this->db->update('cart', $update);
		//$this->db->where('checkout_status!=','paid');
    	//$this->db->delete('cart');
	}

	public function remove_single_cart($data){
		$this->db->where('id', $data['id']);
		$this->db->where('checkout_status!=','paid');
    	$this->db->delete('cart');
	}

	public function get_topic_course($id){
		$this->db->select('c.*,u.name as created_by_name');
	    $this->db->from('course c');
	    $this->db->join('users u','u.id =c.created_by');
		$this->db->where('c_type', 'publish');
		$this->db->where_in('c.created_by', $data['myConn']);
	    $this->db->where('find_in_set("'.$id.'", topic) <> 0');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_topic_scheduled($id){
		$this->db->select('s.*,u.name  as created_by_name');
	    $this->db->from('schudule s');
	    $this->db->join('users u','u.id =s.created_by');
		$this->db->where_in('s.created_by', $data['myConn']);
	    $this->db->where('find_in_set("'.$id.'", topic_id) <> 0');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_topic_user($id){
		$this->db->select('*');
	    $this->db->from('users');
	    $this->db->where('find_in_set("'.$id.'", interst_topic) <> 0');
		$this->db->where_not_in('id', $data['myConn']);
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function list_p_cart($data){

		$this->db->select("c.id,u.name,s.title,s.attach,s.limit,s.fees");
		$this->db->from('cart c');
		$this->db->where('c.checkout_status','paid');
		$this->db->where('c.type','webinar');

		$this->db->where('c.uid',$data['uid']);
		$this->db->join('users u','u.id =c.uid');
		$this->db->join('schudule s','s.id =c.cid');
		
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function list_p_cart_course($data){

		$this->db->select("c.id,u.name,s.title,s.thumb as attach,s.fees");
		$this->db->from('cart c');
		$this->db->where('c.checkout_status','paid');
		$this->db->where('c.uid',$data['uid']);
		$this->db->join('users u','u.id =c.uid');
		$this->db->where('c.type','course');

		$this->db->join('course s','s.id =c.cid');
		
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function list_cart($data){

		$this->db->select("c.id,u.name,s.title,s.thumb_img as attach,s.limit,s.fees");
		$this->db->from('cart c');
		$this->db->where('c.checkout_status !=','paid');
		$this->db->where('c.type','webinar');

		$this->db->where('c.uid',$data['uid']);
		$this->db->join('schudule s','s.id =c.cid');
		$this->db->join('users u','u.id =s.created_by');

		
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function list_cart_course($data){

		$this->db->select("c.id,u.name,s.title,s.thumb as attach,s.fees");
		$this->db->from('cart c');
		$this->db->where('c.checkout_status !=','paid');
		$this->db->where('c.uid',$data['uid']);
		$this->db->where('c.type','course');
		$this->db->join('course s','s.id =c.cid');
		$this->db->join('users u','u.id =s.created_by');
		
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}
	public function get_connection($data){
		$this->db->select("u.name,c.id,u.id as user_id,c.connection_date,c.request_date,u.profileImg");
		$this->db->where('c.userId',$data['u_id']);
		//$this->db->where('c.requestBy',$data['u_id']);
		$this->db->where('c.status !=','remove');
		if($data['type'] == 'connection'){
			$this->db->where('approved','true');
		}else{
			$this->db->where('approved','false');
		}
		$this->db->from('connection c');
		$this->db->join('users u','u.id =c.requestBy');
		//$this->db->join('users u','u.id =c.userId');

		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function get_meetings_count(){
		$this->db->select('*');
	    $this->db->from('one2one');
	    $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
	}

	public function get_Webinars_count(){
		$this->db->select('*');
	    $this->db->from('schudule');
	    $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
	}

	public function check_connection($data){
		$this->db->select('*');
	    $this->db->from('connection');
	    $this->db->where('userId',$data['userId']);
	    $this->db->where('requestBy',$data['requestBy']);
	    $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
	}

	public function get_vc_count(){
		$this->db->select('*');
	    $this->db->from('course');
	    $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
	}
	public function get_topics_list($topic){
		$this->db->select("*");
		$this->db->from('interst_topic');
		$this->db->where_in('id',$topic);
		if( $result = $this->db->get()->result_array() ){ return $result; }
		else{ return []; }
	}

	public function update_connection($data){
		$this->db->where('userId', $data['userId']);
		$this->db->where('requestBy', $data['requestBy']);
		$this->db->update('connection', $data['set']);
	}

	public function get_what_would_learn(){
		$this->db->select('*');
	    $this->db->from('what_would_learn');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_my_connection($data){
		$this->db->select('*');
	    $this->db->from('connection');
	    $this->db->where('requestBy',$data['uid']);
	    $this->db->where('blocked !=','true');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_connection_list($data){
		$this->db->select('id,name,email,user_type,profileImg');
	    $this->db->from('users');
		
	    if(!empty($data['myConn'])){
	    	$this->db->where_not_in('id', $data['myConn']);
	    }

		//Get tutor data in connection tab
	    if($data['role'] == 'tutor'){
	    	$this->db->where('user_type','user');
	    }else{
	    	$this->db->where('user_type','tutor');
	    }
	    
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_tutor_list(){
		$this->db->select('*');
	    $this->db->from('users');
	    $this->db->where('user_type','tutor');
	    $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_cart($data){
		$this->db->select('*');
	    $this->db->from('cart');
	    $this->db->where('uid',$data['uid']);
	    $this->db->where('checkout_status !=','paid');
	    $query = $this->db->get();
        $result = $query->result_array();
        	//echo $this->db->last_query();exit;
        return $result;
	}

	public function get_card($data){
		$this->db->select('*');
	    $this->db->from('card_details');
	    $this->db->where('u_id',$data['u_id']);
	    $this->db->where('p_type',$data['p_type']);
	    $this->db->limit(1);
		$this->db->order_by('id','DESC');
		$query = $this->db->get();

        $result = $query->result_array();
        return $result;
	}

	public function add_checkout($id){
		$this->db->set('checkout_status','paid');
		$this->db->where('id', $id);
		$this->db->update('cart');
	}

	public function add_my_payment($data){
		$this->db->insert('checkout', $data);
		return true;
	}

	public function add_card($data){
		$this->db->insert('card_details', $data);
		return true;
	}
	public function get_card_detail($data){
		$this->db->select("*");
		$this->db->from('card_details');
		$this->db->where('u_id',$data['uid']);
		$this->db->order_by('id','DESC');
		$this->db->limit(1);

		if( $result = $this->db->get()->row_array() ){ return $result; }
		else{ return []; }
	}

	public function remove_connection_notification($data){
		$query = $query = "SELECT `id` FROM `notification` where `nid` = ".$data['nid'];
		$result = $this->db->query($query)->row_array();
		$query = $query = "SELECT `userId`,`requestBy` FROM `connection` where `id` = ".$result['id'];
		$result1 = $this->db->query($query)->row_array();

		$this->db->where('userId', $result1['requestBy']);
		$this->db->where('requestBy', $result1['userId']);
		$this->db->or_where('requestBy', $result1['requestBy']);
		$this->db->where('userId', $result['userId']);
    	$this->db->delete('connection');
		
		$this->db->where('nid', $data['nid']);
    	$this->db->delete('notification');
	}
	public function update_card($data){
		$param = array(
			"card_holder"=> $data['card_holder'],
			"card_no"=> $data['card_no'],
			"exp_month"=>$data['card_no'],
			"exp_year"=>$data['exp_year'],
		);
		$this->db->where('uid', $data['uid']);
		$this->db->where('p_type', $data['p_type']);
		$this->db->update('card_details', $param);
	}
}
?>