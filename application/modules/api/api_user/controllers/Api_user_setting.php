<?php 

Class Api_user_setting extends MX_Controller{

	public function __construct() {
		$this->load->model('api_user_mdl');
	}

    ## get settings
	public function settings(){

		try {

			## validate
			$data = $this->validation_settings();
			
            $data = $this->api_user_mdl->select_settings(['i_user_id' => $data['i_user_id']]);
            $this->api_handler->api_response("200", "success", array(), $data);
			
		}catch (Exception $e){
			$this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
		}

	}

	private function validation_settings(){
		
		$config = array(
			array(
				'field' => 'i_user_id',
				'label' => 'user id',
				'rules' => 'required|integer'
			),
		);
		return $this->api_handler->api_validation($config,"post");
    }
    
    ## update settings
    public function update_settings(){
        
        try {

            ## validate
            $data = $this->validation_update_settings();
            
            $set = [
                'ti_push_notification' => $data['ti_push_notification'],
                'ti_email_notification' => $data['ti_email_notification'],
                'ti_online_status' => $data['ti_online_status'],
                'dt_updated' => date('Y-m-d H:i:s')
            ];
            $where = ['i_user_id' => $data['i_user_id']];
            $data = $this->api_user_mdl->update($set, $where);
            $this->api_handler->api_response("200", "settings_update", array(), array());
            
        }catch (Exception $e){
            $this->api_handler->api_response($e->getCode(), $e->getMessage(), "", "");
        }

    }

    private function validation_update_settings(){
        
        $config = array(
            array(
                'field' => 'i_user_id',
                'label' => 'user id',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'ti_push_notification',
                'label' => 'push notification',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'ti_email_notification',
                'label' => 'email notification',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'ti_online_status',
                'label' => 'online status',
                'rules' => 'required|integer'
            ),
        );
        return $this->api_handler->api_validation($config,"post");

    }

}

?>