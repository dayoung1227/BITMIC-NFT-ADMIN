var	is_clickact = true;
var ikki_version = '20180819';
var language = 'eng';

var system_config = {
		'domain':'https://localhost',
		'dupli_check_key':'p2pblock',
		'env':'production'
};


var click_cnt = 0;

var temp_str;
var toggle_key = false;

var _input_box = $('<input />', {
	class: 'input_text virturl_input_box',
});

var _select_box = $('<select />', {
	class: 'virturl_input_box',
});

var _area_box = $('<textarea />', {
	class: 'virturl_input_box xtextarea',
});

var selected_td_val = "";
var $selected_td = "";


var _audioElement = $('<audio />');


var alram_timer;
var is_new = false;
var sound_flag = true;

var alram_audio;

var reflash_timer = null;

var bt_timer = null;

var cuser_timer = null;

var common_timer = null;

var reflash_timer = null;