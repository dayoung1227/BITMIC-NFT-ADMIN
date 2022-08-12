<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var level = "<?=$level?>";

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {

        $("#i_id").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            if(!validateEmail($("#i_id").val())) {
                fnc_popover("uid_txt", "아이디는 이메일형식으로 입력해주세요.", "red");
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

        $("#i_secure1").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            if(!validateSecure( $("#i_secure1").val())){
                fnc_popover("secure1_txt", "보안번호를 다시 입력해주세요.", "red");
            } else {
                $('#secure1_txt').html('');
            }
        });

        $("#i_secure2").on("input propertychange paste change keyup focusout focuson keydown keypress", function(e) {
            var i_secure1 = $("#i_secure1").val();
            if(!validateSecure( $("#i_secure2").val())){
                fnc_popover("secure2_txt", "보안번호 확인을 다시 입력해주세요.", "red");
            } else if( !compareString(i_secure1,  $("#i_secure2").val())) {
                fnc_popover("secure2_txt", "보안번호랑 보안번호 확인이 서로 다릅니다.", "red");
            } else {
                $('#secure2_txt').html('');
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


        $('.btnupdatemyinfo').on("click", function(e) {
            if(!validateEmail($("#i_id").val())) {
                fnc_popover("uid_txt", "아이디는 이메일형식으로 입력해주세요.", "red");
                return;
            } else {
                $('#uid_txt').html('');
            }

            if(!validateName($("#i_name").val())) {
                fnc_popover("uname_txt", "닉네임은 2글자 이상 입력해주세요.", "red");
                return;
            } else {
                $('#uname_txt').html('');
            }

            if(!validatePassword( $("#i_pw1").val())){
                fnc_popover("upw1_txt", "비밀번호를 다시 입력해주세요.", "red");
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

            if(!validateSecure( $("#i_secure1").val())){
                fnc_popover("secure1_txt", "보안번호를 다시 입력해주세요.", "red");
                return;
            } else {
                $('#secure1_txt').html('');
            }

            var i_secure1 = $("#i_secure1").val();
            if( !compareString(i_secure1,  $("#i_secure2").val())) {
                fnc_popover("secure2_txt", "보안번호랑 보안번호 확인이 서로 다릅니다.", "red");
            } else {
                $('#secure2_txt').html('');
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

        var level = "<?=$level?>";
        var recomm = "";

        if (level != 3) {
            recomm = $("select[name=i_recomm]").val();
        } else {
            recomm = "<?= $level == 3 ? $admin_Info["adm_nnm"] : '';?>";
        }

        var params = {
            "uid" : $("#i_id").val(),
            "unick" : $("#i_name").val(),
            "upw" :$("#i_pw1").val(),
            "usecure" :$("#i_secure1").val(),
            "uphone" : adm_phone,
            "urecomm" : recomm
        };

        //loading_layer_open();
        req(system_config, '/Ajax/insert_userinfo_ajax', {
            json : true,
            data:params,
            callback:function(rtn) {
                //loading_layer_close();
                if(rtn.result > 0){
                    notify_layer("새로운 회원이 등록되었습니다.");

                    if ("<?=$level?>" != 3) {
                        location.replace("/Main/recommends_list");
                    } else {
                        location.replace("/Main/admin_recomm_list");
                    }

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
            <div class="card-header">회원 등록</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>회원 ID</label>
                        <input type="email" class="form-control" id="i_id" placeholder="아이디는 이메일형식으로 입력해주세요." value="" required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uid_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>회원 닉네임</label>
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
                        <label>보안번호</label>
                        <input type="password" class="form-control" id="i_secure1" placeholder="보안번호를 입력해주세요." maxlength='4' value="" required="" autofocus="" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" >
                        <p class="warning_txt" id="secure1_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>보안번호 확인</label>
                        <input type="password" class="form-control" id="i_secure2" placeholder="보안번호 확인을 입력해주세요." maxlength='4' value="" required="" autofocus="" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"  autocomplete="off">
                        <p class="warning_txt" id="secure2_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>핸드폰 번호</label>
                        <input type="text" class="form-control" id="i_phone" value="" placeholder="핸드폰 번호를 입력해주세요." required="" autofocus="" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"  autocomplete="off">
                        <p class="warning_txt" id="uphone_txt"></p>
                    </div>
                </div>
                <div class="form-group col-xl-6 col-lg-12  col-sm-12">
                    <label>추천인</label>
                    <?php if ($level != 3) { ?>
                        <select class="custom_select" id="i_recomm" name="i_recomm">
                            <?php foreach($master_data as $key=>$val) {?>
                                <option value="<?=$val["adm_nnm"]?>" data-seq="<?=$val["adm_sq"]?>"><?=$val["adm_nnm"]?> </option>
                            <? } ?>
                        </select>
                    <? } else { ?>
                        <input type="text" class="form-control" id="i_level" value="<?=$admin_Info["adm_nnm"]?>" placeholder="" autocomplete="off" readonly>
                    <? } ?>
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-default mt-2 btnupdatemyinfo">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>
