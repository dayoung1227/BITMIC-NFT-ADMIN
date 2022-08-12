<script type="text/javascript">
    $(document).ready(function() {
        $(".btn_copy").on("click", function(){
            var copy_address = new Clipboard(".btn_copy");
            notify_layer("복사하였습니다.");
        });
    });
</script>
<div class="row page_title">
    <div class="col-sm-6">
        <h4><?=$mnu;?></h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header"><?=COIN?> 토큰 보유 현황</div>
            <div class="card-body user_info">
                <div class="col-xl-6 col-lg-12 col-sm-12">
                    <table class="table_coin">
                        <tbody>
                        <tr>
                            <td>Token Name</td>
                            <td> : &nbsp; <?=COIN?> Token</td>
                        </tr>
                        <tr>
                            <td>Token Symbol</td>
                            <td> : &nbsp; <?=COIN?></td>
                        </tr>
                        <tr>
                            <td>Network</td>
                            <td> : &nbsp; BNB Smart Chain Network</td>
                        </tr>
                        <tr>
                            <td>관리 지갑 주소</td>
                            <td class="btn_copy" data-clipboard-text="<?php echo ADW_ADDRESS;?>"> : &nbsp; <?=ADW_ADDRESS?>
                                <span class="pdL20"><i class="fal fa-copy"></i></span>
                            </td>
                        </tr>
                        <tr>
                            <td>BNB 보유량</td>
                            <td> : &nbsp; <?= (int) $result > 0 ? (double)((double)number_format($contact_coin,15, '.', ''))." "."BNB" : ' BNB 정보를가져오는데 실패했습니다. 새로고침 해주세요.';?>
                            </td>

                        </tr>
                        <?php if ($contact_coin <= 0.1) { ?>
                            <tr>
                                <td></td>
                                <td class="coin_warning">
                                    <i class="fal fa-exclamation-triangle"></i>&nbsp;  BNB 보유량이 너무 적습니다. BNB 충전이 필요합니다.
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><?=COIN?> 보유량</td>
                            <td> : &nbsp; <?= (int) $result > 0 ? (double)((double)number_format($contact_token,15, '.', ''))." ".COIN : COIN.' TOKEN 정보를가져오는데 실패했습니다. 새로고침 해주세요.';?></td>
                        </tr>
                        <?php if ($contact_token <= 100000) { ?>
                            <tr>
                                <td></td>
                                <td class="coin_warning">
                                    <i class="fal fa-exclamation-triangle"></i>&nbsp;  <?=COIN?> 보유량이 너무 적습니다. <?=COIN?> 충전이 필요합니다.
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
