<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;


    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        var phone = "<?=$admin_data["adm_phone"]?>";
        phone = phone.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
        $("#i_phone").val(phone);


        // 락업기간
        let lock = "<?=$admin_data["join_lockup_month"]?>";
        $("select[name=i_lock]").val(lock);
        $("#i_lock").next('div.styledSelect').text(lock+" 개월");

        $("#i_pw1").on("paste focusout", function(e) {
            if(!validatePassword( $("#i_pw1").val())){
                fnc_popover("upw1_txt", "비밀번호를 다시 입력해주세요.", "red");
            }  else {
                $('#upw1_txt').html('');
            }
        });

        $("#i_pw2").on("paste focusout", function(e) {
            var i_pw1 = $("#i_pw1").val();
            if( !compareString(i_pw1,  $("#i_pw2").val())) {
                fnc_popover("upw2_txt", "비밀번호랑 비밀번호확인이 서로 다릅니다.", "red");
            } else {
                $('#upw2_txt').html('');
            }
        });


        $("#i_sale_price_krw").on("paste focusout", function(e) {
            if($("#i_sale_price_krw").val() < 50){
                fnc_popover("sale_price_krw_txt", "최소 50이상 부터 입력해 주세요.", "red");
                return;
            } else {
                $('#sale_price_krw_txt').html('');
            }
        });

        $("#i_pw1").keydown(function(e) {
            $('#i_pw1').popover('hide');
        });
        $("#i_pw2").keydown(function(e) {
            $('#i_pw2').popover('hide');
        });

        $('.btnupdatemyinfo').on("click", function(e) {
            if(!validateName($("#i_name").val())) {
                fnc_popover("uname_txt", "닉네임은 2글자이상 입력해주세요.", "red");
                return;
            } else {
                $('#uname_txt').html('');
            }

            // 비밀번호 변경 안할 시 -> 2개 다 null
            var upw1 = $("#i_pw1").val();
            var upw2 = $("#i_pw2").val();

            if (upw1 || upw2) {
                if(!validatePassword(upw1)){
                    fnc_popover("upw1_txt", "비밀번호를 다시 입력해주세요.", "red");
                    return;
                } else {
                    $('#upw1_txt').html('');
                }

                if( !compareString(upw1,  upw2)) {
                    fnc_popover("upw2_txt", "비밀번호랑 비밀번호확인이 서로 다릅니다.", "red");
                    return;
                } else {
                    $('#upw2_txt').html('');
                }
            }

            let level = '<?=$admin_data["adm_level"]?>';

            if (level == 3) {
                if($("#i_sale_price_krw").val() < 50){
                    fnc_popover("sale_price_krw_txt", "최소 50이상 부터 입력해 주세요.", "red");
                    return;
                } else {
                    $('#sale_price_krw_txt').html('');
                }
            }

            go_update();
        });


        /* 활성화 / 비활성화 */
        $(".btn_Use").on("click", function() {
            var adm_seq = $(this).data("seq");

            var params = {
                "adm_seq" : adm_seq
            };

            req(system_config, '/Ajax/active_admin_ajax', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){
                        notify_layer(rtn.txt);
                    }else{
                        alert_layer(rtn.err);
                    } //result if
                }
            });
        });

        $(".btn_copy").on("click", function(){
            var copy_address = new Clipboard(".btn_copy");
            notify_layer("복사하였습니다.");
        });

    });

    function change() {
        var phone = "<?=$admin_data["adm_phone"]?>";
        var phone_check = $("#i_phone").val();

        phone = phone.replace(/[^0-9]/g, '');
        phone_check = phone_check.replace(/[^0-9]/g, '');

        if (phone === phone_check) {
            phone = phone.replace(/^0-9/g, '');

            $("#i_phone").val(phone);
        }
    }

    function fnc_popover(this_id, msg, color){
        $('#' + this_id).html(msg);
        $('#' + this_id).css({"color": color});
    }


    function go_update(){
        var adm_phone = $("#i_phone").val();
        adm_phone = adm_phone.replace(/[^0-9]/g, '');

        var params = {
            "adm_level" : '<?=$admin_data["adm_level"]?>',
            "adm_seq" : "<?=$admin_data["adm_sq"]?>",
            "adm_pw" : $("#i_pw1").val(),
            "adm_phone" : adm_phone,
            "adm_sale_price_krw" : $("#i_sale_price_krw").val(),
            "adm_lock_dday" : $("select[name=i_lock]").val()
        };

        //loading_layer_open();
        req(system_config, '/Ajax/update_admin_ajax', {
            json : true,
            data:params,
            callback:function(rtn) {
                //loading_layer_close();
                if(rtn.result > 0){
                    notify_layer("관리자 정보를 수정했습니다.");
                }else{
                    alert_layer(rtn.err);
                } //result if
            }
        });

    }
</script>

<div class="row page_title">
    <div class="col-sm-6">
        <h4><?=$mnu;?></h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">관리자 수정</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin ID</label>
                        <input type="text" class="form-control" id="i_id" value="<?=$admin_data["adm_id"]?>" required="" autofocus="" readonly disabled>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin Nickname</label>
                        <input type="text" class="form-control" id="i_name" value="<?=$admin_data["adm_nnm"]?>" required="" autofocus=""readonly disabled>
                        <p class="warning_txt" id="uname_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin Level Alias</label>
                        <input type="text" class="form-control" id="i_level" value="<?php
                        if ($admin_data["adm_level"] == 0) {
                            echo "최고관리자";
                        } else if ($admin_data["adm_level"] == 1) {
                            echo "본사관리자";
                        } else if ($admin_data["adm_level"] == 2) {
                            echo "본사모니터링";
                        } else {
                            echo "추천인";
                        }?>"
                               required="" autofocus="" disabled readonly>
                    </div>
                </div>
                <?php if ($admin_data["adm_level"] == 3) { ?>
                    <div class="col-xl-6 col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>추천인 url </label>
                            <input type="text" class="form-control" value="<?=$admin_data["adm_domain"]?>" required="" autofocus="" aria-disabled="true" disabled>
                            <i class="btn_copy copy_url fal fa-copy" data-clipboard-text="<?=$admin_data["adm_domain"];?>"></i>
                        </div>
                    </div>
                <?php }  ?>
                <!--                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>입금수수료율</label>
                        <input type="text" class="form-control" id="i_deposit" value="<?/*=$admin_data["adm_deposit_fee"] == 0 ? '-' : $admin_data["adm_deposit_fee"].' %'*/?>" required="" autofocus="" readonly disabled>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>출금수수료율</label>
                        <input type="text" class="form-control" id="i_withdraw_fee" value="<?/*=$admin_data["adm_withdraw_fee"] == 0 ? '-' : number_format($admin_data["adm_withdraw_fee"], 2).' %'*/?>" required="" autofocus="" readonly disabled>
                    </div>
                </div>-->
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin Phone</label>
                        <input type="text" class="form-control" id="i_phone" onclick="change()" required="" autofocus="">
                        <p class="warning_txt" id="uphone_txt"></p>
                    </div>
                </div>
                <?php if ($admin_data["adm_level"] == 3) { ?>
                    <div class="col-xl-6 col-lg-12  col-sm-12"></div>
                    <div class="col-xl-6 col-lg-12  col-sm-12">
                        <div class="form-group">
                            <label>할인판매금액 (KRW)</label>
                            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" id="i_sale_price_krw" value="<?=$admin_data["sale_price_krw"]?>" placeholder="최소 50이상 부터 입력가능합니다."  autocomplete="off">
                            <p class="warning_txt" id="sale_price_krw_txt"></p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>회원 락업기간 설정</label>
                            <select class="custom_select" id="i_lock" name="i_lock">
                                <option value="1">1 개월</option>
                                <option value="2">2 개월</option>
                                <option value="3">3 개월</option>
                                <option value="4">4 개월</option>
                                <option value="5">5 개월</option>
                                <option value="6">6 개월</option>
                                <option value="7">7 개월</option>
                                <option value="8">8 개월</option>
                                <option value="9">9 개월</option>
                                <option value="10">10 개월</option>
                                <option value="11">11 개월</option>
                                <option value="12">12 개월</option>
                            </select>
                            <p class="warning_txt" id="lock_txt"></p>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>비밀번호</label>
                        <input type="password" class="form-control" id="i_pw1" value="" required="" autofocus="">
                        <p class="warning_txt" id="upw1_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>비밀번호 확인</label>
                        <input type="password" class="form-control" id="i_pw2" value="" required="" autofocus="">
                        <p class="warning_txt" id="upw2_txt"></p>
                    </div>
                </div>
                <div class="form-group col-xl-6 col-lg-12 col-sm-12">
                    <?php if ($admin_data["use_yn"] == 'Y') { ?>
                        <button type="button" class="btn btn-danger mt-2 btn_Use"  style="width: 20%;"  data-seq="<?=$admin_data["adm_sq"]?>">비활성</button>
                        <p style="margin: 10px; font-size: 12px; color: red;"><i class="fal fa-exclamation-circle"></i> 현재 활성화된 관리자입니다. 비활성화를 하고 싶으면 비활성버튼을 눌러주세요. <br> &nbsp;&nbsp;&nbsp;&nbsp; 해당 관리자랑 레벨이 같거나 높을 시 불가능합니다.</p>
                    <? } else { ?>
                        <button type="button" class="btn btn-success mt-2 btn_Use" style="width: 20%;" data-seq="<?=$admin_data["adm_sq"]?>">활성</button>
                        <p style="margin: 10px; font-size: 12px; color: red;"><i class="fal fa-exclamation-circle"></i> 현재 비활성화된 관리자입니다. 활성화를 하고 싶으면 활성버튼을 눌러주세요. <br> &nbsp;&nbsp;&nbsp;&nbsp; 해당 관리자랑 레벨이 같거나 높을 시 불가능합니다.</p>
                    <? } ?>
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-default mt-2 btnupdatemyinfo" style="width: 30%;">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>
