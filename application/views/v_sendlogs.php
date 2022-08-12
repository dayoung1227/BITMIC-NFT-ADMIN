<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {

        $(".gotouserdetail").on("click", function(){
            var seq = $(this).data("seq");

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
            if(this_mnu.hasClass("nav-item")){//단일
                $(".nav-item").children(".navbar-sidenav .sidenav-second-level").removeClass("show");
                $(".nav-link").addClass("collapsed");
            }
            this_mnu.addClass("active");

            req(system_config, '/Main/detail_memberinfo',{data:save_params, div : "main_area"});
        });



    });

</script>




<div class="row page_title">
    <div class="col-sm-6">
        <h4><?=$mnu;?></h4>
    </div>
    <div class="col-sm-6">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html">Home</a>
            </li>
            <li class="breadcrumb-item active"><?=$mnu;?></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Send Logs</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="alignR">
                            <label class="clabel01">Search:
                                <select name="search_type" class="form-control form-control-sm cinput01 search_type">
                                    <option value="search_type_a">Send Account</option>
                                    <option value="search_type_b">Receive Account</option>
                                </select>
                                <input type="search" class="form-control form-control-sm cinput01 search_str" placeholder="write string">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 dataTable" id="userdata" width="100%" cellspacing="0">
                        <thead>
                        <tr>
<!--                             <th class="board_order" data-order="board_order_1">BlockHeight</th> -->
                            <th>TXID</th>
                            <th class="board_order" data-order="board_order_2">Sender</th>
                            <th>Send Amount</th>
                            <th>Send Fee</th>
                            <th class="board_order" data-order="board_order_3">Receiver</th>
                            <th class="board_order" data-order="board_order_4">Date</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
<!--                             <th>BlockHeight</th> -->
                            <th>TXID</th>
                            <th>Sender</th>
                            <th>Send Amount</th>
                            <th>Send Fee</th>
                            <th>Receiver</th>
                            <th>Date</th>
                        </tr>
                        </tfoot>


                        <tbody>
                        <?foreach ($list_data as $rows){

                            $rows["amount"] = $rows["amount"];
                            $rows["coin_fee"] = $rows["coin_fee"];
                            $send_amount = sprintf('%s', number_format($rows["amount"], 8, '.', ','));
                            $send_fee = sprintf('%s', number_format($rows["coin_fee"], 8, '.', ','));
                            $send_account = $rows["saccount"];
                            $receive_account = $rows["raccount"];

                            if($receive_account === '()'){
                                $receive_account = 'Unknown';
                            }
                            ?>
                            <tr>
<!--                                 <td class="alignR"><?=$rows["blockheight"];?></td> -->
                                <td><?=$rows["txid"];?></td>
                                <td><?=$send_account;?></td>
                                <td class="alignR"><?=$send_amount;?></td>
                                <td class="alignR"><?=$send_fee;?></td>
                                <td><?=$receive_account;?></td>
                                <td><?=$rows["ins_dttm"];?></td>
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


