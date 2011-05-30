<?php 
 if(!defined('RAPIDLEECH'))
  { require_once("404.php"); exit; }
  
  $show_w3c_validator = false; // show W3C validator link
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; <?php echo $charSet;?>">
<title>:: <?php echo $RL_VER;?> ::</title>
<link rel="shortcut icon" type="image/gif" href="<?php echo IMAGE_DIR.'ico_home.gif?'.rand(11,9999);?>">
<script type="text/javascript">
<!--
var IMG_DIR_TPL = '<?php print IMAGE_DIR;?>';
-->
</script>
<script type="text/javascript" src="<?php echo MISC_DIR;?>b64.js"></script>
<script type="text/javascript" src="<?php echo MISC_DIR;?>js.php?main"></script>

<?php if($ada_rsajax_js && (!$disable_ajax || $exist_accrs || !$disable_ajaxren)){
?>
<script type="text/javascript" src="rsajax.js"></script>
<?php } ?>
<script type="text/javascript" src="rscheck.js"></script>
<?php if(!$disable_to["act_rename"] && $ajax_rename){
?>
<script type="text/javascript" src="rsajax_ren.js"></script>
<?php } ?>

<?php if(file_exists(MISC_DIR."matches.js")){
?>
<script type="text/javascript" src="<?php echo MISC_DIR;?>matches.js"></script>
<?php } ?>

<?php if($jQ_online){
?>
<script type="text/javascript" src="<?php echo $jQ_google_api_file;?>"></script>
<script type="text/javascript" src="<?php echo MISC_DIR;?>jQ_fb.js"></script>
<link type="text/css" media="screen" rel="stylesheet" href="<?php echo MISC_DIR;?>jQ_fb.css"/>
<script type="text/javascript">
//=============================================AJAX=============================================
function gosetfacebook(){
 jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      })
 });
}
$.facebox.settings.loadingImage = '<?php echo MISC_DIR;?>facebox/loading.gif';
</script>
<?php } ?>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>style_sujancok<?php print $csstype;?>.css");
/* ###-MenuUtama-### */
.menuUtama td{
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>mnuUtama.png);
}
/* ###-LeftMenu-### */
td.left_mnu a div { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>left_mnu.png);
}
div.closer_btn{
 background: no-repeat url(<?php print IMAGE_DIR;?>closer.png);
}
.tdheadolgo h1{ 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
.rename_es, .rename_es_save{
 background: no-repeat url(<?php print MISC_DIR;?>editsave.png);
}
-->
</style>
<script type="text/javascript">
<!--

var ajxtmr;
function stacc(){ document.poiuy.submit("staccounts","accwaiting","accshowresults",""); }
function urlload(){ location.href = "<?php echo $PHP_SELF."?act=files"; ?>"; }
function showAll(){ if(getCookie("showAll") == 1){ deleteCookie("showAll"); }else{ d.cookie = "showAll = 1;"; } urlload();}
function changeStatus(file, size){ d.getElementById("status").innerHTML = '<?php echo $gtxt["_uploading"];?> <b>' + file + '</b>, Size <b>' + size + '</b>...<br>';}
-->
</script>
</head>
<body>
<?php if(isset($srvload)){echo $srvload;}?>

<div class="head_container"><center>
<div class="tdheadolgo"><a href="<?php echo $index_file;?>" title="Rapidleech"><h1></h1></a></div>
</center></div>

<table align="center" style="margin-top:-2px;">
<tbody>
<tr>
<td valign="top">
<table width="100%" >
<tr>
<td valign="top">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="left_mnu">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td>
<?php
if($navi_left["showcpanel"]){
  if(isset($xpanel_filename) && @file_exists($xpanel_filename)){
 ?>
 <a href="<?php echo $xpanel_filename;?>" target="_blank" title="<?php echo $txt['cpanel'];?>"><div id="left_cpanel"></div></a>
 <?php
 }
}

if($navi_left["showplugins"]){
?>
<a href="javascript:void(0)" onclick="ddir=document.getElementById('dir'); if(ddir.style.display!=''){ddir.style.display='';}else{ddir.style.display='none';}"><div id="left_plugin"></div></a>
<div align="center" class="splugins"><?php echo '<b><small>'.count($host).'</small></b> Plugins'; ?></div>
<div id="dir" dir="rtl" align="left" style="overflow-y:scroll; height:150px; padding-left:5px; display:none;"></div>
<script type="text/javascript">
 var arHost = Array(
<?php
ksort($host); $cm = "";
foreach($host as $site => $file)
 { echo "$cm'$site'"; $cm = ", "; }
?>
);
function inserthost(){
  var dtpl = "", d = document;
  for(i=0; i<arHost.length; i++)
   {  dtpl += "<span class=\"c\">" + arHost[i] + "<span><br>";}
  d.getElementById('dir').innerHTML = dtpl;
}
setTimeout("inserthost()", 100);
</script>
<?php
}
if($navi_left["showaudl"]){
?>
<a href="audl.php" target="_blank" title="<?php echo "Auto Download";?>"><div id="left_audl"></div></a>
<?php
}
if($navi_left["showauul"]){
?>
<a href="auul.php" target="_blank" title="<?php echo "AutoUpload";?>"><div id="left_auul"></div></a>
<?php
}
if($navi_left["showlynx"]){
?>
<a href="lynx.php" target="_blank"><img src="<?php echo IMAGE_DIR.'listedfile.gif'?>" alt="List Files"></a>
<?php
}
?>

</td>
</tr></table>
</td>
</tr>
<tr><td>
<div id="left_NFO"><center><img style="padding-top:10px;" src="<?php echo MISC_DIR;?>fbload.gif" border="0"></center></div>
</td></tr>
<tr>
<td height="100%" style="padding:2px;">
<div id="nfoconfig" style="height:120px; display:none;">
</div></td>
</tr>
</table>

</td>
</tr>
</table></td>
<td align="center" valign="top"><table cellpadding="0" cellspacing="0" class="menuUtama" border="0">
<noscript><br><b><?php echo $gtxt['js_disable'];?></b></noscript>

<tbody>
<tr>
<td id="navcell1" align="center" onclick="switchCell(1);">&nbsp;</td>
<td id="navcell2" align="center" onclick="switchCell(2);">&nbsp;</td>
<td id="navcell3" align="center" onclick="switchCell(3);">&nbsp;</td>
<td id="navcell4" align="center" onclick="switchCell(4);">&nbsp;</td>
<td id="navcell5" align="center" onclick="switchCell(5);">&nbsp;</td>
</tr>
</tbody>
</table>

<table id="tb_content">
<tbody>
<tr>
<td align="left">

<?php
if($limitbyip){
  if($ada_acc && ($trheute > $maximum_free_downloads)){
?>
<div align="center" style="font-size:15px;"><br> <?php echo $msgNya;?><div><?php echo $txt['sorry_inc'];?></div><br></div>
<?php
  }
}
?>

	
<table class="tab-content" id="tb1" cellspacing="5" width="100%"<?php if($limitbyip){if($trheute>$maximum_free_downloads){print ' style="display:none;"';}}?>>
<tbody>
<tr>

<?php if($is_expired || $is_exceed || !$is_worktime || $storage_exceed || $alert_sloadhigh || $is_dllimit || ($limitbyip && $ada_acc && ($trheute > $maximum_free_downloads))) {
?>
<td align="left">
<p align="left">
<div style="padding-top:20px;"><div class="warn_alert"><?php echo $limitmsg;?></div></div>
</p><div class="warn_alert" style="height:auto;"><?php echo $msg;?></div>
<div style="padding-bottom:15px;">&nbsp;</div>
</td></tr>
<?php 
} else { // Allow use the RL
?>

<td align="left">
<form action="<?php echo $PHP_SELF; ?>" method="post">
<p align="left"><b><?php echo $txt['link_transload'];?></b><br />&nbsp;<input type="text" name="link" id="link" size="50">
<p align="left"><b><?php echo $txt['referrer'];?></b><br />&nbsp;<input type="text" name="referer" id="referer" size="50">
</td>
<td align="left" valign="top">
<div style="padding-top:25px;"></div><input type="submit" value="<?php echo $txt['_transload'];?>">

<?php 
if(isset($mip_enabled) && $mip_enabled){
  $mip_action = "select";  // select || download
  if(file_exists(CLASS_DIR."mip.php")) @include_once(CLASS_DIR."mip.php");
}
?>

</td>
</tr>
<tr>
<td align="left"><label><input type="checkbox" name="add_comment" id="add_comment" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('comment').style.display=displ;">&nbsp;<?php echo $txt['add_comment'];?></label></td>
</tr>
<tr id="comment" style="DISPLAY: none;">
<td align="left" colspan="2">
<textarea name="comment" class="redtxtarea" rows="4" style="width:370px;"></textarea>
</td>
</tr>
<?php }?>


<tr>
<td colspan="2">
<br><small class='subtitle'><?php echo $txt['user_stats'];?></small>

<?php
echo ($limitbyip && $ada_acc ? "&nbsp;&nbsp;&nbsp;<small>[<b class='r'>".$txt['limit_leech']."</b>]</small>":"")."<hr>";

echo ($OnlineVisitor?'<div>User online: <b class="g">' . GetOnline() . '</b></div>':'');
echo $txt['detect_ip']." <b>$ipmu </b>".($logact?"<small class='y'>[logged]</small>":"");
if($ref!=""){echo "<br>Refferer: <small>" . $ref."</small>";}

if($limitbyip && $ada_acc){
  echo "<br>You have used <b class='c'>$heute</b>&nbsp;out of&nbsp;<b class='c'>$maximum_free_downloads</b> pRemiX per <b class='g'>$delay_per_ip</b> ".$txt['premix_used_3'];
}
?>
</td>
</tr>

<tr>
<td colspan="2">

<div style="height:5px;">&nbsp;</div><small class='subtitle'><?php echo $txt['server_stats'];?></small><hr>
<?php echo $txt['current_storage'];?>&nbsp;<b class="g"><?php echo bytesToKbOrMbOrGb($serverfiles);?></b>&nbsp;&nbsp;&nbsp;
<?php
if($storage_limit>0){
	echo '(Max: <b class="r">'.bytesToKbOrMbOrGb($storage_limit*1024*1024).'</b>)';}
if($limitbytraffic){
	echo "<br>".$txt['current_traffic']." <b class='g'>".bytesToKbOrMbOrGb($cur_trf[0])."</b>&nbsp;of&nbsp;<span class=\"s\" title=\"".$txt['max_traffic']."\"><b>".bytesToKbOrMbOrGb($max_trf)."</b></span> until <small><span class='g'>$delay</span></small>";
	$cur_trf = get_traffic(TRAFFIC_LST);	
	if($day_reset_trafic>0) echo "<br>".$txt['reset_traffic_remain']."&nbsp;<small class=\"s\">".sec2time(timeremain_traffic($day_reset_trafic, $cur_trf[1]))."</small>";	
	}
if($limit_timework)	{
  echo "<br>".$gtxt['_timework'].": <b class=\"s\">$workstart</b>&nbsp;upto&nbsp;<b class=\"s\">$workend</b>";	
}
?>
</td>
</tr>

<?php
if($cnt_deleted>0){
?>
<tr><td colspan="2">
<div style="height:5px;">&nbsp;</div><small class='subtitle'><?php echo $txt['log_act'];?></small>
<div id='act_history' class='acthistory_prompt'>
<b class="g"><?php echo $cnt_deleted . "</b>&nbsp;". $txt["lact_files"] ."&nbsp;". $txt["lact_autodeleted"];?>
</div>
</td></tr>
<?php
}
?>

</tbody>
</table>

<table class="hide-table" id="tb2" cellspacing="5" width="100%">
<tbody>
<tr>
<td align="left">
<table align="left" width="300">
<tr>
<td><input type="checkbox" name="domail" id="domail" onClick="javascript:document.getElementById('emailtd').style.display=document.getElementById('splittd').style.display=this.checked?'':'none';document.getElementById('methodtd').style.display=(document.getElementById('splitchkbox').checked && this.checked)?'':'none';"<?php echo isset($_COOKIE["domail"]) ? " checked" : ""; ?>>&nbsp;<label for="domail"><?php echo $txt['send_email'];?></label>
<table id="emailtd"<?php echo isset($_COOKIE["domail"]) ? "" : " style=\"display: none;\""; ?>><tr><td><?php echo $txt['email'];?>&nbsp;<input type="text" name="email" id="email"<?php echo isset($_COOKIE["email"]) ? " value=\"".(isset($_COOKIE["email"])?$_COOKIE["email"]:"")."\"" : ""; ?>></td></tr></table>
</td>
</tr>
<tr id="splittd"<?php echo isset($_COOKIE["split"]) ? "" : " style=\"display: none;\""; ?>>
<td style="padding-bottom:10px ;">
<input id="splitchkbox" type="checkbox" name="split" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('methodtd').style.display=displ;"<?php echo isset($_COOKIE["split"]) ? " checked" : ""; ?>>&nbsp;<label for="splitchkbox"><?php echo $txt['split_file'];?></label>
<table id="methodtd"<?php echo isset($_COOKIE["split"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<table>
<tr>
<td><?php echo $txt['method'];?>&nbsp;<select name="method"><option value="tc"<?php echo isset($_COOKIE["method"]) && $_COOKIE["method"]== "tc" ? " SELECTED='SELECTED'" : ""; ?>><?php echo $txt['tot_com'];?></option><option value=rfc<?php echo isset($_COOKIE["method"]) && $_COOKIE["method"] == "rfc" ? " SELECTED='SELECTED'" : ""; ?>><?php echo $txt['rfc'];?></option></select></td>
</tr>
<tr>
<td><?php echo $txt['part_size'];?>&nbsp;<input type="text" name="partSize" size="2" value=<?php echo isset($_COOKIE["partSize"]) ? $_COOKIE["partSize"] : 10; ?>>&nbsp;MB</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td><input type="checkbox" id="useproxy" name="useproxy" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('tblproxy').style.display=displ;"<?php echo isset($_COOKIE["useproxy"]) ? " checked" : ""; ?>>&nbsp;<label for="useproxy"><?php echo $gtxt['use_proxy'];?></label>
<table id="tblproxy"<?php echo isset($_COOKIE["useproxy"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<table width="150">
<tr><td><?php echo $gtxt['_proxy'];?>&nbsp;</td><td><input type="text" name="proxy" id="proxy" size="20"<?php echo isset($_COOKIE["proxy"]) ? " value=\"".(isset($_COOKIE["proxy"])?$_COOKIE["proxy"]:"")."\"" : ""; ?>></td></tr>
<tr><td><?php echo $gtxt['_uname'];?>&nbsp;</td><td><input type="text" name="proxyuser" id="proxyuser" size="20" <?php echo isset($_COOKIE["proxyuser"]) ? " value=\"".(isset($_COOKIE["proxyuser"])?$_COOKIE["proxyuser"]:"")."\"" : ""; ?>></td></tr>
<tr><td><?php echo $gtxt['_pass'];?>&nbsp;</td><td><input type="text" name="proxypass" id="proxypass" size="20" <?php echo isset($_COOKIE["proxypass"]) ? " value=\"".(isset($_COOKIE["proxypass"])?$_COOKIE["proxypass"]:"")."\"" : ""; ?>></td></tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td><input type="checkbox" name="premium_acc" id="premium_acc" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('prblock').style.display=displ;"<?php if (isset($premium_acc)) print ' checked'; ?>>&nbsp;<label for="premium_acc"><?php echo $gtxt['use_premix'];?></label>
<table id="prblock" style="display: none;">
<tr><td>
<table width="150">
<tr><td><label for="premium_user"><?php echo $gtxt['_uname'];?></label></td><td><input type="text" name="premium_user" id="premium_user" size="15" onFocus="highlight(this);" value=""></td></tr>
<tr><td><label for="premium_pass"><?php echo $gtxt['_pass'];?></label></td><td><input type="password" name="premium_pass" id="premium_pass" size="15" onFocus="highlight(this);" style="color: #912704 ;" value=""></td></tr>
</table>
</td></tr>
</table>
</td>
</tr>

<?php
if($download_dir_is_changeable){
?>
	<tr>
	<td><input type="checkbox" name="saveto" id="saveto" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('path').style.display=displ;"<?php echo isset($_COOKIE["saveto"]) ? " checked" : ""; ?>>&nbsp;<label for="saveto"><?php echo $gtxt['save_to'];?></label>
	<table id="path"<?php echo isset($_COOKIE["saveto"]) ? "" : " style=\"display: none;\""; ?>>
	<tr><td>
	<?php echo $gtxt['save_path'];?>&nbsp;<input type="text" name="path" size="40" value="<?php echo (isset($_COOKIE["path"]) ? $_COOKIE["path"] : (substr($download_dir, 0, 6) != "ftp://" ? realpath(DOWNLOAD_DIR) : $download_dir)); ?>" <?php echo ($download_dir_is_changeable ? '':'readonly');?>>
	</td></tr>
	</table>
	</td>
	</tr>
<?php
}
?>

<tr>
<td><input type="checkbox" name="savesettings" id="savesettings"<?php echo isset($_COOKIE["savesettings"]) ? " checked" : ""; ?> onClick="javascript:var displ=this.checked?'':'none';document.getElementById('clearsettings').style.display=displ;">&nbsp;<label for="savesettings"><?php echo $txt['save_sett'];?></label>
<table id="clearsettings"<?php echo isset($_COOKIE["savesettings"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<a href="javascript:void(0);" onclick="clearSettings();"><?php echo $txt['clear_sett'];?></a></td></tr>
</table></td>
</tr>

<tr>
<td><br>
<small class='subtitle'><?php echo $txt['plugin_opt'];?></small><hr>
<input type="checkbox" name="dis_plug" id="dis_plug">&nbsp;<label for="dis_plug"><small><?php echo $txt['plugin_disable'];?></small></label>
</td>
</tr>

<tr>
<td>
<input type="checkbox" name="ytube_mp4" id="ytube_mp4">&nbsp;<label for="ytube_mp4"><small><?php echo $txt['plugin_youtube'];?></small></label>
</td>
</tr>
<tr>
<td><input type="checkbox" name="imageshack_tor" id="imageshack_tor" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('tprblock').style.display=displ;"<?php if (isset($imageshack_acc)) print ' checked'; ?>>&nbsp;<label for="imageshack_tor"><small><?php echo $txt['plugin_imageshack'];?></small></label><table width="150" id="tprblock" style="display: none;">
<tr><td><?php echo $gtxt['_uname'];?>&nbsp;</td><td><input type="text" name="tor_user" id="tor_user" size="15"/></td></tr>
<tr><td><?php echo $gtxt['_pass'];?>&nbsp;</td><td><input type="password" name="tor_pass" id="tor_pass" size="15"/></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<input type="checkbox" name="mu_acc" id="mu_acc" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('mprblok').style.display=displ;"<?php if(isset($mu_cookie_user_value)) print ' checked'; ?>>&nbsp;<label for="mu_acc"><small><?php echo $txt['plugin_megaupl'];?></small></label>
<table width="150" id="mprblok" style="display: none;">
<tr><td><?php echo $txt['_user'];?></td><td><input type="text" name="mu_cookie" id="mu_cookie" size="45"/></td></tr>
</table>
</td>
</tr>

<tr>
<td>
<input type="checkbox" name="hf_acc" id="hf_acc" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('hfblok').style.display=displ;"<?php if(isset($hf_cookie_auth_value)) print ' checked'; ?>>&nbsp;<label for="hf_acc"><small><?php echo $txt['plugin_hotfile'];?></small></label>
<table width="150" id="hfblok" style="display: none;">
<tr><td><?php echo $txt['_auth'];?></td><td><input type="text" name="hf_cookie" size="45"/></td></tr>
</table>
</td>
</tr>

<tr>
<td>
<input type="checkbox" name="rs_acc" id="rs_acc" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('rsblok').style.display=displ;"<?php if(isset($rs_cookie_enc_value)) print ' checked'; ?>>&nbsp;<label for="rs_acc"><small><?php echo $txt['plugin_rs'];?></small></label>
<table width="150" id="rsblok" style="display: none;">
<tr><td><?php echo $txt['_enc'];?></td><td><input type="text" name="rs_cookie" size="45"/></td></tr>
</table>
</td>
</tr>


<tr>
<td>
<input type="checkbox" name="vBulletin_plug" id="vBulletin_plug">&nbsp;<label for="vBulletin_plug"><small><?php echo $txt['plugin_buletin'];?></small></label>
</td>
</tr>


</table>
</form>
</td>
</tr>
</tbody>
</table>



<script type="text/javascript">
function set_rlxmode(){
  var d = document; var mydate=new Date(); var cklive=eval(1000*3600*12);
  var id = 'rl_ajax'; var dirpath = location.pathname; var lastslash = location.pathname.lastIndexOf("/");
  dirpath = dirpath.substring(0,lastslash+1);
  mydate.setTime(eval(mydate.getTime()+cklive));
  d.cookie=id+"="+"<?php echo ($userck_std_mode ? "1" : "0");?>; path="+dirpath+"; expires="+mydate.toGMTString()+"; ";
}; set_rlxmode();
</script>

<table class="hide-table" id="tb3" cellspacing="5" width="100%">
<?php 
//_create_list();

if(isset($_POST["act"])){
 echo '<tr><td align="center">';
 require_once(CLASS_DIR."options.php");
 echo '</td></tr>';
}
$morethanone = (!isset($list["files"]["totalfile"]) ? count($list)>1 : $list["files"]["totalfile"]>1);
?>
<tr>
<td align="center" width="100%">
<form action="" name="flist" method="post">
<span id="topnavi">
<a href="javascript:void(0);" onclick="setCheckboxes(1);"><?php echo $txt['chk_all'];?></a> |
<a href="javascript:void(0);" onclick="setCheckboxes(0);"><?php echo $txt['chk_unchk'];?></a> |
<a href="javascript:void(0);" onclick="setCheckboxes(2);"><?php echo $txt['chk_invert'];?></a> |
<?php
if($list && $morethanone){
 echo '<a href="javascript:void(0);" onclick="flist_match_hide();">'.$gtxt['chk_txt_matches'].' </a> |';
}
?>
</span>
<a href="javascript:void(0);" onclick='<?php echo (!$ajax_serverfiles? 'urlload()' : 'go_gLoad(150)')?>;'><?php echo $txt['_sfrefresh'];?></a>
<?php
if($list)
 {
  ?>
<?php if ($show_all === true)
   {
  ?>
| <a href="javascript:void(0);" onclick="<?php echo (!$ajax_serverfiles? 'showAll()' : 'showAll2()')?>" id="showall"><?php echo $gtxt['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1) {
  document.write("<?php echo $gtxt['_downloaded'];?>");
}else {
  document.write("<?php echo $gtxt['_everything'];?>");
}
</script></a>
<?php
   }
    if($morethanone){
	?>
<div id="flist_match_hitems" style="display:none;" class="acthistory_prompt">
<input type="text" title="<?php echo $gtxt['chk_txt_matches'];?>" size="20" id="flist_match_search" onkeypress="javascript:if(event.keyCode==13){return flist_match(); }">
<input type="image" title="<?php echo $gtxt['go_match'];?>" style="vertical-align:bottom;padding-bottom:1px;" src="misc/btn_search.png" onclick="return flist_match(); return false;">&nbsp;<a href="javascript:void(0);" onclick="flist_match_hide();"><small><?php echo $gtxt['close'];?></small></a>
<div></div><span>
  <input type="checkbox" id="flist_match_ins"><label for="flist_match_ins"><?php echo $gtxt['match_csensitive'];?></label>
  <input type="checkbox" id="flist_match_hide" checked="checked"><label for="flist_match_hide"><?php echo $gtxt['match_hideunmatch'];?></label>
 </span>
</div>
<?php	
	}
 }
  else
 {
  if ($show_all === true)
    {
    unset($Path);
    ?>
| <a href="javascript:void(0);" onclick="<?php echo (!$ajax_serverfiles? 'showAll()' : 'showAll2()')?>" id="showall"><?php echo $gtxt['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1){
  document.write("<?php echo $gtxt['_downloaded'];?>");
}else{
  document.write("<?php echo $gtxt['_everything'];?>");
}
</script></a>
<?php
    }else{
?>
<script type="text/javascript">deleteCookie("showAll");</script>	
<?php
	}	
  }

echo '<div class="dseparator" style="height:12px"></div>';
  
if($ajax_serverfiles){
  ?>
  <span id="tablefilewaiting" style="position:absolute;margin:-10px 0 0 30px;"></span>
  <div id="tablefilescontent" style="padding-top:5px;"></div>

<?php
} //--end ajax_serverfiles

else
{
  $feat_ajax["ajax_delete"] = (!$disable_to["act_delete"] && $jQ_online ? '1' : '0');

// Show List file in table
if($list)
  {
?>
<div id="parentcontainer" style="padding-top:10px;"><div style="text-align:center; margin-top:-10px;"><a href="javascript:void(0);" onclick="d=document; dcnt=d.getElementById('flcontainer'); ddcnt=d.getElementById('divcontainer'); if(dcnt) {dcnt.style.overflow='visible';d.getElementById('parentcontainer').innerHTML=ddcnt.innerHTML;};try{gosetfacebook();waitandload();}catch(e){};">release</a></div>
	
<div id="divcontainer" style="width:100%; overflow:auto;">
<table id="intbl" cellpadding="2" cellspacing="1" width="100%" class="filelist sortable">
<thead style="width:100%; white-space:nowrap;">
<tr valign="bottom" align="center" class="filelist_title">
<td class="sorttable_nosort">
<b class="sorttable_nosort"></b>
</td>
<td>
<?php if(!$disable_action) 
 { ?>
  <select name="act" onChange="javascript:void(document.flist.submit());" style="float:left;">
   <option SELECTED="SELECTED"><?php echo $gtxt['action'];?></option>
<?php echo 
  (!$disable_to["act_upload"]?'<option value="upload">'.$txt['act_upload'].'</option>':'').
  (!$disable_to["act_ftp"]?'<option value="ftp">'.$txt['act_ftp'].'</option>':'').
  (!$disable_to["act_mail"]?'<option value="mail">'.$txt['act_mail'].'</option>':'').
  (!$disable_to["act_boxes"]?'<option value="boxes">'.$txt['act_boxes'].'</option>':'').
  (!$disable_to["act_split"]?'<option value="split">'.$txt['act_split'].'</option>':'').
  (!$disable_to["act_merge"]?'<option value="merge">'.$txt['act_merge'].'</option>':'').
  (!$disable_to["act_md5"]?'<option value="md5">'.$txt['act_md5']." / Changer".'</option>':'').
  (!$disable_to["act_pack"]&&(@file_exists(CLASS_DIR."pear.php")||@file_exists(CLASS_DIR."tar.php")) ? "<option value=\"pack\">".$txt['act_pack']."</option>$nn" : "").
  (!$disable_to["act_zip"]&&(@file_exists(CLASS_DIR."pclzip.php"))?"<option value=\"zip\">".$txt['act_zip']."</option>$nn" : "").
  (!$disable_to["act_unzip"]&&(@file_exists(CLASS_DIR."unzip.php"))?"<option value=\"unzip\">".$txt['act_unzip']."</option>$nn" : "").
  (!$disable_to["act_rename"] ? "<option value=\"rename\">".$txt['act_rename']."</option>$nn" : "").
  (!$disable_to["act_mrename"] ? "<option value=\"mrename\">".$txt['act_mrename']."</option>$nn" : "").
  (!$disable_to["act_delete"] ? "<option value=\"delete\">".$txt['act_delete']."</option>$nn" : "").
  '</select>&nbsp;&nbsp;';
 }
?>	
<b><?php 
$total_files = (isset($list["files"]["totalfile"]) ? $list["files"]["totalfile"] : count($list));
echo ($total_files>0 ? "(".$total_files.' file'.($total_files>1 ? 's' : '').') &nbsp;' : "") . $txt['tabel_name'];?>

</b>
</td>
<td><b><?php echo $gtxt['tabel_sz'];?></b></td>
<?php
echo 
  (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]==1 && $show_column_sfile["md5"] ? '<td><b>' . 'MD5' . '</b></td>':'').
  (!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE['showAll']!=1) ? ($show_column_sfile["downloadlink"] ? '<td><b>'.$txt['tabel_dl'].'</b></td>':'').
  ($show_column_sfile["comments"] ? '<td><b>'.$txt['tabel_cmt'].'</b></td>' : '') : '').
  ($show_column_sfile["date"] ? '<td><b>'.$gtxt['tabel_dt'].'</b></td>' : '').
  ($show_column_sfile["age"] ? '<td><b class="sorttable_nosort">'.$gtxt['tabel_age'].'</b></td>' : '').
  ($feat_ajax["ajax_delete"]=='1' ? '<td><b class="sorttable_nosort">'.$gtxt['act_del'].'</b></td>' : '')
?>
</tr>
</thead>
<tbody id='flcontainer' style='width:100%; height:<?php echo (count($list)<13? "100%" : "280px");?>; white-space:nowrap; overflow:auto;'>

<?php
//Initiate Counter load time
  $time2 = explode (' ', microtime());
  $time2 = $time2[1] + $time2[0];
  $begintime2 = $time2;
    
  $total_files = 0; $total_size = 0; $kumulatifsz = true;
  if(isset($list["files"]["totalsize"])){
    $total_size = $list["files"]["totalsize"];
	$kumulatifsz = false;
  }

  foreach($list as $key => $file)
    {
    if(isset($file["name"]) && @file_exists($file["name"]))
       {	   
       $total_files++; $rnd = rand(11,99);
	   $_fdkey = str_replace("=","", rotN(base64_encode($file["date"].':'.'4puZ'), $rnd))."-".$rnd;
	   if($kumulatifsz) $total_size+=getfilesize($file["name"]);
       $inCurrDir = strstr(dirname($file["name"]), ROOT_DIR) ? TRUE : FALSE;
       if($inCurrDir){
         $Path = parse_url($PHP_SELF);
         $Path = substr($Path["path"], 0, strlen($Path["path"]) - strlen(strrchr($Path["path"], "/")));
       }else{
		 $cl_Path = relink_path(DOWNLOAD_DIR, $PHP_SELF);
	   }
?>
<tr id="brs<?php echo $total_files;?>" class="rowlist" onMouseDown="clk('cek_<?php echo $total_files;?>', this);" onMouseOut="if(document.getElementById('cek_<?php echo $total_files;?>').checked){this.className='rowlist_checked';} else{this.className='rowlist';}" align="center" title="<?php echo xmlentities(basename($file["name"]));?>">
 <td>
  <?php echo ((!$disable_to["act_rename"] && $ajax_rename) ? '<div id="rename_container" onmouseup="ren(this, \'fn_'.$total_files.'\', \''.$file["date"].'\')"><div class="rename_es"></div></div>':'');?>
  <input type=checkbox name="files[]" id="cek_<?php echo $total_files;?>" value="<?php echo $file["date"];?>" onClick="clk('cek_<?php echo $total_files;?>')">
  </td>
 <td id="fn_<?php echo $total_files;?>"><?php echo "<b><a href=\"". ($inCurrDir ? $Path.str_replace('\\', "/", substr(dirname($file["name"]), strlen(ROOT_DIR)))."/".xmlentities(basename($file["name"])) : $cl_Path["root"]."/".$cl_Path["download"].xmlentities(basename($file["name"]))) . "\">".xmlentities(basename($file["name"]))."</a></b>";?></td>
 <td><?php echo str_replace(" ", "&nbsp;",$file["size"]);?></td>
 <?php 
 $flink = (isset($file["link"])?$file["link"]:"");
 echo 
   (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]==1 && $show_column_sfile["md5"] ? "<td class=\"txtmd5\">{$file["md5"]}</td>":null).
   (!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]!=1) ? ($show_column_sfile["downloadlink"] ? "<td title=\"{$flink}\">".($flink ? "<a href=\"{$flink}\">".trimlink($flink)."</a>" : "").'</td>':'').
     ($show_column_sfile["comments"] ? '<td>'.(isset($file["comment"]) ? str_replace("\\r\\n", "<br>", $file["comment"]) : "").'</td>':''):'').
   ($show_column_sfile["date"] ? '<td>'.str_replace(" ","&nbsp;",date("d.m.Y H:i:s", $file["date"])).'</td>':'').
   ('<td><small>'.$file["age"].'</small></td>').
   ($feat_ajax["ajax_delete"]=="1" ? "<td><a id=\"dL-{$total_files}\" href=\"del.php?d={$_fdkey}&lineid={$total_files}\" rel=\"facebox\"> <img src=\"".IMAGE_DIR."rldel.png\" alt=\"X\"></a></td>" : "")
 ?>
</tr>

<?php
	} // end- if file exist
   } //end foreach

	$n_column = 0;
	foreach($show_column_sfile as $cocol => $colval){
      if($colval){
	    switch($cocol)
		{
	     case "ip": // add colspan when not show all & column ip shown
		   if(!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]!=1))
		   { $n_column++; }
		 break;
		 case "md5": // add colspan when show all & column md5 shown
		   if(isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]==1)
		   { $n_column++; }
		 break;
		 case "downloadlink": // add colspan when downloadlink shown and it's in showdownloaded
		   if(!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]!=1))
		    { $n_column++; }
		 break;
	     default: $n_column++;
		 break;
	    }
	  } //-end $colval enable
    } //-end foreach	
  $time2 = explode(" ", microtime());
  $time2 = $time2[1] + $time2[0];
  $endtime2 = $time2;
  $totaltime2 = ($endtime2 - $begintime2);
?>
</tbody>
<tfoot>
<tr id="sffooter" align='center'>
 <td>&nbsp;</td>
 <td>Total :</td>
 <td><?php 
 $total_size=(isset($list["files"]["totalsize"]) ? $list["files"]["totalsize"] : $total_size);
 $total_files = (isset($list["files"]["totalfile"]) ? $list["files"]["totalfile"] : $total_files);
 echo str_replace(" ", "&nbsp;",bytesToKbOrMbOrGb($total_size));
 $n_column = ($feat_ajax["ajax_delete"]=="1" ? ($n_column+1) : $n_column);
 ?></td>
 <?php echo ($n_column>0 ? "<td colspan='{$n_column}'></td>" : "");?>
</tr>
</tfoot>
</table>
 </div>
</div>
<small class="y"><b><?php echo $total_files;?></b></small>
<small class="footer3"> files loaded in <?php echo $totaltime2;?> sec</small>
<script>gosetfacebook();</script>
 
<?php
 } //-- show files, $list not empty
 
 else{ // Not Files Found
 
  echo "<center><span id=\"nofiles\">{$gtxt['tabel_no_file']}</span></center>"; 
  
 }
} //--end old method listing server files
?>
</form>

<br><div class="acthistory_prompt rlxmode" style="background-color=#FAE5B0;font-size:85%;margin:3px 15px 3px 15px;">RL x-mode:
<?php echo ($userck_std_mode ? "<b>standard</b> |\n<a href='{$PHP_SELF}?ajax=off&ausv={$usrajxnuid}' title='Disable Ajax'>basic HTML</a>" : "<a href='{$PHP_SELF}?ajax=on&ausv={$usrajxnuid}' title='Enable Ajax'>standard</a> |\n <b>basic HTML</b>");
?></div>


</td>
</tr>
</table>

<form name="frmTBLoad" id="frmTBLoad" action="">
   <input type="hidden" id="rfr" name="rfr" value="TBLoad">
</form>

<script type="text/javascript">
var frmTB, destd;
//Link checker
var dlinks, arlinks, startFrm = 0; 
<?php 
if($ajax_serverfiles){
?>
function showAll2(){
  var ainer = d.getElementById('showall');
  var strshow = "<?php echo ($gtxt['_show']!=''?$gtxt['_show']:'Show');?>&nbsp;";  
  if(getCookie("showAll") == 1) {
     deleteCookie("showAll");
	 ainer.innerHTML =  strshow + "<?php echo ($gtxt['_everything']!=''?$gtxt['_everything']:'Everything');?>";
  } else {
     d.cookie = "showAll = 1;";
	 ainer.innerHTML = strshow + "<?php echo ($gtxt['_downloaded']!=''?$gtxt['_downloaded']:'Downloaded');?>";
  }
  go_gLoad(150);
}
function go_gLoad(dlay){
  if(dlay==undefined) dlay = 10;
  ajxtmr = setTimeout("_gLoad('tablefilewaiting', 'tablefilescontent')", dlay);
}
go_gLoad(10);
<?php
}
?>
</script>


<!--xxx-->
<table class="hide-table" id="tb4" cellspacing="5" width="100%">
<tbody>
<tr><td align="center" width="100%">
	<div style="text-align:center">	
	<div align="center" style="cursor:pointer;" onclick='d=document; cur=d.getElementById("lnksupport").style; if(cur.display==""){cur.display="none";}else{cur.display="";};'><span id="ww" style="font-weight:bold;width:100%;"><?php echo $txt['work_with']?></span></div>
	<center>
 <table><tr><td align="center">
	<div id="lnksupport">Axifile.com | Badongo.com | <b>Depositfiles.com</b> | <strong>Megarotic.com</strong>
	Easy-Share.com | Egoshare.com | <b>Filefactory.com</b> | <strong>Adrive.com</strong>
	Files.to | Gigasize.com | <b>Mediafire.com</b> | iFolder.ru | BitRoad.net
	<b>Megashares.com</b> | <b>Megaupload.com</b> | Mihd.net | UploadPalace.com
	Momupload.com | <b>Rapidshare.com</b> | Rapidshare.de | Shareonall.com
	Rndbload.com |  Savefile.com | <b>Sendspace.com</b> | Ziddu.com | <strong>MegaShare.com</strong>
	Speedyshare.com | Turboupload.com | Uploaded.to | Cocoshare.com 
	Uploading.com | Usaupload.net | Zshare.net | <strong>FileFront.com </strong>
	<br><b>Kills</b><br>
	Anonym.to | Linkbucks.com | Lix.in<br>
	Rapidshare.com Folders | Usercash.com</div>
	
	<form name="frmlnkchk" action="javascript:checkLink();" method="POST">
	
	<div align="center" style="padding-top:6px;">	
	<textarea rows="6" id="links" name="links" style='white-space:nowrap;' class="redtxtarea"></textarea><br>
	<label style="float:left;"><input type="checkbox" value="lnkcurl" name="lcurl" id="lcurl"<?php echo (($ch_curl == 1)?' checked=checked':' disabled=disabled');?>>
	<span><?php echo $txt['curl_stat']?>&nbsp;</span><?php echo (($ch_curl == 1) ? '<b class="g">'.$txt['_on'].'</b>':'<b class="b">'.$txt['_off'].'</b>');?></label>
	<label style="float:right;" onclick="chklinkOnly(document.getElementById('d').checked);"><input type="checkbox" value="d" name="d" id="d"><?php echo $txt['link_only']?></label>
	<div style="padding-top:20px;"></div>
	<input type="submit" value="Check Links" id="btnlnkchk" name="submit">
	
	</div>	
	</form>
</td></tr>
</table></center>

	<span id="lnkwait" style="display:none;background-color:#800000;text-align:center;"><img src="<?php echo IMAGE_DIR;?>fbload.gif" border="1" alt="loading">&nbsp;&nbsp;<b class="o">Please&nbsp;wait..</b></span>
	<div id="linkresult" align="left"></div></div>



</td>
</tr>
</tbody>
</table>
<!--xxx-->


<!--AccCheXer-->

<table class="hide-table" id="tb5" cellspacing="5" align="center" width="100%">
<tbody>
<tr><td align="center" width="100%">
<div style="text-align:center"><center>
 <table><tr><td align="center">
 	<div><b><?php echo $txt['rs_acc_chk'];?></b><br><small>[by: reiluke; <?php echo $txt['modded'];?> -&gt; &#73;&#100;&#111;&#101;&#110;&#107;]</small><br>
 	<form name="myform" id="myform" method="post" action="https://ssl.rapidshare.com/cgi-bin/premiumzone.cgi" target=_blank>
	<input type="hidden" name="login" id="login">
	<input type="hidden" name="username" id="username">
	<input type="hidden" name="password" id="password">
	</form>
<script type="text/javascript">
<?php if($ada_rsajax_js){?>
function doAccChk(){
	ajxtmr = setTimeout("get('accounts','waiting','showresults','btnchkacc')", 300);
}
<?php }
 else{  echo "function doAccChk(){alert('Can\'t found rsajax.js');\r\nreturn false;}"; }
?>
</script>
	<form action="javascript:doAccChk();" name="acctform" id="acctform">
   <textarea rows="4" id="accounts" class="redtxtarea"></textarea>
     <br><small class="g"><?php echo $txt['un_pass'];?></small>
	 
	<label style="float:left;"><input type="checkbox" name="acccurl" id="acccurl"<?php echo (($ch_curl == 1)?' checked=checked':' disabled=disabled');?>>
	<span><?php echo $txt['curl_stat']?>&nbsp;</span><?php echo (($ch_curl == 1) ? '<b class="g">'.$txt['_on'].'</b>':'<b class="b">'.$txt['_off'].'</b>');?></label>	 
	<label style="float:left;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="accapi" id="accapi">
	<span><?php echo "Use rsAPI"?>&nbsp;</span></label>	 
	 

	<div style="padding-top:15px;"></div>
	<input type="submit" name="button" id="btnchkacc" value="Check" onclick="document.getElementById('waiting').style.display='';" <?php echo ($ch_curl == 1)?'':'disabled';?>>

 </form>

 
	</div>
</td></tr>
</table></center>
	
 <div><br>
  <div id="waiting" style="display:none;" valign="bottom"></div>
  <div id="showresults"></div>
 </div>
 </div>
</td>


</td></tr>
</table>



</td>
</tr>
</tbody>
</table>
<?php
if(isset($_REQUEST["act"]) || isset($_REQUEST["ajax"]))
  {
 echo "<script type=\"text/javascript\">switchCell(3);</script>";
  }
else
  {
 echo "<script type=\"text/javascript\">switchCell(1);</script>";
  }
?>
<!--AccCheXerr-->




<!--SHOUTBOX frame-->
</td><td align="right" valign="top">
<!--Rightside You may place ashoutbox iframe here -->
</td>
<!--END Rightside-->


</tr>
</tbody>
</table>

<table width="60%" align="center" cellpadding="0" cellspacing="0">
<tr><td>

<div>
<script type="text/javascript">
<?php if($ada_rsajax_js){?>
function doStatacc(){

  ajxtmr = setTimeout("get('staccounts','accwaiting','accshowresults','')", 300);
}
<?php }
  else{ echo "function doStatacc(){alert('Can\'t found rsajax.js');\r\nreturn false;}"; }
 ?>
</script>
<form action="javascript:doStatacc();" name="poiuy" id="poiuy">
   <input type="hidden" id="staccounts" value="lol">
</form>
</div>

<script type="text/javascript">
var show = 0;
var show2 = 0;
</script>
<div align="center">
<?php
$CNfo = "<hr width=150>";
if($showAccRsStatus){ // this mean == ($ch_curl == 1 && $exist_accrs && $premix_status)
 $CNfo.= "<div id=\"refresh\" style=\"float:right;\">&nbsp;<a href=\"javascript:void(0)\" title=\"{$gtxt['_refresh']} acc status\" onclick=\"stacc();this.blur();\"><small>{$gtxt['_refresh']}</small></a></div><div id=\"accshowresults\" style=\"display:none;\"></div><div id=\"accwaiting\" align=\"center\" style=\"display:none;\" title=\"please wait...\"></div><hr>";
}

if($auto_del_time>0){
	if($auto_del_time<1) {
	  $auto_del_time = floor(60 * $auto_del_time);
	  $txt['premix_used_3'] = "minute(s)";
	}
	$CNfo.= "<span class=\"c\">*{$gtxt['_autodel']}:&nbsp;<b class=\"g\">{$auto_del_time}</b>&nbsp;{$txt['premix_used_3']}</span>";
}
if($limitsize>0){
  $CNfo.= "<br><span class=\"c\">*{$gtxt['_maxfilesize']}:&nbsp;<b class=\"s\">{$limitsize}</b>&nbsp;MB</span>";
}
if($lowlimitsize>0)	{
  $CNfo.= "<br><span class=\"c\">*{$gtxt['_minfilesize']}:&nbsp;<b class=\"s\">{$lowlimitsize}</b>&nbsp;MB</span>";
}
if($downloadLimitbyip && $downloadsPerIP > 0){
  $CNfo.= "<br><span class=\"c\">*{$gtxt['_limitip']}:&nbsp;<b class=\"s\">{$downloadsPerIP}</b> per <b class=\"s\">{$downloadDelayPerIP}</b>s</span>";
}
if($pointboost>0){
  $CNfo.= "<br><span class=\"c\">*{$gtxt['_pointboost']}:&nbsp;<b class=\"s\">{$txt['_on']}</b></span>";
}
if(!empty($add_ext_5city)){
  $CNfo.= "<br><span class=\"c\">*{$gtxt['_fakeext']}:&nbsp;<b><a style=\"color:red\" href=\"javascript:void(0)\" title=\"{$gtxt['_fakeext_desc']}\">{$add_ext_5city}</a></b></span>";
}
$CNfo.= "<hr>";

echo "<script type=\"text/javascript\">
function showaccdiv(){
 embedaccnfo(document.getElementById('plusmin'));
}
\nfunction embedaccnfo(Obj){\ntxt=\"&nbsp;embed&nbsp;acc&nbsp;\";cntent=document.getElementById(\"otheracc\");if(cntent.style.display==\"none\"){cntent.style.display=\"\";Obj.innerHTML=txt+\"&#8212;&nbsp;\";}else{cntent.style.display=\"none\";Obj.innerHTML=txt+\"&#43;&nbsp;\";}}</script>\n";
if(isset($premium_acc) && isset($acc_txt) && $acc_txt!=''){
 $CNfo.="<div class=\"acthistory_prompt\" style=\"text-align:left;margin-top:0px;padding:0;position:absolute;\" onMouseOut=\"showaccdiv()\" onMouseOver=\"showaccdiv()\"> <small><label> <span id=\"plusmin\"> &nbsp;embed&nbsp;acc&nbsp;&#43;&nbsp;</span></label><div id=\"otheracc\" style=\"display:none; padding:1px 10px 3px 8px; line-height:10px; text-align:left;margin-top:0px;color:#fff;background:#800000;\">";
 $CNfo.= $acc_txt;
 $CNfo.="</div></small></div>";
}
//--end acc NFO

if(strstr($CNfo,"<span"))
{
echo "<script type=\"text/javascript\">
 var divnfo = document.getElementById('nfoconfig');
 divnfo.innerHTML = '".$CNfo."';\n
 divnfo.style.display='';\n";
if($showAccRsStatus){ 
 echo 'doStatacc();';
}
echo "\n</script>";
}
?><hr>
</div>
<div align="center" class="tdfootcredits"></div>
<div align="center" id="main_footer">
<?php print CREDITS; ?><br><hr><?php print VERSION; ?><br>
</div>
<?php if($show_w3c_validator){?>
<div id="w3cvalidator" style="text-align:center;font-size:9px;">
<a href="http://validator.w3.org/check?uri=referer" target="_blank">HTML 4.01</a>&nbsp;&mdash;&nbsp;<a href="http://jigsaw.w3.org/css-validator/check/referer/" target="_blank">CSS 2.1 / 3.0</a></div>
<?php }?>
</td>
</tr>
</table>
<div id="preleft_nfo" style="display:none;">
<?php if($navi_left["server_info"]) {if(@file_exists(CLASS_DIR."sinfo.php")) require_once(CLASS_DIR."sinfo.php");} 
$time = explode(" ", microtime());
$endtime = $time[1] + $time[0]; unset($time);
$totaltime = ($endtime - $begintime);
echo "<div style='padding-left:5px;'><small>{$server['property']}<br>Page Load: {$totaltime} sec</small></div>";
?>
</div>
<script type="text/javascript">
 var inner_left = document.getElementById('preleft_nfo').innerHTML;
 document.getElementById('left_NFO').innerHTML = inner_left;
 <?php if($limit_cpuload && isset($srvload)){?>
 setTimeout("document.getElementById('srvload').style.display='none'", 2500);
 <?php }?>
</script>
<?php if(file_exists(MISC_DIR."sorttable.js")){
?>
<script type="text/javascript" src="<?php echo MISC_DIR;?>sorttable.js"></script>
<?php } ?>
</body>
</html>