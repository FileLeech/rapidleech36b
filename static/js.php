<?php
define('RAPIDLEECH', 'yes');

if (!isset($_GET['mod']) and isset($_SERVER['QUERY_STRING'])) {
  $_GET['mod'] = $_SERVER['QUERY_STRING'];
}
$nn = "\r\n";
$validpage = array('main','audl', 'auul', 'lynx', 'mtn');

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

$loca["thisFile"] = str_replace('\\','/',(__FILE__));
$loca["docRoot"] = $_SERVER['DOCUMENT_ROOT'] . (substr($_SERVER['DOCUMENT_ROOT'],-1) != "/" ? "/":"");
$webRoot  = str_replace(array($loca["docRoot"], STATIC_DIR . basename(__FILE__) ), '', $loca["thisFile"]);
unset($loca);
if($webRoot=="/") $webRoot = "";
define('ROOT_URL', "http://". $_SERVER["SERVER_NAME"] . "/" . $webRoot);

// Language initialisation
require_once(CLASS_DIR."lang.class.php");
$L = new RxLang;
$L->set_path(LANG_DIR);

// Load language
$L->set_language($options['lang']);
$L->load();
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
php_js_strings[281] = "<?php echo $L->say['local_time'];?>";

var IMG_DIR_TPL = '<?php echo ROOT_URL.IMAGE_DIR;?>';
var STATIC_DIR = '<?php echo ROOT_URL.STATIC_DIR;?>';
var phpjx = '<?php echo ROOT_URL.'ajax_main.php';?>';

// Frame Buster
if (top != self) {
	try {
		if (top.location.host != self.location.host) {
			top.location = self.location;
		}
	} catch (err) {
		top.location = self.location;
	}
}

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
function showAll(){
	if(getCookie("showAll") == 1){
		deleteCookie("showAll");
	}else{
		d.cookie = "showAll = 1;";
	}
	urlload();
}
function urlload(){
	location.href = location.href.split('?',1)+'?act=files';
}
function clk(idck){
	var cur = d.getElementById(idck).checked;
	d.getElementById(idck).checked = !cur;
}
function highlight(field) {
	field.focus(); field.select();
}
function fc(caption,displaytext) {
	if(c>0) {
		d.getElementById("dl").innerHTML = caption + php_js_strings[37].replace('{1}', c.toFixed(1));
		c = c - 0.5;
		setTimeout("fc('"+caption+"','"+displaytext+"')", 500);
	} else {
		d.getElementById("dl").style.display="none";
		d.getElementById("code").innerHTML = unescape(displaytext);
	}
}

// ===== batas global =====
<?php
if($req == 'main' OR $req == 'auul'){
?>
function setCheckboxes(act) {
	try {
		elts = d.getElementsByName("files[]");
	} catch (e) {}
	if (elts == undefined || elts == null) {
		return false;
	};
	var elts_cnt = (typeof(elts.length) != 'undefined') ? elts.length : 0;
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
// end-($req == 'main' OR $req == 'auul')
<?php
}
if($req == 'main' OR $req == 'lynx'){
?>
function gosetfacebook(){
	$(document).ready(function($) {
		$('a[rel*=facebox]').facebox({
			loading_text: php_js_strings[1], close_image: STATIC_DIR + 'images/nav_close6.png'
		})
	});
}
// end -($req == 'main' OR $req == 'lynx')
<?php
}
if($req == 'main'){
?>
function neoClass_tab(id){
	var tbobj = d.getElementById("tb" + id);
	d.getElementById("navcell" + id).className = "tab-on";
	tbobj.className = "tab-content show-table";
}
function switchCell(m) {
	var style;
	var obj = d.getElementById("tb" + m);
	for (i=1; i<=5; i++) {
		if(i != m){
			d.getElementById("navcell" + i).className = "tab-off";
			d.getElementById("tb" + i).className = "hide-table";
		}
	}
	setTimeout("neoClass_tab("+m+")", 70);
}
function clearSettings()  {
	clear("domail"); clear("email"); clear("split"); clear("method");
	clear("partSize"); clear("useproxy"); clear("proxy"); clear("saveto");
	clear("path"); clear("savesettings");

	$('#domail, #splitchkbox, #useproxy, #premium_acc, #saveto, #savesettings').attr('checked', false);
	$('#email, #proxyproxy, #proxyuser, #proxypass, #premium_user, #premium_pass').val('');
	$('#emailtd, #splittd, #methodtd, #proxy, #prblock, #path, #clearsettings').hide();

	d.cookie = "clearsettings = 1;";
}
function clear(name) {
	d.cookie = name + " = " + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
}
function showAdd() {
	d.getElementById('add').style.display = show ? 'none' : '';
	show = show ? 0 : 1;
}
function showAdd2() {
	d.getElementById('add2').style.display = show2 ? 'none' : '';
	show2 = show2 ? 0 : 1;
}
function mail(str, field) {
	d.getElementById("mailPart." + field).innerHTML = str;
	return true;
}
function setFtpParams() {
	setParam("host");
	setParam("port");
	setParam("login");
	setParam("password");
	setParam("dir");
	d.cookie = "ftpParams=1";
	d.getElementById("hrefSetFtpParams").style.color = "#808080";
	d.getElementById("hrefDelFtpParams").style.color = "#0000FF";
}
function delFtpParams() {
	deleteCookie("host");
	deleteCookie("port");
	deleteCookie("login");
	deleteCookie("password");
	deleteCookie("dir");
	deleteCookie("ftpParams");
	d.getElementById("hrefSetFtpParams").style.color = "#0000FF";
	d.getElementById("hrefDelFtpParams").style.color = "#808080";
}
function setParam(param) {
	d.cookie = param + "=" + d.getElementById(param).value;
}
function pr(percent, received, speed) {
	d.getElementById("received").innerHTML = '<b>' + received + '</b>';
	d.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
	d.getElementById("progress").style.width = percent + '%';
	d.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
	d.title = 'Uploaded ' + percent + '%';
	return true;
}
function changeStatus(file, size) {
	d.getElementById("status").innerHTML = php_js_strings[36] + ' <b>' + file + '</b>, Size <b>' + size + '</b>...<br />';
}

function zip() {
	var i = d.ziplist.act.selectedIndex;
	var selected = d.ziplist.act.options[i].value;
	d.getElementById('add').style.display = 'none';
	switch (selected){
		case "zip_add":
			d.getElementById('add').style.display = 'block';
			break;
	}
}
function new_transload_window() {
	var tmp = new Date();
	tmp = tmp.getTime();
	$('form[name=transload]').attr('target', 'rapidleech_'+tmp);
	var options = "width=700,height=320,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no";
	window.open('','rapidleech_'+tmp, options);
	window.setTimeout("$('form[name=transload]').submit();", 200);
}
//end-$req == 'main'
<?php
}
if($req == 'lynx'){
?>
function trparser(){
	var _tpl = "", i = 0;
	for(var date in dFile) {
		pfile = dFile[date];
		_tpl = dtemplate;
		// do replacement string;  10-May-2009, 23:09
		var now = new Date(parseInt(date) * 1000);
		dDate = now.toGMTString("dd-mmm-yy, HH:MM");
		dDate = dDate.split(" ");
		_tpl = _tpl.replace(/\(\(chkidx\)\)/g, i);
		_tpl = _tpl.replace(/\(\(filename\)\)/g, (pfile["name"]));
		_tpl = _tpl.replace(/\(\(filelink\)\)/g, (_dlpath + (pfile["name"])) );
		_tpl = _tpl.replace(/\(\(filesize\)\)/g, pfile["size"]);
		_tpl = _tpl.replace(/\(\(formatdate\)\)/g, dDate[1]+'-'+dDate[2]+'-'+dDate[3].substr(2,2)+', '+dDate[4].substr(0,2)+':'+dDate[4].substr(3,2)+':'+dDate[4].substr(6,2));
		_tpl = _tpl.replace(/\(\(fileage\)\)/g, pfile["age"]);
		_tpl = _tpl.replace(/\(\(dlpath\)\)/g, _dlpath);
		_tpl = _tpl.replace(/\(\(b64filename\)\)/g, pfile["delkey"]);
		text+= _tpl;
		i++;
	}
}

var idwindow = new Array();
function opennewwindow(id) {
	var options = "width=700,height=250,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
	var start_link = d.getElementById(id);
	idwindow[id] = window.open(start_link, dwindow+id, options);
	idwindow[id].opener=self;
	idwindow[id].focus();
}
function sAll(niLai){
	var dc;
	diq=d.getElementById('add_comment').checked=false;
	d.getElementById('comment').style.display='none';
	for (i=0;i< Opt["nC"] ;i++) {
		d.getElementById('chkfL-'+i).checked=niLai;
		dc = d.getElementById('brs'+i);
		if(niLai){
			dc.className='rowlist_checked';
		}else{
			dc.className='rowlist';
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
		var poslynx,
		hostpath = d.location.href;
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

//end-$req == 'lynx'
<?php
}
?>
// =========Below is global use============


/** //BASE64
* ===========================
* Location    : ./static/b64
* Adapted for : RL OLC 36B
*
* Re-generated   : 1009-09 // ddmm-yy
* ===========================
*/

var base64Chars = new Array(
	'A','B','C','D','E','F','G','H',
	'I','J','K','L','M','N','O','P',
	'Q','R','S','T','U','V','W','X',
	'Y','Z','a','b','c','d','e','f',
	'g','h','i','j','k','l','m','n',
	'o','p','q','r','s','t','u','v',
	'w','x','y','z','0','1','2','3',
	'4','5','6','7','8','9','+','/'
	);

var base64Str,base64Count;
function setBase64Str(str){
	base64Str = str;
	base64Count = 0;
}
function readBase64(){
	if (!base64Str) return -1;
	if (base64Count >= base64Str.length) return -1;
	var c = base64Str.charCodeAt(base64Count) & 0xff;
	base64Count++;
	return c;
}
function encodeBase64(str){
	setBase64Str(str);
	var result = '',inBuffer = new Array(3),lineCount = 0,done = false;
	while (!done && (inBuffer[0] = readBase64()) != -1){
		inBuffer[1] = readBase64();
		inBuffer[2] = readBase64();
		result += (base64Chars[ inBuffer[0] >> 2 ]);
		if (inBuffer[1] != -1){
			result += (base64Chars [(( inBuffer[0] << 4 ) & 0x30) | (inBuffer[1] >> 4) ]);
			if (inBuffer[2] != -1){
				result += (base64Chars [((inBuffer[1] << 2) & 0x3c) | (inBuffer[2] >> 6) ]);
				result += (base64Chars [inBuffer[2] & 0x3F]);
			} else {
				result += (base64Chars [((inBuffer[1] << 2) & 0x3c)]);
				result += ('=');
				done = true;
			}
		} else {
			result += (base64Chars [(( inBuffer[0] << 4 ) & 0x30)]);
			result += ('=');
			result += ('=');
			done = true;
		}
	/*
		lineCount += 4;
        if (lineCount >= 76){
            result += ('\n');
            lineCount = 0;
        }
		*/
	}
	return result;
}
//--- end base64
// =====================

/**
* Script by   : Daniel_X_x
* ===========================
* Location    : ./static/matches
* Adapted for : RL OLC 36B
*
* Re-generated   : 3009-09 // ddmm-yy
* ===========================
*/
var fl_rwhide=new Array(); // buffer global table object and selected rows array

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
	var tmp;
	var cell_text;
	var cell_matches;
	var text_regexp;
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
	var newTime;
	newTime = now.getTime() - timeDiff;
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
	var dn="AM";
	if (hours>=12) dn="PM";
	if (hours>12) hours=hours-12;
	if (hours==0) hours=12;
	if (hours<=9) hours="0"+hours;
	if (minutes<=9) minutes="0"+minutes;
	if (seconds<=9) seconds="0"+seconds;

	var cdate="<span style=\"color:#999\">"+hours+":"+minutes+":"+seconds+" "+dn+"&nbsp;</span><span style=\"color:#FF8700\">("+php_js_strings[281]+")</span>";
	$('#clock').html(cdate);
	setTimeout("getthedate()",1000);
}

// =====================