<?php 

Class Api_schedule extends MX_Controller{

	public function __construct() {
		$this->load->model('Api_schedule_modal');
	}

	## list course
	public function list_course(){
		try { 
			## validation
			$data = $this->validation_list_c();
			
			$result['course'] = $this->Api_schedule_modal->list_course($data);
			
			$this->api_handler->api_response("200", "post", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}
	
	protected function validation_list_c(){
	
		$config = array(
			array(
				'field' => 'type',
				'label' => 'type',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	##set one to one call 
	public function get_one2one(){
		try { 
			## validation
			$data = $this->validation_get_one2one();

			$result = $this->Api_schedule_modal->get_onetoone($data);
			
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	protected function validation_get_one2one(){
		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function my_call(){
		try { 
			## validation
			$data = $this->validation_my_call();
			
			$myCall = $this->Api_schedule_modal->get_mycall($data);

			$this->api_handler->api_response("200", "get", array(), $myCall);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_my_call(){
	
		$config = array(
			// array(
			// 	'field' => 'uid',
			// 	'label' => 'uid',
			// 	'rules' => 'required'
			// )
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function update_one2one(){
		try { 
			## validation
			$data = $this->validation_update_one2one();
				if($data['call_start_time']){
					$data['call_start_time']=	date('Y-m-d h:i:s', strtotime($data['call_start_time']));
				}
			$this->Api_schedule_modal->update_onetoone($data);
			
			$this->api_handler->api_response("200", "update", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_update_one2one(){
	
		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			),
			array(
				'field' => 'status',
				'label' => 'status',
				'rules' => 'required'
			),
			array(
				'field' => 'call_start_time',
				'label' => 'call_start_time',
				'rules' => ''
			),
			array(
				'field' => 'refund_status',
				'label' => 'refund_status',
				'rules' => ''
			),
			array(
				'field' => 'reject_by',
				'label' => 'reject_by',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function set_one2one(){
		try { 
			## validation
			$data = $this->validation_set_one2one();
			
			$data['created_date'] =  date('Y-m-d h:i:s', time());
			$data['status'] = 'pending';

			$inserted_id = $this->Api_schedule_modal->add_onetoone($data);

			$notificationData = ['id' => $inserted_id,'uid'=>$data['tutorId'],'type'=>'one2one','status'=>'unread','created_date'=>date('Y-m-d h:i:s', time()) ];
			$this->Api_schedule_modal->add_notification($notificationData);
			
			$this->api_handler->api_response("200", "add", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	protected function validation_set_one2one(){
	
		$config = array(
			array(
				'field' => 'date',
				'label' => 'date',
				'rules' => 'required'
			),
			array(
				'field' => 'userId',
				'label' => 'userId',
				'rules' => 'required'
			),
			array(
				'field' => 'tutorId',
				'label' => 'tutorId',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function list_course_by_user_id(){
		try {
			## validation
			$data = $this->validation_list_c_by_id();

			$result['myCourse'] = $this->Api_schedule_modal->getMyPurchaseCorse($data);

			//_P($result['myCourse']);exit;

			$courseDetail = [];
			foreach ($result['myCourse'] as $key => $value) {
				$res = $this->Api_schedule_modal->get_my_course($value,$data);

				if(!empty($res)){
					$courseDetail[$key] = $res;
					$courseDetail[$key]['parts'] = $this->Api_schedule_modal->list_course_for_part($courseDetail[$key]);
				}
				
			}
			
			$this->api_handler->api_response("200", "post", array(), $courseDetail);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}
	
	protected function validation_list_c_by_id(){
	
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			),
			array(
				'field' => 'search',
				'label' => 'search',
				'rules' => ''
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}
	
	
	// Amit code start
	// public function list_course_by_user_id_and_type(){
	// 	try {

	// 		## validation
	// 		$data = $this->validation_list_c_by_id_and_type();

	// 		$result['myCourse'] = $this->Api_schedule_modal->getCourseByUserIdAndType($data);

	// 		// _P($result['myCourse']);exit;

	// 		$courseDetail = [];
	// 		foreach ($result['myCourse'] as $key => $value) {
	// 			$res = $this->Api_schedule_modal->get_my_course($value,$data);

	// 			if(!empty($res)){
	// 				$courseDetail[$key] = $res;
	// 				$courseDetail[$key]['parts'] = $this->Api_schedule_modal->list_course_for_part($courseDetail[$key]);
	// 			}
				
	// 		}
			
	// 		$this->api_handler->api_response("200", "post", array(), $courseDetail);

	// 	}catch (Exception $e){
	// 		$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
	// 	}

	// }

	// public function validation_list_c_by_id_and_type(){
	// 	$config = array(
	// 		array(
	// 			'field' => 'userId',
	// 			'label' => 'userId',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'type',
	// 			'label' => 'type',
	// 			'rules' => 'required'
	// 		)
	// 	);
	// 	return $this->api_handler->api_validation($config,"post",false);
	// }

	// Amit code end

	## list schedule for calender
	public function list_schedule_for_calender(){
		try { 
			## validation
			$data = $this->validation_list_schedule_for_calender();

			$result['schedule'] = $this->Api_schedule_modal->list_schedule_for_calender($data);
			//$result['course'] = $this->Api_schedule_modal->list_course_for_calender();
			$result['onetoone'] = $this->Api_schedule_modal->list_one2one_for_calender($data);

			//_P($result['onetoone']);exit;

			$merge_arr = [];

			foreach ($result['schedule'] as $key => $value1) {
				$merge_arr[]=	[
					  'Id'=> $value1['id'],
					  'Subject'=> $value1['title'],
					  'StartTime'=> $value1['date_time'],
					  'EndTime'=> date('Y-m-d', strtotime($value1['date_time'])).' '.$value1['end_time'],
					//   'EndTime'=> $this->add_one_hour($value1['date_time']),
					  'type'=>'webinar',
					  'Color' => '#6c60e1'
					];
			}

			foreach ($result['onetoone'] as $key => $value2) {
				$merge_arr[]=	[
					  'Id'=> $value2['id'],
					  'Subject'=> 'One To One Meeting',
					  'StartTime'=> $value2['date'].' '.$value2['start_time'],
					//   'EndTime'=> $value2['end_time'],
					  'EndTime'=> $this->add_one_hour($value2['date'].' '.$value2['start_time']),
					  'type'=>'one2one',
					  'Color' => '#63BE6B'
					];
			}

			$this->api_handler->api_response("200", "get", array(), $merge_arr);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	function add_one_hour($time){
		$one_hour = strtotime($time);
		$one_hour = date('Y-m-d H:i:s', strtotime('+1 hour',$one_hour));
		return $one_hour;
	}

	protected function validation_list_schedule_for_calender(){
	
		$config = array(
			array(
				'field' => 'created_by',
				'label' => 'created_by',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	## list schedule
	public function list_schedule(){
		try { 
			## validation
			$data = $this->validation_list_s();

			if($data['upcoming-past'] == 'upcoming'){
				$result['schedule'] = $this->Api_schedule_modal->u_list_schedule($data);
			}else if($data['upcoming-past'] == 'current'){
				$result['schedule'] = $this->Api_schedule_modal->c_list_schedule($data);
			}
			else{
				$result['schedule'] = $this->Api_schedule_modal->p_list_schedule($data);
			}
			foreach ($result['schedule'] as $key => $value) {
				$result['schedule'][$key]['webinar_count'] = $this->Api_schedule_modal->getWebinarCount(array('id'=>$value['id']));
					
			}
			
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	public function list_s_user(){
		try { 
			## validation
			$data = $this->validation_list_s();

			$get_my_topic = $this->Api_schedule_modal->get_my_topic($data['uid']);

			$topic = explode(",",$get_my_topic['interst_topic']);
			if($data['upcoming-past'] == 'upcoming'){
				$result['schedule'] = $this->Api_schedule_modal->up_list_schedule($data);
			}else if($data['upcoming-past'] == 'current'){
				$result['schedule'] = $this->Api_schedule_modal->cr_list_schedule($data);
			}
			else{
				$result['schedule'] = $this->Api_schedule_modal->ps_list_schedule($data);
			}

			foreach ($result['schedule'] as $key => $value) {
				$p = $this->Api_schedule_modal->is_purchased($data['uid'],$value['id']);
				if($p){
					$result['schedule'][$key]['purchased'] = true;
				}else{
					$result['schedule'][$key]['purchased'] = false;
				}
					
			}

			$this->api_handler->api_response("200", "get", array(), $result);

			}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function list_c_user(){
		try { 
			## validation
			$data = $this->validation_list_s();

			$get_my_topic = $this->Api_schedule_modal->get_my_topic($data['uid']);

			$topic = explode(",",$get_my_topic['interst_topic']);

			if($data['upcoming-past'] == 'upcoming'){
				$result['schedule'] = $this->Api_schedule_modal->up_c_list_schedule($data);
			}else if($data['upcoming-past'] == 'current'){
				$result['schedule'] = $this->Api_schedule_modal->cr_c_list_schedule($data);
			}
			else{
				$result['schedule'] = $this->Api_schedule_modal->ps_c_list_schedule($data);
			}

			$this->api_handler->api_response("200", "get", array(), $result);

			}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function list_s_by_user(){
		try { 
			## validation
			$data = $this->validation_list_s();

			$user_cart =  $this->Api_schedule_modal->get_user_cart($data['uid']);

			$course = [];
			$shedule = [];

			foreach ($user_cart as $key => $value) {
				if($value['type'] == 'webinar'){
					$shedule[] = $value['cid'];
				}
				if($value['type'] == 'course'){
					$course[] = $value['cid'];
				}
			}

			$data['schedule'] = $shedule;

			if($data['upcoming-past'] == 'upcoming'){
				$result['schedule'] = $this->Api_schedule_modal->u_list_schedule($data);
			}else if($data['upcoming-past'] == 'current'){
				$result['schedule'] = $this->Api_schedule_modal->c_list_schedule($data);
			}
			else{
				$result['schedule'] = $this->Api_schedule_modal->p_list_schedule($data);
			}
			
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}


	public function list_c_by_user(){
		try {
			## validation
			$data = $this->validation_list_course();

			$user_cart =  $this->Api_schedule_modal->get_user_cart($data['uid']);

			$course = [];
			$shedule = [];

			foreach ($user_cart as $key => $value) {
				if($value['type'] == 'webinar'){
					$shedule[] = $value['cid'];
				}
				if($value['type'] == 'course'){
					$course[] = $value['cid'];
				}
			}

			$data['course'] = $course;

			$result['course'] = $this->Api_schedule_modal->list_course_detail($data);
			
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_list_course(){
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	protected function validation_list_s(){
	
		$config = array(
			array(
				'field' => 'upcoming-past',
				'label' => 'upcoming-past',
				'rules' => 'required'
			),array(
				'field' => 'search',
				'label' => 'search',
				'rules' => ''
			),
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	#get
	public function get_user_meeting(){
		try { 
			## validation
			$data = $this->validation_get_user_meeting();

			$get['data'] = $this->Api_schedule_modal->get_user_meeting($data);

			$totalFees = 0;

			foreach ($get['data'] as $key => $value) {
				$totalFees = $totalFees+$value['fees'];
			}

			$get['totalFees'] = $totalFees;

			$this->api_handler->api_response("200", "get", array(), $get);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_user_meeting(){
		$config = array(
			array(
				'field' => 'course_id',
				'label' => 'course_id',
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

	##review

	public function get_review_by_id(){
		try { 
			## validation
			$data = $this->validation_get_review_by_id();

			$result = $this->Api_schedule_modal->get_review_by_id($data);

			foreach ($result as $key => $rr) {
				if($rr['profileImg'] == null || $rr['profileImg'] == ''){
					$result[$key]['profileImg'] = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png";
				}elseif ($rr['social_provider_type'] == 0) {
					$result[$key]['profileImg'] = base_url().$result['profileImg'];
				}
			}

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_review_by_id(){
		$config = array(
			array(
				'field' => 'course_id',
				'label' => 'course_id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function add_review(){
		try { 
			## validation
			$data = $this->validation_add_review();

			$data['created_date'] = date('Y-m-d h:i:s', time());

			$this->Api_schedule_modal->add_review($data);

			$this->api_handler->api_response("200", "add_review", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_review(){
		$config = array(
			array(
				'field' => 'cid',
				'label' => 'cid',
				'rules' => 'required'
			),
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			),
			array(
				'field' => 'review',
				'label' => 'review',
				'rules' => 'required'
			),
			array(
				'field' => 'type',
				'label' => 'type',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	## add schedule
	public function add_schedule(){
		try { 
			## validation
			$data = $this->validation_add_s();

			$this->Api_schedule_modal->insert_schedule($data);

			$this->api_handler->api_response("200", "add_schedule", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	protected function validation_add_s(){
		
		$config = array(
			array(
				'field' => 'title',
				'label' => 'title',
				'rules' => 'required'
			),
			array(
				'field' => 'desc',
				'label' => 'desc',
				'rules' => 'required'
			),
			array(
				'field' => 'cat_id',
				'label' => 'cat_id',
				'rules' => 'required'
			),
			array(
				'field' => 'topic_id',
				'label' => 'topic_id',
				'rules' => 'required'
			),
			array(
				'field' => 'date_time',
				'label' => 'date_time',
				'rules' => 'required'
			),
			array(
				'field' => 'chat',
				'label' => 'chat',
				'rules' => 'required'
			),
			array(
				'field' => 'sub_cat_id',
				'label' => 'sub_cat_id',
				'rules' => 'required'
			),
			array(
				'field' => 'created_date',
				'label' => 'created_date',
				'rules' => 'required'
			),
			array(
				'field' => 'created_by',
				'label' => 'created_by',
				'rules' => 'required'
			),

		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	##edit webinar
	public function get_by_id(){
		try { 
			$data = $this->validation_get_data();

			$result = $this->Api_schedule_modal->get_data($data);
			$array  = $this->Api_schedule_modal->purchaseStatus($data);
			$result['webinar_count'] = $this->Api_schedule_modal->getWebinarCount($data);
			
			if(!empty($array)){
				$result['purchased'] = true;
			}else{
				$result['purchased'] = false;
			}
			if($data['table_name'] == "course"){
				$result["video_parts"] = $this->Api_schedule_modal->get_parts_data($result['id']);
			}
			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_data(){
		$config = array(
			array(
				'field' => 'id',
				'label' => 'id',
				'rules' => 'required'
			),
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => ''
			),
			array(
				'field' => 'table_name',
				'label' => 'table_name',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function update_by_id(){
		try { 
			$data = $this->validation_get_data();

			$videoPart = $data['post_data']['video_parts'];
			unset($data['post_data']['video_parts']);
			//_P($data);exit;
			$result = $this->Api_schedule_modal->update_data($data);

			if($data['table_name'] == "course"){
				//_P($data['post_data']['video_parts']);exit;
				$this->Api_schedule_modal->delete_old_parts($data['id']);

				if(!empty($videoPart)){
					foreach ($videoPart as $key => $value) {
						$part_data = [
								'c_id'=>$data['id'],
								'video_title'=>$value['video_title'],
								'videoLink'=>$value['videoLink'],
								'otherAttachName'=>$value['otherAttachName'],
								'videoName'=>$value['videoName'],

								'otherAttachment'=>$value['otherAttachment'],
								'publish'=>$value['publish'],
								'created_date'=>$data['created_date'],
								'created_by'=>$data['created_by']
							];

							$this->Api_schedule_modal->insert_parts($part_data);
					}
				}
				//$result["video_parts"] = $this->Api_schedule_modal->update_parts_data($result['id']);
			}

			$this->api_handler->api_response("200", "updated", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	// protected function validation_add_s(){
		
	// 	$config = array(
	// 		array(
	// 			'field' => 'title',
	// 			'label' => 'title',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'desc',
	// 			'label' => 'desc',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'cat_id',
	// 			'label' => 'cat_id',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'topic_id',
	// 			'label' => 'topic_id',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'date_time',
	// 			'label' => 'date_time',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'chat',
	// 			'label' => 'chat',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'sub_cat_id',
	// 			'label' => 'sub_cat_id',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'create_date',
	// 			'label' => 'create_date',
	// 			'rules' => 'required'
	// 		),
	// 		array(
	// 			'field' => 'created_by',
	// 			'label' => 'created_by',
	// 			'rules' => 'required'
	// 		),

	// 	);
	// 	return $this->api_handler->api_validation($config,"post",false);
	// }


	## onbe2one add
	public function add_one2one(){
		try { 
			## validation
			$data = $this->validation_add_onetoone();
			$data['created_date']= date('Y-m-d h:i:s', time());
			$this->Api_schedule_modal->insert_oneToone($data);

			$this->api_handler->api_response("200", "insert_1to1", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_onetoone(){
		
		$config = array(
			array(
				'field' => 'hour_rate',
				'label' => 'hour_rate',
				'rules' => 'required'
			),
			array(
				'field' => 'late_charge',
				'label' => 'late_charge',
				'rules' => 'required'
			),
			array(
				'field' => 'cansalation_charge',
				'label' => 'cansalation_charge',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function get_one2one_byId(){
		try { 
			## validation
			$data = $this->validation_get_one2one_byId();

			$result = $this->Api_schedule_modal->get_one2one_byId($data);

			$this->api_handler->api_response("200", "get", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_get_one2one_byId(){
		
		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'u_id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	## course add
	public function add_course(){
		try { 
			## validation
			$data = $this->validation_add_course();

			$part_video = $data['video_parts'];

			unset($data['video_parts']);

			$insert_id = $this->Api_schedule_modal->insert_course($data);

			if($insert_id){
				if(!empty($part_video)){
					foreach ($part_video as $key => $value) {
						$part_data = [
							'c_id'=>$insert_id,
							'video_title'=>$value['video_title'],
							'videoLink'=>$value['videoLink'],
							'otherAttachment'=>$value['otherAttachment'],
							'publish'=>$value['publish'],
							'created_date'=>$data['created_date'],
							'created_by'=>$data['created_by']
						];

						$this->Api_schedule_modal->insert_parts($part_data);
					}
			}
		}

			$this->api_handler->api_response("200", "insert_course", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_course(){
		
		$config = array(
			array(
				'field' => 'title',
				'label' => 'title',
				'rules' => 'required'
			),
			array(
				'field' => 'desc',
				'label' => 'desc',
				'rules' => 'required'
			),
			array(
				'field' => 'cat_id',
				'label' => 'cat_id',
				'rules' => 'required'
			),
			array(
				'field' => 'sub_cat_id',
				'label' => 'sub_cat_id',
				'rules' => 'required'
			),
			array(
				'field' => 'topic',
				'label' => 'topic',
				'rules' => 'required'
			),
			array(
				'field' => 'preview_video',
				'label' => 'preview_video',
				'rules' => 'required'
			),
			// array(
			// 	'field' => 'video_title',
			// 	'label' => 'video_title',
			// 	'rules' => 'required'
			// ),
			// array(
			// 	'field' => 'c_title',
			// 	'label' => 'c_title',
			// 	'rules' => 'required'
			// ),
			array(
				'field' => 'fees',
				'label' => 'fees',
				'rules' => 'required'
			),
			array(
				'field' => 'thumb',
				'label' => 'thumb',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	public function watch_history(){
		try { 
			## validation
			$data = $this->validation_watch_history();

			$reseult = $this->Api_schedule_modal->watch_history($data);

			$this->api_handler->api_response("200", "insert", array(), $reseult);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

		protected function validation_watch_history(){
		
		$config = array(
			array(
				'field' => 'uid',
				'label' => 'uid',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}


	public function add_watch(){
		try { 
			## validation
			$data = $this->validation_add_watch();

			$data['created_date'] = date('Y-m-d h:i:s', time());

			$insert_id = $this->Api_schedule_modal->insert_watch($data);

			$this->api_handler->api_response("200", "insert", array(), $data);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_add_watch(){
		
		$config = array(
			array(
				'field' => 'cid',
				'label' => 'cid',
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




	public function uploadFile(){
		try {
			## Upload directory : assets/upload/other
			$result['File'] = "";


			if(!empty($_FILES['File']['name'])){

					$file_name = $_FILES['File']['name'] ;
					$file_ext = substr($file_name, strripos($file_name, '.'));
					$newfilename = rand(11111,99999) . $file_ext;
					$file_size = $_FILES['File']['size'];
					$file_tmp = $_FILES['File']['tmp_name'];
					$file_type = $_FILES['File']['type'];
					$file_ext=strtolower($file_ext);
					$expensions= array(".jpeg",".jpg",".png",".mp4",".pdf");
					
					if(in_array($file_ext,$expensions)=== FALSE){
						$response['status']='error';
						$response['message']='extension not allowed, please choose a JPEG, JPG or PNG file.';
					}
					if(!empty($_FILES['File']['name'])){
						move_uploaded_file($file_tmp,FCPATH."assets/upload/other/".$newfilename);
						$data['File'] = $newfilename;


						$profile_full_url =  base_url()."assets/upload/other/".$newfilename;
						$profile_updated_url = "assets/upload/other/".$newfilename;

						$result['File'] = $profile_full_url;

						$update_data = ['File '=>$profile_updated_url];
						$response['message']='Image updated.';

					}else{
						$response['status']='error';
						$response['message']='Image file name is empty.';
					}
			}else{
					$response['status']='error';
					$response['message']='Image file is empty.';
			}


			$this->api_handler->api_response("200", $response['message'], array(),$result);


		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	public function remove_group()
	{
		try {
			$data =  $this->validation_remove_group();
			
			$this->Api_schedule_modal->remove_g($data['u_id']);
			
			$this->api_handler->api_response("200", "remove", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_remove_group(){
		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);
	}

	public function chat_user_list()
	{
		try {
			$data =  $this->validation_chat_user();
			$user_result = $this->Api_schedule_modal->get_chat_user_list($data['u_id']);
			foreach($user_result as $key =>$user){
				$user_result[$key]['users'] = $this->Api_schedule_modal->get_chat_group_user($user['group_id'],$data['u_id'],$data['search']);
				foreach ($user_result[$key]['users'] as $k => $value) {
					if($value['profileImg'] == '' || $value['profileImg'] == null){ $user_result[$key]['users'][$k]['profileImg'] = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';}
				}
				$user_result[$key]['last_message'] = $this->Api_schedule_modal->get_chat_last_message($user['group_id']);
				$user_result[$key]['is_connection'] = $this->Api_schedule_modal->find_connection_status($user['group_id'],$data['u_id']);
			}
			$result=[];
			foreach($user_result as $key =>$user){
				if(count($user['users']) !=0){
					$result[] = $user;
				}
			}
			$this->api_handler->api_response("200", "post", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}

	protected function validation_chat_user(){

		$config = array(
			array(
				'field' => 'u_id',
				'label' => 'id',
				'rules' => 'required'
			),array(
				'field' => 'search',
				'label' => 'search',
			)
		);
		return $this->api_handler->api_validation($config,"post",false);

	}

	public function chat_user_message()
	{
		try {
			$data =  $this->validation_chat_message();
			
			$result = $this->Api_schedule_modal->get_chat_messages($data['group_id']);
			
			$this->api_handler->api_response("200", "post", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	protected function validation_chat_message(){

		$config = array(
			array(
				'field' => 'group_id',
				'label' => 'id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);

	}

	public function meeting_user_message()
	{
		try {
			$data =  $this->validation_meeting_message();
			//print_r($data);exit;
			$result = $this->Api_schedule_modal->get_meeting_messages($data['room_id']);
			
			$this->api_handler->api_response("200", "post", array(), $result);

		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}
	}
	protected function validation_meeting_message(){

		$config = array(
			array(
				'field' => 'room_id',
				'label' => 'room_id',
				'rules' => 'required'
			)
		);
		return $this->api_handler->api_validation($config,"post",false);

	}
}

?>