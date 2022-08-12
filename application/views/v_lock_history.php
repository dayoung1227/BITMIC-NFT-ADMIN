<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

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
        })
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
            <div class="card-header"><?=$mnu;?></div>
            <div class="card-body pl-0 pr-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="responsive-sm">No</th>
                            <th>Account</th>
                            <th class="responsive-sm">Status</th>
                            <th>Lock Amount</th>
                            <th class="responsive-sm">Descripsion</th>
                            <th class="responsive-sm">Act Admin</th>
                            <th class="responsive-sm">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach($history_data as $row){
                            $row["lock_amount"] = number_format($row["lock_amount"],5);
                            if($row["send_yn"] === "Y"){
                                $row["send_yn"] = '<td><span class="badge-success badge-pill">'.$row["cd_desc"].'</span></td>';
                            }else{
                                $row["send_yn"] = '<td><span class="badge-danger badge-pill">'.$row["cd_desc"].'</span></td>';
                            }


                            ?>
                            <td data-seq="<?=$row["user_seq"];?>" class="gotouserdetail cursor">
                                <td class="responsive-sm"><?=$row["seq"];?></td>
                                <td><?=$row["uid"];?></td>
                                <td class="responsive-sm"><?=$row["send_yn"];?></td>
                                <td class="alignR"><?=$row["lock_amount"];?> <?=COIN?></td>
                                <td class="responsive-sm"><?=$row["lock_desc"];?></td>
                                <td class="responsive-sm"><?=$row["adm_id"];?>(<?=$row["adm_nnm"];?>)</td>
                                <td class="responsive-sm"><?=$row["ins_dttm"];?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom scripts for this page-->
<script src="<?=ASSETS;?>/js/admin-datatables.js?version=<?=_VERSION?>"></script>

