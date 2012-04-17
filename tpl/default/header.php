<?php
if (!defined('RAPIDLEECH')) {
	require_once("index.html");
	exit();
}

if (!isset($litehead)) {

	$show_w3c_validator = false; // show W3C validator link

	$ch_curl = (extension_loaded("curl") ? 1 : 0);

	// This prep embeded acc if there's any
	if (isset($premium_acc)) {
		$acc_txt = '';
		$spacer = '<div class="embd_acc"><\/div>';

		foreach ($premium_acc as $host_acc => $val) {
			if (isset($premium_acc[$host_acc]['user'])) {
				$acc_txt .= ($premium_acc[$host_acc]['user'] != '' && $premium_acc[$host_acc]['pass'] != '' ? $ar_host_acc[$host_acc] . $spacer : '');
			} // end user & pass configuration
			if (isset($premium_acc[$host_acc]['cookie'])) {
				$acc_txt .= ($premium_acc[$host_acc]['cookie'] != '' ? $ar_host_acc[$host_acc] . " cookie" . $spacer : '');
			}
			if (isset($premium_acc[$host_acc]['key'])) {
				$acc_txt .= ($premium_acc[$host_acc]['key'] != '' ? $ar_host_acc[$host_acc] . " key" . $spacer : '');
			}
		}
		$ar_rscom = (isset($premium_acc["rapidshare_com"]) ? $premium_acc["rapidshare_com"] : false);
	} //-end embed acc need
	else {
		$ar_rscom = false;
	}
	// check there's exist rs acc,. single or multi
	if ($ar_rscom) {
		$exist_accrs = (isset($ar_rscom["user"]) ? ($ar_rscom["user"] != '' && $ar_rscom["pass"] != '') : (isset($ar_rscom[0]["user"]) ? ($ar_rscom[0]["user"] != '' && $ar_rscom[0]["pass"] != '') : false));
	} else {
		$exist_accrs = false;
	}

	// like sess-id :P
	$usrajxnuid = str_replace("=", "", base64_encode(str_replace(".", "", $ipmu) . ':' . '4jaX'));

	$userck_std_mode = (isset($_GET["ajax"]) && isset($_GET["ausv"]) ) || (isset($_COOKIE["rl_ajax"]));

	if ($userck_std_mode) {
		if (isset($_GET["ajax"]) && isset($_GET["ausv"]) && $_GET["ausv"] == $usrajxnuid) {
			$options["disable_ajax"] = $_GET["ajax"] == "off";
		} else {
			$options["disable_ajax"] = ( (isset($_COOKIE["rl_ajax"]) ? $_COOKIE["rl_ajax"] == 0 : $options["disable_ajax"]) );
		}
	}
	$userck_std_mode = !$options["disable_ajax"];

	$ajax_serverfiles = (!$options["disable_ajax"]);
	$ajax_rename = ((!$options["disable_ajaxren"]) ? true : false);

	$showAccRsStatus = ($ch_curl == 1 && $exist_accrs && $options["premix_status"]);
} // end not litehead

$jQ_google_api_file = ROOT_URL.STATIC_DIR . "jquery.min.js";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ($charSet != "" ? $charSet : "utf-8"); ?>" />
<link rel="shortcut icon" type="image/gif" href="<?php echo (isset($shortcut_icon) ? $shortcut_icon : ROOT_URL.IMAGE_DIR . 'ico_home.gif') . '?' . rand(11, 9999); ?>" />
<title><?php
if (!isset($page_title)) {
	echo ':: ' . $RL_VER . ' ::';
} else {
	echo htmlentities($page_title);
}
?></title>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css' />
<link type="text/css" href="<?php print ROOT_URL.IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen" />
<script type="text/javascript" src="<?php echo $jQ_google_api_file; ?>"></script>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR; ?>js.php?main"></script>
<?php
if (!isset($litehead)) {
?>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR; ?>ajax.js"></script>
<link type="text/css" href="<?php echo ROOT_URL.STATIC_DIR; ?>jQ_fb.css" rel="stylesheet" media="screen" />
<script type="text/javascript">
/* <![CDATA[ */
var ajxtmr;
function stacc(){ document.poiuy.submit("staccounts","accwaiting","accshowresults",""); }
function doStatacc(){ajxtmr = setTimeout("get('staccounts','accwaiting','accshowresults','')", 300);}

var frmTB, destd;
//Link checker
var dlinks, arlinks, startFrm = 0;
<?php
	if ($ajax_serverfiles) {
?>
function changelink(obj, refresh){
	var strshow = "<?php echo ($L->say['_show'] != '' ? $L->say['_show'] : 'Show'); ?>&nbsp;";
	if(refresh==undefined) refresh = 0;
	if(getCookie("showAll") == 1) {
		if(refresh==0) deleteCookie("showAll");
		obj.innerHTML = strshow + "<?php echo ($L->say['_downloaded'] != '' ? $L->say['_downloaded'] : 'Downloaded'); ?>";
	} else {
		if(refresh==0) d.cookie = "showAll = 1;";
		obj.innerHTML =  strshow + "<?php echo ($L->say['_everything'] != '' ? $L->say['_everything'] : 'Everything'); ?>";
	}
}
function showAll2(){
	changelink(d.getElementById('showall'));
	go_gLoad(150);
}
function go_gLoad(dlay){
	if(dlay==undefined) dlay = 10;
	changelink(d.getElementById('showall'), 1);
	ajxtmr = setTimeout("_gLoad('tablefilewaiting', 'tablefilescontent')", dlay);
}
<?php
	} // end ajax
	if(is_array($deleted) && $deleted["count"] > 0) {
?>
function slip(Obj){var lst='<?php echo $L->say['hide_last_act'];?>';
   var d_txt = $('#act_hist_txt');
   var d_img = $('#act_hist_img');
   var d_hist = $('#act_history');
   if( d_txt.html() == lst ){
     d_txt.html('<?php echo $L->say['show_last_act'];?>');
     d_img.attr('src', '<?php echo ROOT_URL.IMAGE_DIR;?>plus.png'); d_hist.hide();
   }else{
     d_txt.html(lst); d_img.attr('src', '<?php echo ROOT_URL.IMAGE_DIR;?>min.png');
     d_hist.show();
   } d_img.blur(); return false;
}
<?php
	} // end delete log
?>
/* ]]> */
</script>
<?php
} // end not liteheade
?>
</head>
<body>
<?php if (isset($srvload) && $alert_sloadhigh) { echo $srvload; } ?>

<div class="head_container"><center>
<div class="subtitle1">easiest transfer files to your server</div>
<a class="rltitle" href="<?php echo (!isset($shortcut_icon) ? ROOT_URL.$options['index_file'] : "javascript:;"); ?>" title="Rapidleech">
<span class="rltitle">Rapidleech</span>
</a>
<div class="subtitle2">plugmod by eqbal moded idoenk</div>
</center></div>