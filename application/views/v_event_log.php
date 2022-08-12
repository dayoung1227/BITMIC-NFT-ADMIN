<script type="text/javascript">
    var	is_clickact = true;
    var click_cnt = 0;

    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {

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
                            <th class="responsive-sm">Name</th>
                            <th>Action</th>
                            <th class="responsive-sm">Act IP</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach($history_data as $row){
                            ?>
                            <tr>
                                <td class="responsive-sm"><?=$row["seq"];?></td>
                                <td><?=$row["adm_id"];?></td>
                                <td class="responsive-sm"><?=$row["adm_nnm"];?></td>
                                <td><?=$row["log_cd"];?></td>
                                <td class="responsive-sm"><?=$row["act_ip"];?></td>
                                <td><?=$row["ins_dttm"];?></td>
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

