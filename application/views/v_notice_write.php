
<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        // 글 수정
        $(".btn_save").on("click", function() {
            var title = $("#i_title").val();
            var content = $(".ql-editor").html();

            if (title.length < 5 || title.length > 20) {
                alert_layer('제목은 5~20 글자 안으로 작성해 주세요.');
                return;
            }

            if (content.length < 10) {
                alert_layer('내용은 10 글자 이상으로 작성해 주세요.');
                return;
            }

            var params = {
                "title" : title,
                "content" : content,
            };

            //loading_layer_open();
            req(system_config, '/Ajax/insert_notice_ajax', {
                json : true,
                data:params,
                callback:function(rtn) {
                    //loading_layer_close();
                    if(rtn.result > 0){
                        notify_layer("공지사항 글을 작성했습니다.");
                        setTimeout(function () {
                            location.replace("/Main/notice_list");
                        }, 1000);
                    }else{
                        alert_layer(rtn.err);
                    } //result if
                }
            });
        });

        $(".btn_back").on("click", function() {
            location.replace("/Main/notice_list");
        });

        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    [{header: [1, 2, false]}],
                    ['bold', 'italic', 'underline'],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                ]
            },
            placeholder: '내용은 10글자 이상 작성해 주세요.',
            theme: 'snow'  // or 'bubble'
        });

    });



    function fnc_popover(this_id, msg, color){
        $('#' + this_id).html(msg);
        $('#' + this_id).css({"color": color});
    }
</script>

<div class="row page_title">
    <div class="col-sm-6">
        <h4><i class="btn_back fal fa-arrow-left" style="cursor: pointer;"></i>&nbsp;&nbsp; <?=$mnu;?></h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">공지사항 작성</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>공지사항 제목</label>
                        <input type="text" class="form-control" id="i_title" placeholder="제목은 5~20 글자 안으로 작성해 주세요." >
                        <p class="warning_txt" id="utitle_txt"></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12"></div>
                <div class="col-xl-6 col-lg-12  col-sm-12">
                    <div class="form-group">
                        <label>공지사항 내용</label>
                        <div id="editor-container" style=" height: 375px;">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12  col-sm-12"></div>
                <?php if ((int) $level < 2) { ?>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-default mt-2 btn_save" style="width: 30%;">저장</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
