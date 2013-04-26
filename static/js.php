<?php
define('RAPIDLEECH', 'yes');

if (!isset($_GET['mod']) and isset($_SERVER['QUERY_STRING'])) {
  $_GET['mod'] = $_SERVER['QUERY_STRING'];
}
$nn = "\r\n";
$validpage = array('main','audl', 'auul', 'lynx', 'mtn', 'xpanel');

$req = urldecode($_GET['mod']);
$validSearch = preg_match("/^[a-zA-Z0-9_]+$/i", $req);

if(!$validSearch || !in_array($req, $validpage)){
  $req = 'main';
  header("Location: " . basename(__FILE__) . "?" . $req);
}

define('PATH_SPLITTER', (strstr(realpath('./'), '\\')!==false ? '\\' : '/'));
define('ROOT_DIR', realpath('../') . PATH_SPLITTER );
define('CLASS_DIR', ROOT_DIR . 'classes' . PATH_SPLITTER );
define('STATIC_DIR', 'static/');
define('LANG_DIR', CLASS_DIR . 'languages' . PATH_SPLITTER);
define('CONFIG_DIR', ROOT_DIR . 'configs' . PATH_SPLITTER );
require_once(CONFIG_DIR . 'config.php');

define('TEMPLATE_DIR', 'tpl/' . $options['template_used'] . '/');
define('IMAGE_DIR', TEMPLATE_DIR . 'skin/'. $options["csstype"] . '/');

// Language initialisation
require_once(CLASS_DIR."lang.class.php");
$L = new RxLang;
$charSet = $L->settings["charset"];
unset($options);

header('Cache-control: must-revalidate');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 10800) . ' GMT');
header('Content-type: application/javascript');

echo "/*=====".$nn."JS Lib".$nn."Mode: ";
echo ($req);
echo "\r\n=====*/\r\n";
?>
var d = document;

var php_js_strings = [];
php_js_strings[1] = "<?php echo $L->say['loading_pwait'];?>";
php_js_strings[2] = "<?php echo $L->say['acc_username'];?>";
php_js_strings[3] = "<?php echo $L->say['acc_password'];?>";
php_js_strings[4] = "<?php echo $L->say['acc_free_rp'];?>";
php_js_strings[5] = "<?php echo $L->say['acc_fpoints'];?>";
php_js_strings[6] = "<?php echo $L->say['acc_pre_rp'];?>";
php_js_strings[7] = "<?php echo $L->say['acc_ppoints'];?>";
php_js_strings[8] = "<?php echo $L->say['acc_ref_rp'];?>";
php_js_strings[9] = "<?php echo $L->say['acc_rfpoints'];?>";
php_js_strings[10] = "<?php echo $L->say['acc_antihack'];?>";
php_js_strings[11] = "<?php echo $L->say['acc_security'];?>";
php_js_strings[12] = "<?php echo $L->say['acc_email'];?>";
php_js_strings[13] = "<?php echo $L->say['traficshare_left'];?>";
php_js_strings[14] = "<?php echo $L->say['traffic'];?>";
php_js_strings[15] = "<?php echo $L->say['traffic_left'];?>";
php_js_strings[16] = "<?php echo $L->say['traffic__left'];?>";
php_js_strings[17] = "<?php echo $L->say['exp_date'];?>";
php_js_strings[18] = "<?php echo $L->say['acc_expired'];?>";
php_js_strings[19] = "<?php echo $L->say['acc_formated'];?>";
php_js_strings[20] = "<?php echo $L->say['acc_checked'];?>";
php_js_strings[21] = "<?php echo $L->say['acc_checked_dead'];?>";
php_js_strings[22] = "<?php echo $L->say['acc_nosecure'];?>";
php_js_strings[23] = "<?php echo $L->say['acc_nodetail'];?>";
php_js_strings[24] = "<?php echo $L->say['rs_acc_unavailable'];?>";
php_js_strings[25] = "<?php echo $L->say['loading_current_chk'];?>";
php_js_strings[26] = "<?php echo $L->say['_status'];?>";
php_js_strings[27] = "<?php echo $L->say['acc_pre_acc'];?>";
php_js_strings[28] = "<?php echo $L->say['term_download'];?>";
php_js_strings[29] = "<?php echo $L->say['term_nosimultan'];?>";
php_js_strings[30] = "<?php echo $L->say['term_autodelete'];?>";
php_js_strings[31] = "<?php echo $L->say['term_usedelink'];?>";
php_js_strings[32] = "<?php echo $L->say['term_dontpost'];?>";
php_js_strings[33] = "<?php echo $L->say['term_cendolink'];?>";
php_js_strings[34] = "<?php echo $L->say['term_dellink'];?>";
php_js_strings[35] = "<?php echo $L->say['no_selectfile'];?>";
php_js_strings[36] = "<?php echo $L->say['_uploading'];?>";
php_js_strings[37] = "<?php echo $L->say['_pwait_sec'];?>";
php_js_strings[38] = "<?php echo $L->say['hide_last_act'];?>";
php_js_strings[39] = "<?php echo $L->say['show_last_act'];?>";
php_js_strings[281] = "<?php echo $L->say['local_time'];?>";

var IMG_DIR_TPL = '<?php echo IMAGE_DIR;?>';
var STATIC_DIR = '<?php echo STATIC_DIR;?>';
var phpjx = '<?php echo 'ajax_main.php';?>';

// Frame Buster
if (top != self) {
	try {
		if (top.location.host != self.location.host) throw 1;
	} catch (err) {
		top.location.replace(self.location.protocol + '//' + self.location.host + self.location.pathname);
	}
}

function highlight(field) {
	field.focus(); field.select();
}
<?php
if($req == 'main' OR $req == 'auul'){
?>
function setCheckboxes(act) {
	try {
		elts = d.getElementsByName("files[]");
	} catch (e) {}
	if (typeof(elts) == ('undefined' || 'null')) {
		return false;
	};
	var elts_cnt = (typeof(elts.length) !== 'undefined') ? elts.length : 0;
	var dc;
	if (elts_cnt) {
		for (var i = 0; i < elts_cnt; i++) {
			elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);
			dc = d.getElementById('brs' + eval(i + 1));
			if (dc) {
				dc.className = (elts[i].checked == 1) ? 'rowlist_checked' : 'rowlist';
			}
		}
	}
}
<?php
} // end-($req == 'main' OR $req == 'auul')
if($req == 'main' OR $req == 'lynx'){
?>
function gosetfacebook() {
	$(document).ready(function($) {
		$('a[rel*=facebox]').facebox({
			loading_text: php_js_strings[1], close_image: STATIC_DIR + 'images/nav_close6.png'
		})
	});
}
/**
 * Script by   : Daniel_X_x
 * ===========================
 * Location    : ./static/matches
 * Adapted for : RL OLC 36B
 *
 * Re-generated   : 3009-09 // ddmm-yy
 * ===========================
 */
var fl_rwhide = new Array(); // buffer global table object and selected rows array

function flist_match_hide() {
	var obj_container = d.getElementById('flist_match_hitems');
	var tmp = obj_container.style.display == 'none' ? 'block' : 'none';
	var obj_inp = obj_container.getElementsByTagName('input')[0];
	obj_container.style.display = tmp;
	if (tmp != 'none') {
		obj_inp.focus();
	} else {
		flist_toogle_rrestore();
	}
}
function flist_match() {
	var text = d.getElementById('flist_match_search').value;
	var rows = d.getElementById("intbl").tBodies[0].rows;
	if (text == '') {
		clear_chk_row(rows);
		return false;
	}
	var csensitive = d.getElementById('flist_match_ins').checked;
	var hdunmatch = d.getElementById('flist_match_hide').checked;
	var tmp, cell_text, cell_matches, text_regexp;
	text = '*' + text + '*';
	while (text != text.replace(/\*\*/g, '*')) {
		text = text.replace(/\*\*/g, '*');
	}
	text = text.replace(/\^|\$|\?|\+|\||\(|\{|\[|\\/g, '');
	text = text.replace(/\./g, '\\.');
	text = text.replace(/\*/g, '.*?');
	text_regexp = new RegExp(text, csensitive ? '' : 'i');
	clear_chk_row(rows);

	fl_rwhide = {
		"table": "",
		"baris": new Array()
	};
	fl_rwhide["table"] = rows;

	for (var i = 0; i < rows.length; i++) {
		if (rows[i].id == '' || rows[i].id == null) {
			rows[i].id = 'table_filelist_tbody_trs' + i;
		}
		tmp = rows[i].cells[1].getElementsByTagName('*');
		cell_text = (tmp.length > 0) ? tmp[0].innerHTML : rows[i].cells[1].innerHTML;
		cell_matches = text_regexp.exec(cell_text);
		if (cell_matches != null) {
			tmp = rows[i].cells[0].getElementsByTagName('input');
			for (var j = 0; j < tmp.length; j++) {
				if (tmp[j].type == 'checkbox') {
					tmp[j].checked = true;
					d.getElementById(rows[i].id).className = 'rowlist_checked';
					break;
				}
			}
		} else {
			if (hdunmatch) {
				d.getElementById(rows[i].id).style.display = 'none';
				fl_rwhide["baris"].push(i)
			}
		}
	}
	return false;
}
function clear_chk_row(obj) {
	var tmp;
	for (var i = 0; i < obj.length; i++) {
		tmp = obj[i].cells[0].getElementsByTagName('input');
		for (var j = 0; j < tmp.length; j++) {
			if (tmp[j].type == 'checkbox') {
				tmp[j].checked = false;
				break;
			}
		}
		d.getElementById(obj[i].id).style.display = '';
		d.getElementById(obj[i].id).className = 'rowlist';
	}
}
function flist_toogle_rrestore() {
	if (fl_rwhide["baris"] && fl_rwhide["baris"].length > 0) {
		flist_restore_row(fl_rwhide);
		fl_rwhide = new Array();
	}
}
function flist_restore_row(ar_rw) {
	for (var i = 0; i < ar_rw["baris"].length; i++) {
		d.getElementById(ar_rw["table"][ar_rw["baris"][i]].id).style.display = '';
	}
}
/*Mod by:~Idx*/
//--- end matches
// =====================

<?php
} // end -($req == 'main' OR $req == 'lynx')
if($req == 'main' OR $req == 'lynx' OR $req == 'auul'){
?>
function clk(idck) {
	var cur = document.getElementById(idck).checked;
	document.getElementById(idck).checked = !cur;
}

function showAll() {
	if (getCookie("showAll") == 1) {
		deleteCookie("showAll");
	} else {
		d.cookie = "showAll = 1;";
	}
	urlload();
}

function urlload() {
	location.href = location.href.split('?', 1) + '?act=files';
}
<?php
} // end -($req == 'main' OR $req == 'lynx' OR $req == 'auul')
if($req == 'main') {
?>
function neoClass_tab(id) {
	var tbobj = $('#tb' + id + '');
	$('#navcell' + id + '').attr('class', 'tab-on');
	tbobj.attr('class', 'tab-content show-table');
	//alert(tbobj.className);
}

function switchCell(m) {
	var style;
	var obj = $('#tb' + m + '');
	for (i = 1; i <= 5; i++) {
		if (i != m) {
			$('#navcell' + i + '').attr('class', 'tab-off');
			$('#tb' + i + '').attr('class', 'hide-table');
		}
	}
	setTimeout("neoClass_tab(" + m + ")", 70);
}

function clearSettings() {
	clear("domail"); clear("email"); clear("split"); clear("method"); clear("partSize");
	clear("useproxy"); clear("proxy"); clear("saveto"); clear("path"); clear("savesettings");
	$('#domail, #splitchkbox, #useproxy, #premium_acc, #saveto, #savesettings').prop('checked', false);
	$('#email, #proxyproxy, #proxyuser, #proxypass, #premium_user, #premium_pass').val('');
	$('#emailtd, #splittd, #methodtd, #proxy, #prblock, #path, #clearsettings').hide();
	d.cookie = "clearsettings = 1;";
}

function clear() {
	for (var i = 0; i < arguments.length; i++)
		d.cookie = arguments[i] + ' = ' + '; expires=Thu, 01-Jan-70 00:00:01 GMT';
}

function mail(str, field) {
	$('#mailPart.' + field + '').html(str);
	return true;
}

function setFtpParams() {
	setParam("host");
	setParam("port");
	setParam("login");
	setParam("password");
	setParam("dir");
	d.cookie = "ftpParams=1";
	$('#hrefSetFtpParams').css('color', '#808080');
	$('#hrefDelFtpParams').css('color', '#0000FF');
}

function delFtpParams() {
	deleteCookie("host");
	deleteCookie("port");
	deleteCookie("login");
	deleteCookie("password");
	deleteCookie("dir");
	deleteCookie("ftpParams");
	$('#hrefSetFtpParams').css('color', '#0000FF');
	$('#hrefDelFtpParams').css('color', '#808080');
}

function setParam() {
	for (var i = 0; i < arguments.length; i++)
		d.cookie = arguments[i] + '=' + d.getElementById(arguments[i]).value;
}

function pr(percent, received, speed) {
	$('#received').html('<b>' + received + '</b>');
	$('#percent').html('<b>' + percent + '%</b>');
	$('#progress').css('width', percent + '%');
	$('#speed').html('<b>' + speed + ' KB/s</b>');
	d.title = 'Uploaded ' + percent + '%';
	return true;
}

function changeStatus(file, size) {
	$('#status').html(php_js_strings[36] + ' <b>' + file + '</b>, Size <b>' + size + '</b>...<br />');
}

function new_transload_window() {
	var tmp = (new Date()).getTime();
	$('form[name="transload"]').attr('target', 'rapidleech_' + tmp);
	var options = "width=700,height=320,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no";
	window.open('', 'rapidleech_' + tmp, options);
	window.setTimeout("$('form[name=\"transload\"]').submit();", 200);
}
function slip(Obj) {
	var lst = php_js_strings[38];
	var d_txt = $('#act_hist_txt');
	var d_img = $('#act_hist_img');
	var d_hist = $('#act_history');
	if (d_txt.html() == lst) {
		d_txt.html(php_js_strings[39]); d_img.attr('src', IMG_DIR_TPL + 'plus.png');
		d_hist.hide();
	} else {
		d_txt.html(lst); d_img.attr('src', IMG_DIR_TPL + 'min.png');
		d_hist.show();
	}
	d_img.blur();
	return false;
}
<?php
} //end-$req == 'main'
if($req == 'lynx'){
?>
function trparser() {
	var _tpl = "", i = 0;
	for (var date in dFile) {
		pfile = dFile[date];
		_tpl = dtemplate;
		// do replacement string;  10-May-2009, 23:09
		var now = new Date(parseInt(date) * 1000);
		dDate = now.toGMTString("dd-mmm-yy, HH:MM");
		dDate = dDate.split(" ");
		_tpl = _tpl.replace(/\(\(chkidx\)\)/g, i);
		_tpl = _tpl.replace(/\(\(filename\)\)/g, (pfile["name"]));
		_tpl = _tpl.replace(/\(\(filelink\)\)/g, (_dlpath + (pfile["name"])));
		_tpl = _tpl.replace(/\(\(filesize\)\)/g, pfile["size"]);
		_tpl = _tpl.replace(/\(\(formatdate\)\)/g, dDate[1] + '-' + dDate[2] + '-' + dDate[3].substr(2, 2) + ', ' + dDate[4].substr(0, 2) + ':' + dDate[4].substr(3, 2) + ':' + dDate[4].substr(6, 2));
		_tpl = _tpl.replace(/\(\(fileage\)\)/g, pfile["age"]);
		_tpl = _tpl.replace(/\(\(dlpath\)\)/g, _dlpath);
		_tpl = _tpl.replace(/\(\(b64filename\)\)/g, pfile["delkey"]);
		text += _tpl;
		i++;
	}
}
var idwindow = new Array();
function opennewwindow(id) {
	var options = "width=700,height=250,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
	var start_link = d.getElementById(id);
	idwindow[id] = window.open(start_link, dwindow + id, options);
	idwindow[id].opener = self;
	idwindow[id].focus();
}
function sAll(niLai) {
	var dc;
	diq = d.getElementById('add_comment').checked = false;
	d.getElementById('comment').style.display = 'none';
	for (i = 0; i < Opt["nC"]; i++) {
		d.getElementById('chkfL-' + i).checked = niLai;
		dc = d.getElementById('brs' + i);
		//if(niLai){dc.bgColor='#B1F4AE';}else{dc.bgColor='#D49659';}
		if (niLai) {
			dc.className = 'rowlist_checked';
		} else {
			dc.className = 'rowlist';
		}
	}
}
function GenTag() {
	var displ = d.getElementById('add_comment').checked ? '' : 'none';
	if (!displ) {
		var chk, buFF = "", kd = unescape("%B2"), cLnk = 0;
		var nC = Opt["nC"], AdL = Opt["AdL"];
		var ttip = "", ttip2 = "", ttip3 = "", ttip4 = "", delink = "";
		var Simultan = true; //set this true if your server allow simultan download
		var codeTagOnly = d.getElementById('wterm').checked ? false : true; //true is without Term of Download
		var DelLink = Opt["DelLink"] && d.getElementById('dellnk').checked;
		var poslynx, hostpath = d.location.href;
		poslynx = hostpath.lastIndexOf("/");
		hostpath = hostpath.substring(0, poslynx + 1);
		if (!codeTagOnly) {
			ttip = "\n[SIZE=1]"+php_js_strings[28]+"";
			if (!Simultan) ttip2 = "\n-"+php_js_strings[29]+"";
			if (AdL > 0) ttip3 = "\n-"+php_js_strings[30].replace('{1}', AdL)+"";
			if (DelLink) ttip3 += "\n-"+php_js_strings[31]+"";
			ttip4 = "\n-"+php_js_strings[32]+"";
			ttip4 += "\n"+php_js_strings[33]+"";
		}
		delink = "\n[SPOILER="+php_js_strings[34]+"][SIZE=2][CODE]";
		for (i = 0; i < nC; i++) {
			chk = d.getElementById('chkfL-' + i).checked;
			if ((chk) && (chk != '')) {
				buFF = buFF + "\n" + (d.getElementById('fL-' + i));
				if (DelLink) {
					dL = d.getElementById('dL-' + i).href;
					dL = dL.substring(0, dL.indexOf('&lineid'));
					delink = delink + "\n" + dL;
				}
				cLnk++;
			}
		}
		delink = delink + "\n[/CODE][/SIZE][/SPOILER]";
		if (buFF != '') {
			buFF = "[CODE]" + (buFF) + "\n[/CODE]";
			if (DelLink) {
				buFF = buFF + delink;
			}
			buFF += ttip;
			if (cLnk > 1) {
				buFF += ttip2 + ttip3;
			} else {
				buFF += ttip3;
			}
			buFF += ttip4;
			if (!codeTagOnly) {
				buFF += "[/SIZE]";
			}
		}
	}
	var dca = d.getElementById('comment');
	var trwarn = d.getElementById('warn_alert');
	if (buFF != "") {
		dca.style.display = displ;
		d.getElementById('cmtarea').value = buFF;
		trwarn.style.display = 'none';
	} else {
		dca.style.display = 'none';
		d.getElementById('td_warn').innerHTML = '<div class="acthistory_result" style="width:200px;">'+php_js_strings[35]+'</div>';
		trwarn.style.display = '';
	}
}
<?php
} //end-$req == 'lynx'
if ($req !== 'xpanel') {
?>
function deleteCookie(name, path, domain) {
	if (getCookie(name)) {
		d.cookie = name + "=" + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

function getCookie(name) {
	var dc = d.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);
	if (begin == -1) {
		begin = dc.indexOf(prefix);
		if (begin != 0) {
			return null;
		}
	} else {
		begin += 2;
	}
	var end = d.cookie.indexOf(";", begin);
	if (end == -1) {
		end = dc.length;
	}
	return unescape(dc.substring(begin + prefix.length, end));
}

/** //Clock tik tok
* ===========================
* Adapted for : RL OLC 36B
*
* Re-generated   : 1009-09 // ddmm-yy
* ===========================
*/

// Javascript clock for server time
/**
* Numbers < 10 should be presented with a zero in front
*/
function fixNumber(number) {
	return (number < 10) ? '0' + number : number;
}

/**
* Fixed look for month
*/
function fixMonth(number) {
	number = number + 1;
	return (number < 10) ? '0' + number : number;
}

function getLocalDate(year, month, day, dateFormat) {
	if (dateFormat.length < 2) { // When not logged in there is no dateformat
		dateFormat = 'yyyy-mm-dd';
	}
	dateFormat = dateFormat.replace('yyyy', year);
	dateFormat = dateFormat.replace('mm', month);
	dateFormat = dateFormat.replace('dd', day);
	dateFormat = dateFormat.replace('d', day);
	dateFormat = dateFormat.replace('m', month);

	return dateFormat;
}

/**
* Show online users and clock
*/
function runClock(timeDiff, dateFormat) {
	var now = new Date();
	var newTime = now.getTime() - timeDiff;
	now.setTime(newTime);
	var localDate = getLocalDate(now.getFullYear(), fixMonth(now.getMonth()), fixNumber(now.getDate()), dateFormat);
	d.getElementById('server').innerHTML = fixNumber(now.getHours()) + ':' + fixNumber(now.getMinutes()) + ':' + fixNumber(now.getSeconds());
	setTimeout('runClock(timeDiff,"' + dateFormat + '");', 1000);
}

/**
* Calculates a time difference between client and server, to make js clock to run correctly
*/
function timeDiff(Year, Month, Day, Hour, Minute, Second, dateFormat) {
	var timeDifferense;
	var serverClock = new Date(Year, Month - 1, Day, Hour, Minute, Second);

	var clientClock = new Date();
	var serverSeconds;
	var clientSeconds;
	timeDiff = clientClock.getTime() - serverClock.getTime() - 3000;
	runClock(timeDiff, dateFormat);
}

function getthedate(){
	var mydate=new Date();
	var hours=mydate.getHours();
	var minutes=mydate.getMinutes();
	var seconds=mydate.getSeconds();
	var dn='AM';
	if (hours>=12) dn='PM';
	if (hours>12) hours=hours-12;
	if (hours==0) hours=12;
	if (hours<=9) hours='0'+hours;
	if (minutes<=9) minutes='0'+minutes;
	if (seconds<=9) seconds='0'+seconds;

	var cdate='<span style="color:#999">'+hours+':'+minutes+':'+seconds+' '+dn+'&nbsp;</span><span style="color:#FF8700">('+php_js_strings[281]+')</span>';
	$('#clock').html(cdate);
	setTimeout('getthedate()',1000);
}
// =====================
<?php
} // end not xpanel
?>
// =========Below is global use============

/*
 * Taken From http://phpjs.org/
 */
function array_keys(c,e,h){var g=typeof e!=="undefined",d=[],b=!!h,a=true,f="";if(c&&typeof c==="object"&&c.change_key_case){return c.keys(e,h)}for(f in c){if(c.hasOwnProperty(f)){a=true;if(g){if(b&&c[f]!==e){a=false}else{if(c[f]!=e){a=false}}}if(a){d[d.length]=f}}}return d};
function array_key_exists(b,a){if(!a||(a.constructor!==Array&&a.constructor!==Object)){return false}return b in a};
function array_values(a){var b=[],c="";if(a&&typeof a==="object"&&a.change_key_case){return a.values()}for(c in a){b[b.length]=a[c]}return b};
function in_array(e,d,c){var b="",a=!!c;if(a){for(b in d){if(d[b]===e){return true}}}else{for(b in d){if(d[b]==e){return true}}}return false};
function str_replace(t,c,m,l){var g=0,e=0,p="",k="",d=0,o=0,h=[].concat(t),a=[].concat(c),q=m,b=Object.prototype.toString.call(a)==="[object Array]",n=Object.prototype.toString.call(q)==="[object Array]";q=[].concat(q);if(l){this.window[l]=0}for(g=0,d=q.length;g<d;g++){if(q[g]===""){continue}for(e=0,o=h.length;e<o;e++){p=q[g]+"";k=b?(a[e]!==undefined?a[e]:""):a[0];q[g]=(p).split(h[e]).join(k);if(l&&q[g]!==p){this.window[l]+=(p.length-q[g].length)/h[e].length}}}return n?q:q[0]};
function base64_decode(h){var d="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var c,b,a,m,l,k,j,n,g=0,o=0,e="",f=[];if(!h){return h}h+="";do{m=d.indexOf(h.charAt(g++));l=d.indexOf(h.charAt(g++));k=d.indexOf(h.charAt(g++));j=d.indexOf(h.charAt(g++));n=m<<18|l<<12|k<<6|j;c=n>>16&255;b=n>>8&255;a=n&255;if(k==64){f[o++]=String.fromCharCode(c)}else{if(j==64){f[o++]=String.fromCharCode(c,b)}else{f[o++]=String.fromCharCode(c,b,a)}}}while(g<h.length);e=f.join("");return e};
function base64_encode(j){var e="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var d,c,b,n,m,l,k,o,h=0,p=0,g="",f=[];if(!j){return j}do{d=j.charCodeAt(h++);c=j.charCodeAt(h++);b=j.charCodeAt(h++);o=d<<16|c<<8|b;n=o>>18&63;m=o>>12&63;l=o>>6&63;k=o&63;f[p++]=e.charAt(n)+e.charAt(m)+e.charAt(l)+e.charAt(k)}while(h<j.length);g=f.join("");var a=j.length%3;return(a?g.slice(0,a-3):g)+"===".slice(a||3)};
function explode(b,c,a){if(arguments.length<2||typeof b=="undefined"||typeof c=="undefined"){return null}if(b===""||b===false||b===null){return false}if(typeof b=="function"||typeof b=="object"||typeof c=="function"||typeof c=="object"){return{0:""}}if(b===true){b="1"}b+="";c+="";var d=c.split(b);if(typeof a==="undefined"){return d}if(a===0){a=1}if(a>0){if(a>=d.length){return d}return d.slice(0,a-1).concat([d.slice(a-1).join(b)])}if(-a>=d.length){return[]}d.splice(d.length+a);return d};
function implode(d,b){var a="",e="",c="";if(arguments.length===1){b=d;d=""}if(typeof(b)==="object"){if(Object.prototype.toString.call(b)==="[object Array]"){return b.join(d)}for(a in b){e+=c+b[a];c=d}return e}return b};
function get_html_translation_table(h,f){var c={},e={},b;var d={},a={};var i={},g={};d[0]="HTML_SPECIALCHARS";d[1]="HTML_ENTITIES";a[0]="ENT_NOQUOTES";a[2]="ENT_COMPAT";a[3]="ENT_QUOTES";i=!isNaN(h)?d[h]:h?h.toUpperCase():"HTML_SPECIALCHARS";g=!isNaN(f)?a[f]:f?f.toUpperCase():"ENT_COMPAT";if(i!=="HTML_SPECIALCHARS"&&i!=="HTML_ENTITIES"){throw new Error("Table: "+i+" not supported")}c["38"]="&amp;";if(i==="HTML_ENTITIES"){c["160"]="&nbsp;";c["161"]="&iexcl;";c["162"]="&cent;";c["163"]="&pound;";c["164"]="&curren;";c["165"]="&yen;";c["166"]="&brvbar;";c["167"]="&sect;";c["168"]="&uml;";c["169"]="&copy;";c["170"]="&ordf;";c["171"]="&laquo;";c["172"]="&not;";c["173"]="&shy;";c["174"]="&reg;";c["175"]="&macr;";c["176"]="&deg;";c["177"]="&plusmn;";c["178"]="&sup2;";c["179"]="&sup3;";c["180"]="&acute;";c["181"]="&micro;";c["182"]="&para;";c["183"]="&middot;";c["184"]="&cedil;";c["185"]="&sup1;";c["186"]="&ordm;";c["187"]="&raquo;";c["188"]="&frac14;";c["189"]="&frac12;";c["190"]="&frac34;";c["191"]="&iquest;";c["192"]="&Agrave;";c["193"]="&Aacute;";c["194"]="&Acirc;";c["195"]="&Atilde;";c["196"]="&Auml;";c["197"]="&Aring;";c["198"]="&AElig;";c["199"]="&Ccedil;";c["200"]="&Egrave;";c["201"]="&Eacute;";c["202"]="&Ecirc;";c["203"]="&Euml;";c["204"]="&Igrave;";c["205"]="&Iacute;";c["206"]="&Icirc;";c["207"]="&Iuml;";c["208"]="&ETH;";c["209"]="&Ntilde;";c["210"]="&Ograve;";c["211"]="&Oacute;";c["212"]="&Ocirc;";c["213"]="&Otilde;";c["214"]="&Ouml;";c["215"]="&times;";c["216"]="&Oslash;";c["217"]="&Ugrave;";c["218"]="&Uacute;";c["219"]="&Ucirc;";c["220"]="&Uuml;";c["221"]="&Yacute;";c["222"]="&THORN;";c["223"]="&szlig;";c["224"]="&agrave;";c["225"]="&aacute;";c["226"]="&acirc;";c["227"]="&atilde;";c["228"]="&auml;";c["229"]="&aring;";c["230"]="&aelig;";c["231"]="&ccedil;";c["232"]="&egrave;";c["233"]="&eacute;";c["234"]="&ecirc;";c["235"]="&euml;";c["236"]="&igrave;";c["237"]="&iacute;";c["238"]="&icirc;";c["239"]="&iuml;";c["240"]="&eth;";c["241"]="&ntilde;";c["242"]="&ograve;";c["243"]="&oacute;";c["244"]="&ocirc;";c["245"]="&otilde;";c["246"]="&ouml;";c["247"]="&divide;";c["248"]="&oslash;";c["249"]="&ugrave;";c["250"]="&uacute;";c["251"]="&ucirc;";c["252"]="&uuml;";c["253"]="&yacute;";c["254"]="&thorn;";c["255"]="&yuml;"}if(g!=="ENT_NOQUOTES"){c["34"]="&quot;"}if(g==="ENT_QUOTES"){c["39"]="&#39;"}c["60"]="&lt;";c["62"]="&gt;";for(b in c){if(c.hasOwnProperty(b)){e[String.fromCharCode(b)]=c[b]}}return e};
function htmlentities(b,f,e,a){var d=this.get_html_translation_table("HTML_ENTITIES",f),c="";b=b==null?"":b+"";if(!d){return false}if(f&&f==="ENT_QUOTES"){d["'"]="&#039;"}if(!!a||a==null){for(c in d){if(d.hasOwnProperty(c)){b=b.split(c).join(d[c])}}}else{b=b.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g,function(i,h,g){for(c in d){if(d.hasOwnProperty(c)){h=h.split(c).join(d[c])}}return h+g})}return b};
function html_entity_decode(c,f){var e={},d="",a="",b="";a=c.toString();if(false===(e=this.get_html_translation_table("HTML_ENTITIES",f))){return false}delete (e["&"]);e["&"]="&amp;";for(d in e){b=e[d];a=a.split(b).join(d)}a=a.split("&#039;").join("'");return a};
function htmlspecialchars(c,h,g,b){var e=0,d=0,f=false;if(typeof h==="undefined"||h===null){h=2}c=c.toString();if(b!==false){c=c.replace(/&/g,"&amp;")}c=c.replace(/</g,"&lt;").replace(/>/g,"&gt;");var a={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(h===0){f=true}if(typeof h!=="number"){h=[].concat(h);for(d=0;d<h.length;d++){if(a[h[d]]===0){f=true}else{if(a[h[d]]){e=e|a[h[d]]}}}h=e}if(h&a.ENT_HTML_QUOTE_SINGLE){c=c.replace(/'/g,"&#039;")}if(!f){c=c.replace(/"/g,"&quot;")}return c};
function htmlspecialchars_decode(b,f){var d=0,c=0,e=false;if(typeof f==="undefined"){f=2}b=b.toString().replace(/&lt;/g,"<").replace(/&gt;/g,">");var a={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(f===0){e=true}if(typeof f!=="number"){f=[].concat(f);for(c=0;c<f.length;c++){if(a[f[c]]===0){e=true}else{if(a[f[c]]){d=d|a[f[c]]}}}f=d}if(f&a.ENT_HTML_QUOTE_SINGLE){b=b.replace(/&#0*39;/g,"'")}if(!e){b=b.replace(/&quot;/g,'"')}b=b.replace(/&amp;/g,"&");return b};
function json_decode(str_json){var json=this.window.JSON;if(typeof json==="object"&&typeof json.parse==="function"){try{return json.parse(str_json)}catch(err){if(!(err instanceof SyntaxError)){throw new Error("Unexpected error type in json_decode()")}this.php_js=this.php_js||{};this.php_js.last_error_json=4;return null}}var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;var j;var text=str_json;cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return"\\u"+("0000"+a.charCodeAt(0).toString(16)).slice(-4)})}if((/^[\],:{}\s]*$/).test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,""))){j=eval("("+text+")");return j}this.php_js=this.php_js||{};this.php_js.last_error_json=4;return null};
function json_encode(g){var e,b=this.window.JSON;try{if(typeof b==="object"&&typeof b.stringify==="function"){e=b.stringify(g);if(e===undefined){throw new SyntaxError("json_encode")}return e}var d=g;var a=function(h){var j=/[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;var i={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"};j.lastIndex=0;return j.test(h)?'"'+h.replace(j,function(k){var l=i[k];return typeof l==="string"?l:"\\u"+("0000"+k.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+h+'"'};var f=function(s,o){var q="";var j="    ";var m=0;var l="";var t="";var h=0;var p=q;var n=[];var r=o[s];if(r&&typeof r==="object"&&typeof r.toJSON==="function"){r=r.toJSON(s)}switch(typeof r){case"string":return a(r);case"number":return isFinite(r)?String(r):"null";case"boolean":case"null":return String(r);case"object":if(!r){return"null"}if((this.PHPJS_Resource&&r instanceof this.PHPJS_Resource)||(window.PHPJS_Resource&&r instanceof window.PHPJS_Resource)){throw new SyntaxError("json_encode")}q+=j;n=[];if(Object.prototype.toString.apply(r)==="[object Array]"){h=r.length;for(m=0;m<h;m+=1){n[m]=f(m,r)||"null"}t=n.length===0?"[]":q?"[\n"+q+n.join(",\n"+q)+"\n"+p+"]":"["+n.join(",")+"]";q=p;return t}for(l in r){if(Object.hasOwnProperty.call(r,l)){t=f(l,r);if(t){n.push(a(l)+(q?": ":":")+t)}}}t=n.length===0?"{}":q?"{\n"+q+n.join(",\n"+q)+"\n"+p+"}":"{"+n.join(",")+"}";q=p;return t;case"undefined":case"function":default:throw new SyntaxError("json_encode")}};return f("",{"":d})}catch(c){if(!(c instanceof SyntaxError)){throw new Error("Unexpected error type in json_encode()")}this.php_js=this.php_js||{};this.php_js.last_error_json=4;return null}};
function json_last_error(){return this.php_js&&this.php_js.last_error_json?this.php_js.last_error_json:0};
function serialize(c){var a,i,j,b="",f="",d=0,g=function(p){var n=0,m=0,k=p.length,o="";for(m=0;m<k;m++){o=p.charCodeAt(m);if(o<128){n+=1}else{if(o<2048){n+=2}else{n+=3}}}return n},h=function(p){var l,n,k,m,o=typeof p;if(o==="object"&&!p){return"null"}if(o==="object"){if(!p.constructor){return"object"}k=p.constructor.toString();l=k.match(/(\w+)\(/);if(l){k=l[1].toLowerCase()}m=["boolean","number","string","array"];for(n in m){if(k==m[n]){o=m[n];break}}}return o},e=h(c);switch(e){case"function":a="";break;case"boolean":a="b:"+(c?"1":"0");break;case"number":a=(Math.round(c)==c?"i":"d")+":"+c;break;case"string":a="s:"+g(c)+':"'+c+'"';break;case"array":case"object":a="a";for(i in c){if(c.hasOwnProperty(i)){b=h(c[i]);if(b==="function"){continue}j=(i.match(/^[0-9]+$/)?parseInt(i,10):i);f+=this.serialize(j)+this.serialize(c[i]);d++}}a+=":"+d+":{"+f+"}";break;case"undefined":default:a="N";break}if(e!=="object"&&e!=="array"){a+=";"}return a};
function unserialize(f){var c=this,a=function(h){var i=h.charCodeAt(0);if(i<128){return 0}if(i<2048){return 1}return 2},b=function(j,k,i,h){throw new c.window[j](k,i,h)},g=function(l,n,m){var j=2,h=[],k=l.slice(n,n+1);while(k!=m){if((j+n)>l.length){b("Error","Invalid")}h.push(k);k=l.slice(n+(j-1),n+j);j+=1}return[h.length,h.join("")]},e=function(m,n,l){var j,k,h;h=[];for(j=0;j<l;j++){k=m.slice(n+(j-1),n+j);h.push(k);l-=a(k)}return[h.length,h.join("")]},d=function(z,l){var x,o,w,p,y,j,m,h,u,A,k,r,s,n,t,q=0,v=function(i){return i};if(!l){l=0}x=(z.slice(l,l+1)).toLowerCase();o=l+2;switch(x){case"i":v=function(i){return parseInt(i,10)};j=g(z,o,";");q=j[0];y=j[1];o+=q+1;break;case"b":v=function(i){return parseInt(i,10)!==0};j=g(z,o,";");q=j[0];y=j[1];o+=q+1;break;case"d":v=function(i){return parseFloat(i)};j=g(z,o,";");q=j[0];y=j[1];o+=q+1;break;case"n":y=null;break;case"s":m=g(z,o,":");q=m[0];h=m[1];o+=q+2;j=e(z,o+1,parseInt(h,10));q=j[0];y=j[1];o+=q+2;if(q!=parseInt(h,10)&&q!=y.length){b("SyntaxError","String length mismatch")}break;case"a":y={};w=g(z,o,":");q=w[0];p=w[1];o+=q+2;for(u=0;u<parseInt(p,10);u++){k=d(z,o);r=k[1];A=k[2];o+=r;s=d(z,o);n=s[1];t=s[2];o+=n;y[A]=t}o+=1;break;default:b("SyntaxError","Unknown / Unhandled data type(s): "+x);break}return[x,o-l,v(y)]};return d((f+""),0)[2]};

// =====================
