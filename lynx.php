<?php
define('RAPIDLEECH', 'yes');

$time = explode (' ', microtime()); 
$begintime = $time[1] + $time[0]; unset($time);

error_reporting(0);
//error_reporting(E_ALL); 
//@ini_set('display_errors', true); 

define('MISC_DIR', 'misc/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', 'configs/');
define('LANG_DIR', 'languages/');
clearstatcache();
$PHP_SELF = !isset($PHP_SELF) ? $_SERVER["PHP_SELF"] : $PHP_SELF;

error_reporting(6135);
$nn = "\r\n";
$rev_num = '36B.Rv7.4';
$RL_VER = 'Rx08.ii'.$rev_num;
define('VERSION', "[ TuxiNuX::TimSukses ][ ccpb::kaskus ]");

require_once(CONFIG_DIR."config.php");
require_once(CLASS_DIR."other.php");

// Load languages set for lynx
$vpage = "lynx";
require_once(LANG_DIR."language.$lang.inc.php");

$charSet = (isset($charSet) && !empty($charSet) ? $charSet : 'charset=UTF-8');

define('DOWNLOAD_DIR', (substr($download_dir, 0, 6) == "ftp://" ? '' : $download_dir));
define('TPL_PATH', 'tpl'. '/' . $csstype . '/');
define('IMAGE_DIR', MISC_DIR . TPL_PATH);

//$jQ_filename = "http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js";
$jQ_filename = MISC_DIR."jquery.min.js";
$jQ_online = fopen($jQ_filename, "r");
$server = getServerPlatf();

#=====================

//Cek ip yg banned || is it listed as authorized ip || check country limit
if($limited_edition || $limited_area)
{
  $dlimitation = array($limited_edition, $limited_area);
  require_once("limit_district.php");
}

if(!$forbid_lynx){
 if ($login===true){
 if(!isset($_SERVER['PHP_AUTH_USER']) || ($loggeduser = logged_user($users)) === false)
	{
		header("WWW-Authenticate: Basic realm=\"Rx08\"");
		header("HTTP/1.0 401 Unauthorized");
		exit("<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\"><style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body>$nn<h1>$RL_VER: NuLL</h1>$nn</body>$nn</html>");
	}
 }
}else {
 echo "<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\">$nn<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style></head>$nn<body>$nn<h1>:: $RL_VER :: <br>Lynx Disabled</h1>$nn</body>$nn</html>";
 exit();
}


$page = 'lynx';
header("Content-type: text/html; $charSet");
?><!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; <?php echo $charSet;?>">
<title>LynX :: <?php echo $RL_VER;?> ::</title>
<link rel="shortcut icon" href="<?php echo IMAGE_DIR.'idmdl.gif?'.rand(11,9999);?>" type="image/gif">
<script type="text/javascript" src="<?php echo MISC_DIR;?>js.php?lynx"></script>
<?php if(file_exists(MISC_DIR."matches.js")){
?>
<script type="text/javascript" src="<?php echo MISC_DIR;?>matches.js"></script>
<?php } ?>

<?php if($jQ_online){
?>
<script type="text/javascript" src="<?php echo $jQ_filename;?>"></script>
<script type="text/javascript" src="<?php echo MISC_DIR;?>jQ_fb.js"></script>
<link type="text/css" media="screen" rel="stylesheet" href="<?php echo MISC_DIR;?>jQ_fb.css"/>
<?php } ?>
<script type="text/javascript">
<!--
function showAll(){
  if(getCookie("showAll") == 1){
    deleteCookie("showAll");
  }else{
    d.cookie = "showAll = 1;";
  }
  location.href = "<?php echo $PHP_SELF."?act=files"; ?>"; 
}
-->
</script>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>style_sujancok<?php print $csstype;?>.css");
-->
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
</style>
</head><body>
<div class="head_container"><center>
<a href="<?php echo $index_file;?>" alt="Rapidleech 2.3"><div class="tdheadolgo">&nbsp;</div></a></center>
</div>
<center>
<?php
//SHOW TIME WORK
$is_worktime = cek_worktime($workstart, $workend);
if(!$is_worktime && $limit_timework){
  $limitmsg="";
  if(!$is_worktime){
    if(!empty($limitmsg)){$limitmsg.="<br>";}$limitmsg.=$gtxt['worktime_alert'];
    echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class=\"warn_alert\">{$limitmsg}</div></div>";
  }
}
else
{

?>
<noscript>
<p><b><?php echo $gtxt['js_disable'];?></b></noscript>

<?php
if(!$jQ_online){
 echo "<div id='jquery_warn'><div class=\"warn_alert\" style=\"width:65%;padding:5 2 2 2;border:1px solid #C7A20C;\"><a href=\"http://jquery.com/\" title=\"jQuery\" style=\"color:#A6FFFF;\" class=\"jquery_fail\">jQuery</a> is not well defined.</div><div class=\"acthistory_result jquery_filename\" style=\"width:65%;\">" . $jQ_filename ."</div></div>";
}
$c = 0; $total_size = 0; $nn = "\n";  $kumulatifsz = true;
 
// true in _create_list mean never count md5_file()
_create_list(true);

if(isset($list["files"]["totalsize"])){
  $total_size = $list["files"]["totalsize"];
  $kumulatifsz = false;
}

if($list)
{
  $morethanone = (count($list)>1);
  if ($show_all === true){
   unset($Path);
  }
?>

<script type="text/javascript">
<?php if($jQ_online){
?>
//=============================================AJAX=============================================
jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      }) 
});
$.facebox.settings.loadingImage = '<?php echo MISC_DIR;?>facebox/loading.gif';
<?php } ?>
//==============================================//==============================================
var dFile = new Object(); var text = "", thead, tfoot;

<?php 

 echo "var _dlpath = '".($download_dir != "" ? "{$download_dir}" : "/")."';{$nn}{$nn}";

 
 foreach($list as $key => $file)
  {
  if(isset($file["name"]) && @file_exists($file["name"])){
	 $c++;
     //$total_size+=filesize($file["name"]);	 
     if($kumulatifsz) $total_size+=getfilesize($file["name"]);
	 echo 'dFile["'.$file["date"].'"] = new Object();'.$nn;
	 echo 'dFile["'.$file["date"].'"]["name"] = "'.xmlentities(basename($file["name"]), false).'"'.$nn;
	 echo 'dFile["'.$file["date"].'"]["size"] = "'.$file["size"].'"'.$nn;
	 echo 'dFile["'.$file["date"].'"]["age"] = "'.$file["age"].'"'.$nn;
	 $rnd = rand(11,99);
	 echo 'dFile["'.$file["date"].'"]["delkey"] = "'.str_replace("=","", rotN(base64_encode($file["date"].':'.'4puZ'), $rnd))."-".$rnd.'"'.$nn;	 	
	}
  } // end loop all files
	  
  echo 'total_size = "'.$total_size.'";'."$nn";
  echo 'c = "'.$c.'";'."$nn";
  $lynx_del = ($deletelink_in_lynx && !$disable_to["act_delete"] && $jQ_online);
?>
   
   thead = "<table id='intbl' cellpadding='1' cellspacing='1' class='sortable' style='display: none;'>";
   thead+= "<tr valign='bottom' align='center' id='filelist_title_head' class='filelist_title'>";
   thead+= "<td><b class='sorttable_nosort'></b></td>";
   thead+= "<td align=right><b>&nbsp;<?php echo $ltxt['_fname'];?> &nbsp;</b></td>";
   thead+= "<td align=left><b>&raquo;&nbsp;|&nbsp;<?php echo $gtxt['tabel_sz'];?>&nbsp;</b></td>";
   thead+= "<td><b>&nbsp;<?php echo $gtxt['tabel_dt'];?></b></td>";
   thead+= "<td><b class='sorttable_nosort'>&nbsp;<?php echo $gtxt['tabel_age'];?></b></td>";
   thead+= "<?php if($lynx_del){?><td><b class='sorttable_nosort'>&nbsp;<?php echo $gtxt['act_del'];?></b></td><?php }?></tr>";
   tfoot = "<tfoot>";
   tfoot+= "<tr class='filelist_title'><td><input type=checkbox id='chksAll' onClick='javascript:sAll(this.checked);'></td>";
   tfoot+= "<td id='totfile'><?php echo 'Total : <b class=\"y\">'.$c.'</b> file(s)';?></td>";
   tfoot+= "<td align='right' id='totsz'><?php echo '<b class=\"y\">'.bytesToKbOrMbOrGb($total_size).'</b>';?> </td>";
   tfoot+= "<td align=right></td><?php  if($lynx_del){?><td></td><?php }?><td></td></tr></tfoot>";
   
   var dtemplate = "<tr id='brs((chkidx))' class='rowlist' onMouseDown='clk(\"chkfL-((chkidx))\")' onMouseOut='if(d.getElementById(\"chkfL-((chkidx))\").checked){this.className=\"rowlist_checked\";}else{this.className=\"rowlist\";}'>";
   dtemplate+= "<td><input type=checkbox id='chkfL-((chkidx))' onClick='clk(\"chkfL-((chkidx))\")'></td>";
   dtemplate+= "<td align=right><span id='fN-((chkidx))'>((filename))</span></td>"; 
   dtemplate+= "<td align=left><a id='fL-((chkidx))' href='((filelink))' title='((filename))'><img src=\'<?php echo IMAGE_DIR;?>idmdl.gif\'></a>((filesize))</td>";
   dtemplate+= "<td align=right>&nbsp;&nbsp;((formatdate))</td>";
   dtemplate+= "<td align=right><small>((fileage))</small></td>";
   
   /*Old Trick(window pop-up):
   <a id='dL-((chkidx))' title='Delete: ((filename))' href='del.php?d=((b64filename))' onclick='opennewwindow(this.id);return false;'>
   */   
   dtemplate+= "<?php  if($lynx_del){?><td align=center><a id='dL-((chkidx))' title='Delete: ((filename))' href='del.php?d=((b64filename))&lineid=((chkidx))' rel='facebox'> <img src='<?php echo IMAGE_DIR;?>rldel.png'></a></td><?php }?>";
   dtemplate+= "</tr>";
/*  chkidx; filename; dlpath; filesize; formatdate; b64filename;  */
</script>
<br>
<?php
}
else
{
	echo "<br><span class='warn_alert' style='padding:0 100px 0 100px;'><b>{$gtxt['tabel_no_file']}</b></span><br>";
}

if($list && $morethanone){
 echo "<a href=\"javascript:void(0);\" onclick=\"flist_match_hide();\">{$gtxt['chk_txt_matches']}</a> | ";
}

if($show_all === true)
  {
?>
<a href="javascript:showAll();"><?php echo $gtxt['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1)
  {
  d.write("<?php echo $gtxt['_downloaded'];?>");
  }
else
  {
  d.write("<?php echo $gtxt['_everything'];?>");
  }
</script></a>
<?php
  }else{
  ?>
<script type="text/javascript"> deleteCookie("showAll");</script>
<?php
  }
?>

&nbsp;<input class="refresh" onclick="location.reload();" alt="Refresh" title="Refresh" type="image" src="<?php echo IMAGE_DIR;?>refresh.png" style="vertical-align:bottom;">
<table id="main_tbl" border="0">
<tr><td style="text-align:center;">
<?php if($list && $morethanone){ ?>
<div id="flist_match_hitems" style="display:none;clear:both;" class="acthistory_prompt">
<input type="text" title="<?php echo $gtxt['chk_txt_matches'];?>" size="20" id="flist_match_search" onkeypress="javascript:if(event.keyCode==13){return flist_match(); }"><input type="image" title="<?php echo $gtxt['go_match'];?>" style="vertical-align:bottom;padding-bottom:1px;" src="misc/btn_search.png" onclick="return flist_match(); return false;">&nbsp;<a href="javascript:void(0);" onclick="flist_match_hide();"><small><?php echo $gtxt['close'];?></small></a>
<div></div><span><input type="checkbox" id="flist_match_ins"><label for="flist_match_ins"><?php echo $gtxt['match_csensitive'];?></label><input type="checkbox" id="flist_match_hide" checked="checked"><label for="flist_match_hide"><?php echo $gtxt['match_hideunmatch'];?></label>
</span></div>
<?php } ?>

</td></tr>
<tr><td>

<table id='tblbaru'><tr><td><div id="dtblbaru"></div></td></tr></table>

</td></tr>
</table>
<script type="text/javascript">var dwindow = '<?php echo '_'.substr(md5(time()),0,7).'_'; ?>'; var Opt = new Array(); Opt = {"nC" : "<?php echo $c;?>", "AdL" : "<?php echo $auto_del_time; ?>", "DelLink" : <?php echo ($lynx_del ? "true" : "false");?>}; trparser();</script>

<table width="60%" align=center cellpadding="0" cellspacing="0">
<tbody id="checknavigat" style="DISPLAY: none;">
<tr id="warn_alert" style="display:none;"><td id="td_warn" align=center></td></tr>
<tr><td align="center">
<label><input type="checkbox" name="wterm" id="wterm" onClick="javascript:GenTag();" checked>&nbsp;<?php echo $ltxt['_term'];?> </label>&nbsp;&nbsp;
<?php if($lynx_del){?>
<label><input type="checkbox" name="dellnk" id="dellnk" onClick="javascript:GenTag();">&nbsp;<?php echo $ltxt['_deletelink'];?></label>&nbsp;&nbsp;
<?php }?>
<input type="checkbox" name="add_comment" id="add_comment" onClick="javascript:GenTag();">&nbsp;<a onClick="javascript:var diq=d.getElementById('add_comment');if(!diq.checked){diq.checked=true;}GenTag();"><u><label for="cmtarea"><?php echo $ltxt['_genlink'];?></label></u></a>
</td></tr>
<tr id="comment" style="DISPLAY: none;"><td align="center"><textarea id="cmtarea" name="comment" rows="5" style="color:#666; width:90%; font-family:verdana,lucida,arial; font-size: 7pt;" readonly="yes" onFocus='highlight(this);'></textarea><br/></td></tr>
</tbody>

<?php }?>
<tr align=center><td id="usage"><br>
<?php
if($auto_del_time>0)
	{
	echo "<span class=\"c\">".$gtxt['_autodel'].":&nbsp;<b class=\"g\">".$auto_del_time."</b>&nbsp;hour".($auto_del_time>1?"s":"")."</span>";
    //auto_del($auto_del_time);
    purge_files($auto_del_time);
	}
if($lowlimitsize>0)
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">".$gtxt['_minfilesize'].":&nbsp;<b class=\"s\">".$lowlimitsize."</b>&nbsp;MB</span>";
	}
if($limitsize>0)
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">".$gtxt['_maxfilesize'].":&nbsp;<b class=\"s\">".$limitsize."</b>&nbsp;MB</span>";
	}
if(!empty($add_ext_5city))
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">".$gtxt['_fakeext'].":&nbsp;<b><a style=\"color:red;\" href=\"javascript:void(0);\" title=\"Auto rename extension with this\">.$add_ext_5city</a></b></span>";
	}
if($limit_timework)
	{
	echo "<br><span class=\"c\">".$gtxt['_timework'].":&nbsp;</span><b class=\"s\">$workstart</b>&nbsp;upto&nbsp;<b class=\"s\">$workend</b>";
	}
?>
<br>

<?php
if($navi_left["server_info"]){
  if(@file_exists(CLASS_DIR."sinfo.php")) {
    require_once(CLASS_DIR."sinfo.php");
	$time = explode(" ", microtime());
	$endtime = $time[1] + $time[0]; unset($time);
    $totaltime = ($endtime - $begintime);
	echo "<div style='padding-left:5px;text-align:left;width:190px;'><small>{$server['property']}<br>Page Load: {$totaltime} sec</small></div>";
  }
}else echo "<hr>";
?> 
 </td>
</tr>
<tr><td><div align="center"><?php print VERSION; ?><hr></div></td>
</tr></table>

<?php
if($list){
?>
<script type="text/javascript">var dtabelbaru = d.getElementById("tblbaru").getElementsByTagName("div")[0]; dtabelbaru.innerHTML = thead + text + tfoot; d.getElementById('intbl').style.display=''; d.getElementById('checknavigat').style.display='';</script> 
<?php }
if(file_exists(MISC_DIR."sorttable.js")){
?><script type="text/javascript" src="<?php echo MISC_DIR;?>sorttable.js"></script>
<?php } ?></body></html><br>