<script type="text/javascript">
    $(document).ready(function($) {
        var offset = $(".sub_list_div").offset();
        $('html, body').animate({scrollTop : offset.top}, 400);
    });

</script>

    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Transactions List</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table_s1 " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>confirms</th>
                            <th>txid</th>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>txid</th>
                            <th>Type</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?foreach($history_data as $row){
                            $row["amount"] = number_format($row["amount"],2);

                            if($row["ttime_dttm"] > 5){
                                $row["status"] = '<td><span class="badge-success badge-pill">Completed</span></td>';
                            }else{
                                $row["status"] = '<span class="badge-warning badge-pill">Pending</span>';
                            }
                            if($row["category"] === 'receive'){
                                $row["category"] = '<td class="text-success alignC">Receive</td>';
                            }else{
                                $row["category"] = '<td class="text-danger alignC">Send</td>';
                            }
                            ?>
                            <tr>
                                <td><?=$row["ttime_dttm"];?></td>
                                <td class="alignR"><?=$row["amount"];?> <?=COIN?></td>
                                <?=$row["status"];?>
                                <td><?=$row["txid"];?></td>
                                <?=$row["category"];?>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<!-- Custom scripts for this page-->
<script src="<?=ASSETS;?>/js/admin-datatables.js?version=<?=_VERSION?>"></script>

