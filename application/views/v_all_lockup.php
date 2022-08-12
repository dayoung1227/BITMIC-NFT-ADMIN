<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {

        $(".btn_preview").attr('disabled',true);
        $(".fail-icon").css("display", "none");
        
        $(".i_lockpercent").on('keydown', function () {
            $('.btn_preview').attr('disabled', false);
        });

       /* $(".btn_sync").on('click', function () {
            if($(".i_lockpercent").val() > 100) {
                alert_layer("100%를 초과한 숫자를 입력하셨습니다.");
                return;
            }
            $(".second-step").addClass('show');
            $(".btn_preview").attr('disabled', true);

            var params = {};
            req_hidden(system_config, '/Ajax/set_membercoininfo', {
                json : true,
                data:params,
                callback:function(rtn) {
                    if(rtn.result > 0) {
                        $(".loading-bar-img").attr("src", "=PROTOCOL.DOMAIN;/assets/images/loading_full.gif");
                        $(".btn_preview").attr("disabled", false);
                        $(".success-icon").css("display", "block");
                    } else {
                        $(".loading-bar-img").attr("src", "=PROTOCOL.DOMAIN;/assets/images/loading_non.gif");
                        //$(".btn_preview").attr("disabled", false);
                        $(".fail-icon").css("display", "block");
                    }
                }
            });

        });*/

        $(".btn_preview").on('click',function () {
            if($(".i_lockpercent").val() > 100 || $(".i_lockpercent").val() < 0) {
                alert_layer("0부터 100까지의 숫자만 입력하실 수 있습니다.");
                return;
            }
            loading_layer_open();

            params = {
                "percent" : $(".i_lockpercent").val()
            };

            req_hidden(system_config, '/Ajax/get_preview_list', {
                json : true,
                data:params,
                callback:function(rtn) {
                    $(".third-step").addClass('show');
                    $(".btn_preview").attr('disabled',true);
                    loading_layer_close();
                    con(rtn.data);
                    var user_list = rtn.data;

					$('#i_lockpercent').attr('disabled', true);

                    $('#dataTable3').DataTable({
                        data : user_list,
                        columns : [
                            {data: 'uid'},
                            {data: 'unick'},
                            {data : 'balance'},
                            {data: 'preview'},
                            {data: 'wallet_addr'}
                        ],
                        order : [[ 3, "desc"]]
                    });
                }

            });
        });

        $(".btn_setup").on("click",function () {
            loading_layer_open();

            var lockup_percent = $(".i_lockpercent").val();
            params = {
                "percent" : lockup_percent
            };
            req_hidden(system_config, '/Ajax/set_all_user_lockup', {
               json : true,
               data : params,
               callback:function(rtn) {
                   $(".lock-finished").addClass('show');
                   loading_layer_close();
                   //con(rtn.lock_result)
                   $(".user_all_balance").val(rtn.all_balance);
                   $(".lockup_percent").val(lockup_percent);
                   $(".lockup_coin").val(rtn.all_lock_amount);
                   $(".usable_coin").val(rtn.usable);
               }
            });
        });

        $(".btn_reset_all").on("click",function () {
            $(".btn_preview").attr('disabled',true);
            $(".third-step").removeClass('show');
            $(".lock-finished").removeClass('show');
            $(".i_lockpercent").val('');
			$('#i_lockpercent').attr('disabled', false);
            $(".loading-bar-img").attr("src", "<?=PROTOCOL.DOMAIN;?>/assets/images/loading.gif");
            $("#dataTable3").DataTable().destroy();
            $(".success-icon").css("display", "none");
            $(".fail-icon").css("display", "none");
        });

    });

</script>



<div class="row page_title">
    <div class="col-sm-6">
        <h4><?=$mnu;?></h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn pading_custom02 btn_reset_all btn-default mb-2" aria-disabled="false">입력 초기화</button>
    </div>
</div>
<div class="row first-step">
    <div class="col-md-12">
        <div class="card  mb-3">
            <div class="card-header">1단계 LockUp 설정</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-6 col-lg-12 float-right">
                        <h6>전체 유저 보유코인</h6>
                        <p><strong class="user_amount"><?=$all_user_amount?></strong></p>
                    </div>
                    <div class="col-xl-6 col-lg-12 float-right">
                        <input type="number" class="form-control w200px mgR10 i_lockpercent" id="i_lockpercent" required="" autofocus="" style="display: inline-block"><strong>%</strong>
                        <p class="small text-muted ">% 단
                            위 락업 설정은 소수점 포함 반올림 됩니다.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn pading_custom02 btn_preview btn-default mb-2" aria-disabled="false">미리보기</button>
            </div>
        </div>
    </div>
</div>
<!--
<div class="row fade second-step">
    <div class="col-md-12">
        <div class="card  mb-3">
            <div class="card-header">2단계 동기화</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-12 col-lg-12">
                        <div>
                            <h5>지갑 정보를 최신 상태로 동기화 시키고 있습니다.</h5>
                            <p class="text-red">지갑상태가 최신 정보가 아닐 경우 잘못된 금액이 Lock Up 될 수 있습니다.</p>
                        </div>
                        <img class="loading-bar-img" src="<?/*=PROTOCOL.DOMAIN;*/?>/assets/images/loading.gif">
                        <div class="success-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="fail-icon">
                            <i class="fa fa-ban"></i>
                            <p>지갑 정보 동기화에 실패했습니다 서버 상태를 확인해주세요.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn pading_custom02 btn_preview btn-default mb-2" aria-disabled="false">미리보기</button>
            </div>
        </div>
    </div>
</div>-->

<div class="row fade third-step">
    <div class="col-md-12">
        <div class="card  mb-3">
            <div class="card-header">2단계 회원 미리보기</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <h6 class="text-center pdT15 pdB15">보유코인이 많은 순서대로 1000건만 미리보기 조회합니다.</h6>
                        <div class="table-responsive">
                            <table class="table mb-0 table_s1 " id="dataTable3" width="100%" cellspacing="0">
                                <thead>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Balance</th>
                                    <th>Lock Amount</th>
                                    <th>Wallet addr</th>
                                </thead>
                                <tfoot>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Balance</th>
                                    <th>Lock Amount</th>
                                    <th>Wallet addr</th>
                                </tfoot>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn pading_custom02 btn_setup btn-red mb-2" aria-disabled="false">설정된 락업 적용하기</button>
            </div>
        </div>
    </div>
</div>

<div class="row fade lock-finished">
    <div class="col-md-12">
        <div class="card  mb-3">
            <div class="card-header">3단계 완료</div>
            <div class="card-body lockup_result">
                <div class="row user_info">
                    <div class="setup_result">
                        <h5 class="text-center pdT15 pdB15">설정하신 락업이 적용 완료되었습니다</h5>
                        <div class="show_lockup"">
                            <div class="form-group">
                                <label>유저 보유코인</label>
                                <input type="text" class="form-control user_all_balance bold"  aria-disabled="true" disabled>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group">
                                <label>락업 설정비율 %</label>
                                <input type="text" class="form-control lockup_percent bold"  aria-disabled="true" disabled>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group">
                                <label>락업 설정된 코인</label>
                                <input type="text" class="form-control lockup_coin bold"  aria-disabled="true" disabled>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group">
                                <label>사용 가능한 코인</label>
                                <input type="text" class="form-control usable_coin bold" aria-disabled="true" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Custom scripts for this page-->
<script src="<?=ASSETS;?>/js/admin-datatables.js?version=<?=_VERSION?>"></script>

