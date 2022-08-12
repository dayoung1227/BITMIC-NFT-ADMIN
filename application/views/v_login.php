<?=doctype();?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no" />
    <meta name="author" content="P2PBlock Console">
    <meta name="generator" content="P2PBlock Console">
    <meta name="description" content="P2PBlock Console">
    <meta name="keywords" content="P2PBlock Console">
    <meta property="og:image" content="https://tadmin.p2pblock.io/assets/images/og.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:url" content="https://admin.p2pblock.io">
    <meta property="og:title" content="P2PBlock Console">
    <meta property="og:description" content="P2PBlock Console">
    <meta property="og:site_name" content="P2PBlock Console">
    <meta property="og:type" content="website">
    <title>P2PBlock Console</title>
    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=ASSETS;?>/favicon.ico">

    <!-- system core js -->
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/jquery.cookie.js?version=<?=_VERSION;?>"></script>

    <!-- user defined js -->
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/global.variable.js?version=<?=_VERSION;?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/common.func.js?version=<?=_VERSION;?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/clipboard.min.js?version=<?=_VERSION;?>"></script>

    <!-- system core css -->
    <link href="<?=ASSETS;?>/vendor/bootstrap/css/bootstrap.css?<?=_VERSION?>" rel="stylesheet">
    <link href="<?=ASSETS;?>/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="<?=ASSETS;?>/vendor/webfont/css/cryptocoins.css" rel="stylesheet" type="text/css">
    <link href="<?=ASSETS;?>/vendor/webfont/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="<?=ASSETS;?>/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">

    <!-- user defined css -->
    <link rel="stylesheet" href="<?=ASSETS;?>/css/theme_defined.css?version=<?=_VERSION?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/admin.css?version=<?=_VERSION?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/common.css?version=<?=_VERSION;?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/defined.css?version=<?=_VERSION;?>">
</head>

<script type="text/javascript">
var	is_clickact = true;
var click_cnt = 0;
var chk = "";

//초기화 하면 안되는 변수라서 여기 사용
$(document).ready(function($) {

	//alert($.cookie);
	if($.cookie("aid") != null){
	    //alert($.cookie("aid"));
		$("#i_uid").val($.cookie("aid"));
		$("input:checkbox[id='check_id_save']").prop("checked", true); /* by ID */
		$("#i_upw").focus();
	};


	$("#i_uid").keydown(function(e) {
        $('#i_uid').popover('hide');
		if( e.keyCode ===  13 ){
			var idaddress = $("#i_uid").val();
			if(validateId(idaddress)) {
                fnc_popend("uid_txt")
			} else {
                fnc_popover("uid_txt", "아이디를 다시 입력해주세요.", "red");
                return;
            }
		};
	});

    $("#i_uid").on("input propertychange paste change keyup focusout focuson keydown keypress" ,function (e) {
        var idaddress = $("#i_uid").val();
        if(validateId(idaddress)) {
            fnc_popend("uid_txt")
        } else {
            fnc_popover("uid_txt", "아이디를 다시 입력해주세요.", "red");
            return;
        }
    });


	$("#i_upw").keydown(function(e) {
        $('#i_upw').popover('hide');
		if( e.keyCode ===  13 ){
            var passwd = $("#i_upw").val();
			if( validatePassword(passwd)) {
                secure_send();
			} else {
                fnc_popover("upw_txt", "비밀번호를 다시 입력해주세요.", "red");
                $("#i_upw").val("");
            }
		};
	});

    $("#i_secure").keydown(function(e) {
        $('#i_secure').popover('hide');
        if( e.keyCode ===  13 ){
            var passwd = $("#i_secure").val();
            if(passwd) {
                go_login();
            }
        };
    });

    $("#btn_send").on("click", function () {
        if ($("#btn_send").hasClass("disabled") == false) {
            $("#btn_send").addClass("disabled");
            secure_send();
        }
    });
	/*------------------ real code is ended -------------------*/

	$('#btnlogin').on("click", function(){
		go_login();
	});

});

function secure_send() {
    if(!is_clickact) return;

    var params = {
        "uid" : $("#i_uid").val(),
        "upw" : $("#i_upw").val()
    };

    if(params.uid === '' || !validateId(params.uid)){
        $("#i_uid").focus();
        fnc_popover("uid_txt", "아이디를 다시 입력해주세요.", "red");
        $("#btn_send").removeClass("disabled");
        return;
    } else {
        if(params.upw === '' ){
            $("#i_upw").focus();
        }
        if(!validatePassword(params.upw)){
            fnc_popover("upw_txt", "비밀번호를 다시 입력해주세요.", "red");
            $("#btn_send").removeClass("disabled");
            return;
        }
    }

    is_clickact = false;

    req(system_config, '/Ajax/send_sms_ajax', {
        json : true,
        data:params,
        callback:function(rtn) {
            is_clickact = true;
            if(rtn.result > 0){
                fnc_popover("upw_txt", "인증번호가 등록된 휴대폰 번호로 전송되었습니다.", "blue");
                $("#btn_send").css({"color": "rgba(255, 255, 255, 0.5)", "background": "#a7c2dd"});
                $("#i_uid, #i_upw").attr("readonly",true);

                chk = rtn.secure;
            }else{
                //tooltip 참고해야 할듯
                if(rtn.result === -1) {
                    fnc_popover("uid_txt", rtn.err, "red");
                }else if(rtn.result === -2){
                    fnc_popover("upw_txt", rtn.err, "red");
                }else{
                    if(rtn.err !== '') {
                        alert_layer(rtn.err);
                    }else{
                        alert_layer('Unknown Error!');
                    }
                }
                $("#btn_send").removeClass("disabled");
                $("#i_uid, #i_upw").attr("readonly", false);
                //alertLayer('아이디나 패스워드를<BR>다시한번 확인해주세요');
            } //result if
        }
    });
}


function fnc_popover(this_id, msg, color){
    $('#' + this_id).html(msg);
    $('#' + this_id).css({"color": color});
}

function fnc_popend(this_id) {
    $('#' + this_id).html('');
}


function go_login(){
    if(!is_clickact) return;

    var params = {
        "uid" : $("#i_uid").val(),
        "upw" : $("#i_upw").val(),
        "secure" : $("#i_secure").val(),
        "chk" : chk,
        "id_save" : $('#check_id_save').is(":checked")
    };

    if(params.uid === '' || !validateId(params.uid)){
        $("#i_uid").focus();
        return;
    }else{
        if(params.upw === '' ){
            $("#i_upw").focus();
            return;
        } else if(!validatePassword(params.upw)){
            fnc_popover("upw_txt", "비밀번호를 다시 입력해주세요.", "red");
            return;
        } else {
            if (params.secure === '') {
                fnc_popover("secure_txt", "인증번호를 입력해주세요.", "red");
                return;
            }
        }
    }

    is_clickact = false;

    req(system_config, '/Ajax/login_ajax', {
        json : true,
        data:params,
        callback:function(rtn) {
            is_clickact = true;
            if(rtn.result > 0){
                if (rtn.alevel == 0 || rtn.alevel == 1) {
                    $(location).attr('href', "/Main/index");
                } else if (rtn.alevel == 2) {
                    $(location).attr('href', "/Main/recommends_list");
                } else if (rtn.alevel == 3) {
                    $(location).attr('href', "/Main/admin_recomm_list");
                }

            }else{
                //tooltip 참고해야 할듯
                if(rtn.result === -1) {
                    fnc_popover("uid_txt", rtn.err, "red");
                }else if(rtn.result === -2){
                    fnc_popover("upw_txt", rtn.err, "red");
                }else{
                    if(rtn.err !== '') {
                        fnc_popover("secure_txt", rtn.err, "red");
                        //alert_layer(rtn.err);

                    }else{
                        alert_layer('Unknown Error!');
                    }
                }
                //alertLayer('아이디나 패스워드를<BR>다시한번 확인해주세요');
            } //result if
        }
    });
}
</script>

<!--body-->
<body class="fixed-nav sticky-footer" id="page-top">
<div class="bg_image h-100">
    <div class="lr_wrap">
        <div class="card-body">
            <div class="lr_icon text-center">
                <img src="<?=LOGO_ICON?>" alt="logo" style="width: 80px;"/>
            </div>
            <h6 class="my-4 text-center text-uppercase">Welcome to P2PBLOCK Management</h6>
            <form name="myform" onsubmit="return false">
                <div class="form-group">
                    <label for="i_uid">관리자 ID</label>
                    <input class="form-control" id="i_uid" type="text" placeholder="아이디를 입력해주세요."  data-placement="bottom" title="" required autocomplete="off">
                    <p class="warning_txt" id="uid_txt"></p>
                </div>
                <div class="form-group">
                    <label for="i_upw">비밀번호</label>
                    <div class="row w-100 m-0">
                        <input type="password" id="i_upw" class="form-control w-75" required="" title="" placeholder="비밀번호를 입력해주세요." autocomplete="off">
                        <span class="btn btn-default pl-1 pr-1 w-25 pt-2 pb-1" id="btn_send" >보내기</span>
                        <p class="warning_txt" id="upw_txt"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="i_secure">인증번호</label>
                    <input class="form-control" id="i_secure" type="password" placeholder="인증번호 6자리를 입력해주세요."  data-placement="bottom" title="" required autocomplete="off">
                    <p class="warning_txt" id="secure_txt"></p>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" id="check_id_save"> 아이디 저장</label>
                    </div>
                </div>
                <button class="btn btn-default btn-block" id="btnlogin">Login</button>
                <!--a href="#" data-target="#alert_popup" data-toggle="modal" class="new-wallet">aaaaaaaa</a-->
            </form>
        </div>
    </div>
</div>

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

</body>
</html>
