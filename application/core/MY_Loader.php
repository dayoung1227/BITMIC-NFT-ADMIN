<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader{
	
	function __construct()
	{	
		parent::__construct();
	}

	function controller($controller = '', $params = NULL, $object_name = NULL)
	{
		if ($controller == '')
		{
			return FALSE;
		}

		if ( ! is_null($params) AND ! is_array($params))
		{
			$params = NULL;
		}

		if (is_array($controller))
		{
			foreach ($controller as $class)
			{
				$this->_ci_load_controller($class, $params, $object_name);
			}
		}
		else
		{
			$this->_ci_load_controller($controller, $params, $object_name);
		}
		
	}
  
  function _ci_load_controller($class, $params = NULL, $object_name = NULL)
	{	
		// Get the class name, and while we're at it trim any slashes.  
		// The directory path can be included as part of the class name, 
		// but we don't want a leading slash
		$class = str_replace(EXT, '', trim($class, '/'));
	
		// Was the path included with the class name?
		// We look for a slash to determine this
		$subdir = '';
		if (strpos($class, '/') !== FALSE)
		{
			// explode the path so we can separate the filename from the path
			$x = explode('/', $class);	
			
			// Reset the $class variable now that we know the actual filename
			$class = end($x);
			
			// Kill the filename from the array
			unset($x[count($x)-1]);
			
			// Glue the path back together, sans filename
			$subdir = implode($x, '/').'/';
		}

		// We'll test for both lowercase and capitalized versions of the file name
		foreach (array(ucfirst($class), strtolower($class)) as $class)
		{
			$subclass = APPPATH.'controllers/'.$subdir.config_item('subclass_prefix').$class.EXT;

			// Is this a class extension request?			
			if (file_exists($subclass))
			{
				$baseclass = BASEPATH.'controllers/'.ucfirst($class).EXT;
				
				if ( ! file_exists($baseclass))
				{
					log_message('error', "Unable to load the requested class: ".$class);
					show_error("Unable to load the requested class: ".$class);
				}

				// Safety:  Was the class already loaded by a previous call?
				if (in_array($subclass, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied.  If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);			
						}
					}
					
					$is_duplicate = TRUE;
					log_message('debug', $class." class already loaded. Second attempt ignored.");
					return;
				}
	
				include_once($baseclass);				
				include_once($subclass);
				$this->_ci_loaded_files[] = $subclass;
	
				return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);			
			}
		
			// Lets search for the requested library file and load it.
			$is_duplicate = FALSE;		
			for ($i = 1; $i < 3; $i++)
			{
				$path = ($i % 2) ? APPPATH : BASEPATH;	
				$filepath = $path.'controllers/'.$subdir.$class.EXT;
				
				// Does the file exist?  No?  Bummer...
				if ( ! file_exists($filepath))
				{
					continue;
				}
				
				// Safety:  Was the class already loaded by a previous call?
				if (in_array($filepath, $this->_ci_loaded_files))
				{
					// Before we deem this to be a duplicate request, let's see
					// if a custom object name is being supplied.  If so, we'll
					// return a new instance of the object
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, '', $params, $object_name);
						}
					}
				
					$is_duplicate = TRUE;
					log_message('debug', $class." class already loaded. Second attempt ignored.");
					return;
				}
				
				include_once($filepath);
				$this->_ci_loaded_files[] = $filepath;
				return $this->_ci_init_class($class, '', $params, $object_name);
			}
		} // END FOREACH

		// One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
		if ($subdir == '')
		{
			$path = strtolower($class).'/'.$class;
			return $this->_ci_load_controller($path, $params);
		}
		
		// If we got this far we were unable to find the requested class.
		// We do not issue errors if the load call failed due to a duplicate request
		if ($is_duplicate == FALSE)
		{
			log_message('error', "Unable to load the requested class: ".$class);
			show_error("Unable to load the requested class: ".$class);
		}
	}
	
}

/* End of file MY_Loader.php */
/* Location: ./system/application/libraries/Loader.php */