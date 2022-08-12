<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        var phone = "<?=$my_data["adm_phone"]?>";
        phone = phone.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
        $("#i_phone").val(phone);

        $("#i_pw1").on("paste focusout", function(e) {
            if(!validatePassword( $("#i_pw1").val())){
                fnc_popover("upw1_txt", "비밀번호를 다시 입력해주세요.", "red");
            } else {
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

        $("#i_pw1").keydown(function(e) {
            $('#i_pw1').popover('hide');
        });
        $("#i_pw2").keydown(function(e) {
            $('#i_pw2').popover('hide');
        });


        $('.btnupdatemyinfo').on("click", function(e) {
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
            go_update();
        });

        $(".btn_copy").on("click", function(){
            var copy_address = new Clipboard(".btn_copy");
            notify_layer("복사하였습니다.");
        });
    });

    function fnc_popover(this_id, msg, color){
        $('#' + this_id).html(msg);
        $('#' + this_id).css({"color": color});
    }

    function change() {
        var phone = "<?=$my_data["adm_phone"]?>";
        var phone_check = $("#i_phone").val();

        phone = phone.replace(/[^0-9]/g, '');
        phone_check = phone_check.replace(/[^0-9]/g, '');

        if (phone === phone_check) {
            phone = phone.replace(/^0-9/g, '');
            $("#i_phone").val(phone);
        }
    }

    function go_update(){
        var adm_phone = $("#i_phone").val();
        adm_phone = adm_phone.replace(/[^0-9]/g, '');

        var params = {
            "adm_pw" : $("#i_pw1").val(),
            "adm_phone" : adm_phone
        };

        req(system_config, '/Ajax/update_admininfo_ajax', {
            json : true,
            data:params,
            callback:function(rtn) {
                if(rtn.result > 0){
                    notify_layer("관리자 정보를 수정했습니다.");
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
            <div class="card-header">내 정보 수정</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin No.</label>
                        <input type="text" class="form-control" value="<?=$my_data["adm_sq"]?>" required="" autofocus="" aria-disabled="true" disabled>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin ID</label>
                        <input type="text" class="form-control" value="<?=$my_data["adm_id"]?>" required="" autofocus="" aria-disabled="true" readonly disabled>
                    </div>
                </div>
<!--                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>입금수수료율</label>
                        <input type="text" class="form-control" id="i_deposit" value="<?/*=$my_data["adm_deposit_fee"] == 0 ? '-' : $my_data["adm_deposit_fee"].' %'*/?>" required="" autofocus="" readonly disabled>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>출금수수료율</label>
                        <input type="text" class="form-control" id="i_withdraw_fee" value="<?/*=$my_data["adm_withdraw_fee"] == 0 ? '-' : number_format($my_data["adm_withdraw_fee"], 2).' %'*/?>" required="" autofocus="" readonly disabled>
                    </div>
                </div>-->
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin Nickname</label>
                        <input type="text" class="form-control" id="i_name" value="<?=$my_data["adm_nnm"]?>" required="" autofocus="" readonly disabled>
                        <p class="warning_txt" id="uname_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>Admin Phone</label>
                        <input type="text" class="form-control" id="i_phone" onclick="change()" required="" autofocus="">
                        <p class="warning_txt" id="uphone_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>새로운 비밀번호</label>
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
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>마지막 로그인 일시</label>
                        <input type="text" class="form-control" value="<?=$my_data["last_login_dttm"]?>" required="" autofocus="" aria-disabled="true" disabled>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12 col-sm-12">
                    <div class="form-group">
                        <label>추천인 url </label>
                        <input type="text" class="form-control" value="<?=$my_data["adm_domain"]?>" required="" autofocus="" aria-disabled="true" disabled>
                        <i class="btn_copy copy_url fal fa-copy" data-clipboard-text="<?=$my_data["adm_domain"];?>"></i>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-default mt-2 btnupdatemyinfo">수정</button>
                </div>

            </div>
        </div>
    </div>
</div>
