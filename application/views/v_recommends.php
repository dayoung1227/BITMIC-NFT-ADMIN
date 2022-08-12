<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {

 /*       $('.curr_user_info').addClass("cursor");

        $('.curr_user_info').on("click", function(){
            var save_params = {
                "seq" : $(this).data("seq"),
                "search_type" : search_type,
                "search_str" : search_str,
                "order_type" : order_type,
                "order_asc" : order_asc,
                "curr_page" : curr_page
            };
            req(system_config, '/Main/detail_memberinfo',{data:save_params, div : change_div});
        });
*/


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
            <div class="card-header">Member List</div>
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
                                    <option value="search_type_b">코드</option>
                                    <option value="search_type_c">결과</option>
                                    <option value="search_type_c">Admin ID</option>
                                </select>

                                <input type="search" class="form-control form-control-sm cinput01 search_str col-7" placeholder="write string">
                                <button class="btn-search col-1 search_btn" aria-hidden="true"><i class="fa fa-search" style="font-weight: bold;"></i></button>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 dataTable" id="userdata" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="board_order responsive-sm">No.</th>
                            <th class="board_order board_order_2" data-order="board_order_2">코드</th>
                            <th class="board_order responsive-sm board_order_3" data-order="board_order_3">결과</th>
                            <th class="board_order board_order_4" data-order="board_order_4">IP</th>
							<th class="responsive-sm"  data-order="board_order_5">날짜</th>
                            <th class="responsive-sm" data-order="board_order_6">ID</th>
                            <th class="responsive-sm">코드 설명</th>
                        </tr>
                        </thead>


                        <tbody>
                        <?foreach ($list_data as $rows){

                            $rows["act_code"] = "<span class='badge-success badge-pill'>ACTIVE</span>";

                            if($rows["act_code"] == 'W'){
                                $rows["act_code"] = "<span class='badge-warning badge-pill'>WAITING</span>";
                            }else if($rows["act_code"] == 'D'){
                                $rows["act_code"] = "<span class='badge-danger badge-pill'>DELETE</span>";
                            }

                            ?>
                            <tr class="curr_user_info" data-seq="<?=$rows["seq"];?>">
                                <td class="alignR responsive-sm"><?=$rows["seq"];?></td>
                                <td class="responsive-sm"><?=$rows["act_code"];?></td>
                                <td class="alignR"><?=$rows["act_result"];?></td>
								<td class="alignR responsive-sm"><?=$rows["log_ip"];?></td>
                                <td class="responsive-sm"><?=$rows["log_dttm"];?></td>
                                <td class="alignC responsive-sm"><?=$rows["adm_id"];?></td>
                                <td class="responsive-sm"><?=$rows["cd_desc"];?></td>
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

<!-- e: Main Contents -->