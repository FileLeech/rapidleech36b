<?php
$time = explode (' ', microtime()); 
$begintime = $time[1] + $time[0]; unset($time);

error_reporting(0);
//error_reporting(E_ALL); 
@ini_set('display_errors', true); 

@set_time_limit(0);
ini_alter("memory_limit", "1024M");
@ob_end_clean();
@ob_implicit_flush(TRUE);
ignore_user_abort(1);
clearstatcache();
error_reporting(6135);

$nn = "\r\n";
$fromaddr = "RapidLeech";
$dev_name = 'eqbal';
$rev_num = '36B.Rv7.3';
$RL_VER = 'Rx08.ii'.$rev_num;

$PHP_SELF = !isset($PHP_SELF) ? $_SERVER["PHP_SELF"] : $PHP_SELF;
define('RAPIDLEECH', 'yes');
define('ROOT_DIR', realpath("./"));
define('PATH_SPLITTER', (strstr(ROOT_DIR, "\\") ? "\\" : "/"));
define('HOST_DIR', 'pluginz/');
define('MISC_DIR', 'misc/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', './');
define('LANG_DIR', 'languages/');

define('BUILD', '23082007');
define('CREDITS', '<a href="http://www.rapidleech.com/" style="text-decoration:none"><b>RL</b></a>&nbsp;<b class="footer1">PlugMod rev. '.$rev_num.'</b> <span class="footer2">by '.$dev_name.'</span><br><small class="footer2">Credits to Pramode &amp; Checkmate &amp; Kloon. Mod by: MsNeil &amp; Idoenk</small>');
define('VERSION', '<small class="footer3"><span>[ olc | ccpb | kaskus ]</span><BR>[ Shout: Viant77, Jujuan, Huka, Limpy ]</small>');

require_once(CONFIG_DIR."config.php");
require_once(CLASS_DIR."other.php");

// Load languages set for index
$vpage = "index";
require_once(LANG_DIR."language.$lang.inc.php");

//$charSet = (isset($charSet) && !empty($charSet) ? $charSet : 'charset=ISO-8859-1');
$charSet = 'UTF-8';


// $download_dir should always end with '/'
if (substr($download_dir,-1) != '/') $download_dir .= '/';
define('DOWNLOAD_DIR', (substr($download_dir, 0, 6) == "ftp://" ? '' : $download_dir));

define('TPL_PATH', 'tpl'. '/' . $csstype . '/');
define('IMAGE_DIR', MISC_DIR . TPL_PATH);

//Checking DOWNLOAD_DIR
if(file_exists(DOWNLOAD_DIR)) {
	if (!is__writable(DOWNLOAD_DIR)) {
	  html_error(DOWNLOAD_DIR." is not writable, please make sure it is chmod to 777");
	}
 }
 else{
 html_error("Fatal Error, [DIR] ".DOWNLOAD_DIR." is not exist.<br>Check your config, check value of \$download_dir");
}



// Check configs/files.lst is not writable,
$czFlst = 0;
if(file_exists(FILES_LST)) {
  if (!is__writable(FILES_LST)) {
	html_error(FILES_LST." is not writable, please make sure it is chmod to 777");
  }
  $czFlst = @filesize(FILES_LST);
 }
 else{
  html_error(FILES_LST." is not exist.");
 }


if ($no_cache) {
  header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
 }

if(!defined('CRLF')) define('CRLF',"\r\n");
if(!defined("FTP_AUTOASCII")) define("FTP_AUTOASCII", -1);
if(!defined("FTP_BINARY")) define("FTP_BINARY", 1);
if(!defined("FTP_ASCII")) define("FTP_ASCII", 0);
if(!defined('FTP_FORCE')) define('FTP_FORCE', TRUE);
define('FTP_OS_Unix','u');
define('FTP_OS_Windows','w');
define('FTP_OS_Mac','m');
$server = getServerPlatf();

//session_start();
$ipmu = get_real_ip();

if($limitbyip){
  ipcounter(); // check and count real ip downloader
  $ada_acc = false;
  if(isset($premium_acc)){
   foreach($premium_acc as $dhost => $val){ if($val){$ada_acc=true; break;}   }
  }
  if(isset($mu_cookie_user_value)){$ada_acc=($mu_cookie_user_value!='');}
  if(isset($hf_cookie_auth_value)){$ada_acc=($hf_cookie_auth_value!='');}
  if(isset($rs_cookie_enc_value)){$ada_acc=($rs_cookie_enc_value!='');}
  if(isset($imageshack_acc)){$ada_acc=($imageshack_acc["user"]!='' && $imageshack_acc["pass"]!='');}
  // check if max download reach
  $trheute = $heute+1;
  if($trheute > $maximum_free_downloads){
   $msgNya = $txt['premix_used_1']."&nbsp;<b style='color:red;'>$maximum_free_downloads</b>&nbsp;".$txt['premix_used_2']."&nbsp;<b style='color:#00FF33'>$delay_per_ip</b>&nbsp;".$txt['premix_used_3']."&nbsp;";
  }
}

// Init call create_list
if( $czFlst==0 && (!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]!=1) )){
  _create_list(false, true); // medic mode, to force list in downloaded files
  updateCozEmpty(FILES_LST); // relist file    
}

    $is_exceed = $is_expired = false;
	if($limitbytraffic){
		$cur_trf = get_traffic(TRAFFIC_LST);		
		if($day_reset_trafic > 0){
		 $trafic_start_date = autoreset_traffic($day_reset_trafic, $cur_trf);
		}
		$max_trf = ($max_trafic * 1024 * 1024);
		$is_exceed = ($cur_trf[0] - $max_trf) >= 0;
		$date=explode("/", $date_trafic); 
		$delay = (date("d-M-Y",mktime(0,0,0,$date[1],$date[0],$date[2])));
		$is_expired = (((int)strtotime("now"))-((int)strtotime($delay)))>0;
	}

	if($limit_timework){  $is_worktime = cek_worktime($workstart, $workend); }else{$is_worktime = true;}
	$serverfiles = calcUsedSpace(); $storage_exceed = false;

	if($storage_limit>0){if($serverfiles>($storage_limit*1024*1024)){$storage_exceed=true;}}
	
	//check cpuload if necessary
	$alert_sloadhigh=false;
	if($limit_cpuload && file_exists(CLASS_DIR.'sload.php')){
	  $nocpus = 0; $loadcpu =''; 
	  if (!isset($_GET["link"]) && !isset($_POST["link"])) { $main=1; }
	  $srvload = "<p id=\"srvload\" style=\"color:red; position:absolute;\"><code>LimitServerLoad Mode: <b>ON</b></code></p>\n";
	  require_once(CLASS_DIR.'sload.php'); // this will return result of $alert_sloadhigh	  
	}

	$FilesDownloadedPerTime = 0; $is_dllimit = false;
	if($downloadLimitbyip){
	 _create_lists();
	 foreach ( $list as $k => $file ) {
	  if ($file ["ip"] == $ipmu) {
		if (time () - $downloadDelayPerIP < $file ['date']) {
		  $FilesDownloadedPerTime++;
		}
	  }
	 }
	 $is_dllimit = ($FilesDownloadedPerTime >= $downloadsPerIP);
	}
	
	// Build Message for any limitation occurs
	$msg=null;
	if($is_expired || $is_exceed || !$is_worktime || $storage_exceed || $alert_sloadhigh || $is_dllimit) {
		$limitmsg=""; 
		if($is_expired || $is_exceed){$limitmsg = $txt['quote_alert']; $msg.=$txt['quote_status'];}  // limitbytraffic alerted
		if($is_exceed){$msg.=($msg!=""?"<br>":"").$txt['exceed_alert'];}
		if($is_expired){$msg.=($msg!=""?"<br>":"").$txt['expired_since'].date("d-M-Y",mktime(0,0,0,$date[1],$date[0],$date[2]));}
		
		if($alert_sloadhigh){$msg.=($msg!=""?"<br>":$txt['cpuload_sloadhigh']); }  // limit_cpuload alerted
		if($is_dllimit){$msg.=($msg!=""?"<br>":"You have exceeded your download limit, you can only download ". $downloadsPerIP ." files in " . sec1time ( $downloadDelayPerIP ). "." ); }  // downloadLimitbyip alerted
		if(!$is_worktime){if(!empty($limitmsg)){$limitmsg.="<br>";}$limitmsg.=$gtxt['worktime_alert'];}  // limit_timework alerted
		if($storage_exceed){if(!empty($limitmsg)){$limitmsg.="<br>";}$limitmsg.=$txt['maxstorage_alert_1'];
							if($auto_del_time>0){$limitmsg.=$txt['maxstorage_alert_2'];}}  //  storage_limit alerted	
							
		$flag = true;  // Tell you there's limitation alert
	}
	
	if(isset($_SERVER['HTTP_REFERER'])){
	 $arRef = explode("http://", $_SERVER['HTTP_REFERER']);
	 $arRef = explode("/", $arRef[1]);
	 $ref=(trim($arRef[0])!==$_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_REFERER']:'');
	}else{
	 $ref='';
	}
	if($logact){
	// Ditulis oleh Lewi Verdatama untuk OLC
	// Digunakan untuk mencatat detail pengunjung seperti : Waktu, IP, Refferer, dan Browser
	$salt_ip = "i!X9@_=>~|:/'?[}"; //  to store a salted md5 for latest ip visitor
	$curvisit = md5($ipmu.$salt_ip);
	$latestvisit = false;

	$latestvisit = chklatesvisitor($curvisit);
	if(!$latestvisit){
	  saveLogsys($curvisit);
	  }
	}

//Cek ip yg banned || is it listed as authorized ip || check country limit
if($limited_edition || $limited_area)
{
  $dlimitation = array($limited_edition, $limited_area);
  require_once("limit_district.php");
}

$cnt_deleted = ($auto_del_time > 0 ? purge_files($auto_del_time):0);
//($downloadLimitbyip ? purge_files_ip($downloadDelayPerIP):'');
($OnlineVisitor ? UpdateOnline():'');
register_shutdown_function("pause_download");


if ($login===true){
if(!isset($_SERVER['PHP_AUTH_USER']) || ($loggeduser = logged_user($users)) === false)
	{
		header('WWW-Authenticate: Basic realm="Rx08"');
		header('HTTP/1.0 401 Unauthorized');
		exit("<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\"><style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body>$nn<h1>$RL_VER: NuLL</h1>$nn</body>$nn</html>");
	}
}



//************************
// MAIN CODE ...
//************************

foreach($_POST as $key => $value)
  {
  $_GET[$key] = $value;
  }

if(!$_COOKIE)
  {
      if(strstr($_SERVER["HTTP_COOKIE"], ";"))
        {
            foreach(explode("; ", $_SERVER["HTTP_COOKIE"]) as $key => $value)
              {
                  list($var, $val) = explode("=", $value);
                  $_COOKIE[$var] = $val;
              }
        }
      else
        {
          list($var, $val) = @explode("=", $_SERVER["HTTP_COOKIE"]);
          $_COOKIE[$var] = $val;
        }
  }

require_once(CLASS_DIR."cookie.php");

if (!@file_exists(HOST_DIR."hosts.php"))
	{
	create_hosts_file("hosts.php");
	}

if (!empty($_GET["image"]))
	{
	require_once(CLASS_DIR."http.php");
	require_once(CLASS_DIR."image.php");
	exit;
	}

if(isset($_GET["useproxy"]) && (!$_GET["proxy"] || !strstr($_GET["proxy"], ":")))
      {
        html_error($txt['wrong_proxy']);
      }
      	else
      {
      	if (isset($_GET["pauth"]))
      		{
      			$pauth = $_GET["pauth"];
      		}
      			else
      		{
      			$pauth = (isset($_GET["proxyuser"]) && isset($_GET["proxypass"])) ? base64_encode($_GET["proxyuser"].":".$_GET["proxypass"]) : "";
      		}
      }

if (!isset($_GET["path"]) || $download_dir_is_changeable == false)
  {
	if(!isset($_GET["host"]))
	  {
		$_GET["path"] = (substr($download_dir, 0, 6) != "ftp://") ? realpath(DOWNLOAD_DIR) : $download_dir;
	  }
	else
	  {
		$_GET["saveto"] = (substr($download_dir, 0, 6) != "ftp://") ? realpath(DOWNLOAD_DIR) : $download_dir;
	  }
  }
  if(!isset($_GET["filename"]) || !$_GET["host"] || !$_GET["path"])
  {
      //require "host.php";
	require_once(HOST_DIR."hosts.php");
  
   if(!isset($_POST["link"])){
     if(isset($_GET["idx"])) //if link was sent from audl
	 {
	   $LINK = (isset($_GET["link"]) ? utf8_strrev(base64_decode($_GET["link"])) : "");	   
	 }else{
	   $LINK = (isset($_GET["link"]) ? $_GET["link"]:"");
	 }
   }else{
     $LINK = (isset($_POST["link"])?$_POST["link"]:"");
   }
	
    $LINK = urlcleaner(trim(urldecode($LINK))); // urldecode, trim, strip * in link	

    if(!$LINK){  //Initiate main php needs
	   $ch_curl = (extension_loaded("curl") ? 1 : 0);
	   
	   _create_list();
	   
	   // This prep embeded acc if there's any
	   if(isset($premium_acc)){
	     $acc_txt = '';		
		 $spacer = '<div class="dv_embed_acc" style="padding-top:.1em;"></div>';
		 $ar_host_acc = array(
		  "rs_com"=>"rapidshare.com",
		  "rs_de"=>"rapidshare.de",
		  "megaupload"=>"megaupload.com",
		  "megashare"=>"megashare.com",
		  "netload"=>"netload.in",
		  "gigasize"=>"gigasize.com",
		  "share_online"=>"share_online.com",
		  "uploaded_to"=>"uploaded.to",
		  "easyshare"=>"easy-share.com",
		  "depositfiles"=>"depositfiles.com",
		  "hotfile_com"=>"hotfile.com",
		  "uploading"=>"uploading.com",
		  "filefactory"=>"filefactory.com",
		  "ifile_it"=>"ifile.it",
		 );
		 foreach($premium_acc as $host_acc => $val){
		   $acc_txt.= (isset($premium_acc[$host_acc]["user"]) ? ($premium_acc[$host_acc]["user"]!=''&&$premium_acc[$host_acc]["pass"]!='' ? $ar_host_acc[$host_acc] . $spacer : '') : $ar_host_acc[$host_acc]." multi acc" . $spacer);
		 }
		 if(isset($mu_cookie_user_value)){
		   $acc_txt.= ($mu_cookie_user_value!='' ? "megaupload.com cookie" . $spacer:'');
		 }
		 if(isset($hf_cookie_auth_value)){
		   $acc_txt.= ($hf_cookie_auth_value!='' ? "hotfile.com cookie" . $spacer:'');
		 }
		 if(isset($rs_cookie_enc_value)){
		   $acc_txt.= ($rs_cookie_enc_value!='' ? "rapidshare.com cookie" . $spacer:'');
		 }
		 if(isset($imageshack_acc)){
		   $acc_txt.= ($imageshack_acc["user"]!='' && $imageshack_acc["pass"]!='' ? "Imageshack Account" . $spacer : '');
		 }
		 $ar_rscom = (isset($premium_acc["rs_com"]) ? $premium_acc["rs_com"] : false);		
	   } //-end embed acc need
	    else{
	     $ar_rscom = false;
	   }
	   // check there's exist rs acc,. single or multi
	   if($ar_rscom){
	    $exist_accrs = (
		   isset($ar_rscom["user"]) ? ($ar_rscom["user"]!='' && $ar_rscom["pass"]!='') :
		  (isset($ar_rscom[0]["user"]) ? ($ar_rscom[0]["user"]!='' && $ar_rscom[0]["pass"]!='') : false)
		);
	   }else{
	    $exist_accrs = false;
	   }
	   // dummy like sess-id  -_-'
       $usrajxnuid = str_replace("=","",base64_encode(str_replace(".","",$ipmu).':'.'4jaX'));

	   
	   $userck_std_mode = (isset($_GET["ajax"]) && $_GET["ajax"]=="on" && isset($_GET["ausv"]) && $_GET["ausv"]==$usrajxnuid) || (isset($_COOKIE["rl_ajax"]) );  
	   
	   if($userck_std_mode ){
	    $disable_ajax = (!isset($_GET["ajax"]) || !isset($_GET["ausv"]) || ( isset($_GET["ausv"]) && $_GET["ausv"]!=$usrajxnuid) ? !isset($_COOKIE["rl_ajax"]) || (isset($_COOKIE["rl_ajax"]) && $_COOKIE["rl_ajax"]!=1) : (!isset($_GET["ajax"]) || (isset($_GET["ajax"]) && $_GET["ajax"]!="on")));
		$userck_std_mode = !$disable_ajax;
	   }
	   
       $ada_rsajax_js = @file_exists("rsajax.js");
       $ajax_serverfiles = ($ada_rsajax_js && !$disable_ajax);
       $ajax_rename = (($ada_rsajax_js && !$disable_ajaxren) ? true : false);
	   
	   $userck_std_mode = (!$disable_ajax && $ada_rsajax_js);
	   $disable_ajax = !$userck_std_mode;
	   
	   $showAccRsStatus = ($ch_curl == 1 && $exist_accrs && $premix_status && $ada_rsajax_js);
	   
	   //$jQ_google_api_file = "http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js";
	   $jQ_google_api_file = "misc/jquery.min.js";
	   $jQ_online = @fopen($jQ_google_api_file, "r");
	   
       require_once(CLASS_DIR."main.php");
       exit;
	   
	   
    }elseif($flag===true){
        html_error('Limitation alerted, can not use RL! '.$msg, 1);
    }

    if(!empty($_GET["saveto"]) && !$_GET["path"])
      {
      html_error($txt['path_not_defined']);
      }
      
    if (empty($_GET["useproxy"]))
    	{
      $_GET["proxy"] = "";
    	}
    	
    if(!empty($_GET["domail"]) && !checkmail($_GET["email"]))
      {
      html_error("You didn't enter a valid e-mail address");
	    if($_GET["split"] && !is_numeric($_GET["partSize"]))
	      {
	      html_error($txt['size_not_true']);
	      }
      }
	
    $Referer = ($_GET["referer"] ? trim(urldecode($_GET["referer"])) : $LINK);
    $Url = parse_url($LINK);
	if($Url['scheme'] != 'http' && $Url['scheme'] != 'https' && $Url['scheme'] != 'ftp'){
		$LINK = utf8_strrev(base64_decode($LINK));
		$LINK = urlcleaner($LINK);
		$Url = parse_url($LINK);
		if($Url['scheme'] != 'http' && $Url['scheme'] != 'https' && $Url['scheme'] != 'ftp'){
		html_error($txt['url_unknown'].", <span style=color:#000>".$txt['url_only_use']." <span style=color:#05F>http</span> ".$txt['url_or']." <span style=color:#05F>https</span> ".$txt['url_or']." <span style=color:#05F>ftp</span> Protocol</span>");
		}
	}
	
	if($pointboost>0){
		$pos = str_replace(".", "", strrchr($LINK, "."));
		$numidx = (is_numeric($pos)?$pos:-1);
	}

	if($_GET["dis_plug"] != "on")
	{
	//check Domain-Host
		if(isset($_GET["vBulletin_plug"]))
		  {
			print "<html>$nn<head>$nn<title>".$txt['downloading']." $LINK</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\">$nn";
			print "<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body>$nn<br>$nn";
			require_once(CLASS_DIR."http.php");
			require_once(HOST_DIR."vBulletin_plug.php");
			exit;
		  }
		else
		  {
			foreach ($host as $site => $file)
				{
				if (preg_match("/^(.+\.)?".$site."$/i", $Url["host"]))
					{
				print "<html>$nn<head>$nn<title>".$txt['prep_dl']." $LINK</title>$nn<link rel=\"shortcut icon\" type=\"image/gif\" href=\"".IMAGE_DIR."rsload_2.gif\">$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\">$nn";
				print "<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body>$nn<center><img src='".IMAGE_DIR."rl_lgo.png'>";
					require_once(CLASS_DIR."http.php");
					require_once(HOST_DIR.$file);
					exit;
					
					}
				}
		  }
	}
		
		print "<html>$nn<head>$nn<title>".$txt['leeching']." $LINK</title>$nn<link rel=\"shortcut icon\" type=\"image/gif\" href=\"".IMAGE_DIR."rsload_2.gif\">$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\">$nn</head>$nn<body>$nn";
    
		$Url = parse_url($LINK);
		$FileName = !$FileName ? basename($Url["path"]) : $FileName;
		/* $mydomain = $_SERVER['SERVER_NAME'];
		$myip = $_SERVER['SERVER_ADDR'];
		if($bw_antileech && preg_match("/($mydomain|$myip)/i", $Url["host"]))
			{
				html_error("You are not allowed to leech from <font color=black>".$mydomain." (".$myip.")</font>");
			} */
             
		$auth = ($Url["user"] && $Url["pass"]) ? "&auth=".base64_encode($Url["user"].":".$Url["pass"]) : "";
		
		insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
  }
else
  {
    ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; <?php echo $charSet;?>">
<link rel="shortcut icon" type="image/gif" href="<?php echo IMAGE_DIR.'rsload_2.gif?'.rand(11,9999);?>" />
<title>nYedoTz...</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR; ?>style_sujancok<?php print $csstype;?>.css");
-->
</style>
</head>
<body>
<center>
<?php
/*
// Deprecated of useless and unworth feature for some cases might fail this status.
if($MaxServerJob > 0){
  if($MaxServerJob <= ongoingGet()){
	html_error("Server is only allowed to download $MaxServerJob downloads at a time.");
  }
}
*/

if($limitbyip){
 if($ada_acc){
  $trheute=$heute+1;
  if($trheute>$maximum_free_downloads){
	html_error($msgNya);
  }
 }
}

// Deprecated of useless and unworth feature for some cases might fail this status.
//	ongoingAdd ();
    do
    {
	    list($_GET["filename"],$tmp) = explode('?',urldecode(trim($_GET["filename"])));
	    $_GET["saveto"] = urldecode(trim($_GET["saveto"]));
	    $_GET["host"] = urldecode(trim($_GET["host"]));
		$_GET["path"] = urldecode(trim($_GET["path"]));
	    $_GET["port"] = $_GET["port"] ? urldecode(trim($_GET["port"])) : 80;
	    $_GET["referer"] = $_GET["referer"] ? urldecode(trim($_GET["referer"])) : 0;
	    $_GET["link"] = urldecode(trim($_GET["link"]));
		
	    $_GET["post"] = $_GET["post"] ? unserialize(stripslashes(urldecode(trim($_GET["post"])))) : 0;
	    $_GET["cookie"] = $_GET["cookie"] ? urldecode(trim($_GET["cookie"])) : 0;
	    //$resume_from = $_GET["resume"] ? intval(urldecode(trim($_GET["resume"]))) : 0;
	    //if ($_GET["resume"]) {unset($_GET["resume"]);}

        $redirectto = "";
	    
	    $pauth = urldecode(trim($_GET["pauth"]));
	    $auth = urldecode(trim($_GET["auth"]));

	    if($_GET["auth"]){
	      $AUTH["use"] = TRUE;
	      $AUTH["str"] = $_GET["auth"];
	    }
	    else {
	      unset($AUTH);
	    }

	    $ftp = parse_url($_GET["link"]);
	    
	    $IS_FTP = $ftp["scheme"] == "ftp" ? TRUE : FALSE;
	    $AUTH["ftp"] = array("login"    => $ftp["user"] ? $ftp["user"] : "anonymous",
	                         "password" => $ftp["pass"] ? $ftp["pass"] : "anonymous@leechget.com");
	                         
	    $pathWithName = $_GET["saveto"].PATH_SPLITTER.$_GET["filename"];
	    while(stristr($pathWithName, "\\\\"))
        {
        $pathWithName = str_replace("\\\\", "\\", $pathWithName);				
        }

	    list($pathWithName,$tmp) = explode('?',$pathWithName);

	    if ($ftp["scheme"] == "ftp" && !$_GET["proxy"])
	    	{
	    	require_once(CLASS_DIR."ftp.php");
	    	$file = getftpurl($_GET["host"], $ftp["port"] ? $ftp["port"] : 21, $_GET["path"], &$pathWithName);
	    	}
	    else
	    	{
	    	require_once(CLASS_DIR."http.php");
			$_GET["force_name"] ? $force_name = urldecode($_GET["force_name"]) : '';
			$file = geturl($_GET["host"], $_GET["port"], $_GET["path"], $_GET["referer"], $_GET["cookie"], $_GET["post"], &$pathWithName, $_GET["proxy"], $pauth, $auth, $ftp["scheme"]);
	    	}		
		if($redir && $lastError && stristr($lastError,$txt['_error']." ".$txt['_redirect_to']." ["))
			{
				$redirectto = trim(cut_str($lastError,$txt['_error']." ".$txt['_redirect_to']." [","]"));
				print $txt['_redirecting_to']." <b>$redirectto</b> ... <br>$nn";
				$_GET["link"] = $redirectto;
				$purl = parse_url($redirectto);
			    list($_GET["filename"],$tmp) = explode('?',basename($redirectto));
				$_GET["host"] = $purl["host"];
    			$_GET["path"] = $purl["path"].($purl["query"] ? "?".$purl["query"] : "");
    			$lastError = "";
			}
	    
	} while ($redirectto && !$lastError);
	
// Deprecated of useless and unworth feature for some cases might fail this status.
//	ongoingRemove ();
	
	if($lastError){
        html_error($lastError, 0);
    }elseif($file["bytesReceived"] == $file["bytesTotal"] || $file["size"] == "Unknown"){
        $inCurrDir = stristr(dirname($pathWithName), ROOT_DIR) ? TRUE : FALSE;
        if($inCurrDir)
          {
            $Path = parse_url($PHP_SELF);
            $Path = substr($Path["path"], 0, strlen($Path["path"]) - strlen(strrchr($Path["path"], "/")));
          }else{
		    $cl_Path = relink_path(DOWNLOAD_DIR, $PHP_SELF);
		  }
        print "<script>pr(100, '".$file["size"]."', '".$file["speed"]."')</script>";
        print "File <b>".($inCurrDir ? "<a href=\"".$Path."/".substr(dirname($pathWithName), strlen(ROOT_DIR) + 1)."/".basename($file["file"])."\">" : "<a href=\"".$cl_Path["root"]."/".$cl_Path["download"].basename($file["file"])."\">").basename($file["file"])."</a>"."</b> (<b>".$file["size"]."</b>) ".$txt['_saved']."<br>Time: <b>".$file["time"]."</b> | ".$txt['_avg_spd']." <b>".$file["speed"]." KB/s</b><br>";
		
		if($inCurrDir){
		 $unix_zone = filectime(substr(dirname($pathWithName), strlen(ROOT_DIR) + 1)."/".basename($file["file"]));
		}else{
		 $unix_zone = filectime(DOWNLOAD_DIR.basename($file["file"]));
		}
		$unix_zone = ( $unix_zone - date("Z") + (3600 * $timezone));
		$file["date"] = $unix_zone;
		$result = write_file(FILES_LST, serialize(array("name" => $file["file"], "size" => str_replace(" ", "&nbsp;",$file["size"]), "date" => $file["date"], "age" => $gtxt["less_a_minute"], "link" => $_GET["link"],"comment" => str_replace("\n", "\\n", str_replace("\r", "\\r", $_GET["comment"])), "ip" => $ipmu))."\r\n", 0);
		
		$result2 = write_file(IP_L33CH_L0G, serialize(array("name" => $file["file"],"date" => $file["date"],"ip" => $ipmu))."\r\n", 0);
		
		
		//$file ["date"] = time ();
		//$file ["date"] = ($inCurrDir ? filectime(substr(dirname($pathWithName), strlen(ROOT_DIR) + 1)."/".basename($file["file"])) : filectime(DOWNLOAD_DIR.basename($file["file"])));
		
		if (! $result && !$result2)
          {
          print $txt['error_upd_list']."<br>";
          }
		  
		if($limitbytraffic) {
		 if($max_trafic>0) {
			$fn_trafic = TRAFFIC_LST;
			if(@file_exists($fn_trafic)) {
			  $ar_trafic = get_traffic($fn_trafic);
			  if(is_numeric($ar_trafic[0])) {
				$add_trafic = ($ar_trafic[0] + $file["bytesReceived"]);
			  }
			  else {
				$add_trafic = $file["bytesReceived"];
			  }
			  if(!write_traffic($fn_trafic, $add_trafic . ":" . (isset($trafic_start_date) ? $trafic_start_date : (strtotime("now")+(3600 * $timezone)) ) )) {
				  print $txt['error_upd_trf_list']."<br>";
			  }
			}
		  }
		}


        if($_GET["email"])
          {
            require_once(CLASS_DIR."mail.php");
            $_GET["partSize"] = (isset($_GET["partSize"]) ? $_GET["partSize"] * 1024 * 1024 : FALSE);
            if(xmail($fromaddr, $_GET["email"], "File ".basename($file["file"]), "File: ".basename($file["file"])."\r\n"."Link: ".$_GET["link"].($_GET["comment"]? "\r\n"."Comments: ".str_replace("\\r\\n", "\r\n", $_GET["comment"]) : ""), $pathWithName, $_GET["partSize"], $_GET["method"]))
              {
              print "<script>mail('".$txt['mail_file_sent']."<b>".$_GET["email"]."</b>.', '".basename($file["file"])."');</script>";
              }
            else
              {
              print "<div class='acthistory_result'>".$txt['mail_error_send']."</div>";
              }
          }

		// restrict loop if this index called by audl.php; (empty($_GET["idx"]))
		if($pointboost>0 && empty($_GET["idx"]))
		{
			// i'd rather not delete current downloaded file
			if(!defined("DOWNLOADED_FILE")){define('DOWNLOADED_FILE', DOWNLOAD_DIR.basename($file["file"]));}
			@write_file(DOWNLOADED_FILE, " ");
			if($numidx < $pointboost-1) 
			{
			  // generate reload form (link, secondcounterdown, auth, indexnumber)
			  echo genReload($_GET["link"], 3, $auth, $numidx);
			}
		}
		else
		{   // generate delete link
			$rnd = rand(11,99);
			 echo '<p>Delete Link: <a href="del.php?d='.str_replace("=","", rotN(base64_encode($file["date"].':'.'4puZ'), $rnd))."-".$rnd.'">Delete</a>';
			 echo '<br/>'.$txt['delete_link_notice'].'<br/></p>';		
		}
		print "<br><a id='tdone' href=\"".$PHP_SELF."\">[&nbsp;<b><span id='txtdone'>".$gtxt['back_main']." ]</b></span></a><br/>";
      }
    else
      {
	    unlink($pathWithName);
        print "Connection lost<br><a href=\"javascript:location.reload();\">".$txt['_reload']."</a>";		
		print '<script>setTimeout("location.reload()",1000);</script>';
      }
	//check whether this index called from audl.php
	$audlparam = (isset($_GET["idx"])) ? $_GET["idx"] : '';
	if($audlparam!=''){
		 $audlparam = explode('|',$audlparam);
		 $audlparam[0] = ($audlparam[0]!=''?true:false);
		 if($audlparam[1]==''){$audlparam[1] = 'none';}
		 echo autoNext($audlparam[0], true, $audlparam[1]);
	}
    ?>
</center>
</body>
</html>
    <?php

  }
?>