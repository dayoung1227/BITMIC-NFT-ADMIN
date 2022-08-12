<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var level = "<?=$level?>";

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {

        $("#i_id").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            if(!validateId($("#i_id").val())) {
                fnc_popover("uid_txt", "아이디는 영문, 숫자 사용해서 6~16자로 입력해주세요.", "red");
            } else {
                $('#uid_txt').html('');
            }
        });

        $("#i_name").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            if(!validateName($("#i_name").val())) {
                fnc_popover("uname_txt", "닉네임은 2글자 이상 입력해주세요.", "red");
            } else {
                $('#uname_txt').html('');
            }
        });

        $("#i_pw1").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            if(!validatePassword( $("#i_pw1").val())){
                fnc_popover("upw1_txt", "비밀번호를 다시 입력해주세요.", "red");
            } else {
                $('#upw1_txt').html('');
            }
        });

        $("#i_pw2").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            var i_pw1 = $("#i_pw1").val();
            if( !compareString(i_pw1,  $("#i_pw2").val())) {
                fnc_popover("upw2_txt", "비밀번호랑 비밀번호확인이 서로 다릅니다.", "red");
            } else {
                $('#upw2_txt').html('');
            }
        });

        $("#i_id").keydown(function(e) {
            $('#i_id').popover('hide');
        });
        $("#i_name").keydown(function(e) {
            $('#i_name').popover('hide');
        });
        $("#i_pw1").keydown(function(e) {
            $('#i_pw1').popover('hide');
        });
        $("#i_pw2").keydown(function(e) {
            $('#i_pw2').popover('hide');
        });

        $(".select_options li").on("click", function() {
            if ($(this).text() == '추천인') {
                $("select[name=i_lock]").val('1');
                $("#i_lock").next('div.styledSelect').text("1 개월");
                $(".krw").removeClass("fade");
            } else {
                if ($("select[name=i_level]").val() != 3) {
                    $(".krw").addClass("fade");
                }
            }
        });

        $("#i_withdraw_fee").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            var i_withdraw_fee = $("#i_withdraw_fee").val();
            var cash = i_withdraw_fee.replaceAll(",", "");

            var max_withdraw_fee = "<?=MAX_ADM_WITHDRAW_FEE?>";

            if (cash.indexOf('.') > 0) {
                var split = i_withdraw_fee.split(".");

                if (split[0] == "10") {
                    cash = cash.substr(0, cash.length - 1);
                }

                if (split[1].length > 2) {
                    cash = cash.substr(0, cash.length - 1);
                }
            }

            i_withdraw_fee = parseFloat(i_withdraw_fee);
            max_withdraw_fee = parseFloat(max_withdraw_fee);

            if (i_withdraw_fee > max_withdraw_fee) {
                fnc_popover("withdraw_txt", "출금 수수료는 최대 10%까지 가능합니다.", "red");
                cash = cash.substr(0, cash.length - 1);
            } else {
                $("#withdraw_txt").html('');
            }

            if (i_withdraw_fee <= 0) {
                fnc_popover("withdraw_txt", "출금 수수료는 0 % 불가능합니다.", "red");
            } else {
                $("#withdraw_txt").html('');
            }

            $("#i_withdraw_fee").val(cash);

        });


        $('.btnupdatemyinfo').on("click", function(e) {
            if(!validateId($("#i_id").val())) {
                fnc_popover("uid_txt", "아이디는 영문, 숫자 포함해서 6~16자로 입력해 주세요.", "red");
                return;
            } else {
                $('#uid_txt').html('');
            }

            if(!validateName($("#i_name").val())) {
                fnc_popover("uname_txt", "닉네임은 2글자 이상 입력해 주세요.", "red");
                return;
            } else {
                $('#uname_txt').html('');
            }

            if(!validatePassword( $("#i_pw1").val())){
                fnc_popover("upw1_txt", "비밀번호를 다시 입력해 주세요.", "red");
                return;
            } else {
                $('#upw1_txt').html('');
            }

            var i_pw1 = $("#i_pw1").val();
            if( !compareString(i_pw1,  $("#i_pw2").val())) {
                fnc_popover("upw2_txt", "비밀번호랑 비밀번호확인이 서로 다릅니다.", "red");
                return;
            } else {
                $('#upw2_txt').html('');
            }

            if ($(".krw").hasClass("fade") == false) {
                if($("#i_sale_price_krw").val() < 50){
                    fnc_popover("sale_price_krw_txt", "최소 50이상 부터 입력해 주세요.", "red");
                    return;
                } else {
                    $('#sale_price_krw_txt').html('');
                }
            }

            go_update();
        });
    });


    function fnc_popover(this_id, msg, color){
        $('#' + this_id).html(msg);
        $('#' + this_id).css({"color": color});
    }

    function go_update(){
        var adm_phone = $("#i_phone").val();
        adm_phone = adm_phone.replace(/[^0-9]/g, '');

        var params = {
            "adm_level" : $("select[name=i_level]").val(),
            "adm_id" : $("#i_id").val(),
            "adm_nnm" : $("#i_name").val(),
            "adm_pw" :$("#i_pw1").val(),
            "adm_phone" : adm_phone,
            "adm_sale_price_krw" : $("#i_sale_price_krw").val(),
            "adm_lock_dday" : $("select[name=i_lock]").val()
        };

        //loading_layer_open();
        req(system_config, '/Ajax/insert_admininfo_ajax', {
            json : true,
            data:params,
            callback:function(rtn) {
                //loading_layer_close();
                if(rtn.result > 0){
                    notify_layer("새로운 관리자가 등록되었습니다.");

                    setTimeout(function () {
                        location.replace("/Main/admin_list");
                    }, 1000);
                }else{
                    notify_layer(rtn.err);
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
            <div class="card-header">관리자 등록</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin ID</label>
                        <input type="text" class="form-control" id="i_id" placeholder="아이디는 영문, 숫자 포함해서 6~16자로 입력해주세요." value="" required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uid_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin Nickname</label>
                        <input type="text" class="form-control" id="i_name" placeholder="닉네임은 2글자 이상 입력해주세요." value="" required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uname_txt"></p>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>비밀번호</label>
                        <input type="password" class="form-control" id="i_pw1" placeholder="비밀번호를 입력해주세요." value="" required="" autofocus="" autocomplete="new-password">
                        <p class="warning_txt" id="upw1_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>비밀번호 확인</label>
                        <input type="password" class="form-control" id="i_pw2" placeholder="비밀번호 확인을 입력해주세요." value="" required="" autofocus="" autocomplete="new-password">
                        <p class="warning_txt" id="upw2_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>핸드폰 번호</label>
                        <input type="number" class="form-control" id="i_phone" value="" placeholder="핸드폰 번호를 입력해주세요." required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uphone_txt"></p>
                    </div>
                </div>
                <div class="form-group col-xl-6 col-lg-12  col-sm-12">
                    <label>관리자 등급</label>
                    <select class="custom_select" id="i_level" name="i_level">
                        <?php if ($level == 0) { ?>
                            <option value="1">관리가능 관리자</option>
                        <? } ?>
                        <option value="2">보기전용 관리자</option>
                        <option value="3">추천인</option>
                    </select>
                </div>
<!--                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>출금 수수료</label>
                        <input type="text" class="form-control" id="i_withdraw_fee" placeholder="최대 10%까지 가능하며 소수점 2자리까지 입력가능합니다." oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  autocomplete="off">
                        <p class="warning_txt" id="withdraw_txt"></p>
                    </div>
                </div>-->
                <div class="col-xl-6 col-lg-12 col-sm-12 fade krw">
                    <div class="form-group">
                        <label>할인판매금액 (KRW)</label>
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" id="i_sale_price_krw" placeholder="최소 50이상 부터 입력가능합니다."  autocomplete="off">
                        <p class="warning_txt" id="sale_price_krw_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12 col-sm-12 fade krw">
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
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-default mt-2 btnupdatemyinfo">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>
