<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var level = "<?=$level?>";

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        $(".btn_Change").on("click", function() {
            companyChange();
        });

    });


    function fnc_popover(this_id, msg, color){
        $('#' + this_id).html(msg);
        $('#' + this_id).css({"color": color});
    }

    function companyChange() {
        var i_company = $("#i_company").val();

        if (i_company == "") {
            fnc_popover("warning_txt", "변경할 회사이름을 입력해주세요.", "red");
            return;
        }

        var params = {
            "i_company" : i_company
        };

        loading_layer_open();

        req(system_config, '/Ajax/company_change_ajax', {
            json : true,
            data:params,
            callback:function(rtn) {
                //loading_layer_close();
                if(rtn.result > 0){
                    notify_layer("회사정보가 변경되었습니다.");
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
                        <label>회사이름</label>
                        <input type="text" class="form-control" id="i_company" placeholder="회사이름을 입력해주세요." value="<?=$company?>" required="" autofocus="" autocomplete="false">
                        <p class="warning_txt" id="uname_txt"></p>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-default mt-2 btn_Change">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>
