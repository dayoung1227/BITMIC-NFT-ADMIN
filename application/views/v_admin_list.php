<script type="text/javascript">
var	is_clickact = true;
var click_cnt = 0;
var list_data = <?=json_encode($list_data)?>;
//초기화 하면 안되는 변수라서 여기 사용
$(document).ready(function($) {
    var table = $('#dataTable3').DataTable({
        data : list_data,
        columns : [
            {data: 'adm_sq'},
            {data: 'adm_id'},
            {data: 'adm_nnm'},
            {data: 'adm_level'},
            {data: 'adm_phone'},
            {data: 'sale_price_krw'},
        ],
        "columnDefs": [
            {
                "targets": 0,
                "data": "adm_sq",
                "render": function ( data, type, row, meta ) {
                    var no = meta.row + 1
                    data = '<span class="btn_admin_modify" data-seq="'+row.adm_sq+'">'+no+'</span>';
                    return data;
                },
            },
            {
                "targets": 1,
                "data": "adm_id",
                "render": function ( data, type, row, meta ) {
                    data = '<span class="btn_admin_modify" data-seq="'+row.adm_sq+'">'+data+'</span>';
                    return data;
                },
            },
            {
                "targets": 2,
                "data": "adm_nnm",
                "render": function ( data, type, row, meta ) {
                    data = '<span class="btn_admin_modify" data-seq="'+row.adm_sq+'">'+data+'</span>';
                    return data;
                }
            },
            {
                "targets": 3,
                "data": "adm_level",
                "render": function ( data, type, row, meta ) {
                    // 나중에 상태 값 정해지면 변경
                    //data = '<span class="btn btn_admin_modify btn-default pl-1 pr-1 w-50 pt-2 pb-1" data-seq="'+data+'">수정</span>';
                    //레벨 Alias : 0 - 최고관리자, 1 - 본사관리자, 2 - 본사모니터링, 3 - 추천인
                    if (data == 0) {
                        data = "최고관리자";
                    } else if (data == 1) {
                        data = "본사관리자";
                    } else if (data == 2) {
                        data = "본사모니터링";
                    } else {
                        data = "추천인";
                    }

                    data = '<span class="btn_admin_modify" data-seq="'+row.adm_sq+'">'+data+'</span>';

                    if (row.adm_level == 3) {
                        data += '<i class="pl-3 btn_copy fal fa-copy" data-clipboard-text="'+row.adm_domain+'"></i>';
                    }

                    return data;
                }
            },
            {
                "targets": 4,
                "data": "adm_phone",
                "render": function ( data, type, row, meta ) {
                    // 나중에 상태 값 정해지면 변경
                    //data = '<span class="btn btn_admin_delete btn-danger pl-1 pr-1 w-50 pt-2 pb-1" data-seq="'+data+'">삭제</span>';
                    data = data.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");

                    data = '<span class="btn_admin_modify" data-seq="'+row.adm_sq+'">'+data+'</span>';

                    return data;
                }
            },
            {
                "targets": 5,
                "data": "sale_price_krw",
                "render": function ( data, type, row, meta ) {
                    // 나중에 상태 값 정해지면 변경
                    //data = '<span class="btn btn_admin_delete btn-danger pl-1 pr-1 w-50 pt-2 pb-1" data-seq="'+data+'">삭제</span>';
                    data = (row.adm_level != 3 ? '- ' : data +" KRW");

                    data = '<span class="btn_admin_modify" data-seq="'+row.adm_sq+'">'+data+'</span>';

                    return data;
                }
            },
        ],

        order : [[ 0, "asc"]]
    });

    $('#dataTable3 tbody tr').addClass("cursor");

    // 팝업 포인터
    $('.btn_popup').css('cursor', "pointer");

    // 팝업 표시
    $('#dataTable3 tbody').on('click', 'tr', function () {

    });

    $(".btn_admin_control").on("click", function () {
        location.replace('/Main/admin_control')
    });

    $(".btn_admin_modify").on("click", function () {
        var admin_seq = $(this).data("seq");
        location.replace('/Main/admin_modify/' + admin_seq);

    });

    $(".btn_admin_delete").on("click", function () {
        var admin_seq = $(this).data("seq");
    });

    $(".btn_copy").on("click", function(){
        var copy_address = new Clipboard(".btn_copy");
        notify_layer("복사하였습니다.");
    });
});

</script>






<div class="row page_title">
    <div class="col-sm-6">
        <h4><?=$mnu;?></h4>
    </div>
</div>


<div class="row sub_list_div" id="sub_list_div">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header" style="display: flex; justify-content: space-between">
                <p style="margin-bottom: 0">전체 관리자 목록<span style="font-size: 14px; color: #f7931a;"> ( 추천인 레벨은 추천인 초대코드 복사가 가능합니다. ) </span></p>
                <?php if ($level == 0 || $level == 1) {  ?>
                    <a href="#" class="btn btn-primary pading_default btn_admin_control">관리자 등록</a>
                <?php }?>
            </div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable3" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>아이디</th>
                            <th>닉네임</th>
                            <th>레벨 Alias</th>
                            <th>핸드폰번호</th>
                            <th>할인판매금액 (KRW)</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="buy_popup">
        <div class="buy_popup_content">
            <div class="buy_popup_content_head">
                <h4>구매 상세내역</h4>
                <i class="far fa-times cancel"></i>
            </div>
            <div class="buy_popup_content_body">
                <table>
                    <tr>
                        <td>닉네임 ( 이메일 )</td>
                        <td class="nick"></td>
                    </tr>
                    <tr>
                        <td>신청금액</td>
                        <td class="amount"></td>
                    </tr>
                    <tr>
                        <td><?=COIN;?> 수량</td>
                        <td class="coin"></td>
                    </tr>
                    <tr>
                        <td>신청은행</td>
                        <td class="bank_name"></td>
                    </tr>
                    <tr>
                        <td>계좌번호</td>
                        <td class="bank_num"></td>
                    </tr>
                    <tr>
                        <td>입금자</td>
                        <td class="account_name"></td>
                    </tr>
                    <tr>
                        <td>핸드폰번호</td>
                        <td class="phone"></td>
                    </tr>
                    <tr>
                        <td>보유 <?=COIN;?> 수량</td>
                        <td class="balance"></td>
                    </tr>
                    <tr>
                        <td>구매상태</td>
                        <td class="state"></td>
                    </tr>
                    <tr>
                        <td>신청일시</td>
                        <td class="dttm"></td>
                    </tr>
                    <tr>
                        <td>상태변경일시</td>
                        <td class="upt_dttm"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- e: Main Contents -->


