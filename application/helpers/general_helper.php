<?php

//benchmark(TRUE);
function benchmark($action, $config = array()){
    
    $CI =& get_instance();
    $sections = array(
        'benchmarks'         => isset($config['benchmarks'])?$config['benchmarks']:TRUE,
        'config'             => isset($config['config'])?$config['config']:TRUE,
        'controller_info'    => isset($config['controller_info'])?$config['controller_info']:TRUE,
        'get'                => isset($config['get'])?$config['get']:TRUE,
        'http_headers'       => isset($config['http_headers'])?$config['http_headers']:TRUE,
        'memory_usage'       => isset($config['memory_usage'])?$config['memory_usage']:TRUE,
        'post'               => isset($config['post'])?$config['post']:TRUE,
        'queries'            => isset($config['queries'])?$config['queries']:TRUE,
        'uri_string'         => isset($config['uri_string'])?$config['uri_string']:TRUE,
        'session_data'       => isset($config['session_data'])?$config['session_data']:TRUE,
        'query_toggle_count' => isset($config['query_toggle_count'])?$config['query_toggle_count']:25,
    );
    $CI->output->set_profiler_sections($sections);
    $CI->output->enable_profiler($action);

}

function pr($a = array()) {

	if (empty($a)) return false;

	echo "<pre>";
	print_r($a);
	echo "</pre>";
	
}

function _p($a = array()) {
	
	if (empty($a)) return false;

	echo "<pre>";
	print_r($a);
	echo "</pre>";
	
}

function GetUser_Tbl($type = "") {

	if ($type == "" || !in_array($type, array('a', 'c'))) return "";

	if ($type == "a") {
		$tbl = "ipm_admin_users";
	} elseif ($type == "c") {
		$tbl = "ipm_company_users";
	} else {
		$tbl = "";
	}

	return $tbl;
}

function ACL_ArrayHelper($acl = array()) {

	$return = array();

	$return['eCanView'] = (in_array("view", $acl)) ? "yes" : "no";
	$return['eCanEdit'] = (in_array("edit", $acl)) ? "yes" : "no";
	$return['eCanAdd'] = (in_array("add", $acl)) ? "yes" : "no";
	$return['eCanDelete'] = (in_array("delete", $acl)) ? "yes" : "no";
	$return['eCanApprove'] = (in_array("approve", $acl)) ? "yes" : "no";
	$return['eCouponSubmit_Store'] = (in_array("coupon_submit_to_store", $acl)) ? "yes" : "no";


	/*
		  foreach($acl as $acl_key => $acl_name){

			$return[$acl_name] = "yes";
		}
	*/

	return $return;

}
/////////////////////////

function _current_year(){
	return (int)date('Y');
}
function _current_quarter(){
	return (int)(ceil(date('n') / 3));
}
function _current_week(){
	//return (int)date("W");
	$weeks = weeks(_current_year(), null, date('Y-m-d'));
	return $weeks['id'];
}
function _current_month(){
	return (int)date('m');
}

//////////////////////////

## check isset and not empty
function _isset_not_empty($value,$key){
	return ( isset($value[$key]) && !empty($value[$key]) );
}

function _isset_not_empty_($value,$key){
	return ( isset($value[$key]) && !empty($value[$key]) ) ? $value[$key] : NULL;
}
function get_dates_of_week($year, $week){

	// $start = (new DateTime())->setISODate($year, $week)->format('Y-m-d 00:00:00'); //start date
	// $end = (new DateTime())->setISODate($year, $week, 7)->format('Y-m-d 23:59:59'); //end date
	$dates = weeks($year, $week);
	return [
		'start' => $dates['start']." 00:00:00",
		'end' => $dates['end']." 23:59:59",
		'end_date' => $dates['end']." 23:59:59",
	];
}
function get_duration_dates($duration_in, $year=NULL, $quarter=NULL, $week=NULL){

	$return = [];
	if( empty($year) ){ $year =  _current_year(); }
	if( empty($quarter) ){ $quarter = _current_quarter(); }
	if( empty($week) ){ $week = _current_week(); }

	if( $duration_in == 'year' ){
		$return['start'] = date('Y-m-d 00:00:00', strtotime('first day of jan '.$year));
		$return['end'] = $return['end_date'] = date('Y-m-d 23:59:59', strtotime('last day of dec '.$year));
	}else if( $duration_in == 'quarter' ){
		$return = get_dates_of_quarter((int)$quarter, (int)$year, 'Y-m-d H:i:s');
	}else if( $duration_in == 'week' ){
		$return = get_dates_of_week($year, $week);
	}
	$return['year'] = $year;
	$return['quarter'] = $quarter;
	$return['quarter_name'] = ('Q'.$quarter);
	$return['week'] = $week;
	$return['week_name'] = ('Week '.$week);
	$return['current'] = date('Y-m-d H:i:s');
	$return['days'] = total_day_count($return['start'], $return['end']);

	return $return;

}
function total_day_count($start_date, $end_date) {
	$date1 = new DateTime($start_date);
	$date2 = new DateTime($end_date);
	## fixed with added one plus, because at that time i was not got solution, and this will gives me 1 year decremented days
	return (int)($date2->diff($date1)->format("%a")+1);
}



//////////////////////
function Ipm_Pagination($total_rec = "", $p_page = "", $c_page = "", $s_page = "", $url = "") {

	if ($total_rec == "" || $p_page == "" || $c_page == "" || $s_page == "") {
		throw new Exception("Argument missing for pagination");
	}

	if ($url == "") {
		$url = "#";
	}

	$total_records = $total_rec;
	$perpage = $p_page;
	$total_pages = ceil($total_records / $perpage);

	$current_page = $c_page;
	$showmax_page = $s_page;

	$paging['total_pages'] = $total_pages;
	$paging['current_page'] = $current_page;
	$paging['previous_page'] = "";
	$paging['next_page'] = "";
	$paging['showmax_page'] = $showmax_page;

	/** Go to First page **/
	if ($current_page != "1") {
		$paging['first'] = array('num' => '1', 'range' => "1-" . $perpage);
	}

	/** Go to Previous page **/
	//if($current_page!="1" && $current_page!="2"){
	if ($current_page != "1") {

		$pre_num = $current_page - 1;
		$pre_to = $pre_num * $perpage;
		$pre_from = $pre_to - ($perpage - 1);
		$pre_to = ($pre_to > $total_records) ? $total_records : $pre_to;

		$paging['previous'] = array('num' => $pre_num, 'range' => $pre_from . "-" . $pre_to);
		$paging['previous_page'] = $pre_num;

	}

	/** Go to Last page **/
	if ($current_page != $total_pages) {

		$last_num = $total_pages;
		$last_to = $last_num * $perpage;
		$last_from = $last_to - ($perpage - 1);
		$last_to = ($last_to > $total_records) ? $total_records : $last_to;

		$paging['last'] = array('num' => $last_num, 'range' => $last_from . "-" . $last_to);
	}


	/** Go to Next page **/
	if ($current_page != $total_pages) {

		$next_num = $current_page + 1;
		$next_to = $next_num * $perpage;
		$next_from = $next_to - ($perpage - 1);
		$next_to = ($next_to > $total_records) ? $total_records : $next_to;

		$paging['next'] = array('num' => $next_num, 'range' => $next_from . "-" . $next_to);
		$paging['next_page'] = $next_num;
	}

	$paging['page_list'] = array();

	$count = 1;
	for ($i = $current_page; $i <= $total_pages; $i++) {

		$to = $i * $perpage;
		$from = $to - ($perpage - 1);
		$to = ($to > $total_records) ? $total_records : $to;

		$paging['page_list'][$count - 1]['num'] = $i;
		$paging['page_list'][$count - 1]['range'] = $from . "-" . $to;

		if ($showmax_page == $count) break;

		$count++;

	}

	$return['data'] = $paging;
	$return['html'] = Ipm_PagingHtml($paging, $url);

	return $return;

}

function Ipm_PagingHtml($pg = array(), $url) {

	$html['wrapper_start'] = '<ul class="pagination pull-right" data-PreviousPage="' . $pg['previous_page'] . '" data-CurrentPage="' . $pg['current_page'] . '" data-NextPage="' . $pg['next_page'] . '" style="margin:0;">';

	if (isset($pg['first']) && !empty($pg['first'])) {
		$html['first'] = '<li id="pg_first" class="paginate_button first"><a href="' . $url . '?page=' . $pg['first']['num'] . '" data-page="' . $pg['first']['num'] . '" data-range="' . $pg['first']['range'] . '">First</a></li>';
	} else {
		$html['first'] = '<li id="pg_first" class="paginate_button first disabled"><a href="#">First</a></li>';
	}

	if (isset($pg['previous']) && !empty($pg['previous'])) {
		$html['previous'] = '<li id="pg_previous" class="paginate_button previous"><a href="' . $url . '?page=' . $pg['previous']['num'] . '" data-page="' . $pg['previous']['num'] . '" data-range="' . $pg['previous']['range'] . '">Previous</a></li>';
	} else {
		$html['previous'] = '<li id="pg_previous" class="paginate_button previous disabled"><a href="#">Previous</a></li>';
	}


	if (isset($pg['page_list']) && !empty($pg['page_list'])) {

		foreach ($pg['page_list'] as $pkey => $page) {
			$html['pages'][$pkey] = '<li id="page_' . $page['num'] . '" class="paginate_button page"><a href="' . $url . '?page=' . $page['num'] . '" data-page="' . $page['num'] . '" data-range="' . $page['range'] . '">' . $page['num'] . '</a></li>';
		}

	} else {
		$html['pages'][0] = '<li id="pg_next" class="paginate_button next disabled"><a href="#">next</a></li>';
	}


	if (isset($pg['next']) && !empty($pg['next'])) {
		$html['next'] = '<li id="pg_next" class="paginate_button next"><a href="' . $url . '?page=' . $pg['next']['num'] . '" data-page="' . $pg['next']['num'] . '" data-range="' . $pg['next']['range'] . '">Next</a></li>';
	} else {
		$html['next'] = '<li id="pg_next" class="paginate_button next disabled"><a href="#">next</a></li>';
	}


	if (isset($pg['last']) && !empty($pg['last'])) {
		$html['last'] = '<li id="pg_last" class="paginate_button last"><a href="' . $url . '?page=' . $pg['last']['num'] . '" data-page="' . $pg['last']['num'] . '" data-range="' . $pg['last']['range'] . '">Last</a></li>';
	} else {
		$html['last'] = '<li id="pg_last" class="paginate_button last disabled"><a href="#">last</a></li>';
	}

	$html['wrapper_end'] = "</ul>";

	return $html;

}

function two_date_diff($date1, $date2) {
	
	$diff = abs(strtotime($date2) - strtotime($date1));

	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

	$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 

	$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 

	$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 

	return array('years' => $years, 'months' => $months, 'days' => $days, 'hours' => $hours, 'minutes' => $minuts, 'seconds' => $seconds);

}

function _random_key($length = "30") {

	$key = array();
	$keys = array_merge(range(0, 9), range('a', 'z'));

	for ($i = 0; $i < $length; $i++) {
		$key[$i] = $keys[array_rand($keys)];
	}

	$key = implode("", $key).time();
	return strtoupper($key);

}

## Get real IP address
function _get_IP(){
	
	if( !empty($_SERVER['HTTP_CLIENT_IP']) ){ //check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}else if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ){ //to check ip is pass from proxy    
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;

}

function _months(){
    return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
}

function _monthsValue(){
    $key = $value = _months();
    return array_combine($key, $value);
}

function _quarter(){
    return ['1'=>'Quater 1','2'=>'Quater 2','3'=>'Quater 3','4'=>'Quater 4'];
}

function _years(){
    return range(date('Y',strtotime('+1 year')), date('Y')-2, -1);
}

function _yearsValue(){
    $key = $value = _years();
    return array_combine($key, $value);
}
function weeks($year, $_week=null, $_date=null){
    
    $weeks = [];
    $flag = true;
	$week = $i = 0;
	$current_date = date('Y-m-d');
	$current_date_str = strtotime($current_date);
    $year_start_day = date('Y-m-d', strtotime("first day of jan $year"));
    $year_last_day = date('Y-m-d', strtotime("last day of dec $year"));
    $week_end_day = date('Y-m-d', strtotime("first fri of jan $year"));
    $start_date = $year_start_day;
    while( $flag ){

        $_start = $start_date;
        if( $start_date == $week_end_day ){
            $_end = $_start;
        }else{
            $_end = date('Y-m-d', strtotime("$start_date next fri"));
        }

        if( $_end == $year_last_day ){
            $_end = $year_last_day;
            $flag = false;
        }
        else if( date('Y', strtotime($_end)) != $year ){
            $_end = $year_last_day;
            $flag = false;
        }

        $start_date = date('Y-m-d', strtotime("$_end +1 day"));

		$week++;
        $weeks[$i] = [
			'id' => $week,
			'name' => ('W'.$week.':- ('.date('M-d', strtotime($_start)).' to '.date('M-d', strtotime($_end)).')'),
            'start' => $_start,
            'end' => $_end,
			'end_date' => $_end,
			'year' => $year,
			'current' => false,
		];
		
		if( $current_date_str >= strtotime($_start) && $current_date_str <= strtotime($_end) ){
			$weeks[$i]['current'] = true;
			break;
		}
		$i++;

	}
	
	## get week date
	if( $_week != null ){
		foreach( $weeks as $w ){
			if( $w['id'] == $_week ){
				$weeks = $w;
			}
		}
	}

	## get date date
	if( $_date != null ){
		$_date_str = strtotime($_date);
		foreach( $weeks as $w ){
			if( $_date_str >= strtotime($w['start']) && $_date_str <= strtotime($w['end']) ){
				$weeks = $w;
			}
		}
	}

    return $weeks;

}


function genSelectOptions($options = array(), $selected = ''){
    
    $selOptions = "";
    if(count($options)){
        foreach($options as $k=>$option){
            if($selected == $k){
                $selOptions .= "<option selected='selected' value='".$k."'>".$option."</option>";
            }
            else{
                $selOptions .= "<option value='".$k."'>".$option."</option>";				
            }
        }
    }

    return $selOptions;
}

function genSelectOptionsArray($datas = array(), $options = []){
    
    $selOptions = [];
    if(count($datas)){
        foreach($datas as $k => $row){
            $key = $options['key'];
            $value = $options['value'];
            $selOptions[$row->$key] = $row->$value;
        }
    }

    return $selOptions;
}

function searchArea($options=[]){
    
    return '
        <div class="input-group  group">
            <input id="search" class="form-control" placeholder="'.(isset($options['placeholder'])?$options['placeholder']:'Search').'" type="text" title="'.(isset($options['title'])?$options['title']:'').'">
            <div class="input-group-btn">
                <button type="button" id="btnSearch" class="search-new-btn btn"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </div>
    ';

}

function isLogin($flag=false){

    $CI =& get_instance();
    $actionClass = strtolower($CI->router->fetch_class());
	$action = strtolower($CI->router->fetch_method());
	$has_userdata = $CI->session->has_userdata('si_userdata') ? $CI->session->userdata['si_userdata']['i_admin_user_id'] : 0;
	if( $has_userdata ){
        return true;
    }else if( isset($_COOKIE['erd_rmc']) && !empty($_COOKIE['erd_rmc']) ){
        $getCookie = json_decode(stripslashes($_COOKIE['erd_rmc']), true);
		$CI->load->model('admins/admins_mdl');
        $chechk_login = $CI->admins_mdl->select($getCookie);
        if( $chechk_login ){
            if( $chechk_login['ti_status'] ){
                setSessionData($chechk_login);
                return true;
            }
        }	
    }else if( !$flag ){
        if( ($actionClass == 'login' && in_array($action, ['index','forgot_password','reset_password','capcha'])) ){
            if( $action == 'index' ){
                setCookieEnable(); //set cookie, it will remove from this controller method "login"
            }
            return true;
        }else if( in_array($actionClass, ['m3', 'generalcron']) ){
            return true;
        }
    }

    return false;

    // ## Check remember me cookie available or not
	// if( isset($_COOKIE['ehbus_rmc']) && !empty($_COOKIE['ehbus_rmc']) ){
	// 	$getCookie = json_decode(stripslashes($_COOKIE['ehbus_rmc']), true);
	// 	$CI->load->model('user_db');
	// 	$chechk_login = $CI->user_db->select($getCookie);
    //     if($chechk_login)
    //     {
    //         if($chechk_login['loginStatus'] == "Active"){
    //             setSessionData($chechk_login);
    //             return true;
    //         }else{
    //             return false;
    //         }
    //     }else{
    //         return false;
    //     }	
	// }else{
	// 	return false;
	// }
}

function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function setSessionData($userData){
	
	$CI =& get_instance();

	_addLogInWeb([
		'v_module'    => 'admin',
		'v_type'      => 'login',
		'v_msg'       => 'Admin Login',
		'i_admin_user_id' => $userData['i_admin_user_id'],
		'i_record_id' => $userData['i_admin_user_id'],
		'v_old_data'  => json_encode([]),
		'v_new_data'  => json_encode([]),
	]);
	
	## Set Login Logs in Log Table
	//$newdata['userLogId'] = setLoginLogs($loginLogArr); 
	
	$data['si_userdata'] = $userData;
	$CI->session->set_userdata($data);

}

function setLoginLogs($loginLogArr){

	$CI =& get_instance();
	$CI->load->model('DbUserLog');
	$userLogId = $CI->DbUserLog->loginLog($loginLogArr);
	return $userLogId;
}

function linkVersion(){
	return "?ver=1.51";
}

function setCookieEnable(){
	// just stop work
	return true;
	setcookie('erd_cEnable', '12319283918sd31oihkjads8971', time() + 60 * 60 * 24 * 30, '/');
}

function isCookieEneble(){
	// just stop work
	return true;
	if( isset( $_COOKIE['erd_cEnable'] ) ){
		setcookie('erd_cEnable', null, -1, '/');
		return true;
	}
	return false;

}

function ganerateSerialNo($options){
	$series  = 1000;
	if(isset($options['series'])){
		$series  = $options['series'];
	}
	$plusvalue = $series + $options['serialId'];
	return $options['intial'] . $plusvalue;
}


function _flashMessage($options=[]){
	
	## $options = ['status' => 'error', 'message' => 'Something went wrong!']);
	## $options = ['status' => 'success', 'message' => 'Everything is ok.']);

	$CI =& get_instance();

	if( isset($options['html']) && $options['html'] == true ){
		if( $options['status'] == 'success' ){
			$options['message'] = '<div class="alert alert-success alert-dismissible fade in"><strong>Success!</strong> '.$options['message'].'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
		}else if( $options['status'] == 'error' ){
			$options['message'] = '<div class="alert alert-danger alert-dismissible fade in"><strong>Danger!</strong> '.$options['message'].'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
		}
		$CI->session->set_flashdata('html_flash_message', json_encode($options));
	}else{
		$CI->session->set_flashdata('flash_message', json_encode($options));
	}

}

function _escapeExtraChars($string=""){

	return preg_replace("/'/", "\&#39;", $string);

}

function dayCount($from, $to) {
	$date1 = new DateTime($from);
	$date2 = new DateTime($to);
	return $diff = $date2->diff($date1)->format("%a");
}

function _getSetting($key, $value=true){

	$CI =& get_instance();
	$select = "*";
	if($value == true){
		$select = "value";
	}
	$CI->db->select($select);
	$CI->db->where(array("key" => strtoupper($key), "status" => 1));
	$result = $CI->db->get("setting")->row();
	if($value == true){
		return $result->value;
	}else{
		return $result;
	}
	
}


function _getSettingKeyValue($keys=[]){

	$CI =& get_instance();
	
	$CI->db->select("key,value");
	$CI->db->where(array("status" => 1));
	if( !empty($keys) ){
		$CI->db->where_in('key', $keys);
	}
	$result = $CI->db->get("setting")->result();
	$return_array = array();
	foreach ($result as $value){
		$return_array[$value->key] = $value->value;
	}
	return $return_array;
}

function ganerateSerialNumber($options){

	$plusvalue = 1;
	$options['serialId'] = (int)$options['serialId'];
	if($options['serialId'] != 0){
		$plusvalue = $options['serialId'] + 1;
	}

	return $options['intial'].$plusvalue."-".$options['i_user_id']."-".$options['date'];  
	
}

## add web log 
function _addLogInWeb($logArr){

	## add user log
	/*
	_addLogInWeb([
		'v_module'    => 'profile',
		'v_type'      => 'view',
		'v_msg'       => 'User profile view',
		'v_fieldName' => 'field name'
		'i_record_id' => $data['i_user_id'],
		'v_old_data'  => json_encode([]),
		'v_new_data'  => json_encode([]),
	]);
	*/
	
    ## create instance & load modal
    $CI =& get_instance();
    $CI->load->model('admins_log/admins_log_mdl');

    ## set Static data 
    if($CI->session->has_userdata('i_admin_user_id')){
        $logArr['i_admin_user_id'] = $CI->session->userdata('i_admin_user_id');
    }
    $logArr['v_ip'] = _get_IP();
	$logArr['dt_created'] = date('Y-m-d H:i:s');
    $logArr['v_device'] = 'web';

    ## insert log
    $CI->admins_log_mdl->insert($logArr);

}

## add user log from app
function _addLogInApp($logArr){

	## add user log
	/*
	_addLogInApp([
		'v_module'    => 'profile',
		'v_type'      => 'view',
		'v_msg'       => 'User profile view',
		'i_record_id' => $data['i_user_id'],
		'i_user_id'   => $data['i_user_id'],
		'v_old_data'  => json_encode([]),
		'v_new_data'  => json_encode([]),
		'v_device'    => (_isset_not_empty($data['v_device']) ? $data['v_device'] : '')
	]);
	*/
	
    ## create instance & load modal
    $CI =& get_instance();
    $CI->load->model('api_user_log/api_user_log_mdl');

	## set Static data 
	$logArr['v_ip'] = _get_IP();
	$logArr['dt_created'] = date('Y-m-d H:i:s');
    
    ## insert log
    $CI->api_user_log_mdl->insert($logArr);
	
}

## check isset and not empty

function assets($path){
	
	return base_url('assets/'.$path);

}

function search_session_is_reset($sessionId){

	$CI =& get_instance();
	if( $CI->session->has_userdata($sessionId) ){

		$search_data = $CI->session->userdata($sessionId);
		if(isset($search_data['reset']) && $search_data['reset']=='1'){
			return '1';
		}
		else{
			return '0';
		}

	}
	else {
		return '1';
	}

}

function search_session_data($sessionId){

	$posts = array();
	$CI =& get_instance();
	if( $CI->session->has_userdata($sessionId) ){

		$posts = $CI->session->userdata($sessionId);
		if(isset($posts['reset'])){
			unset($posts['reset']);
		}
	
	}

	return $posts;

}

function upload_file_exists($filename){
	
	$file = ASSETS_DIR.$filename;
	if( !empty($filename) && file_exists($file) ){
		return true;
	}
	return false;

}

function upload_mkdir($filename){

	$file = ASSETS_DIR.$filename;
	if( !empty($filename) && !file_exists($file) ){
		mkdir($file, 0777);
		return true;
	}
	return false;

}

function upload_file_unlink($filename){

	$file = ASSETS_DIR.$filename;
	if( !empty($filename) && file_exists($file) ){
		unlink($file);
	}
	return false;

}

function upload_file_move($filename, $save_to){

	$file = ASSETS_DIR.$filename;
	$save = ASSETS_DIR.$save_to;
	if( !empty($filename) && file_exists($file) ){
		rename($file, $save);
	}

}

function _user_profile_completion($options=[]){

	$profile = ['percentage' => 100, 'incomplete' => [], 'can_eligible' => true];
	$CI =& get_instance();
	$CI->db->select("
		u.i_user_id,
		CONCAT(u.v_first_name,' ',u.v_last_name) AS v_full_name,
		u.v_email,
		u.bi_graduation,
		u.i_p_skill_id,
		u.v_profile_image,
		u.t_about_me,
		u.bi_dob,
		(SELECT COUNT(*) FROM erd_users_skills AS us WHERE us.i_user_id = u.i_user_id) AS skills_count
	");
	$CI->db->where(['i_user_id' => $options['i_user_id']]);
	$user = $CI->db->get("erd_user AS u")->row_array();
	if( !empty($user) ){
		if( empty($user['v_full_name']) ){ $profile['percentage'] -= 15; $profile['incomplete'][] = "Full Name"; }
		if( empty($user['v_email']) ){ $profile['percentage'] -= 15; $profile['incomplete'][] = "Email Address"; }
		if( empty($user['i_p_skill_id']) ){ $profile['percentage'] -= 15; $profile['incomplete'][] = "Skill"; }
		if( empty($user['skills_count']) ){ $profile['percentage'] -= 15; $profile['incomplete'][] = "Skills"; }

		if( empty($user['bi_graduation']) ){ $profile['percentage'] -= 10; $profile['incomplete'][] = "Graduation Date"; }
		if( empty($user['v_profile_image']) ){ $profile['percentage'] -= 10; $profile['incomplete'][] = "Profile Image"; }
		if( empty($user['t_about_me']) ){ $profile['percentage'] -= 10; $profile['incomplete'][] = "About Me"; }
		if( empty($user['bi_dob']) ){ $profile['percentage'] -= 10; $profile['incomplete'][] = "Date of Birth"; }
	}
	if( $profile['percentage'] < 60 ) $profile['can_eligible'] = false;

	if( $options['return_data'] ){
		return $profile;
	}else if( !$profile['can_eligible'] ){
		$CI->api_handler->api_response("500", "Sorry, You need to complete minimum 60% of your profile details.", array(), $profile);
	}
	return $profile;
	
}

function push_notification($data) {

	ob_start();
	// error_reporting(-1);
	// ini_set('display_errors', 'On');

	$CI =& get_instance();
	$CI->load->library('firebase');
	$CI->load->library('push');
	
	$firebase = new Firebase();
	$push = new Push();

	// optional payload
	$payload = array(
		"project" => "Erdos"
	);
	// notification title
	$title = $data['title']; 
	$message = $data['message']; 

	$push->setTitle($title);
	$push->setMessage($message);
	$push->setIsBackground(FALSE);
	$push->setPayload($payload);
	$json = '';
	$response = '';

	$json = $push->getPush();
	$regId = $data['register_id'];
	
	$response = $firebase->send($regId, $json, $data);
	return $response;
	
}

function send_notification($data, $push=false){

	if($push == true){
		## send push notification	
		$response = push_notification($data);
		$noti_response = json_decode($response);
		if($noti_response->success == 1){
			$data['ti_firebase_status'] = 1;
		}else if($noti_response->success == 0){
			$data['ti_firebase_status'] = 2;
		}
	}else{
		$data['ti_firebase_status'] = 3;
	}
	unset($data['register_id'], $data['title'], $data['message']);
	$data['dt_created'] = date('Y-m-d H:i:s');

    ## create instance & load modal
    $CI =& get_instance();
    $CI->load->model('notification/User_notification_mdl');
	
    ## insert Notification
    $CI->User_notification_mdl->insert($data);

}


function compress_image($path,$file_name){

	$CI =& get_instance();
	$CI->load->library('image_lib');  
			
	if(!is_dir(USER_PROFILE_IMAGE_COM_DIR)){ mkdir(USER_PROFILE_IMAGE_COM_DIR,0777,TRUE); }
	
	$config['image_library'] = 'gd2';
	$config['source_image'] = $path.$file_name;
	$config['create_thumb'] = FALSE;  
	$config['maintain_ratio'] = TRUE;  
	$config['height'] = 140;  
	$config['quality'] = '90%';  
	$config['new_image'] = USER_PROFILE_IMAGE_COM_DIR.$file_name;  
	
	$CI->image_lib->initialize($config);
	$CI->image_lib->resize();
	return true;
	
}

function _firebase_token($data){

	$CI =& get_instance();
	$CI->load->model('api_user/Api_user_mdl');
	$firebase_token = "";

	$proposal_noti_type = array("2", "3");
	$apply_proposal_noti_type = array("4");
	$withdraw_noti_type = array("5");

	if (in_array($data['noti_type'], $proposal_noti_type)){
		
		$firebase_token = $CI->Api_user_mdl->get_firebase_token(["i_proposal_id"=>$data['i_proposal_id']]);

	} else if(in_array($data['noti_type'], $apply_proposal_noti_type)) {

		$firebase_token = $CI->Api_user_mdl->_get_user_name(["i_user_id"=>$data['i_user_id']]);

	} else if(in_array($data['noti_type'], $withdraw_noti_type)){

		$firebase_token = $CI->Api_user_mdl->get_token(["i_proposal_id"=>$data['i_proposal_id']]);
	}

	return $firebase_token;
}

function _get_project_name($data){

	$CI =& get_instance();
	$CI->load->model('api_project/Api_project_mdl');
	$project = $CI->Api_project_mdl->_get_project_name(["i_project_id"=>$data['i_project_id']]);
	return $project;
}

function _get_name($data){

	$CI =& get_instance();
	$CI->load->model('api_user/Api_user_mdl');
	$userData = $CI->Api_user_mdl->_get_user_name(["i_user_id"=>$data['i_user_id']]);
	return $userData;
}

function _has_filter_search($data=[]){

	if( isset($data['search_keyword']) && !empty($data['search_keyword']) ){
		return true;
	}else if( isset($data['i_s_skill_id']) && !empty($data['i_s_skill_id']) ){
		return true;
	}else if( isset($data['i_payment_type_id']) && !empty($data['i_payment_type_id']) ){
		return true;
	}else if( isset($data['v_duration_in']) && !empty($data['v_duration_in']) ){
		return true;
	}else if( isset($data['i_p_skill_id']) && !empty($data['i_p_skill_id']) ){
		return true;
	}else if( isset($data['f_my_rating']) && !empty($data['f_my_rating']) ){
		return true;
	}	
	return false;

}

function _get_notification_count($data){

	$CI =& get_instance();
	$CI->load->model('api_notification/Api_notification_mdl');
	return $CI->Api_notification_mdl->notification_count(["i_user_id" => $data['i_user_id'], "ti_read_status" => 0]);

}