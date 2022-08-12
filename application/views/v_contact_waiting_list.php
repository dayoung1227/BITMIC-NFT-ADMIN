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
                {data: 'unick'},
                {data: 'bbs_subject'},
                {data: 'bbs_content'},
                {data: 'bbs_comment'},
                {data: 'bbs_comment'},
                {data: 'adm_nnm'},
                {data: 'ins_dttm'},
                {data: 'upt_dttm'}
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "data": "seq",
                    "render": function ( data, type, row, meta ) {
                        var no = meta.row + 1
                        data = '<span class="btn_admin_modify" data-seq="'+row.seq+'">'+no+'</span>';
                        return data;
                    },
                },
                {
                    "targets": 1,
                    "data": "unick",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 2,
                    "data": "bbs_subject",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 3,
                    "data": "bbs_content",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 4,
                    "data": "bbs_comment",
                    "render": function ( data, type, row, meta ) {
                        if (!data) {
                            data = "-";
                        }
                        return data;
                    }
                },
                {
                    "targets": 5,
                    "data": "bbs_comment",
                    "render": function ( data, type, row, meta ) {
                        if (data) {
                            data = "답변 완료";
                        } else {
                            data = "미답변";
                        }
                        return data;
                    }
                },
                {
                    "targets": 6,
                    "data": "adm_nnm",
                    "render": function ( data, type, row, meta ) {
                        if (!data) {
                            data = "-";
                        }
                        return data;
                    }
                },
                {
                    "targets": 7,
                    "data": "ins_dttm",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    "targets": 8,
                    "data": "upt_dttm",
                    "render": function ( data, type, row, meta ) {
                        if (!data) {
                            data = "-";
                        }
                        return data;
                    }
                },
            ],
            order : [[ 0, "desc"]]
        });

        $('#dataTable3 tbody tr').addClass("cursor");

        // 팝업 포인터
        $('.btn_popup').css('cursor', "pointer");

        $('#dataTable3 tbody').on('click', 'tr', function () {
            var contact_seq = table.row( this ).data();
            contact_seq = contact_seq.seq;


            var params = {
                "contact_seq" : contact_seq
            };

            req(system_config, '/Ajax/ajax_contact_popup', {
                json : true,
                data : params,
                callback:function(rtn) {
                    if(rtn.result > 0){
                        $('.buy_popup').addClass('active');
                        // 닉네임, 아이디
                        $('.nick').text(rtn.unick + " ( " + rtn.uemail + " )");
                        // 문의 제목
                        $('.title').text(rtn.bbs_subject);
                        // 문의 내용
                        $('.content').text(rtn.bbs_content);
                        // 답변
                        // 답변상태
                        var state = "";
                        var comment = "";
                        if (rtn.bbs_comment) {
                            state = "답변완료";
                            comment = rtn.bbs_comment;
                        } else {
                            state = "미답변";
                            comment = "";
                        }

                        $('.comment').text(comment);
                        $('.state').text(state);
                        // 관리자
                        var adm = "";
                        if (rtn.adm_nnm) {
                            adm = rtn.adm_nnm;
                        } else {
                            adm = "-";
                        }
                        $('.adm_nnm').text(adm);
                        // 문의작성 일시
                        $('.ins_dttm').text(rtn.ins_dttm);
                        // 문의 답변일시
                        if (rtn.upt_dttm) {
                            $('.upt_dttm').text(rtn.upt_dttm);
                        } else {
                            $('.upt_dttm').text('-');
                        }

                        if (rtn.bbs_comment) {
                            $('.btn_save').html("답변 수정");
                        }

                        $('.btn_save').attr('data-seq', rtn.contact_seq);
                    }else{
                        alert(rtn.err);
                    }
                }
            });
        });

        // 취소
        $('.cancel').on("click", function() {
            $('.buy_popup').removeClass('active');
        });

        // 답변 수정 & 작성
        $('.btn_save').on('click', function() {
            var contact_seq = $('.btn_save').data("seq");
            var comment = $(".comment").val();

            if(!validateComment($(".comment").val())) {
                alert_layer("답변은 최소 5글자 이상 작성해주세요.");
                return;
            }

            var level = "<?=$level?>";

            if (level == 3) {
                alert_layer("답변 작성 권한이 없습니다.");
                return;
            }

            var params = {
                "contact_seq" : contact_seq,
                "comment" : comment
            };

            //loading_layer_open();
            req(system_config, '/Ajax/update_contact_ajax', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){
                        notify_layer("답변작성이 완료되었습니다.");

                        location.replace("/Main/contact_list");

                    }else{
                        notify_layer(rtn.err);
                    } //result if
                }
            });

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
            <div class="card-header">문의답변 대기 목록</div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable3" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>닉네임</th>
                            <th>제목</th>
                            <th>내용</th>
                            <th>답변</th>
                            <th>답변상태</th>
                            <th>관리자</th>
                            <th>작성일시</th>
                            <th>답변일시</th>
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
                <h4>문의 상세내역</h4>
                <i class="far fa-times cancel"></i>
            </div>
            <div class="buy_popup_content_body">
                <table>
                    <tr>
                        <td>닉네임 ( 이메일 )</td>
                        <td class="nick"></td>
                    </tr>
                    <tr>
                        <td>문의 제목</td>
                        <td class="title"></td>
                    </tr>
                    <tr>
                        <td>문의 내용</td>
                        <td>
                            <textarea class="content" style="resize: none; width: 100%; height: 150px; padding: 10px; border: 1px solid #dddddd;" disabled></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>답변</td>
                        <td>
                            <textarea class="comment" placeholder="답변을 작성해주세요." style="resize: none; width: 100%; height: 150px; padding: 10px; border: 1px solid #dddddd;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>답변상태</td>
                        <td class="state"></td>
                    </tr>
                    <tr>
                        <td>관리자</td>
                        <td class="adm_nnm"></td>
                    </tr>
                    <tr>
                        <td>작성일시</td>
                        <td class="ins_dttm"></td>
                    </tr>
                    <tr>
                        <td>답변일시</td>
                        <td class="upt_dttm"></td>
                    </tr>
                </table>


            </div>
            <div class="buy_popup_content_footer">
                <a class="btn btn-cancel cancel">닫기</a>
                <?php if ($level != 3) { ?>
                    <a class="btn btn-normal btn_save">답변 작성</a>
                <? } ?>

            </div>
        </div>
    </div>
</div>



<!-- Custom scripts for this page-->
<script src="<?=ASSETS;?>/js/admin-datatables.js?version=<?=_VERSION?>"></script>


<!-- e: Main Contents -->