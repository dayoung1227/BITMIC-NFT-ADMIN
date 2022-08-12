</div>
<!-- s: footer-->
<footer class="sticky-footer">
    <div class="container">
        <div class="text-center">
            <small>Copyright ©<?=COPYRIGHT?> 2022 All Rights Reserved.</small>
        </div>
    </div>
</footer>
<!-- e: footer-->
<!-- s:Scroll to Top Button-->
<a class="scroll-to-top rounded btn-default" href="#page-top">
    <i class="fa fa-angle-up"></i>
</a>
<!-- e:Scroll to Top Button-->
</div>
<!-- e: Main Body-->


<!-- Page level plugin JavaScript-->
<script src="<?=ASSETS;?>/vendor/chart.js/Chart.min.js"></script>
<script src="<?=ASSETS;?>/vendor/datatables/jquery.dataTables.js"></script>
<script src="<?=ASSETS;?>/vendor/datatables/dataTables.bootstrap4.js"></script>
<!--Perfect scrollbar.js-->
<script src="<?=ASSETS;?>/js/perfect-scrollbar.js"></script>


<!-- countdown js  -->
<script src="<?=ASSETS;?>/js/jquery.countdown.min.js"></script>


<!-- Custom scripts for all pages-->
<script src="<?=ASSETS;?>/js/admin.js?version=<?=_VERSION?>"></script>
<!-- Custom scripts for this page-->
<script src="<?=ASSETS;?>/js/admin-datatables.js?version=<?=_VERSION?>"></script>
<script src="<?=ASSETS;?>/js/admin-charts.min.js?version=<?=_VERSION?>"></script>



<!-- s:Alert Popup -->
<div class="modal fade" id="alert_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!--header-->
                <h5 class="modal-title cred"><i class="fa fa-exclamation-triangle"></i> Alert</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group contents w100rate alignC">
                        <!--s:Contents-->
                        asdfsdfsfasf
                        <!--e:Contents-->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- e:Alert Popup -->

<!-- s:Notice Popup -->
<div class="modal fade" id="notice_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg_yellow">
                <!--header-->
                <h5 class="modal-title"><i class="fa fa-check"></i> Notice</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group contents w100rate alignC">
                        <!--s:Contents-->
                        asdfsdfsfasf
                        <!--e:Contents-->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- e:Notice Popup -->


<!-- s:Notice Popup -->
<div class="modal fade" id="swap_confirm_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg_info">
                <!--header-->
                <h5 class="modal-title text_white"><i class="fa fa-check"></i> Swap Information</h5>
                <button class="close text_white"  type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <p class="pdL30 bold">Account : <span class="msg_swap_uid"></span></p>
                <p class="pdL30 bold txt_blue2">swap Amount : <span class="msg_swap_amount"></span></p>
                <p class="pdL30 bold">Address : <span class="msg_swap_wallet"></span></p>
                <p class="pdL30 bold">Swap Description</p>
                <pre class="pdL30 msg_swap_desc"></pre>
                <BR>
                <p class="pdL30 alignC bold">위 사항이 틀림없나요?</p>

            </div>
            <div class="modal-footer">
                <div class="margin0auto  alignC">
                    <button type="button" class="btn pading_custom01 btn_ok btn-info mgR50" aria-disabled="false">Swap It!</button>

                    <button type="button" class="btn pading_custom01 btn_cancel btn-red" data-dismiss="modal" aria-label="Close" aria-disabled="false">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- e:Notice Popup -->


<div id="menu-lodingbar" style="display: none" data-menu-height="90" class="menu menu-box-modal">
    <div class="popup-menu-scroll">
        <img src="/assets/images/ajax-loader.gif?sss" class="icon-center">
    </div>
</div>


</body>

</html>
