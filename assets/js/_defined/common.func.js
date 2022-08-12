
function validateEmail(email) {

	if(email == ""){
		return false;
	}else{
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		return emailReg.test( email );
	}
}

function validateId(id) {
    if(id == ""){
        return false;
    }

	var idReg = /^[a-zA-Z0-9]{4,19}$/g;
	return idReg.test( id );

}

function validateSecure(pincode) {
    var strength = 0;
    if (pincode.length < 4) {
        return false;
    }

    if (pincode.length > 4){
        return false;
    }

    if (pincode.length === 4) strength += 1;

    // If it has numbers and characters, increase strength value. (대문자 하나, 소문자 하나, 숫자 하나)
    if (pincode.match(/([0-9])/)) strength += 1;


    if (strength > 1) {
        return true;
    }else{
        return false;
    }
}


function validatePassword(password) {
	var strength = 0
	if (password.length < 8) {
		return false;
	}
	
	if (password.length > 7) strength += 1

	// If it has numbers and characters, increase strength value. (대문자 하나, 소문자 하나, 숫자 하나)
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1


	/*
	// If password contains both lower and uppercase characters, increase strength value.(대문자 하나 소문자 하나)
	if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
	// If it has numbers and characters, increase strength value. (대문자 하나, 소문자 하나, 숫자 하나)
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
	// If it has one special character, increase strength value.(특수문자 하나)
	if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
	// If it has two special characters, increase strength value. (특수문자 두개)
	if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
	// Calculated strength value, we can return messages
	// If value is less than 2
	*/

	if (strength > 1) {
		return true;
	}else{
		return false;
	}


}

function validateComment(str) {
    if (str.length < 5) {
        return false;
    }else{
        return true;
    }
}

function validateName(str) {
    if (str.length < 2) {
        return false;
    }else{
        return true;
    }
}

function validateLength(str) {
	var strength = 0
	if (str.length < 4) {
		return false;
	}else{
		return true;
	}
}

function validateStrLength(str, len) {
    if (str.length < len) {
        return false;
    }else{
        return true;
    }
}



function compareString(str1, str2){
	if(str1 != str2){
		return false;
	}else{
		return true;
	}
}


function tooltip_open(check_cls, tooltop_cls, tooltop_txt){
	$("." + check_cls).removeClass('ok');
	$("." + tooltop_cls).find('.tooltip-inner').html(tooltop_txt);
	$("." + tooltop_cls).removeClass('opacity0').addClass('opacity10');
	$("." + tooltop_cls).show();
}


function tooltip_close(check_cls, tooltop_cls){
	$("." + check_cls).addClass('ok');
	$("." + tooltop_cls).removeClass('opacity10').addClass('opacity0');
	$("." + tooltop_cls).hide();
}


function tooltip_open2(tooltop_cls, tooltop_txt){
	$("." + tooltop_cls).find('.tooltip-inner').html(tooltop_txt);
	$("." + tooltop_cls).removeClass('opacity0').addClass('opacity10');
	$("." + tooltop_cls).show();
}


function tooltip_close2(tooltop_cls){
	$("." + tooltop_cls).removeClass('opacity10').addClass('opacity0');
	$("." + tooltop_cls).hide();
}


function fnc_validate_chk(chk_id, check_cls, tooltop_cls, tooltop_txt, fnc){
	var chk_value = $("#" + chk_id).val();
	if( !fnc(chk_value)) {
		if(check_cls != ''){
			tooltip_open(check_cls, tooltop_cls, tooltop_txt);
			return false;
		}else{
			tooltip_open2(tooltop_cls, tooltop_txt);
			return false;
		}
	}else{
		if(check_cls != ''){
			tooltip_close(check_cls, tooltop_cls);
			return true;
		}else{
			tooltip_close2(tooltop_cls);
			return true;
		}
	}
}


function fnc_compare_chk(compare_id, chk_id, check_cls, tooltop_cls, tooltop_txt, fnc){
	var compare_value = $("#" + compare_id).val();
	var chk_value = $("#" + chk_id).val();
	if( !fnc(compare_value, chk_value)) {
		if(check_cls != ''){
			tooltip_open(check_cls, tooltop_cls, tooltop_txt);
			return false;
		}else{
			tooltip_open2(tooltop_cls, tooltop_txt);
			return false;
		}
	}else{
		if(check_cls != ''){
			tooltip_close(check_cls, tooltop_cls);
			return true;
		}else{
			tooltip_close2(tooltop_cls);
			return true;
		}
	}
}




function form_maxlength_1(form_element_id){
  $('#' + form_element_id).maxlength({
    alwaysShow: true,
    threshold: 10,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger",
    separator: ' of ',
    preText: 'You have ',
    postText: ' chars remaining.',
    validate: true
  });
}




function alert_layer(str){
	$('#alert_popup').find(".contents").html("<p>" + str + "</p>");
	$('#alert_popup').modal('show');
}



function notify_layer(str){
	$('#notice_popup').find(".contents").html("<p>" + str + "</p>");
	$('#notice_popup').modal('show');
}



function LoadingLayer_defined(message, act_fnc, timer_fnc, time_interval) {
	$(".common_popup").hide();
	$(".popup_black_bg").hide();
	$(".popup_black_bg").show();
	
	var popup = $("#common_popup2");
	
	popup.css("left","50%")
		.css("top","50%")
		.css("margin-left","-120px")
		.css("margin-top","-100px")
		.css("position","absolute");
	
	popup.find(".cmlayer_text").html(message);
	
	popup.show();
	act_fnc();

	common_timer = setInterval( timer_fnc, time_interval);
	
	return false;
}

function go_content(_link_url, _target){
    req(system_config, _link_url ,{div : _target});
}

function go_menu(_link_url){
	req(system_config, _link_url ,{body : true});
}


function loading_layer_open(){
    var menuID = "menu-lodingbar";
    var menuSize = $('#'+menuID).data('menu-height');

    $('#'+menuID).css({"height": menuSize});
    $('#'+menuID).css({"margin-top": (menuSize/2)*(-1)});
    $('#'+menuID).toggleClass('active-menu-box-modal');
    $('.menu-hider').addClass('active-menu-hider');
}


function loading_layer_close(){
    $('.menu-hider').removeClass('active-menu-hider');
    $('.menu').removeClass('active-menu-box-modal');
    $('.menu').css({'margin-top':''})
    return false;
}


function confirmLayer(message, fnc) {
    $(".common_popup").hide();
    $(".popup_black_bg").hide();
    $(".popup_black_bg").show();

    var popup = $("#common_popup");

    popup.draggable(); //팝업 레이어 드래그하기

    popup.css("left","50%")
        .css("top","50%")
        .css("margin-left","-120px")
        .css("margin-top","-100px")
        .css("position","absolute");

    popup.find(".del_file_name").html(message);

    popup.find(".icon_popup_close").unbind("click").bind("click", function(){
        $("#common_popup").hide();
        $(".popup_black_bg").hide();
        is_clickact = true;
    });

    popup.find(".btn_confirm").unbind("click").bind("click", function(){
        fnc();
    });
    popup.find(".btn_cancel").unbind("click").bind("click", function(){
        $("#common_popup").hide();
        $(".popup_black_bg").hide();
        is_clickact = true;
    });
    popup.find(".btn_cancel").show();

    popup.show();

    return false;
}



function confirmLayer(message, fnc) {
    $(".common_popup").hide();
    $(".popup_black_bg").hide();
    $(".popup_black_bg").show();

    var popup = $("#common_popup");

    popup.draggable(); //팝업 레이어 드래그하기

    popup.css("left","50%")
        .css("top","50%")
        .css("margin-left","-120px")
        .css("margin-top","-100px")
        .css("position","absolute");

    popup.find(".del_file_name").html(message);

    popup.find(".icon_popup_close").unbind("click").bind("click", function(){
        $("#common_popup").hide();
        $(".popup_black_bg").hide();
        is_clickact = true;
    });

    popup.find(".btn_confirm").unbind("click").bind("click", function(){
        fnc();
    });
    popup.find(".btn_cancel").unbind("click").bind("click", function(){
        $("#common_popup").hide();
        $(".popup_black_bg").hide();
        is_clickact = true;
    });
    popup.find(".btn_cancel").show();

    popup.show();

    return false;
}





function req(sc, url, opts){
	var err_server_desc = 'SERVER ERROR!!!';
	var err_wrong_data_desc = 'SERVER WRONG DATA!!';
	var err_latency_desc = 'LATENCY ERROR!!';
	var err_session_desc = 'SESSION ERROR!!';

	if( typeof opts == 'undefined' ){
		opts = {};
	}
	
	if( typeof sc == 'undefined'){
		sc = {'dupli_check_key' : 'test'};
	}
	
	//var new_opts = CopyObj(opts);
	
	//default setting
	if( typeof opts.html == 'undefined' ){
		opts.html = false;
	}

	if( typeof opts.popup == 'undefined'){
		opts.popup = false;
	}

	if( typeof opts.data !== 'object' || ! opts.data ){
		opts.data = {};
	}

	if( typeof opts.json === 'undefined' ){
		opts.json = false;
		opts.html = true;
	}else{
		opts.json = true;
		opts.html = false;
	}

    loading_layer_open();

	if( opts.json ){
		opts.data.dupli_check_key = sc.dupli_check_key;
		opts.data.json = true;
		$.post(url, opts.data, function( resp ){
            loading_layer_close();
			if(system_config.env == 'development'){
				con('LOCAL JSON===========');
				con( resp );
			}
			
			//server check
			if( typeof resp !== 'undefined' && typeof resp.result !== 'undefined'){
				if( resp.result == -99 ){
					req('/game_error/serverCheck');
					return;
				}else if( resp.result == 0 ){
					//log.debug( 'session expired' );
					/*
					alertLayer_defined('다시 로그인 해주세요', function(){
						$("#common_popup").hide();
						$(".popup_black_bg").hide();
						req( system_config, '/c_loginout/logincheck' ,{div : 'contentspart'});
					});
					*/
					return false;
				}
				
				if( typeof opts.callback === 'function' ){
					opts.callback(resp);
				}
				loading_end();
			}

		}, 'json');
	}else{

		opts.data.dupli_check_key = sc.dupli_check_key;
		$.post(url, opts.data, function (resp) {
			loading_layer_close();
			con('LOCAL HTML===========');
			click_cnt++;

			if (resp.length == 0 || resp == '') {
				log.debug( 'session expired' );
				/*
				alertLayer_defined('다시 로그인 해주세요', function(){
					$("#common_popup").hide();
					$(".popup_black_bg").hide();
					req( system_config, '/c_loginout/logincheck' ,{div : 'contentspart'});
				});
				*/
				return false;
			}

			if (typeof opts.html != 'undefined' && opts.html) {
				if (typeof opts.body == 'undefined' || opts.body) {
					$('body').html('');
					$('body').html(resp)
				}else{
					$('#' + opts.div).html('');
					$('#' + opts.div).fadeOut(50, function () {
						//$(this).html( resp );
						$(this).html(resp).fadeIn();
						return;
					});

				}
			} else {
				if (typeof opts.callback === 'function') {
					opts.callback(resp);
				}
			}
			loading_end();
		});
		return false;

	}
}

/**
 * JSON Data 를 STRING 변환
 */
function objectToJSONString(object) {
	var isArray = (object.join && object.pop && object.push
					&& object.reverse && object.shift && object.slice && object.splice);
	var results = [];

	for (var i in object) {
		var value = object[i];

		if (typeof value == "object") 
			results.push((isArray ? "" : "\"" + i.toString() + "\" : ")
							 + objectToJSONString(value));
		else if (value)
			results.push((isArray ? "" : "\"" + i.toString() + "\" : ") 
							+ (typeof value == "string" ? "\"" + value + "\"" : value));
	}

	return (isArray ? "[" : "{") + results.join(", ") + (isArray ? "]" : "}");
}

function CopyObj(obj) {
	if( typeof obj == 'object' && obj ){
		var cp = {};
		for(var i in obj){
			if( typeof obj[i] == 'object' && obj[i] ){
				cp[i] = CopyObj(obj[i]);
			}else if( typeof obj[i] == 'string' && obj.length < 16 && obj[i] != '' && ! isNaN(Number(obj[i])) ){
				cp[i] = Number(obj[i]);
			}else{
				cp[i] = obj[i];
			}
		}
		return cp;
	}else if( typeof obj == 'string' && obj.length < 16 && obj != '' && ! isNaN(Number(obj)) ) {
		cp = Number(obj);
	}else {
		cp = obj;
	}
	return cp;
};


//console.log
function con(arg){
	if( typeof console == 'object' ){
		if(navigator.appName === "Netscape"){
			console.log(arg);
		}else if(navigator.appName === "Microsoft Internet Explorer"){
			if( typeof console.dir  !== 'undefined') {
				console.dir(arg);
			}
		}
	}else if( typeof console === 'undefined' ){
		console = {log: function(arg) {arg;}};
	}
};

function loading_end(){

}





function req_hidden(sc, url, opts){
	var err_server_desc = 'SERVER ERROR!!!';
	var err_wrong_data_desc = 'SERVER WRONG DATA!!';
	var err_latency_desc = 'LATENCY ERROR!!';
	var err_session_desc = 'SESSION ERROR!!';

	if( typeof opts == 'undefined' ){
		opts = {};
	}
	
	if( typeof sc == 'undefined'){
		sc = {'dupli_check_key' : 'test'};
	}
	
	//var new_opts = CopyObj(opts);
	
	//default setting
	if( typeof opts.html == 'undefined' ){
		opts.html = false;
	}

	if( typeof opts.popup == 'undefined'){
		opts.popup = false;
	}

	if( typeof opts.data !== 'object' || ! opts.data ){
		opts.data = {};
	}

	if( typeof opts.json === 'undefined' ){
		opts.json = false;
		opts.html = true;
	}else{
		opts.json = true;
		opts.html = false;
	}
	

	if( opts.json ){
		opts.data.dupli_check_key = sc.dupli_check_key;
		opts.data.json = true;
		$.post(url, opts.data, function( resp ){
			if(system_config.env == 'development'){
				con('LOCAL JSON===========');
				con( resp );
			}
			
			//server check
			if( typeof resp !== 'undefined' && typeof resp.result !== 'undefined'){
				if( resp.result == -99 ){
					req('/game_error/serverCheck');
					return;
				}else if( resp.result == 0 ){
					//log.debug( 'session expired' );
					/*
					alertLayer_defined('다시 로그인 해주세요', function(){
						$("#common_popup").hide();
						$(".popup_black_bg").hide();
						req( system_config, '/c_loginout/logincheck' ,{div : 'contentspart'});
					});
					*/
					return false;
				}
				
				if( typeof opts.callback === 'function' ){
					opts.callback(resp);
				}
			}

		}, 'json');
	}else{
		opts.data.dupli_check_key = sc.dupli_check_key;
		$.post(url, opts.data, function( resp ){
			con('LOCAL HTML===========');
			click_cnt++;
			
			if( resp.length == 0 || resp == ''){
				//log.debug( 'session expired' );
				/*
				alertLayer_defined('다시 로그인 해주세요', function(){
					$("#common_popup").hide();
					$(".popup_black_bg").hide();
					req( system_config, '/c_loginout/logincheck' ,{div : 'contentspart'});
				});
				*/
				return false;
			}
			
			if( typeof opts.html != 'undefined' && opts.html ){
				if( typeof opts.div == 'undefined' || opts.div == '' ){
					opts.div = 'contentspart';
				}
				

				$('#' + opts.div).html('');

				$('#' + opts.div).fadeOut(50, function(){
					//$(this).html( resp );
					$(this).html( resp ).fadeIn();
					return;
				});

				//$('#' + opts.div).html( resp );
				
			}else{
				if( typeof opts.callback === 'function' ){
					opts.callback(resp);
				}
			}
		});
		return false;
	}
}

function copyToClipboard(text, el) {
    var copyTest = document.queryCommandSupported('copy');
    var elOriginalText = el.attr('data-original-title');




    if (copyTest === true) {
        var copyTextArea = document.createElement("textarea");
        copyTextArea.value = text;
        document.body.appendChild(copyTextArea);
        copyTextArea.select();
        try {
            alert('aaaaaaaaaaaaaaaaaaa');
            var successful = document.execCommand('copy');
            var msg = successful ? 'Copied!' : 'Whoops, not copied!';
            el.attr('data-original-title', msg).tooltip('show');
        } catch (err) {
            alert('bbbbbbbbbbbbbb');
            console.log('Oops, unable to copy');
        }
        document.body.removeChild(copyTextArea);
        el.attr('data-original-title', elOriginalText);
    } else {
        alert('cccccccccccccc');
        // Fallback if browser doesn't support .execCommand('copy')
        window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
    }
}