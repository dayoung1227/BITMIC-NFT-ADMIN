<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;
    var page = '<?php echo $page?>';
    $(document).ready(function() {
        // 팝업 표시
        $('#userdata tbody').on('click', 'tr', function () {
            var buy_seq = $(this).children();
            buy_seq = buy_seq[0];
            buy_seq = $(buy_seq).data("seq");

            var buy_params = {
                "buy_seq" : buy_seq,
                "type" : "buy"
            };

            req(system_config, '/Ajax/ajax_popup_list', {
                json : true,
                data : buy_params,
                callback:function(rtn) {
                    if(rtn.result > 0){
                        $('.buy_popup').addClass('active');
                        // 닉네임, 아이디
                        $('.nick').text(rtn.nick + " ( " + rtn.id + " )");
                        // 신청 수량
                        $('.amount').text(rtn.amount_price + " 원");

                        // 추천인
                        $('.recomm').text(rtn.recomm);
                        // 입금자
                        $('.account_name').text(rtn.account);
                        // 핸드폰번호
                        var phone = rtn.phone;
                        phone = phone.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
                        $('.phone').text("+" + rtn.phone_country + ") " + phone);
                        // 보유 수량
                        if (rtn.balance == 0) {
                            $('.balance').text('-');
                        } else {
                            $('.balance').text(rtn.balance + " <?=COIN;?>");
                        }
                        // 신청일시
                        $('.dttm').text(rtn.ins_dttm);
                        // 상태
                        var state = "";
                        var state_txt = "";
                        if (rtn.adw_state == "C") {
                            state = "<span class='badge-danger badge-pill'>취소</span>";
                            state_txt = "-";
                        } else if (rtn.adw_state == "S") {
                            state = "<span class='badge-success badge-pill'>성공</span>";
                            state_txt = rtn.coin_amount + " <?=COIN;?>";
                        } else {
                            state = "<span class='badge-info badge-pill'>대기</span>";

                            state_txt = rtn.coin_amount + " <?=COIN;?>";
                        }
                        // 전환 수량
                        // 수량
                        $('.coin').text(state_txt);
                        $(".state").html(state);
                        // 상태 변경 일시
                        $('.upt_dttm').text(rtn.upt_dttm ? rtn.upt_dttm : "-");

                        $('.btn_save').attr('data-seq', rtn.buy_seq);
                        $('.btn_save').attr('data-state', rtn.adw_state);
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


        $("#datepicker1,#datepicker2").datepicker({
            dateFormat: 'yy-mm-dd' //달력 날짜 형태
            ,showOtherMonths: true //빈 공간에 현재월의 앞뒤월의 날짜를 표시
            ,showMonthAfterYear:true // 월- 년 순서가아닌 년도 - 월 순서
            ,changeYear: true //option값 년 선택 가능
            ,changeMonth: true //option값  월 선택 가능
            ,showOn: "both" //button:버튼을 표시하고,버튼을 눌러야만 달력 표시 ^ both:버튼을 표시하고,버튼을 누르거나 input을 클릭하면 달력 표시
            ,buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif" //버튼 이미지 경로
            ,buttonImageOnly: true //버튼 이미지만 깔끔하게 보이게함
            ,buttonText: "선택" //버튼 호버 텍스트
            ,yearSuffix: "년" //달력의 년도 부분 뒤 텍스트
            ,monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'] //달력의 월 부분 텍스트
            ,monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'] //달력의 월 부분 Tooltip
            ,dayNamesMin: ['일','월','화','수','목','금','토'] //달력의 요일 텍스트
            ,dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토요일'] //달력의 요일 Tooltip
            ,minDate: "-5Y" //최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전)
            ,maxDate: "0" //최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후)
        });

        //초기값을 오늘 날짜로 설정해줘야 합니다.
        //$('#datepicker2').datepicker('setDate', 'today'); //(-1D:하루전, -1M:한달전, -1Y:일년전), (+1D:하루후, -1M:한달후, -1Y:일년후)

        // 날짜별
        $("#btn_change").on("change",function(){
            var date = $("select[name='search_type']").val();

            if (date == "search_type_c" || date == "search_type_d" || date == "search_type_e") {
                $(".date").removeClass("none");
                $(".search_input").addClass("none");
                $(".search_input").val("");
            } else {
                $(".date").addClass("none");
                $(".search_input").removeClass("none");
                $(".search_input").val("");
            }
        });

        var date = $("select[name='search_type']").val();
        if (date == "search_type_c" || date == "search_type_d" || date == "search_type_e") {
            $(".date").removeClass("none");
            $(".search_input").addClass("none");
            $(".search_input").val("");
        }
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
            <div class="card-header">전체 구매 목록</div>
            <div class="card-body pl-0 pr-0">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="alignR">
                            <label class="clabel01">
                                <select name="search_type" class="form-control form-control-sm cinput01 search_type col-3" id="btn_change">
                                    <option value="search_type_a">닉네임</option>
                                    <option value="search_type_b">추천인</option>
                                    <option value="search_type_c">신청일</option>
                                    <option value="search_type_d">승인일</option>
                                    <option value="search_type_e">취소일</option>
                                    <option value="search_type_f">구매상태</option>
                                </select>
                                <span class="date none" >
                                    <input  type="text" id="datepicker1" class="search_str1" style="width: 110px;"> ~
                                    <input  type="text" id="datepicker2" class="search_str2" style="width: 110px;">
                                </span>
                                <input type="search" class="form-control form-control-sm cinput01 search_str col-7 search_input" placeholder="search">
                                <button class="btn-search col-1 search_btn" aria-hidden="true"><i class="fa fa-search" style="font-weight: bold;"></i></button>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 dataTable" id="userdata" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>닉네임</th>
                            <th>입금정보</th>
                            <th>구매요청</th>
                            <th>추천인</th>
                            <th>구매 상태</th>
                            <th>구매신청일시</th>
                            <th>승인/취소일시</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($list_data as $key => $rows){

                            $state = "";
                            if($rows["adw_state"] === 'W'){ // 대기
                                $state = "<span class='badge-info badge-pill'>대기</span>";
                            } else if($rows["adw_state"] === 'S'){
                                $state = "<span class='badge-success badge-pill'>성공</span>";
                            } else if($rows["adw_state"] === 'C') {
                                $state = "<span class='badge-danger badge-pill'>취소</span>";
                            }
                            ?>

                            <tr>
                                <td class="responsive-sm buy_seq" data-seq="<?=$rows["seq"]?>"><?=$page == 1 ? $key + 1 : (10 * ($page - 1)) + ($key + 1);?></td>
                                <td class="responsive-sm"><?=$rows["unick"];?></td>
                                <td class="responsive-sm"><?=" 입금자 : " .$rows["account"]. " ";?></td>
                                <td class="responsive-sm"><?=number_format($rows["amount"])." 원";?></td>
                                <td class="responsive-sm"><?=$rows["adm_nnm"];?></td>
                                <td class="responsive-sm"><?=$state;?></td>
                                <td class="responsive-sm"><?= $rows["ins_dttm"] ?></td>
                                <td class="responsive-sm"><?= $rows["upt_dttm"] ? $rows["upt_dttm"] : '-';?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>

                <?php echo $page_info["navi_str"];?>

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
                    <tr class="bold">
                        <td>닉네임</td>
                        <td class="nick"></td>
                    </tr>
                    <tr>
                        <td>추천인</td>
                        <td class="recomm"></td>
                    </tr>
                    <tr class="bold">
                        <td>구매요청</td>
                        <td class="amount"></td>
                    </tr>
                    <tr>
                        <td>지급 <?=COIN;?> 수량</td>
                        <td class="coin"></td>
                    </tr>
                    <tr class="bold">
                        <td>입금자</td>
                        <td class="account_name"></td>
                    </tr>
                    <tr class="bold">
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

