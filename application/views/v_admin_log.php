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
                {data: 'act_code'},
                {data: 'act_result'},
                {data: 'log_ip'},
                {data: 'log_dttm'},
                {data: 'adm_id'},
                {data: 'cd_desc'},
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "data": "seq",
                    "render": function ( data, type, row, meta ) {
                        var no = meta.row + 1
                        data = '<span class="btn_admin_modify">'+no+'</span>';
                        return data;
                    },
                },
                {
                    "targets": 1,
                    "data": "act_code",
                    "render": function ( data, type, row, meta ) {
                        data = "<span class='badge-success badge-pill'>ACTIVE</span>";

                        if(data == 'W'){
                            data = "<span class='badge-warning badge-pill'>WAITING</span>";
                        }else if(data == 'D'){
                            data = "<span class='badge-danger badge-pill'>DELETE</span>";
                        }

                        return data;
                    },
                },
                {
                    "targets": 2,
                    "data": "act_result",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 3,
                    "data": "log_ip",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 4,
                    "data": "log_dttm",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 5,
                    "data": "adm_id",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 6,
                    "data": "cd_desc",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
            ],

            order : [[ 0, "desc"]]
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
                관리자 활동 내역
            </div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable3" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>코드</th>
                            <th>내용</th>
                            <th>IP</th>
                            <th>활동 일시</th>
                            <th>ID</th>
                            <th>코드 설명</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- e: Main Contents -->