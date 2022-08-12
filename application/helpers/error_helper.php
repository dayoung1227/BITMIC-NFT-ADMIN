<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 에러 메세지 출력
 * @param 
 * @return 
 */
if ( ! function_exists('PutErrorMsg'))
{
	function PutErrorMsg($error_id = 'message', $tag = 'div', $option = 'style="color:red;"')
	{
		// get flash message from CI instance
		$CI =& get_instance();
		$flashmsg = $CI->session->flashdata($error_id);
		$html = '';
		if(isset($flashmsg))
		{
			if( is_array($flashmsg) )
			{
				switch($flashmsg['type'])
				{
					case 'general':
						$html='<' . $tag . ' ' . $option . '>' .
							'<strong>' . $flashmsg['content'] . '</strong>' .
						'</' . $tag . '>';
						break;
					default:
						break;
				}
			}
			else
			{
				$html = '<' . $tag . ' ' . $option . '>' .
					'<strong>' . $flashmsg . '</strong>' .
				'</' . $tag . '>';
			}
		}
		return $html;
	}
}

/**
 * prrint array
 * @param $array
 * @return void
 */
if ( ! function_exists('pa'))
{
	function pa($arr)
	{
		echo "=============================================<br>\n";
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
		echo "=============================================<br>\n";
	}
}

/**
 * dump
 * @param $array
 * @return void
 */
if ( ! function_exists('dump'))
{
	function dump($arr)
	{
		echo "=============================================<br>\n";
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
		echo "=============================================<br>\n";
	}
}

/**
 * err_output
 * variable output
 * @param $array
 * @return void
 */
if ( ! function_exists('err_output'))
{
	function err_output($arr)
	{
		foreach($arr as $key => $value)
		{
			echo $key . " : " . $value . "<br>";
		}
	}
}

/**
 * echo last query
 * @param stop TRUE or FALSE
 * @return string
 */
if ( ! function_exists('lq'))
{
	function lq($stop = FALSE)
	{
		$CI =& get_instance();
		echo "***********************************************************************<br>\n";
		echo $CI->db->last_query();
		echo "<br>***********************************************************************<br>\n";
		($stop === FALSE) ? exit : '';
	}
}