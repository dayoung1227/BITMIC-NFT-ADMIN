<?php
include_once APPPATH.'core/MY_PageController.php';

class MY_Controller extends CI_Controller {
	protected static $instance;
	
	public $data = array();
	public $global_navigate = array();
	
	function __construct() {
		parent::__construct();
		$this->data['errors'] = array();
		$this->load->helper('form');
		$this->load->helper('url');
	}	

	public static function getInstance(){
		if(self::$instance == null){
			self::$instance = new MY_Controller;
		}
		return self::$instance;
	}
	
	 
}