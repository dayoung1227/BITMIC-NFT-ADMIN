<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if( ! function_exists('big4encode'))
{
	function big4encode( $arg_str = '' )
	{
		if( $arg_str === '' )
			return NULL;
		$ksencrypt_kskey = "n82s1big4games";
		log_message('DEBUG', 'big4encode ==== 1 ===');
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		log_message('DEBUG', 'big4encode ==== 2 ==='.$iv_size);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		log_message('DEBUG', 'big4encode ==== 2 ==='.$iv);
		return bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $ksencrypt_kskey, $arg_str, MCRYPT_MODE_ECB, $iv));
	}
}

if( ! function_exists('big4decode'))
{
	function big4decode( $arg_hexcode = '' )
	{
		if( $arg_hexcode === '' )
			return NULL;
		$binarycode = "";
		for( $i = 0; $i < strlen($arg_hexcode) - 1; $i++ )
		{
			$binarycode .= chr(hexdec($arg_hexcode[$i] . $arg_hexcode[$i + 1]));
			$i++;
		}
		if( $binarycode === '' )
			return NULL;
		$ksencrypt_kskey = "n82s1big4games";
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decodefinal = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $ksencrypt_kskey, $binarycode, MCRYPT_MODE_ECB, $iv);
		
		$temp_decodefinal = "";
		for( $i = 0; $i < strlen($decodefinal); $i++ )
		{
			if( $decodefinal[$i] === chr(0) )
				break;
			$temp_decodefinal .= $decodefinal[$i];
		}
		if( $temp_decodefinal === '' )
			return NULL;
		return $temp_decodefinal;
	}
}

if( ! function_exists('probability'))
{
	// get probability. $val : percentage value  ex) 100, 25, ...
	function probability( $val )
	{
		return mt_rand(1, 100) <= $val;
	}
}

if ( ! function_exists('BonusToArray'))
{
	function BonusToArray( $bonus_str )
	{
		$bonus_str = explode('|', $bonus_str);
		foreach( $bonus_str as &$item )
		{
			if( $item != '' )
			{
				$tmp_item = explode(',', $item);
				$item = array(
					'type'	=> $tmp_item[0],
					'grade'	=> $tmp_item[1],
					'cnt'	=> $tmp_item[2]
				);
			}
		}
		unset($item);
		unset($tmp_item);
		return $bonus_str;
	}
}

if ( ! function_exists('dice'))
{
	function dice ($arr)
	{
		if ( ! is_array ($arr)) {
			return FALSE;
		}
		$p_sum = array_sum($arr);
		$p_rand = mt_rand(1, $p_sum);

		$cnt = 0;
		$tmp = 0;
		$flag = false;
		for ($i = 0; $i < count($arr); $i++) {
			if ($flag !== false) {
				continue;
			}
			$cnt += $arr[$i];

			if ($cnt >= $p_rand && $tmp <= $p_rand) {
				return $i;
			}
			$tmp = $cnt;
		}
		return FALSE;
	}
}

/*
 * 다차원 배열 정렬 함수
 * $array : 배열
 * $colum : 정렬하려고 하는 필드(key)
 * $opts : 정렬순서 0:내림차순, 1:올림차순
 */
if ( ! function_exists('csort'))
{
	function csort($array, $column, $opts = 0)
	{
		for($i = 0; $i < count($array); $i++)
		{
			$sortarr[] = $array[$i][$column]; 
		}
		unset($i);
		$op = array(SORT_DESC, SORT_ASC);
		@array_multisort($sortarr, $op[$opts], $array); 
		unset($op);
		return($array);
	}
}	