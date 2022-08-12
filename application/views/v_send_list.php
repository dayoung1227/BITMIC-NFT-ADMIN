<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    $(document).ready(function() {
        $(".gotouserdetail").on("click", function(){
            var seq = $(this).children();
            seq = seq[0];
            seq = $(seq).data("seq");

            if(seq === "") {
                alert_layer("Member Not Found!");
                return;
            }

            var save_params = {
                "seq" : seq,
                "search_type" : "",
                "search_str" : "",
                "order_type" : "",
                "order_asc" : "",
                "curr_page" : 1
            };

            var this_mnu = $(".mnuwalletmembers");
            $(".nav-item").removeClass("active");
            $(".nav-item").find("li").removeClass("active");
            if(this_mnu.hasClass("nav-item")){//ë¨ěź
                $(".nav-item").children(".navbar-sidenav .sidenav-second-level").removeClass("show");
                $(".nav-link").addClass("collapsed");
            }
            this_mnu.addClass("active");

            req(system_config, '/Main/detail_memberinfo',{data:save_params, div : "main_area"});
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

            if (date == "search_type_e") {
                $(".date").removeClass("none");
                $(".search_input").addClass("none");
                $(".search_input").val("");
            } else {
                $(".date").addClass("none");
                $(".search_input").removeClass("none");
                $(".search_input").val("");
                $(".search_str1").val("");
                $(".search_str2").val("");
            }
        });

        var date = $("select[name='search_type']").val();
        if (date == "search_type_e") {
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
            <div class="card-header">Send List</div>
            <div class="card-body pl-0 pr-0">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="alignR">
                            <label class="clabel01">
                                <select name="search_type" class="form-control form-control-sm cinput01 search_type col-4" id="btn_change">
                                    <option value="search_type_a">보낸사람</option>
                                    <option value="search_type_b">받은사람</option>
                                    <option value="search_type_c">닉네임</option>
                                    <option value="search_type_d">txid</option>
                                    <option value="search_type_e">전송일</option>
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
                            <th class="board_order responsive-sm" data-order="board_order_1">No.</th>
                            <th>TXID</th>
                            <th class="board_order responsive-sm" data-order="board_order_2">Category</th>
                            <th class="responsive-sm">보낸 사람</th>
                            <th class="responsive-sm">받은 사람</th>
                            <th>보낸 수량</th>
                            <th class="responsive-sm">수수료</th>
                            <th class="board_order" data-order="board_order_3">전송받은 일시</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach ($list_data as $rows){
                            $rows["amount"] = sprintf('%s', number_format($rows["amount"], 5, '.', ','));
                            $rows["fee"] = sprintf('%s', number_format($rows["fee"], 6, '.', ','));


                            $sender_seq = "";
                            $receiver_seq = "";

                            $v_w_color = "";
                            $v_d_color = "";
                            foreach($member_data as $key => $row){
                                if($rows["runick"] == $row["unick"]){
                                    $receiver_seq = $row["seq"];
                                }

                                if($rows["sunick"] == $row["unick"]){
                                    $sender_seq = $row["seq"];
                                }

                                if($rows["runick"] === '' || $rows["runick"] === null){
                                    $rows["runick"] = '외부출금';
                                    $v_w_color = "color_withdrawal";
                                }

                                if($rows["sunick"] === '' || $rows["sunick"] === null){
                                    $rows["sunick"] = '외부입금';
                                    $v_d_color = "color_deposit";
                                }
                            }

                            if($rows["category"] == "send"){
                                $rows["category"] = "<span class='badge-warning badge-pill'>SEND</span>";
                            }else if($swap_yn === "Y"){
                                $rows["category"] = "<span class='badge-info badge-pill'>SWAP</span>";
                            }else{
                                $rows["category"] = "<span class='badge-success badge-pill'>RECEIVE</span>";
                            }

                            $rows["amount"] = str_replace('.00000000','',$rows["amount"]);
                            $rows["fee"] = str_replace('.00000000','',$rows["fee"]);
                            ?>
                            <tr>
                                <td class="responsive-sm" data-seq="<?=$rows["seq"];?>"><?=$page == 1 ? $key + 1 : (10 * ($page - 1)) + ($key + 1);?></td>
                                <td>
                                    <?=substr($rows["txid"], 0, 10);?>...
                                </td>
                                <td class="responsive-sm"><?=$rows["category"];?></td>
                                <td class="gotouserdetail responsive-sm <?php echo $v_d_color;?>" style="cursor: pointer;"data-seq="<?=$sender_seq;?>"><?=$rows["sunick"];?></td>
                                <td class="gotouserdetail responsive-sm <?php echo $v_w_color;?>"  style="cursor: pointer;"data-seq="<?=$receiver_seq;?>"><?=$rows["runick"];?></td>
                                <td class="alignR"><?=$rows["amount"]." ".COIN;?></td>
                                <td class="alignR responsive-sm"><?=$rows["fee"]." ".COIN?></td>
                                <td><?=$rows["trans_time"];?></td>
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


