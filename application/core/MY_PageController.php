<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_PageController extends MY_Controller {

	function __construct(){
		parent::getInstance();	
	}
	  
  public function __get($var){
    if(isset(parent::$instance->$var)) {
      return parent::$instance->$var;
    }
  }

  public function __call($name, $arguments) {  
    if(method_exists(parent::$instance,$name))
    call_user_func_array(array(parent::$instance, $name), $arguments);
  }
}

/* End of file MY_PageController.php */
/* Location: ./system/application/libraries/MY_PageController.php */