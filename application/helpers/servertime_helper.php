<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Microtime 문자열 파싱.
 * @param string $arg_microtime microtime() 형식(ex. "0.453452 1263898207") 직접 사용 가능. 미 입력시 현재 microtime()값에 대한 string 반환. 13자리 mtimestamp 형식의 경우 무조건 리턴 array.
 * @return array array(sec, milesecond)
 */
if ( ! function_exists('ParseMTime'))
{
	function ParseMTime($arg_microtime = FALSE)
	{
		if(is_numeric($arg_microtime))
		{
			$temp_len = strlen($arg_microtime);
			if( strlen($arg_microtime) > 3 )
			{
				$stime = (int) substr($arg_microtime, 0, $temp_len - 3);
				$mtime = (int) substr($arg_microtime, $temp_len - 3, 3);
			}
			else
			{
				$stime = 0;
				$mtime = (int) $arg_microtime;
			}
		}
		else
		{
			if( $arg_microtime === FALSE )
				$arg_microtime = microtime();
			elseif( strpos($arg_microtime, ' ') === FALSE )
				return FALSE; // $arg_microtime is unknown format
			$raw_time = explode(' ', $arg_microtime);
			$stime = (int) $raw_time[1];
			$mtime = intval($raw_time[0]*1000);
		}
		return array($stime, $mtime);
	}
}

/**
 * 소요 시간 얻기
 * @param string $arg_time 10자리seconds 또는 13자리 mileseconds.
 * @param string $only_sec "sec":초단위 스트링만 리턴, "msec":array("년월일시분초", 밀리초)리턴.
 * @param string $display 출력 형식. "COMPACT" or "FULL"(2010-02-04 19:05:48)
 * @return array string 년월일시분초 / array("년월일시분초", 밀리초)
 */
if ( ! function_exists('GetHumanTime'))
{
	function GetHumanTime( $arg_time = 0, $only_sec = "SEC", $display = "COMPACT" )
	{
		if( is_numeric($arg_time) === FALSE || strlen($arg_time) < 10 )
		{
			return FALSE;
		}
		$arg_time_length = strlen($arg_time);

		//$unit_second= 1000;
		$unit_second= 1;
		$unit_minute= 60 * $unit_second;
		$unit_hour	= 60 * $unit_minute;
		$unit_day	= 24 * $unit_hour;
		$stime;
		$mtime;

		if( $arg_time_length > 10 )
		{
			//argument is timestamp string
			$rawtime = str_split($arg_time, $arg_time_length - 3);
			$stime = $rawtime[0];
			$mtime = $rawtime[1];
			$mtime = sprintf("%03d", $mtime);
		}
		elseif( $arg_time_length == 10 )
		{
			$stime = $arg_time;
			$mtime = "000";
		}
		elseif( $arg_time_length < 10 )
		{
			//wrong timestamp.
			return FALSE;
		}

		$t_year		= "";
		$t_month	= "";
		$t_day		= "";
		$t_hour		= "";
		$t_minute	= "";

		$cur_time = getdate(time());
		$t_time = getdate($stime);

		if( strtoupper($display) === "FULL" )
		{
			$t_year = $t_time['year'] . "-";
			$t_month = $t_time['mon'] . "-";
			$t_day = $t_time['mday'] . " ";
		}
		else
		{
			if( $cur_time['year'] !== $t_time['year'] )
			{
				$t_year = $t_time['year'] . "년";
			}

			if( $cur_time['year'] !== $t_time['year'] OR $cur_time['mon'] != $t_time['mon'] )
			{
				$t_month = $t_time['mon'] . "월";
			}

			if( $cur_time['year'] !== $t_time['year'] OR $cur_time['mon'] !== $t_time['mon'] OR $t_time['mday'] > ($cur_time['mday'] + 1) )
			{
				$t_day = $t_time['mday'] . "일";
			}
			elseif( $t_time['mday'] === ($cur_time['mday'] + 1) )
			{
				$t_day = "내일";
			}
			elseif( $t_time['mday'] === $cur_time['mday'] )
			{
				$t_day = "";
			}
		}
		$t_hour = sprintf("%02d", $t_time['hours']) . ':';
		$t_minute = sprintf("%02d", $t_time['minutes']) . ':';
		$t_sec = sprintf("%02d", $t_time['seconds']);
		$t_milesec = $mtime;

		if( strtoupper($only_sec) == "SEC" )
		{
			return $t_year . $t_month . $t_day . $t_hour . $t_minute . $t_sec;
		}
		else
		{
			return array(($t_year . $t_month . $t_day . $t_hour . $t_minute . $t_sec), $t_milesec);
		}
	}
}

/**
 * 간단한 소요 시간 형식 변환.
 * @param string $arg_mtime 밀리세컨단위 숫자.
 * @return string "시:분:초"
 */
if ( ! function_exists('GetDuration'))
{
	function GetDuration( $arg_microtime = 0 )
	{
		$f_hours = intval($arg_microtime / (60 * 60));
		$arg_microtime = $arg_microtime % (60 * 60);
		$f_minutes = intval($arg_microtime / 60);
		$arg_microtime = $arg_microtime % 60;
		$f_secs = $arg_microtime;
		$f_minutes = sprintf("%02d", $f_minutes);
		$f_secs = sprintf("%02d", $f_secs);

		return $f_hours . ":" . $f_minutes . ":" . $f_secs;
	}
}

///**
// * 간단한 소요 시간 형식 변환.
// * @param string $arg_mtime 밀리세컨단위 숫자.
// * @return string "시:분:초"
// */
//if ( ! function_exists('GetDuration'))
//{
//	function GetDuration( $arg_microtime = 0 )
//	{
//		$mtime_length = strlen($arg_microtime);
//		if( $mtime_length > 3 )
//		{
//			//$arg_microtime = ParseMTime($arg_microtime);
//			$f_hours = intval($arg_microtime[0] / (60 * 60));
//			$arg_microtime[0] = $arg_microtime[0] % (60 * 60);
//			$f_minutes = intval($arg_microtime[0] / 60);
//			$arg_microtime[0] = $arg_microtime[0] % 60;
//			$f_secs = $arg_microtime[0];
//			$f_minutes = sprintf("%02d", $f_minutes);
//			$f_secs = sprintf("%02d", $f_secs);
//
//			return $f_hours . ":" . $f_minutes . ":" . $f_secs;
//		}
//		else
//		{
//			return $arg_microtime < 500 ? "0:00:00" : "0:00:01";
//		}
//	}
//}

/**
 * Microtime 연산.
 * @param string $arg_microtime microtime() 형식(ex. "0.453452 1263898207") 직접 사용 가능. 미 입력시 현재 microtime()값에 대한 string 반환. 13자리 mtimestamp 형식의 경우 무조건 리턴 array.
 * @param bool $as_array 스트링이 아닌 배열로 리털할 지 여부
 * @return string 13자리 milesecond timestamp
 */
if ( ! function_exists('MTime'))
{
	function MTime( $arg_microtime = FALSE, $as_array = FALSE )
	{
		$mtime = ParseMTime($arg_microtime);
		$mtime[1] = sprintf("%03d", $mtime[1]);
		return $as_array ? array($mtime[0], $mtime[1]) : $mtime[0] . $mtime[1];
	}
}
/**
 * microtime 더하기 연산.
 * @param string $src_time 13자리. microtime() 형식도(ex. "0.453452 1263898207") 직접 사용 가능
 * @param string $add_time 더해질 시간 (Milesecond 값) 단, microtime()형식 사용 불가.
 * @param bool $as_array 리턴 array or not
 * @return string 13자리 milesecond timestamp
 */
if ( ! function_exists('AddMTime'))
{
	function AddMTime($src_mtime, $add_mtime = 0, $as_array = FALSE)
	{
		$sarray = ParseMTime($src_mtime);
		$tarray = ParseMTime($add_mtime);
		$final_stime = $sarray[0] + $tarray[0];
		$final_mtime = $sarray[1] + $tarray[1];
		$final_stime += intval($final_mtime/1000);
		$final_mtime = sprintf("%03d", $final_mtime%1000);
		return $as_array ? array($final_stime, $final_mtime) : $final_stime . $final_mtime;
	}
}

/**
 * Microtime 시간 간격 구하기. $src_time 보다 $tar_time이 큰 시간이어야만 함. 작은 경우 return FALSE
 * @param string $src_time 시작 13자리 타임스탬프. microtime() 형식도 가능. (ex. "0.453452 1263898207")
 * @param string $tar_time 목표 13자리 타임스탬프. microtime() 형식도 가능. (ex. "0.453452 1263898207")
 * @param bool $as_timestamp "시:분:초" or 타임스탬프로 리턴할 지 여부.
 * @param bool $as_array [0]초 [1]밀리초로 리턴할 지 여부
 * @param bool $show_mtime $as_timestamp가 FALSE일 때 "시:분:초<span class='mtime'>밀리초</span>"으로 리턴할 지 여부
 * @return string 13자리 milesecond timestamp
 */
if ( ! function_exists('SubMTime'))
{
	function SubMTime($tar_time, $src_time, $as_timestamp = FALSE, $as_array = FALSE, $show_mtime = FALSE)
	{
		if( ! isset($src_time) || ! isset($tar_time) )
			return FALSE;
                
		$src_time = ParseMTime($src_time);

		if( ! $src_time )
			return FALSE;
		$tar_time = ParseMTime($tar_time);

		if( ! $tar_time )
			return FALSE;
		$final_mtime = $tar_time[1] - $src_time[1];

		if( $tar_time[0] - $src_time[0] < 0 )
		{
			// target time must be later than source time
			return FALSE;
		}
		else
		{
			if( ($tar_time[0] - $src_time[0] === 0) && ($final_mtime == 0) )
				return 0;
			if( ($tar_time[0] - $src_time[0] === 0) && ($final_mtime <= 0) )
				return FALSE;
			$finaltime = $tar_time[0] - $src_time[0];
			// clac mtime
			if( $final_mtime < 0 )
			{
				$finaltime--;
				$final_mtime += 1000;
			}
		}
                
		//get duration proccess
		if( $as_timestamp === TRUE )
		{
			if( $finaltime != 0 )
			{
				if( $final_mtime != 0 )
					$final_mtime = sprintf("%03d", $final_mtime);
				else
					$final_mtime = 0;
			}
			else
			{
				$finaltime = '';
			}
			return $as_array ? array($finaltime, $final_mtime) : $finaltime . $final_mtime;
		}
		else
		{
			$f_hours = intval($finaltime / (60*60));
			$finaltime = $finaltime % (60*60);
			$f_minutes = intval($finaltime / 60);
			$finaltime = $finaltime % 60;
			$f_secs = $finaltime;
			$f_minutes = sprintf("%02d", $f_minutes);
			$f_secs = sprintf("%02d", $f_secs);
			return $f_hours . ":" . $f_minutes . ":" . $f_secs . ($show_mtime ? "<sapn class='mtime'>" . $final_mtime . "</span>" : '');
		}
	}
}


if ( ! function_exists('ReminderMTime'))
{
	function ReminderMTime($src_time, $tar_time, $as_timestamp = FALSE, $as_array = FALSE, $show_mtime = FALSE)
	{
		
	}
}

/**
 * microtime 곱하기 연산.
 * @param string $arg_microtime 숫자 or microtime 형식
 * @param string $multi_val 곱해질 값.
 * @param bool $as_array 리턴 array or not
 * @return string 13자리 milesecond timestamp or array
 */
if ( ! function_exists('MultiMTime'))
{
	function MultiMTime($arg_microtime, $multi_val = 1, $as_array = FALSE)
	{
		if(! is_numeric($multi_val))
			return FALSE;
		$temp_time = ParseMTime($arg_microtime);
//		////////////////// debug
//		echo gettype($arg_microtime) . ", " . $arg_microtime;
//		echo "<pre>";
//		print_r($temp_time);
//		echo "</pre>";
//		///////////////////////////
		if(!$temp_time)
			return FALSE;

		$temp_time[0] = intval($temp_time[0] * $multi_val);
		$temp_time[1] = $temp_time[1] * $multi_val;
		$temp_time[0] += intval($temp_time[1] / 1000);
		$temp_time[1] %= 1000;
		$temp_time[1] = sprintf("%03d", $temp_time[1]);
		return $as_array ? array($temp_time[0], $temp_time[1]) : $temp_time[0] . $temp_time[1];
	}
}

if ( ! function_exists('TimeToSec'))
{
	function TimeToSec($time)
	{
		$hours = substr($time, 0, -6);
		$minutes = substr($time, -5, 2);
		$seconds = substr($time, -2);

		return $hours * 3600 + $minutes * 60 + $seconds;
	}
}
if ( ! function_exists('SecToTime'))
{
	function SecToTime($seconds)
	{
		$hours = floor($seconds / 3600);
		$minutes = floor($seconds % 3600 / 60);
		$seconds = $seconds % 60;

		return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
	}
}

if ( ! function_exists('SecToMTime'))
{
	function SecToMTime($seconds, $format = false)
	{
		$seconds = floor($seconds/1000);
		$hours = floor($seconds / 3600);
		$minutes = floor($seconds % 3600 / 60);
		$seconds = $seconds % 60;

                return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
	}
}