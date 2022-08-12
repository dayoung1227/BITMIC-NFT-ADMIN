<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends MY_PageController{

	var $navigate;
	var $user_info = array();
	var $login_yn = 'N';

	function __construct(){
        parent::getInstance();

        $this->load->helper(array('cookie', 'comfunc', 'form', 'date'));
        $this->load->model('Main_model');

        $this->output->set_header("Cache-Control: no-cache, must-revalidate");
        $this->output->set_header('HTTP/1.0 200 OK');
        $this->output->set_header('HTTP/1.1 200 OK');
        $this->output->set_header('Cache-Control: no-store, no-cache');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        $this->user_info['seq'] = $this->session->userdata('seq');
        $this->user_info['aid'] = $this->session->userdata('aid');

        //$this->load->library('cfnc_lib');
	
	}

	function index() { //default
		$dat		= array();
		$dat["result"] = 1;
		$dat["err"] = "";
		$dat["login_yn"] = $this->login_yn;

        if($this->user_info['aid'] !== '' && $this->user_info['aid'] !== false){
            header('Location: '.PROTOCOL.DOMAIN.'/Main');
            exit;
        }else{
            header('Location: '.PROTOCOL.DOMAIN.'/Index/login');
            exit;
        }

	}


    function template() { //default
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["login_yn"] = $this->login_yn;
        $this->load->view('/v_template', $dat);
    }


    function template_blank() { //default
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["login_yn"] = $this->login_yn;
        $this->load->view('/v_template_blank', $dat);
    }


    function template_login() { //default
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["login_yn"] = $this->login_yn;
        $this->load->view('/v_template_login', $dat);
    }


	function login() { //default
		$dat		= array();
		$dat["result"] = 1;
		$dat["err"] = "";
		$this->load->view('/v_login');
	}


}
