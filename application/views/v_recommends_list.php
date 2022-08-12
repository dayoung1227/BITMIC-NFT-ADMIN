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
            if(this_mnu.hasClass("nav-item")){
                $(".nav-item").children(".navbar-sidenav .sidenav-second-level").removeClass("show");
                $(".nav-link").addClass("collapsed");
            }
            this_mnu.addClass("active");

            req(system_config, '/Main/detail_memberinfo',{data:save_params, div : "main_area"});
        });

        $(".btn_user_control").on("click", function () {
            location.replace('/Main/user_control')
        });

        $('#userdata tbody').on('click', 'tr', function () {
            var user_seq = $(this).children();
            user_seq = user_seq[0];
            user_seq = $(user_seq).data("seq");

            var save_params = {
                "seq" :user_seq,
                "type" : 'recomm_list',
            };
            req(system_config, '/Main/detail_memberinfo',{data:save_params});
        });

/*        $(".btn_uptcoininfo").on("click",function(){
            var params = {};
            req(system_config, '/Ajax/set_membercoininfo', {
                json : true,
                data:params,
                callback:function(rtn) {
                    location.reload();
                }
            });
        });*/

        $(".btn_copy").on("click", function(){
            var copy_address = new Clipboard(".btn_copy");
            notify_layer("복사하였습니다.");
        });
    });

</script>

<div class="row page_title">
    <div class="col-sm-6">
        <?php if ($level == 3) { ?>
            <h4>추천인 URL : <?=$adm_domain?><i class="pl-3 btn_copy fal fa-copy" data-clipboard-text="<?php echo $adm_domain;?>"></i></h4>
        <?php } else { ?>
            <h4><?=$mnu;?></h4>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <?php if ($level != 3) { ?>
                <div class="card-header" style="display: flex; justify-content: space-between">전체 회원 목록</div>
            <?php } else { ?>
                <div class="card-header" style="display: flex; justify-content: space-between">회원 현황</div>
            <? }?>
            <div class="card-body pl-0 pr-0">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="alignR">
                            <label class="clabel01">
                                <select name="search_type" class="form-control form-control-sm cinput01 search_type col-3">
                                    <option value="search_type_a">닉네임</option>
                                    <option value="search_type_b">추천인</option>
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
                            <th>No</th>
                            <th>ID</th>
                            <th>닉네임</th>
                            <th>전화번호</th>
                            <th>보유 <?=COIN?></th>
                            <th>Lock D-day</th>
                            <th>추천인</th>
                            <th>Lock Y/N</th>
                            <th>가입일시</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($list_data as $key => $rows){ ?>
                            <tr style="cursor: pointer;">
                                <td class="responsive-sm" data-seq="<?=$rows["seq"];?>"><?=$page == 1 ? $key + 1 : (10 * ($page - 1)) + ($key + 1);?></td>
                                <td class="responsive-sm"><?=$rows["uid"];?></td>
                                <td class="responsive-sm"><?=$rows["unick"];?></td>
                                <td class="responsive-sm"><?=$rows["phone"] ? preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $rows["phone"]) : '-'?></td>
                                <td class="responsive-sm"><?=$rows["balance_hype"] == 0 ? '-' : number_format($rows["balance_hype"],5)." ".COIN;?></td>
                                <td class="responsive-sm"><?=$rows["lock_dday"] ? $rows["lock_dday"] : '-';?></td>
                                <td class="responsive-sm"><?= $rows["adm_nnm"];?></td>
                                <td class="responsive-sm"><?= $rows["send_yn"] == 'Y' ? 'N' : 'Y';?></td>
                                <td><?=$rows["join_dttm"];?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <?php echo $page_info["navi_str"];?>

            </div>
        </div>
    </div>
</div>


