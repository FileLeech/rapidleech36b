<?php
error_reporting(0);
clearstatcache();
@ignore_user_abort(TRUE);
@set_magic_quotes_runtime(0);
//$win = strtolower(substr(PHP_OS,0,3)) == "win";

// Debug ? show config-template pre-saving
$_debug = false;

$ch_curl = (extension_loaded("curl") ? 1 : 0);
if(get_magic_quotes_gpc()) {
	if(!function_exists("strips")) {
		function strips(&$arr,$k="") {
			if (is_array($arr)) {
				foreach($arr as $k=>$v) {
					if (strtoupper($k) != "GLOBALS") 
					{strips($arr["$k"]);}}} 
			else {$arr = stripslashes($arr);}}}
		strips($GLOBALS);
}
$ver_um = '1.5';
define('RAPIDLEECH', 'yes');
define('CONFIG_DIR', './');
define('CLASS_DIR', 'classes/');
define('MISC_DIR', 'misc/');
define('LANG_DIR', 'languages/');

define('VERSION', "RL-CPanel RC ".$ver_um." by Idoenk :: nohuhu<br>[ TuxiNuX::<a href='javascript:void(0);' title='Kaskus.US #14'>CCPB</a> ]");
define('ACCVER', "Rapidshare-ACC Checker [by: reiluke]<br><small>Thanks to limpy&amp;Viant77</small>");
define('CONFIG_FILE', 'config.php');  // define your config file name here

define('ACC_EMBED', "<span class='g'>account embeded</span>");
define('CK_EMBED', "<span class='g'>cookie embeded</span>");

$fileconfig = CONFIG_DIR.CONFIG_FILE;
$nn = "\r\n";

require_once($fileconfig);
require_once(CLASS_DIR."other.php");
//needed in tpl, check if it false,. ajax will not load.
$ajax_serverfiles = (@file_exists("rsajax.js") && !$disable_ajax);

$PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;
$showcpanel2 =  $allowcpanel;

define('TPL_PATH', 'tpl'. '/' . $csstype . '/');
define('IMAGE_DIR', MISC_DIR . TPL_PATH);

$arlang = getArrayfromfile(LANG_DIR, 'language.', '.inc.php', 'file');
$arCSS = getArrayfromfile(MISC_DIR . 'tpl'. '/', '', '', 'dir');

$cp_login = array();
 if(!isset($loginCp)){
    $loginCp=array('admin'=>'admin');   //admin=>admin
	$defaultcp = true;
 }
 else{
  foreach($loginCp as $u=>$p){
	if($u.$p==='admin'.'admin')

	 {$defaultcp = true;}
	 $cp_login[0] = $u;
	 $cp_login[1] = $p;
  }
 }
 $rl_login = array();
 foreach($users as $u_rl=>$p_rl){
   $rl_login[0] = $u_rl;
   $rl_login[1] = $p_rl;
 }
 if(isset($ip_premixstat_list))
 {
	$cain_ip = '';
	foreach($ip_premixstat_list as $key => $value)
	{
		$cain_ip.= $ip_premixstat_list[$key];
		$cain_ip.= ", ";
	}
	$cain_ip = substr($cain_ip, 0, strlen($cain_ip)-2);
 }
 $ada_acc = false;
 foreach($premium_acc as $dhost => $val){
	if($val){$ada_acc=true; break;}
 } 
 
 @$task = $_REQUEST["mode"];
#=========LOGIN Area=========
if($showcpanel2){
 foreach($loginCp as $u=>$p){
  if(!empty($u)&&!empty($p)){
   if($_SERVER["PHP_AUTH_USER"]!=$u || $_SERVER["PHP_AUTH_PW"]!=$p){
    header('WWW-Authenticate: Basic realm="::Rx08 CPanel::"');
    header('HTTP/1_0 401 Unauthorized');
    exit( "<html>$nn<head>$nn<title>::Rx08 RL-CPanel::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">$nn<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style></head>$nn<body>$nn<h1>Rx08 RL-CPanel: NuLL</h1>$nn</body>$nn</html>");
   }
  }
 }
} else {
 echo "<html>$nn<head>$nn<title>:: Rx08 RL-CPanel::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">$nn<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style></head>$nn<body>$nn<h1>:: Rx08 :: <br>RL-CPanel Disabled</h1>$nn</body>$nn</html>";
 exit();
}
#=========END LOGIN Area=========

#=========Initial Glbl Array=========
$ar_chkbox_othr_acc = array(
 // Other Acc-Non Premix 
 'muhash_premix',
 'imghack_premix'

);
$ar_chkbox_acc = array(
 'rs_com_premix', // index = 0
 'rs_de_premix', 
 'megaupload_premix',
 'megashare_premix',
 'netload_premix',
 'gigasize_premix', // index = 5
 'share_online_premix', 
 'vBulletin_acc_premix',
 'uploaded_to_premix',
 'easyshare_premix',
 'depositfiles_premix', // index = 10
 'hotfile_com_premix', 
 'uploading_premix',
 'filefactory_premix',
 'ifile_it_premix',

);

$ar_chkbox_up_acc = array(
 'mediafire_up_upload',
 'rs_com_coll_upload',
 'rs_com_pre_upload',
 'mu_pre_upload',
 'hf_com_upload',
 'easysh_mem_upload',
 'deposit_up_upload',
 'uploading_up_upload'
);


#=========END Glbl Array=========

if (!function_exists("view_perms")){
 function view_perms($mode){
  if (($mode & 0xC000) === 0xC000) {$type = "s";}
  elseif (($mode & 0x4000) === 0x4000) {$type = "d";}
  elseif (($mode & 0xA000) === 0xA000) {$type = "l";}
  elseif (($mode & 0x8000) === 0x8000) {$type = "-";}
  elseif (($mode & 0x6000) === 0x6000) {$type = "b";}
  elseif (($mode & 0x2000) === 0x2000) {$type = "c";}
  elseif (($mode & 0x1000) === 0x1000) {$type = "p";}
  else {$type = "?";}
  $owner["read"] = ($mode & 00400)?"r":"-";
  $owner["write"] = ($mode & 00200)?"w":"-";
  $owner["execute"] = ($mode & 00100)?"x":"-";
  $group["read"] = ($mode & 00040)?"r":"-";
  $group["write"] = ($mode & 00020)?"w":"-";
  $group["execute"] = ($mode & 00010)?"x":"-";
  $world["read"] = ($mode & 00004)?"r":"-";
  $world["write"] = ($mode & 00002)? "w":"-";
  $world["execute"] = ($mode & 00001)?"x":"-";
  if ($mode & 0x800) {$owner["execute"] = ($owner["execute"] == "x")?"s":"S";}
  if ($mode & 0x400) {$group["execute"] = ($group["execute"] == "x")?"s":"S";}
  if ($mode & 0x200) {$world["execute"] = ($world["execute"] == "x")?"t":"T";}
  return $type.join("",$owner).join("",$group).join("",$world);
 }
}
if(!function_exists("view_size")) {
 function view_size($size){
  if (!is_numeric($size)) {return FALSE;}
  else {
   if ($size >= 1073741824) {$size = round($size/1073741824*100)/100 ."&nbsp;GB";}
   elseif ($size >= 1048576) {$size = round($size/1048576*100)/100 ."&nbsp;MB";}
   elseif ($size >= 1024) {$size = round($size/1024*100)/100 ."&nbsp;KB";}
   else {$size = $size . "&nbsp;B";}
   return $size;
  }
 }
}
if (!function_exists("view_perms_color")){
 function view_perms_color($o){
  if (!is_readable($o)) {return "<blink><font color=red>".view_perms(fileperms($o))."</font></blink>";}
  elseif (!is_writable($o)) {return "<blink><font color=white>".view_perms(fileperms($o))."</font></blink> <small>(read-only)</small>";}
  else {return "<font color=#00FF00>".view_perms(fileperms($o))."</font>";}
 }
}

function GetBetween($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}
	
function filterNumericFld($postname, $title, $defaultval){
 $valpost = (trim($_POST[$postname])==='' ? $defaultval : (numericPost($postname, $title) ? trim($_POST[$postname]) : $defaultval));
 return $valpost;
}

function numericPost($postname, $errmsg) {
 global $showpostn, $iserr;	
 if(!is_numeric(trim($_POST[$postname])) || trim($_POST[$postname]) < 0){
	 $showpostn = true; 
	 echo '<b class="a">'.$errmsg.' must be in positive numerical..</b><b class="b">!</b><br>';
	 $iserr = true;
	 return false;
 }else{
	 return true;
 }
}

function cekBlankAcc($parentchk, $usrnya, $passnya) {
	global $iserr;	
    if(($_POST[$parentchk]=='on')){
	  if($passnya!=''){$iserr = ($_POST[$passnya]=='');}
	  if($usrnya!=''){$iserr = $iserr || ($_POST[$usrnya]=='');}
	}
}

function isblankfield($parentchk, $childfield){
	global $showpostn, $iserr;
	if($showpostn)
	{
		if(($_POST[$parentchk]=='on')){
			if(empty($_POST[$childfield])){
			    echo '<b class="a">['.$parentchk.'] Account\'s Username or Password or Cookie is empty..</b><b class="b">!</b><br>';
				$iserr = true;
				return false;
			}
		}
	}	
}

function singlefilter($fpar, $fu, $fp, $ff){
  global $_COOKIE;  
  if($_POST[$fpar]=='on'){
	$accchange = (isset($_COOKIE[$fpar])?($_COOKIE[$fpar]!=''?true:false):false);
	if(!$accchange){ // no new acc, grab from config
	 switch($fpar)
	 {
	  case 'muhash_premix':
	    global $mu_cookie_user_value;
		$_POST[$fu] = $mu_cookie_user_value;
	  break;
	  case 'imghack_premix':
	    global $imageshack_acc;
	    $_POST[$fu] = $imageshack_acc["user"];
	    $_POST[$fp] = $imageshack_acc["pass"];
	  break;
	  
	  default: break;
	 }
	}else{
	  $_POST[$fu] = str_replace($ff, '', $_POST[$fu]);	
	  if($fpar=='imghack_premix')
	    {$_POST[$fp] = str_replace($ff, '', $_POST[$fp]);}
	  cekBlankAcc($fpar,$fu,$fp);
	}	
  }
  setcookie($fpar, "", time() - 3600, "/");
}

function fieldfilter($fpar, $fu, $fp, $ff, $acc = "acc_DL"){
 global $premium_acc, $upload_acc, $_COOKIE;
 if($fpar==''){ // if parent checkbox not given
   $_POST[$fu] = str_replace($ff, '', $_POST[$fu]);
   return $_POST[$fu];
 }else{
   if($_POST[$fpar]=='on'){
     // check the cookie. new given acc, must change its cookie key
     $accchange = (isset($_COOKIE[$fpar]) ? ($_COOKIE[$fpar]!='' ? true:false) : false);
	 if(!$accchange){ // no new acc, grab from config
		if($acc == "acc_DL"){
		  $key = str_replace("_premix", "", $fpar);
		  $_POST[$fu] = $premium_acc[$key]["user"];
		  $_POST[$fp] = $premium_acc[$key]["pass"];	
		}else{		  
		  $key = str_replace("_upload", "", $fpar);
		  $_POST[$fu] = $upload_acc[$key]["user"];
		  $_POST[$fp] = $upload_acc[$key]["pass"];
		}
	 }else{ // fill with new acc
        $_POST[$fu] = str_replace($ff, '', $_POST[$fu]);
        if($fp!=''){ 
		   $_POST[$fp] = str_replace($ff, '', $_POST[$fp]); 
		}
	    cekBlankAcc($fpar, $fu, $fp);
	 }
   }else { 
     $_POST[$fu] = ""; $_POST[$fp] = ""; 
   }
   setcookie($fpar, "", time() - 3600, "/");
 }
}

#============= WRITE CONFIG ================
# Final filter to write config
# Filter level 2; check strict value; raise error if value not valid
function write_config_file($config_file, $s) {
 global $_debug, $close_config_page, $saved_success, $showpostn, $iserr;
 $showpostn=true;
 
 //cek limit by ip
 if($_POST['limitbyip']=='on'){
  if(empty($_POST['max_free_dl'])){
   $showpostn=true;
   echo '<b class="a">Leeching per ip is empty..</b><b class="b">!</b><br>';
   $iserr = true;
   return false;
  } elseif(empty($_POST['delayip'])){
   $showpostn=true;
   echo '<b class="a">Delay per ip is empty..</b><b class="b">!</b><br>';
   $iserr = true;
   return false;
  }
 }

	 
	if($_POST["limitbytraffic"]=="on")
	{
	 if(!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $_POST['YetAnotherDate']) || (empty($_POST['max_trafic'])))
     {
		echo '<b class="a">Expire-Date Traffic is not a Date..</b><b class="b">!</b><br>';
		$iserr = true;
		return false;
     }
	}

	if($_POST["limit_timework"]=="on")
	{
	 $regtime = '/^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$/';
	 if((!preg_match($regtime, $_POST['workstart'])) || (!preg_match($regtime,$_POST['workend'])))
     {
		echo '<b class="a">Limit by Time is not a Time format..</b><b class="b">!</b><br>';
		$iserr = true;
		return false;
     }
	}
	isblankfield($_POST['limited_edition'], $_POST['list_allow_ip']);


  if(empty($_POST['dir'])){
  $showpostn=true;
  echo '<b class="a">Download directory is empty..</b><b class="b">!</b><br>';
  $iserr = true;
  return false;
 }
 
 if(empty($_POST['newusercp']) || empty($_POST['newpascp'])) {
  $showpostn=true;
  echo '<b class="a">Username or Password login Cpanel is empty..</b><b class="b">!</b><br>';
  $iserr = true;
  return false;
 }
 if(($_POST['login']=='on') && (empty($_POST['user']) || empty($_POST['pass']))){
  $showpostn=true;
  echo '<b class="a">Username or Password login index page is empty..</b><b class="b">!</b><br>';
  $iserr = true;
  return false;
 }
 
 if($_debug){
   echo "Debug Mode: <b class=a>ON</b><br>";
   echo "<textarea name=\"edit_text\" cols=\"122\" rows=\"20\" width=\"100%\" readonly>".($s)."</textarea>";
 }

 $close_config_page = true;
 $saved_success = false;
 if(!$iserr){
  $fp = @fopen($config_file,"w");
  if(!$fp) {
    $showpostn=true;
    echo "<b class=\"a\">Can't write to file..</b><b class=\"b\">!</b><br>";
    fclose($fp);
    return false;
  }else{
    @fwrite($fp,$s);
    fclose($fp);
    echo '<p><b style="color:#00FF33">Saved!</b>';
	$saved_success = true;
  }
 }
 else
 { echo '<br><b class="a">An errors occur, configuration is not saved</b><br>'; }
 
 if($_POST['day_reset_trafic'] > 0){$cur_trf = get_traffic(TRAFFIC_LST); upd_traffictime($cur_trf[0]);}
 
 echo '<div style="height:10px;"></div><input type="button" onClick="location.href=\'./'.basename($_SERVER['PHP_SELF']).'?mode=\'" value="Go Back to Config">&nbsp;|&nbsp;<input type="button" onClick="location.href=\'./\'" value="Go to main">';

}  // ===END write_config_file
#============= WRITE CONFIG ================

if($task=='editor'){
 if($disableadvanceeditor){
	exit( "<html><head></head><body>
<form action='".basename($PHP_SELF)."' name='frmmentalback' id='frmmentalback' method='post'>\n
<input type='hidden' name='_lh' value='KSIAN.deh.GW'>\n
</form><script type='text/javascript'>document.frmmentalback.submit();alert('Advanced Editor Disabled..!!!');</script></body></html>");
 }
}

?>
<html><head>
<meta name="robots" content="noindex, nofollow">
<title>:: Rx08 :: RL-CPanel</title>
<style type="text/css">
label:hover{text-decoration:underline overline;}
table{ border-collapse: collapse; border:0px solid #1E2637;}
th, td { padding: 0;}
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
</style>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>style_sujancok<?php print $csstype;?>.css");
-->
</style>
<link rel="shortcut icon" type="image/gif" href="<?php echo IMAGE_DIR.'ico_cpanel.gif?'.rand(11,9999);?>">
<script type="text/javascript">
var timer, txplain, method, stRet, hdret;
var d = document, lastslash;
var dirpath = location.pathname;
lastslash = dirpath.lastIndexOf("/");
dirpath = dirpath.substring(0,lastslash+1);
var imgpath = '<?php echo IMAGE_DIR;?>';
</script>
<script type="text/javascript" src="rxpanel.js"></script>
</head>

<body>
<div class="head_container"><center>
<div class="tdheadolgo">&nbsp;</div></center>
</div>

<center>
<table cellspacing="0" cellpadding="1" width="80%" class="tablexpanel">
<tr><td>
<div align="center">
<?php
if ((!is_readable($fileconfig) or is_dir($fileconfig))) {
  if (file_exists($fileconfig)) echo "<b>Permision denied (".htmlspecialchars($fileconfig).")!</b>";
  else { echo "<b>File does not exists.</b>";}
}
else
{
  $styledisplay=' style="display:none"';
  $showpostn=false; $iserr=false;
  $t_head = "<b>File:&nbsp;&nbsp;&nbsp;&nbsp;".CONFIG_FILE." (".view_size(filesize($fileconfig));
  $t_head.= ") &nbsp; &nbsp; &nbsp; attrib: &nbsp; &nbsp; ".view_perms_color($fileconfig)."</b>";
  $t_head.= "<br>You're&nbsp;logged&nbsp;with&nbsp;IP:&nbsp;<b class='g'>".get_real_ip()."</b><br><hr width=\"800\">";
  echo $t_head;
  if(isset($_GET["mode"])){
   if($_GET["mode"]=="editor"){
    echo "<p><b><span id='nv1' style='background-color:#840000;color:yellow;'>&nbsp;Editor&nbsp;</span></p>";}
  }

if(isset($_POST['submit'])){
    if($task=='editor'){
		$edt = $_POST["edit_text"];
		$fp = fopen($fileconfig,"w");
		if(!$fp) {echo "<b class='a'>Can't write to file!</b>";}
		else
		{
			fwrite($fp,$edt);
			fclose($fp);
			echo "<b style='color:#00FF33'>Saved!</b>";
			$r = $edt;
		}
	}
	else
	{
		if(empty($_POST['cplHash'])){
			// CPanel must have a login, at least this default u/p: admin/admin
			$u = $cp_login[0]; $p = $cp_login[1];
			if(trim($cp_login[0])==''){$u = 'admin';}
			if(trim($cp_login[1])==''){$p = 'admin';}
			$_POST['newusercp'] = $u;
			$_POST['newpascp'] = $p;
		}
		

		//cek rl login
		if(empty($_POST['rlauthHash'])){
			$_POST['user'] = $rl_login[0];
			$_POST['pass'] = $rl_login[1];			
		}

		if($_POST['newusercp'] != $cp_login[0] || $_POST['newpascp'] != $p)
		{	unset($_SESSION['cpauth']);	}
		if($_POST['user'] != $rl_login[0] || $_POST['pass'] != $rl_login[1])
		{	unset($_SESSION['reauth']);	}

		//filter input
	$badpetik = array('\'', '"');
	$badip = array('\'', '"', '`', '/', '\\', '|', '<', '>', '?', '+', '=', '^', ':', ';');
	$baddir = array('\'','"','`','\\','|','<','>','?','+','=','^','*',':',';');
	$badchr = array('\'','"','`','/','\\','|','<','>','?','+','=','^','*',':',';');
	$badtime = array('\'','"','`','/','\\','|','<','>','?','+','=','^','*',';',',','.');
	$_POST['dir'] = str_replace($baddir, '', $_POST['dir']);
	while(strstr($_POST['dir'], "//"))
     { $_POST['dir'] = str_replace("//", "/", $_POST['dir']); }
	if (substr($_POST['dir'],-1) != '/'){ $_POST['dir'] .= '/';}
		  
	$_POST['prefix'] = str_replace($badchr, '', $_POST['prefix']);
	$_POST['suffix'] = str_replace($badchr, '', $_POST['suffix']);
	$_POST['extension'] = str_replace($badchr, '', $_POST['extension']);
	$_POST['autorenfiletype'] = str_replace($badchr, '', $_POST['autorenfiletype']);
	$_POST['ip_list'] = str_replace($badchr, '', $_POST['ip_list']);
	
	$_POST['workstart'] = str_replace($badtime, '', $_POST['workstart']);
	$_POST['workend'] = str_replace($badtime, '', $_POST['workend']);
	
	$_POST['list_allow_ip'] = str_replace($badip, '', $_POST['list_allow_ip']);
	$_POST['list_baned_ip'] = str_replace($badip, '', $_POST['list_baned_ip']);	
	
	$_POST['allow_CID'] = str_replace($badip, '', strtoupper($_POST['allow_CID']));
	$_POST['baned_CID'] = str_replace($badip, '', strtoupper($_POST['baned_CID']));

	
	// Use premiX acc
	if($_POST['usepcc']=='on')
	{
	   if($_POST["rs_com_premix"]=='on'){
		// lets count the RS acc 
		$idx_mrsacc = (isset($_COOKIE['rs_com_premix'])?($_COOKIE['rs_com_premix']):"null"); // if this is set, assumed user insert new acc

		$arr_acc = array();		
		if($idx_mrsacc=="null"){ // nothin new rs acc, grab the from config
		  if(isset($premium_acc["rs_com"][0]["user"])){
		    $arr_acc = $premium_acc["rs_com"];
		    $multiacc = true;
		  }else{
		    $arr_acc[0]["user"] = $premium_acc["rs_com"]["user"];
		    $arr_acc[0]["pass"] = $premium_acc["rs_com"]["pass"];
		    $multiacc = false;
		  }
		}else{ // we got new acc here
		 if(is_numeric($idx_mrsacc)){
		  for($i=0; $i<=$idx_mrsacc; $i++){
			$dumy_arr = array();
			$_POST["rpl$i"] = str_replace($badpetik, '', $_POST["rpl$i"]);
			$_POST["rppl$i"] = str_replace($badpetik, '', $_POST["rppl$i"]);
			if($_POST["rpl$i"]!='' && $_POST["rppl$i"]!=''){
			 $dumy_arr["user"] = $_POST["rpl$i"];			
			 $dumy_arr["pass"] = $_POST["rppl$i"];
			 $arr_acc[] = $dumy_arr;
			}
			$multiacc=(count($arr_acc)>1?true:false);
		  }
		 }
		} //-- counting done

		
		 // we do list array vector here...
		if(!$multiacc){ // single acc
		  $vector_sacc = split(":", $arr_acc[0]["user"]. ":" .$arr_acc[0]["pass"]);
		}else{
		  $vector_macc = array();
		  for($i=0; $i<count($arr_acc); $i++){
			$strROTr = ($arr_acc[$i]["user"] . ":" .$arr_acc[$i]["pass"]);
			$vector_macc[$i] = split(":", $strROTr);
		  }
		} //--end list array vector
		 
		setcookie("rs_com_premix", "", time() - 3600, "/");
		
	   } //--end if-rs_premix
		
		#Filter level 1; do replace or give default value if possible
		// Do filter field acc, out of rs acc
		$iserr = false;
		fieldfilter($ar_chkbox_acc[1],'rplde','rpplde',$badpetik); 
		fieldfilter($ar_chkbox_acc[2],'usrmu','passmu',$badpetik); 
		fieldfilter($ar_chkbox_acc[3],'usrmega','passmega',$badpetik); 
		fieldfilter($ar_chkbox_acc[4],'usrnet','passnet',$badpetik); 
		fieldfilter($ar_chkbox_acc[5],'usrgz','passgz',$badpetik); 
		fieldfilter($ar_chkbox_acc[6],'usrsho','passsho',$badpetik); 
		fieldfilter($ar_chkbox_acc[7],'usrvbul','passvbul',$badpetik); 
		fieldfilter($ar_chkbox_acc[8],'usrupto','passupto',$badpetik); 
		fieldfilter($ar_chkbox_acc[9],'usreasy','passeasy',$badpetik); 
		fieldfilter($ar_chkbox_acc[10],'usrdepos','passdepos',$badpetik); 
		fieldfilter($ar_chkbox_acc[11],'usrhotfile','passhotfile',$badpetik);
		fieldfilter($ar_chkbox_acc[12],'usruploading','passuploading',$badpetik); 
		fieldfilter($ar_chkbox_acc[13],'usrfilefactory','passfilefactory',$badpetik); 
		fieldfilter($ar_chkbox_acc[14],'usrifile','passifile',$badpetik); 

	} //-- end $_POST['usepcc']
	else{
	// Why we should clean up this POST ? i dunno.
	  for($i=0; $i<count($ar_chkbox_acc);$i++){
		$_POST[$ar_chkbox_acc[$i]] = '';
	  }
	  for($i=0; $i<count($ar_chkbox_acc);$i++){
		$_POST[$ar_chkbox_acc[$i]] = '';
	  }
	  
	}
	
	// If Upload Acc checked
	if($_POST['useulcc']=='on'){
		fieldfilter($ar_chkbox_up_acc[0],'usrmf_up','passmf_up',$badpetik,"acc_UL"); 
		fieldfilter($ar_chkbox_up_acc[1],'usrrs_com_coll_up','passrs_com_coll_up',$badpetik,"acc_UL");
		fieldfilter($ar_chkbox_up_acc[2],'usrrs_com_pre_up','passusrrs_com_pre_up',$badpetik,"acc_UL");
		fieldfilter($ar_chkbox_up_acc[3],'usrmu_pre_up','passmu_pre_up',$badpetik,"acc_UL");
		fieldfilter($ar_chkbox_up_acc[4],'usrhf_com_up','passhf_com_up',$badpetik,"acc_UL");
		fieldfilter($ar_chkbox_up_acc[5],'usreasysh_mem_up','passeasysh_mem_up',$badpetik,"acc_UL");
		fieldfilter($ar_chkbox_up_acc[6],'usrdeposit_up_up','passdeposit_up_up',$badpetik,"acc_UL");
		fieldfilter($ar_chkbox_up_acc[7],'usruploading_up_up','passuploading_up_up',$badpetik,"acc_UL");
	}
	
	
	singlefilter('muhash_premix','muhashcookie','',$badpetik);
	singlefilter('imghack_premix','usrimghk','passimghk',$badpetik); 

	$rnfltp = (empty($_POST['autorenfiletype'])?'':fieldfilter('','autorenfiletype','',$badchr));
	
	/*
	//Check Numeric Field, if invalid or blank, give its default value.
	  >> filterNumericFld($postname, $title, $defaultval) {}
	*/
	$_POST['max_free_dl'] = filterNumericFld('max_free_dl', 'Leeching per ip', 5);
	$_POST['auto_delet'] = filterNumericFld('auto_delet', 'Auto deleting file', 0);	
	$_POST['limitsize'] = filterNumericFld('limitsize', 'Maximum file size', 0);
	$_POST['lowlimitsize'] = filterNumericFld('lowlimitsize', 'Minimum file size', 0);	
	$_POST['cpulimit'] = filterNumericFld('cpulimit', 'CPU Limit', 0);	
	$_POST['serverjob'] = filterNumericFld('serverjob', 'Server Jobs', 5);	
	$_POST['storage_limit'] = filterNumericFld('storage_limit', 'Storage Server File', 0);	
	$_POST['pointbooster'] = filterNumericFld('pointbooster', 'Point booster', 0);	
	$_POST['iframealocate'] = filterNumericFld('iframealocate', 'IFrame Allocation', 0);	
	$_POST['auto_close'] = filterNumericFld('auto_close', 'Auto-close popup', 300);	
	$_POST['max_trafic'] = filterNumericFld('max_trafic', 'Maximum Traffic', 0);
	$_POST['day_reset_trafic'] = filterNumericFld('day_reset_trafic', 'Auto Reset Traffic', 0);
	$_POST['audl'] = filterNumericFld('audl', 'Maximum Links Audl', 0);
	$_POST['auul'] = filterNumericFld('auul', 'Maximum Links Auul', 0);
	$_POST['dl_limit_max'] = filterNumericFld('dl_limit_max', 'Max Download per IP', 5);
	$_POST['dl_per_ip'] = filterNumericFld('dl_per_ip', 'Delay Download per IP', 3600);
	
	$_POST['language'] = (is_numeric($_POST['language'])?(($_POST['language']>=0)&&($_POST['language']<count($arlang))?$_POST['language']:0):0);
	
	 
	 
	 function reListIP($ar_ipnya){
		$c_ip = '';
		if(count($ar_ipnya)>0){			
			foreach($ar_ipnya as $key => $value)
			{ $c_ip.= "'".trim($ar_ipnya[$key])."'"; $c_ip.= ", "; }
			$c_ip = substr($c_ip, 0, strlen($c_ip)-2);
		  }
		return $c_ip;
	 }
	 if(!empty($_POST['ip_list'])) {$list_ip_arr = explode(",", $_POST['ip_list']);}
	 $cain_ip = reListIP($list_ip_arr);
	  
	  
	 //===================
	 
	$upcc = '';
	 if(empty($_POST['rs_com_premix'])&&empty($_POST['rs_de_premix'])&&empty($_POST['megaupload_premix'])&&empty($_POST['megashare_premix'])&&empty($_POST['netload_premix'])&&empty($_POST['gigasize_premix'])&&empty($_POST['share_online_premix'])&&empty($_POST['vBulletin_acc'])&&empty($_POST['uploaded_to_premix'])&&empty($_POST['easyshare_premix'])&&empty($_POST['depositfiles_premix'])&&empty($_POST['hotfile_com_premix'])&&empty($_POST['uploading_premix'])&&empty($_POST['filefactory_premix'])&&empty($_POST['ifile_it_premix'])&&empty($_POST['muhash_premix'])&&empty($_POST['imghack_premix']))
	  { $upcc='//'; }
	  
	  
	$useulcc = '';
	if($_POST['useulcc']=='on'){
	 if(empty($_POST['mediafire_up_upload'])&&empty($_POST['rs_com_coll_upload'])&&empty($_POST['rs_com_pre_upload'])&&empty($_POST['mu_pre_upload'])&&empty($_POST['hf_com_upload'])&&empty($_POST['easysh_mem_upload'])&&empty($_POST['uploading_up_upload'])&&empty($_POST['deposit_up_upload']))
	  { $useulcc='//'; }
	}else
	  { $useulcc='//';}
	

	if($_POST['show_column_sfile']=='on'){
	  $_POST['col_md5'] = 'on';
	  $_POST['col_downloadlink'] = 'on';
	  $_POST['col_comments'] = 'on';
	  $_POST['col_date'] = 'on';
	  $_POST['col_ip'] = 'on';
	}

	
	//==========================
	// Build template config.php
	$_config = "<?php\nif (!defined('RAPIDLEECH'))\n  {require_once(\"404.php\");exit;}\n\n";
	
    $_config .= "//for security reason we add this value so you just can set manualy directly to this file\n".
				"//whether you want disable/enable advanced editor in xpanel.\n";
    $_config .= "\$disableadvanceeditor = ".($disableadvanceeditor==true ? 'true' : 'false').";\n\n";
	
    $_config .= "//set index filename, needed in audl incase you have a different name instead index.php\n";
    $_config .= "\$index_file = '".(($index_file != "" && strstr($index_file, ".")) ? "{$index_file}" : "index.php")."';\n\n";

    $_config .= "###-LOGIN-CONFIG\n";
    $_config .= "\$allowcpanel =  true; // WARNING, set this to FALSE will Disable access to xpanel.\n";
	$_config .= "\$loginCp = array('".$_POST['newusercp']."'=>'".$_POST['newpascp']."'); // user=>pass\n\n";
    $_config .= "\$login = ".($_POST['login']=='on'?'true':'false').";\n\$users = array('".$_POST['user']."' => '".$_POST['pass']."');  // user=>pass\n\n";
	
    $_config .= "###-PREMIUM-CONFIG\n\n";

	$_config .= $upcc."\$premium_acc = array();\n";
	$_config .= ($_POST['rs_com_premix']=='on' ? ($multiacc ? '//' : $upcc):'//')."\$premium_acc[\"rs_com\"] = array('user' => '".$vector_sacc[0]."', 'pass' => '".$vector_sacc[1]."');\n\n";
	
	if(!$multiacc){
	 $_config .= ($_POST['rs_com_premix']=='on'?(!$multiacc ? '/*' : $upcc):'/*');
	 $_config .= "// below here is the way u insert multiple acc for rapidshare.com\n";
	 $_config .= "\$premium_acc[\"rs_com\"] = array(\n";
	 $_config .= "  array('user' => 'user1', 'pass' => 'pass1'),\n";
	 $_config .= "  array('user' => 'user2', 'pass' => 'pass2'),\n";
	 $_config .= "  array('user' => 'user3', 'pass' => 'pass3')\n";
	 $_config .= ");  //foobar - rs_com- multi; */\n";
	}else{
	 $_config .= "\$premium_acc[\"rs_com\"] = array(";
	 $vektor = "\n";
	 for($i=0; $i<count($vector_macc); $i++){
	    $vektor .= "  array('user' => '".$vector_macc[$i][0]."', 'pass' => '".$vector_macc[$i][1]."')";
		if(($i+1)!=count($vector_macc)){$vektor .= ",\n";}
	 }
	 $vektor .= "\n";
	 $_config .= $vektor.");  //foobar - rs_com- multi; */\n";
	}
	
	$_config .= ($_POST['rs_de_premix']=='on'?$upcc:'//')."\$premium_acc[\"rs_de\"] = array('user' => '".trim($_POST['rplde'])."', 'pass' => '".trim($_POST['rpplde'])."');\n";
	$_config .= ($_POST['megaupload_premix']=='on'?$upcc:'//')."\$premium_acc[\"megaupload\"] = array('user' => '".trim($_POST['usrmu'])."', 'pass' => '".trim($_POST['passmu'])."');\n";
	$_config .= ($_POST['megashare_premix']=='on'?$upcc:'//')."\$premium_acc[\"megashare\"] = array('user' => '".trim($_POST['usrmega'])."', 'pass' => '".trim($_POST['passmega'])."');\n";
	$_config .= ($_POST['netload_premix']=='on'?$upcc:'//')."\$premium_acc[\"netload\"] = array('user' => '".trim($_POST['usrnet'])."', 'pass' => '".trim($_POST['passnet'])."');\n";
	$_config .= ($_POST['gigasize_premix']=='on'?$upcc:'//')."\$premium_acc[\"gigasize\"] = array('user' => '".trim($_POST['usrgz'])."', 'pass' => '".trim($_POST['passgz'])."');\n";
	$_config .= ($_POST['share_online_premix']=='on'?$upcc:'//')."\$premium_acc[\"share_online\"] = array('user' => '".trim($_POST['usrsho'])."', 'pass' => '".trim($_POST['passsho'])."');\n";
	$_config .= ($_POST['vBulletin_acc_premix']=='on'?$upcc:'//')."\$premium_acc[\"vBulletin_acc\"] = array('user' => '".trim($_POST['usrvbul'])."', 'pass' => '".trim($_POST['passvbul'])."');\n";
	$_config .= ($_POST['uploaded_to_premix']=='on'?$upcc:'//')."\$premium_acc[\"uploaded_to\"] = array('user' => '".trim($_POST['usrupto'])."', 'pass' => '".trim($_POST['passupto'])."');\n";
	$_config .= ($_POST['easyshare_premix']=='on'?$upcc:'//')."\$premium_acc[\"easyshare\"] = array('user' => '".trim($_POST['usreasy'])."', 'pass' => '".trim($_POST['passeasy'])."');\n";
	$_config .= ($_POST['depositfiles_premix']=='on'?$upcc:'//')."\$premium_acc[\"depositfiles\"] = array('user' => '".trim($_POST['usrdepos'])."', 'pass' => '".trim($_POST['passdepos'])."');\n";
	$_config .= ($_POST['hotfile_com_premix']=='on'?$upcc:'//')."\$premium_acc[\"hotfile_com\"] = array('user' => '".trim($_POST['usrhotfile'])."', 'pass' => '".trim($_POST['passhotfile'])."');\n";
	$_config .= ($_POST['uploading_premix']=='on'?$upcc:'//')."\$premium_acc[\"uploading\"] = array('user' => '".trim($_POST['usruploading'])."', 'pass' => '".trim($_POST['passuploading'])."');\n";
	$_config .= ($_POST['filefactory_premix']=='on'?$upcc:'//')."\$premium_acc[\"filefactory\"] = array('user' => '".trim($_POST['usrfilefactory'])."', 'pass' => '".trim($_POST['passfilefactory'])."');\n";
	$_config .= ($_POST['ifile_it_premix']=='on'?$upcc:'//')."\$premium_acc[\"ifile_it\"] = array('user' => '".trim($_POST['usrifile'])."', 'pass' => '".trim($_POST['passifile'])."');\n\n";
	
	$_config .= "#Auto Download Premium Account #\n";
	$_config .= ($_POST['autodlpremix']=='on' ? $upcc:'//')."\$premium_acc_audl = ".($_POST['autodlpremix']=='on'?'true':'false').";\n\n";
	
	$_config .= "#Megaupload cookie #\n";
	$_config .= ($_POST['muhash_premix']=='on'?'':'//')."\$mu_cookie_user_value = '".addslashes(trim($_POST['muhashcookie']))."';\n\n";
	$_config .= "###Imageshack Torrent Account ###\n";
	$_config .= ($_POST['imghack_premix']=='on'?'':'//')."\$imageshack_acc = array('user' => '".trim($_POST['usrimghk'])."', 'pass' => '".trim($_POST['passimghk'])."');\n\n";
	
	$_config .= "#Upload Account #\n";
	$_config .= ($_POST['mediafire_up_upload']=='on'?$useulcc:'//')."\$upload_acc[\"mediafire_up\"] = array('user' => '".trim($_POST['usrmf_up'])."', 'pass' => '".trim($_POST['passmf_up'])."');\n";
	$_config .= ($_POST['rs_com_coll_upload']=='on'?$useulcc:'//')."\$upload_acc[\"rs_com_coll\"] = array('user' => '".trim($_POST['usrrs_com_coll_up'])."', 'pass' => '".trim($_POST['passrs_com_coll_up'])."');\n";
	$_config .= ($_POST['rs_com_pre_upload']=='on'?$useulcc:'//')."\$upload_acc[\"rs_com_pre\"] = array('user' => '".trim($_POST['usrrs_com_pre_up'])."', 'pass' => '".trim($_POST['passrs_com_pre_up'])."');\n";
	$_config .= ($_POST['mu_pre_upload']=='on'?$useulcc:'//')."\$upload_acc[\"mu_pre\"] = array('user' => '".trim($_POST['usrmu_pre_up'])."', 'pass' => '".trim($_POST['passmu_pre_up'])."');\n";
	$_config .= ($_POST['hf_com_upload']=='on'?$useulcc:'//')."\$upload_acc[\"hf_com\"] = array('user' => '".trim($_POST['usrhf_com_up'])."', 'pass' => '".trim($_POST['passhf_com_up'])."');\n";
	$_config .= ($_POST['easysh_mem_upload']=='on'?$useulcc:'//')."\$upload_acc[\"easysh_mem\"] = array('user' => '".trim($_POST['usreasysh_mem_up'])."', 'pass' => '".trim($_POST['passeasysh_mem_up'])."');\n";
	$_config .= ($_POST['deposit_up_upload']=='on'?$useulcc:'//')."\$upload_acc[\"deposit_up\"] = array('user' => '".trim($_POST['usrdeposit_up_up'])."', 'pass' => '".trim($_POST['passdeposit_up_up'])."');\n";
	$_config .= ($_POST['uploading_up_upload']=='on'?$useulcc:'//')."\$upload_acc[\"uploading_up\"] = array('user' => '".trim($_POST['usruploading_up_up'])."', 'pass' => '".trim($_POST['passuploading_up_up'])."');\n\n";

	$_config .= "#-LIMITATION-CONFIG\n";
	$_config .= "\$limitbyip = ".($_POST['limitbyip']=='on'?'true':'false')."; //limit RL by IP; dont forget chmod 777 to folder tmp\n";
	$_config .= "\$maximum_free_downloads = ".$_POST['max_free_dl']."; //how many times it'll granted?\n";
	$_config .= "\$delay_per_ip = ".$_POST['delayip']."; //(in hour) recycle allowable IP\n\n";
	
	$_config .= "\$auto_del_time = ".$_POST['auto_delet']."; //(in hour) delete leeched file\n";
	$_config .= "\$limitsize = ".$_POST['limitsize']."; //(in MB) limit upper-bound of filesize\n";
	$_config .= "\$lowlimitsize = ".$_POST['lowlimitsize']."; //(in MB) limit lower-bound of filesize\n\n";
	
	$_config .= "\$storage_limit = ".$_POST['storage_limit']."; //(in MB) limit your server files storage.  1 * 1024 = 1 GB; \n\n";
	
	//$_config .= "\$cpulimit = ".$_POST['cpulimit'].";//CPU load percen to limit leeching \n\n";
	
	$_config .= "\$downloadLimitbyip = ".($_POST['downloadLimitbyip']=='on'?'true':'false')."; //limit RL by IP\n";
	$_config .= "\$downloadsPerIP = ".$_POST['dl_limit_max']."; //how many times it'll granted?\n";
	$_config .= "\$downloadDelayPerIP = ".$_POST['dl_per_ip']."; //(in second)\n\n";
	
	$_config .= "\$audl = ".$_POST['audl']."; //how many link allow to auto-download work ?\n";
	$_config .= "\$auul = ".$_POST['auul']."; //how many file allow to auto-upload work ?\n\n";

	$_config .= "\$limitbytraffic = ".($_POST['limitbytraffic']=='on'?'true':'false')."; //limit RL by Traffic Flow\n";
	$_config .= "\$max_trafic = ".$_POST['max_trafic']."; // (in MB). eg: 1 GB = 1 * (1024) MB\n";
	$_config .= "\$date_trafic = '".$_POST['YetAnotherDate']."'; // (d-day traffic quota expired). date in dd/mm/YYYY \n";
	//$_config .= "\$day_reset_trafic = ".$_POST['day_reset_trafic']."; // auto reset traffic. in days. \n\n";	
	//$date_now = date("d-m-Y", strtotime("now"));
	$_config .= "\$day_reset_trafic = ".$_POST['day_reset_trafic']."; // auto reset traffic. delay in days; \n\n";
	
	$_config .= "\$limited_edition = ".($_POST['limited_edition']=='on'?'true':'false')."; // limit authorization RL by ip address (banned and allowd list) \n";
	$_config .= "\$list_allow_ip = '".$_POST['list_allow_ip']."'; // White list ip. eg. 111.111.111.111, 255.*.*.*  //--never blank this shit if you set \$limited_edition = true \n";
	$_config .= "\$list_baned_ip = '".$_POST['list_baned_ip']."'; // blacklist ip, u think so?!. eg. 111.111.111.111, 222.*.*.*, 212.212.212.* \n\n";
	
	$_config .= "\$limited_area = ".($_POST['limited_area']=='on'?'true':'false')."; // limit authorization RL by ID Country\n";
	$_config .= "\$allow_CID = '".$_POST['allow_CID']."'; // White list Country ID, blank mean all country is allowed. eg. ID, MY; // allow only Indonesia And Malaysia\n";
	$_config .= "\$baned_CID = '".$_POST['baned_CID']."'; // Blacklist Country ID, blank mean no country is banned. eg. US; // all country from US being banned.\n\n";
	
	$_config .= "\$limit_timework = ".($_POST['limit_timework']=='on'?'true':'false')."; // limit your RL by time. Client's Current time depend on (Server timezone)\n";
	$_config .= "\$workstart = '".$_POST['workstart']."'; // Your RL start to work\n";
	$_config .= "\$workend = '".$_POST['workend']."'; // Your RL end to work\n\n";
	
	$_config .= "\$limit_cpuload = ".($_POST['cpulimit'] > 0 ? 'true':'false')."; // limit cpu load and task server job\n";
	$_config .= "  \$ServerLoadAllowed = ".$_POST['cpulimit']."; // Maximum server load allowed; Disable = 0\n";
	$_config .= "  \$CpuLoadFormat = 'load'; // Value = 'load' for load format; 'percent' for percent format\n";
	$_config .= "  ((!function_exists('exec')&&!function_exists('shell_exec'))?\$CpuLoadFormat = 'percent':null); //CpuLoadFormat must be in percent mode if required functions is not exists\n";
	$_config .= "  \$passthru_allowed = (!function_exists('passthru')?false:true) OR FALSE; // Does your host allows passthru?\n";
	$_config .= "  \$MaxServerJob = ".$_POST['serverjob']."; // Maximum server jobs at a time; Disable = 0\n\n";
		
	
	$_config .= "###-FILE CONFIG\n";
	$_config .= "\$download_dir = \"".$_POST['dir']."\"; // Your downloaded files are saved here;\n";
	$_config .= "\$download_dir_is_changeable = ".($_POST['dirchange']=='on'?'true':'false')."; // To allow users to change the download dir ( index page )\n\n";
	$_config .= "\$maysaveto = ".($_POST['dirchangeaudl']=='on'?'true':'false')."; // To allow users to change downloaded files to saved ( in audl )\n\n";

	$_config .= "\$forbidden_filetypes = array('.htaccess', '.htpasswd', '.php', '.php3', '.php4', '.php5', '.phtml', '.asp', '.aspx', '.cgi');\n";
	$_config .= "\$rename_these_filetypes_to = '.".$rnfltp."';\n";
	$_config .= "\$check_these_before_unzipping = true;\n\n";

	$_config .= "\$disable_action = ".($_POST['disall']=='on'?'true':'false')."; //no action menus\n";
	
	#$_config .= "\$disable_deleting = ".$disabl_delet."; //restrict action delete file in RL\n";
	#$_config .= "\$miniact = ".$disabl_ren."; //allow only action rename & upload\n\n";
	
	$_config .= "\$disable_to = array( // disabled action files properties\n";
	$_config .= " 'act_upload' => ".($_POST['act_upload']=='on'?'true':'false').",\n";
	$_config .= " 'act_ftp' => ".($_POST['act_ftp']=='on'?'true':'false').",\n";
	$_config .= " 'act_mail' => ".($_POST['act_mail']=='on'?'true':'false').",\n";
	$_config .= " 'act_boxes' => ".($_POST['act_boxes']=='on'?'true':'false').",\n";
	$_config .= " 'act_split' => ".($_POST['act_split']=='on'?'true':'false').",\n";
	$_config .= " 'act_merge' => ".($_POST['act_merge']=='on'?'true':'false').",\n";
	$_config .= " 'act_md5' => ".($_POST['act_md5']=='on'?'true':'false').",\n";
	$_config .= " 'act_pack' => ".($_POST['act_pack']=='on'?'true':'false').",\n";
	$_config .= " 'act_zip' => ".($_POST['act_zip']=='on'?'true':'false').",\n";
	$_config .= " 'act_unzip' => ".($_POST['act_unzip']=='on'?'true':'false').",\n";
	$_config .= " 'act_rename' => ".($_POST['act_rename']=='on'?'true':'false').",\n";
	$_config .= " 'act_mrename' => ".($_POST['act_mrename']=='on'?'true':'false').",\n";
	$_config .= " 'act_delete' => ".($_POST['act_delete']=='on'?'true':'false').",\n";
	$_config .= ");\n\n";
	
	$_config .= "\$show_column_sfile = array(  // property server_file's column\n";
	$_config .= " 'md5' => ".($_POST['col_md5']=='on'?'true':'false').",\n";
	$_config .= " 'downloadlink' => ".($_POST['col_downloadlink']=='on'?'true':'false').",\n";
	$_config .= " 'comments' => ".($_POST['col_comments']=='on'?'true':'false').",\n";
	$_config .= " 'date' => ".($_POST['col_date']=='on'?'true':'false').",\n";
	$_config .= " 'age' => ".($_POST['col_age']=='on'?'true':'false').",\n";
	$_config .= " 'ip' => ".($_POST['col_ip']=='on'?'true':'false').",\n";
	$_config .= ");\n\n";
	
	$_config .= "\$show_all = ".($_POST['showallfiles']=='on'?'true':'false').";\n";
	$_config .= "\$bw_save = ".($_POST['bandwidthsave']=='on'?'true':'false')."; \n\n";
	
	$_config .= "\$deletelink_in_lynx = ".($_POST['deletelink_in_lynx']=='on'?'true':'false')."; \n\n";
	
	$_config .= "#Auto-Rename #\n";
	$_config .= "\$rename_prefix = '".$_POST['prefix']."';//eg. mysite => mysite_file_name.rar\n";
	$_config .= "\$rename_suffix = '".$_POST['suffix']."';//eg. mysite => file_name_mysite.rar\n";
	$_config .= "\$add_ext_5city = '".$_POST['extension']."';//eg. ccpb => file_name.rar.ccpb\n\n";
	
	$_config .= "//define writable files\n";
	$_config .= "define('LOG_DIR', 'logfile/');\n";
	$_config .= "define('FILES_LST', LOG_DIR.'Rx08_2208081410_f1L3Z.lst');\n";
	$_config .= "define('IP_L33CH_L0G', LOG_DIR.'Rx08_2208081410_IP_L33CH_L0G.lst');\n";
	$_config .= "define('VISITOR_LST', LOG_DIR.'Rx08_2208081410_v1zit0r.lst');\n";
	$_config .= "define('TRAFFIC_LST', LOG_DIR.'Rx08_2208081410_tR4fic.txt');\n";
	$_config .= "define('LOG_PHP', LOG_DIR.'Rx08_2208081410_d4Mn.log.php');\n";
	$_config .= "define('LASTLOG_LST', LOG_DIR.'Rx08_2208081410_d4Mn_Last.txt');\n";
	$_config .= "define('MYUPLOAD_LST', LOG_DIR.'myuploads.txt');\n";
	$_config .= "define('ONGOING_LST', LOG_DIR.'Rx08_2208081410_ongoingTask.lst');\n\n";
	
	
	$_config .= "###-VIEW-CONFIG\n";
	$_config .= "\$navi_left = array(\n";
	$_config .= " 'showcpanel' => ".($_POST['showcpanel']=='on'?'true':'false').",\n";
	$_config .= " 'showplugins' => ".($_POST['showplugins']=='on'?'true':'false').",\n";
	$_config .= " 'showaudl' => ".($_POST['showaudl']=='on'?'true':'false').",\n";
	$_config .= " 'showauul' => ".($_POST['showauul']=='on'?'true':'false').",\n";
	$_config .= " 'showlynx' => ".($_POST['showlynx']=='on'?'true':'false').",\n";
	$_config .= " 'server_info' => ".($_POST['showinfoserver']=='on'?'true':'false').",\n";
	$_config .= ");\n\n";
	
	
	$_config .= " \$forbid_audl = ".($_POST['forbid_audl']=='on'?'true':'false').";\n";
	$_config .= " \$forbid_auul = ".($_POST['forbid_auul']=='on'?'true':'false').";\n";
	$_config .= " \$forbid_lynx = ".($_POST['forbid_lynx']=='on'?'true':'false').";\n";
	$_config .= " \$cpuUsageNFO = ".($_POST['cpuUsageNFO']=='on'?'true':'false').";\n";
	
	$_config .= "\$OnlineVisitor = ".($_POST['onlinevisit']=='on'?'true':'false')."; //Show Online Visitor\n\n";

	$_config .= "\$premix_status = ".($_POST['showpremixstatus']=='on'?'true':'false')."; // enable acc premix status\n";
	$_config .= "\$ip_premixstat_list = array(".$cain_ip."); // trusted ip, can view detil acc.\n\n";

	$_config .= "###-MISC-CONFIG\n";
	$_config .= "\$no_cache = ".($_POST['nocache']=='on'?'true':'false').";\n";
	$_config .= "\$redir = ".($_POST['redirrect']=='on'?'true':'false').";\n\n";
	
	
	$_config .= "\$disable_ajax = ".($_POST['disable_ajax']=='on'?'true':'false')."; //switch to old method, No-Ajax in Serverfiles\n";
	$_config .= "\$disable_ajaxren = ".($_POST['disable_ajaxren']=='on'?'true':'false')."; //toogle ajax instant rename. require: rsajax.js; rsajax_ren.js\n";
	
	$_config .= "\$logact = ".($_POST['logact']=='on'?'true':'false')."; //do log-activity of the users\n";
	$_config .= "\$alternatefree = ".($_POST['alternatefree']=='on'?'true':'false')."; //Auto switch freedownload if premium not good\n";
	
	$_config .= "\$showautoclose= ".($_POST['auto_cl']=='on'?'true':'false').";//autoclose popup when leeching in audl\n";
	$_config .= "\$timeautoclose= ".$_POST['auto_close'].";\n";
	$_config .= "\$autochecklink = ".($_POST['autochecklink']=='on'?'true':'false')."; // Auto check submited link in audl\n\n";
	
	$_config .= "\$iframealocate = ".$_POST['iframealocate'].";//how many iframe to allocate in audl for manual method.\n";
	$_config .= "\$pointboost = ".$_POST['pointbooster'].";//boost your RS-Point with this feature!!\n";
	$_config .= "\$autosubmit = true;\n\n";
	
	$_config .= "\$timezone = ".$_POST['timezone']."; // set Timezone. It is GMT+(7) for Indonesia.\n";
	$_config .= "\$lang = '".$arlang[$_POST['language']]."'; // set Language.\n\n";
	
	//$_config .= "\$arCSS = getArrayfromfile(IMAGE_DIR, 'style_sujancok_', '.css');\n";
	$_config .= "\$csstype = '".$arCSS[$_POST['theme']]."'; // set Theme to your RL. eg. _default\n";
	$_config .= "?>";
	
	
	
	$close_config_page = false;
	write_config_file($fileconfig,$_config);

	
	if($close_config_page) {
	    if($saved_success){
 		   
		  foreach($ar_chkbox_othr_acc as $key => $_opremix){
		    $arfield["oth"].= "'".$_opremix."'".($key != (count($ar_chkbox_othr_acc)-1) ? ", ":"");
		  }
		  foreach($ar_chkbox_acc as $key => $_premix){ 
		   $arfield["acc"].= "'".$_premix."'".($key != (count($ar_chkbox_acc)-1) ? ", ":"");
		  }
		  foreach($ar_chkbox_up_acc as $key => $_upremix){ 
		   $arfield["up"].= "'".$_upremix."'".($key != (count($ar_chkbox_up_acc)-1) ? ", ":"");
		  }
		  $buff_cleancookie_js = "
<script type=\"text/javascript\">
 var tmpCk = new Object();
  tmpCk['custom_rlck'] = Array('rl_ajax');
  tmpCk['arfield_oth'] = Array(". $arfield["oth"] .");
  tmpCk['arfield_acc'] = Array(". $arfield["acc"] .");
  tmpCk['arfield_up'] = Array(". $arfield["up"] .");
 for(var subCok in tmpCk){
  ckRec = tmpCk[subCok];
  for(var i=0; i<ckRec.length; i++){deleteCookie(ckRec[i], dirpath, '');} 
 }
</script>";  
		echo $buff_cleancookie_js;
		}
		print "\r\n<hr width=\"800\">\r\n<small>".VERSION."</small>\r\n</div>\r\n</td></tr>\r\n</table>\r\n</center>\r\n</body>\r\n</html><br>";
		exit;
	}
   }
}
?>


<?php

	include_once("xpanelform.tpl.php");
}
echo "<hr width=\"800\">\r\n<small>".VERSION."</small>";
?>

</td>
</tr>
</table>
<br><img src="http://notepad-plus.sourceforge.net/commun/images/npp.animated.logo.gif" alt="notepad++">
</center>
</body>
</html>
<br>