<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var list_data = <?=json_encode($list_data)?>;
    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        var table = $('#dataTable3').DataTable({
            data : list_data,
            columns : [
                {data: 'seq'},
                {data: 'uid'},
                {data: 'unick'},
                {data: 'phone'},
                {data: 'balance'},
                {data: 'lock_amount'},
                {data: 'bank_name'},
                {data: 'adm_nnm'},
                {data: 'send_yn'},
                {data: 'join_dttm'}
            ],
            "columnDefs": [

                {
                    "targets": 1,
                    "data": "uid",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 2,
                    "data": "unick",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 3,
                    "data": "phone",
                    "render": function ( data, type, row, meta ) {
                        data = data.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
                        return data
                    }
                },
                {
                    "targets": 4,
                    "data": "balance",
                    "render": function ( data, type, row, meta ) {
                        var number = $.fn.dataTable.render.number( ',', '.', 5). display(data);
                        number = number + " <?=COIN?>";

                        return number;
                    }
                },
                {
                    "targets": 5,
                    "data": "lock_amount",
                    "render": function ( data, type, row, meta ) {
                        var number = $.fn.dataTable.render.number( ',', '.', 5). display(data);
                        number = number + " <?=COIN?>";

                        return number;
                    }
                },
                {
                    "targets": 6,
                    "data": "bank_name",
                    "render": function ( data, type, row, meta ) {
                        if (data == "" || data == null || data == 0) {
                            data = '-';
                        } else {
                            data = data + ' - ' + row.bank_num + " ( 입금자 : " + row.account + " )";
                        }


                        return data;
                    }
                },
                {
                    "targets": 7,
                    "data": "send_yn",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 8,
                    "data": "recomm_seq",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 9,
                    "data": "join_dttm",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },

            ],
            order : [[ 0, "desc"]]
        });

        $('#dataTable3 tbody tr').addClass("cursor");

        // 팝업 포인터
        $('.btn_popup').css('cursor', "pointer");

        // 팝업 표시
        $('#dataTable3 tbody').on('click', 'tr', function () {

        });

        $('#dataTable3 tbody').on('click', 'tr', function () {
            var user_seq = table.row( this ).data();
            user_seq = user_seq.seq;

            var save_params = {
                "seq" :user_seq,
                "type" : 'user_list',
            };
            req(system_config, '/Main/detail_memberinfo',{data:save_params});
        });

        $(".btn_uptcoininfo").on("click",function(){
            loading_layer_open();
            params = {};
            req(system_config, '/Ajax/set_membercoininfo', {
                json : true,
                data:params,
                callback:function(rtn) {
                    loading_layer_close();

                    var reload_params = {
                        "search_type" : search_type,
                        "search_str" : search_str,
                        "order_type" : order_type,
                        "order_asc" : order_asc,
                        "curr_page" : curr_page
                    };
                    req(system_config, link_url ,{data:reload_params, div : change_div});
                }
            });
        });

        $(".btn_alluserlockup").on("click", function () {
            req(system_config, '/Main/all_user_lockup',{div : "main_area"});
        });

        $(".btn_user_control").on("click", function () {
            location.replace('/Main/user_control')
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
        <div class="card mb-3">
            <div class="card-header" style="display: flex; justify-content: space-between">전체 회원 목록</div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable3" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>닉네임</th>
                            <th>전화번호</th>
                            <th>보유토큰</th>
                            <th>Lock 수량</th>
                            <th>입/출통장</th>
                            <th>추천인</th>
                            <th>Lock Y/N</th>
                            <th>가입일시</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- e: Main Contents -->