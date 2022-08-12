<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_PageController{

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
		$this->user_info['level'] = $this->session->userdata('level');

		if($this->user_info['aid'] != '' && $this->user_info['aid'] != false){
			$this->login_yn = 'Y';
		}else{
			header('Location: '.PROTOCOL.DOMAIN.'/Index/login');
			exit;
		}
		//$this->load->library('cfnc_lib');
	}

	function index() { //default
	    $dat = array();
		$dat["result"] = 1;
		$dat["err"] = "";
		$dat["mnu"] = "dashboard";
        $dat["menu"] = "dashboard";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $api_url = null;

        $dat["add_query"] = '';
        $dat["active"] = "A";

        $dat["total_cnt"] = $this->Main_model->getUserTotalCount();
        $dat["week_inout"] = $this->Main_model->get_weekly_inout();
        $dat["week_trans"] = $this->Main_model->get_weekly_transaction();
        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["total_cnt"] = number_format($dat["total_cnt"],0);

        // 레벨 3은 추천인이랑 관련된 회원만 보여줌
        if ($dat["level"] != 3) {
            $dat["join_list"] = $this->Main_model->getUserDataRecent();
            $dat["trans_list"] = $this->Main_model->getTransactionData(0, 10);
        } else {
            $dat["join_list"] = $this->Main_model->getUserDataRecentLevel($dat["seq"]);
            $dat["trans_list"] = $this->Main_model->getTransactionLevelData(0, 10, $dat["seq"]);
        }


		$this->load->view('/_include/v_top', $dat);
		$this->load->view('/v_index', $dat);
		$this->load->view('/_include/v_bottom', $dat);
	}

    function member_list() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 회원 목록";
        $dat["menu"] = "member";
        $dat["menu02"] = "member_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["list_data"] = $this->Main_model->getUserData();

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_user_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    /* 레벨 3 관리자만 볼 수 있는 회원 현황 */
    function admin_recomm_list() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "회원 현황";
        $dat["menu"] = "admin_recomm";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        /* 레벨 3 관리자만 볼 수 있는 회원 현황 */
        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ($dat["level"] == 1 || $dat["level"] == 0) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/index');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $adm_recomm_data = $this->Main_model->getAdminLevelInfo();
        $dat["recomm_seq"] = $adm_recomm_data["adm_sq"];

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'vu.unick',
            'search_type_b'=>'a.adm_nnm'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'vu.seq',
            'board_order_2'=>'vu.join_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"]) {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " vu.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/admin_recomm_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " WHERE vu.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND vu.recomm_seq = ".$dat["seq"];
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalMemberCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"]);
        Fnc_Update_PageInfo($page_info); //pointer
        $page_info["add_query"] = '';

        $add_query = " ORDER BY vu.join_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            if ($page_info["search_str"]) {
                $level_query = " AND vu.recomm_seq = ".$dat["seq"];
            } else {
                $level_query = " WHERE vu.recomm_seq = ".$dat["seq"];
            }
        } else {
            $level_query = "";
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalMemberData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        // #관리자 추천인 url
        $adminInfo = $this->Main_model->getAdminInfoFromSeq($dat["seq"]);
        $dat["adm_domain"] = $adminInfo["adm_domain"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_recommends_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function admin_recomm_page() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "회원 현황";
        $dat["menu"] = "admin_recomm";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        /* 레벨 3 관리자만 볼 수 있는 회원 현황 */
        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ($dat["level"] == 1 || $dat["level"] == 0) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/index');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $adm_recomm_data = $this->Main_model->getAdminLevelInfo();
        $dat["recomm_seq"] = $adm_recomm_data["adm_sq"];

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'vu.unick',
            'search_type_b'=>'a.adm_nnm'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'vu.seq',
            'board_order_2'=>'vu.join_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"]) {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " vu.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/recomm_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " WHERE vu.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND vu.recomm_seq = ".$dat["seq"];
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalMemberCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"]);
        Fnc_Update_PageInfo($page_info); //pointer
        $page_info["add_query"] = '';

        $add_query = " ORDER BY vu.join_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            if ($page_info["search_str"]) {
                $level_query = " AND vu.recomm_seq = ".$dat["seq"];
            } else {
                $level_query = " WHERE vu.recomm_seq = ".$dat["seq"];
            }
        } else {
            $level_query = "";
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalMemberData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_recommends_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function member_page() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 회원 목록";
        $dat["menu"] = "member";
        $dat["menu02"] = "member_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'u.uid',
            'search_type_b'=>'u.unick',
            'search_type_c'=>'uw.wallet_addr'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'seq',
            'board_order_2'=>'uid',
            'board_order_3'=>'unick',
            'board_order_4'=>'balance',
            'board_order_5'=>'send_yn',
            'board_order_6'=>'join_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);
        $page_info["page_size"] = 10;
        $page_info["block_size"] = 10;
        $page_info["basic_order"] = " seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/member_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " WHERE seq > 0 AND uw.symbol = '".COIN."'";
        $page_info["total_record_count"] = $this->Main_model->getTotalUserCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $dat["list_data"] = $this->Main_model->getUserData($page_info["add_query"]);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_user_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    /* 회원 상세보기 */
    function detail_memberinfo() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "회원 기본정보";
        $dat["menu"] = "member";
        $dat["menu02"] = "member_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        $dat["user_seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
        $dat["type"] = Fnc_NullToString($this->input->post('type', TRUE));
        $user_data = $this->Main_model->getUserDataDetail($dat["user_seq"]);


        // 추천인 표시
        if ($user_data["recomm_seq"]) {
            $dat["recomm_seq"] = $user_data["recomm_seq"];
            $recomm = $this->Main_model->getRecommUserInfo($dat);
            $user_data["recomm"] = $recomm["adm_nnm"];
        }

        $dat["lock_data"] = $this->Main_model->getLockHistoryFromUserSeq($dat["user_seq"]);

        if($user_data["send_yn"] ==='Y'){
            $user_data["send_yn_dp"] = 'Unlocked';
            $user_data["lock_amount"] = 0;
        }else{
            $user_data["send_yn_dp"] = 'Wallet Locked';
            $user_data["lock_amount"] = $user_data["lock_amount"];
        }
        $dat["post_data"] = $post_data;
        $dat["user_data"] = $user_data;

        // 구매판매 내역
        $dat["list_data"] = $this->Main_model->getDealData($user_data["seq"]);

        // 전송 내역
        $page_info["basic_search"] = " WHERE seq > 0 AND ( sunick = '".$user_data["unick"]."' OR runick = '".$user_data["unick"]."' )";
        $page_info["total_record_count"] = $this->Main_model->getTotalTransactionCount($page_info["basic_search"], "", "");

        $start = 0;
        $limit = 10000;
        // 회원의 전송 내역 조회
        $dat["history_data"] = $this->Main_model->getUserTransactionData($start, $limit, $user_data["seq"]);

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_user_detail', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    /* 구매 목록 */
    function buy_list() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 구매 목록";
        $dat["menu"] = "buy";
        $dat["menu02"] = "buy_list";
        $dat["menu_depth"] = "depth02";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'va.unick',
            'search_type_b'=>'a.adm_nnm',
            'search_type_c'=>'va.ins_dttm',
            'search_type_d'=>'va.upt_dttm',
            'search_type_e'=>'va.upt_dttm',
            'search_type_f'=>'va.adw_state'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'va.seq',
            'board_order_2'=>'va.ins_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " va.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/buy_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND va.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.recomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_d") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'S ' ";
        } else if ($page_info["search_type"] == "search_type_e") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'C ' ";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalBuyCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"],$page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $page_info["add_query"] = '';

        $add_query = " ORDER BY va.ins_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND va.recomm_seq = ".$dat["seq"];
            if ($page_info["search_type"] == "search_type_d") {
                $level_query = $level_query." AND va.adw_state = 'S";
            } else if ($page_info["search_type"] == "search_type_e") {
                $level_query = $level_query." AND va.adw_state = 'C'";
            }
        } else {
            $level_query = "";
            if ($page_info["search_type"] == "search_type_d") {
                $level_query = $level_query." AND va.adw_state = 'S '";
            } else if ($page_info["search_type"] == "search_type_e") {
                $level_query = $level_query." AND va.adw_state = 'C '";
            }
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];


        $dat["list_data"] = $this->Main_model->getTotalBuyData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_buy_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function buy_page() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 구매 목록";
        $dat["menu"] = "buy";
        $dat["menu02"] = "buy_list";
        $dat["menu_depth"] = "depth02";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'va.unick',
            'search_type_b'=>'a.adm_nnm',
            'search_type_c'=>'va.ins_dttm',
            'search_type_d'=>'va.upt_dttm',
            'search_type_e'=>'va.upt_dttm',
            'search_type_f'=>'va.adw_state'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'va.seq',
            'board_order_2'=>'va.ins_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " va.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/buy_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND va.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.recomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_d") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'S ' ";
        } else if ($page_info["search_type"] == "search_type_e") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'C ' ";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalBuyCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"],$page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $page_info["add_query"] = '';

        $add_query = " ORDER BY va.ins_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND va.recomm_seq = ".$dat["seq"];
            if ($page_info["search_type"] == "search_type_d") {
                $level_query = $level_query." AND va.adw_state = 'S";
            } else if ($page_info["search_type"] == "search_type_e") {
                $level_query = $level_query." AND va.adw_state = 'C'";
            }
        } else {
            $level_query = "";
            if ($page_info["search_type"] == "search_type_d") {
                $level_query = $level_query." AND va.adw_state = 'S '";
            } else if ($page_info["search_type"] == "search_type_e") {
                $level_query = $level_query." AND va.adw_state = 'C '";
            }
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalBuyData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);

        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_buy_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }
    /* 구매 대기자 목록 */
    function buy_waiting_list() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "구매 대기자 목록";
        $dat["menu"] = "buy";
        $dat["menu02"] = "buy_waiting_list";
        $dat["menu_depth"] = "depth02";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'va.unick',
            'search_type_b'=>'a.adm_nnm',
            'search_type_c'=>'va.ins_dttm'
        );

        $order_type_array = array(
            'board_order_1'=>'va.seq',
            'board_order_2'=>'va.ins_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " va.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/buy_waiting_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND va.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.recomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_d") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'S ' ";
        } else if ($page_info["search_type"] == "search_type_e") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'C ' ";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalBuyWaitCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"],$page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $page_info["add_query"] = '';

        $add_query = " ORDER BY va.ins_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND va.recomm_seq = ".$dat["seq"];
        } else {
            $level_query = "";
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];


        $dat["list_data"] = $this->Main_model->getTotalBuyWaitData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);

        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_buy_waiting_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    /* 구매 대기자 */
    function buy_waiting_page() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "구매 대기자 목록";
        $dat["menu"] = "buy";
        $dat["menu02"] = "buy_waiting_list";
        $dat["menu_depth"] = "depth02";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'va.unick',
            'search_type_b'=>'a.adm_nnm',
            'search_type_c'=>'va.ins_dttm'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'va.seq',
            'board_order_2'=>'va.ins_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " va.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/buy_waiting_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND va.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.recomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_d") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'S ' ";
        } else if ($page_info["search_type"] == "search_type_e") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND va.adw_state = 'C ' ";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalBuyWaitCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"],$page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $page_info["add_query"] = '';

        $add_query = " ORDER BY va.ins_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND va.recomm_seq = ".$dat["seq"];
        } else {
            $level_query = "";
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalBuyWaitData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);

        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_buy_waiting_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }


    /* Receive List */
    function transaction_list(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "Receive List";
        $dat["menu"] = "trans";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["menu_depth"] = "";
        $dat["menu02"] = "transaction_list";

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'runick',
            'search_type_b'=>'sunick',
            'search_type_c'=>'unick', // 모든 닉네임
            'search_type_d'=>'txid',
            'search_type_e'=>'trans_time'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'seq',
            'board_order_2'=>'category',
            'board_order_3'=>'trans_time'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = "seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/transaction_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND rrecomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_c") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND ( category='receive' AND runick='".$page_info["search_str"]."') OR ( category='receive' AND sunick='".$page_info["search_str"]."')";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalReceiveCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer


        $page_info["add_query"] = '';

        $add_query = " ORDER BY a.trans_time desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND rrecomm_seq = ".$dat["seq"];
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='receive' AND runick='".$page_info["search_str"]."') OR ( category='receive' AND sunick='".$page_info["search_str"]."')";
            }
        } else {
            $level_query = "";
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='receive' AND runick='".$page_info["search_str"]."') OR ( category='receive' AND sunick='".$page_info["search_str"]."')";
            }
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];


        $dat["list_data"] = $this->Main_model->getTotalReceiveData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_transactions', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function transaction_page(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "Receive List";
        $dat["menu"] = "trans";
        $dat["menu_depth"] = "";
        $dat["menu02"] = "transaction_list";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'runick',
            'search_type_b'=>'sunick',
            'search_type_c'=>'unick', // 모든 닉네임
            'search_type_d'=>'txid',
            'search_type_e'=>'trans_time'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'seq',
            'board_order_2'=>'category',
            'board_order_3'=>'trans_time'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/transaction_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND rrecomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_c") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND ( category='receive' AND runick='".$page_info["search_str"]."') OR ( category='receive' AND sunick='".$page_info["search_str"]."')";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalReceiveCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $add_query = " ORDER BY a.trans_time desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND rrecomm_seq = ".$dat["seq"];
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='receive' AND runick='".$page_info["search_str"]."') OR ( category='receive' AND sunick='".$page_info["search_str"]."')";
            }
        } else {
            $level_query = "";
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='receive' AND runick='".$page_info["search_str"]."') OR ( category='receive' AND sunick='".$page_info["search_str"]."')";
            }
        }

        $page_info["add_query"] = '';

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalReceiveData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_transactions', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function send_list(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "Send List";
        $dat["menu"] = "trans";
        $dat["menu_depth"] = "";
        $dat["menu02"] = "send_list";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'sunick',
            'search_type_b'=>'runick',
            'search_type_c'=>'unick', // 모든 닉네임
            'search_type_d'=>'txid',
            'search_type_e'=>'trans_time'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'seq',
            'board_order_2'=>'category',
            'board_order_3'=>'trans_time'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }
        $page_info["basic_order"] = " seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/send_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND srecomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_c") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND ( category='send' AND sunick='".$page_info["search_str"]."') OR ( category='send' AND runick='".$page_info["search_str"]."')";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalSendCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $add_query = " ORDER BY a.trans_time desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND srecomm_seq = ".$dat["seq"];
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='send' AND sunick='".$page_info["search_str"]."') OR ( category='send' AND runick='".$page_info["search_str"]."')";
            }
        } else {
            $level_query = "";
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='send' AND sunick='".$page_info["search_str"]."') OR ( category='send' AND runick='".$page_info["search_str"]."')";
            }
        }

        //$dat["swap_history_data"] = $this->Main_model->getSwapData($add_query);
        $page_info["add_query"] = '';

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalSendData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);

        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_send_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function send_page(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "Send List";
        $dat["menu"] = "trans";
        $dat["menu_depth"] = "";
        $dat["menu02"] = "send_list";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'sunick',
            'search_type_b'=>'runick',
            'search_type_c'=>'unick', // 모든 닉네임
            'search_type_d'=>'txid',
            'search_type_e'=>'trans_time'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'seq',
            'board_order_2'=>'category',
            'board_order_3'=>'trans_time'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"] != "" || $page_info["search_str1"] != "" || $page_info["search_str2"] != "") {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }
        $page_info["basic_order"] = " seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/send_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " AND seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND srecomm_seq = ".$dat["seq"];
        }

        if ($page_info["search_type"] == "search_type_c") {
            $page_info["basic_search"] = $page_info["basic_search"]." AND ( category='send' AND sunick='".$page_info["search_str"]."') OR ( category='send' AND runick='".$page_info["search_str"]."')";
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalSendCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $add_query = " ORDER BY a.trans_time desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND srecomm_seq = ".$dat["seq"];
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='send' AND sunick='".$page_info["search_str"]."') OR ( category='send' AND runick='".$page_info["search_str"]."')";
            }
        } else {
            $level_query = "";
            if ($page_info["search_type"] == "search_type_c") {
                $level_query = $level_query." AND ( category='send' AND sunick='".$page_info["search_str"]."') OR ( category='send' AND runick='".$page_info["search_str"]."')";
            }
        }

        $page_info["add_query"] = '';

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalSendData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $page_info["search_str1"], $page_info["search_str2"],$level_query);

        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_send_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function myinfo(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "내 정보 수정";
        $dat["menu"] = "account";
        $dat["menu02"] = "myinfo";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        $my_data = $this->Main_model->getAdminInfo($dat["aid"]);

        $dat["my_data"] = $my_data;

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_myinfo', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function logout(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["menu"] = "account";

        $this->session->sess_destroy();
        //$this->load->helper('url');
        //redirect('/');
        header('Location: '.PROTOCOL.DOMAIN);
        exit;
    }


    function admin_logs() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "관리자 활동내역";
        $dat["menu"] = "admin";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["list_data"] = $this->Main_model->getAdminLogDataList($dat["level"]);

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_admin_log', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function admin_control() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "관리자 등록";
        $dat["menu"] = "admin_list";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_admin_control', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }


    function bank_control() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "계좌정보 변경";
        $dat["menu"] = "account";
        $dat["menu02"] = "bank";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        // 등록된 계좌 정보 불러오기
        $bank_data = $this->Main_model->get_bankInfo();

        $dat["bank_name"] = $bank_data["bank_name"];
        $dat["bank_code"] = $bank_data["bank_code"];
        $dat["bank_num"] = $bank_data["bank_num"];
        $dat["account"] = $bank_data["account"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_bank_control', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function company_control() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "회사정보 변경";
        $dat["menu"] = "account";
        $dat["menu02"] = "company";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        // 등록된 계좌 정보 불러오기
        $dat["company"] = $this->Main_model->getCompany();

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_company_info', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function contact_list() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 문의 목록";
        $dat["menu"] = "contact";
        $dat["menu02"] = "contact_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        // 전체 문의 목록
        if ($dat["level"] == 3) {
            $dat["list_data"] = $this->Main_model->getContactLevelList($dat["seq"]);
        } else {
            $dat["list_data"] = $this->Main_model->getContactList();
        }

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_contact_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function contact_waiting_list() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "문의답변 대기 목록";
        $dat["menu"] = "contact";
        $dat["menu02"] = "contact_waiting_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["list_data"] = $this->Main_model->getContactWaitList();

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_contact_waiting_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function admin_modify($adm_sq) { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "관리자 수정";
        $dat["menu"] = "admin_list";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["adm_sq"] = $adm_sq;

        if ($dat["level"] == 2) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/recommends_list');
            exit;
        } else if ( $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        $admin_data = $this->Main_model->getAdminInfoFromSeqUseCheck($dat["adm_sq"]);
        $dat["admin_data"] = $admin_data;

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_admin_modify', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function admin_list() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 관리자 목록";
        $dat["menu"] = "admin_list";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";


        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["list_data"] = $this->Main_model->getAllAdminInfoList($dat["level"]);

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_admin_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    // 전체 회원 목록
    function recommends_list() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 회원 목록";
        $dat["menu"] = "member";
        $dat["menu02"] = "recommends_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ($dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/admin_recomm_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $adm_recomm_data = $this->Main_model->getAdminLevelInfo();
        $dat["recomm_seq"] = $adm_recomm_data["adm_sq"];

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'vu.unick',
            'search_type_b'=>'a.adm_nnm'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'vu.seq',
            'board_order_2'=>'vu.join_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"]) {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " vu.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/recommends_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " WHERE vu.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND vu.recomm_seq = ".$dat["seq"];
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalMemberCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"]);
        Fnc_Update_PageInfo($page_info); //pointer


        $page_info["add_query"] = '';

        $add_query = " ORDER BY vu.join_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND vu.recomm_seq = ".$dat["seq"];
        } else {
            $level_query = "";
        }

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];


        $dat["list_data"] = $this->Main_model->getTotalMemberData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_recommends_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function recommends_page(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "전체 회원 목록";
        $dat["menu"] = "member";
        $dat["menu02"] = "recommends_list";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
        $search_type_array = array(
            'search_type_a'=>'vu.unick',
            'search_type_b'=>'vu.adm_nnm'
        );
        //order defined
        $order_type_array = array(
            'board_order_1'=>'vu.seq',
            'board_order_2'=>'vu.join_dttm'
        );

        $order_asc_array = array('asc'=>'ASC', 'desc'=>'DESC');

        if(!isset($post_data["order_type"])) $post_data["order_type"] = "board_order_1";
        if(!isset($post_data["order_asc"])) $post_data["order_asc"] = "desc";

        $page_info = Fnc_Define_PageInfo($post_data, $search_type_array, $order_type_array, $order_asc_array);

        if ($page_info["search_str"]) {
            $page_info["page_size"] = 50;
            $page_info["block_size"] = 50;
        } else {
            $page_info["page_size"] = 10;
            $page_info["block_size"] = 10;
        }

        $page_info["basic_order"] = " vu.seq asc "; //무조건 기본이 되는 정렬 하나 지정. 다른정렬이 있더라도 이 정렬이 뒤에 옴 없으면 "" 해도 됨
        $page_info["link_url"] = "/Main/recommends_page"; //페이지 이동시 이동해야 할 controller
        $page_info["target_div"] = "main_area"; //설치될 div
        $page_info["change_div"] = "main_area"; //이동될 div

        $page_info["basic_search"] = " WHERE vu.seq > 0 ";

        if ($dat["level"] == 3) {
            $page_info["basic_search"] = $page_info["basic_search"]." AND vu.recomm_seq = ".$dat["seq"];
        }

        $add_query = " limit 0,100000";
        $dat["member_data"] = $this->Main_model->getUserData($add_query);

        $page_info["total_record_count"] = $this->Main_model->getTotalMemberCount($page_info["basic_search"], $page_info["search_type_str"], $page_info["search_str"]);
        Fnc_Update_PageInfo($page_info); //pointer

        $add_query = " ORDER BY vu.join_dttm desc limit 0,2000";

        if ($dat["level"] == 3) {
            $level_query = " AND vu.recomm_seq = ".$dat["seq"];
        } else {
            $level_query = "";
        }

        //$dat["swap_history_data"] = $this->Main_model->getSwapData($add_query);
        $page_info["add_query"] = '';

        if ($page_info["curr_page"] == 1) {
            $start = 0;
        } else {
            $start = ($page_info["curr_page"] - 1) * $page_info["block_size"];
        }
        $limit = $page_info["block_size"];

        $dat["list_data"] = $this->Main_model->getTotalMemberData($start, $limit, $page_info["search_type_str"], $page_info["search_str"], $level_query);
        $dat["page_info"] = $page_info;
        $dat["page"] = $page_info["curr_page"];

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('_include/m_pagination', $page_info);
        $this->load->view('v_recommends_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    /* 토큰 보유 정보 */
    function contract() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = '토큰 보유 정보';
        $dat["menu"] = "account";
        $dat["menu02"] = "contract";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $walletInfo = $this->Main_model->getAdwWallet();

        $api_url = "http://".WALLET_IP.":3000/";
        $api_url = $api_url."getBalances/".ADW_ADDRESS;

        try{
            $wallet_rst = $this->curl->simple_get($api_url);

            if(!$wallet_rst || $wallet_rst === ''){
                $dat["contact_token"] = NULL;
                $dat["contact_coin"] = NULL;
                $dat["result"] = -4;
                $dat["err"] = "error";
            }else{
                $curl_rst = json_decode($wallet_rst, true);
                $dat["result"] = $curl_rst["result"];

                if($dat["result"] > 0){
                    $dat["contact_token"] = $curl_rst["data"]["token_balance"];
                    $dat["contact_coin"] = $curl_rst["data"]["coin_balance"];
                } else {
                    $dat["result"] = -4;
                    $dat["err"] = "error";
                }
            }
        } catch(Exception $e){
            $dat["result"] = -4;
            $dat["err"] = "error";
        }

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_contract_info', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function event_log(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "Recent Event Logs";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        $add_query = " ORDER BY ins_dttm desc limit 0,200";
        $dat["history_data"] = $this->Main_model->getLogData($add_query);

        $this->load->view('v_event_log', $dat);
    }

    function all_user_lockup() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "All User LockUp Setting";

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $user_amount = null;
        $user_amount =  $this->Main_model->getAllUserBalance();

        $dat["all_user_amount"] = number_format($user_amount["all_balance"],8);

        $this->load->view('v_all_lockup', $dat);
    }

    /*
     *         공지사항
     * */
    function notice_list() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "공지사항 목록";
        $dat["menu"] = "notice_list";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $dat["list_data"] = $this->Main_model->getAllNoticeList();

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_notice_list', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function notice_write() { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "공지사항 작성";
        $dat["menu"] = "notice_list";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        if ( $dat["level"] == 2 && $dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/notice_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter


        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_notice_write', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

    function notice_modify($notice_seq) { //default
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $dat["mnu"] = "공지사항 수정";
        $dat["menu"] = "notice_list";
        $dat["menu02"] = "";
        $dat["menu_depth"] = "depth01";

        $dat["seq"] = $this->user_info['seq'];
        $dat["aid"] = $this->user_info['aid'];
        $dat["level"] = $this->user_info['level'];

        $dat["notice_seq"] = $notice_seq;

        if ($dat["level"] == 3) {
            header('Location: '.PROTOCOL.DOMAIN.'/Main/notice_list');
            exit;
        }

        $dat["buy_cnt"] = $this->Main_model->getBuyWaitingData();
        $dat["contact_cnt"] = $this->Main_model->getContactWaitingData();

        $post_data = NULL;
        $post_data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

        $notice_data = $this->Main_model->getNoticeDetail($dat["notice_seq"]);
        $dat["notice_data"] = $notice_data;

        $this->load->view('/_include/v_top', $dat);
        $this->load->view('v_notice_modify', $dat);
        $this->load->view('/_include/v_bottom', $dat);
    }

}





	


