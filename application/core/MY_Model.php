<?php
class MY_Model extends CI_Model {
    function __construct(){
        parent::__construct();
        
		$this->db = $this->load->database('db', TRUE);

    }
}