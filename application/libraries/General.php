<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * iPromot General Class
 * This class used as auto_load libraries
 **/
class General {

	protected $ci;
	protected $per_page = "3";

	function __construct() {

		$this->ci =& get_instance();
		$this->ci->load->helper(array('general'));

		if ($this->ci->config->item("per_page_record") !== "") {
			$this->per_page = $this->ci->config->item("per_page_record");
		}

	}

	public function ActionLog_Insert($ActionOnModule = "", $ActionByUserGroup = "", $ActionOnRecord = "", $ActionType = "", $ActionMsg = "", $ActionByUser = "", $OldData = "", $NewData = "") {

		//"INSERT INTO `ipm_admin_users_log` (`pLogID`, `iAdmUser`, `dActionTime`, `vActionType`, `vActionByThisUser`, `vIP`, `vOldData`, `vNewData`) VALUES (NULL, '1', '2016-06-23 00:00:00', 'feef', '1', 'fef', 'fewfew', 'weew')";

		try {

			if ($ActionOnRecord == "" || $ActionType == "" || $ActionByUser == "") {
				throw new Exception("Required argument missing. In this General Method AdminUser_ActionLog_Insert()");
			}

			$insert_array['dActionTime'] = "NOW()";
			$insert_array['vActionOnModule'] = $ActionOnModule;
			$insert_array['vActionType'] = $ActionType;
			$insert_array['vActionMsg'] = $ActionMsg;
			$insert_array['vActionOnRecord'] = $ActionOnRecord;
			$insert_array['vActionByUser'] = $ActionByUser;
			$insert_array['vActionByUserGroup'] = $ActionByUserGroup;
			$insert_array['vIP'] = $this->ci->input->ip_address();
			$insert_array['vOldData'] = ($OldData != '') ? $OldData : '';
			$insert_array['vNewData'] = ($NewData != '') ? $NewData : '';


			$query = "INSERT INTO `ipm_admin_users_log` (`dActionTime`, `vActionOnModule`, `vActionType`, `vActionMsg`, `vActionOnRecord`, `vActionByUser`, `vActionByUserGroup`, `vIP`, `vOldData`, `vNewData`) VALUES (" . $insert_array['dActionTime'] . ", '" . $insert_array['vActionOnModule'] . "', '" . $insert_array['vActionType'] . "', '" . $insert_array['vActionMsg'] . "', '" . $insert_array['vActionOnRecord'] . "', '" . $insert_array['vActionByUser'] . "', '" . $insert_array['vActionByUserGroup'] . "', '" . $insert_array['vIP'] . "', '" . $insert_array['vOldData'] . "','" . $insert_array['vNewData'] . "')";
			$this->ci->db->query($query);
			//$this->ci->db->insert('ipm_admin_users_log', $insert_array);
			$log_data = array();
			$log_data['row_id'] = $this->ci->db->insert_id();

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			//return array("status" => "0", "msg" => $e->getMessage(), "data" => array());
		}

		return array("status" => "1", "msg" => "Token created", "data" => $log_data);

	}

	public function ActionLog_Get($RecordId = "", $ModuleName = "", $UserGroup = "", $Page = "") {

		try {

			if ($RecordId == "") {
				throw new Exception("Required argument missing. In this General Method AdminUser_ActionLog_Get()");
			}

			//=== Collect data for Action Performed by this $userID ===//
			/*
			$log_query = "
			SELECT 
			pLogID AS log_id,
			dActionTime AS action_time,
			vActionOnModule AS action_on_module, 
			vActionType AS action_type, 
			vActionMsg AS action_msg, 
			vActionOnRecord AS action_on_record, 
			CONCAT(u.vFname,' ',u.vLname) AS action_by_user_name, 
			vActionByUser AS action_by_user, 
			vActionByUserGroup AS user_group,
			vIP AS action_ip, 
			vOldData AS before_action_data, 
			vNewData AS after_action_data 
			FROM `ipm_admin_users_log` LEFT JOIN `ipm_admin_users` AS u ON u.pUid = vActionByUser WHERE vActionByUser = '".$ModuleName."' AND vActionByUserGroup = '".$UserGroup."' ORDER BY `pLogID` DESC";
			*/


			//=== Collect data for Action Performed on this $RecordId recorder id ===//
			$log_query = "
			SELECT 
			pLogID AS log_id,
			dActionTime AS action_time,
			vActionOnModule AS action_on_module, 
			vActionType AS action_type, 
			vActionMsg AS action_msg, 
			vActionOnRecord AS action_on_record, 
			CONCAT(u.vFname,' ',u.vLname) AS action_by_user_name, 
			vActionByUser AS action_by_user, 
			vIP AS action_ip, 
			vOldData AS before_action_data, 
			vNewData AS after_action_data 
			FROM `ipm_admin_users_log` LEFT JOIN `ipm_admin_users` AS u ON u.pUid = vActionByUser WHERE vActionOnModule = '" . $ModuleName . "' AND vActionOnRecord = '" . $RecordId . "' ORDER BY `pLogID` DESC";

			if (isset($Page) && $Page != "") {
				$offset = ($Page - 1) * $this->per_page;
				$log_query .= ' LIMIT ' . $offset . "," . $this->per_page;
			} else {
				$log_query .= ' LIMIT 0,' . $this->per_page;
			}


			$total_result = explode("LIMIT", $log_query);
			$total_result = $this->ci->db->query($total_result[0]);
			$total_result = $total_result->num_rows();
			$k[] = $this->ci->db->last_query();

			$log_query = $this->ci->db->query($log_query);
			$log_data = $log_query->result_array();
			$this->ci->db->last_query();

			$count = count($log_data);
			if ($count > 0) {
				$return['data'] = $log_data;
				$return['total_show'] = $count;
				$return['total_rec'] = $total_result;
				$return['current_page'] = (isset($Page) && $Page != "") ? $Page : "1";
				$return['pagination'] = Ipm_Pagination($total_result, $this->per_page, $return['current_page'], "5", "");
			} else {
				$return['total_show'] = "0";
			}


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			//return array("status" => "0", "msg" => $e->getMessage(), "data" => array());
		}

		if (!empty($log_data)) {
			return array("status" => "1", "msg" => "History fetched successfully", "data" => $return);
		} else {
			return array("status" => "0", "msg" => "No History found", "data" => "");
		}

	}

	public function SuperAdmin_AccessLevel_Assign($UserId = "", $UserGroupId = "") {

		$result = SuperAdmin_AccessLevel();
		$return = array();

		foreach ($result as $acl_key => $acl) {

			$insert_data['iUserId'] = $UserId;
			$insert_data['iUserGroup'] = $UserGroupId;
			$insert_data = array_merge($acl, $insert_data);

			$this->ci->db->set('dCreatedOn', 'NOW()', FALSE);
			$this->ci->db->insert('ipm_user_access_rights', $insert_data);
			array_push($return, array($this->ci->db->insert_id(), $this->ci->db->last_query()));
			unset($insert_data);

		}

		return $return;

	}

	public function AccessLevel_Assign($ModuleName = "", $UserId = "", $UserGroupId = "", $AccessLevel = array()) {

		$insert_data = ACL_ArrayHelper($AccessLevel);

		$insert_data['iUserId'] = $UserId;
		$insert_data['iUserGroup'] = $UserGroupId;
		$insert_data['vModuleName'] = $ModuleName;

		$this->ci->db->set('dCreatedOn', 'NOW()', FALSE);
		$this->ci->db->insert('ipm_user_access_rights', $insert_data);
		return $this->ci->db->insert_id();

	}

	public function AccessLevel_Get($UserId = "", $UserGroupId = "") {

		if ($UserId == "" || $UserGroupId == "") {

			throw new Exception("User id and group id is required to fetch user accesslevel");

		}

		$acl_query = "SELECT pAccessRightID AS acl_id, iUserId AS user_id, iUserGroup AS user_group_id, vModuleName AS module_name, eCanAdd AS `add`, eCanView AS `view`, eCanEdit AS `edit`, eCanDelete AS `delete`, eCanApprove AS approve, eCouponSubmit_Store as coupon_submit_to_store,dCreatedOn AS created_date  FROM `ipm_user_access_rights` WHERE iUserId = '" . $UserId . "' AND iUserGroup = '" . $UserGroupId . "' ORDER BY dCreatedOn DESC";
		$query = $this->ci->db->query($acl_query);
		$acl_result = $query->result_array();

		$acl_structure = array();

		if (!empty($acl_result)) {

			foreach ($acl_result as $akey => $ac_item) {


				$this_loop['acl_id'] = $ac_item['acl_id'];
				$this_loop['user_id'] = $ac_item['user_id'];
				$this_loop['user_group_id'] = $ac_item['user_group_id'];
				$this_loop['module_name'] = $ac_item['module_name'];
				$this_loop['have_rights'] = "";
				$this_loop['dosent_have_rights'] = "";

				if ($ac_item['add'] == "yes") {
					$this_loop['have_rights'][] = "add";
				} else {
					$this_loop['dosent_have_rights'][] = "add";
				}

				if ($ac_item['view'] == "yes") {
					$this_loop['have_rights'][] = "view";
				} else {
					$this_loop['dosent_have_rights'][] = "view";
				}

				if ($ac_item['edit'] == "yes") {
					$this_loop['have_rights'][] = "edit";
				} else {
					$this_loop['dosent_have_rights'][] = "edit";
				}

				if ($ac_item['delete'] == "yes") {
					$this_loop['have_rights'][] = "delete";
				} else {
					$this_loop['dosent_have_rights'][] = "delete";
				}

				if ($ac_item['approve'] == "yes") {
					$this_loop['have_rights'][] = "approve";
				} else {
					$this_loop['dosent_have_rights'][] = "approve";
				}

				if ($ac_item['coupon_submit_to_store'] == "yes") {
					$this_loop['have_rights'][] = "coupon_submit_to_store";
				} else {
					$this_loop['dosent_have_rights'][] = "coupon_submit_to_store";
				}

				array_push($acl_structure, $this_loop);
				unset($this_loop);

			}

		}

		return $acl_structure;

	}

	public function AccessLevel_Update($ModuleName = "", $UserId = "", $UserGroupId = "", $AccessLevel = array(), $AclPrimaryKey = "") {

		$update_data = ACL_ArrayHelper($AccessLevel);

		if ($AclPrimaryKey == "") {
			$this->ci->db->where('iUserId', $UserId);
			$this->ci->db->where('iUserGroup', $UserGroupId);
			$this->ci->db->where('vModuleName', $ModuleName);
		} else {
			$this->ci->db->where('iUserId', $AclPrimaryKey);
		}

		$this->ci->db->update('ipm_user_access_rights', $update_data);

		$return["affected_rows"] = $this->ci->db->affected_rows();
		$return["last_query"] = $this->ci->db->last_query();

		return $return;

	}

}
