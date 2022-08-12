<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var list_data = <?=json_encode($list_data)?>;


    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        var table = $('#dataTable3').DataTable({
            ordering: true,
            data : list_data,
            columns : [
                {data: 'seq'},
                {data: 'bbs_subject'},
                {data: 'bbs_content'},
                {data: 'adm_nnm'},
                {data: 'use_yn'},
                {data: 'ins_dttm'},
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "data": "seq",
                    "render": function ( data, type, row, meta ) {
                        var no = meta.row + 1
                        data = '<span class="btn_notice_modify" data-seq="'+row.seq+'">'+no+'</span>';
                        return data;
                    },
                },
                {
                    "targets": 1,
                    "data": "bbs_subject",
                    "render": function ( data, type, row, meta ) {
                        data = '<span class="btn_notice_modify" data-seq="'+row.seq+'">'+data+'</span>';
                        return data;
                    },
                },
                {
                    "targets": 2,
                    "data": "bbs_content",
                    "render": function ( data, type, row, meta ) {
                        if (data.length > 20) {
                            data = data.substr(0, 10) + "...";
                        }
                        data = '<span class="btn_notice_modify" data-seq="'+row.seq+'">'+data+'</span>';
                        return data;
                    }
                },
                {
                    "targets": 3,
                    "data": "adm_nnm",
                    "render": function ( data, type, row, meta ) {
                        data = '<span class="btn_notice_modify" data-seq="'+row.seq+'">'+data+'</span>';
                        return data;
                    }
                },
                {
                    "targets": 4,
                    "data": "use_yn",
                    "render": function ( data, type, row, meta ) {
                        if (data == 'Y') {
                            data = "<span class='btn_notice_modify badge-success badge-pill' data-seq="+row.seq+">활성</span>";
                        } else {
                            data = "<span class='btn_notice_modify badge-danger badge-pill' data-seq="+row.seq+">비활성</span>";
                        }

                        return data;
                    }
                },
                {
                    "targets": 5,
                    "data": "ins_dttm",
                    "render": function ( data, type, row, meta ) {
                        data = '<span class="btn_notice_modify" data-seq="'+row.seq+'">'+data+'</span>';
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

        // 공지사항 상세페이지
        $(".btn_notice_modify").on("click", function() {
            var notice_seq = $(this).data("seq");
            location.replace('/Main/notice_modify/' + notice_seq);
        });

        $('.btn_write').on("click", function() {
            location.replace('/Main/notice_write');
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
                공지사항 목록
                <?if ($level == 0 || $level == 1) {  ?>
                    <a href="#" class="btn btn-primary pading_default btn_write">글작성</a>
                <? }?>
            </div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable3" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>제목</th>
                                <th>내용</th>
                                <th>작성자</th>
                                <th>상태</th>
                                <th>작성일</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- e: Main Contents -->


