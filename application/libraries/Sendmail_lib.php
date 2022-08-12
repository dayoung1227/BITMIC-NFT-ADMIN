<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Common Function Library
class Sendmail_lib{
	var $CI;

	public function __construct(){
		$this->CI =&get_instance();

		$email_config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'alencer@gmail.com', // change it to yours
			'smtp_pass' => 'rkdmfdl0909', // change it to yours
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'wordwrap' => TRUE
		);
		
		$this->$CI->load->library('email', $email_config);
	
	}

	public function send_mail_register_confirm($to, $verify_str){

		$dat		= array();
		$dat["result"] = 1;
		$verificationText = "aaaaaaaaaaaaa";
		$dat["verificationText"] = $verify_str;
		
		$CI->load->library('email', $email_config);
		$CI->email->set_newline("\r\n");
		$CI->email->from('alencer@gmail.com', "Admin Team");
		$CI->email->to($to);  
		$CI->email->subject("Email Verification");

		$body = $CI->load->view('_email/v_register_confirm',$dat,TRUE);
		$CI->email->message($body);

		if($CI->email->send()) {
			return true;
		} else {
			return false;
		}


	}
	
	
}