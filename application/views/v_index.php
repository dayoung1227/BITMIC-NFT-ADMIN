
<!-- s: Main Contents -->
<?php if ($level == 0 || $level == 1) { ?>
    <div class="row page_title">
        <div class="col-sm-6 responsive-sm">
            <h4>Dashboard</h4>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4 index-box">
            <div class="box bg_red">
                <div class="box-head align-items-center justify-content-between">
                    <h2><b><?=number_format($week_inout).' 원'?></b></h2>
                    <i class="fas fa-money-check-edit-alt"></i>
                </div>
                <span>최근 7일간 입금</span>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4 index-box">
            <div class="box bg_info">
                <div class="box-head align-items-center justify-content-between">
                   <h2><b><?=number_format($week_trans, 5).' '.COIN?></b></h2>
                    <i class="fad fa-exchange-alt"></i>
                </div>
                <span>최근 7일간  전송</span>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4 index-box responsive-sm">
            <div class="box bg_secondary">
                <div class="box-head align-items-center justify-content-between">
                    <h2><b><?=number_format($total_cnt).' 명'?></b></h2>
                    <i class="fad fa-users"></i>
                </div>
                <span>전체 가입자 수</span>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">최근 가입한 회원목록</div>
                <div class="card-body pl-0 pr-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table_s1" width="100%" id="dataTable1" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="responsive-sm">No</th>
                                <th>ID</th>
                                <th class="responsive-sm">닉네임</th>
                                <th class="responsive-sm">보유 수량</th>
                                <th class="responsive-sm">지갑 주소</th>
                                <th class="responsive-sm">가입 상태</th>
                                <th>회원가입일시</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($join_list) {
                                 foreach ($join_list as $rows){

                                 if ($rows["balance"] != 0) {
                                     $rows["balance"] = sprintf('%s', number_format($rows["balance"], 5, '.', ','));
                                 } else {
                                     $rows["balance"] = '-';
                                 }

                                $rows["curr_state"] = "<span class='badge-success badge-pill'>ACTIVE</span>";

                                if($rows["join_status"] == 'W'){
                                    $rows["curr_state"] = "<span class='badge-warning badge-pill'>WAITING</span>";
                                }else if($rows["curr_state"] == 'D'){
                                    $rows["curr_state"] = "<span class='badge-danger badge-pill'>DELETE</span>";
                                }

                                ?>
                                <tr>
                                    <td class="alignR responsive-sm"><?=$rows["seq"];?></td>
                                    <td><?=$rows["uid"];?></td>
                                    <td class="responsive-sm"><?=$rows["unick"];?></td>
                                    <td class="responsive-sm alignR"><?=$rows["balance"];?></td>
                                    <td class="responsive-sm"><?=$rows["wallet_addr"];?></td>
                                    <td class="alignC responsive-sm"><?=$rows["curr_state"]?></td>
                                    <td><?=$rows["join_dttm"];?></td>
                                </tr>
                            <? } } else { ?>
                                <tr style="text-align: center; font-size: 15px; font-weight: bold;">
                                    <td colspan="7" style="padding: 35px 0">최근 가입한 회원이 없습니다.</td>
                                </tr>
                            <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">최근 전송 목록</div>
                <div class="card-body pl-0 pr-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table_s1 " id="dataTable2" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="responsive-sm">No</th>
                                <th class="responsive-sm">보낸 사람</th>
                                <th class="responsive-sm">받는 사람</th>
                                <th class="responsive-sm">수량</th>
                                <th class="responsive-sm">전송 일시</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($trans_list as $row){
                                $row["amount"] = $row["amount"];
                                $row["fee"] = $row["fee"];
                                $send_amount = sprintf('%s', number_format($row["amount"], 5, '.', ','));
                                $fee = sprintf('%s', number_format($row["fee"], 5, '.', ','));
                                $send_account = $row["sunick"];
                                $receive_account = $row["runick"];

                                $v_w_color = "";
                                $v_d_color = "";
                                if($receive_account === '----' || $receive_account === null){
                                    $receive_account = '외부출금';
                                    $v_w_color = "color_withdrawal";
                                }

                                if($send_account === '----' || $send_account === null){
                                    $send_account = '외부입금';
                                    $v_d_color = "color_deposit";
                                }
                                ?>
                                <tr>
                                    <td class="responsive-sm"><?=$row["seq"];?></td>
                                    <td class="<?php echo $v_d_color;?> responsive-sm"><?=$send_account;?></td>
                                    <td class="<?php echo $v_w_color;?> responsive-sm"><?=$receive_account;?></td>
                                    <td class="alignR responsive-sm"><?=$send_amount;?> <?=COIN?></td>
                                    <td class="responsive-sm"><?=$row["trans_time"];?></td>
                                </tr>
                            <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- e: Main Contents -->

<?php } else { ?>
    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
<?php } ?>

