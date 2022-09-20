<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_lib
{
    public $CI;
	public $email_from;
	public $email_pass;
	public $email_from_name;
	public $email_to;
	public $email_cc;
	public $email_bcc;
	public $email_reply_to;
	public $email_subject;
	public $protocol;
	public $host;
	public $port;
	
    function __construct(){

		## smtp settings
		$smtp_settings = json_decode(_getSetting("SMTP", true));

		## Make instance
        $this->CI = &get_instance();
        $this->CI->load->library('parser');
		
		## Set default values
		// $this->email_from = $smtp_settings->EMAIL_FROM;
		// $this->email_pass = $smtp_settings->PASSWORD;
		// $this->email_from_name = $smtp_settings->EMAIL_NAME;
		// $this->email_to = $smtp_settings->EMAIL_TO;
		// $this->email_cc = $smtp_settings->EMAIL_CC;
		// $this->email_bcc = $smtp_settings->EMAIL_BCC;
		// $this->email_reply_to = $smtp_settings->EMAIL_REPLY_TO;
		// $this->email_subject = $smtp_settings->EMAIL_SUBJECT;
		// $this->protocol = $smtp_settings->PROTOCOL;
		// $this->host = $smtp_settings->HOST;
		// $this->port = $smtp_settings->PORT;


    }
	
    function sendEmail($data){
		
         if(!empty($data)){
			
			## Set config options

            $config = Array(
				'protocol'  => 'smtp',
			    'smtp_host' => 'email-smtp.us-west-1.amazonaws.com',
				'smtp_crypto' => 'tls',
			    'smtp_port' => 587,
			    'smtp_user' => 'AKIAUWKH5ASI2XZ7P2RL',
			    'smtp_pass' => 'BEjv0KqBlErIE/4DJZx/YXRN2ASwg0DcKgpK/VvrzDa3',
			    'mailtype'  => 'html',
			    'starttls'  => true,
			    'newline'   => "\r\n",
		        'charset' => 'utf-8',
		        'wordwrap' => TRUE,
				'validation' => true,
            );

            ## Set to email
            if( isset($data['sendto']) && !empty($data['sendto']) ){}
            else{
                $data['sendto'] = $this->email_to;
            }
			
			## Set subject
			if( isset($data['subject']) && !empty($data['subject']) ){}
			else{
				$data['subject'] = $this->email_subject;
			}
			
			## Set cc
			if( isset($data['cc']) && !empty($data['cc'])){
				$data['cc'] = $this->email_cc.','.$data['cc'];
			}else{
				$data['cc'] = $this->email_cc;
			}
            
            ## Set reply
            if( isset($data['reply_to']) && !empty($data['reply_to']) ){
                $this->email_reply_to = $this->email_reply_to.','.$data['reply_to'];
            }

			## Load library
            $this->CI->load->library('email', $config);
			
			## Set options
            $this->CI->email->clear(true);
            $this->CI->email->set_crlf( "\r\n" );
            $this->CI->email->set_newline("\r\n");
            $this->CI->email->from('info@gooneelive.com');
            $this->CI->email->to($data['sendto']); // change it to yours
			// $this->CI->email->cc($data['cc']); // change it to yours
			// $this->CI->email->reply_to($this->email_reply_to); // change it to yours
            $this->CI->email->subject($data['subject']); // change it to yours
            $this->CI->email->message($data['comment']);

			## Add attachment
            if(!empty($data['attachment_path'])){
            	foreach ($data['attachment_path'] as $row) {
                	$this->CI->email->attach($row,'attachment');
            	}
            }
			
			## Send email
            if($this->CI->email->send()) {
				return true;
            } else {
				_P($this->CI->email->print_debugger());exit;
				var_dump('hello by');exit;
                return false;
            }
			
        }
        else{
            return false;
        } 
		
    }
}
