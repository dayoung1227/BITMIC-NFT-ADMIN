<?php ## Training Model
//class alliance_n_model extends CI_Model{
class Main_model extends MY_Model{
    ## var $md_dat = array();
    ## var $sql = '';
    ## var $md_row;

    function __construct(){
        parent::__construct();
    }

    /*
    *  admin INFO
    * */
    // # - 관리자 정보
    function getAdminInfo($c_data){
        $md_row = null;
        $sql = "SELECT 	count(*) as cnt, adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn, adm_deposit_fee, adm_withdraw_fee, sale_price_krw, sale_price_usdt, adm_domain, join_lockup_month   
                  FROM  tb_admin
                 WHERE 	adm_id = ?
                   AND  use_yn = 'Y'
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 추천인 닉네임 정보 확인
    function getAdminInfoNick($c_data){
        $md_row = null;
        $sql = "SELECT count(*) as cnt, adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn, adm_deposit_fee, adm_withdraw_fee, sale_price_krw, sale_price_usdt, adm_domain, join_lockup_month   
                  FROM  tb_admin
                 WHERE 	adm_nnm = ?
                   AND  use_yn = 'Y'
                   AND  adm_level = '3'
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 추천인 목록에서 기준 한명
    function getAdminLevelInfo() {
        $md_row = null;
        $sql = "SELECT 	adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn , sale_price_krw, sale_price_usdt, adm_domain, join_lockup_month    
                  FROM  tb_admin
                 WHERE 	adm_level = '3'
                 LIMIT 	0,1;
                 ";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row;
    }
    // # - 닉네임 중복검사
    function getAdminInfoFromName($c_data){
        $md_row = null;
        $sql = "SELECT 	count(*) as cnt, adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn, sale_price_krw, sale_price_usdt, adm_domain , join_lockup_month 
                  FROM  tb_admin
                 WHERE 	adm_nnm = ?
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 휴대폰 중복검사
    function getAdminInfoFromPhone($c_data){
        $md_row = null;
        $sql = "SELECT 	count(*) as cnt, adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn, sale_price_krw, sale_price_usdt, adm_domain, join_lockup_month   
                  FROM  tb_admin
                 WHERE 	adm_phone = ?
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 관리자 정보
    function getAdminInfoFromSeq($c_data){
        $md_row = null;
        $sql = "SELECT 	count(*) as cnt, adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn, sale_price_krw, sale_price_usdt, adm_domain , join_lockup_month 
                  FROM  tb_admin
                 WHERE 	adm_sq = ?
                   AND  use_yn = 'Y'
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 관리자 확인
    function getAdminInfoFromSeqUseCheck($c_data){
        $md_row = null;
        $sql = "SELECT 	count(*) as cnt, adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn , adm_deposit_fee, adm_withdraw_fee , sale_price_krw, sale_price_usdt, adm_domain, join_lockup_month
                  FROM  tb_admin
                 WHERE 	adm_sq = ?
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 자신보다 하위이거나 같은 관리자 목록
    function getAllAdminInfoList($c_data){
        $md_row = null;
        $sql = "SELECT adm_sq, adm_id, adm_nnm, adm_pw, adm_level, adm_phone, adm_domain, last_login_dttm, use_yn, adm_deposit_fee, adm_withdraw_fee, sale_price_krw, sale_price_usdt, adm_domain, join_lockup_month  
                  FROM tb_admin
                 WHERE adm_level >= ?
                   AND adm_level NOT IN ('0');";
        $md_row = $this->db->query($sql, array($c_data))->result_array();
        return $md_row;
    }
    // # -추천인 정보
    function getRecommUserInfo($c_data) {
        $md_row = null;
        $sql = "SELECT  adm_nnm, adm_domain, join_lockup_month 
                  FROM 	tb_admin
                 WHERE	adm_sq = ?";
        $md_row = $this->db->query($sql, $c_data["recomm_seq"])->row_array();
        return $md_row;
    }
    // # - 활성/비활성 변경
    function setAdminUseChange($c_data){
        $md_row = null;
        $sql = "UPDATE tb_admin 
                   SET use_yn = ? 
                 WHERE adm_sq = ?;";
        $md_row = $this->db->query($sql,array($c_data["use_yn"], $c_data["adm_seq"]));
        return $md_row;
    }
    // # - 내정보수정 -> 비밀번호까지
    function setAdminPw($c_data){
        $md_row = null;
        $sql = "UPDATE 	tb_admin
                   SET 	adm_pw = ?, adm_phone = ?
                 WHERE 	adm_sq = ?";
        $md_row = $this->db->query($sql,array($c_data["adm_pw"], $c_data["adm_phone"], $c_data["seq"]));
        return $md_row;
    }
    // # - 내정보 수정 -> 비밀번호 변경 안함
    function setAdminNoPw($c_data){
        $md_row = null;
        $sql = "UPDATE 	tb_admin
                   SET 	adm_phone = ?
                 WHERE 	adm_sq = ?";
        $md_row = $this->db->query($sql,array($c_data["adm_phone"], $c_data["seq"]));
        return $md_row;
    }
    // # - 관리자 마지막 로그인 정보
    function setAdminLogin($c_data){
        $md_row = null;
        $sql = "UPDATE tb_admin 
                   SET last_login_dttm = now() 
                 WHERE adm_sq = ?";
        $md_row = $this->db->query($sql,$c_data);
        return $md_row;
    }
    // # - 관리자 등록
    function setAdmin($c_data){
        $md_row = null;
        $sql = "INSERT INTO tb_admin (adm_id, adm_nnm, adm_pw, adm_level, adm_phone, sale_price_krw, sale_price_usdt, join_lockup_month, m_code, adm_domain, use_yn, join_dttm) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Y', NOW())";
        $md_row = $this->db->query($sql,array($c_data["adm_id"], $c_data["adm_nnm"], $c_data["adm_pw"], $c_data["adm_level"], $c_data["adm_phone"], $c_data["adm_sale_price_krw"], $c_data["adm_sale_price_usdt"], $c_data["adm_lock_dday"], $c_data["m_code"], $c_data["adm_domain"]));
        return $md_row;
    }
    // # - 관리자 수정 - 비번, 닉네임, 폰번호
    function setUpdateAdmin($c_data){
        $md_row = null;
        $sql = "UPDATE tb_admin 
                   SET adm_pw = ?, adm_phone = ?, sale_price_krw = ?, sale_price_usdt = ?, join_lockup_month = ?
                 WHERE adm_sq = ?";
        $md_row = $this->db->query($sql,array($c_data["adm_pw"], $c_data["adm_phone"], $c_data["adm_sale_price_krw"], $c_data["adm_sale_price_usdt"], $c_data["adm_lock_dday"], $c_data["adm_seq"]));
        return $md_row;
    }
    // # - 관리자 수정 - 닉네임, 폰번호
    function setUpdateAdminNoPw($c_data){
        $md_row = null;
        $sql = "UPDATE tb_admin 
                   SET adm_phone = ?, sale_price_krw = ?, sale_price_usdt = ?, join_lockup_month = ?
                 WHERE adm_sq = ?";
        $md_row = $this->db->query($sql,array($c_data["adm_phone"], $c_data["adm_sale_price_krw"], $c_data["adm_sale_price_usdt"], $c_data["adm_lock_dday"], $c_data["adm_seq"]));
        return $md_row;
    }
    // # - 회원가입 시 추천인 확인
    function get_MCode($c_data){
        $md_row = null;
        $sql = "SELECT count(*) total_cnt, adm_sq, adm_domain, join_lockup_month 
				  FROM tb_admin
				 WHERE m_code = ?
				   AND adm_level = '3'
				   AND use_yn = 'Y';
				 ";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }

    /*
     * 관리자 지갑 정보
     * */
    // # - 관리자 지갑 정보
    function getAdwWallet() {
        $md_row = null;
        $sql = "SELECT wallet_addr, priv_key
                  FROM tb_wallets
                 WHERE symbol = 'HYPE'";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row;
    }
    // # - 관리자 계좌정보 가져오기
    function get_bankInfo() {
        $md_row = null;
        $sql = "SELECT bank_name, bank_code, bank_num, account
				  FROM 	tb_wallets
                  ;
				 ";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row;
    }
    // # - 관리자 계좌 정보 변경
    function setBankChange($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_wallets 
                   SET bank_code = ?, bank_num = ?, bank_name = ?, account = ? 
                 WHERE symbol = ?";
        $md_row = $this->db->query($sql,array($c_data["bank_code"], $c_data["bank_num"], $c_data["bank_name"], $c_data["account"], $c_data["symbol"]));
        return $md_row;
    }

    /*
     *          회사정보
     * */
    function getCompany() {
        $md_row = null;
        $sql = "SELECT company_info
				FROM tb_wallets";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row["company_info"];
    }
    function setCompanyChange($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_wallets 
                   SET company_info = ?, upt_dttm = NOW() 
                 WHERE symbol = ?";
        $md_row = $this->db->query($sql,array($c_data["i_company"], $c_data["symbol"]));
        return $md_row;
    }

    /*
     *  회원 계좌 정보
     * */
    // # - 회원의 구매 정보와 지갑주소
    function getBuyAdwUser($c_data) {
        $md_row = null;
        $sql = "SELECT amount, wallet_addr, coin_amount
                  FROM vw_tb_user_adw
                 WHERE seq = ?
                   AND adw_type = 'D'
                   AND adw_state = 'W'
                 LIMIT 0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 전체 구매 대기자 수
    function getBuyWaitCount() {
        $md_row = null;
        $sql = "SELECT  count(*) as buy_wait_cnt
				  FROM 	vw_tb_user_adw 
				 WHERE	adw_state = 'W'
				   AND  adw_type = 'D'";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row;
    }
    // # - 구매 팝업 상세
    function getBuyDetail($c_data) {
        $md_row = null;
        $sql = "SELECT  va.seq, va.user_seq, va.uid, va.unick, va.amount, va.coin_amount, va.bank_name, va.bank_num, va.account, va.phone_country, va.phone, va.balance_hype, va.balance_bnb, va.adw_state, va.ins_dttm, va.upt_dttm, a.adm_nnm
				  FROM 	vw_tb_user_adw va
             LEFT JOIN  tb_admin a  ON va.recomm_seq = a.adm_sq
				 WHERE	va.adw_type = 'D'
				   AND  va.seq = ?";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 구매 페이지 - 추천인관리자 */
    function getBuyData($c_add_query){
        $md_row = null;
        $sql = "SELECT 	va.seq, va.user_seq, va.uid, va.adw_type, va.bank_code, va.bank_name, va.bank_num, va.account, va.amount, va.coin_amount, va.adw_bank, va.adw_state, va.ins_dttm, va.upt_dttm, va.adm_sq, va.unick, va.recomm_seq, a.adm_nnm
				  FROM 	vw_tb_user_adw va
				  LEFT JOIN tb_admin a  
				  ON va.recomm_seq = a.adm_sq";

        if ($c_add_query) {
            $sql = $sql.$c_add_query;
        }

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 전체 구매 대기 수 */
    function getTotalBuyWaitCount($c_basic_search, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
                FROM 	vw_tb_user_adw va
                LEFT JOIN  tb_admin a ON va.recomm_seq = a.adm_sq
                WHERE adw_type = 'D'
                AND  adw_state = 'W' ";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
        }

        if($c_search_type != "" && $c_search_str != "") {
            $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        } else if ($c_search_type != "" && $c_search_str1 != "" && $c_search_str2 != "") {
            $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 전체 구매 대기 목록
    function getTotalBuyWaitData($c_start, $c_limit, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2, $c_level) {
        $md_row = null;
        $sql = "SELECT va.seq, va.user_seq, va.uid, va.adw_type, va.bank_code, va.bank_name, va.bank_num, va.account, va.amount, va.coin_amount, va.adw_bank, va.adw_state, va.ins_dttm, va.upt_dttm, va.adm_sq, va.unick, va.recomm_seq, a.adm_nnm
				  FROM 	vw_tb_user_adw va
				  LEFT JOIN tb_admin a  ON va.recomm_seq = a.adm_sq
				  WHERE adw_type = 'D'
				  AND  adw_state = 'W'";

        if ($c_search_type != "") {
            if ($c_search_str1 != "" && $c_search_str2 != "") {
                $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
            } else if($c_search_str != "") {
                $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
            }
        }

        if ($c_level != "") {
            $sql = $sql.$c_level;
        }

        $sql = $sql." ORDER BY va.seq DESC LIMIT ".$c_start.", ".$c_limit;

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 구매 관련 목록 수
    function getTotalBuyCount($c_basic_search, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
				  FROM 	vw_tb_user_adw va
			 LEFT JOIN  tb_admin a ON va.recomm_seq = a.adm_sq
			     WHERE  adw_type = 'D'";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
        }

        if($c_search_type != "" && $c_search_str != "") {
            if ($c_search_type == "va.adw_state") {
                if ($c_search_str == "대기") {
                    $c_search_str = "W";
                } else if ($c_search_str == "승인") {
                    $c_search_str = "S";
                } else if ($c_search_str == "취소") {
                    $c_search_str = "C";
                }
            }
            $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        } else if ($c_search_type != "" && $c_search_str1 != "" && $c_search_str2 != "") {
            $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 구매 관련 목록 데이터
    function getTotalBuyData($c_start, $c_limit, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2, $c_level) {
        $md_row = null;
        $sql = "SELECT  va.seq, va.user_seq, va.uid, va.adw_type, va.bank_code, va.bank_name, va.bank_num, va.account, va.amount, va.coin_amount, va.adw_bank, va.adw_state, va.ins_dttm, va.upt_dttm, va.adm_sq, va.unick, va.recomm_seq, a.adm_nnm
                  FROM 	vw_tb_user_adw va
             LEFT JOIN  tb_admin a ON va.recomm_seq = a.adm_sq
                 WHERE  adw_type = 'D'";

        if ($c_search_type != "") {
            if ($c_search_str1 != "" && $c_search_str2 != "") {
                $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
            } else if($c_search_str != "") {
                if ($c_search_type == "va.adw_state") {
                    if ($c_search_str == "대기") {
                        $c_search_str = "W";
                    } else if ($c_search_str == "성공") {
                        $c_search_str = "S";
                    } else if ($c_search_str == "취소") {
                        $c_search_str = "C";
                    }
                }
                $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
            }
        }

        if ($c_level != "") {
            $sql = $sql.$c_level;
        }

        $sql = $sql." ORDER BY va.seq DESC LIMIT ".$c_start.", ".$c_limit;

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 회원 구매/판매 내역
    function getDealData($c_data){
        $md_row = null;
        $sql = "SELECT 	seq, user_seq, bank_name, uid, adw_type, bank_code, bank_num, account, amount, coin_amount,  adw_state, ins_dttm, upt_dttm, adm_sq
				  FROM 	tb_user_adw 
				 WHERE  user_seq = ?";
        $md_row = $this->db->query($sql, array($c_data))->result_array();
        return $md_row;
    }
    // # - 해당 구매/판매 내역 상세
    function getAdwData($c_data){
        $md_row = null;
        $sql = "SELECT  seq, user_seq, bank_name, uid, adw_type, bank_code, bank_num, account, amount, coin_amount,  adw_state, ins_dttm, upt_dttm, adm_sq
				  FROM 	tb_user_adw
				 WHERE  seq = ?";

        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 전체 구매 대기자 수 */
    function getBuyWaitingData() {
        $md_row = null;
        $sql = "SELECT  count(*) as total_count
                  FROM 	vw_tb_user_adw
                 WHERE  adw_type='D'
                   AND  adw_state = 'W'";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row["total_count"];
    }



    /*
     *  관리자 수수료 수익 (현재 불필요)
     * */
    function getTotalRecommFee($c_data) {
        $md_row = null;
        $sql = "SELECT 	adm_id, withdraw_fee_total , deposit_fee_total
                  FROM  avw_admin_fee_total
                 WHERE 	adm_sq = ? 
                   AND  adm_level = '3'";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    function getDailyRecommFee($c_data) {
        $md_row = null;
        $sql = "SELECT 	 *
                  FROM  tb_admin_fee_daily
                 WHERE 	adm_sq = ? 
                   AND  this_dt BETWEEN DATE_ADD(NOW(),INTERVAL -1 WEEK ) AND NOW();";
        $md_row = $this->db->query($sql, array($c_data))->result_array();
        return $md_row;
    }
    function getTodayRecommFee($c_data) {
        $md_row = null;
        $sql = "SELECT 	adm_id, withdraw_fee , deposit_fee
                  FROM  avw_admin_fee_today
                 WHERE 	adm_sq = ? 
                   AND  adm_level = '3';";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    function getWeekRecommFee($c_data) {
        $md_row = null;
        $sql = "SELECT 	adm_id, withdraw_fee_week , deposit_fee_week
                  FROM  avw_admin_fee_week
                 WHERE 	adm_sq = ? 
                   AND  adm_level = '3';";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    function getMonthRecommFee($c_data) {
        $md_row = null;
        $sql = "SELECT 	adm_id, withdraw_fee_month , deposit_fee_month
                  FROM  avw_admin_fee_month
                 WHERE 	adm_sq = ? 
                   AND  adm_level = '3';";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }

    /*
     *   관리자 로그
     * */
    // # - 관리자 활동 내역 - 레벨
    function getAdminLogDataList($c_data) {
        $md_row = null;
        $sql = "SELECT  seq, adm_sq, act_code, act_result, log_ip, log_dttm, cd_group, cd_desc, adm_level, adm_id
                  FROM  avw_admin_log
                 WHERE  adm_level >= ?
                   AND  adm_level NOT IN ('0');";
        $md_row = $this->db->query($sql, array($c_data))->result_array();
        return $md_row;
    }
    // # - 관리자 로그
    function setAdminLog($c_data){
        $md_row = null;
        $sql = "INSERT INTO tb_admin_log (adm_sq, act_code, act_result, log_ip, log_dttm) 
                     VALUES (?, ?, ?, ?, NOW())";
        $md_row = $this->db->query($sql,array($c_data["adm_sq"], $c_data["act_code"], $c_data["act_result"], $c_data["log_ip"]));
        return $md_row;
    }
    // # - 관리자 활동 내역 로그 목록 수 */
    function getTotalLogCount($c_basic_search, $c_search_type, $c_search_str){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
				  FROM 	avw_admin_log
				  ";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }else{
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." WHERE ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 관리자 활동 내역 로그 목록 */
    function getAdminLogData($c_add_query){
        $md_row = null;
        $sql = "SELECT seq, adm_sq, act_code, act_result, log_ip, log_dttm, cd_group, cd_desc, adm_id, adm_nnm, adm_level
				  FROM avw_admin_log
				  ";
        if($c_add_query != ""){
            $sql = $sql.$c_add_query;
        }else{
            $sql = $sql." LIMIT 0,20";
        }

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    /* 관리자 코드 - 미사용 */
    function getCodeData(){
        $md_row = null;
        $sql = "SELECT cd_no, cd_group, cd_desc
				  FROM tb_code
				  ";

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }



    /*
     *      sp list
     * */
    // #1 - 구매 승인 call
    function setBuyChange($c_data) {
        $md_row = null;
        $sql = "CALL sp_set_deposit(?, ?, ?, UUID(), ?)";
        $md_row = $this->db->query($sql, array($c_data["adw_seq"], $c_data["adm_sq"], $c_data["adw_state"], $c_data["log_ip"]));
        if(is_object($md_row)){
            $res = $md_row->result();
            $md_row->next_result();
            $md_row->free_result();
        }else{
            $res = array();
            $res[0]["result"] = -100;
            $res[0]["fund_seq"] = -100;
            $res[0] = (object) $res[0];
        }
        return $res[0];
    }




    /*
     *      회원 정보
     * */
    // # - 회원 닉네임 중복 검사
    function getUserInfoNick($c_data){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) AS cnt, uid, unick, phone
                  FROM  tb_user
                 WHERE 	unick = ?
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 회원 폰번호 중복 검사
    function getUserInfoPhone($c_data){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) AS cnt, uid, unick, phone
                  FROM  tb_user
                 WHERE 	phone = ?
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 전체 회원 수
    function getUserTotalCount(){
        $md_row = null;
        $sql = "SELECT COUNT(*) AS total_cnt FROM tb_user";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row["total_cnt"];
    }
    // # - 전체 회원 수
    function getTotalUserCount($c_basic_search, $c_search_type, $c_search_str){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
				  FROM 	tb_user u
			 LEFT JOIN  tb_user_wallet uw ON u.seq = uw.user_seq";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }else{
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." WHERE ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 회원 전체 목록
    function getUserData(){
        $md_row = null;
        $sql = "SELECT 	*
				  FROM 	vw_tb_user";
        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 회원 정보
    function getUserDataDetail($c_data){
        $md_row = null;
        $sql = "SELECT 	count(*) as user_cnt, vu.seq, vu.uid, vu.unick, vu.lock_amount , vu.send_yn, DATE_FORMAT(vu.lock_dday,'%Y-%m-%d') AS lock_dday,
                        vu.join_status, vu.join_ip, vu.temp_pw, vu.activation_code, vu.market_id, vu.app_ver, 
                        vu.oneid, vu.push_yn, DATE_FORMAT(vu.join_dttm,'%Y-%m-%d') AS join_dttm, DATE_FORMAT(vu.upt_dttm,'%Y-%m-%d') AS upt_dttm, vu.wallet_addr , vu.balance_bnb, vu.balance_hype, vu.phone_country, vu.phone, vu.recomm_seq, ua.bank_name, ua.bank_num, ua.account
                  FROM 	vw_tb_user vu
             LEFT JOIN  tb_user_adw ua ON vu.seq = ua.user_seq
                 WHERE	vu.seq = ?
                 LIMIT 	0, 1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 전체 회원 수
    function getTotalMemberCount($c_basic_search, $c_search_type, $c_search_str){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as membercount
				  FROM 	vw_tb_user vu";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }else{
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["membercount"];
    }
    // # - 전체 회원 데이터
    function getTotalMemberData($c_start, $c_limit, $c_search_type, $c_search_str, $c_level) {
        $md_row = null;
        $sql = "SELECT vu.seq, vu.uid, vu.upw, vu.secure_num, vu.unick, vu.join_status, vu.join_ip, vu.temp_pw, vu.activation_code, vu.market_id, vu.app_ver, vu.phone, vu.phone_country,
                    vu.cur, vu.lang, vu.oneid, vu.push_yn, vu.fund_yn, vu.app_key, vu.temp_app_key, vu.last_login_dttm, vu.m_code, vu.recomm_seq, DATE_FORMAT(vu.join_dttm,'%Y-%m-%d') AS join_dttm,  
                    vu.upt_dttm, vu.del_dttm, vu.wallet_addr, vu.balance_hype, vu.approch, uw.deposit_amount, uw.withdraw_amount, 
                    uw.lock_amount, uw.send_yn, vu.adm_nnm, a.adm_nnm, DATE_FORMAT(vu.lock_dday,'%Y-%m-%d') AS lock_dday
				  FROM 	vw_tb_user vu
             LEFT JOIN tb_admin a ON vu.recomm_seq = a.adm_sq
             LEFT JOIN tb_user_wallet uw ON vu.seq = uw.user_seq
				  ";

        if ($c_search_type != "") {
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." WHERE ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        if ($c_level != "") {
            $sql = $sql.$c_level;
        }

        $sql = $sql." ORDER BY vu.seq DESC LIMIT ".$c_start.", ".$c_limit;

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 회원 등록 검사 */
    function getUserInfoId($c_data){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) AS cnt, uid, unick, phone
                  FROM  tb_user
                 WHERE 	uid = ?
                 LIMIT 	0,1;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 최근 가입 회원
    function getUserDataRecent(){
        $md_row = null;
        $sql = "SELECT 	u.seq, u.uid, u.upw, u.unick, u.join_status, u.join_ip, u.temp_pw, 
                        u.activation_code, u.market_id, u.app_ver, u.oneid, u.push_yn, u.join_dttm, u.upt_dttm ,
						uw.balance , uw.wallet_addr 
				  FROM 	tb_user u
			 LEFT JOIN  tb_user_wallet uw ON u.seq = uw.user_seq 
				 WHERE  u.join_status = 'S' 
				   AND  uw.symbol = '".COIN."'
              ORDER by  join_dttm DESC 
				 LIMIT  0,5";
        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 추천인관리자 통해서 가입한 회원
    function getUserDataRecentLevel($c_data){
        $md_row = null;
        $sql = "SELECT 	u.seq, u.uid, u.upw, u.unick, u.join_status, u.join_ip, u.temp_pw, 
                        u.activation_code, u.market_id, u.app_ver, u.oneid, u.push_yn, u.join_dttm, u.upt_dttm ,
						uw.balance , uw.wallet_addr 
				  FROM 	tb_user u
			 LEFT JOIN  tb_user_wallet uw ON u.seq = uw.user_seq 
				 WHERE  u.join_status = 'S' 
				   AND  uw.symbol = '".COIN."' 
				   AND  u.recomm_seq = ?
              ORDER by  join_dttm DESC 
				 LIMIT  0,5";
        $md_row = $this->db->query($sql, array($c_data))->result_array();
        return $md_row;
    }
    /* 전체 회원 정보 목록 */
    function getRecommendDataList($c_add_query){
        $md_row = null;
        $sql = "SELECT seq, uid, upw, secure_num, unick, join_status, join_ip, temp_pw, activation_code, market_id, app_ver, phone, phone_country,
                    cur, lang, oneid, push_yn, fund_yn, app_key, temp_app_key, last_login_dttm, m_code, recomm_seq, join_dttm,  
                    upt_dttm, del_dttm, wallet_addr, bank_name, bank_num, account, balance, approch, deposit_amount, withdraw_amount, 
                    lock_amount, send_yn, adm_nnm
				  FROM 	vw_tb_user
				  ";

        if ($c_add_query != ""){
            $sql = $sql.$c_add_query;
        }

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 임시 비밀번호 발급
    function set_UserInfo_temppw($c_data){
        $md_row = null;
        $sql = "UPDATE 	tb_user
                   SET 	temp_pw = ?, upt_dttm = NOW()
                 WHERE 	seq = ?";
        $md_row = $this->db->query($sql,array($c_data["temp_pw"], $c_data["seq"]));
        return $md_row;
    }
    // # - 보안번호 발급
    function set_UserInfo_secure($c_data){
        $md_row = null;
        $sql = "UPDATE 	tb_user
                   SET 	secure_num = ?, upt_dttm = NOW()
                 WHERE 	seq = ?";
        $md_row = $this->db->query($sql,array($c_data["temp_secure_num"], $c_data["seq"]));
        return $md_row;
    }
    // # - appkey 초기화 */
    function resetAppKey($c_data){
        $md_row = null;
        $sql = "UPDATE tb_user 
                   SET app_key = null, upt_dttm = now()
                 WHERE seq = ?;";
        $md_row = $this->db->query($sql,array($c_data["seq"]));
        return $md_row;
    }
    // 회원 정보 변경
    function setUserChange($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_user 
                   SET unick = ?, phone = ?, upt_dttm = NOW()
                 WHERE seq = ?";
        $md_row = $this->db->query($sql,array($c_data["unick"], $c_data["uphone"], $c_data["seq"]));

        return $md_row;
    }
    // 회원 비활성화
    function setUserStatusChange($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_user set join_status = ?, upt_dttm = NOW()
                WHERE seq = ?";
        $md_row = $this->db->query($sql,array($c_data["status"], $c_data["seq"]));

        return $md_row;
    }
    // # - 미사용 예상 */
    function getUserBalanceOrderData($c_data){
        $md_row = null;
        $sql = "SELECT 	u.uid, u.unick, uw.wallet_addr, uw.balance, uw.lock_amount, CAST(uw.balance * (?/100) AS DECIMAL(24,8)) AS preview
                  FROM 	tb_user u 
             LEFT JOIN  tb_user_wallet uw ON u.seq = uw.user_seq
                 WHERE  u.join_status = 'S'
              ORDER BY  uw.balance DESC 
                 LIMIT  0,1000";
        $md_row = $this->db->query($sql,$c_data)->result_array();
        return $md_row;
    }
    /* 회원 등록 */
    /*    function setUser($c_data){
            $md_row = null;

            $sql = "CALL sp_set_member(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?)";
            $md_row = $this->db->query($sql, array($c_data["uid"],$c_data["upw"],$c_data["usecure"],$c_data["unick"], $c_data["nation"], $c_data["uphone"],
                $c_data["join_ip"], $c_data["activation_code"], $c_data["lang"], $c_data["app_key"], $c_data["m_code"], $c_data["symbol"], $c_data["svr_id"], $c_data["p_user_seq"], $c_data["wallet_addr"], $c_data["mnemonic"],  $c_data["private_key"]));

            if(is_object($md_row)){
                $res = $md_row->result();
                $md_row->next_result();
                $md_row->free_result();
            }else{
                $res = array();
                $res[0]["result"] = -100;
                $res[0]["fund_seq"] = -100;
                $res[0] = (object) $res[0];
            }
            //return json_encode($res,true);
            return $res[0];
        }*/



    /*
     *    회원 지갑 정보
     * */
    // #1 - 회원 전송, LOCK 상태 유
    function setUserSendStatus($c_data){
        $md_row = null;
        $sql = "UPDATE tb_user_wallet 
                   SET send_yn = ?, lock_dday = ?, upt_dttm = now() 
                 WHERE user_seq = ?;";
        $md_row = $this->db->query($sql,array($c_data["i_sendyn"], $c_data["i_date"], $c_data["seq"]));
        return $md_row;
    }
    // # - 회원 전송, LOCK 상태 무
    function setUserSendStatusUnlock($c_data){
        $md_row = null;
        $sql = "UPDATE tb_user_wallet 
                   SET send_yn = 'Y', lock_amount = 0, lock_dday = null, upt_dttm = now() 
                 WHERE user_seq = ?;";
        $md_row = $this->db->query($sql,$c_data["seq"]);
        return $md_row;
    }
    /* 전체 락업 - 미사용 */
    function setAllUserLockup($c_data){
        $md_row = null;
        $sql = "UPDATE tb_user_wallet uw
             LEFT JOIN tb_user u ON uw.user_seq = u.seq
				   SET uw.lock_amount = (uw.balance * (? / 100)),
					   uw.send_yn = 'N',
					   uw.upt_dttm = NOW()
				 WHERE u.join_status = 'S'";
        $md_row = $this->db->query($sql,$c_data);
        return $md_row;
    }
    // # - lock amount
    function setUserLockAmount($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_user_wallet 
                   SET lock_amount = ?, upt_dttm = now() 
                 WHERE user_seq = ?;";
        $md_row = $this->db->query($sql, array($c_data["i_lockamount"], $c_data["seq"]));
        return $md_row;
    }

    /* 시세 가져오기 */
    function get_ExchangeInfo(){
        $md_row = null;
        $sql = "SELECT symbol, svrid, balance, lock_amount, coin_usdt, coin_amount, exchange_amount
				  FROM vw_tb_user_wallet ";

        $md_row = $this->db->query($sql)->row_array();
        return $md_row;
    }
    /* 미사용 예상 */
    function getAllUserBalance(){
        $md_row = null;
        $sql = "SELECT  count(*), ifnull(SUM(uw.balance),0) AS all_balance, ifnull(SUM(uw.lock_amount),0) AS all_lock_amount
                  FROM 	tb_user_wallet uw 
             LEFT JOIN  tb_user w ON uw.user_seq = w.seq
                 WHERE  w.join_status = 'S'
				   AND  uw.symbol = '".COIN."'";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row;
    }



    /*
     *   회원 LOCK HISTORY
     * */
    // # - 락업 정보 저장
    function setUserSendStatusLog($c_data){
        $md_row = null;
        $sql = "INSERT INTO tb_lock_history (adm_sq, user_seq, uid, send_yn, lock_dday, lock_desc, ins_dttm) 
                    SELECT 	?, seq, uid, ?, ?, ?, NOW()
                      FROM 	tb_user
                     WHERE 	seq = ?";
        $md_row = $this->db->query($sql,array($c_data["adm_sq"], $c_data["i_sendyn"], $c_data["i_date"], $c_data["i_sendlockdesc"], $c_data["seq"]));
        return $md_row;
    }
    // # - 락업 목록 - 미사용
    function getLockData($c_add_query){
        $md_row = null;
        $sql = "SELECT 	a.seq, a.uid, a.lock_amount, a.lock_desc, a.ins_dttm,
                        b.adm_id, b.adm_nnm, c.unick, d.cd_desc, a.send_yn, a.user_seq
                  FROM 	tb_lock_history a
             LEFT JOIN  tb_admin b ON a.adm_sq = b.adm_sq
             LEFT JOIN  tb_user c ON a.user_seq = c.seq
             LEFT JOIN  tb_code d ON a.send_yn = d.cd_nm 
                   AND  d.cd_group = 'tb_lock_history'";

        if($c_add_query != ""){
            $sql = $sql.$c_add_query;
        }else{
            $sql = $sql." LIMIT 0,20";
        }

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    function getLockHistoryFromUserSeq($c_data){
        $md_row = null;
        $sql = "SELECT 	lock_dday, lock_desc
                  FROM 	tb_lock_history
                 WHERE  user_seq = ? 
              ORDER BY  ins_dttm DESC 
                 LIMIT  0, 1";
        $md_row = $this->db->query($sql, $c_data)->row_array();
        return $md_row;
    }


    /*
     *    회원 로그
     * */
    // # - 회원 로그 목록 - 미사용
    function getLogData($c_add_query){
        $md_row = null;
        $sql = "SELECT 	a.seq, b.adm_id, b.adm_nnm, a.log_cd, a.act_ip, a.ins_dttm, c.cd_desc
                  FROM 	tb_logs a
             LEFT JOIN  tb_admin b ON a.adm_sq = b.adm_sq
             LEFT JOIN  tb_code c ON a.log_cd = c.cd_nm 
                   AND  c.cd_group = 'tb_logs'";

        if($c_add_query != ""){
            $sql = $sql.$c_add_query;
        }else{
            $sql = $sql." LIMIT 0,20";
        }
        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }


    /*
     *   전송내역
     * */
    // # - 전체 전송 내역 수
    function getTotalTransactionCount($c_basic_search, $c_search_type, $c_search_str){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
				  FROM 	tb_txinfo2";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }else{
            if($c_search_type != "" && $c_search_str != "") $sql = $sql." WHERE ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 전체 받기 수 */
    function getTotalReceiveCount($c_basic_search, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
				  FROM 	tb_txinfo2
				 WHERE  category= 'receive'";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
        }

        if ($c_search_str1 != "" && $c_search_str2 != "") {
            $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
        } else if($c_search_type != "" && $c_search_str != "" && $c_search_type != "unick") {
            $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 전체 보내기 수 */
    function getTotalSendCount($c_basic_search, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2){
        $md_row = null;
        $sql = "SELECT 	COUNT(*) as recordcount
				  FROM 	tb_txinfo2
			     WHERE  category= 'send'";

        if($c_basic_search != ""){
            $sql = $sql.$c_basic_search;
        }

        if ($c_search_str1 != "" && $c_search_str2 != "") {
            $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
        } else if($c_search_type != "" && $c_search_str != "" && $c_search_type != "unick") {
            $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
        }

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["recordcount"];
    }
    // # - 전체 받기 데이터 */
    function getTotalReceiveData($c_start, $c_limit, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2, $c_level) {
        $md_row = null;
        $sql = "SELECT 	* 
				  FROM 	tb_txinfo2
				  WHERE category='receive'";

        if ($c_search_type != "") {
            if ($c_search_str1 != "" && $c_search_str2 != "") {
                $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
            } else if($c_search_type != "" && $c_search_str != "" && $c_search_type != "unick") {
                $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
            }
        }

        if ($c_level != "") {
            $sql = $sql.$c_level;
        }

        $sql = $sql." ORDER BY seq DESC LIMIT ".$c_start.", ".$c_limit;

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 전체 보내기 데이터 */
    function getTotalSendData($c_start, $c_limit, $c_search_type, $c_search_str, $c_search_str1, $c_search_str2, $c_level) {
        $md_row = null;
        $sql = "SELECT 	* 
				  FROM 	tb_txinfo2
				 WHERE  category='send'";

        if ($c_search_type != "") {
            if ($c_search_str1 != "" && $c_search_str2 != "") {
                $sql = $sql." AND ".$c_search_type." BETWEEN '".$c_search_str1."' AND DATE_ADD('".$c_search_str2."', INTERVAL 1 DAY)";
            } else if($c_search_type != "" && $c_search_str != "" && $c_search_type != "unick") {
                $sql = $sql." AND ".$c_search_type." LIKE '%".$c_search_str."%'";
            }
        }

        if ($c_level != "") {
            $sql = $sql.$c_level;
        }

        $sql = $sql." ORDER BY seq DESC LIMIT ".$c_start.", ".$c_limit;

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 전체 전송 내역 */
    function getTransactionData($start, $limit){
        $md_row = null;
        $sql = "SELECT   *
                  FROM   tb_txinfo2 ";

        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    /// # - 추천인관리자 - 해당 회원 전체 전송 내역 */
    function getTransactionLevelData($start, $limit, $c_add_query){
        $md_row = null;
        $sql = "SELECT   *
                  FROM  tb_txinfo2
                 WHERE  (rrecomm_seq = ? OR srecomm_seq = ?) 
                   AND  category = 'send'";

        if ($c_add_query != "") {
            $sql = $sql." LIMIT ".$start." ,".$limit;
        } else{
            $sql = $sql." LIMIT 0, 10";
        }

        $md_row = $this->db->query($sql, array($c_add_query, $c_add_query))->result_array();
        return $md_row;
    }
    // # - 회원의 전송 내역 */
    function getUserTransactionData($start, $limit, $c_add_query){
        $md_row = null;
        $sql = "SELECT   *
                  FROM   tb_txinfo2
                 WHERE  ( ruseq = ? AND category = 'receive' OR suseq = ? AND category = 'send') ";

        if ($c_add_query != "") {
            $sql = $sql." LIMIT ".$start." ,".$limit;
        } else{
            $sql = $sql." LIMIT 0, 10000";
        }

        $md_row = $this->db->query($sql, array($c_add_query, $c_add_query))->result_array();
        return $md_row;
    }


    /*
     *     Dashboard
     * */

    /* 최근 7일간 입출금 */
    function get_weekly_inout(){
        $md_row = null;
        $sql = "SELECT IFNULL(SUM(amount),0) AS total_adw 
                 FROM tb_user_adw 
                WHERE adw_state = 'S' 
                  AND DATEDIFF(NOW(), ins_dttm )  < 8
				";

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["total_adw"];
    }

    /* 최근 7일간 전송 */
    function get_weekly_transaction(){
        $md_row = null;
        $sql = "SELECT IFNULL(SUM(amount),0) AS total_txinfo 
                  FROM tb_txinfo2 
                 WHERE state = 'SD' 
                   AND DATEDIFF(NOW(), trans_time)  < 8
				";

        $md_row = $this->db->query($sql)->row_array();
        return $md_row["total_txinfo"];
    }


    /*
     *      문의하기
     * */
    // #1 - 문의하기 상세 팝업
    function getContactDetail($c_data) {
        $md_row = null;
        $sql = "SELECT b.seq, b.adm_sq, b.user_seq, b.bbs_email, b.bbs_subject, b.bbs_content, b.bbs_comment, b.bbs_type, b.state, b.upt_dttm, b.ins_dttm, a.adm_nnm, u.unick
				  FROM tb_bbs b
             LEFT JOIN tb_admin a ON b.adm_sq = a.adm_sq
             LEFT JOIN tb_user u  ON b.user_seq = u.seq
                 WHERE b.seq = ?;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }
    // # - 문의하기 목록 */
    function getContactList() {
        $md_row = null;
        $sql = "SELECT  b.seq, b.adm_sq, b.user_seq, b.bbs_email, b.bbs_subject, b.bbs_content, b.bbs_comment, b.bbs_type, b.state, b.upt_dttm, b.ins_dttm, a.adm_nnm, u.unick
				  FROM 	tb_bbs b
             LEFT JOIN  tb_admin a ON b.adm_sq = a.adm_sq
             LEFT JOIN  tb_user u ON b.user_seq = u.seq;";
        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 추천인관리자 문의하기 목록 */
    function getContactLevelList($c_data) {
        $md_row = null;
        $sql = "SELECT  b.seq, b.adm_sq, b.user_seq, b.bbs_email, b.bbs_subject, b.bbs_content, b.bbs_comment, b.bbs_type, b.state, b.upt_dttm, b.ins_dttm, a.adm_nnm, u.unick, a.adm_level, u.recomm_seq
				  FROM 	tb_bbs b
             LEFT JOIN  tb_admin a ON b.adm_sq = a.adm_sq
             LEFT JOIN  tb_user u  ON b.user_seq = u.seq
                 WHERE  u.recomm_seq = ?;";
        $md_row = $this->db->query($sql, array($c_data))->result_array();
        return $md_row;
    }
    // # - 문의하기 미답변 수 */
    function getContactWaitingData() {
        $md_row = null;
        $sql = "SELECT  COUNT(*) AS total_count
                  FROM tb_bbs
                 WHERE bbs_comment IS NULL
                  ";
        $md_row = $this->db->query($sql)->row_array();
        return $md_row["total_count"];
    }
    // # - 문의하기 미답변 목록 */
    function getContactWaitList() {
        $md_row = null;
        $sql = "SELECT b.seq, b.adm_sq, b.user_seq, b.bbs_email, b.bbs_subject, b.bbs_content, b.bbs_comment, b.bbs_type, b.state, b.upt_dttm, b.ins_dttm,
                       a.adm_nnm, u.unick
				  FROM 	tb_bbs b
             LEFT JOIN tb_admin a ON b.adm_sq = a.adm_sq
             LEFT JOIN tb_user u  ON b.user_seq = u.seq
                 WHERE b.bbs_comment IS NULL;";
        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    // # - 문의하기 답변 */
    function setComment($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_bbs 
                   SET adm_sq = ?, bbs_comment = ?, upt_dttm = NOW()
                 WHERE seq = ?";
        $md_row = $this->db->query($sql,array($c_data["seq"], $c_data["comment"], $c_data["contact_seq"]));
        return $md_row;
    }


    /*
     *      은행코드
     * */
    // # - 은행코드에 대한 은행 정보 */
    function get_bankName($c_data) {
        $md_row = null;
        $sql = "SELECT bank_name, bank_code
				  FROM 	tb_bank
				 WHERE bank_code = ?;";
        $md_row = $this->db->query($sql, array($c_data))->row_array();
        return $md_row;
    }


    /*
     *      공지사항
     * */
    function getAllNoticeList() {
        $md_row = null;
        $sql = "SELECT n.seq, n.adm_sq, n.bbs_subject, n.bbs_content, n.bbs_type, n.use_yn, DATE_FORMAT(n.ins_dttm , '%Y-%m-%d') AS ins_dttm, DATE_FORMAT(n.upt_dttm , '%Y-%m-%d') AS upt_dttm, a.adm_nnm
                  FROM tb_notice n 
                  LEFT JOIN tb_admin a ON n.adm_sq = a.adm_sq";
        $md_row = $this->db->query($sql)->result_array();
        return $md_row;
    }
    function getNoticeDetail($c_data) {
        $md_row = null;
        $sql = "SELECT n.seq, n.adm_sq, n.bbs_subject, n.bbs_content, n.bbs_type, n.use_yn, DATE_FORMAT(n.ins_dttm , '%Y-%m-%d') AS ins_dttm, DATE_FORMAT(n.upt_dttm , '%Y-%m-%d') AS upt_dttm, a.adm_nnm
                  FROM tb_notice n 
                  LEFT JOIN tb_admin a ON n.adm_sq = a.adm_sq
                  WHERE n.seq = ?
                  ";
        $md_row = $this->db->query($sql, array($c_data))->rOW_array();
        return $md_row;
    }
    function setNoticeModify($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_notice 
                   SET adm_sq = ?, bbs_subject = ?, bbs_content = ?, upt_dttm = NOW()
                 WHERE seq = ?";
        $md_row = $this->db->query($sql,array($c_data["seq"], $c_data["title"], $c_data["content"], $c_data["notice_seq"]));
        return $md_row;
    }
    function setNoticeUseChange($c_data) {
        $md_row = null;
        $sql = "UPDATE tb_notice 
                   SET use_yn = ? 
                 WHERE seq = ?;";
        $md_row = $this->db->query($sql,array($c_data["use_yn"], $c_data["notice_seq"]));
        return $md_row;
    }
    function setNoticeWrite($c_data) {
        $md_row = null;
        $sql = "INSERT INTO tb_notice (adm_sq, bbs_subject, bbs_content, use_yn, ins_dttm) 
                     VALUES (?, ?, ?, 'Y', NOW())";
        $md_row = $this->db->query($sql,array($c_data["seq"], $c_data["title"], $c_data["content"]));
        return $md_row;
    }




} // End of Class n_quest_model

/* End of file n_quest_model.php */
/* Location: /application/models/n_quest_model.php */
