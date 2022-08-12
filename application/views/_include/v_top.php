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
    <meta property="og:image" content="https://admin.p2pblock.io/assets/images/og.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:url" content="https://admin.p2pblock.io">
    <meta property="og:title" content="P2PBlock Console">
    <meta property="og:description" content="P2PBlock Console">
    <meta property="og:site_name" content="P2PBlock Console">
    <meta property="og:type" content="website">
    <title>P2PBlock Console</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=ASSETS;?>/favicon.ico">

    <!-- system core js -->
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/jquery.cookie.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/jquery.number.js"></script>

    <!-- user defined js -->
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/global.variable.js?version=<?=_VERSION;?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/common.func.js?version=<?=_VERSION;?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/_defined/clipboard.min.js?version=<?=_VERSION;?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?=ASSETS;?>/js/zebra_datepicker.min.js?version=<?=_VERSION;?>" ></script>

    <!-- system core css -->
    <link href="<?=ASSETS;?>/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?=ASSETS;?>/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="<?=ASSETS;?>/vendor/webfont/css/cryptocoins.css" rel="stylesheet" type="text/css">
    <link href="<?=ASSETS;?>/vendor/webfont/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="<?=ASSETS;?>/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">

    <!-- user defined css -->
    <link rel="stylesheet" type="text/css" href="<?=FONT;?>/css/fontawesome-all.min.css?version=<?=_VERSION?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/theme_defined.css?version=<?=_VERSION?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/admin.css?version=<?=_VERSION?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/common.css?version=<?=_VERSION;?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/defined.css?version=<?=_VERSION;?>">
    <link rel="stylesheet" href="<?=ASSETS;?>/css/zebra_datepicker.css?version=<?=_VERSION;?>">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="<?=ASSETS;?>/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-yJpxAFV0Ip/w63YkZfDWDTU6re/Oc3ZiVqMa97pi8uPt92y0wzeK3UFM2yQRhEom" crossorigin="anonymous">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>

<script type="text/javascript">
    //초기화 하면 안되는 변수라서 여기 사용
    $(document).ready(function($) {
        var menu = "<?=$menu?>";
        var menu_depth = "<?=$menu_depth?>";
        var menu02 = "<?=$menu02?>";


        $(".nav-item").removeClass("active");
        if(menu_depth === "depth01"){
            $("."+menu).addClass("active");
        }else{
            if (!menu_depth) {
                $("."+menu).find("a").removeClass("collapsed");
                $("."+menu).find("ul").addClass("show");
            }
        }

        $(".ready").on("click", function () {
            alert_layer("준비 중 입니다.");
        });

        if(!$('.menu-hider').length){
            $('body').append('<div class="menu-hider"></div>');
        }

/*        $(".mnu_navigate").on("click", function(){
            var this_mnu = $(this).parent();
            var link_url = $(this).data("link");

            $(".nav-item").removeClass("active");
            $(".nav-item").find("li").removeClass("active");
            if(this_mnu.hasClass("nav-item")){//단일
                $(".nav-item").children(".navbar-sidenav .sidenav-second-level").removeClass("show");
                $(".nav-link").addClass("collapsed");
            }
            this_mnu.addClass("active");
            go_content(link_url, "main_area");
            $('.menu-hider').removeClass('active-menu-hider');
            $("#navbarResponsive").removeClass("show");
        });*/

        $(".mobile_menu").on("click", function () {
            $(".menu-hider").toggleClass("active-menu-hider");
            $(".active-menu-hider").on("click", function () {
                $("#navbarResponsive").removeClass("show");
                $(".menu-hider").removeClass("active-menu-hider");
                //return false;
            });
        });


        //$('.menu-hider').removeClass('active-menu-hider');

    });

</script>
<body class="fixed-nav sticky-footer" id="page-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="/Main">
            <img class="logo_icon" src="<?=LOGO_ICON?>" style="height: 43px;"/>
            <img class="logo" src="<?=LOGO_IMG?>"/>
        </a>
        <div class="mobile_menu">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-menu icons"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarheader">
            <ul class="navbar-nav">
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link text-center menubar" id="sidenavToggler">
                        <span class="line"></span>
                        <span class="line"></span>
                        <span class="line"></span>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto align-items-center">
            </ul>
        </div>
        <!-- s: LNB -->
        <div class="collapse navbar-collapse navbar-sidenav fc-scroll" id="navbarResponsive">
            <ul class="navbar-nav" id="exampleAccordion">
                <?php if ($level == 1 || $level == 0) { ?>
                    <li class="nav-item dashboard">
                        <a class="nav-link" href="/Main">
                            <i class="icon-grid icons"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($level != 3) { ?>
                    <li class="nav-item mnuwalletmembers member">
                        <a class="nav-link nav-link-collapse <?= $menu == "member" ? '' : 'collapsed'?>" data-toggle="collapse" href="#collapseExamplePages8" data-parent="#exampleAccordion">
                            <i class="far fa-address-book icons"></i>
                            <span class="nav-link-text">회원 목록</span>
                        </a>
                        <ul class="sidenav-second-level collapse <?= $menu == "member" ? 'show' : ''?>" id="collapseExamplePages8">
                             <li class="recommends_list <?= $menu02 == "recommends_list" ? 'active' : ''?>">
                                <a class="mnu_navigate" href="/Main/recommends_list">전체 회원 목록</a>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                <li class="nav-item mnutransactions admin_recomm">
                    <a class="nav-link mnu_navigate" href="/Main/admin_recomm_list">
                        <i class="icon-shuffle icons"></i>
                        <span class="nav-link-text">회원 현황</span>
                    </a>
                </li>
                <?php } ?>
                <li class="nav-item mnuwalletmembers contact">
                    <a class="nav-link nav-link-collapse <?= $menu == "contact" ? '' : 'collapsed'?>" data-toggle="collapse" href="#collapseExamplePages9" data-parent="#exampleAccordion">
                        <i class="fal fa-pen-square icons"></i>
                        <span class="nav-link-text">문의 목록</span>
                        <?php if ($level != 3 && $contact_cnt > 0) { ?>
                            <span class="badge-danger badge-pill" style="margin-left: 10%; font-size: 10px;text-align: center">new</span>
                        <?php } ?>
                    </a>
                    <ul class="sidenav-second-level collapse <?= $menu == "contact" ? 'show' : ''?>" id="collapseExamplePages9">
                        <?php if ($level == 0 || $level == 1) { ?>
                            <li class="contact_waiting_list <?= $menu02 == "contact_waiting_list" ? 'active' : ''?>">
                                <a class="mnu_navigate" href="/Main/contact_waiting_list">문의답변 대기
                                    <?php if ($contact_cnt > 0) { ?>
                                        <span class="badge-info badge-pill" style="margin-left: 5%; font-size: 10px; text-align: center; vertical-align: text-top;"><?= $contact_cnt?></span>
                                    <?php } ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="contact_list <?= $menu02 == "contact_list" ? 'active' : ''?>">
                            <a class="mnu_navigate" href="/Main/contact_list">전체 문의</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item mnutransactions buy">
                    <a class="nav-link nav-link-collapse <?= $menu == "buy" ? '' : 'collapsed'?>" data-toggle="collapse" href="#collapseExamplePages1" data-parent="#exampleAccordion">
                        <i class="icon-wallet icons"></i>
                        <span class="nav-link-text">구매</span>
                        <?php if ($level != 3 && $buy_cnt > 0) { ?>
                            <span class="badge-danger badge-pill" style="margin-left: 10%; font-size: 10px;text-align: center">new</span>
                        <?php } ?>
                    </a>
                    <ul class="sidenav-second-level collapse <?= $menu == "buy" ? 'show' : ''?>" id="collapseExamplePages1">
                        <?php if ($level == 0 || $level == 1) { ?>
                            <li class="buy_waiting_list <?= $menu02 == "buy_waiting_list" ? 'active' : ''?>">
                                <a class="mnu_navigate" href="/Main/buy_waiting_list">구매 대기자
                                    <?php if ($buy_cnt > 0) { ?>
                                        <span class="badge-info badge-pill" style="margin-left: 5%; font-size: 10px; text-align: center; vertical-align: text-top;"><?= $buy_cnt?></span>
                                    <?php } ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="buy_list <?= $menu02 == "buy_list" ? 'active' : ''?>">
                            <a class="mnu_navigate" href="/Main/buy_list">전체 구매</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item mnutransactions transaction">
                    <a class="nav-link nav-link-collapse <?= $menu == "trans" ? '' : 'collapsed'?>" data-toggle="collapse" href="#collapseExamplePages3" data-parent="#exampleAccordion">
                        <i class="far fa-money-check-edit icons"></i>
                        <span class="nav-link-text">거래 내역</span>
                    </a>
                    <ul class="sidenav-second-level collapse <?= $menu == "trans" ? 'show' : ''?>" id="collapseExamplePages3">
                        <li class="transaction_list <?= $menu02 == "transaction_list" ? 'active' : ''?>">
                            <a class="mnu_navigate" href="/Main/transaction_list">Receive List</a>
                        </li>
                        <li class="send_list <?= $menu02 == "send_list" ? 'active' : ''?>">
                            <a class="mnu_navigate" href="/Main/send_list">Send List</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item mnuadmin notice_list">
                    <a class="nav-link mnu_navigate" href="/Main/notice_list">
                        <i class="far fa-list icons"></i>
                        <span class="nav-link-text">공지사항 목록</span>
                    </a>
                </li>
                <?php if ($level != 3 ) { ?>
                    <li class="nav-item mnuadmin admin_list">
                        <a class="nav-link mnu_navigate" href="/Main/admin_list">
                            <i class="far fa-user-plus icons"></i>
                            <span class="nav-link-text">관리자 등록/관리</span>
                        </a>
                    </li>
                    <li class="nav-item mnuadmin admin">
                        <a class="nav-link mnu_navigate" href="/Main/admin_logs">
                            <i class="far fa-tasks icons"></i>
                            <span class="nav-link-text">관리자 활동내역</span>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item mnumyaccount account">
                    <a class="nav-link nav-link-collapse <?= $menu == "account" ? '' : 'collapsed'?>" data-toggle="collapse" href="#collapseExamplePages4" data-parent="#exampleAccordion2">
                        <i class="icon-settings icons"></i>
                        <span class="nav-link-text">환경설정</span>
                    </a>
                    <ul class="sidenav-second-level collapse <?= $menu == "account" ? 'show' : ''?>" id="collapseExamplePages4">
                        <li class="myinfo <?= $menu02 == "contract" ? 'active' : ''?>">
                            <a class="mnu_navigate" href="/Main/contract">토큰 보유 정보</a>
                        </li>
                        <?php if ($level == 0 || $level == 1) { ?>
                            <li class="myinfo <?= $menu02 == "bank" ? 'active' : ''?>">
                                <a class="mnu_navigate" href="/Main/bank_control">계좌정보 변경</a>
                            </li>
                            <li class="myinfo <?= $menu02 == "company" ? 'active' : ''?>">
                                <a class="mnu_navigate" href="/Main/company_control">회사정보 변경</a>
                            </li>
                        <?php } ?>
                        <li class="myinfo <?= $menu02 == "myinfo" ? 'active' : ''?>">
                            <a class="mnu_navigate" href="/Main/myinfo">내정보 수정</a>
                        </li>
                        <li class="logout">
                            <a class="mnu_navigate" href="/Main/logout">로그아웃</a>
                        </li>
                    </ul>
                </li>
                <!--<li class="nav-item mnuhistory history">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages5" data-parent="#exampleAccordion3">
                        <i class="icon-printer icons"></i>
                        <span class="nav-link-text">HISTORY</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseExamplePages5">
                        <li>
                            <a class="mnu_navigate" href="/Main/lock_history">Lock History</a>
                        </li>
                        <li>
                            <a class="mnu_navigate" href="/Main/event_log">Event Log</a>
                        </li>
                    </ul>
                </li>-->
            </ul>
        </div>
    </nav>
    <div class="content-wrapper">
        <div class="container-fluid" id="main_area">