<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var level = "<?=$level?>";

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        var bank_code = "<?=$bank_code?>";
        var bank_name = "<?=$bank_name?>";
        var bank_num = "<?=$bank_num?>";
        var account = "<?=$account?>";


        $("#bank_code").val(bank_code);
        $(".styledSelect").html(bank_name.replace('은행',''));

        $("#i_bank_num").val(bank_num);
        $("#i_account").val(account);


        $(".btn_bankChange").on("click", function() {
            accountAdd();
        });

    });


    function fnc_popover(this_id, msg, color){
        $('#' + this_id).html(msg);
        $('#' + this_id).css({"color": color});
    }

    // 계좌 등록
    function accountAdd() {
        var bank_code = $("select[name=bank_select]").val();;
        var bank_num = $("#i_bank_num").val();
        var account = $("#i_account").val();


        if (bank_code == "" || bank_num == "" || account == "") {
            fnc_popover("secure2_txt", "입력한 계좌정보를 다시 확인해주세요.", "red");
            return;
        }

        if (bank_num.length > 18) {
            fnc_popover("secure2_txt", "입력한 계좌정보를 다시 확인해주세요.", "red");
            return;
        }

        var params = {
            "bank_code" : bank_code,
            "bank_num" : bank_num,
            "account" : account
        };

        loading_layer_open();

        req(system_config, '/Ajax/account_change_ajax', {
            json : true,
            data:params,
            callback:function(rtn) {
                //loading_layer_close();
                if(rtn.result > 0){
                    notify_layer("계좌정보가 변경되었습니다.");
                }else{
                    notify_layer(rtn.err);
                }
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
            <div class="card-header">계좌정보 변경</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>은행</label>
                        <select id="bank_code" class="custom_select" name="bank_select">
                            <option value="" disabled >은행을 선택해 주세요.</option>
                            <option value="001">한국</option>
                            <option value="002">한국산업</option>
                            <option value="003">IBK기업</option>
                            <option value="004">KB국민</option>
                            <option value="007">수협</option>
                            <option value="008">한국수출입</option>
                            <option value="011">농협</option>
                            <option value="012">지역 농·축협</option>
                            <option value="020">우리</option>
                            <option value="023">SC제일</option>
                            <option value="027">한국씨티</option>
                            <option value="031">DGB대구</option>
                            <option value="032">BNK부산</option>
                            <option value="034">광주</option>
                            <option value="035">제주</option>
                            <option value="037">전북</option>
                            <option value="039">BNK경남</option>
                            <option value="054">HSBC 서울지점</option>
                            <option value="081">하나</option>
                            <option value="088">신한</option>
                            <option value="089">케이뱅크</option>
                            <option value="090">카카오뱅크</option>
                            <option value="092">토스뱅크</option>
                            <option value="045">새마을금고</option>
                            <option value="048">신용협동조합</option>
                            <option value="050">상호저축은행</option>
                            <option value="071">우정사업본부</option>
                        </select>
                        <p class="warning_txt" id="uid_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>계좌번호</label>
                        <input type="text" class="form-control" id="i_bank_num" placeholder="'-' 넣어서 입력해주세요." value="" required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uname_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>예금주</label>
                        <input type="text" class="form-control" id="i_account" placeholder="통장에 적힌 예금주 이름으로 입력해주세요." value="" required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uname_txt"></p>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-default mt-2 btn_bankChange">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>
