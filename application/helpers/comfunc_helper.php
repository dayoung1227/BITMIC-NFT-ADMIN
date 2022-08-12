<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('Fnc_NullToString')){
	function Fnc_NullToString($str){
		if (is_null($str) || empty($str)) return '';
		return $str;
	}
}


if ( ! function_exists('Fnc_SessionCheck')){
	function Fnc_SessionCheck($str){
		if (is_null($str) || empty($str)){
			return 0;
		}
		return 1;
	}
}

if ( ! function_exists('Fnc_MailConfig')){
	function Fnc_MailConfig($protocol, $host, $port, $user, $pass){

		$cfg = Array(
			'protocol' => $protocol,
			'smtp_host' => $host,
			'smtp_port' => $port,
			'smtp_user' => $user, // change it to yours
			'smtp_pass' => $pass, // change it to yours
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'wordwrap' => TRUE
		);

		return $cfg;
	}
}


if ( ! function_exists('Fnc_TempPw')){
	function Fnc_TempPw($length){
    $str = 'abcdefghijkmnpqrstuvwxyz123456789';
    $max = strlen($str) - 1;
    $chr = '';
    $len = abs($length);
    for($i=0; $i<$len; $i++) {
        $chr .= $str[random_int(0, $max)];
    }
		return $chr;
	}
}


if ( ! function_exists('Fnc_SecureNum')){
    function Fnc_SecureNum($length){
        $str = '123456789';
        $max = strlen($str) - 1;
        $chr = '';
        $len = abs($length);
        for($i=0; $i<$len; $i++) {
            $chr .= $str[random_int(0, $max)];
        }
        return $chr;
    }
}


if ( ! function_exists('Fnc_Define_PageInfo')){
    function Fnc_Define_PageInfo($c_post_data, $c_search_type_array, $c_order_type_array, $c_order_asc_array){
        $rst = array();
        $rst["total_record_count"]  = "";
        $rst["page_size"] 			= 1;
        $rst["block_size"] 		    = 1;
        $rst["post_data"] 		   	= $c_post_data;
        $rst["basic_search"] 		= "";
        $rst["basic_order"] 		= "";
        $rst["search_type_str"]     = "";
        $rst["order_type_str"]      = "";
        $rst["order_asc_str"]       = "";

        if(!isset($c_post_data["curr_page"])) $c_post_data["curr_page"] = 1;
        if(!isset($c_post_data["search_type"])) $c_post_data["search_type"] = "";
        if(!isset($c_post_data["search_str"])) $c_post_data["search_str"] = "";
        if(!isset($c_post_data["search_str1"])) $c_post_data["search_str1"] = "";
        if(!isset($c_post_data["search_str2"])) $c_post_data["search_str2"] = "";
        if(!isset($c_post_data["order_type"])) $c_post_data["order_type"] = "";
        if(!isset($c_post_data["order_asc"])) $c_post_data["order_asc"] = "";

        $rst["curr_page"]   = $c_post_data["curr_page"];
        $rst["search_type"] = $c_post_data["search_type"];
        $rst["search_str"]  = $c_post_data["search_str"];
        $rst["search_str1"]  = $c_post_data["search_str1"];
        $rst["search_str2"]  = $c_post_data["search_str2"];
        $rst["order_type"]  = $c_post_data["order_type"];
        $rst["order_asc"]   = $c_post_data["order_asc"];

        if($rst["search_str"] != ""){
            $rst["search_type_str"] = $c_search_type_array[$rst["search_type"]];
        }

        if($rst["search_str1"] != "" || $rst["search_str2"] != ""){
            $rst["search_type_str"] = $c_search_type_array[$rst["search_type"]];
        }

        if($rst["order_type"] != ""){
            $rst["order_type_str"] = $c_order_type_array[$rst["order_type"]];
            if($rst["order_asc"] != ""){
                $rst["order_asc_str"] = $c_order_asc_array[$rst["order_asc"]];
            }
        }
        return $rst;
    }
}

if ( ! function_exists('Fnc_ObjectToArray')){
    function Fnc_ObjectToArray($obj){
        if (is_object($obj)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $obj = get_object_vars($obj);
        }

        if (is_array($obj)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $obj);
        }
        else {
            // Return array
            return $obj;
        }
    }
}

if ( ! function_exists('Fnc_Update_PageInfo')){
    function Fnc_Update_PageInfo(&$c_page_info){
        $page_navi 	= '<div class="row text-center"><div class="col-sm-12 col-md-12 dataTables_wrapper"><div class="dataTables_info">';

        $c_page_info["total_record_count"] = Fnc_NullToNumber_0($c_page_info["total_record_count"]);
        $c_page_info["page_size"] = Fnc_NullToNumber_1($c_page_info["page_size"]);
        $c_page_info["block_size"] = Fnc_NullToNumber_1($c_page_info["block_size"]);
        $c_page_info["total_page"] 	= ceil($c_page_info["total_record_count"] / $c_page_info["page_size"]);
        $c_page_info["total_block"] = ceil($c_page_info["total_page"] / $c_page_info["block_size"]);
        $c_page_info["curr_block"] 	= ceil($c_page_info["curr_page"] / $c_page_info["block_size"]);

        $c_row_start = 0;
        $c_row_end = 0;
        $c_row_start = $c_page_info["curr_page"] * $c_page_info["block_size"] - ($c_page_info["block_size"] - 1);
        $c_row_end = $c_row_start + $c_page_info["block_size"] - 1;
        if($c_row_end > $c_page_info["total_record_count"]) $c_row_end = $c_page_info["total_record_count"];

        $page_navi 	= $page_navi.'Showing '.$c_row_start.' to '.$c_row_end.' of '.$c_page_info["total_record_count"].' entries';
        $page_navi 	= $page_navi.'</div></div><div class="col-sm-12 col-md-12 text-center"><div class="dataTables_paginate paging_simple_numbers"><ul class="pagination">';


        $c_page_info["start_page"] 	= ($c_page_info["curr_block"] - 1) * $c_page_info["block_size"] + 1;
        $c_page_info["end_page"] 	= $c_page_info["start_page"] + ($c_page_info["block_size"] -1);
        $c_page_info["prev_page"]	= $c_page_info["start_page"] - 1;
        $c_page_info["next_page"] 	= $c_page_info["end_page"] + 1;

        if($c_page_info["next_page"] > $c_page_info["total_page"]) $c_page_info["next_page"] = $c_page_info["total_page"];
        if($c_page_info["prev_page"] < 1) $c_page_info["prev_page"] = 1;
        if($c_page_info["end_page"] > $c_page_info["total_page"]) $c_page_info["end_page"] = $c_page_info["total_page"];

        $c_page_info["add_query"] = $c_page_info["basic_search"];
        if($c_page_info["add_query"] != ""){
            if($c_page_info["search_type_str"] != ""){
                $c_page_info["add_query"] = $c_page_info["add_query"]." AND (".$c_page_info["search_type_str"]." LIKE '%".$c_page_info["search_str"]."%')";
            }
        }else{
            if($c_page_info["search_type_str"] != ""){
                $c_page_info["add_query"] = " WHERE (".$c_page_info["search_type_str"]." LIKE '%".$c_page_info["search_str"]."%')";
            }

        }

/*        if ($c_page_info["admin_log"] != "") {
            $c_page_info["add_query"] = $c_page_info["add_query"]." ".$c_page_info["admin_log"];
        }*/


        if($c_page_info["order_type_str"] != ""){
            $c_page_info["add_query"] = $c_page_info["add_query"]." ORDER BY ".$c_page_info["order_type_str"]." ".$c_page_info["order_asc_str"];
            if($c_page_info["basic_order"] != "") $c_page_info["add_query"] = $c_page_info["add_query"].",".$c_page_info["basic_order"];
        }else{
            if($c_page_info["basic_order"] != "") $c_page_info["add_query"] = $c_page_info["add_query"]." ORDER BY ".$c_page_info["basic_order"];
        }


        $c_page_info["add_query"] = $c_page_info["add_query"]." LIMIT ".(($c_page_info["curr_page"] - 1) * $c_page_info["page_size"]).",".$c_page_info["page_size"];

        if($c_page_info["curr_page"] != 1){
            $page_navi = $page_navi."<li class='paginate_button page-item previous'><a href='#' class='page-link' onclick='navi_go(1);'><span>FIRST</a></li>";
        }else{
            $page_navi = $page_navi."<li class='paginate_button page-item previous disabled'><a href='#' class='page-link'>FIRST</a></li>";
        }

        if($c_page_info["curr_block"] > 1){
            $page_navi = $page_navi."<li class='paginate_button page-item previous'><a href='#' class='page-link' onclick='navi_go(".$c_page_info["prev_page"].");'><span>Previous</a></li>";
        }else{
            $page_navi = $page_navi."<li class='paginate_button page-item previous disabled'><a href='#' class='page-link'>Previous</a></li>";
        }

        for($i=$c_page_info["start_page"]; $i <= $c_page_info["end_page"]; $i++){
            if($i == $c_page_info["curr_page"]){
                $page_navi = $page_navi."<li class='paginate_button page-item active responsive-sm'><a href='#' class='page-link'>{$i}</a></li>";
            }else{
                $page_navi = $page_navi."<li class='paginate_button page-item responsive-sm'><a href='#' class='page-link' onclick='navi_go(".$i.");'>{$i}</a></li>";
            }
        }


        if($c_page_info["curr_block"] < $c_page_info["total_block"]){
            $page_navi = $page_navi."<li class='paginate_button page-item next'><a href='#' class='page-link' onclick='navi_go(".$c_page_info["next_page"].");'><span>Next</a></li>";
        }else{
            $page_navi = $page_navi."<li class='paginate_button page-item next disabled'><a href='#' class='page-link'>Next</a></li>";
        }

        if($c_page_info["curr_page"] != $c_page_info["total_page"] && $c_page_info["curr_page"] < $c_page_info["total_page"]){
            $page_navi = $page_navi."<li class='paginate_button page-item next'><a href='#' class='page-link' onclick='navi_go(".$c_page_info["total_page"].");'><span>Last</a></li>";
        }else{
            $page_navi = $page_navi."<li class='paginate_button page-item next disabled'><a href='#' class='page-link'>Last</a></li>";
        }

        $page_navi = $page_navi."</ul></div></div></div>";

        $c_page_info["navi_str"] = $page_navi;
    }
}



if ( ! function_exists('Fnc_NullToNumber_0')){
    function Fnc_NullToNumber_0($str){
        if (is_null($str) || $str == '' || !is_numeric($str) || empty($str)) return 0;
        return $str;
    }
}


if ( ! function_exists('Fnc_NullToNumber_1')){
    function Fnc_NullToNumber_1($str){
        if (is_null($str) || $str == '' || !is_numeric($str) || empty($str)) return 1;
        return $str;
    }
}


if ( ! function_exists('Fnc_TempSpon')){
    function Fnc_TempSpon($length){
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($str) - 1;
        $chr = '';
        $len = abs($length);
        for($i=0; $i<$len; $i++) {
            $chr .= $str[random_int(0, $max)];
        }

        return $chr;
    }
}

if ( ! function_exists('Fnc_CreateSecureKey')){
    function Fnc_CreateSecureKey(){
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($str) - 1;
        $chr = '';
        $len = abs(4);
        for($i=0; $i<$len; $i++) { //4자까지는 영문
            $chr .= $str[random_int(0, $max)];
        }

        //5자는 무조건 숫자
        $str = '0123456789';
        $max = strlen($str) - 1;
        $chr .= $str[random_int(0, $max)];

        //6자부터 10자까지는 무조건 문자
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($str) - 1;
        $len = abs(5);
        for($i=0; $i<$len; $i++) {
            $chr .= $str[random_int(0, $max)];
        }
        return $chr;
    }
}


if ( ! function_exists('Fnc_CheckSecureKey')){
    function Fnc_CheckSecureKey($ori_str){
        $rst = true;
        if(strlen($ori_str)  != 10){
            $rst = false;
        }else{
            $str = $ori_str[4];
            if(!is_numeric($str)){
                $rst = false;
            }
        }
        return $rst;
    }
}

if ( ! function_exists('Fnc_MCode')){
    function Fnc_MCode($length){
        $str = 'ABCDEFHJKMNPRSTUVWXY23456789';
        $max = strlen($str) - 1;
        $chr = '';
        $len = abs($length);
        for($i=0; $i<$len; $i++) {
            $chr .= $str[random_int(0, $max)];
        }

        return $chr;
    }
}
