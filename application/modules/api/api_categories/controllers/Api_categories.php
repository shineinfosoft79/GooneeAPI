<?php 

Class Api_categories extends MX_Controller{

	public function __construct() {
		$this->load->model('Api_categories_mdl');
	}
	## get user
	public function get_category(){
		try {

			$cat_result = $this->Api_categories_mdl->get_cat();
			
			foreach ($cat_result as $key => $value) {
				$cat_result[$key]['sub_cat'] = $this->Api_categories_mdl->get_sub_cat_1($value['id']);
			}
			$this->api_handler->api_response("200", "cat_get", array(), $cat_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	##add card
	public function add_card(){
		try {
			$data = $this->validation_add_card();

			$data['created_date'] = date('Y-m-d h:i:s', time());

			$res = $this->Api_categories_mdl->get_card($data);
			if(!empty($res))
			{
				$this->Api_categories_mdl->update_card($data);
				$this->api_handler->api_response("200", "Card Updated", array(), $data);
			}
			else
			{
				$this->Api_categories_mdl->add_card($data);
				$this->api_handler->api_response("200", "Card Added", array(), $data);
			}

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_card(){
		$config = array(
			array(
				'field' => 'card_holder',
				'label' => 'card_holder',
				'rules' => 'required'
			),
			array(
				'field' => 'card_no',
				'label' => 'card_no',
				'rules' => 'required'
			),
			array(
				'field' => 'exp_month',
				'label' => 'exp_month',
				'rules' => 'required'
			),
			array(
				'field' => 'exp_year',
				'label' => 'exp_year',
				'rules' => 'required'
			)
			,
			array(
				'field' => 'p_type',
				'label' => 'p_type',
				'rules' => 'required'
			),
			array(
				'field' => 'u_id',
				'label' => 'u_id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function cat_sub_cat(){
		try {
			$post_data = $this->validation_cat_sub_cat();

			$result = [];

			if($post_data['c_id']){
				$result['sub_cat'] = $this->Api_categories_mdl->get_sub_cat_1($post_data['c_id']);
			}
			if($post_data['c_s_id']){
				$result['topics'] = $this->Api_categories_mdl->get_topics($post_data);
			}


			//$result = $this->Api_categories_mdl->get_card($post_data);

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_cat_sub_cat(){
				$config = array(
			array(
				'field' => 'c_id',
				'label' => 'c_id',
				'rules' => 'required'
			),
			// array(
			// 	'field' => 'c_s_id',
			// 	'label' => 'c_s_id',
			// 	'rules' => 'required'
			// )
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	public function get_card(){
		try {
			$post_data = $this->validation_get_card_detail();

			$result = $this->Api_categories_mdl->get_card($post_data);

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_card_detail(){
		$config = array(
			array(
				'field' => 'p_type',
				'label' => 'p_type',
				'rules' => 'required'
			),
			array(
				'field' => 'u_id',
				'label' => 'u_id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function get_card_detail(){
    	try {
			## fields validation
			$data = $this->validation_card_detail();
		//	print_r($data['id']);exit;

			$user_result = $this->Api_categories_mdl->get_card_detail($data);
			$this->api_handler->api_response("200", "user_get", array(), $user_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
    }

    protected function validation_card_detail(){

		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	##contact_us
	public function contact_us(){
		try {
			$data = $this->validation_contact_us();

			$this->load->library('Email_lib');

			// 'sendto' => 'pradip.k@techroversolutions.com',
			// 'sendto' => 'abhishek@huptechweb.com',
			$email_data = [
				'sendto' => 'abhishek@huptechweb.com',
				'cc' => 'kumar@huptechweb.com',
				'parser_name' => "api_email_parser/email_contact",
				'parse_content' =>$data,
				'subject' => 'Goonee Contact Us',
				'comment' => $data['message']
				];
				$msg = 'Help';

				$this->email_lib->sendEmail($email_data);
				// print_r($a);exit;


			$this->api_handler->api_response("200", "Mail_send", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_contact_us(){
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
				'field' => 'email',
				'label' => 'email',
				'rules' => 'required'
			),
			array(
				'field' => 'phone',
				'label' => 'phone',
				'rules' => 'required'
			)
			,
			array(
				'field' => 'message',
				'label' => 'message',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}



	## send help 
	public function help_send(){
		try {
			$data = $this->validation_help();

			$this->load->library('Email_lib');
			 
			## old configuration of email
			// 'sendto' => 'bansi.s@techroversolutions.com',
			$email_data = [
				'sendto' => 'abhishek@huptechweb.com',  // for the testing purpose
				'parser_name' => "api_email_parser/email_help",
				'parse_content' => [
					'comment' =>$data['comment']
				],
				'subject' => $data['subject'],
				'comment' =>$data['comment']
				];
				$msg = 'Help';

				$this->email_lib->sendEmail($email_data);


			$this->api_handler->api_response("200", "help", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	##Topic
	public function get_topics(){
		try {
			$cat_result = $this->Api_categories_mdl->get_topic();
			$this->api_handler->api_response("200", "cat_topic", array(), $cat_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}


	protected function validation_help(){
		$config = array(
			array(
				'field' => 'subject',
				'label' => 'subject',
				'rules' => 'required'
			),
			array(
				'field' => 'comment',
				'label' => 'comment',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	##cart
	public function checkout(){
		try {
			$data = $this->validation_checkout();
			
			$get_cart = $this->Api_categories_mdl->get_cart($data);

			$total = 0;

			foreach ($get_cart as $key => $value) {
				$total = $total+$value['price'];
				$this->Api_categories_mdl->add_checkout($value['id']);
			}

			$post_data = ['uid'=>$data['uid'],'total'=>$total,'created_date'=>date('Y-m-d h:i:s', time())];

			$this->Api_categories_mdl->add_my_payment($post_data);

			$this->api_handler->api_response("200", "checkout", array(), $post_data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_checkout(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			),
			array(
				'field' => 'payment_type',
				'label' => 'payment_type',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function remove_cart(){
		try {
			$data = $this->validation_remove_cart();
			$this->Api_categories_mdl->remove_cart($data);
			$this->api_handler->api_response("200", "remove", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function get_transaction(){
		try {
			$data = $this->validation_get_transcation();
			if(isset($data['user_type']) && $data['user_type']=='tutor')
			{
				
				if($data['type'] =='' ){
					$webinar_transaction = $this->Api_categories_mdl->get_transaction_webinar_tutor($data);
					$course_transaction = $this->Api_categories_mdl->get_transaction_course_tutor($data);
					$transaction_one2one = $this->Api_categories_mdl->get_transaction_one2one_tutor($data);
					$transaction_data = array_merge($webinar_transaction,$course_transaction);
					$transaction = array_merge($transaction_data,$transaction_one2one);
				}
				else if($data['type'] =='webinar')
				{
					$transaction = $this->Api_categories_mdl->get_transaction_webinar_tutor($data);
				}
				else if( $data['type'] =='course')
				{
					$transaction = $this->Api_categories_mdl->get_transaction_course_tutor($data);
				}
				else if( $data['type'] =='one2one')
				{
					$transaction = $this->Api_categories_mdl->get_transaction_one2one_tutor($data);
				}
				$result = [];
				foreach ($transaction as $key => $value) {
					$transaction[$key]['purchaseby'] = $value['name'];
					if($value['type']=='course'){
						$tmp = $this->Api_categories_mdl->get_c_detail($value);
					}else if($value['type']=='webinar'){
						$tmp = $this->Api_categories_mdl->get_s_detail($value);
					}else if($value['type']=='one2one'){
						$tmp = $this->Api_categories_mdl->get_o_detail($value);
					}
					$admin_commission = $this->Api_categories_mdl->getAdminCommission();
					$t_per = 100 - (int)$admin_commission;
					$tutor_amt  = round(((int)$value['price'] * $t_per)/100) * 100;
					$transaction[$key]['total'] = $tutor_amt;
					$transaction[$key]['courseName'] = $tmp['title'];
					if($tmp['thumb'] == null || $tmp['thumb'] == ''){
						$tmp['thumb'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
					}
					$transaction[$key]['productImg'] = $tmp['thumb'];

				}
			}
			else
			{
				$transaction = $this->Api_categories_mdl->get_transaction($data);
				if($data['type'] =='' || $data['type'] =='one2one'){
					$transaction_one2one = $this->Api_categories_mdl->get_transaction_one2one($data);
					$transaction = array_merge($transaction,$transaction_one2one);
				}
				$result = [];
				foreach ($transaction as $key => $value) {
					$transaction[$key]['purchaseby'] = $value['name'];
					if($value['type']=='course'){
						$tmp = $this->Api_categories_mdl->get_c_detail($value);
					}else if($value['type']=='webinar'){
						$tmp = $this->Api_categories_mdl->get_s_detail($value);
					}else if($value['type']=='one2one'){
						$tmp = $this->Api_categories_mdl->get_o_detail($value);
					}
					$admin_commission = $this->Api_user_login_mdl->getAdminCommission();
					$t_per = 100 - (int)$admin_commission;
					$tutor_amt  = round(((int)$value['price'] * $t_per)/100) * 100;
					$transaction[$key]['total'] = $tutor_amt;
					$transaction[$key]['courseName'] = $tmp['title'];
					if($tmp['thumb'] == null || $tmp['thumb'] == ''){
						$tmp['thumb'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
					}
					$transaction[$key]['productImg'] = $tmp['thumb'];

				}
			}
			$this->api_handler->api_response("200", "get", array(), $transaction);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_remove_cart(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	protected function validation_get_transcation(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			),
			array(
				'field' => 'startDate',
				'label' => 'startDate',
				'rules' => ''
			),array(
				'field' => 'endDate',
				'label' => 'endDate',
				'rules' => ''
			),
			array(
				'field' => 'type',
				'label' => 'type',
				'rules' => ''
			),
			array(
				'field' => 'user_type',
				'label' => 'user_type',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function remove_single_cart(){
		try {
			$data = $this->validation_remove_single_cart();
			$this->Api_categories_mdl->remove_single_cart($data);
			$this->api_handler->api_response("200", "remove", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_remove_single_cart(){
		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function add_cart(){
		try {
			$data = $this->validation_add_cart();
			$data['created_date'] = date('Y-m-d h:i:s', time());
			$data['checkout_status']='';
			$data['payment_type']='';
			$this->Api_categories_mdl->add_cart($data);
			$this->api_handler->api_response("200", "add", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_cart(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			),
			array(
				'field' => 'type',
				'label' => 'type',
				'rules' => 'required'
			),
			array(
				'field' => 'price',
				'label' => 'price',
				'rules' => 'required'
			),
			array(
				'field' => 'cid',
				'label' => 'cid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function list_paid_cart(){
		try {
			$data = $this->validation_list_cart();
			$result['schedule'] = $this->Api_categories_mdl->list_p_cart($data);
			$result['course'] = $this->Api_categories_mdl->list_p_cart_course($data);
			//$res = array_merge($result,$result1);
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function list_cart(){
		try {
			$data = $this->validation_list_cart();
			$result = $this->Api_categories_mdl->list_cart($data);
			$result1 = $this->Api_categories_mdl->list_cart_course($data);
			$res = array_merge($result,$result1);
			$this->api_handler->api_response("200", "get", array(), $res);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_list_cart(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	#TC
	public function terms_cond(){
		try {
			
			$tc = $this->Api_categories_mdl->get_tc();
			$this->api_handler->api_response("200", "get", array(), $tc);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function faq(){
		try {
			
			$faq = $this->Api_categories_mdl->faq();
			$this->api_handler->api_response("200", "get", array(), $faq);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function pp(){
		try {
			
			$faq = $this->Api_categories_mdl->pp();
			$this->api_handler->api_response("200", "get", array(), $faq);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	## cat
	public function get_sub_cat(){
		try {
			$data = $this->validation_get_sub();
			$cat_result = $this->Api_categories_mdl->get_sub_cat($data);

			foreach ($cat_result as $key => $value) {
				if($value['img'] == null){
					$cat_result[$key]['thumb'] = 'https://smart-menu.hotelbudget.us/GooneeApi/assets/subC.jpg';
				}else{
					// $cat_result[$key]['thumb'] = base_url().$value['img'];
					$cat_result[$key]['thumb'] =(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].'/'.$value['img'];
				}
				
			}
			$this->api_handler->api_response("200", "cat_topic", array(), $cat_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function get_detail_by_topic(){
		try {
			$data = $this->validation_get_detail_by_topic();
			if(!empty($data['uid'])){
				$myConn = $this->Api_categories_mdl->get_my_connection($data);

				$data['myConn'] = [];
				foreach ($myConn as $key => $value) {
					$data['myConn'][] = $value['userId'];
				}
			}
			$result['course']=[];
			$result['schedule']=[];
			$result['expert']=[];
			if(!empty($data['myConn'])){
				$result['course'] = $this->Api_categories_mdl->get_topic_course($data['topic']);
				$result['schedule'] = $this->Api_categories_mdl->get_topic_scheduled($data['topic']);
			    $result['expert'] = $this->Api_categories_mdl->get_topic_user($data['topic']);
			}
			


			foreach ($result['expert'] as $key => $value) {
				 $rate = $this->Api_categories_mdl->get_hour_charge($value['id']);
				 if($rate['hour_rate'] == null || empty($rate)){ $rate['hour_rate'] = 0;}
				 $result['expert'][$key]['rate'] = $rate['hour_rate'];
				 $result['interesting_topic_list'] = [];
				if($value['interst_topic']){
							$topics = explode(",",$value['interst_topic']);
							$result['expert'][$key]['interesting_topic_list'] = $this->Api_categories_mdl->get_topics_list($topics);
						}
			}
			

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_detail_by_topic(){
		$config = array(
			array(
				'field' => 'topic',
				'label' => 'topic',
				'rules' => 'required'
			),
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	public function get_sub_cat_detail(){
		try {
			$data = $this->validation_get_sub();
			$data['cid'] = $data['c_id'];
			if(!empty($data['uid'])){
				$myConn = $this->Api_categories_mdl->get_my_connection($data);

				$data['myConn'] = [];
				foreach ($myConn as $key => $value) {
					$data['myConn'][] = $value['userId'];
				}
			}

			$cat_result['cat_detail'] = $this->Api_categories_mdl->get_cat_detail($data);
			$cat_result['sub_detail'] = $this->Api_categories_mdl->get_sub_cat($data);

			//_P($cat_result['cat_detail']);exit;

			$cat_result['course']=[];
			$cat_result['schedule'] =[];
			$exp_c =[];
			$exp_s =[];
			if(!empty($data['myConn'])){
				$cat_result['course'] = $this->Api_categories_mdl->get_cat_course($data);
				$cat_result['schedule'] = $this->Api_categories_mdl->get_cat_webinar($data);
				$exp_c = $this->Api_categories_mdl->get_c_expert($data);
			$exp_s = $this->Api_categories_mdl->get_s_expert($data);
			}
			

			$expert = array_merge($exp_c,$exp_s);
			$tmp = [];
			foreach ($expert as $key => $value) {
				$tmp[] = $value['created_by'];
			}
			$expert = array_unique($tmp);

			//
			if($expert){
					$exp_detail = $this->Api_categories_mdl->get_expert_detail($expert);
			}
			

			foreach ($exp_detail as $key => $value) {
				 $rate = $this->Api_categories_mdl->get_hour_charge($value['id']);
				 if($rate['hour_rate'] == null || empty($rate)){ $rate['hour_rate'] = 0;}
				 $exp_detail[$key]['rate'] = $rate['hour_rate'];
			}

			$cat_result['expert_detail'] =  $exp_detail;


			foreach ($cat_result['expert_detail'] as $key => $value) {
				if($value['profileImg'] == null){
					$cat_result['expert_detail'][$key]['profileImg'] = base_url('assets/upload/profile/connectionPro.png');
				}elseif(substr($value['profileImg'],0,4) == "http"){
					$cat_result['expert_detail'][$key]['profileImg'] = $value['profileImg'];
				}else{
					$cat_result['expert_detail'][$key]['profileImg'] = base_url($value['profileImg']);

				}

				$cat_result['interesting_topic_list'] = [];
						if($value['interst_topic']){
							$topics = explode(",",$value['interst_topic']);
							$cat_result['expert_detail'][$key]['interesting_topic_list'] = $this->Api_categories_mdl->get_topics_list($topics);
						}
			}


			foreach ($cat_result['sub_detail'] as $key => $value) {
				if($value['img'] == null){
					$cat_result['sub_detail'][$key]['thumb'] = 'https://smart-menu.hotelbudget.us/GooneeApi/assets/subC.jpg';
				}else{
					$cat_result['sub_detail'][$key]['thumb'] = base_url().$value['img'];
				}
				
			}
			$this->api_handler->api_response("200", "get", array(), $cat_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}


	protected function validation_get_sub(){
		$config = array(
			array(
				'field' => 'c_id',
				'label' => 'c_id',
				'rules' => 'required'
			),
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	## Connection

	public function add_connection(){
		try {
			$data = $this->validation_add_connection();

			$check_connection = $this->Api_categories_mdl->check_connection($data);

			if($check_connection != 0){
				$data['desc']="You are already Requested!";
				$data['connected'] = true;
				$this->api_handler->api_response("400", "exist", array(), $data);
			}else{
				$data['connected'] = false;
			}

			unset($data['connected']);

			$data['approved'] = 'false';
			$data['blocked'] = 'false';
			$data['report'] = 'false';
			$data['status'] = 'pending';
			$data['request_date'] = date('Y-m-d h:i:s', time());
			$userId = $data['userId'];
			$data['userId'] = $data['requestBy'];
			$a = $data['userId'];
			$data['requestBy'] = $userId;
			$b = $data['requestBy'];



			$inserted_id = $this->Api_categories_mdl->add_connection($data);

			$data['approved'] = 'true';
			$data['userId'] = $b;
			$data['requestBy'] = $a;

			$inserted_id1 =  $this->Api_categories_mdl->reinset_add_connection($data);

			$notificationData = ['id' => $inserted_id1,'uid'=>$a,'type'=>'request','status'=>'unread','created_date'=>$data['request_date'] ];
			$this->Api_categories_mdl->add_notification($notificationData);

			$this->api_handler->api_response("200", "add", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_connection(){
		$config = array(
			array(
				'field' => 'userId',
				'label' => 'userId',
				'rules' => 'required'
			),array(
				'field' => 'requestBy',
				'label' => 'requestBy',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	## notification
	public function read_notification(){
		try {
			$data = $this->validation_read_notification();

			if($data['nid']){
				$this->Api_categories_mdl->read_single_notification($data);
			}else{
				$this->Api_categories_mdl->read_notification($data);
			}

			$this->api_handler->api_response("200", "update", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_read_notification(){
		$config = array(
			array(
				'field' => 'type',
				'label' => 'type',
				'rules' => 'required'
			),array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function show_notification(){
		try {
			$data = $this->validation_show_notification();

			$result['message']=$this->Api_categories_mdl->show_unread_message($data);
			$result['request']=$this->Api_categories_mdl->show_unread_request($data);
			$result['one2one']=$this->Api_categories_mdl->show_unread_one2one($data);
			//print_r($result['message']);exit;
			foreach ($result['request'] as $key => $user_result) {
				if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
					$result['request'][$key]['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
				}elseif(substr($user_result['profileImg'],0,4) == "http"){
					$result['request'][$key]['profileImg'] = $user_result['profileImg'];
				}else{
					$result['request'][$key]['profileImg'] = base_url($user_result['profileImg']);
				}
			}
			foreach ($result['message'] as $key => $user_result) {
				if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
					$result['message'][$key]['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
				}elseif(substr($user_result['profileImg'],0,4) == "http"){
					$result['message'][$key]['profileImg'] = $user_result['profileImg'];
				}else{
					$result['message'][$key]['profileImg'] = base_url($user_result['profileImg']);
				}
			}
			foreach ($result['one2one'] as $key => $user_result) {
				if($user_result['profileImg'] == null || $user_result['profileImg'] == ''){
					$result['one2one'][$key]['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
				}elseif(substr($user_result['profileImg'],0,4) == "http"){
					$result['one2one'][$key]['profileImg'] = $user_result['profileImg'];
				}else{
					$result['one2one'][$key]['profileImg'] = base_url($user_result['profileImg']);
				}
				$result['one2one'][$key]['message']=" You have a scheduled call with ".$result['one2one'][$key]['name'];
			}
		
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_show_notification(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function remove_notification(){
		try {
			$data = $this->validation_remove_notification();

			$this->Api_categories_mdl->remove_notification($data);

			$this->api_handler->api_response("200", "get", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_remove_notification(){
		$config = array(
			array(
				'field' => 'nid',
				'label' => 'nid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function approve_connection(){
		try {
			$data = $this->validation_approve_connection();

			$this->Api_categories_mdl->approve_connection($data);

			$conn = $this->Api_categories_mdl->get_my_connection_2($data);

			$gid = $this->Api_categories_mdl->add_group();

			$temp = [];
			if($conn){
					$temp[0] = $conn['userId'];
					$temp[1] = $conn['requestBy'];
			}

			$this->Api_categories_mdl->add_group_user($temp,$gid);
				

			$this->api_handler->api_response("200", "updated", array(), array());

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_approve_connection(){
		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function remove_connection(){
		try {
			$data = $this->validation_approve_connection();

			$this->Api_categories_mdl->removed_connection($data);

			$this->api_handler->api_response("200", "remove", array(), array());

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function get_connection(){
		try {
			$data = $this->validation_get_connection();

			$con_result = $this->Api_categories_mdl->get_connection($data);

			foreach ($con_result as $key => $value) {
				if($value['profileImg'] == null){
					$con_result[$key]['profileImg'] = base_url('assets/upload/profile/connectionPro.png');
				}elseif(substr($value['profileImg'],0,4) == "http"){
					$con_result[$key]['profileImg'] = $value['profileImg'];
				}else{
					$con_result[$key]['profileImg'] = base_url($value['profileImg']);
				}
			}

			$this->api_handler->api_response("200", "get", array(), $con_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_connection(){
		$config = array(
			array(
				'field' => 'type',
				'label' => 'type',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function update_connection(){
		try {
			$data = $this->validation_update_connection();
			
			if($data['status'] == 'blocked'){
				$data['set']['blocked'] = 'true';
			}elseif($data['status'] == 'report'){
				$data['set']['report'] = 'true';
			}elseif($data['status'] == 'remove'){
				$data['set']['approved'] = 'false';
			}

			$data['set']['approved'] = 'false';
			$data['set']['status'] = 'remove';
			$data['set']['reason'] = $data['reason'];


			unset($data['status']);

			$this->Api_categories_mdl->update_connection($data);

			$this->api_handler->api_response("200", "updated", array(), $con_result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_update_connection(){
		$config = array(
			array(
				'field' => 'userId',
				'label' => 'userId',
				'rules' => 'required'
			),
			array(
				'field' => 'status',
				'label' => 'status',
				'rules' => 'required'
			),
			array(
				'field' => 'reason',
				'label' => 'reason',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}



	#get_overall_earning
	public function get_overall_earning(){
		try {
			//$data = $this->validation_get_overall_earning();

			$result['one2one'] = $this->Api_categories_mdl->get_meetings_count();
			$result['Webinars'] = $this->Api_categories_mdl->get_Webinars_count();
			$result['vc'] = $this->Api_categories_mdl->get_vc_count();
			$response['labels'] = ['One to one','Webinars','Video Courses'];
			$response['datasets'] = [
						  'data'=> [$result['one2one'], $result['Webinars'], $result['vc'] ],
						  'backgroundColor'=> [
						    "#63BE6B",
						    "#E3E1F5",
						    "#6C60E1"
						  ],
						  'borderColor'=> [
						    "#63BE6B",
						    "#E3E1F5",
						    "#6C60E1"
						  ],
						];
						

			$this->api_handler->api_response("200", "get", array(), $response);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	// protected function validation_get_overall_earning(){
	// 	$config = array(
	// 		array(
	// 			'field' => 'type',
	// 			'label' => 'type',
	// 			'rules' => 'required'
	// 		)
	// 	);
	// 	return $this->api_handler->api_validation($config,"post",false);
	// }


	public function what_would_learn(){
		try {
			$result = $this->Api_categories_mdl->get_what_would_learn();

			foreach ($result as $key => $value) {
				$result[$key]['image'] = base_url().$value['image'];
			}
				

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function list_connection(){
		try {
			$data = $this->get_list_connection();

			if(!empty($data['uid'])){
					$myConn = $this->Api_categories_mdl->get_my_connection($data);

					$data['myConn'] = [];
					foreach ($myConn as $key => $value) {
						$data['myConn'][] = $value['userId'];
					}
			}

			$result = $this->Api_categories_mdl->get_connection_list($data);

			foreach ($result as $key => $value) {
				if($value['profileImg'] == null || $value['profileImg'] == ''){
					$result[$key]['profileImg'] =  "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
				}elseif($value['social_provider_type'] == 0){
					$result[$key]['profileImg'] = $value['profileImg'];
				}
			}		

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function get_list_connection(){
		$config = array(
			array(
				'field' => 'role',
				'label' => 'role',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function tutor_list(){
		try {
			$result = $this->Api_categories_mdl->get_tutor_list();


			foreach ($result as $key => $value) {
				 $rate = $this->Api_categories_mdl->get_hour_charge($value['id']);

				 if($rate['hour_rate'] == null || empty($rate)){ $rate['hour_rate'] = 0;}
				 $result[$key]['rate'] = $rate['hour_rate'];
			}



			foreach ($result as $key => $value) {
						if($value['profileImg'] == null || $value['profileImg'] == ''){
							$result[$key]['profileImg'] =  "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
						}elseif(substr($value['profileImg'] , 0, 4) == 'http'){
							$result[$key]['profileImg'] = $value['profileImg'];
						}else{
							$result[$key]['profileImg'] = base_url().$value['profileImg'];
						}
						$value['interesting_topic_list'] = [];
						if($value['interst_topic']){
							$topics = explode(",",$value['interst_topic']);
							$result[$key]['interesting_topic_list'] = $this->Api_categories_mdl->get_topics_list($topics);
						}
					}		

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	## review
	public function check_connection(){
		try {
			$data = $this->validation_check_connection();

			$noOfRow = $this->Api_categories_mdl->check_connection($data);

			if($noOfRow != 0){
				$data['connected'] = true;
			}else{
				$data['connected'] = false;
			}

			$this->api_handler->api_response("200", "remove", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_check_connection(){
		$config = array(
			array(
				'field' => 'userId',
				'label' => 'userId',
				'rules' => 'required'
			),
			array(
				'field' => 'requestBy',
				'label' => 'requestBy',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	## Code added : 31/08/2022
	## Get Webinar Count 
	public function user_webinar_count()
	{
		try {
			$data = $this->validation_user_webinar_count();

			$result = $this->Api_categories_mdl->check_connection($data);

			$this->api_handler->api_response("200", "remove", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	protected function validation_user_webinar_count(){
		$config = array(
			array(
				'field' => 'wid',
				'label' => 'wid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function remove_connection_notification(){
		try {
			$data = $this->validation_remove_connection_notification();
			
			$this->Api_categories_mdl->remove_connection_notification($data);

			$this->api_handler->api_response("200", "get", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}


	}

	public function validation_remove_connection_notification(){
		$config = array(
			array(
				'field' => 'nid',
				'label' => 'nid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
}

?>