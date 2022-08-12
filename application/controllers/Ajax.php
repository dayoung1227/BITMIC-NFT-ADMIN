<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require  '/home/ubuntu/www/tadmin.p2pblock.io/vendor/autoload.php';
use Twilio\Rest\Client;

class Ajax extends MY_PageController{

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
    }

    function index() { //default

    }

    function login_ajax(){
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $post = array();
        $user_data = array();

        $post["uid"] = Fnc_NullToString($this->input->post('uid', TRUE));
        $post["upw"] = Fnc_NullToString($this->input->post('upw', TRUE));
        $post["secure"] = Fnc_NullToString($this->input->post('secure', TRUE));
        $post["chk"] = Fnc_NullToString($this->input->post('chk', TRUE));
        $post["id_save"] = Fnc_NullToString($this->input->post('id_save', TRUE));
        $post["uid"] = strtolower(trim($post["uid"]));
        $post["upw"] = trim($post["upw"]);

        $adm_data = $this->Main_model->getAdminInfo($post["uid"]);

        if($adm_data["cnt"] < 1){
            $dat["result"] = -1;
            $dat["err"] = "아이디를 다시 입력해주세요.";
        }else{
            if(password_verify($post["upw"], $adm_data['adm_pw'])){
               $dat["s_secure"] = $this->session->userdata('secure');
               if (!$dat["s_secure"] || $dat["s_secure"] == "") {
                   $dat["s_secure"] = $post["chk"];
               }

                if ($dat["s_secure"] == $post["secure"]) {
                    //success login
                    $this->session->set_userdata(array(
                        'seq' => $adm_data["adm_sq"],
                        'aid' => $adm_data["adm_id"],
                        'level' => $adm_data["adm_level"]
                    ));

                    if($post["id_save"] == 'true'){
                        $cookie= array(
                            'name'   => 'aid',
                            'value'  => $adm_data['adm_id'],
                            'expire' => '31556926'
                        );
                        set_cookie($cookie);

                        $dat["cookie"] = $cookie;
                    }else{
                        delete_cookie("aid");
                    }

                    $this->Main_model->setAdminLogin($adm_data["adm_sq"]);

                    /* 텔레그램 봇 */
                    $chat_id = explode(',',CHAT_ID);

                    $dat["alevel"] = $adm_data["adm_level"];

                    if ($adm_data["adm_level"] != 0) {
                        foreach($chat_id as $msg){
                            $chat_id = $msg;
                            // path to the picture,
                            $text = $adm_data["adm_nnm"]." ( ".$adm_data["adm_id"]." ) 관리자가 로그인하였습니다.";
                            // following ones are optional, so could be set as null
                            $disable_web_page_preview = null;
                            $reply_to_message_id = null;
                            $reply_markup = null;

                            $data = array(
                                'chat_id' => $chat_id,
                                'text' => $text
                            );

                            $url = 'https://api.telegram.org/bot'.CHAT_TOKEN_ID.'/sendMessage';

                            //  open connection
                            $ch = curl_init();
                            //  set the url
                            curl_setopt($ch, CURLOPT_URL, $url);
                            //  number of POST vars
                            curl_setopt($ch, CURLOPT_POST, count($data));
                            //  POST data
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            //  To display result of curl
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            //  execute post
                            $result = curl_exec($ch);
                            //  close connection
                            curl_close($ch);
                        }

                        $dat["act_code"] = "5001";
                        $dat["act_result"] = $adm_data["adm_nnm"]." ( ".$adm_data["adm_id"]." ) 관리자가 로그인하였습니다.";
                        $dat["adm_sq"] = $this->session->userdata('seq');
                        $dat["log_ip"] = $this->input->ip_address();
                        $this->Main_model->setAdminLog($dat);
                    }
                } else {
                    $dat["result"] = -3;
                    $dat["err"] = "인증번호를 다시 입력해주세요.";
                }
            }else{
                $dat["result"] = -2;
                $dat["err"] = "비밀번호를 다시 입력해주세요.";
            }
        }
        echo json_encode($dat);
    }

    function ajax_contact_popup() {
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_seq"] = $this->session->userdata('seq');

        $dat["contact_seq"] = Fnc_NullToString($this->input->post('contact_seq', TRUE));

        if ($dat["contact_seq"] == "") {
            $dat["result"] = -1;
            $dat["err"] = "다시 시도해주세요.";
        } else {
            $popup = $this->Main_model->getContactDetail($dat['contact_seq']);

            $dat['adm_nnm'] = $popup["adm_nnm"];
            $dat['unick'] = $popup["unick"];
            $dat['uemail'] = $popup["bbs_email"];
            $dat['bbs_subject'] = $popup["bbs_subject"];
            $dat['bbs_content'] = $popup["bbs_content"];
            $dat['bbs_comment'] = $popup["bbs_comment"];
            $dat['upt_dttm'] = $popup["upt_dttm"];
            $dat['ins_dttm'] = $popup["ins_dttm"];
        }

        echo json_encode($dat);
    }

    // 문의 작성 & 수정
    function update_contact_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // 일단 레벨이 3인지 확인 한번 더
        if ($dat["level"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "문의답변 작성 권한이 없습니다.";
        }

        $dat["contact_seq"] = Fnc_NullToString($this->input->post('contact_seq', TRUE));
        $dat["comment"] = Fnc_NullToString($this->input->post('comment', TRUE));

        if ($dat["contact_seq"] == "" || $dat["comment"] == "") {
            $dat["result"] = -2;
            $dat["err"] = "문의 정보를 확인해주세요.";
        }

        // 디비 저장
        if ($dat["result"] > 0) {
            $isok = $this->Main_model->setComment($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            } else {
                $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

                if ( $dat["level"] != 0) {
                    $dat["act_code"] = "3001";
                    $dat["act_result"] = $admin_data["adm_nnm"]."관리자가 문의답변을 작성했습니다.";
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }
        echo json_encode($dat);
    }

    // 상품권 팝업
    function ajax_popup_list() {
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_seq"] = $this->session->userdata('seq');

        $dat["buy_seq"] = Fnc_NullToString($this->input->post('buy_seq', TRUE)); // tb_user_adw -> seq = buy_seq
        $type = Fnc_NullToString($this->input->post('type', TRUE));

        if ($dat["buy_seq"] === "" || $type == "") {
            $dat["result"] = -1;
            $dat["err"] = "번호 오류.";
        } else {
            if ($type == "buy") {
                $popup = $this->Main_model->getBuyDetail($dat['buy_seq']);
            }

            $dat['buy_seq'] = $popup["seq"];
            $dat['id'] = $popup["uid"];
            $dat['nick'] = $popup["unick"];
            $dat['recomm'] = $popup["adm_nnm"];

            // 시세 변환
            $dat["amount_price"] = number_format($popup["amount"]);
            $dat["coin_amount"] = number_format($popup["coin_amount"], 4);
            $dat["account"] = $popup["account"];
            $dat["phone_country"] = $popup["phone_country"];
            $dat["phone"] = $popup["phone"];

            if ($popup["balance_hype"] == 0) {
                $dat["balance"] = 0;
            } else {
                $dat["balance"] = number_format($popup["balance_hype"], 5);
            }

            $dat["adw_state"] = $popup["adw_state"];
            $dat["ins_dttm"] = $popup["ins_dttm"];
            $dat["upt_dttm"] = $popup["upt_dttm"];
        }
        echo json_encode($dat);
    }

    /* 구매 상태 변경 */
    function ajax_buy_change() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        $dat["adw_seq"] = Fnc_NullToString($this->input->post('buy_seq', TRUE));
        $dat["adw_state"] = Fnc_NullToString($this->input->post('state', TRUE));

        if ($dat["alevel"] == 2 || $dat["alevel"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "구매상태변경 권한이 없습니다.";
        }

        if ($dat["adw_seq"] == "" || $dat["adw_state"] == "") {
            $dat["result"] = -2;
            $dat["err"] = "입력값 오류.";
        } else {
            $adw_chk = $this->Main_model->getAdwData($dat["adw_seq"]);

            if ($adw_chk["adw_state"] != 'W') {
                $dat["result"] = -2;

                if ($adw_chk["adw_state"] == 'S') {
                    $dat["err"] = "이미 승인완료 되었습니다.";
                } else if ($adw_chk["adw_state"] == 'C') {
                    $dat["err"] = "이미 취소완료 되었습니다.";
                }
            } else {

                $dat["log_ip"] = $_SERVER['HTTP_X_FORWARDED_FOR'];

                $v_result = "";
                $txid = "";

                if ($dat["adw_state"] == "C") {
                    $v_result = "취소";
                    $dat["adw_txid"] = $txid;
                } else {
                    $v_result = "승인";

                    // #1 tb_wallets에서 관리자주소, private key 전송
                    $walletInfo = $this->Main_model->getAdwWallet();
                    $adw_addr = ADW_ADDRESS;
                    $adw_key = ADW_KEY;

                    // #2 회원의 구매 금액, 지갑주소 가져오기
                    $UserAdwInfo = $this->Main_model->getBuyAdwUser($dat["adw_seq"]);

                    $user_addr = $UserAdwInfo["wallet_addr"];
                    $user_amount = $UserAdwInfo["coin_amount"];

                    /*$api_url = "http://" . WALLET_IP . ":3000/tokenBuy";

                    $params = Array(
                        "symbol" => COIN,
                        "toaddr" => $user_addr,
                        "amount" => $user_amount,
                    );

                    try {
                        $buy_rst = json_decode($this->curl->simple_post($api_url, $params), true);
                        if (!$buy_rst || $buy_rst === '') {
                            $dat["result"] = -4;
                            $dat["err"] = '구매 전송 오류입니다.';
                        } else {
                            if ($buy_rst) {
                                $dat["adw_txid"] = $buy_rst["data"]["txid"];

                                if($dat["adw_txid"] == '') {
                                    $dat["result"] = -4;
                                    $dat["err"] = '구매 전송 오류입니다.';
                                }
                            } else {
                                $dat["result"] = -4;
                                $dat["err"] = '구매 전송 오류입니다.';
                            }
                        }
                    } catch (Exception $e) {
                        $dat["result"] = -4;
                        $dat["err"] = '구매 전송 오류입니다.';
                    }*/
                }

                if ($dat["result"] > 0) {
                    // 구매하기 call
                    $rst2 = $this->Main_model->setBuyChange($dat);
                    $rst2 = Fnc_ObjectToArray($rst2);

                    if ((int)$rst2["result"] < 1) {
                        $dat["real"] = (int)$rst2["err"];
                        $dat["result"] = -72;
                        $dat["err"] = "서버 연결 오류입니다. <br> 잠시 후 다시 시도해 주세요.";
                    } else {
                        /* 텔레그램 봇 */
                        $user_adw = $this->Main_model->getAdwData($dat["adw_seq"]);
                        $user_data = $this->Main_model->getUserDataDetail($user_adw["user_seq"]);
                        $adm_data = $this->Main_model->getAdminInfo($dat["aid"]);


                        if (!$user_adw || !$user_data || !$adm_data) {
                            $dat["result"] = -2;
                            $dat["err"] = "서버 오류";
                        } else {
                            $chat_id = explode(',', CHAT_ID);

                            if ($adm_data["adm_level"] != 0) {
                                foreach ($chat_id as $msg) {
                                    $chat_id = $msg;
                                    // path to the picture,
                                    $text = $user_data["unick"] . " ( " . $user_data["uid"] . " ) 님의 입금 ( " . number_format($user_adw["amount"]) . " 원 ) 을 " . $adm_data["adm_nnm"] . "관리자가 " . $v_result . "처리하였습니다.";
                                    // following ones are optional, so could be set as null
                                    $disable_web_page_preview = null;
                                    $reply_to_message_id = null;
                                    $reply_markup = null;

                                    $data = array(
                                        'chat_id' => $chat_id,
                                        'text' => $text
                                    );

                                    $url = 'https://api.telegram.org/bot' . CHAT_TOKEN_ID . '/sendMessage';

                                    //  open connection
                                    $ch = curl_init();
                                    //  set the url
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    //  number of POST vars
                                    curl_setopt($ch, CURLOPT_POST, count($data));
                                    //  POST data
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                    //  To display result of curl
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    //  execute post
                                    $result = curl_exec($ch);
                                    //  close connection
                                    curl_close($ch);
                                }
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($dat);
    }

    function set_membercoininfo(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        $api_url = null;

        if ($dat["result"] > 0) {
            $api_url = "http://".WALLET_IP.":3000/updateAllUserBalance/HYPE";

            $rst2 = json_decode($this->curl->simple_get($api_url), true);


            if((int)$rst2["result"] < 1){
                $dat["real"] = (int)$rst2["result"];
                $dat["result"] = -73;
                $dat["err"] = '새로고침 해주세요.';
            } else {
                $dat["balance_hype"] = $rst2["data"];
            }
        }

        if($dat["result"] > 0){
            $user_data["log_cd"] = "UPT_COIN_Y";
        }else{
            $user_data["log_cd"] = "UPT_COIN_N";
        }

        if ($dat["alevel"] != 0) {
            $dat["act_code"] = "3001";
            $dat["act_result"] = $user_data["log_cd"];
            $dat["adm_sq"] = $this->session->userdata('seq');
            $dat["log_ip"] = $this->input->ip_address();
            $this->Main_model->setAdminLog($dat);
        }

        echo json_encode($dat);
    }


    /* lock 설정 */
    function setlockyn(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
        $dat["i_sendyn"] = Fnc_NullToString($this->input->post('i_sendyn', TRUE));
        $dat["i_date"] = Fnc_NullToString($this->input->post('i_date', TRUE));
        $dat["i_sendlockdesc"] = Fnc_NullToString($this->input->post('i_sendlockdesc', TRUE));

        if ($dat["seq"] == "" || $dat["i_sendyn"] == "" || $dat["i_date"] == "" ) {
            $dat["result"] = -2;
            $dat["err"] = "다시 시도해주세요.";
        } else {
            $isok =  $this->Main_model->setUserSendStatus($dat);

            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            } else {
                if($dat["i_sendyn"] === 'Y'){
                    $this->Main_model->setUserSendStatusUnlock($dat);
                }else{
                    $this->Main_model->setUserSendStatusLog($dat);
                }

                $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

                if($dat["i_sendyn"] === 'Y'){
                    $dat["log_cd"] = "UPT_LOCK_N".$admin_data["adm_nnm"]."관리자가 ". $dat["seq"]."번 회원을 수정했습니다.";
                }else{
                    $dat["log_cd"] = "UPT_LOCK_Y".$admin_data["adm_nnm"]."관리자가 ". $dat["seq"]."번 회원을 수정했습니다.";
                }

                if ($dat["alevel"] != 0) {
                    $dat["act_code"] = "3001";
                    $dat["act_result"] = $dat["log_cd"];
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }

        echo json_encode($dat);
    }

    /* lock amount */
    function setlockAmount() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
        $dat["i_lockamount"] = Fnc_NullToNumber_0($this->input->post('i_lockamount', TRUE));

        if ($dat["seq"] == "") {
            $dat["result"] = -2;
            $dat["err"] = "다시 시도해주세요.";
        } else {
            if ($dat["i_lockamount"] == '') {
                $dat["i_lockamount"] = 0;
            }

            $isok =  $this->Main_model->setUserLockAmount($dat);

            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            } else {
                $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);
                $dat["log_cd"] = "UPT_LOCK_AMOUNT".$admin_data["adm_nnm"]."관리자가 ". $dat["seq"]."번 회원을 수정했습니다.";

                if ($dat["alevel"] != 0) {
                    $dat["act_code"] = "3001";
                    $dat["act_result"] = $dat["log_cd"];
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }
        echo json_encode($dat);
    }

    /* 회원 상태 변경 */
    function changeJoinStatus(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
        $dat["join_status"] = Fnc_NullToString($this->input->post('join_status', TRUE));

        $this->Main_model->changeJoinStatus($dat);

        if($dat["join_status"] === 'S'){
            $dat["log_cd"] = "UPT_JOIN_STATUS_S";
        } else if ($dat["join_status"] === 'W'){
            $dat["log_cd"] = "UPT_JOIN_STATUS_W";
        } else {
            $dat["log_cd"] = "UPT_JOIN_STATUS_F";
        }

        if ($dat["alevel"] != 0) {
            $dat["act_code"] = "3001";
            $dat["act_result"] = $dat["log_cd"];
            $dat["adm_sq"] = $this->session->userdata('seq');
            $dat["log_ip"] = $this->input->ip_address();
            $this->Main_model->setAdminLog($dat);
        }

        echo json_encode($dat);
    }

    /* App key 초기화 */
    function resetAppKey(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        if ($dat["alevel"] == 2 || $dat["alevel"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "App key 초기화 권한이 없습니다.";
        }

        if ($dat["result"] > 0) {
            $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));

            $this->Main_model->resetAppKey($dat);

            if ($dat["alevel"] != 0) {
                $dat["act_code"] = "3001";
                $dat["act_result"] = "RESET_APP_KEY";
                $dat["adm_sq"] = $this->session->userdata('seq');
                $dat["log_ip"] = $this->input->ip_address();
                $this->Main_model->setAdminLog($dat);
            }
        }

        echo json_encode($dat);
    }

    /* 이메일 전송 */
    function sendmail_verify(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        if ($dat["alevel"] == 2 || $dat["alevel"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "이메일 전송 권한이 없습니다.";
        }

        if ($dat["result"] > 0) {
            $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
            $user_data = $this->Main_model->getUserDataDetail($dat["seq"]);

            $mail_config = Fnc_MailConfig(MAIL_PROTOCOL, MAIL_HOST, MAIL_PORT, SMTP_USER, SMTP_PASS);
            $this->load->library('email', $mail_config);
            $this->email->set_newline("\r\n");
            $this->email->from( MAIL_USER, MAIL_USER_NM);
            $this->email->to($user_data["uid"]);
            $this->email->subject('Verify your '.WALLETNAME.' email account');
            $body = $this->load->view('_email/v_register_confirm',$user_data,TRUE);
            $this->email->message($body);

            if(!$this->email->send()) {
                $dat["result"] = -5;
                $dat["err"] = 'Send failed. please re-send email';
                $dat["log_cd"] = "SEND_MAIL_N";
            }else{
                $dat["log_cd"] = "SEND_MAIL_Y";
            }

            if ($dat["alevel"] != 0) {
                $dat["act_code"] = "3001";
                $dat["act_result"] = $dat["log_cd"];
                $dat["adm_sq"] = $this->session->userdata('seq');
                $dat["log_ip"] = $this->input->ip_address();
                $this->Main_model->setAdminLog($dat);
            }
        }

        echo json_encode($dat);
    }

    /* 비밀번호 temp */
    function sendmail_reset(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        if ($dat["alevel"] == 2 || $dat["alevel"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "비밀번호 초기화 권한이 없습니다.";
        }

        if ($dat["result"] > 0) {
            $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
            $user_data = $this->Main_model->getUserDataDetail($dat["seq"]);

            $user_data["temp_pw"] = Fnc_TempPw(10);

            $dat["temp_pw"] = $user_data["temp_pw"];
            $user_data["temp_pw"] = password_hash($user_data["temp_pw"], PASSWORD_DEFAULT); //암호화
            $this->Main_model->set_UserInfo_temppw($user_data);

            if ($dat["alevel"] != 0) {
                $dat["act_code"] = "3001";
                $dat["act_result"] = $user_data["unick"]."(".$user_data["uid"].") 임시비밀번호 설정하였습니다.";
                $dat["adm_sq"] = $this->session->userdata('seq');
                $dat["log_ip"] = $this->input->ip_address();
                $this->Main_model->setAdminLog($dat);
            }
        }

        echo json_encode($dat);
    }

    /* 보안번호 초기화 */
    function secure_num_reset() {
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        if ($dat["alevel"] == 2 || $dat["alevel"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "판매상태변경 권한이 없습니다.";
        }

        if ($dat["result"] > 0) {
            $dat["seq"] = Fnc_NullToString($this->input->post('seq', TRUE));
            $user_data = $this->Main_model->getUserDataDetail($dat["seq"]);

            $user_data["temp_secure_num"] = Fnc_SecureNum(4);

            $dat["temp_secure_num"] = $user_data["temp_secure_num"];
            $user_data["temp_secure_num"] = password_hash($user_data["temp_secure_num"], PASSWORD_DEFAULT); //암호화
            $this->Main_model->set_UserInfo_secure($user_data);

            if ($dat["alevel"] != 0) {
                $dat["act_code"] = "3001";
                $dat["act_result"] = $user_data["unick"]."(".$user_data["uid"].") 보안번호 초기화하였습니다.";
                $dat["adm_sq"] = $this->session->userdata('seq');
                $dat["log_ip"] = $this->input->ip_address();
                $this->Main_model->setAdminLog($dat);
            }
        }

        echo json_encode($dat);
    }

    /* 내 정보 수정 */
    function update_admininfo_ajax(){
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        $dat["adm_pw"] = Fnc_NullToString($this->input->post('adm_pw', TRUE));
        $dat["adm_phone"] = Fnc_NullToString($this->input->post('adm_phone', TRUE));

        // 현재 관리자 닉네임 가져옴
        $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

        if($dat["adm_phone"] == ""){
            $dat["result"] = -1;
            $dat["err"] = "다시 입력해주세요.";
        }else{
            // 휴대폰 중복검사
            $admin_phone = $this->Main_model->getAdminInfoFromPhone($dat["adm_phone"]);
            if ($admin_phone["cnt"] > 0) {
                if ($admin_phone["adm_sq"] !== $dat["seq"]) {
                    $dat["result"] = -2;
                    $dat["err"] = "이미 등록된 휴대폰 번호 입니다.";
                }
            }

            if ($dat["adm_pw"]) {
                $dat["adm_pw"] = password_hash($dat["adm_pw"], PASSWORD_DEFAULT); //암호화

                $isok = $this->Main_model->setAdminPw($dat);
                if(!$isok){
                    $dat["result"] = -4;
                    $dat["err"] = "다시 시도해주세요.";
                }else{
                    if ($dat["level"] != 0) {
                        $dat["act_code"] = "5001";
                        $dat["act_result"] =  $admin_data["adm_nnm"]."관리자 정보수정하였습니다.";
                        $dat["adm_sq"] = $this->session->userdata('seq');
                        $dat["log_ip"] = $this->input->ip_address();
                        $this->Main_model->setAdminLog($dat);
                    }
                }
            } else { // 비밀번호 변경 안함
                $isok = $this->Main_model->setAdminNoPw($dat);
                if(!$isok){
                    $dat["result"] = -4;
                    $dat["err"] = "다시 시도해주세요.";
                }else{
                    if ($dat["level"] != 0) {
                        $dat["act_code"] = "5001";
                        $dat["act_result"] =  $admin_data["adm_nnm"]."관리자 정보수정하였습니다.";
                        $dat["adm_sq"] = $this->session->userdata('seq');
                        $dat["log_ip"] = $this->input->ip_address();
                        $this->Main_model->setAdminLog($dat);
                    }
                }
            }
        }
        echo json_encode($dat);
    }

    /* 관리자 계좌 정보 변경 */
    function account_change_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        $dat["bank_code"] = Fnc_NullToString($this->input->post('bank_code', TRUE));
        $dat["bank_num"] = Fnc_NullToString($this->input->post('bank_num', TRUE));
        $dat["account"] = Fnc_NullToString($this->input->post('account', TRUE));

        if ($dat["bank_code"] == "" ||  $dat["bank_num"] == "" || $dat["account"] == ""){
            $dat["result"] = -4;
            $dat["err"] = "다시 시도해주세요.";
        }

        if ($dat["level"] == 2 || $dat["level"] == 3) {
            $dat["result"] = -4;
            $dat["err"] = "계좌정보 변경 할 수 없는 관리자입니다..";
        }

        if ($dat["result"] > 0) {
            // 은행 이름
            $bank_name = $this->Main_model->get_bankName($dat["bank_code"]);

            if (!$bank_name) {
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            }

            $dat["bank_name"] = $bank_name["bank_name"];
            $dat["symbol"] = COINNAME;

            $isok = $this->Main_model->setBankChange($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            }else{
                if ($dat["level"] != 0) {
                    $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

                    $dat["act_code"] = "5001";
                    $dat["act_result"] =  $admin_data["adm_nnm"]."관리자가 계좌정보를 변경하였습니다.";
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }

        echo json_encode($dat);
    }

    /* 로그아웃 */
    function logout(){
        $dat = array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        if ($dat["alevel"] != 0) {
            $dat["act_code"] = "5001";
            $dat["act_result"] =  "LOGOUT";
            $dat["adm_sq"] = $this->session->userdata('seq');
            $dat["log_ip"] = $this->input->ip_address();
            $this->Main_model->setAdminLog($dat);
        }

        $this->session->sess_destroy();

        header('Location: '.PROTOCOL.DOMAIN);
        exit;
    }

    /* 락업 목록 페이지 - 미사용 */
    function get_preview_list() {
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $all_data = array();

        $dat["percent"] = Fnc_NullToNumber_0($this->input->post('percent', TRUE));

        $balance_data = $this->Main_model->getUserBalanceOrderData($dat["percent"]);

        foreach($balance_data as $rows){
            $rows["balance"] = sprintf('%s', number_format($rows["balance"], 8, '.', ','));
            $rows["preview"] = sprintf('%s', number_format($rows["preview"], 8, '.', ','));
            log_message('debug',$rows["preview"]);
        }

        $dat["data"] = $balance_data;
        echo json_encode($dat);
    }

    /* 락업 - 미사용 */
    function set_all_user_lockup() {
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";
        $rock_result = array();

        $dat["adm_sq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["alevel"] = $this->session->userdata('level');

        $dat["percent"] = Fnc_NullToNumber_0($this->input->post('percent', TRUE));

        $this->Main_model->setAllUserLockup($dat["percent"]);

        if ($dat["alevel"] != 0) {
            $dat["act_code"] = "3001";
            $dat["act_result"] =  "TOTAL_LOCKUP";
            $dat["adm_sq"] = $this->session->userdata('seq');
            $dat["log_ip"] = $this->input->ip_address();
            $this->Main_model->setAdminLog($dat);
        }

        $lock_result = $this->Main_model->getAllUserBalance();

        $dat["all_balance"] = $lock_result["all_balance"];
        $dat["all_lock_amount"] = $lock_result["all_lock_amount"];

        $dat["usable"] = $dat["all_balance"] - $dat["all_lock_amount"];

        $dat["usable"] = number_format($dat["usable"],8);
        $dat["all_balance"] = number_format($dat["all_balance"],8);
        $dat["all_lock_amount"] = number_format($dat["all_lock_amount"],8);
        echo json_encode($dat);
    }

    /* 문자 전송 */
    function send_sms_ajax(){
        $dat		= array();
        $dat["result"] = 1;
        $dat["err"] = "";

        $post = array();
        $user_data = array();

        $post["uid"] = Fnc_NullToString($this->input->post('uid', TRUE));
        $post["upw"] = Fnc_NullToString($this->input->post('upw', TRUE));
        $post["uid"] = strtolower(trim($post["uid"]));
        $post["upw"] = trim($post["upw"]);

        $user_data = $this->Main_model->getAdminInfo($post["uid"]);

        if($user_data["cnt"] < 1){
            $dat["result"] = -1;
            $dat["err"] = "Please Check Your ID";
        }else{
            if(password_verify($post["upw"], $user_data['adm_pw'])){
                $account_sid = 'AC869c0a1c49caaae59b675da799c24338';
                $auth_token = '425a4ea407238343b20f1e786ee72fa7';

                $dat["secure"] = Fnc_SecureNum(6);

                $twilio_number = "+12183924400";

                $client = new Client($account_sid, $auth_token);
                $client->messages->create(
                    '+82'.$user_data["adm_phone"],
                    array(
                        'from' => $twilio_number,
                        'body' => 'P2PBlock - 관리자 로그인 인증번호는 [' .$dat["secure"]. "] 입니다."
                    )
                );

                $this->session->set_userdata(array(
                    'secure' => $dat["secure"]
                ));

                $this->Main_model->setAdminLogin($user_data["adm_sq"]);

                if ($user_data["adm_level"] != 0) {
                    $dat["act_code"] = "4001";
                    $dat["act_result"] =  "관리자 로그인 인증번호 전송";
                    $dat["adm_sq"] = $user_data["adm_sq"];
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }else{
                $dat["result"] = -2;
                $dat["err"] = "비밀번호를 다시 입력해주세요.";
            }
        }
        echo json_encode($dat);
    }

    /* 관리자 등록 */
    function insert_admininfo_ajax(){
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        if ($dat["level"] == 2 || $dat["level"] == 3) {
            $dat["result"] = -4;
            $dat["err"] = "관리자 등록 권한이 없습니다.";
        }

        $dat["adm_id"] = Fnc_NullToString($this->input->post('adm_id', TRUE));
        $dat["adm_nnm"] = Fnc_NullToString($this->input->post('adm_nnm', TRUE));
        $dat["adm_pw"] = Fnc_NullToString($this->input->post('adm_pw', TRUE));
        $dat["adm_level"] = Fnc_NullToString($this->input->post('adm_level', TRUE));
        $dat["adm_phone"] = Fnc_NullToString($this->input->post('adm_phone', TRUE));
        $dat["adm_sale_price_krw"] = Fnc_NullToString($this->input->post('adm_sale_price_krw', TRUE));
        $dat["adm_lock_dday"] = Fnc_NullToString($this->input->post('adm_lock_dday', TRUE));
        $dat["adm_sale_price_usdt"] = 0;
        $dat["m_code"] = "";
        $dat["adm_domain"] = "";

        // 현재 관리자 정보
        $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

        if($dat["adm_id"] == "" || $dat["adm_nnm"] == "" || $dat["adm_pw"] == "" || $dat["adm_level"] == "" || $dat["adm_phone"] == "" ){
            $dat["result"] = -1;
            $dat["err"] = "다시 입력해주세요.";
        }

        if ($dat["adm_level"] == 3) {
            if ( $dat["adm_sale_price_krw"] == "" ){
                $dat["result"] = -3;
                $dat["err"] = "할인판매금액은 최소 50이상 부터 입력해 주세요.";
            } else {
                if ((int)$dat["adm_sale_price_krw"] < 50) {
                    $dat["result"] = -3;
                    $dat["err"] = "할인판매금액은 최소 50이상 부터 입력해 주세요.";
                } else {
                    $adm_sale_price_usdt = (int) $dat["adm_sale_price_krw"] / 1200;
                    $dat["adm_sale_price_usdt"] = number_format($adm_sale_price_usdt, 4);
                }
            }

            if ($dat["adm_lock_dday"] == "") {
                $dat["result"] = -3;
                $dat["err"] = "회원 락업 기간을 설정해주세요.";
            }
        } else {
            $dat["adm_sale_price_krw"] = 0;
            $dat["adm_lock_dday"] = 0;
        }

        if ($dat["result"] > 0) {
            // 아이디 중복검사
            $admin_id = $this->Main_model->getAdminInfo($dat["adm_id"]);

            if ($admin_id["cnt"] > 0) {
                $dat["result"] = -2;
                $dat["err"] = "이미 등록된 아이디 입니다.";
            }

            // 닉네임 중복검사
            $admin_name = $this->Main_model->getAdminInfoFromName($dat["adm_nnm"]);

            if ($admin_name["cnt"] > 0) {
                $dat["result"] = -2;
                $dat["err"] = "이미 등록된 닉네임 입니다.";
            }

            // 휴대폰 중복검사
            $admin_phone = $this->Main_model->getAdminInfoFromPhone($dat["adm_phone"]);

            if ($admin_phone["cnt"] > 0) {
                $dat["result"] = -2;
                $dat["err"] = "이미 등록된 휴대폰 번호 입니다.";
            }
        }

        if ($dat["result"] > 0) {
            if ($dat["adm_level"] == 3) {
                $dat["m_code"] = Fnc_TempSpon(6);

                $api_url = "http://".WALLET_IP.":3000/";
                $api_url = $api_url."getRegistLink/".$dat["adm_id"];

                try {
                    $url_rst = $this->curl->simple_get($api_url);

                    if (!$url_rst || $url_rst === '') {
                        $dat["result"] = -4;
                        $dat["err"] = '추천인 URL 오류입니다.';
                    } else {
                        if ($url_rst) {
                            $curl_rst = json_decode($url_rst, true);
                            $dat["adm_domain"] = $curl_rst["data"];

                            if($dat["adm_domain"] == '') {
                                $dat["result"] = -4;
                                $dat["err"] = '추천인 URL 오류입니다.';
                            }
                        } else {
                            $dat["result"] = -4;
                            $dat["err"] = '추천인 URL 오류입니다.';
                        }
                    }
                } catch (Exception $e) {
                    $dat["result"] = -4;
                    $dat["err"] = '추천인 URL 오류입니다.';
                }
            }

            $dat["adm_pw"] = password_hash($dat["adm_pw"], PASSWORD_DEFAULT); //암호화
            $isok = $this->Main_model->setAdmin($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            }else{
                if ($dat["level"] != 0) {
                    $dat["act_code"] = "4001";
                    $dat["act_result"] = $admin_data["adm_nnm"]."관리자가 아이디 ".$dat["adm_id"]."의 새로운 관리자를 등록했습니다.";
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }
        echo json_encode($dat);
    }

    /* 회원 정보 수정 */
    function update_userinfo_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // 일단 레벨이 0인지 확인 한번 더
        if ($dat["level"] != 0) {
            $dat["result"] = -1;
            $dat["err"] = "회원정보 수정 권한이 없습니다.";
        }

        $dat["seq"] = Fnc_NullToString($this->input->post('user_seq', TRUE));
        $dat["unick"] = Fnc_NullToString($this->input->post('c_nick', TRUE));
        $dat["uphone"] = Fnc_NullToString($this->input->post('c_phone', TRUE));

        $dat["unick"] = preg_replace("/\s+/", "", $dat["unick"]);
        $dat["uphone"] = preg_replace("/\s+/", "", $dat["uphone"]);

        if ($dat["seq"] == "" || $dat["unick"] == "" || $dat["uphone"] == "") {
            $dat["result"] = -2;
            $dat["err"] = "수정할 정보를 확인해주세요.";
        }

        // 회원 정보 가져오기
        if ($dat["result"] > 0) {
            $user_data = $this->Main_model->getUserDataDetail($dat["seq"]);

            // 닉네임이 디비랑 같을 시 중복 확인 패스
            if ($user_data["unick"] != $dat["unick"]) {
                $user_nick = $this->Main_model->getUserInfoNick($dat["unick"]);

                if ($user_nick["cnt"] > 0) {
                    $dat["result"] = -2;
                    $dat["err"] = "이미 등록된 닉네임 입니다.";
                }
            }

            // 휴대폰 번호가 디비랑 같을 시 중복 확인 패스
            if ($user_data["phone"] != $dat["uphone"]) {
                $user_phone = $this->Main_model->getUserInfoPhone($dat["uphone"]);

                if ($user_phone["cnt"] > 0) {
                    $dat["result"] = -2;
                    $dat["err"] = "이미 등록된 휴대폰 번호 입니다.";
                }
            }
        }

        // 디비 저장
        if ($dat["result"] > 0) {
            $isok = $this->Main_model->setUserChange($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            }
        }

        echo json_encode($dat);
    }

    /* 회원 등록 */
    /*function insert_userinfo_ajax(){
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // 일단 레벨이 0인지 확인 한번 더
        // id 나 name, phone 이 중복되는지도 확인

        $dat["uid"] = Fnc_NullToString($this->input->post('uid', TRUE));
        $dat["unick"] = Fnc_NullToString($this->input->post('unick', TRUE));
        $dat["upw"] = Fnc_NullToString($this->input->post('upw', TRUE));
        $dat["usecure"] = Fnc_NullToString($this->input->post('usecure', TRUE));
        $dat["uphone"] = Fnc_NullToString($this->input->post('uphone', TRUE));
        $dat["urecomm"] = Fnc_NullToString($this->input->post('urecomm', TRUE));

        if($dat["uid"] == "" || $dat["unick"] == "" || $dat["upw"] == "" || $dat["usecure"] == "" || $dat["uphone"] == "" || $dat["urecomm"] == ""){
            $dat["result"] = -1;
            $dat["err"] = "다시 입력해주세요.";
        }else{
            // 아이디 중복검사
            $admin_id = $this->Main_model->getUserInfoId($dat["uid"]);

            if ($admin_id["cnt"] > 0) {
                $dat["result"] = -2;
                $dat["err"] = "이미 등록된 아이디 입니다.";
            }

            // 닉네임 중복검사
            $admin_name = $this->Main_model->getUserInfoNick($dat["unick"]);

            if ($admin_name["cnt"] > 0) {
                $dat["result"] = -2;
                $dat["err"] = "이미 등록된 닉네임 입니다.";
            }

            // 휴대폰 중복검사
            $admin_phone = $this->Main_model->getUserInfoPhone($dat["uphone"]);

            if ($admin_phone["cnt"] > 0) {
                $dat["result"] = -2;
                $dat["err"] = "이미 등록된 휴대폰 번호 입니다.";
            }
        }

        // 현재 관리자 정보
        $admin_data = $this->Main_model->getAdminInfoNick($dat["urecomm"]);

        if ($admin_data["cnt"] > 0) {
            $dat["p_user_seq"] = $admin_data["adm_sq"];
        } else {
            $dat["result"] = -2;
            $dat["err"] = "추천인을 확인해주세요.";
        }

        if ($dat["result"] > 0) {
            $dat["lang"] = $this->lng;
            $dat["nation"] = "82";
            $dat["activation_code"] = md5($dat["uid"]);
            $dat["join_ip"] = $this->input->ip_address();
            $dat["upw"] = password_hash($dat["upw"], PASSWORD_DEFAULT); //암호화
            $dat["usecure"] = password_hash($dat["usecure"], PASSWORD_DEFAULT); //암호화
            $dat["app_key"] = Fnc_CreateSecureKey();
            $dat["m_code"] = Fnc_MCode(6);
            $is_unique = false;
            while (!$is_unique) {
                $result = $this->Main_model->get_MCode($dat["m_code"]);
                if ($result["total_cnt"] == 0){
                    $is_unique = true;
                }else{
                    $dat["m_code"] = Fnc_TempSpon(6);
                }
            }
            // address 발급
            $svr_id_array = array('VENI01','VENI01');

            $svr_num = array_rand($svr_id_array, 1);
            $dat["svr_id"] = $svr_id_array[$svr_num];
            $dat["symbol"] = COINNAME;
            $dat["wallet_addr"] = "";

            //address/:symbol/:svrid/:account //$api_url = $api_url."address/".$dat["symbol"]."/".$dat["svr_id"]."/".$dat["uid"];
            $api_url = "http://".WALLET_IP.":3000/";
            $api_url = $api_url."createaddress";


            try{
                $wallet_rst = $this->curl->simple_get($api_url);
                if(!$wallet_rst || $wallet_rst === ''){
                    $dat["result"] = -4;
                    $dat["err"] = $this->lang->line('register_txt27');
                }else{
                    $curl_rst = json_decode($wallet_rst, true);

                    $dat["result"] = $curl_rst["result"];

                    if((int)$dat["result"] > 0){
                        $dat["wallet_addr"] = $curl_rst["data"]["address"];
                        $dat["private_key"] = $curl_rst["data"]["priv_key"];
                        $dat["mnemonic"] = $curl_rst["data"]["mnemonic"];

                        $user_data = $this->Main_model->setUser($dat);
                        $user_data = Fnc_ObjectToArray($user_data);

                        if ($user_data["result"] < 0) {
                            $dat["result"] = -4;
                            $dat["err"] = $this->lang->line('register_txt27');
                        } else {
                            if ($dat["level"] != 0) {
                                $dat["act_code"] = "3001";
                                $dat["act_result"] = $admin_data["adm_nnm"]."관리자가 아이디 ".$dat["uid"]."의 새로운 회원을 등록했습니다.";
                                $dat["adm_sq"] = $this->session->userdata('seq');
                                $dat["log_ip"] = $this->input->ip_address();
                                $this->Main_model->setAdminLog($dat);
                            }
                        }
                    }else{
                        $dat["result"] = -4;
                        $dat["err"] = $this->lang->line('register_txt27');
                    }
                }
            } catch(Exception $e){
                $dat["result"] = -4;
                $dat["err"] = $this->lang->line('register_txt27');
            }
        }
        echo json_encode($dat);
    } */

    /* 관리자 수정  */
    function update_admin_ajax(){
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // 일단 레벨이 0인지 확인 한번 더
        // id 나 name, phone 이 중복되는지도 확인
        $dat["adm_level"] = Fnc_NullToString($this->input->post('adm_level', TRUE));
        $dat["adm_seq"] = Fnc_NullToString($this->input->post('adm_seq', TRUE));
        $dat["adm_pw"] = Fnc_NullToString($this->input->post('adm_pw', TRUE));
        $dat["adm_phone"] = Fnc_NullToString($this->input->post('adm_phone', TRUE));
        $dat["adm_sale_price_krw"] = Fnc_NullToString($this->input->post('adm_sale_price_krw', TRUE));
        $dat["adm_lock_dday"] = Fnc_NullToString($this->input->post('adm_lock_dday', TRUE));
        $dat["adm_sale_price_usdt"] = 0;

        // 현재 관리자 닉네임 가져옴
        $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

        if($dat["adm_phone"] == "" || $dat["adm_seq"] == ""){
            $dat["result"] = -1;
            $dat["err"] = "다시 입력해주세요.";
        }else{
            // 수정할 관리자 정보 가져오기
            $admin_modify = $this->Main_model->getAdminInfoFromSeq($dat["adm_seq"]);

            if ($admin_modify["cnt"] <= 0) {
                $dat["result"] = -2;
                $dat["err"] = "비활성화된 관리자는 수정이 불가능합니다.";
            }

            if ($dat["adm_level"] == 3) {
                if ($dat["adm_sale_price_krw"] == "") {
                    $dat["result"] = -3;
                    $dat["err"] = "할인판매금액은 최소 50이상 부터 입력해 주세요.";
                } else {
                    if ((int)$dat["adm_sale_price_krw"] < 50) {
                        $dat["result"] = -3;
                        $dat["err"] = "할인판매금액은 최소 50이상 부터 입력해 주세요.";
                    } else {
                        $adm_sale_price_usdt = (int) $dat["adm_sale_price_krw"] / 1200;
                        $dat["adm_sale_price_usdt"] = number_format($adm_sale_price_usdt, 4);
                    }
                }

                if ($dat["adm_lock_dday"] == "") {
                    $dat["result"] = -3;
                    $dat["err"] = "회원 락업 기간을 설정해주세요.";
                }
            } else {
                $dat["adm_sale_price_krw"] = 0;
                $dat["adm_lock_dday"] = 0;
            }


            // 휴대폰 중복검사
            $admin_phone = $this->Main_model->getAdminInfoFromPhone($dat["adm_phone"]);
            if ($admin_phone["cnt"] > 0) {
                if ($admin_phone["adm_sq"] !== $dat["adm_seq"]) {
                    $dat["result"] = -2;
                    $dat["err"] = "이미 등록된 휴대폰 번호 입니다.";
                }
            }

            if ($dat["result"] > 0) {
                if ($dat["level"] >= $admin_modify["adm_level"]) { // 0 >= 2
                    $dat["result"] = -3;
                    $dat["err"] = "수정할 관리자 레벨이 같거나 높을 시 수정 불가능합니다.";
                } else {
                    if ($dat["adm_pw"] == "") { // 닉네임 또는 휴대폰 수정
                        $isok = $this->Main_model->setUpdateAdminNoPw($dat);
                        if(!$isok){
                            $dat["result"] = -4;
                            $dat["err"] = "다시 시도해주세요.";
                        }else{
                            if ($dat["level"] != 0) {
                                $dat["act_code"] = "4001";
                                $dat["act_result"] =  $admin_data["adm_nnm"]." 관리자가 아이디".$admin_modify["adm_id"]."관리자를 수정하였습니다.";
                                $dat["adm_sq"] = $this->session->userdata('seq');
                                $dat["log_ip"] = $this->input->ip_address();
                                $this->Main_model->setAdminLog($dat);
                            }
                        }
                    } else {
                        $dat["adm_pw"] = password_hash($dat["adm_pw"], PASSWORD_DEFAULT); //암호화
                        $isok = $this->Main_model->setUpdateAdmin($dat);
                        if(!$isok){
                            $dat["result"] = -4;
                            $dat["err"] = "다시 시도해주세요.";
                        }else{
                            if ($dat["level"] != 0) {
                                $dat["act_code"] = "4001";
                                $dat["act_result"] = $admin_data["adm_nnm"]." 관리자가 아이디".$admin_modify["adm_id"]."관리자를 수정하였습니다.";
                                $dat["adm_sq"] = $this->session->userdata('seq');
                                $dat["log_ip"] = $this->input->ip_address();
                                $this->Main_model->setAdminLog($dat);
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($dat);
    }

    /* 미사용 */
    function recomm_list() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // #1 - 넘어오는 값 확인
        $dat["adm_seq"] = Fnc_NullToString($this->input->post('adm_seq', TRUE));

        if (!$dat["adm_seq"] || $dat["adm_seq"] == "" || $dat["adm_seq"] == null) {
            $dat["result"] = -1;
            $dat["err"] = "다시 시도해주세요.";
        }

        $c_add_query = "where recomm_seq = ".$dat["adm_seq"];

        $dat["list_data"] = $this->Main_model->getRecommendDataList($c_add_query);
        // 전체 출금 수수료
        $total_data = $this->Main_model->getTotalRecommFee($dat["adm_seq"]);
        $dat['withdraw_fee_total'] = number_format($total_data["withdraw_fee_total"]);
        // 금일 출금 수수료
        $today_data = $this->Main_model->getTodayRecommFee($dat["adm_seq"]);
        $dat['withdraw_fee'] = number_format($today_data["withdraw_fee"]);
        // 이번주 출금 수수료
        $week_data = $this->Main_model->getWeekRecommFee($dat["adm_seq"]);
        $dat['withdraw_fee_week'] = number_format($week_data["withdraw_fee_week"]);
        // 이번달 출금 수수료
        $month_data = $this->Main_model->getMonthRecommFee($dat["adm_seq"]);
        $dat['withdraw_fee_month'] = number_format($month_data["withdraw_fee_month"]);


        echo json_encode($dat);
    }

    /* 회원 비활성화/활성화 */
    function update_userLogin_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // 일단 레벨이 0인지 확인 한번 더
        if ($dat["level"] == 2 || $dat["level"] == 3) {
            $dat["result"] = -1;
            $dat["err"] = "비활성화 권한이 없습니다.";
        }

        $dat["seq"] = Fnc_NullToString($this->input->post('user_seq', TRUE));
        $dat["status"] = Fnc_NullToString($this->input->post('status', TRUE));

        if ($dat["seq"] == "" || $dat["status"] == "") {
            $dat["result"] = -2;
            $dat["err"] = "수정할 정보를 확인해주세요.";
        }

        if ($dat["result"] > 0) {
            if ($dat["status"] == "S") {
                $dat["status"] = "F";
            } else if ($dat["status"] == "F") {
                $dat["status"] = "S";
            } else {
                $dat["status"] = "W";
            }

            $isok = $this->Main_model->setUserStatusChange($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            } else {
                if ($dat["level"] != 0) {
                    // 현재 관리자 닉네임 가져옴
                    $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

                    $dat["act_code"] = "3001";
                    $dat["act_result"] = $admin_data["adm_nnm"]." 관리자가 ".$dat["seq"]."번 회원을 비활성화하였습니다.";
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }

        echo json_encode($dat);
    }

    /* 관리자 활성화 / 비활성화 */
    function active_admin_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // #1 - 넘어오는 값 확인
        $dat["adm_seq"] = Fnc_NullToString($this->input->post('adm_seq', TRUE));

        if (!$dat["adm_seq"] || $dat["adm_seq"] == "" || $dat["adm_seq"] == null) {
            $dat["result"] = -1;
            $dat["err"] = "다시 시도해주세요.";
        }

        // 현재 관리자 닉네임 가져옴
        $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

        // #2 - 수정할 사람이 자신보다 낮은 레벨인지 확인, use_yn 검사하기
        if ($dat["result"] > 0) {
            $adminActive = $this->Main_model->getAdminInfoFromSeqUseCheck($dat["adm_seq"]);

            if ($dat["level"] >= $adminActive["adm_level"]) {
                $dat["result"] = -2;



                $dat["err"] = "활성화할 관리자 레벨이 같거나 높을 시 활성화 불가능합니다.";
            } else {  // #3 - 활성화 하기
                if ($adminActive["use_yn"] == 'Y') {
                    $dat["use_yn"] = 'N';
                    $type = '비활성화';
                    $dat["txt"] = "비활성화하셨습니다.";
                } else {
                    $dat["use_yn"] = 'Y';
                    $type = '활성화';
                    $dat["txt"] = "활성화하셨습니다.";
                }

                // 활성 / 비활성
                $this->Main_model->setAdminUseChange($dat);

                if ($dat["level"] != 0) {
                    $dat["act_code"] = "4001";
                    $dat["act_result"] = $admin_data["adm_nnm"]." 관리자가 ".$adminActive["adm_id"]." ".$type."하였습니다.";
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }

            }
        }
        echo json_encode($dat);
    }


    /*
     *      공지사항
     * */
    function update_notice_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // #1 - 넘어오는 값 확인
        $dat["notice_seq"] = Fnc_NullToString($this->input->post('notice_seq', TRUE));
        $dat["title"] = Fnc_NullToString($this->input->post('title', TRUE));
        $dat["content"] = Fnc_NullToString($this->input->post('content', TRUE));

        if ($dat["notice_seq"] == "" || $dat["title"] == "" || $dat["content"] == "") {
            $dat["result"] = -1;
            $dat["err"] = "다시 시도해주세요.";
        }

        if ($dat["result"] > 0) {
            $isok = $this->Main_model->setNoticeModify($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            } else {
                if ($dat["level"] != 0) {
                    // 현재 관리자 닉네임 가져옴
                    $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

                    if ($dat["level"] != 0) {
                        $dat["act_code"] = "4001";
                        $dat["act_result"] = $admin_data["adm_nnm"]." 관리자가 공지사항 ".$dat["notice_seq"]."번 글을 수정하였습니다.";
                        $dat["adm_sq"] = $this->session->userdata('seq');
                        $dat["log_ip"] = $this->input->ip_address();
                        $this->Main_model->setAdminLog($dat);
                    }
                }
            }
        }

        echo json_encode($dat);
    }

    function active_notice_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // #1 - 넘어오는 값 확인
        $dat["notice_seq"] = Fnc_NullToString($this->input->post('notice_seq', TRUE));

        if (!$dat["notice_seq"] || $dat["notice_seq"] == "" || $dat["notice_seq"] == null) {
            $dat["result"] = -1;
            $dat["err"] = "다시 시도해주세요.";
        }

        // 현재 관리자 닉네임 가져옴
        $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

        // #2 - 수정할 사람이 자신보다 낮은 레벨인지 확인, use_yn 검사하기
        if ($dat["result"] > 0) {
            $noticeActive = $this->Main_model->getNoticeDetail($dat["notice_seq"]);
            if ($noticeActive["use_yn"] == 'Y') {
                $dat["use_yn"] = 'N';
                $type = '비활성화';
                $dat["txt"] = "비활성화하셨습니다.";
            } else {
                $dat["use_yn"] = 'Y';
                $type = '활성화';
                $dat["txt"] = "활성화하셨습니다.";
            }

            // 활성 / 비활성
            $this->Main_model->setNoticeUseChange($dat);

            if ($dat["level"] != 0) {
                $dat["act_code"] = "4001";
                $dat["act_result"] = $admin_data["adm_nnm"]." 관리자가 공지사항 ".$dat["notice_seq"]."번 글을 ".$type."하였습니다.";
                $dat["adm_sq"] = $this->session->userdata('seq');
                $dat["log_ip"] = $this->input->ip_address();
                $this->Main_model->setAdminLog($dat);
            }
        }
        echo json_encode($dat);
    }

    function insert_notice_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        // #1 - 넘어오는 값 확인
        $dat["title"] = Fnc_NullToString($this->input->post('title', TRUE));
        $dat["content"] = Fnc_NullToString($this->input->post('content', TRUE));

        if ($dat["title"] == "" || $dat["content"] == "") {
            $dat["result"] = -1;
            $dat["err"] = "다시 시도해주세요.";
        }

        if ($dat["result"] > 0) {
            $isok = $this->Main_model->setNoticeWrite($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            } else {
                if ($dat["level"] != 0) {
                    // 현재 관리자 닉네임 가져옴
                    $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);

                    if ($dat["level"] != 0) {
                        $dat["act_code"] = "4001";
                        $dat["act_result"] = $admin_data["adm_nnm"]." 관리자가 공지사항 글을 작성하였습니다.";
                        $dat["adm_sq"] = $this->session->userdata('seq');
                        $dat["log_ip"] = $this->input->ip_address();
                        $this->Main_model->setAdminLog($dat);
                    }
                }
            }
        }

        echo json_encode($dat);
    }

    // 회사이름 변경
    function company_change_ajax() {
        $dat		= array();
        $dat["result"] = 1;

        $user_data = array();
        $isok = false;

        $dat["seq"] = $this->session->userdata('seq');
        $dat["aid"] = $this->session->userdata('aid');
        $dat["level"] = $this->session->userdata('level');

        $dat["i_company"] = Fnc_NullToString($this->input->post('i_company', TRUE));
        $dat["symbol"] = COIN_NAME;

        if ($dat["i_company"] == ""){
            $dat["result"] = -4;
            $dat["err"] = "다시 시도해주세요.";
        }

        if ($dat["level"] == 2 || $dat["level"] == 3) {
            $dat["result"] = -4;
            $dat["err"] = "회사정보 변경 할 수 없는 관리자입니다..";
        }

        if ($dat["result"] > 0) {
            $isok = $this->Main_model->setCompanyChange($dat);
            if(!$isok){
                $dat["result"] = -4;
                $dat["err"] = "다시 시도해주세요.";
            }else{
                if ($dat["level"] != 0) {
                    $admin_data = $this->Main_model->getAdminInfo($dat["aid"]);
                    $dat["act_code"] = "5001";
                    $dat["act_result"] =  $admin_data["adm_nnm"]."관리자가 회사정보를 변경하였습니다.";
                    $dat["adm_sq"] = $this->session->userdata('seq');
                    $dat["log_ip"] = $this->input->ip_address();
                    $this->Main_model->setAdminLog($dat);
                }
            }
        }

        echo json_encode($dat);
    }

}
