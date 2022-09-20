<?php

class Auth{

    function __construct(){

    }

    function authLogin(){
        
        $CI =& get_instance();        
        ## check moduler is api then skip
        if( strpos($CI->router->fetch_module(), 'api_') === false ) {
            $isAjax = isAjax();

            ## check user login
            if( !isLogin() ){
                $this->validateUserLogin($isAjax);
            }
        }
        
    }

    function validateUserLogin($isAjax){

        ## check request is ajax
        if( $isAjax ){
            $result = [
                'status' => 'error',
                'message' => 'Your session is expired, please refresh your page!'
            ];
            $CI =& get_instance();
            $postData = $CI->input->post();
            if( empty($postData) ){
                $result = [
                    'status' => 'error',
                    'message' => 'Empty request please try again!'
                ];
            }
            echo json_encode($result); exit;
        }
        ## request is not ajax
        else{
            redirect(base_url('log-in'));
        }

    }

}

?>