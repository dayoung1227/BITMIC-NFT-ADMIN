<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    var seq = "<?=$user_data["seq"]?>";
    var balance = "<?=$user_data["balance_hype"]?>";
    var lock_amount = "<?=str_replace(",","",$user_data["lock_amount"]);?>";
    var type  = "<?=$type;?>";

    var lock_dday = "<?=$user_data["lock_dday"]?>";
    var send_yn = "<?=$user_data["send_yn"]?>";

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        $('#datepicker').Zebra_DatePicker();
        var today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 4;
        const date = today.getDate();
        today = `${year}-${month >= 10 ? month : '0' + month}-${date >= 10 ? date : '0' + date}`;


        if($(".btn_sendcheck").is(":checked")){
            $(".i_sendlockdesc").attr('disabled', true);
            $("#datepicker").attr("disabled", true);
            $("#datepicker").val(lock_dday);
            $(".i_lockpercent").attr('disabled',false);
            $(".switch_slide").html('lock');
            var percent = parseFloat((lock_amount * 100) / balance);
            if (isNaN(percent)) {
                $(".i_lockpercent").val(0);
            } else {
                $(".i_lockpercent").val(percent);
            }
        }else{
            $(".i_sendlockdesc").attr('disabled', false);
            $("#datepicker").attr("disabled", false);
            $("#datepicker").val("");
            $(".switch_slide").html('');
            $(".i_lockpercent").val('0');
        }

        $(".btn_sendcheck").on("change",function(){
            if($(this).is(":checked")){
                $("#datepicker").attr("disabled", true);
                $("#datepicker").val(today);
                $(".switch_slide").html('lock');
            }else{
                $(".i_sendlockdesc").attr('disabled', false);
                $("#datepicker").attr("disabled", false);
                $("#datepicker").val("");
                $(".switch_slide").html('');
            }
        });

        $(".btn_copy").on("click", function(){
            var copy_address = new Clipboard(".btn_copy");
            notify_layer("Wallet Address Copied!");
        });


        $('.btn_backlist').on("click", function(){
            if (type == "user_list") {
                location.replace("/Main/member_list");
            } else if (type == "recomm_list") {
                location.replace("/Main/recommends_list");
            } else {
                history.back();
            }
        });

        $(".btn_lockamount").on('click', function() {
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("Lock 설정 권한이 없습니다.");
                return;
            }

            var i_lockamount = $(".i_lockamount").val();

            if (parseFloat(i_lockamount) != 0) {
                if(parseFloat(i_lockamount) < 0.0001){
                    alert_layer('Lock Amount는 최소 0.0001 이상입니다');
                    return;
                }
            }

            var params = {
                'seq' : seq,
                'i_lockamount' : i_lockamount
            };

            req(system_config, '/Ajax/setlockAmount', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){//login success

                        if(parseFloat(i_lockamount) != 0){
                            $('.curr_lockamount').val(i_lockamount);
                        }else{
                            $(".i_lockamount").val('0');
                            $(".i_lockpercent").val('0');
                        }

                        notify_layer("Lock Amount 수정하였습니다.");
                    }else{
                        alert_layer(rtn.err);
                    } //result if

                }

            });
        });

        $('.btn_sendyn').on("click", function(){
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("Lock 설정 권한이 없습니다.");
                return;
            }

            var i_date = $("#datepicker").val();
            var i_sendlockdesc = $(".i_sendlockdesc").val();


            var params = {
                'seq' : seq,
                'i_sendyn' : 'Y',
                'i_date' : i_date + ' 23:59:59',
                'i_sendlockdesc' : i_sendlockdesc
            };

            if($(".btn_sendcheck").is(":checked")){
                if (send_yn === 'N') {
                    params.i_sendlockdesc = '날짜변경';
                }
                params.i_sendyn = 'N';
                if(!validateStrLength(params.i_sendlockdesc, 4)){
                    alert_layer('사유를 입력해주세요');
                    return;
                }
            }

            //loading_layer_open();
            req(system_config, '/Ajax/setlockyn', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){//login success

                        if ($(".btn_sendcheck").is(":checked")){

                        } else {
                            $("#datepicker").val('');
                        }

                        notify_layer("Lock 수정하였습니다.");
                    }else{
                        alert_layer(rtn.err);
                    } //result if

                }

            });
        });

        $('.btn_sendmail_verify').on("click", function(){
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("권한이 없습니다.");
                return;
            }

            var params = {
                'seq' : seq
            };
            //loading_layer_open();
            req(system_config, '/Ajax/sendmail_verify', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){//login success
                        notify_layer("Success to Send Verification E-mail");
                    }else{
                        alert_layer(rtn.err);
                    } //result if

                }

            });
        });

        // 임시 비밀번호 발급
        $('.btn_sendmail_reset').on("click", function(){
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("임시 비밀번호 발급 권한이 없습니다.");
                return;
            }

            var params = {
                'seq' : seq
            };
            //loading_layer_open();
            req(system_config, '/Ajax/sendmail_reset', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){//login success
                        notify_layer("Success to assign temp password:<br><br>" + rtn.temp_pw);
                    }else{
                        alert_layer(rtn.err);
                    } //result if
                }
            });
        });

        // 임시 보안번호 발급
        $('.btn_sendsecure_reset').on("click", function(){
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("임시 보안번호 발급 권한이 없습니다.");
                return;
            }

            var params = {
                'seq' : seq
            };
            //loading_layer_open();
            req(system_config, '/Ajax/secure_num_reset', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){//login success
                        notify_layer("Success to assign temp secure number:<br><br>" + rtn.temp_secure_num);
                    }else{
                        alert_layer(rtn.err);
                    } //result if
                }
            });
        });


        $(".i_lockpercent").on("keyup", function () {
            var amount = $(".i_lockamount").val();
            var percent = $(".i_lockpercent").val();

            amount = parseFloat((balance * percent) / 100);
	        //amount.replace(',', '');
            $(".i_lockamount").val(amount);

        });

        $(".i_lockamount").on("keyup", function () {
            var amount = $(".i_lockamount").val();
            var percent = $(".i_lockpercent").val();

            percent = parseFloat((amount * 100) /balance);
            $(".i_lockpercent").val(percent);
        });

        $(".btn_change_status").on("click", function() {
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("비활성화 권한이 없습니다.");
                return;
            }

            var selected = $("#joinStatus option:selected").val();
            var status = $("#joinStatus option:selected").html();

            if (confirm('유저상태를 '+status+'로 변경하시겠습니까?')) {

                if(!is_clickact){
                    return false;
                }else{
                    is_clickact = false;
                }

                var params = {
                    'seq' : seq,
                    'join_status' : selected
                };

                loading_layer_open();
                req(system_config, '/Ajax/changeJoinStatus', {
                    json : true,
                    data:params,
                    callback:function(rtn) {
                        loading_layer_close();
                        is_clickact = true;
                        if(rtn.result > 0){//login success
                            notify_layer("Success To Change Status");
                        }else{
                            alert_layer(rtn.err);
                        } //result if
                    }
                });
            }
        });

        $(".btn_reset_app_key").on("click", function() {
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("등록된 기기정보를 초기화 권한이 없습니다.");
                return;
            }

            if (confirm('등록된 기기정보를 초기화하시겠습니까?')) {

                if(!is_clickact){
                    return false;
                }else{
                    is_clickact = false;
                }

                var params = {
                    'seq' : seq
                };

                loading_layer_open();
                req(system_config, '/Ajax/resetAppKey', {
                    json : true,
                    data:params,
                    callback:function(rtn) {
                        loading_layer_close();
                        is_clickact = true;
                        if(rtn.result > 0){//login success
                            notify_layer("Success To Reset Device Info");
                        }else{
                            alert_layer(rtn.err);
                        } //result if
                    }
                });
            }
        });

        // 회원 비활성화 변경
        $(".btn_nLogin").on("click", function() {
            var level = "<?=$level?>";

            if (level == 2 || level == 3) {
                alert_layer("비활성화 권한이 없습니다.");
                return;
            }

            var params = {
                "user_seq" : seq,
                "status" : "<?=$user_data["join_status"]?>"
            };

            req(system_config, '/Ajax/update_userLogin_ajax', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){
                        notify_layer("회원이 비활성화 되었습니다.");

                        setTimeout( function () {
                            location.replace("/Main/recommends_list");
                        }, 1000 );

                    }else{
                        notify_layer(rtn.err);
                    } //result if
                }
            });

        });

        // 회원 정보 변경
        $("#btn_change").on("click", function() {
            var regex = /\s/gi;
            var c_nick = $("#c_nick").val();
            c_nick = c_nick.replace(regex, '');
            var c_phone = $("#c_phone").val();
            c_phone = c_phone.replace(regex, '');
            c_phone = c_phone.replace(/[^0-9]/g, '');

            var level = "<?=$level?>";

            if (level != 0) {
                alert_layer("회원정보 수정 권한이 없습니다.");
                return;
            }

            if (c_nick == "" || c_phone == "") {
                alert_layer("변경할 회원정보를 입력해주세요.");
                return;
            }

            var params = {
                "user_seq" : seq,
                "c_nick" : c_nick,
                "c_phone" : c_phone
            };

            //loading_layer_open();
            req(system_config, '/Ajax/update_userinfo_ajax', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){
                        notify_layer("회원 정보가 수정되었습니다.");

                        setTimeout( function () {
                            location.replace("/Main/recommends_list");
                        }, 1000 );


                    }else{
                        notify_layer(rtn.err);
                    } //result if
                }
            });
        });
    });
</script>



<div class="row page_title">
    <div class="col-sm-6">
        <h4 class="row">
            <button class="btn btn-outline-primary btn_backlist pt-0 pb-0 pl-2 pr-2 mr-3"><i class="far fa-arrow-left"></i></button><?=$mnu;?>
        </h4>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="card mb-4">
            <div class="card-header">회원 기본정보</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>닉네임</label>
                        <?php if ($level == 0) { ?>
                            <input type="text" class="form-control" id="c_nick"  placeholder="닉네임은 2글자 이상 입력해주세요." value="<?=$user_data["unick"];?>" required="" autofocus=""  aria-disabled="true">
                        <?php } else { ?>
                            <input type="text" class="form-control" value="<?=$user_data["unick"];?>" required="" autofocus="" disabled aria-disabled="true">
                        <?php }?>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" value="<?=$user_data["uid"];?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>핸드폰</label>
                        <?php if ($level == 0) { ?>
                            <input type="text" id="c_phone" class="form-control" placeholder="핸드폰 번호를 입력해주세요." value="<?=$user_data["phone"];?>" required="" autofocus="" aria-disabled="true">
                        <?php } else { ?>
                            <input type="text" class="form-control" value="<?="+".$user_data["phone_country"]." ) ".$user_data["phone"];?>" required="" autofocus="" disabled aria-disabled="true">
                        <?php }?>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>추천인</label>
                        <input type="text" class="form-control" value="<?=$user_data["recomm_seq"] ? $user_data["recomm"]  : "-" ;?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>가입일시</label>
                        <input type="text" class="form-control" value="<?=$user_data["join_dttm"];?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>회원정보 수정일시</label>
                        <input type="text" class="form-control" value="<?=$user_data["upt_dttm"] ? $user_data["upt_dttm"] : '-';?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>보유 <?=COIN?></label>
                        <input type="text" class="form-control" value="<?=$user_data["balance_hype"] != 0 ? (double)((double) number_format($user_data["balance_hype"],15, '.', ''))." ".COIN : "-";?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>보유 <?=BNB?></label>
                        <input type="text" class="form-control" value="<?=$user_data["balance_bnb"] != 0 ? (double)((double) number_format($user_data["balance_bnb"],15, '.', ''))." ".BNB : "-";?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Lock Y/N</label>
                        <input type="text" class="form-control" value="<?=$user_data["send_yn_dp"];?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Lock 수량</label>
                        <input type="text" class="form-control curr_lockamount" value="<?=$user_data["lock_amount"] != 0 ? (double)((double) number_format($user_data["lock_amount"],15, '.', ''))." ".COIN : "-";?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Lock D-day</label>
                        <input type="text" class="form-control curr_lockamount" value="<?=$user_data["lock_dday"] ? $user_data["lock_dday"] : '-';?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>지갑주소</label>
                        <div class="row w-100 m-0">
                            <input type="text" class="form-control w-75" value="<?=$user_data["wallet_addr"];?>" required="" autofocus="" disabled aria-disabled="true" data-toggle="tooltip" title="" data-original-title="Copy to Clipboard" data-clipboard-text="<?=$user_data["wallet_addr"]?>">
                            <span class="btn btn_copy btn-default pl-1 pr-1 w-25 pt-2 pb-1" data-toggle="tooltip" title="" data-original-title="Copy to Clipboard" data-clipboard-text="<?=$user_data["wallet_addr"]?>">Copy</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>가입상태</label>
                        <input type="text" class="form-control" value="<?=$user_data["join_status"] === 'W' ? '인증대기' : ($user_data["join_status"] === 'S' ? '인증완료' : '로그인불가');?>" required="" autofocus="" disabled aria-disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Security Update</label>
                        <div class="col-md-12 pl-0 pr-0">
                            <button class="btn btn-default btn_sendmail_reset mb-2 mr-2 pt-2 pb-2">임시비밀번호발급</button>
                            <button class="btn btn-default btn_sendsecure_reset mb-2 mr-2 pt-2 pb-2">임시보안번호발급</button>
                            <?php if($user_data["join_status"] === 'W'){ ?>
                                <button class="btn btn_sendmail_verify btn-default mb-2 pt-2 pb-2">인증메일발송</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Reset Device Info</label>
                        <div class="col-md-12 pl-0 pr-0">
                            <span class="btn btn-default pt-2 pb-2 btn_reset_app_key">등록된 기기 초기화</span>
                        </div>
                    </div>
                </div>
                <?php if ($level == 0) { ?>
                    <div class="col-xl-6 col-lg-12  col-sm-12">
                        <div class="form-group">
                            <label>회원정보 수정 ( 닉네임, 휴대폰번호만 수정가능합니다. )</label>
                            <div class="col-md-12 pl-0 pr-0">
                                <span class="btn btn-default pt-2 pb-2" id="btn_change">회원정보 수정</span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($level == 0 || $level == 1) { ?>
                    <div class="col-xl-6 col-lg-12  col-sm-12">
                        <div class="form-group">
                            <label>회원 비활성화 ( 비활성화된 회원은 로그인이 불가합니다. )</label>
                            <div class="col-md-12 pl-0 pr-0">
                                <?php if ($user_data["join_status"] == "S") { ?>
                                    <span class="btn btn-red pt-2 pb-2 btn_nLogin">회원 비활성화</span>
                                <?php } else { ?>
                                    <span class="btn btn-info pt-2 pb-2 btn_nLogin">회원 활성화</span>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if ($level == 0 || $level == 1) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card  mb-3">
                <div class="card-header"><?=COIN?> Lock Setting</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-12">
                            <div class="switch_box float-right pl-3">
                                <label class="switch">
                                    <input class="switch_checkbox btn_sendcheck" type="checkbox" <?php if($user_data["send_yn"] == 'N') echo "checked"?>>
                                    <span class="switch_slide"></span>
                                </label>
                            </div>
                            <h6>Send Lock Information</h6>
                            <p class="small text-muted ">옆의 버튼을 눌러서 발송 락을 잠금 혹은 해제하세요.</p>
                            <h6 style="bold; padding-top: 20px;">< 주의사항 ></h6>
                            <p style="color: red !important;" class="small text-muted">Lock 설정은 <?=COIN?>만 가능합니다. </br> Lock 설정한 경우 Lock 설정된 날짜 동안 전송이 불가능합니다.</p>
                        </div>
                        <div class="col-xl-3 col-lg-12 mgB10">
                            <textarea class="form-control i_sendlockdesc" placeholder="Lock 사유를 입력해주세요" style="height: 100%"><?=$lock_data ? $lock_data["lock_desc"]: ''?></textarea>
                        </div>
                        <div class="col-xl-5 col-lg-12 float-right">

                            <input type="text" id="datepicker">
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn float-right pading_custom01 btn_sendyn btn-default mb-2 text-right" aria-disabled="false">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card  mb-3">
                <div class="card-header">Lock Amount Setting</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-12">
                            <h6>Send Lock Amount Information</h6>
                            <h6 style="bold; padding-top: 20px;">< 주의사항 ></h6>
                            <p style="color: red !important;" class="small text-muted">Lock Amount 설정은 <?=COIN?>만 가능합니다. </br> Lock Amount는 전송 불가능한 코인 수 입니다.</p>
                        </div>
                        <div class="col-xl-5 col-lg-12 float-right">
                            <div class="mgB5 mgL6em">
                                <input type="number" class="form-control mgR10 w150px i_lockamount" value="<?=str_replace(".00000000","",str_replace(",","",$user_data["lock_amount"]));?>" required="" autofocus="" <?php if($user_data["send_yn"] == 'Y') echo "disabled"?>>
                                <span class="small text-muted d-inline-block">Lock 코인 갯수</span>
                            </div>
                            <div class="mgT5 mgL6em">
                                <input type="number" class="form-control mgR10 w150px i_lockpercent" required="">
                                <span class="small text-muted d-inline-block">퍼센트 비율<strong> %</strong></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn float-right pading_custom01 btn_lockamount btn-default mb-2 text-right" aria-disabled="false">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row sub_list_div2" id="sub_list_div2">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Buy & Sale List</div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 display"  width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="board_order responsive-sm" data-order="board_order_1">No</th>
                            <th class="board_order" data-order="board_order_2">Category</th>
                            <th class="board_order responsive-sm" data-order="board_order_3">Amount</th>
                            <th class="board_order" data-order="board_order_4">입금자명</th>
                            <th class="responsive-sm">상태</th>
                            <th class="responsive-sm">신청 일시</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list_data as $rows){
                            $v_amount = "";

                            if ($rows["adw_type"] == "W") {
                                $v_amount = number_format($rows["coin_amount"]);
                            } else if ($rows["adw_type"] == "D") {
                                $v_amount = number_format($rows["amount"]);
                            }

                            $rows["amount"] = sprintf('%s', number_format($rows["amount"]));
                            $rows["coin_amount"] = sprintf('%s', number_format($rows["coin_amount"]));

                            $type = "";
                            if($rows["adw_type"] == 'W'){ // 출금
                                $rows["adw_type"] = "<span class='badge-warning badge-pill'>판매</span>";
                                $type = "판매";
                            } else if($rows["adw_type"] == 'D'){ // 입금
                                $rows["adw_type"] = "<span class='badge-primary badge-pill'>구매</span>";
                                $type = "구매";
                            }

                            if ($rows["adw_state"] == 'W'){ // 대기
                                $rows["adw_state"] = "<span class='badge-info badge-pill'>".$type."대기</span>";
                            } else if($rows["adw_state"] == 'S'){
                                $rows["adw_state"] = "<span class='badge-success badge-pill'>".$type."완료</span>";
                            } else if($rows["adw_state"] == 'C') {
                                $rows["adw_state"] = "<span class='badge-danger badge-pill'>".$type."취소</span>";
                            }

                            ?>
                            <tr class="curr_user_info" data-seq="<?=$rows["seq"];?>">
                                <td class="alignR responsive-sm"><?=$rows["seq"];?></td>
                                <td><?=$rows["adw_type"];?></td>
                                <td class="responsive-sm"><?=$v_amount;?></td>
                                <td class="alignR"><?=" 입금자 : ".$rows["account"]." ";?></td>
                                <td class="alignR responsive-sm"><?=$rows["adw_state"];?></td>
                                <td class="responsive-sm"><?=$rows["ins_dttm"];?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="row sub_list_div" id="sub_list_div">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Transactions List</div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 display"  width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th class="responsive-sm">confirms</th>
                            <th class="responsive-sm">txid</th>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($history_data as $row){
                            $row["amount"] = number_format($row["amount"],5);

                            if($row["state"] == "RD" || $row["state"] == "SD"){
                                $row["status"] = '<span class="badge-success badge-pill">Completed</span>';
                            }else{
                                $row["status"] = '<span class="badge-warning badge-pill">Pending</span>';
                            }
                            if($row["category"] === 'receive'){
                                $row["category"] = '<td class="text-success alignC">Receive</td>';
                            }else{
                                $row["category"] = '<td class="text-danger alignC">Send</td>';
                            }
                            ?>
                            <tr>
                                <td><?=$row["trans_time"];?></td>
                                <td class="alignR"><?=$row["amount"];?> <?=COIN?></td>
								<td class="responsive-sm"><?=$row["status"];?></td>
                                <td class="responsive-sm"><?=substr($row["txid"], 0, 10);?>...</td>
                                <?=$row["category"];?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom scripts for this page-->
<script src="<?=ASSETS;?>/js/admin-datatables.js?version=<?=_VERSION?>"></script>

