<?php

## General project options
function _projectOptions($key){

	$options = [
		'project_name' => 'Investor',
		'title' => 'Investor',
		'footer_copyright' => date('Y').' &copy;Investor.com'
	];

	return $options[$key];

}

function _getProjectDuration(){
	
	$options = [
		[
			'duration_id' => 1,
			'duration_key' => 'hours',
			'duration_name' => 'Hours',
			'duration_filter_name' => '160 Hours',
			'duration_placeholder' => 'No. of Hours',
		],
		[
			'duration_id' => 2,
			'duration_key' => 'weeks',
			'duration_name' => 'Weeks',
			'duration_filter_name' => '4 Weeks',
			'duration_placeholder' => 'No. of Weeks',
		],
		[
			'duration_id' => 3,
			'duration_key' => 'months',
			'duration_name' => 'Months',
			'duration_filter_name' => '1 Month',
			'duration_placeholder' => 'No. of Months',
		],
		[
			'duration_id' => 4,
			'duration_key' => 'years',
			'duration_name' => 'Years',
			'duration_filter_name' => '1 Year',
			'duration_placeholder' => 'No. of Years',
		]
	];

	return $options;
}

function _project_status( $ti_project_status = null ){

	$options = [
		'1' => 'Active',
		'2' => 'Hide',
		'3' => 'Complete',
		'4' => 'Delete'
	];

	if( !empty($ti_project_status) ) return $options[$ti_project_status];
	return $options;

}

function _notification_type( $ti_type = null ){
	
	$options = [
		'1' => 'profile_matches',
		'2' => 'unread_chat',
		'3' => 'proposal',
	];

	if( !empty($ti_type) ) return $options[$ti_type];
	return $options;

}

function _get_notification_types($data=[]){
	
	$options = [
		[
			'noti_id' => 1,
			'noti_type' => 'profile_match',
		],
		[
			'noti_id' => 2,
			'noti_type' => 'accept_application',
		],
		[
			'noti_id' => 3,
			'noti_type' => 'reject_application',
		],
		[
			'noti_id' => 4,
			'noti_type' => 'apply_project',
		],
		[
			'noti_id' => 5,
			'noti_type' => 'withdraw_application',
		]
	];

	return $options;
}


?>