<?php
if (!defined('RAPIDLEECH')){
  require_once("index.html");exit;
}

?><table align="center" style="margin-top:-2px;">
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
if($options["navi_left"]["showcpanel"]){
  if(isset($options["xpanel_filename"]) && @file_exists($options["xpanel_filename"])){
 ?>
 <div><a href="<?php echo ROOT_URL.$options["xpanel_filename"];?>" id="left_cpanel" class="nav-leftimg" target="_blank" title="<?php echo $L->say['cpanel'];?>"><span><?php echo $L->say['cpanel'];?></span></a></div>
 <?php
 }
}

if($options["navi_left"]["showplugins"]){
?>
<div><a href="javascript:;" onclick="ddir=document.getElementById('dir'); if(ddir.style.display!=''){ddir.style.display='';}else{ddir.style.display='none';}" id="left_plugin" class="nav-leftimg" title="<?php echo $L->say['plugins'];?>"><span><?php echo $L->say['plugins'];?></span></a></div>
<div align="center" class="splugins"><?php echo '<b><small>'.count($host).'</small></b> Plugins'; ?></div>
<div id="dir" dir="rtl" align="left" style="overflow-y:scroll; height:150px; padding-left:5px; display:none;"></div>
<script type="text/javascript">
/* <![CDATA[ */
 var arHost = Array(
<?php
ksort($host); $cm = "";
foreach($host as $site => $file)
 { echo "$cm'$site'"; $cm = ", "; }
?>
);
function inserthost(arHost){
  var dtpl = '', d = document;
  for(var i=0; i < arHost.length; i++)
   {  dtpl += '<span class=&quot;c&quot;>' + arHost[i] + '<\/span><br \/>';}
  d.getElementById('dir').innerHTML = dtpl;
}
setTimeout("inserthost(arHost)", 100);
/* ]]> */
</script>
<?php
}
if($options["navi_left"]["showaudl"]){
?>
<div><a href="<?php echo ROOT_URL;?>audl.php" id="left_audl" class="nav-leftimg" target="_blank" title="<?php echo $L->say['audl'];?>"><span><?php echo $L->say['audl'];?></span></a></div>
<?php
}
if($options["navi_left"]["showauul"]){
?>
<div><a href="<?php echo ROOT_URL;?>auul.php" id="left_auul" class="nav-leftimg" target="_blank" title="<?php echo $L->say['auul'];?>"><span><?php echo $L->say['auul'];?></span></a></div>
<?php
}
if($options["navi_left"]["showlynx"]){
?>
<div><a href="<?php echo ROOT_URL;?>lynx.php" id="left_lynx" class="nav-leftimg" target="_blank" title="<?php echo $L->say['lynx'];?>"><span><?php echo $L->say['lynx'];?></span></a></div>
<?php
}
if($options["navi_left"]["showmtn"]){
?>
<div><a href="<?php echo ROOT_URL;?>mtn.php" id="left_mtn" class="nav-leftimg" target="_blank" title="<?php echo $L->say['mtn'];?>"><span><?php echo $L->say['mtn'];?></span></a></div>
<?php
}
?></td></tr>
</table>
</td></tr>
<tr><td>
<div id="left_NFO"><center><img style="padding-top:10px;" src="<?php echo ROOT_URL.IMAGE_DIR;?>fbload.gif" border="0" alt="<?php echo $L->say['loading_pwait']?>" /></center></div>
</td></tr>
<tr>
<td height="100%" style="padding:2px;">
<div id="nfoconfig" style="height:120px; display:none;"></div>
</td>
</tr>
</table>
</td></tr>
</table></td>
<td align="center" valign="top">
<noscript><br /><b><?php echo $L->say['js_disable'];?></b></noscript>
<table cellspacing="2" class="menuUtama" border="0">
<tbody>
<tr>
<td id="navcell1" onclick="switchCell(1);">Main</td>
<td id="navcell2" onclick="switchCell(2);">Config</td>
<td id="navcell3" onclick="switchCell(3);">Server Files</td>
<td id="navcell4" onclick="switchCell(4);">Validator</td>
<td id="navcell5" onclick="switchCell(5);">RS Checker</td>
</tr>
</tbody>
</table>

<table id="tb_content">
<tbody>
<tr>
<td align="left">

<?php
if($options["limitbyip"]){
  if($ada_acc && ($trheute > $options["maximum_free_downloads"])){
?>
<div align="center" style="font-size:15px;"><br /> <?php echo $msgNya;?><div><?php echo $L->say['sorry_inc'];?></div><br /></div>
<?php
  }
}
?>

<form action="<?php echo $PHP_SELF;?>" name="transload" method="post"<?php if ($options['new_window']) { echo ' target="_blank"'; } ?>>
<table class="tab-content" id="tb1" cellspacing="5" width="100%"<?php if($options["limitbyip"]){if($trheute>$options["maximum_free_downloads"]){print ' style="display:none;"';}}?>>
<tbody>
<tr>

<?php if( (isset($limitation_alert) && $limitation_alert) || ($options["limitbyip"] && $ada_acc && ($trheute > $options["maximum_free_downloads"]))) {
?>
<td align="left">
<div style="padding-top:20px;"><div class="warn_alert"><?php echo $limitmsg;?></div></div>
<div class="warn_alert" style="height:auto;"><?php echo $limit_msg;?></div>
<div style="padding-bottom:15px;">&nbsp;</div>
</td></tr>
<?php
} else { // Allow use the RL
?>

<td align="left">

<p align="left"><b><?php echo $L->say['link_transload'];?></b><br />&nbsp;<input type="text" name="link" id="link" size="50" />
<p align="left"><b><?php echo $L->say['referrer'];?></b><br />&nbsp;<input type="text" name="referer" id="referer" size="50" />
</td>
<td align="left" valign="top">
<div style="padding-top:25px;"></div><input type="<?php echo ($options['new_window'] && $options['new_window_js']) ? 'button" onclick="new_transload_window();' : 'submit'; ?>" value="<?php echo $L->say['_transload'];?>" />

<?php
if(isset($options["mip_enabled"]) && $options["mip_enabled"]){
  $mip_action = "select";  // select || download
  if(file_exists(CLASS_DIR."mip.php")) @include_once(CLASS_DIR."mip.php");
}
?>

</td>
</tr>
<tr>
<td align="left"><label><input type="checkbox" name="user_pass" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('usernpass').style.display=displ;" value="on" />&nbsp;<?php echo $L->say['uname_pass_http'];?></label></td>
</tr>
<tr id="usernpass" style="display: none;">
<td align="center">
<label for="iuser"><?php echo $L->say['_iuser'];?></label><input type="text" name="iuser" value="" /><br />
<label for="ipass"><?php echo $L->say['_ipass'];?></label><input type="text" name="ipass" value="" />
</td>
</tr>
<tr>
<td align="left"><label><input type="checkbox" name="add_comment" id="add_comment" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('comment').style.display=displ;" />&nbsp;<?php echo $L->say['add_comment'];?></label></td>
</tr>
<tr id="comment" style="display: none;">
<td align="left" colspan="2">
<textarea name="comment" class="redtxtarea" rows="4" style="width:370px;"></textarea>
</td>
</tr>
<?php }?>


<tr>
<td colspan="2">
<br /><small class='subtitle'><?php echo $L->say['user_stats'];?></small>

<?php
echo ($options["limitbyip"] && $ada_acc ? $L->say['limit_leech'] : '').'<hr />';
echo ($options["OnlineVisitor"] ? '<div>'.$L->say['user_online'].' <b class="g">'.$visitors->online['total'].'</b></div>':'');
echo $L->say['detect_ip'].' <b>'.$ipmu.' </b>'.($options["logact"] ? '<small class="y">' . $L->say['logged'].'</small>' : '');
if($ref!=''){echo '<br />'.$L->say['referrer'].': <small>'.$ref.'</small>';}
if($options["limitbyip"] && $ada_acc){
   echo '<br />', $L->sprintf( $L->say['already_use_premix'], $heute, $options["maximum_free_downloads"], $options["delay_per_ip"] );
}
?>
</td>
</tr>

<tr>
<td colspan="2">

<div style="height:5px;">&nbsp;</div><small class='subtitle'><?php echo $L->say['server_stats'];?></small><hr />
<?php echo $L->say['current_storage'];?>&nbsp;<b class="g"><?php echo bytesToKbOrMbOrGb($serverfiles);?></b>&nbsp;&nbsp;&nbsp;
<?php
if($options["storage_limit"]>0){
	echo '(Max: <b class="r">'.bytesToKbOrMbOrGb($options["storage_limit"]*1024*1024).'</b>)';
}
if($options["limitbytraffic"]){
	echo "<br />".$L->say['current_traffic']." <b class='g'>".bytesToKbOrMbOrGb($traffic->total).
	     "</b>&nbsp;".$L->say['of']."&nbsp;<span class=\"s\" title=\"".$L->say['max_traffic']."\"><b>".bytesToKbOrMbOrGb($traffic->max)."</b></span> ".($traffic->expiredate ? $L->say['until']." <small><span class='g'>".date("d-M-Y", $traffic->expiredate)."</span></small>" : "");

	if($options["day_reset_trafic"] > 0) {
	  echo "<br />".$L->say['reset_traffic_remain']."&nbsp;<small class=\"s\">".sec2time( $traffic->timeremain() )."</small>";
	}
}
if($options["limit_timework"])	{
  echo "<br />".$L->say['_timework'].": <b class=\"s\">{$options["workstart"]}</b>&nbsp;".$L->say['upto']."&nbsp;<b class=\"s\">{$options["workend"]}</b>";
}
?>
</td>
</tr>

<?php
if(is_array($deleted) && $deleted["count"] > 0){
?>
<tr><td colspan="2">
<div style="height:5px;">&nbsp;</div><small class='subtitle'><?php echo $L->say['log_act'];?></small>
<div id='log_activity' class='acthistory_prompt'>
<b class="g"><?php echo $deleted["count"];?></b>&nbsp;
<?php echo $L->say['lact_files'];?>&nbsp;
<?php echo $L->say['lact_autodeleted'];?>
<label onclick='return slip(this);' style='float:right;'>
<span id='act_hist_txt'><?php echo $L->say['show_last_act'];?></span>
<input id='act_hist_img' type='image' src='<?php echo ROOT_URL.IMAGE_DIR;?>plus.png' /></label>
<div id='act_history' class='acthistory_result' style='display:none;'>
<pre style='margin:0;'>
<?php foreach($deleted["files"] as $file){
   echo "* " . htmlentities($file) . "\n";
 }
 ?>
</pre></div>
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
<td><label><input type="checkbox" name="domail" id="domail" onclick="javascript:document.getElementById('emailtd').style.display=document.getElementById('splittd').style.display=this.checked?'':'none';document.getElementById('methodtd').style.display=(document.getElementById('splitchkbox').checked && this.checked)?'':'none';"<?php echo isset($_COOKIE["domail"]) ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['send_email'];?></label>
<table id="emailtd"<?php echo isset($_COOKIE["domail"]) ? "" : " style=\"display: none;\""; ?>><tr><td><?php echo $L->say['email'];?>&nbsp;<input type="text" name="email" id="email"<?php echo isset($_COOKIE["email"]) ? " value=\"".(isset($_COOKIE["email"])?$_COOKIE["email"]:"")."\"" : ""; ?> /></td></tr></table>
</td>
</tr>
<tr id="splittd"<?php echo isset($_COOKIE["split"]) ? "" : " style=\"display: none;\""; ?>>
<td style="padding-bottom:10px ;"><label>
<input id="splitchkbox" type="checkbox" name="split" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('methodtd').style.display=displ;"<?php echo isset($_COOKIE["split"]) ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['split_file'];?></label>
<table id="methodtd"<?php echo isset($_COOKIE["split"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<table>
<tr>
<td><?php echo $L->say['method'];?>&nbsp;
 <select name="method">
  <option value="tc"<?php echo isset($_COOKIE["method"]) && $_COOKIE["method"]== "tc" ? " selected='selected'" : ""; ?>><?php echo $L->say['tot_com'];?></option>
  <option value="rfc<?php echo isset($_COOKIE["method"]) && $_COOKIE["method"] == "rfc" ? " selected='selected'" : ""; ?>"><?php echo $L->say['rfc'];?></option></select></td>
</tr>
<tr>
<td><?php echo $L->say['part_size'];?>&nbsp;<input type="text" name="partSize" size="2" value="<?php echo isset($_COOKIE["partSize"]) ? $_COOKIE["partSize"] : 10; ?>" />&nbsp;MB</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td><label><input type="checkbox" id="useproxy" name="useproxy" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('proxy').style.display=displ;"<?php echo isset($_COOKIE["useproxy"]) ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['use_proxy'];?></label>
<table id="proxy"<?php echo isset($_COOKIE["useproxy"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<table width="150">
<tr><td><?php echo $L->say['_proxy'];?>&nbsp;</td><td><input type="text" name="proxy" id="proxyproxy" size="20"<?php echo isset($_COOKIE["proxy"]) ? " value=\"".(isset($_COOKIE["proxy"])?$_COOKIE["proxy"]:"")."\"" : ""; ?> /></td></tr>
<tr><td><?php echo $L->say['_uname'];?>&nbsp;</td><td><input type="text" name="proxyuser" id="proxyuser" size="20" <?php echo isset($_COOKIE["proxyuser"]) ? " value=\"".(isset($_COOKIE["proxyuser"])?$_COOKIE["proxyuser"]:"")."\"" : ""; ?> /></td></tr>
<tr><td><?php echo $L->say['_pass'];?>&nbsp;</td><td><input type="text" name="proxypass" id="proxypass" size="20" <?php echo isset($_COOKIE["proxypass"]) ? " value=\"".(isset($_COOKIE["proxypass"])?$_COOKIE["proxypass"]:"")."\"" : ""; ?> /></td></tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td><label><input type="checkbox" name="premium_acc" id="premium_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('prblock').style.display=displ;"<?php if (isset($premium_acc) && count($premium_acc) > 0) print ' checked="checked"';?> />&nbsp;<?php echo $L->say['use_premix'];?></label>
<table id="prblock" style="display: none;">
<tr><td>
<table width="150">
<tr><td><label for="premium_user"><?php echo $L->say['_uname'];?></label></td><td><input type="text" name="premium_user" id="premium_user" size="20" onfocus="highlight(this);"  value="" /></td></tr>
<tr><td><label for="premium_pass"><?php echo $L->say['_pass'];?></label></td><td><input type="password" name="premium_pass" id="premium_pass" size="20" onfocus="highlight(this);" style="color:#912704;"  value="" /></td></tr>
</table>
</td></tr>
</table>
</td>
</tr>

<?php
if($options["download_dir_is_changeable"]){
?>
<tr>
<td><label><input type="checkbox" name="saveto" id="saveto" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('path').style.display=displ;"<?php echo isset($_COOKIE["saveto"]) ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['save_to'];?></label>
<table id="path"<?php echo isset($_COOKIE["saveto"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<?php echo $L->say['save_path'];?>&nbsp;<input type="text" name="path" size="40" value="<?php echo (isset($_COOKIE["path"]) ? $_COOKIE["path"] : (substr($options["download_dir"], 0, 6) != "ftp://" ? realpath(DOWNLOAD_DIR) : $options["download_dir"])); ?>" <?php echo ($options["download_dir_is_changeable"] ? '':'readonly');?> />
</td></tr>
</table>
</td>
</tr>
<?php
}
?>

<tr>
<td><label><input type="checkbox" name="savesettings" id="savesettings"<?php echo isset($_COOKIE["savesettings"]) ? ' checked="checked"' : ''; ?> onclick="javascript:var displ=this.checked?'':'none';document.getElementById('clearsettings').style.display=displ;" />&nbsp;<?php echo $L->say['save_sett'];?></label>
<table id="clearsettings"<?php echo isset($_COOKIE["savesettings"]) ? "" : " style=\"display: none;\""; ?>>
<tr><td>
<a href="javascript:;" onclick="clearSettings();"><?php echo $L->say['clear_sett'];?></a></td></tr>
</table></td>
</tr>

<tr>
<td><br />
<small class='subtitle'><?php echo $L->say['plugin_opt'];?></small><hr />
<label><input type="checkbox" name="dis_plug" id="dis_plug" />&nbsp;<small><?php echo $L->say['plugin_disable'];?></small></label>
</td>
</tr>

<tr>
<td>
<label><input type="checkbox" name="ytube_mp4" id="ytube_mp4" />&nbsp;<small><?php echo $L->say['plugin_youtube'];?></small></label>
</td>
</tr>

<tr>
<td>
<label><input type="checkbox" name="df_acc" id="df_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('dfblock').style.display=displ;"<?php if(isset($premium_acc['depositfiles_com']['cookie'])) print ' checked="checked"'; ?> />&nbsp;<small><?php echo $L->say['plugin_depost'];?></small></label>
<table width="150" id="dfblock" style="display: none;">
<tr><td><?php echo $L->say['_autolog'];?></td><td><input type="text" name="df_cookie" id="df_cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="hf_acc" id="hf_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('hfblock').style.display=displ;"<?php if(isset($premium_acc['depositfiles_com']['cookie'])) print ' checked="checked"'; ?> />&nbsp;<small><?php echo $L->say['plugin_hotfile'];?></small></label>
<table width="150" id="hfblock" style="display: none;">
<tr><td><?php echo $L->say['_auth'];?></td><td><input type="text" name="hf_cookie" id="hf_cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="net_acc" id="net_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('netblock').style.display=displ;"<?php if(isset($premium_acc['netload_in']['cookie'])) print ' checked="checked"'; ?> />&nbsp;<small><?php echo $L->say['plugin_netl'];?></small></label>
<table width="150" id="netblock" style="display: none;">
<tr><td><?php echo $L->say['_cookie_user'];?></td><td><input type="text" name="net_cookie" id="net_cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="rs_acc" id="rs_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('rsblock').style.display=displ;"<?php if(isset($premium_acc['rapidshare_com']['cookie'])) print ' checked="checked"'; ?> />&nbsp;<small><?php echo $L->say['plugin_rs'];?></small></label>
<table width="150" id="rsblock" style="display: none;">
<tr><td><?php echo $L->say['_enc'];?></td><td><input type="text" name="rs_cookie" id="rs_cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="ul_acc" id="ul_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('ulblock').style.display=displ;"<?php if(isset($premium_acc['uploaded_to']['cookie'])) print ' checked="checked"'; ?> />&nbsp;<small><?php echo $L->say['plugin_ul'];?></small></label>
<table width="150" id="ulblock" style="display: none;">
<tr><td><?php echo $L->say['_login'];?></td><td><input type="text" name="ul_cookie" id="ul_cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="upl_acc" id="upl_acc" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('uplblock').style.display=displ;"<?php if(isset($premium_acc['uploading_com']['cookie'])) print ' checked="checked"'; ?> />&nbsp;<small><?php echo $L->say['plugin_upl'];?></small></label>
<table width="150" id="uplblock" style="display: none;">
<tr><td><?php echo $L->say['_rem_user'];?></td><td><input type="text" name="upl_cookie" id="upl_cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>
<tr>
<td>
<label><input type="checkbox" name="cookieuse" id="cookieuse" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('cookieblock').style.display=displ;" />&nbsp;<small><?php echo $L->say['plugin_cookie'];?></small></label>
<table width="150" id="cookieblock" style="display: none;">
<tr><td><?php echo $L->say['_cookie'];?></td><td><input type="text" name="cookie" size="45" value="" /></td></tr>
</table>
</td>
</tr>

<tr>
<td>
<label><input type="checkbox" name="vBulletin_plug" id="vBulletin_plug" />&nbsp;<small><?php echo $L->say['plugin_buletin'];?></small></label>
</td>
</tr>

</table>

</td>
</tr>
</tbody>
</table>
</form>
<script type="text/javascript">
/* <![CDATA[ */
function set_rlxmode(){
  var d = document; var mydate=new Date(); var cklive=eval(1000*3600*12);
  var id = 'rl_ajax'; var dirpath = location.pathname; var lastslash = location.pathname.lastIndexOf("/");
  dirpath = dirpath.substring(0,lastslash+1);
  mydate.setTime(eval(mydate.getTime()+cklive));
  d.cookie=id+"="+"<?php echo ($userck_std_mode ? "1" : "0");?>; path="+dirpath+"; expires="+mydate.toGMTString()+"; ";
}; set_rlxmode();
/* ]]> */
</script>

<table class="hide-table" id="tb3" cellspacing="5" width="100%">
<?php
//_create_list();

if(isset($_POST["act"])){
 echo '<tr><td align="center">';
 require_once(CLASS_DIR."options.php");
 echo '</td></tr>';
}
//$morethanone = (!isset($list["files"]["totalfile"]) ? count($list)>1 : $list["files"]["totalfile"]>1);
?>
<tr>
<td align="center" width="100%">
<form action="" name="flist" method="post">
<span id="topnavi">
<a href="javascript:;" onclick="setCheckboxes(1);"><?php echo $L->say['chk_all'];?></a>&nbsp;|&nbsp;
<a href="javascript:;" onclick="setCheckboxes(0);"><?php echo $L->say['chk_unchk'];?></a>&nbsp;|&nbsp;
<a href="javascript:;" onclick="setCheckboxes(2);"><?php echo $L->say['chk_invert'];?></a>&nbsp;|&nbsp;
<a href="javascript:;" onclick="flist_match_hide();"><?php echo $L->say['chk_txt_matches'];?></a>&nbsp;|&nbsp;
</span>
<a href="javascript:;" onclick='<?php echo (!$ajax_serverfiles? 'urlload()' : 'go_gLoad(150)')?>;'><?php echo $L->say['_sfrefresh'];?></a>

<?php
  if ($options["show_all"] === true) {
  if(!$list) unset($Path);
  ?>&nbsp;|&nbsp;<a href="javascript:;" onclick="<?php echo (!$ajax_serverfiles? 'showAll()' : 'showAll2()')?>" id="showall"><?php echo $L->say['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1) {
  document.write("<?php echo $L->say['_downloaded'];?>");
}else {
  document.write("<?php echo $L->say['_everything'];?>");
}
</script></a>
<?php
   }else{
     if(!$list) echo '<script type="text/javascript">deleteCookie("showAll");</script>';
   }
?>
<div id="flist_match_hitems" style="display:none;" class="acthistory_prompt">
<input type="text" title="<?php echo $L->say['chk_txt_matches'];?>" size="20" id="flist_match_search" onkeypress="javascript:if(event.keyCode==13){return flist_match(); }" />
<input type="image" title="<?php echo $L->say['go_match'];?>" style="vertical-align:bottom;padding-bottom:1px;" src="<?php echo ROOT_URL.STATIC_DIR;?>images/btn_search.png" onclick="return flist_match(); return false;" />&nbsp;
<a href="javascript:;" onclick="flist_match_hide();"><small><?php echo $L->say['close'];?></small></a>
<div></div><span>
  <label><input type="checkbox" id="flist_match_ins" /><?php echo $L->say['match_csensitive'];?></label>
  <label><input type="checkbox" id="flist_match_hide" checked="checked" /><?php echo $L->say['match_hideunmatch'];?></label>
 </span>
</div>
<div class="dseparator" style="height:12px"></div>
<?php
if($ajax_serverfiles){
?>
  <span id="tablefilewaiting" style="position:absolute;margin:-10px 0 0 30px;"></span>
  <div id="tablefilescontent" style="padding-top:5px;"></div>

<?php
} //--end ajax_serverfiles

else
{
  $feat_ajax["ajax_delete"] = (!$options["disable_to"]["act_delete"] ? '1' : '0');

// Show List file in table
if($list)
  {
?>
<div id="parentcontainer" style="padding-top:10px;"><div style="text-align:center; margin-top:-10px;"><a href="javascript:;" onclick="d=document; dcnt=d.getElementById('flcontainer'); ddcnt=d.getElementById('divcontainer'); if(dcnt) {dcnt.style.overflow='visible';d.getElementById('parentcontainer').innerHTML=ddcnt.innerHTML;};try{gosetfacebook();waitandload();}catch(e){};">release</a></div>

<div id="divcontainer" style="width:100%; overflow:auto;">
<table id="intbl" cellpadding="2" cellspacing="1" width="100%" class="filelist sortable">
<thead style="width:100%; white-space:nowrap;">
<tr valign="bottom" align="center" class="filelist_title">
<td class="sorttable_nosort">
<b class="sorttable_nosort">&nbsp;</b>
</td>
<td>
<?php if(!$options["disable_action"]) {
	echo renderActions();
}
?>
<b><?php
$total_files = (isset($list["files"]["totalfile"]) ? $list["files"]["totalfile"] : count($list));
echo ($total_files>0 ? "(".$total_files.' file'.($total_files>1 ? 's' : '').') &nbsp;' : "") . $L->say['tabel_name'];?>

</b>
</td>
<td><b><?php echo $L->say['tabel_sz'];?></b></td>
<?php
echo
  (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]==1 && $options["show_column_sfile"]["md5"] ? '<td><b>' . 'MD5' . '</b></td>':'').
  (!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE['showAll']!=1) ? ($options["show_column_sfile"]["downloadlink"] ? '<td><b>'.$L->say['tabel_dl'].'</b></td>':'').
  ($options["show_column_sfile"]["comments"] ? '<td><b>'.$L->say['tabel_cmt'].'</b></td>' : '') : '').
  ($options["show_column_sfile"]["date"] ? '<td><b>'.$L->say['tabel_dt'].'</b></td>' : '').
  ($options["show_column_sfile"]["age"] ? '<td><b class="sorttable_nosort">'.$L->say['tabel_age'].'</b></td>' : '').
  ($feat_ajax["ajax_delete"]=='1' ? '<td><b class="sorttable_nosort">'.$L->say['act_del'].'</b></td>' : '')
?>
</tr>
</thead>
<tbody id='flcontainer' style='width:100%; height:<?php echo (count($list)<13? "100%" : "280px");?>; white-space:nowrap; overflow:auto;'>

<?php
//Initiate Counter load time
  $tabletimer = new timer();
  $tabletimer->timer();

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
?>
<tr id="brs<?php echo $total_files;?>" class="rowlist" onmousedown="clk('cek_<?php echo $total_files;?>', this);" onmouseout="if(document.getElementById('cek_<?php echo $total_files;?>').checked){this.className='rowlist_checked';} else{this.className='rowlist';}" align="center" title="<?php echo xmlentities(basename($file["name"]));?>">
 <td>
  <?php echo ((!$options["disable_to"]["act_rename"] && $ajax_rename) ? '<div id="rename_container" onmouseup="ren(this, \'fn_'.$total_files.'\', \''.$file["date"].'\')"><div class="rename_es"></div></div>':'');?>
  <input type="checkbox" name="files[]" id="cek_<?php echo $total_files;?>" value="<?php echo $file["date"];?>" onclick="clk('cek_<?php echo $total_files;?>')" />
  </td>
  <td id="fn_<?php echo $total_files;?>"><b><?php echo link_for_file($file["name"]);?></b></td>
 <td><?php echo str_replace(" ", "&nbsp;",$file["size"]);?></td>
 <?php
 $flink = (isset($file["link"])?$file["link"]:"#");
 echo
   (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]==1 && $options["show_column_sfile"]["md5"] ? "<td class=\"txtmd5\">{$file["md5"]}</td>":null).
   (!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"]!=1) ? ($options["show_column_sfile"]["downloadlink"] ? "<td title=\"{$flink}\">".($flink ? "<a href=\"{$flink}\">".trimlink($flink)."</a>" : "").'</td>':'').
	($options["show_column_sfile"]["comments"] ? '<td>'.(isset($file["comment"]) ? str_replace("\\r\\n", "<br />", $file["comment"]) : "").'</td>':''):'').
   ($options["show_column_sfile"]["date"] ? '<td>'.str_replace(" ","&nbsp;",date("d.m.Y H:i:s", $file["date"])).'</td>':'').
   ($options["show_column_sfile"]["age"] ? '<td><small>'.$file["age"].'</small></td>':'').
   ($feat_ajax["ajax_delete"]=="1" ? "<td><a id=\"dL-{$total_files}\" href=\"".ROOT_URL."del.php?d={$_fdkey}&amp;lineid={$total_files}\" rel=\"facebox\"> <img src=\"".ROOT_URL.IMAGE_DIR."rldel.png\" alt=\"X\" /></a></td>" : "")
 ?>
</tr>

<?php
	} // end- if file exist
   } //end foreach

	$n_column = 0;
	foreach($options["show_column_sfile"] as $cocol => $colval){
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
  $totaltime2 = $tabletimer->stop();;
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
<?php echo $L->sprintf( $L->say["loaded_in"], $total_files, ($total_files>1?'s':''), $totaltime2 );?>
<script type="text/javascript">gosetfacebook();</script>

<?php
 } //-- show files, $list not empty

 else{ // Not Files Found

  echo "<center><span id=\"nofiles\">{$L->say['tabel_no_file']}</span></center>";

 }
} //--end old method listing server files
?>
</form>

<br /><div class="acthistory_prompt rlxmode" style="background-color:transparent;font-size:85%;margin:3px 15px 3px 15px;">RL x-mode:&nbsp;
<?php echo ($userck_std_mode ? "<b>standard</b>&nbsp;|&nbsp;<a href='".ROOT_URL.basename($PHP_SELF)."?ajax=off&amp;ausv={$usrajxnuid}' title='Disable Ajax'>basic HTML</a>" : "<a href='".ROOT_URL.basename($PHP_SELF)."?ajax=on&amp;ausv={$usrajxnuid}' title='Enable Ajax'>standard</a>&nbsp;|&nbsp;<b>basic HTML</b>");
?></div>

</td>
</tr>
</table>

<form name="frmTBLoad" id="frmTBLoad" action="">
   <input type="hidden" id="rfr" name="rfr" value="TBLoad" />
</form>

<?php
if($ajax_serverfiles) {
	echo '<script type="text/javascript">go_gLoad(10);</script>';
}
?>

<!--xxx-->
<table class="hide-table" id="tb4" cellspacing="5" width="100%">
<tbody>
<tr><td align="center" width="100%">
	<div style="text-align:center">
	<div align="center" style="cursor:pointer;" onclick='d=document; cur=d.getElementById("lnksupport").style; if(cur.display==""){cur.display="none";}else{cur.display="";};'><span id="ww" style="font-weight:bold;width:100%;"><?php echo $L->say['work_with']?></span></div>
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
	<br /><b>Kills</b><br />
	Anonym.to | Linkbucks.com | Lix.in<br />
	Rapidshare.com Folders | Usercash.com</div>

	<form name="frmlnkchk" action="javascript:checkLink();" method="post">

	<div align="center" style="padding-top:6px;">
	<textarea rows="6" id="links" name="links" style='white-space:nowrap;' class="redtxtarea"></textarea><br />
	<label style="float:left;"><input type="checkbox" value="lnkcurl" name="lcurl" id="lcurl"<?php echo (($ch_curl == 1)?' checked="checked"':' disabled="disabled"');?> />
	<span><?php echo $L->say['curl_stat']?>&nbsp;</span><?php echo (($ch_curl == 1) ? '<b class="g">'.$L->say['_on'].'</b>':'<b class="b">'.$L->say['_off'].'</b>');?></label>
	<label style="float:right;" onclick="chklinkOnly(document.getElementById('d').checked);"><input type="checkbox" value="d" name="d" id="d" /><?php echo $L->say['link_only']?></label>
	<div style="padding-top:20px;"></div>
	<input type="submit" value="Check Links" id="btnlnkchk" name="submit" />

	</div>
	</form>
</td></tr>
</table></center>

	<span id="lnkwait" style="display:none;background-color:#800000;text-align:center;"><img src="<?php echo ROOT_URL.IMAGE_DIR;?>fbload.gif" border="1" alt="<?php echo $L->say['loading_pwait']?>" />&nbsp;&nbsp;<b class="o"><?php echo $L->say['loading_pwait']?></b></span>
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
   <div><b><?php echo $L->say['rs_acc_chk'];?></b><br /><small>[by: reiluke; <?php echo $L->say['modded'];?> -&gt; &#73;&#100;&#111;&#101;&#110;&#107;]</small><br />
 	<form name="myform" id="myform" method="post" action="https://ssl.rapidshare.com/cgi-bin/premiumzone.cgi" target=_blank>
	<input type="hidden" name="login" id="login" />
	<input type="hidden" name="username" id="username" />
	<input type="hidden" name="password" id="password" />
	</form>
<script type="text/javascript">
/* <![CDATA[ */
function doAccChk(){
	ajxtmr = setTimeout("get('accounts','waiting','showresults','btnchkacc')", 300);
}
/* ]]> */
</script>
	<form action="javascript:doAccChk();" name="acctform" id="acctform">
   <textarea rows="4" id="accounts" class="redtxtarea"></textarea>
     <br /><small class="g"><?php echo $L->say['un_pass'];?></small>

	<label style="float:left;"><input type="checkbox" name="acccurl" id="acccurl"<?php echo (($ch_curl == 1)?' checked=checked':' disabled="disabled"');?> />
	<span><?php echo $L->say['curl_stat']?>&nbsp;</span><?php echo (($ch_curl == 1) ? '<b class="g">'.$L->say['_on'].'</b>':'<b class="b">'.$L->say['_off'].'</b>');?></label>
	<label style="float:left;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="accapi" id="accapi" />
	<span><?php echo "Use rsAPI"?>&nbsp;</span></label>


	<div style="padding-top:15px;"></div>
	<input type="submit" name="button" id="btnchkacc" value="Check" onclick="document.getElementById('waiting').style.display='';" <?php echo ($ch_curl == 1)?'':'disabled';?> />
     </form>
   </div>
</td></tr>
</table></center>

 <div><br />
  <div id="waiting" style="display:none;"></div>
  <div id="showresults"></div>
 </div>

 </div>
</td>
</tr>
</tbody>
</table>

</td>
</tr>
</tbody>
</table>
<?php
if(isset($_REQUEST["act"]) || isset($_REQUEST["ajax"])){
 echo "<script type=\"text/javascript\">switchCell(3);</script>";
}else{
 echo "<script type=\"text/javascript\">switchCell(1);</script>";
}
?><!--AccCheXerr-->

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
<form action="javascript:doStatacc();" name="poiuy" id="poiuy">
  <input type="hidden" id="staccounts" value="lol" />
</form>
</div>
<script type="text/javascript">
var show = 0;
var show2 = 0;
</script>
<div align="center">
<?php
$CNfo = "<hr \/>";
if($showAccRsStatus){ // this mean == ($ch_curl == 1 && $exist_accrs && $options["premix_status"])
 $CNfo.= "<div id=\"refresh\" style=\"float:right;\">&nbsp;<a href=\"javascript:;\" title=\"{$L->say['_refresh']} acc status\" onclick=\"stacc();this.blur();\"><small>{$L->say['_refresh']}<\/small><\/a><\/div><div id=\"accshowresults\" style=\"display:none;\"><\/div><div id=\"accwaiting\" align=\"center\" style=\"display:none;\" title=\"".$L->say['loading_pwait']."\"><\/div><hr \/>";
}
if($options["auto_del_time"]>0){
	$autodel_unit_time = $L->say['hours'];
	if($options["auto_del_time"]<1) {
	  $options["auto_del_time"] = floor(60 * $options["auto_del_time"]);
      $autodel_unit_time = $L->say['minutes'];
	}
	$CNfo.= "<span class=\"c\">*{$L->say['_autodel']}:&nbsp;<b class=\"g\">{$options["auto_del_time"]}<\/b>&nbsp;{$autodel_unit_time}<\/span>";
}
if($options["limitsize"]>0){
  $CNfo.= "<br \/><span class=\"c\">*{$L->say['_maxfilesize']}:&nbsp;<b class=\"s\">{$options["limitsize"]}<\/b>&nbsp;MB<\/span>";
}
if($options["lowlimitsize"]>0)	{
  $CNfo.= "<br \/><span class=\"c\">*{$L->say['_minfilesize']}:&nbsp;<b class=\"s\">{$options["lowlimitsize"]}<\/b>&nbsp;MB<\/span>";
}
if($options["downloadLimitbyip"] && $options["downloadsPerIP"] > 0){
  $CNfo.= "<br \/><span class=\"c\">*{$L->say['_limitip']}:&nbsp;<b class=\"s\">{$options["downloadsPerIP"]}<\/b> per <b class=\"s\">{$options["downloadDelayPerIP"]}<\/b>s<\/span>";
}
//if($options["pointboost"]>0){
//  $CNfo.= "<br \/><span class=\"c\">*{$L->say['_pointboost']}:&nbsp;<b class=\"s\">{$L->say['_on']}<\/b><\/span>";
//}
if(!empty($options["add_ext_5city"])){
  $CNfo.= "<br \/><span class=\"c\">*{$L->say['_fakeext']}:&nbsp;<b><a style=\"color:red\" href=\"javascript:;\" title=\"{$L->say['_fakeext_desc']}\">{$options["add_ext_5city"]}<\/a><\/b><\/span>";
}
if(strstr($CNfo,"<span")) $CNfo.= "<hr \/>";

echo "<script type=\"text/javascript\">\nfunction showaccdiv(){\n  embedaccnfo(document.getElementById('plusmin'));\n}
\nfunction embedaccnfo(Obj){\ntxt=\"&nbsp;embed&nbsp;acc&nbsp;\";cntent=document.getElementById(\"otheracc\");if(cntent.style.display==\"none\"){cntent.style.display=\"\";Obj.innerHTML=txt+\"&#8212;&nbsp;\";}else{cntent.style.display=\"none\";Obj.innerHTML=txt+\"&#43;&nbsp;\";}}</script>\n";
if(isset($premium_acc) && isset($acc_txt) && $acc_txt!=''){
 $CNfo.="<div class=\"acthistory_prompt\" style=\"text-align:left;margin-top:0px;padding:0;position:absolute;\" onmouseout=\"showaccdiv()\" onmouseover=\"showaccdiv()\"> <small><label> <span id=\"plusmin\"> &nbsp;embed&nbsp;acc&nbsp;&#43;&nbsp;<\/span><\/label><div id=\"otheracc\" style=\"display:none; padding:1px 10px 3px 8px; line-height:10px; text-align:left;margin-top:0px;color:#fff;background:#800000;\">";
 $CNfo.= $acc_txt;
 $CNfo.="<\/div><\/small><\/div>";
}
//--end acc NFO

if(strstr($CNfo,"<span")){
  echo "<script type=\"text/javascript\">
  var divnfo = document.getElementById('nfoconfig');
  divnfo.innerHTML = '".$CNfo."';\n
  divnfo.style.display='';\n";
  if($showAccRsStatus) echo 'doStatacc();';
  echo "\n</script>";
}
?><hr />
</div>
<?php
if (isset($_GET["act"]) && ($_GET["act"] == 'unrar_go') && !$options['disable_to']['act_unrar']) {
	require_once(CLASS_DIR."options/unrar.php");
	unrar_go_go();
} elseif (isset($_GET["act"]) && ($_GET["act"] == 'rar_go') && !$options['disable_to']['act_rar']) {
	require_once(CLASS_DIR."options/rar.php");
	rar_go_go();
}
?>