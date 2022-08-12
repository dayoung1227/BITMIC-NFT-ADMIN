  <script type="text/javascript">
      var order_type = "<?=$post_data["order_type"]?>";
      var order_asc = "<?=$post_data["order_asc"]?>";
      var search_type = "<?=$post_data["search_type"]?>";
      var search_str = "<?=$post_data["search_str"]?>";
      var curr_page = "<?=$post_data["curr_page"]?>";

      $(document).ready(function($) {

      });
  </script>

    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Buy & Sale List</div>
            <div class="card-body pl-0 pr-0">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div>
                            <!-- <a href="#" class="btn btn-primary pading_default btn_uptcoininfo">UPDATE COIN INFO</a> -->
                            <!--<a href="#" class="btn btn-primary pading_default btn_alluserlockup">전체 유저 락업 기능</a>-->
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 form-group">
                        <div class="responsive-align">
                            <label class="clabel01">
                                <select name="search_type" class="form-control form-control-sm cinput01 search_type col-3">
                                    <option value="search_type_a">ID</option>
                                    <option value="search_type_b">Category</option>
                                    <option value="search_type_c">Amount</option>
                                </select>

                                <input type="search" class="form-control form-control-sm cinput01 search_str col-7" placeholder="search">
                                <button class="btn-search col-1 search_btn" aria-hidden="true"><i class="fa fa-search" style="font-weight: bold;"></i></button>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 dataTable" id="userdata" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="board_order responsive-sm" data-order="board_order_1">No</th>
                            <th class="board_order" data-order="board_order_2">Category</th>
                            <th class="board_order responsive-sm" data-order="board_order_3">Amount</th>
                            <th class="board_order" data-order="board_order_4">Bank Info</th>
                            <th class="responsive-sm">Status</th>
                            <th class="responsive-sm">Date</th>
                        </tr>
                        </thead>


                        <tbody>
                        <?foreach ($list_data as $rows){

                            $v_amount = "";

                            if ($rows["adw_type"] == "W") {
                                $v_amount = number_format($rows["coin_amount"]);
                            } else if ($rows["adw_type"] == "D") {
                                $v_amount = number_format($rows["amount"]);
                            }

                            $rows["amount"] = sprintf('%s', number_format($rows["amount"]));
                            $rows["coin_amount"] = sprintf('%s', number_format($rows["coin_amount"]));

                            if($rows["adw_type"] == 'W'){ // 출금
                                $rows["adw_type"] = "<span class='badge-warning badge-pill'>Withdraw</span>";
                            }else if($rows["adw_type"] == 'D'){ // 입금
                                $rows["adw_type"] = "<span class='badge-danger badge-pill'>Deposit</span>";
                            }

                            if($rows["adw_state"] == 'W'){ // 대기
                                $rows["adw_state"] = "<span class='badge-success badge-pill'>대기</span>";
                            } else if($rows["adw_state"] == 'S'){
                                $rows["adw_state"] = "<span class='badge-success badge-pill'>성공</span>";
                            } else if($rows["adw_state"] == 'C') {
                                $rows["adw_state"] = "<span class='badge-danger badge-pill'>취소</span>";
                            }

                            ?>
                            <tr class="curr_user_info" data-seq="<?=$rows["seq"];?>">
                                <td class="alignR responsive-sm"><?=$rows["seq"];?></td>
                                <td><?=$rows["adw_type"];?></td>
                                <td class="responsive-sm"><?=$v_amount;?></td>
                                <td class="alignR"><?=$rows["bank_name"]." ".$rows["bank_num"]." ( ".$rows["account"]." )";?></td>
                                <td class="alignR responsive-sm"><?=$rows["adw_state"];?></td>
                                <td class="responsive-sm"><?=$rows["ins_dttm"];?></td>
                            </tr>
                        <?}?>

                        </tbody>
                    </table>
                </div>

                <?php echo $page_info["navi_str"];?>

            </div>
        </div>
    </div>
