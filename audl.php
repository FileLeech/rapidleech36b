<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: audl.php - 06apr2010-Idx $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";

$ch_curl = (extension_loaded("curl") ? 1 : 0);
$jQ_google_api_file = STATIC_DIR . "jquery.min.js";
//===================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charSet; ?>" />
<title>ADL :: <?php echo $RL_VER;?> ::</title>
<link type="text/css" href="<?php print IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen" />
<link type="image/gif" rel="shortcut icon" href="<?php echo IMAGE_DIR . 'ico_audl.gif?' . rand(11, 9999); ?>" />
<script type="text/javascript" src="<?php echo $jQ_google_api_file; ?>"></script>
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>js.php?audl"></script>
</head>
<body>
<div class="head_container"><center>
<a href="<?php echo $options['index_file'];?>" class="tdheadolgo" title="Rapidleech"></a></center>
</div>
<br />
<center>
<noscript><p><b><?php echo $L->say['js_disable'];?></b></p></noscript>
<?php
if( (isset($limitation_alert) && $limitation_alert) || ($options["limitbyIP"] && $ada_acc && ($trheute > $options["maximum_free_downloads"]))) {
	if (!empty($limitmsg) || !empty($limit_msg)) $errmsg = $limitmsg.$limit_msg;
	elseif (!empty($msgNya)) $errmsg = $msgNya;
	echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class=\"warn_alert\">{$errmsg}</div></div>";
	if($options["navi_left"]["server_info"]){
	  if(@file_exists(CLASS_DIR."sinfo.php")) {
		require_once(CLASS_DIR."sinfo.php");
		echo "<div style='padding-left:5px;text-align:center;width:auto;margin-top:-5px;'><small>{$server['property']}". $L->sprintf($L->say["page_load"], $maintimer->stop())."</small></div>";
	  }
	}else echo "<hr />";
	echo "</center></body></html>";
	exit();
}
if ($options['auto_del_time'] > 0) {
	list($ddelay, $autodel_unit_time) = autodel_formatted($options['auto_del_time']);
	echo "<span class=\"c\">" . $L->say['_autodel'] . ":&nbsp;<b class=\"g\">" . $ddelay . "</b>&nbsp;" . $autodel_unit_time . "</span>";
	//auto_del($options['auto_del_time']);
	purge_files($options['auto_del_time']);
}
if ($options['audl'] > 0) {
	echo "&nbsp;||&nbsp;<span class=\"c\">Link Allow:&nbsp;<b class=\"g\">" . $options['audl'] . "</b>&nbsp;link" . ($options['audl'] > 1 ? "s" : "") . "</span>";
}
if ($options['minlimitsize'] > 0) {
	echo "&nbsp;||&nbsp;<span class=\"c\">" . $L->say['_minfilesize'] . ":&nbsp;<b class=\"s\">" . $options['minlimitsize'] . "</b>&nbsp;MB</span>";
}
if ($options['maxlimitsize'] > 0) {
	echo "&nbsp;||&nbsp;<span class=\"c\">" . $L->say['_maxfilesize'] . ":&nbsp;<b class=\"s\">" . $options['maxlimitsize'] . "</b>&nbsp;MB</span>";
}
if (!empty($options['add_ext_5city'])) {
	echo "&nbsp;||&nbsp;<span class=\"c\">" . $L->say['_fakeext'] . ":&nbsp;<b><a style=\"color:red;\" href=\"javascript:;\" title=\"Auto rename extension with this\">." . $options['add_ext_5city'] . "</a></b></span>";
}
if ($options['limit_timework']) {
	echo "<br /><span class=\"c\">" . $L->say['_timework'] . ":&nbsp;</span><b class=\"s\">" . $options['workstart'] . "</b>&nbsp;" . $L->say['_upto'] . "&nbsp;<b class=\"s\">" . $options['workend'] . "</b>";
}
?>
<?php
if (isset($_REQUEST["GO"]) && $_REQUEST["GO"] == "GO" && isset($_POST["links"])) {
	$getlinks = explode("\r\n", htmlentities(trim($_POST["links"])));
	foreach ($getlinks as $key => $value) {
		if(empty($getlinks[$key])) unset($getlinks[$key]);
		$getlinks[$key] = urlcleaner($getlinks[$key]);
		/*if (!preg_match("/^(http(s?):\/\/|ftp:\/\/{1})+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i", trim($getlinks[$key]), $mathces)) {
			unset($getlinks[$key]);
		}*/
	}
	$getlinks = array_values($getlinks);
	if (!count($getlinks) || (htmlentities(trim($_POST["links"])) == "")) {
		echo '<script type="text/javascript">function gotoback(){au=d.location.href; au=au.substring(0, au.indexOf("?")); d.location.href=au;}</script>';
		die('<br/><br/><span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>' . $L->say['not_link'] . '</b></span><br />' .
			'<script type="text/javascript">setTimeout("gotoback()", 1500);</script>');
	}

	if ($options['audl'] > 0) {  // if there's a limitation in audl link submission
		if (count($getlinks) > $options['audl']) {
			echo '<script type="text/javascript">function dopostback(){d.backpost.submit();}</script><form action="' . basename($PHP_SELF) . '" name="backpost" id="backpost" method="post"><div style="display:none;"><textarea name="bufferlink" id="bufferlink">' . $_POST["links"] . '</textarea></div></form>';
			die('<span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>' . $L->sprintf($L->say['reach_lim_audl'], $options['audl']) . '</b></span><br/><br/><a href="javascript:;" onclick="dopostback()"><b>[ ' . $L->say['back_main'] . ' ]</b></a>' . "\r\n" . '</body></html>');
		}
	}

	$start_link = $options["index_file"] . '?';
	for ($i = 0; $i < count($getlinks); $i++) {
		$sLnk = $getlinks[$i];
		$getlinks[$i] = urlcleaner($getlinks[$i]);
	}

	if (isset($_REQUEST["useproxy"]) && $_REQUEST["useproxy"] && (!$_REQUEST["proxy"] || !strstr($_REQUEST["proxy"], ":"))) {
		die('<span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>' . $L->say['wrong_proxy'] . '</b></span><br />');
	} else {
		if (isset($_REQUEST["useproxy"]) && $_REQUEST["useproxy"] == "on") {
			$start_link.='&proxy=' . $_REQUEST["proxy"];
			$start_link.='&proxyuser=' . $_REQUEST["proxyuser"];
			$start_link.='&proxypass=' . $_REQUEST["proxypass"];
		}
	}

	if (isset($_POST['cookieuse'])) {
		$start_link.='&cookie=' . urlencode(($_POST['cookie']));
	}
	if (isset($_POST['ytube_mp4'])) {
		$start_link.='&ytube_mp4=' . urlencode(($_POST['ytube_mp4'])) . '&yt_fmt=' . urlencode(($_POST['yt_fmt']));
	}

	$rnum = rand(11, 99);

	$start_link.= (isset($_REQUEST["df_acc"]) ? '&df_acc=' . $_REQUEST["df_acc"] : '') . (isset($_REQUEST["hf_acc"]) ? '&hf_acc=' . $_REQUEST["hf_acc"] : '') .
			(isset($_REQUEST["net_acc"]) ? '&net_acc=' . $_REQUEST["net_acc"] : '') . (isset($_REQUEST["rs_acc"]) ? '&rs_acc=' . $_REQUEST["rs_acc"] : '') .
			(isset($_REQUEST["ul_acc"]) ? '&ul_acc=' . $_REQUEST["ul_acc"] : '') . (isset($_REQUEST["upl_acc"]) ? '&upl_acc=' . $_REQUEST["upl_acc"] : '') .
			(isset($_REQUEST["rspre_com"]) ? '&premium_acc=' . $_REQUEST["rspre_com"] : '');

	if (isset($_REQUEST["rspre_com"]) && $_REQUEST["rspre_com"] == "on") {
		$pre_type = $_REQUEST["acc_type"];
		$pre_user = $_REQUEST["rrapidlogin_com"] ? $_REQUEST["rrapidlogin_com"] : null;
		$pre_pass = $_REQUEST["rrapidpass_com"] ? $_REQUEST["rrapidpass_com"] : null;
		$start_link.=($pre_user == null && $pre_pass == null ? '&maudl=multi' : ($pre_type != '' ? '&premium_user=' . $pre_user . '&premium_pass=' . $pre_pass : '&auth_hash=' . encEnti(rotN(base64_encode($pre_user . ":" . $pre_pass), $rnum)) . $rnum) );
	}
	if (isset($_REQUEST["df_acc"]) && $_REQUEST["df_acc"] == "on") {
		$cook = isset($_REQUEST["df_cookie"]) ? $_REQUEST["df_cookie"] : $premium_acc['depositfiles_com']['cookie'];
		$start_link.='&df_hash=' . encEnti(rotN($cook, $rnum)) . $rnum;
	}
	if (isset($_REQUEST["hf_acc"]) && $_REQUEST["hf_acc"] == "on") {
		$cook = isset($_REQUEST["hf_cookie"]) ? $_REQUEST["hf_cookie"] : $premium_acc['hotfile_com']['cookie'];
		$start_link.='&hf_hash=' . encEnti(rotN($cook, $rnum)) . $rnum;
	}
	if (isset($_REQUEST["net_acc"]) && $_REQUEST["net_acc"] == "on") {
		$cook = isset($_REQUEST["net_cookie"]) ? $_REQUEST["net_cookie"] : $premium_acc['netload_in']['cookie'];
		$start_link.='&net_hash=' . encEnti(rotN($cook, $rnum)) . $rnum;
	}
	if (isset($_REQUEST["rs_acc"]) && $_REQUEST["rs_acc"] == "on") {
		$cook = isset($_REQUEST["rs_cookie"]) ? $_REQUEST["rs_cookie"] : $premium_acc['rapidshare_com']['cookie'];
		$start_link.='&rs_hash=' . encEnti(rotN($cook, $rnum)) . $rnum;
	}
	if (isset($_REQUEST["ul_acc"]) && $_REQUEST["ul_acc"] == "on") {
		$cook = isset($_REQUEST["ul_cookie"]) ? $_REQUEST["ul_cookie"] : $premium_acc['uploaded_to']['cookie'];
		$start_link.='&ul_hash=' . encEnti(rotN($cook, $rnum)) . $rnum;
	}
	if (isset($_REQUEST["upl_acc"]) && $_REQUEST["upl_acc"] == "on") {
		$cook = isset($_REQUEST["upl_cookie"]) ? $_REQUEST["upl_cookie"] : $premium_acc['uploading_com']['cookie'];
		$start_link.='&upl_hash=' . encEnti(rotN($cook, $rnum)) . $rnum;
	}
?>
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>ajax.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
var audl=<?php echo ($options['audl'] > 0 ? $options['audl'] : 0);?>; //batas max autodownload
var set_delay=0;
var delay_=0;
var current_dlink=-1;
var current_status=-1;
var current_iframe=0;
var quotaframe = <?php echo (is_numeric($options['iframealocate']) ? (int) $options['iframealocate'] : 5);?>;  // How many iframe you will provide
var last_status = new Array();
var links = new Array();
var idwindow = new Array();
var iframe_ready = new Array();
var start_link='<?php echo $start_link; ?>';
var isAuto = false;
var StrMethod = "";
var metAudl = 0;  // queue atau simultan
var tmrNext;
var auto_check = <?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on" ? 'true' : 'false');?>

Array.prototype.inArray = function(valeur) {
	for (var i in this) { if (this[i] == valeur) return i; }
	return -1;
}

function IsNumeric(input){
	var RE = /^-{0,1}\d*\.{0,1}\d+$/;
	return (RE.test(input));
}

<?php if($options['audl'] > 0){
// protect audl being hijack with direct set value from url
?>
function forceAudl(){if(audl!=<?php echo $options['audl'];?>){audl=<?php echo $options['audl'];?>;};setTimeout("forceAudl()", 100);}
<?php }	?>

function chk_readyiframe() {
	var avliframe = -1;
	var i=-1;
	while((i < quotaframe) && (avliframe == -1)){
		i++;
		avliframe = iframe_ready.inArray(Math.round(i));
	}
	current_iframe = i;
}

function download(idx) {
	//check if the iframe ready
	chk_readyiframe();
	if(current_iframe == quotaframe){alert(quotaframe+' <?php echo $L->say['quota_reach'];?>'); return;}
	isAuto = false;
	StrMethod = "&idx=|manual";
	opennewwindow(idx);
	d.getElementById('audlbutton').style.display = 'none';
	d.getElementById('dButton' + idx).style.display = 'none';
	var methtxt = 'Method <b class="g">Manual</b>';
	d.getElementById('curmetode').innerHTML = methtxt;
	d.getElementById('action'+idx).innerHTML='&nbsp;<?php echo $L->say['_started'];?>&nbsp;';
}

function startisdone(id) {
	d.getElementById('action' + id).innerHTML = '<b class="g"><?php echo $L->say['_done'];?></b>';
}

function startauto() {
	//bring back simultan audl
	isAuto = true;
	metAudl=d.getElementById('metAudl').value;
	delay_ = d.getElementById('delay').value;
	if (metAudl==1 && (!((delay_>=1) && (delay_<=3600)) || !IsNumeric(delay_)))	{
		alert('<?php echo $L->say['error_interval']; ?>');
		return;
	}

	//bypass limit audl links brings auto button disapear, return false
	if(audl > 0 && links.length > audl){
		alert('<?php echo $L->sprintf($L->say['reach_lim_audl'], $options['audl']);?>');
		d.getElementById('audlbutton').style.display='none';
		return false;
	}

	set_delay = delay_*1000;

	current_dlink = -1;
	StrMethod = "&idx=auto|";
	if(metAudl==0){StrMethod = StrMethod + 'queue';}else{StrMethod = StrMethod + 'sims';}
	d.getElementById('audlbutton').style.display='none';

	for(var i=0; i<links.length; i++) {
		d.getElementById('dButton'+i).style.display='none';
		d.getElementById('action'+i).innerHTML='&nbsp;<?php echo $L->say['_waiting'];?> ';
	}
	nextlink(current_dlink);
}

function cdown(tgt, dlay){
	if(dlay>0){
		dlay = dlay - 1;
		d.getElementById('action'+tgt).innerHTML='&nbsp;<b>~<?php echo $L->say['_waiting'];?><span class="g">'+ dlay +'<\/span><\/b>';
		tmrNext = setTimeout("cdown("+tgt+", "+dlay+")", 1000);
	}else{
		clearTimeout(tmrNext);
		nextlink(tgt);
	}
}

function nextlink(ids) {
	if(metAudl==0){
		if(current_dlink>-1){d.getElementById('action'+current_dlink).innerHTML='<b class="g"><?php echo $L->say['_done'];?><\/b>';}
	}
	current_dlink++;
	if (current_dlink < links.length) {
		setTimeout('opennewwindow(current_dlink)', 300);
		d.getElementById('action'+current_dlink).innerHTML='&nbsp;<?php echo $L->say['_started'];?>&nbsp;';
		if(metAudl==1){
			if(current_dlink<links.length-1){
				cdown(current_dlink+1, delay_);
			}
		}
	}
}

function opennewwindow(id) {
	if (!isAuto || metAudl==0){
		var _tbliframe = d.getElementById('tbliframe').style.display = 'block';
		var _closeiframe = d.getElementById('closeiframe');
		var _auiframe = d.getElementById('auiframe');
		var _autitleiframe = d.getElementById('titleiframe');
		var _dwidth;
		if (window.innerWidth || window.innerHeight){
			//opera Netscape 6 Netscape 4x Mozilla
			_dwidth = eval(window.innerWidth * 70/100) + 'px';
		}
		if (d.body.clientWidth || d.body.clientHeight){
			////IE Mozilla
			_dwidth = eval(d.body.clientWidth * 70/100) + 'px';
		}
	}
	if(!isAuto){  // Start Manual mode
		_closeiframe.style.display = 'none';
		_auiframe.style.display = 'none';
		_autitleiframe.style.display = 'none';
		var _diviframe = d.getElementById('perdiv' + current_iframe);
		var _iframe = d.getElementById('frmaudl' + current_iframe);
		var _titleiframe = d.getElementById('ifrmlink' + current_iframe);
		var dum_iframe = new Array(), posCur = 0;

		_diviframe.style.display = 'block';
		_titleiframe.style.width = _dwidth;
		_iframe.style.width = _dwidth;
		_titleiframe.innerHTML = '<span class="g" id="idxlink' + current_iframe + '" style="padding-right:10px;">'+eval(id+1)+'<\/span><b>'+ links[id] +'<\/b>';
		posCur = iframe_ready.inArray(Math.round(current_iframe));
		iframe_ready.splice(posCur, 1);
		d.getElementById('iframealocatevalue').innerHTML = iframe_ready.length + ' of ' + quotaframe;
		_iframe.src = start_link + '&link='+strrev(unescape(links[id])) + StrMethod;

	}else{
		if(metAudl==0){ // Start Auto ~ Queue mode
			_auiframe.style.width = _dwidth;
			_autitleiframe.style.width = _dwidth;
			_autitleiframe.innerHTML = '<b><span class="g" style="padding-right:10px;">'+eval(id+1)+'<\/span>'+ links[id] +'<\/b>';
			_auiframe.src = start_link+'&link='+strrev(unescape(links[id]))+StrMethod;
			_closeiframe.style.display = 'block';
			_auiframe.style.display = 'block';
			d.getElementById('iframealocate').style.display = 'none';
		} else { // Start Simultan Download ~ open window popup
			var useloc = "<?php echo (isset($_REQUEST[" rspre_com "])?'no':'yes');?>";
			var options = "width=700,height=300,toolbar=no,location=" + useloc + ",directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
			idwindow[id] = window.open(start_link + '&link=' + strrev(unescape(links[id])) + StrMethod, id, options);
			idwindow[id].opener = self;
			idwindow[id].focus();
		}
	}
} // end opennewwindow

function addLinks(txtA) {
	var tbody = d.getElementById("links").getElementsByTagName("tbody")[0];
	Obj_txta = d.getElementById(txtA);
	try {
		Obj_txta.focus();
	} catch (e) {}
	var stringLinks = Obj_txta.value;
	var link_sisa = 0;

	if (audl > 0) {
		var tbody_row = tbody.getElementsByTagName("tr");
		link_sisa = eval(audl - tbody_row.length);
		if(link_sisa==0 || link_sisa<0){
			alert('<?php echo $L->sprintf($L->say['reach_lim_audl'], $options['audl']);?>');
			return false;
		}
	}
	arrayLinks = LnkChkHandler(stringLinks, link_sisa);

	for (var i = 0; i < (arrayLinks.length); i++) {
		arrayLinks[i] = arrayLinks[i].replace(/^\s+|\s+$/g,"");
		if(arrayLinks[i]!=""){
			arrayLinks[i] = jlnkfilter(arrayLinks[i]);
			var row = d.createElement("tr");
			var td1 = d.createElement("td");
			td1.appendChild(d.createTextNode(arrayLinks[i]));
			td1.setAttribute("align", "right");
			var td2 = d.createElement("td");
			var butn = d.createElement('button');
			td2.setAttribute("align", "center");
			td2.setAttribute("id", "action"+links.length);
			butn.appendChild(d.createTextNode('<?php echo $L->say['_download'];?>'));
			butn.setAttribute("onclick", "javascript:download("+links.length+");");
			butn.setAttribute("id", "dButton"+links.length);
			td2.appendChild(butn);
			var td3 = d.createElement("td");
			 <?php if (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on") {?>
			var imgLd = d.createElement("img");
			imgLd.setAttribute("src", "<?php echo IMAGE_DIR;?>fbload.gif");
			td3.appendChild(imgLd);
			td3.setAttribute("id", "status"+links.length);
			td3.setAttribute("align", "center");
			<?php }?>

			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			tbody.appendChild(row);

			links[links.length] = arrayLinks[i];
		} else {
			arrayLinks.length--;
		}
	}
	Obj_txta.value = "";
	startFrm = links.length - arrayLinks.length;
	setTimeout("Obj_txta.blur()", 100); <?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on" ? "d.getElementById('btnaddlinks').disabled=true; loadHandler();" : ""); ?>
} //end addLinks

function iframeReady(idx_ifr){
	iframe_ready.splice(0,0,idx_ifr);
	d.getElementById('iframealocatevalue').innerHTML = iframe_ready.length + ' of ' + quotaframe;
}

function clDIV(id){
	var divframe = d.getElementById("perdiv"+id);
	var _iframe = d.getElementById('frmaudl' + id);
	_iframe.src = 'about:blank';
	divframe.style.display = 'none';
}

function showIFrame() {
	var divframe = d.getElementById("divframeset");

	var ifrm, div, perdiv;
	var IMAGE_DIR = '<?php print IMAGE_DIR;?>';
	divframe.innerHTML = '';

	for (var i = 0; i < quotaframe; i++){
		perdiv = d.createElement('div');
		perdiv.setAttribute("id", "perdiv"+i);
		perdiv.setAttribute("style", "display:none;border:.1em solid rgb(102, 102, 102);padding:5px;");

		div = d.createElement('div');
		div.setAttribute("class", "g");
		div.setAttribute("id", "ifrmlink"+i);
		div.setAttribute("style", "padding-bottom:5px; font-weight:bold; float:left;");
		div.appendChild(d.createTextNode('Link '+i));
		perdiv.appendChild(div);
		div = d.createElement('div');
		div.setAttribute("class", "closer_btn");
		div.setAttribute("style", "position:relative; left:49%; margin-top:-2px;");
		div.setAttribute("onclick", "javascript:clDIV("+i+");");
		div.setAttribute("title", "Close this iframe");

		ca = d.createElement('a');
		ca.setAttribute("id", "close"+i);
		ca.setAttribute("href", "javascript:;");

		ca.innerHTML = "&nbsp;";
		div.appendChild(ca);
		perdiv.appendChild(div);
		ifrm = d.createElement("iframe");
		ifrm.id = "frmaudl" + i;
		ifrm.name = "frmaudl" + i;
		ifrm.height = '190px';

		if (d.all) {
			// MSIE, opera
			ifrm.src = "about:blank";
			perdiv.appendChild(ifrm);
		} else {
			perdiv.appendChild(ifrm);
			ifrm.src = "about:blank";
		}

		divframe.appendChild(perdiv);
		iframe_ready[i] = i;
	}
	d.getElementById('tbliframe').style.display = 'block';
} //end showIFrame
<?php
	for ($i = 0; $i < count($getlinks); $i++) {
		echo "\tlast_status[$i]=''; links[" . $i . "]='" . ($getlinks[$i]) . "';\n";
	}
?>
var rslinks, startFrm = 0;
function reLink() {
	startFrm = 0;
	d.getElementById('btnaddlinks').disabled=true;
	for(var i=0; i<links.length; i++){
		d.getElementById('statusTitle').innerHTML="&nbsp;<b><blink><?php echo $L->say['_status']; ?><\/blink><\/b>&nbsp;";
		d.getElementById('status'+i).innerHTML='&nbsp;<img src="<?php echo IMAGE_DIR;?>fbload.gif" \/>&nbsp;';
	}
}

function loadHandler() {
	rslinks = "";
	for(var i=startFrm; i<links.length; i++){
		rslinks+=links[i] + ";";
	}
	getResults(rslinks, links, 'audl');
}

function hEnter(inField, e) {
	var chCode;
	if(e && e.which){
		chCode = e.which;
	}else if(window.event){
		e = window.event;
		chCode = e.keyCode;
	}
	if(chCode == 13) {
		startauto();
	}
}

var btnaudl;
function radioaudl(id){
	var dv=d.getElementById('divdelay');
	var meth=d.getElementById('metAudl'), methtxt = 'Method ';
	if(id=="metodaudl0") {	 // Queue method selected
		dv.style.display = "none";
		meth.value = "0";
		d.getElementById('spanobo').style.background = "#800000";
		d.getElementById('spanobo').style.color = "#AAAAAA";
		d.getElementById('spansim').style.background = "";
		methtxt += '<b class="g">Queue</b>';
	} else {
		dv.style.display = "";
		dv = d.getElementById('delay');
		dv.focus();
		dv.select();
		meth.value = "1";
		d.getElementById('spanobo').style.background = "";
		d.getElementById('spansim').style.background = "#800000";
		d.getElementById('spansim').style.color = "#AAAAAA";
		methtxt += '<b class="g">Simultan</b>';
	}
	var _btnaudl = btnaudl;
	d.getElementById('audlstart').value = _btnaudl + ' [' + d.getElementById(id).value + ']';
	d.getElementById('curmetode').innerHTML = methtxt;
}
<?php if ($options["audl"] > 0) { ?>
forceAudl();
<?php } ?>
/* ]]> */
</script>

<table id="links" width="90%" style="border:1px solid #666" class="audlcontainer" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td width="80%" align="center"><b><?php echo $L->say['_link']; ?></b></td>
<td width="70" align="center">&nbsp;<b><?php echo $L->say['action']; ?></b>&nbsp;</td>
<td width="70" id='statusTitle' align="center">&nbsp;<b><?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on" ? "<blink>" : ""); echo $L->say['_status']; echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on" ? "</blink>" : "") ?></b>&nbsp;</td>
</tr>
</thead>
<tbody>
<?php
	for ($i = 0; $i < count($getlinks); $i++) {
		echo "<tr><td width='80%' nowrap align='right' id='row" . $i . "'><span>" . $getlinks[$i] . "</span></td>";
		echo "<td width='70' align='center' id='action" . $i . "'><button onclick='javascript:download($i);' id='dButton" . $i . "'>Download</button></td>";
		echo "<td width='70' align='center' id='status" . $i . "'>&nbsp;" . (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on" ? "<img src='" . IMAGE_DIR . "fbload.gif' />" : "") . "&nbsp;</td>";
		echo "</tr>\n";
	}
?>
</tbody>
<tfoot id="audlbutton">
<tr id='auto'>
<td colspan='3' align='center'><div style="padding-top:10px;"></div><input type='button' id='audlstart' value='<?php echo $L->say['audl_start']; ?>' onclick='javascript:startauto();' />
<script type="text/javascript">btnaudl = d.getElementById('audlstart').value;</script>
</td>
</tr>
<tr>
<td colspan="3" align='center'>
<table border='0' cellspacing="0" cellpadding="0">
<tr>
<td width="220" valign="top">&nbsp;</td>
<td width="140" valign="top"><label><input type="radio" name="metodaudl" id="metodaudl0" value="Queue" onclick="radioaudl(this.id)" onkeypress="hEnter(this, event);" checked="checked" />&nbsp;<span id="spanobo" style="background-color: #800000;color:#AAAAAA;"><?php echo $L->say['one_by_one']; ?></span></label><input type="hidden" id="metAudl" value="0" /></td>
<td width="300" valign="top"><label><input type="radio" name="metodaudl" id="metodaudl1" value="Simultanious" onclick="radioaudl(this.id)" />&nbsp;<span id="spansim" style="color:#AAAAAA;"><?php echo $L->say['simultanious']; ?></span></label></td>
</tr>
<tr>
<td></td>
<td colspan="2"><div id="divdelay" style="padding-left:50px; display:none;"><?php echo $L->say['audl_interval']; ?>&nbsp;<input type="text" name="delay" id="delay" value="30" size="10" onfocus="highlight(this);" onkeypress="hEnter(this, event);" style="padding-left:3px;" />&nbsp;<?php echo $L->say['_second']; ?></div>
</td>
</tr>
</table>
</td></tr>
</tfoot>
</table>
<div style="display:none">
<input type="checkbox" value="lnkcurl" name="lcurl" id="lcurl"<?php echo (($ch_curl == 1) ? ' checked="checked"' : ' disabled="disabled"'); ?> />
</div>
<script type="text/javascript">
/* <![CDATA[ */
<?php if (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on") { ?>loadHandler();<?php } ?>
 function dopostback(){ d.backpost.submit(); }
 function ifrmClose_audl(){d.getElementById("auiframe").src="about:blank"; d.getElementById("tbliframe").style.display="none"; }
/* ]]> */
</script>

<form action="<?php echo basename($PHP_SELF);?>" name="backpost" id="backpost" method="post">
<div style="display:none;"><textarea name="bufferlink" id="bufferlink"><?php print $_REQUEST["links"];?></textarea></div>
</form>

<span id="curmetode"><?php echo $L->say['audl_method']; ?> <b class="g"><?php echo $L->say['audl_queue']; ?></b></span>
<span id="iframealocate" title="Manual Method property">&nbsp;&nbsp;&#8212;&nbsp;&nbsp;<?php echo $L->say['iframe_ready']; ?> <b class="g" id="iframealocatevalue"><?php echo $options['iframealocate'] . " of " . $options['iframealocate'];?></b></span>
<table style="border:1px solid #666" class="container">
<tr>
<td valign="top" class="backaudl">
<a href="javascript:;" onclick="dopostback();" title="Back to Autodl" alt="Back to Autodl"><div id="left_audl"></div></a>
</td>
<td><textarea name="addlinks" id="addlinks" class="redtxtarea" style="width:640px; height:20px;" onfocus="if(this.value=='add more link here...'){this.value='';}this.style.height='100px';" onblur="if(this.value==''){this.style.height='20px'; this.value='add more link here...';}"><?php echo $L->say['add_more']; ?></textarea></td>
<td valign="top"><input type="button" id="btnaddlinks" value="<?php echo $L->say['add_link']; ?> " onclick="javascript:addLinks('addlinks');" <?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"] == "on" ? "disabled=true" : ""); ?> /></td>
</tr>
</table>

<table id="tbliframe" width="70%" style="display:none; padding-top:15px;">
<tr>
<td width="100%"><div id="titleiframe"></div></td>
<td align="right" id="closeiframe" style="display:none;">
<div class="closer_btn" onclick="ifrmClose_audl()" title="Close this iframe"></div>
</td>
</tr>
<tbody>
<tr><td colspan="2" align="center" width="100%">
<iframe id="auiframe" name="auiframe" src="about:blank" style="display:none;width:100%;">Frames not supported, update your browser</iframe>
<div id="divframeset"></div>
</td></tr>
</tbody>
</table>

<script type="text/javascript">
 showIFrame();
</script>
</center>
</body>
</html>
<?php
	exit();  // GO end here
}
/*
//SHOW TIME WORK
if($options['limit_timework']){
    $is_worktime = cek_worktime($options['workstart'], $options['workend']);
	$limitmsg="";
	if(!$is_worktime){
	  if(!empty($limitmsg)){$limitmsg.="<br />";}$limitmsg.=$L->say['worktime_alert'];
	  echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class='warn_alert'>$limitmsg</div></div>";
	  if($options['navi_left']['server_info']){if(@file_exists(CLASS_DIR."sinfo.php")) require_once(CLASS_DIR."sinfo.php");}else echo "<hr />";
	  echo "</body></html>";
	  exit();
	}
}*/
?>
<script type="text/javascript">
/* <![CDATA[ */
function ViewPage(page)	{
	vcurent = d.getElementById(page);
	vcurent.style.display = (vcurent.style.display=='none'?'block':'none');
	cHeight=d.getElementById('links').style.height.replace('px','');
	if(vcurent.style.display=='block'){
	 vHeight = (cHeight / 2) + 19 + 'px';
	}else{
	 vHeight = ((cHeight-19) * 2) + 'px';
	}
	d.getElementById('links').style.height=vHeight;
 }
function HideAll(){
	d.getElementById('entered').style.display='none';
}
/* ]]> */
</script>
<table cellspacing="0" cellpadding="1" id="entered" class="audlcontainer"><tr><td>
<form action="<?php echo $PHP_SELF;?>?GO=GO" method="post">
<table width="700" border="0">
<tr id="menu"><td width="700" align="center">
<a href="javascript:ViewPage('options');"><?php echo $L->say['_opt'];?></a>
</td></tr>
<tr><td width="100%" valign="top">
<div id="listing" style="display:block;">
<table border="0" style="width:710px;" >
<tbody>
<tr>
<td>
<div width="100%" id="options" style="display:none;">
<table cellspacing="5">
<tbody>
<tr>
<td align="center">
<table align="center">
<tr>
<td><label><input type="checkbox" id="autochk_lnk" name="autochk_lnk" <?php echo ($options['autochecklink'] ? ' checked="checked"' : ''); ?> />&nbsp;<?php echo $L->say['auto_check_link']; ?></label></td>
</tr>
<tr>
<td width="320">
<label><input type="checkbox" id="useproxy" name="useproxy" onclick="var displ=this.checked?'':'none';document.getElementById('proxy').style.display=displ;"<?php echo isset($_COOKIE["useproxy"]) ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['use_proxy']; ?></label>
<table border="0" id="proxy" style="padding-left:22px; display: none;">
<tr><td><?php echo $L->say['_proxy']; ?></td><td><input type="text" name="proxy" size="25"<?php echo isset($_COOKIE["proxy"]) ? " value=\"" . (isset($_COOKIE["proxy"]) ? $_COOKIE["proxy"] : "") . "\"" : ""; ?> /></td></tr>
<tr><td><?php echo $L->say['_uname']; ?></td><td><input type="text" name="proxyuser" size="25"<?php echo isset($_COOKIE["proxyuser"]) ? " value=\"" . (isset($_COOKIE["proxyuser"]) ? $_COOKIE["proxyuser"] : "") . "\"" : ""; ?> /></td></tr>
<tr><td><?php echo $L->say['_pass']; ?></td><td><input type="text" name="proxypass" size="25"<?php echo isset($_COOKIE["proxypass"]) ? " value=\"" . (isset($_COOKIE["proxypass"]) ? $_COOKIE["proxypass"] : "") . "\"" : ""; ?> /></td></tr>
</table>
</td>
</tr>
<?php
if ($options["maysaveto"] === true) {
?>
<tr>
<td>
<label><input type="checkbox" name="saveto" id="saveto" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('path').style.display=displ;"<?php echo isset($_COOKIE["saveto"]) ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['save_to']; ?></label>
<table id="path" <?php echo isset($_COOKIE["saveto"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td style="padding-left:22px;"><input type="text" name="savedir" size="55" value="<?php echo (!empty($_COOKIE['savedir']) ? $_COOKIE['savedir'] : (substr($options['download_dir'], 0, 6) != 'ftp://' ? realpath(DOWNLOAD_DIR) : $options['download_dir'])); ?>" /></td></tr>
</table>
</td>
</tr>
<?php
}
if (($ada_acc = (isset($premium_acc) && (is_array($premium_acc) && count($premium_acc) > 0)))) {
	foreach ($premium_acc as $dhost => $val) {
		if ($val) {
			$ada_acc = true;
			break;
		}
	}
}
?>
<tr>
<td>
<label><input type="checkbox" value="on" name="rspre_com" id="rspre_com" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('rapidblockcom').style.display=displcom;"<?php if (isset($options['premium_acc_audl']) && $ada_acc && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['use_premix']; ?></label>
<table id="rapidblockcom" width="150" border="0" <?php echo isset($_COOKIE["rspre_com"]) ? "" : " style=\"display: none;\""; ?>>
<tr>
<td style="padding-left:22px;"><label for="acc_type"><?php echo "Type: "; ?></label></td>
<td>
<select name="acc_type" id="acc_type">
<?php
foreach ($ar_host_acc as $khost => $nmhost) {
	echo '<option value="' . $khost . '">' . $nmhost . '</option>';
}
?>
</select>
</td>
</tr>
<tr><td style="padding-left:22px;"><label for="rpl"><?php echo $L->say['_uname']; ?></label></td><td><input type="text" id="rpl" name="rrapidlogin_com" size="20" onfocus="highlight(this);" value="<?php echo (isset($_COOKIE["rrapidlogin_com"]) ? $_COOKIE["rrapidlogin_com"] : ""); ?>" /></td></tr>
<tr><td style="padding-left:22px;"><label for="rppl"><?php echo $L->say['_pass']; ?></label></td><td><input type="password" id="rppl" name="rrapidpass_com" size="20" onfocus="highlight(this);" style="color: #912704;" value="<?php echo (isset($_COOKIE["rrapidpass_com"]) ? $_COOKIE["rrapidpass_com"] : ""); ?>" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" value="on" name="df_acc" id="df_acc" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('dfblock').style.display=displcom;" <?php if (isset($premium_acc['depositfiles_com']['cookie']) && isset($options['premium_acc_audl']) && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['plugin_depost']; ?></label>
<table width="150" id="dfblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="rpldf"><?php echo $L->say['_autolog']; ?></label></td><td><input type="text" id="rpldf" size="45" name="df_cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" value="on" name="hf_acc" id="hf_acc" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('hfprblock').style.display=displcom;" <?php if (isset($premium_acc['hotfile_com']['cookie']) && isset($options['premium_acc_audl']) && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['plugin_hotfile']; ?></label>
<table width="150" id="hfprblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="rplhf"><?php echo $L->say['_auth']; ?></label></td><td><input type="text" id="rplhf" size="45" name="hf_cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" value="on" name="net_acc" id="net_acc" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('netblock').style.display=displcom;" <?php if (isset($premium_acc['netload_in']['cookie']) && isset($options['premium_acc_audl']) && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['plugin_netl']; ?></label>
<table width="150" id="netblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="rplnet"><?php echo $L->say['_cookie_user']; ?></label></td><td><input type="text" id="rplnet" size="45" name="net_cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" value="on" name="rs_acc" id="rs_acc" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('rsprblock').style.display=displcom;" <?php if (isset($premium_acc['rapidshare_com']['cookie']) && isset($options['premium_acc_audl']) && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['plugin_rs']; ?></label>
<table width="150" id="rsprblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="rplrs"><?php echo $L->say['_enc']; ?></label></td><td><input type="text" id="rplrs" size="45" name="rs_cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" value="on" name="ul_acc" id="ul_acc" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('ulblock').style.display=displcom;" <?php if (isset($premium_acc['uploaded_net']['cookie']) && isset($options['premium_acc_audl']) && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['plugin_ul']; ?></label>
<table width="150" id="ulblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="rplul"><?php echo $L->say['_login']; ?></label></td><td><input type="text" id="rplul" size="45" name="ul_cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" value="on" name="upl_acc" id="upl_acc" onclick="javascript:var displcom=this.checked?'':'none';document.getElementById('uplblock').style.display=displcom;" <?php if (isset($premium_acc['uploading_com']['cookie']) && isset($options['premium_acc_audl']) && $options['premium_acc_audl']) print ' checked="checked"'; ?> />&nbsp;<?php echo $L->say['plugin_upl']; ?></label>
<table width="150" id="uplblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="rplupl"><?php echo $L->say['_rem_user']; ?></label></td><td><input type="text" id="rplupl" size="45" name="upl_cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="cookieuse" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('cookieblock').style.display=displ;" />&nbsp;<?php echo $L->say['plugin_cookie']; ?></label>
<table width="150" id="cookieblock" style="display: none;">
<tr><td style="padding-left:22px;"><label for="cookie"><?php echo $L->say['_cookie']; ?></label></td><td><input type="text" id="cookie" size="45" name="cookie" onfocus="highlight(this);" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="ytube_mp4" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('ytubeopt').style.display=displ;" checked="checked" />&nbsp;<?php echo $L->say['ytube_select']; ?></label>
<table width="150" border="0" id="ytubeopt" style="display: none;">
<tr>
<td><small><?php echo $L->say['ytube_fmt']; ?></small></td>
<td>
<select name="yt_fmt" id="yt_fmt">
<option value="highest" selected="selected"><?php echo $L->say['ytube_auto']; ?></option>
<option value="38"><?php echo $L->say['ytube_38']; ?></option>
<option value="37"><?php echo $L->say['ytube_37']; ?></option>
<option value="22"><?php echo $L->say['ytube_22']; ?></option>
<option value="45"><?php echo $L->say['ytube_45']; ?></option>
<option value="35"><?php echo $L->say['ytube_35']; ?></option>
<option value="44"><?php echo $L->say['ytube_44']; ?></option>
<option value="34"><?php echo $L->say['ytube_34']; ?></option>
<option value="43"><?php echo $L->say['ytube_43']; ?></option>
<option value="18"><?php echo $L->say['ytube_18']; ?></option>
<option value="5"><?php echo $L->say['ytube_5']; ?></option>
<option value="17"><?php echo $L->say['ytube_17']; ?></option>
</select>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</div>
<textarea id="links" name="links" class="redtxtarea" style="width:640px; height:250px;"><?php echo (isset($_REQUEST["bufferlink"]) ? $_REQUEST["bufferlink"] : ""); ?></textarea></td>
<td valign="top"><input type="submit" value="<?php echo $L->say['_download']; ?> " onclick="javascript:HideAll();" style="width:60px;height:80px;" /></td>
</tr>
</tbody>
</table>
</div>

</td></tr>
</table>
</form>
</td></tr>
</table>

</center>
</body>
</html>
