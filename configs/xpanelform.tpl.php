<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit;
}

	if($ajax_serverfiles){
?>
<script type="text/javascript" src="<?php echo STATIC_DIR;?>ajax.js"></script>
<?php
	}

	switch($task) {

	// Advanced Editor Mode
		case 'editor':

?>
<p><b><span id='nv1' style='background-color:#840000;color:yellow;'>&nbsp;Editor&nbsp;</span></b></p>
<script type="text/javascript">var dId;</script>


<table id="entered" width="100%">
<tr> <td width="100%" valign="top">

<div id='editor' style="display:block;">
<center>
<table border="0" style="width:710px;">
<tbody>
<tr><td>
<?php
		if ($_REQUEST['step'] == 'fileselect') {
		  $sfile = $_POST['fileselect'] ;
		  $r = file_get_contents($sfile);
?>
<div align="left" class="a">:: Editor Mode ::</div>
<form action="<?php echo basename($PHP_SELF);?>?mode=editor" method="post">
<textarea class="redtxtarea" style="width:100%;font-size:12px;" name="edit_text" cols="122" rows="28" width="100%"><?php echo htmlspecialchars($r);?></textarea><br />
<div class="warn_alert"><span class='b'>Warning..!</span> Any misconfiguration on this config file may bring <b class="a">XPanel</b>&nbsp;and <b class="a">RapidLeech</b> not working.<br /><b class="a">You know wot you're doin here, don't you ?!</b></div>
<div align="center" style="padding-top:10px;">
<input type="hidden" name="file" value="<?php echo $sfile;?>" />
<input type="hidden" name="setupsave" value="1" />
<input type="submit" name="submit" value="Save" />&nbsp;&nbsp;&nbsp;
<input type="reset" value="Reset" />&nbsp;&nbsp;&nbsp;
<input type="button" onclick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=\'';?>" value="Back to Xpanel" /></div>
</form>
<?php
		} else {
?>
<form action="<?php echo basename($PHP_SELF);?>?mode=editor" method="post">
<div align="center" style="padding-top:10px;">
<select name='fileselect'>
<option value="<?php echo FILECONFIG;?>"><?php echo basename(FILECONFIG);?></option>
<option value="<?php echo FILEACCOUNT;?>"><?php echo basename(FILEACCOUNT);?></option>
</select><br /><br />
<input type="hidden" name="step" value="fileselect" />
<input type="submit" name="submit" value="Continue " />&nbsp;&nbsp;&nbsp;
<input type="button" onclick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=\'';?>" value="Back to Xpanel" />
</div>
</form>
<?php
		}
?>
</td></tr>
</tbody>
</table></center>
</div>


</td></tr>
</table>


<?php
	  break; //end -case 'editor':




	// View Log Mode
	  case 'viewlog':
		$fnlog = LOG_PHP;
		$fnsize = @filesize($fnlog);
		$showfnlog = false; // Are you gonna show the filename here??
?>
<form name="dlog" id="dlog" action="javascript:get('deletelog','logwaiting','logshowresults','');" >
   <input type="hidden" id="deletelog" value="<?php echo base64_encode($cp_login[0].":".$cp_login[1]);?>" />
</form>

<?php if($fnsize>3000){
 echo '<input type="button" id="topback" onclick="location.href=\'./'.basename($PHP_SELF).'?mode=\'" value="Back to Xpanel" />';}
?>

&nbsp;&nbsp;<span>Log Filesize<?php echo ($showfnlog ? " <b>[".$fnlog."]</b>&nbsp;":"");?>:&nbsp;<span id="cur_log"><b class="g"><span id="logshowresults" style="float:none;"><?php echo bytesToKbOrMbOrGb($fnsize);?></span>&nbsp;&nbsp;<a href="javascript:;" id="resetlog" onclick="<?php echo ($ajax_serverfiles?'if(confirm(\'This will reset Log-System, proceed?\')){flushlog();};' : 'alert(\'Ajax Disabled\')');?>" style="display:none;"><small>FLUSH LOG-FILE</small></a></b></span></span><div id="logwaiting" style="display:none;"></div>
<div style="padding-top:15px;">

<table id="vlog" width="100%">
<tr><td><tbody id="vlogtd">
<?php
	 $fnlog = LOG_PHP;
	 if(@file_exists($fnlog)) require_once($fnlog);
	 else echo "<br /><center><b class='r'>$fnlog</b> not found!</center><br />";
?>
</tbody>
</td></tr>
</table>
</div>
<div><input type="button" onclick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=\'';?>" value="Back to Xpanel" />
<?php if($fnsize>3000){ ?>
 &nbsp;&nbsp;<a href="javascript:;" onclick="scroll(0,0);"><b>TOP</b></a>
<?php } ?>
</div>
<script type="text/javascript">
<?php if($ajax_serverfiles){
?>
/* <![CDATA[ */
function flushlog(){
  d.dlog.submit();
  $('#isilog').hide();
  if(typeof($('#topback')) !== 'null'){ $('#topback').hide();}
}
<?php } ?>
$('#resetlog').show();
/* ]]> */
</script>
<?php
		break; // end -case 'viewlog':




//=================================
// XPanel Mode
//=================================
	default:
?>
<form name="poiuy" id="poiuy" action="javascript:get('staccounts','accwaiting','accshowresults','');" >
   <input type="hidden" id="staccounts" value="lol" />
</form>
<form name="trew" id="trew" action="javascript:get('posttraffic','trafwaiting','trafshowresults','');" >
   <input type="hidden" id="posttraffic" value="<?php echo base64_encode($cp_login[0].":".$cp_login[1]);?>" />
</form>
<form method="post" action="<?php echo basename($PHP_SELF);?>">
<table width="100%">
<tbody><tr><td class="xpTitleColoum" width="100px">Option</td><td class="xpTitleColoum">Description</td>
</tr>
</tbody>
</table>


<table width="100%">
<tbody>



<!-- ==========================START LOGIN-CONFIG -->
<!-- =============================================-->
<tr><td class="tdacc">
<h1>LOGIN CONFIG</h1></td><td>&nbsp;</td></tr>
<tr><td class="tdlog">
<input type="checkbox" id="opt_allowcpanel" name="opt_allowcpanel" value="1" disabled="disabled" checked="checked" /><label for="opt_allowcpanel">&nbsp;Login Cpanel</label></td>
<td class="tdacc"><div id="cpbtnChange"><input type="button" value="Change" onclick="var d=document; d.getElementById('cpbtnChange').style.display='none';d.getElementById('newloginCp').style.display='';d.getElementById('cplHash').value='1';d.getElementById('newusercp').focus();" /><span id="isdefcp"><?php if($defaultcp)print "&nbsp;<small class='b'>Default Login CP Detected (admin:admin)</small>&nbsp;";?></span></div>
<div id="newloginCp" style="display:none;">
<label for="newusercp">user: </label><input type="text" id="newusercp" name="usercp[]" />
<label for="newpascp">pass: </label><input type="password" id="newpascp" name="passcp[]" />
<input type="button" id="cpcancel" value="Cancel" onclick="doCancel('newusercp','newpascp','waitc','cplHash','cpbtnChange','newloginCp')" />
<span id="waitc"></span></div></td>
</tr>

<tr>
<td class="tdlog"><input type="checkbox" id="opt_login" name="opt_login" value="1" onclick="clk(this,'rlbtnChange','lip',0); document.getElementById('loginrl').style.display='none';var displ=this.checked?'':'none';document.getElementById('logincgi').style.display=displ;"<?php $logindontdisplay=true;if($showpostn){if(isset($_POST['opt_login']) && $_POST['opt_login']=='1'){echo ' checked="checked"';$logindontdisplay=false;}}elseif($options['login']){echo ' checked="checked"';$logindontdisplay=false;}?> /><label for="opt_login">&nbsp;Login index page</label></td>
<td class="tdacc">
<div id="rlbtnChange"<?php if($logindontdisplay) echo $styledisplay;?>><input type="button" value="Change" id="loginchange" onclick="var d=document; d.getElementById('loginrl').style.display=''; d.getElementById('rlbtnChange').style.display='none';d.getElementById('rlauthHash').value='1';d.getElementById('users').focus();" /></div>
<div id="loginrl" style="display:none;">
<label for="users">user: </label><input type="text" id="users" name="users[]" />
<label for="pass">pass: </label><input type="password" id="pass" name="pass[]" />
<input type="button" id="chngsvn" onclick="doCancel('users','pass','lipu','rlauthHash','rlbtnChange','loginrl');" value="Cancel" />
<span id="lipu"></span></div>
<span id="lip" <?php if(!$logindontdisplay) echo $styledisplay; ?>>RL-Login Authorization Mode is <b class="b">OFF</b></span></td>
</tr>
<tr id="logincgi" <?php if($logindontdisplay) echo $styledisplay;?>>
<td class="tdlog"><input type="checkbox" id="opt_login_cgi" name="opt_login_cgi" value="1"<?php if($showpostn){if(isset($_POST['opt_login_cgi']) && $_POST['opt_login_cgi']=='1'){echo ' checked="checked"';}}elseif($options['login_cgi']){echo ' checked="checked"';}?> /><label for="opt_login_cgi">&nbsp;Enable CGI authorization fix</label></td>
<td class="tdacc">True - Will try to workaround CGI authorization</td>
</tr>
<!-- ==========================END LOGIN-CONFIG -->

<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>

<!-- ==========================START LIMITATION-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trlimit" onclick="trclick(this);">
<td colspan="2" class="tdacc"><h1><span id="nod_trlimit"></span>LIMITATION</h1>
</td></tr>
<tbody id="bd_trlimit" <?php echo (isset($_COOKIE["trlimit"])?($_COOKIE["trlimit"]==1?$styledisplay:''):$styledisplay);?>>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_limitbyIP" name="opt_limitbyIP" value="1" /><label for="opt_limitbyIP">&nbsp;<b>Limit Premium Leeching per IP</b></label></td>
<td class="tdacc">This feature only enable if you have any <b class="r">Premium Account</b> in your RL.</td>
</tr>
<tr id="limitbyiplist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_maximum_free_downloads" name="opt_maximum_free_downloads" size="4" maxlength="4" value="" />&nbsp;<b class="g">Times</b> leeching per IP</div></li>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_delay_per_ip" name="opt_delay_per_ip" size="4" maxlength="3" value="" />&nbsp;<b class="g">Hours</b> delay per IP</div></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>How many times users can use your RL per IP</span></li>
<li class="ndot"><span>How long it will recycle allowable download per IP</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_downloadLimitbyIP" name="opt_downloadLimitbyIP" value="1" /><label for="opt_downloadLimitbyIP">&nbsp;<b>Limit Leeching by IP</b></label></td>
<td class="tdacc">This feature always enable although you don't use <b class="r">Premium Account</b></td>
</tr>
<tr id="downloadlimitbyiplist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_downloadsPerIP" name="opt_downloadsPerIP" size="4" maxlength="4" value="" />&nbsp;<b class="g">Times</b> leeching per IP</div></li>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_downloadDelayPerIP" name="opt_downloadDelayPerIP" size="4" maxlength="6" value="" />&nbsp;<b class="g">Seconds</b> delay per IP</div></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>How many times users can use your RL per IP</span></li>
<li class="ndot"><span>How long it will limit download per IP</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_limitbytraffic" name="opt_limitbytraffic" value="1" /><label for="opt_limitbytraffic">&nbsp;<b>Limit Traffic</b></label></td>
<td class="tdacc">This feature Limit Traffic in your RL</td>
</tr>
<?php
		require_once(CLASS_DIR . 'limit_traffic.class.php');
		$traffic = new traffics();
		$traffic->get();
?>
<tr id="limitbytrafficlist">
<td class="tdacc">
<script type="text/javascript" src="<?php echo STATIC_DIR;?>date.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_DIR;?>date.css" />
<div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><input id="opt_max_trafic" type="text" name="opt_max_trafic" size="5" value="" />&nbsp;<b class="g">MB</b> Quota Traffic</div></li>
<li class="ndot"><div style="padding-left:5px;"><div class="pngDate" onclick="displayDatePicker('opt_date_trafic', false, 'dmy', '/');"><img title="pick date" src="<?php echo STATIC_DIR;?>images/calendar.png" height="15">&nbsp;</div><input id="opt_date_trafic" name="opt_date_trafic" type="text" size="10" maxlength="10" value="" />&nbsp;<b class="g" title="Day/Month/Year">DMY</b> Expire Due-date</div></li>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_day_reset_trafic" name="opt_day_reset_trafic" size="5" maxlength="2" value="" />&nbsp;<b class="g">day<small>(s)</small></b> Reset Traffic</div></li>
</ul></div></td>
<td class="tdacc">
<div id="curtrafic" align="center" style="position:absolute;float:left;width:200px;padding-left:270px; margin-top:-10px;"><div id="trafwaiting" align="left" style="display:none;"></div></div>
<div class="div_right_ndot"><ul>
<li class="ndot"><span>Quota Traffic allowed. Currently traffic:&nbsp;<span id="cur_traffic"><b class="g" style="position:absolute;"><span id="trafshowresults"><?php echo bytesToKbOrMbOrGb($traffic->total);?></span>&nbsp;&nbsp;<a href="javascript:;" onclick="<?php echo ($ajax_serverfiles?'if(confirm(\'This will reset Current Traffic, proceed?\')){trafst();}' : 'alert(\'Ajax Disabled\');');?>;"><small>reset</small></a></b></span></span></li>
<li class="ndot"><span>The D-Day traffic quota will expired</span></li>
<li class="ndot"><span>Auto-Reset traffic. set to 0 or blank to disable.
<?php
	if ($options['day_reset_trafic'] > 0) {
		echo "<small class='g'>remain: " . sec2time($traffic->timeremain()) . "</small>";
	}
?>
</span></li></ul>
<?php if($ajax_serverfiles){
?>
<script type="text/javascript">function trafst() {document.trew.submit("posttraffic","trafwaiting","trafshowresults",""); }</script>
<?php } ?>
</div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_limited_edition" name="opt_limited_edition" value="1" /><label for="opt_limited_edition">&nbsp;<b>Limit Authorize by IP</b></label></td>
<td class="tdacc">Limit access to your RL by authorized IP only.</td>
</tr>
<tr id="limitededitionlist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_list_allow_ip" name="opt_list_allow_ip" value="" /> White List IP</div></li>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_list_baned_ip" name="opt_list_baned_ip" value="" /> Black Listed IP</div></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>White list IP. eg. <b class="g"><?php echo $visitors->userip;?></b>, 111.111.111.111, 255.*.*.*, // <span class="r">never blank this if you set limitation=true</span></span></li>
<li class="ndot"><span>Banned / Blacklist IP,!. eg. 111.111.111.111, 222.*.*.*, 212.212.212.*</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_limited_area" name="opt_limited_area" value="1" /><label for="opt_limited_area">&nbsp;<b>Limit Authorize by Country ID Area</b></label></td>
<td class="tdacc" id="showlimitarea">Limit access to your RL by Country ID. <span class='g'><b>classes/geoip.inc.php</b> OR <b>binary/geoip/GeoIP.dat</b> Not Found.</span></td>
</tr>
<tr id="limitedarealist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_allow_CID" name="opt_allow_CID" value="" /> White List CID</div></li>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_baned_CID" name="opt_baned_CID" value="" /> Black CID</div></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>White list Country ID, set blank mean all country is allowed. eg. 'ID', 'MY' // allow only Indonesia And Malaysia</span></li>
<li class="ndot"><span>Blacklist Country ID, set blank mean no country is banned. eg. 'US' // all country from US being banned.</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_limit_timework" name="opt_limit_timework" value="1" /><label for="opt_limit_timework">&nbsp;<b>Limit by Time</b></label></td>
<td class="tdacc">Limit access your RL by Time.</td>
</tr>
<tr id="limittimeworklist">
<td class="tdacc"><div style="padding-left:25px;">
<input type="text" size="9" id="opt_workstart" name="opt_workstart" value="" />&nbsp;-&nbsp;<input type="text" size="9" id="opt_workend" name="opt_workend" value="" /></div>
</td>
<td class="tdacc"><div style="padding-left:25px; margin-top: 0px;">
<span>Your RL Start &amp; End to Work. eg. 08:00:00 - 17:00:00</span>
</div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_limit_cpuload" name="opt_limit_cpuload" value="1" /><label for="opt_limit_cpuload">&nbsp;<b>Limit CPU load</b></label></td>
<td class="tdacc">Limit CPU load and task server job.</td>
</tr>
<tr id="limitcpuloadlist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_ServerLoadAllowed" name="opt_ServerLoadAllowed" size="5" maxlength="3" value="" />&nbsp;<b class="g">%</b> CPU load</div></li>
<li class="ndot"><div style="padding-left:5px;"><input type="text" id="opt_CpuLoadFormat" name="opt_CpuLoadFormat" size="7" readonly="readonly" value="" />&nbsp;<b class="g">Format</b> CPU load</div></li>
<li class="ndot"><input type="checkbox" id="opt_passthru_allowed" name="opt_passthru_allowed" value="1" disabled="disabled" />&nbsp;<b class="g">Passthru</b> allowed</li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>Maximum server load allowed. set to 0 or blank to disable</span></li>
<li class="ndot"><span>Value "load" for load format, "percent" for percent format.</span></li>
<li class="ndot"><span>Does your host allows passthru?</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_auto_del_time" name="opt_auto_del_time" maxlength="5" size="5" value="" />&nbsp;<b class="g">Minutes</b> auto delete</label></div></td>
<td class="tdacc">Auto delete file after leeching. set to 0 or blank to disable</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_maxlimitsize" name="opt_maxlimitsize" size="5" maxlength="4" value="" />&nbsp;<b class="g">MB</b> maximum file size</label></div></td>
<td class="tdacc">Limit transfering each file by <b class="o">maximum</b> filesize. set to 0 or blank to disable</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_minlimitsize" name="opt_minlimitsize" size="5" maxlength="4" value="" />&nbsp;<b class="g">MB</b> minimum file size</label></div></td>
<td class="tdacc">Limit transfering each file by <b class="o">minimum</b> filesize. set to 0 or blank to disable</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_storage_limit" name="opt_storage_limit" size="5" maxlength="6" value="" />&nbsp;<b class="g">MB</b> storage limit</label></div></td>
<td class="tdacc">Limit storage in Server Files. set to 0 or blank to disable</td>
</tr>
</tbody>
<!-- ==========================END LIMITATION CONFIG -->

<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>

<!-- ==========================START FILES-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trfiles" onclick="trclick(this);">
<td class="tdacc"><h1><span id="nod_trfiles"></span>FILES CONFIG</h1>
</td><td>&nbsp;</td></tr>
<tbody id="bd_trfiles" <?php echo (isset($_COOKIE["trfiles"])?($_COOKIE["trfiles"]==1?$styledisplay:''):$styledisplay);?>>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_download_dir_is_changeable" name="opt_download_dir_is_changeable" value="1" /><label for="opt_download_dir_is_changeable">&nbsp;Download Directory is changeable</label></td>
<td class="tdacc">Don't check this to disallow users to change the download directory!</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="opt_maysaveto" name="opt_maysaveto" value="1" /><label for="opt_maysaveto">&nbsp;Download Dir is changeable&nbsp;(audl.php)</label></td>
<td class="tdacc">Don't check this to disallow users to change the download directory in <b>audl.php</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_download_dir" name="opt_download_dir" value="" />&nbsp;Download directory</label></div></td>
<td class="tdacc">This is where the downloaded files are saved</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_rename_prefix" name="opt_rename_prefix" value="" />&nbsp;Rename prefix</label></div></td>
<td class="tdacc">Example: <b class="o">www.mysite.com</b> =&gt; <b class="o">www.mysite.com_</b><b class="g">file_name.rar</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_rename_suffix" name="opt_rename_suffix" value="" />&nbsp;Rename suffix</label></div></td>
<td class="tdacc">Example: <b class="o">www.mysite.com</b> =&gt; <b class="g">file_name</b><b class="o">_www.mysite.com</b><b class="g">.rar</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_add_ext_5city" name="opt_add_ext_5city" size="7" maxlength="7" value="" />&nbsp;Add filename extension</label></div></td>
<td class="tdacc">Example: <b class="o">ccpb</b> =&gt; <b class="g">file_name.rar</b><b class="o">.ccpb</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_forbidden_filetypes" name="opt_forbidden_filetypes" value="" />&nbsp;Forbidden file types</label></div></td>
<td class="tdacc">List forbidden file type that will be renamed or blocked, you can add that and separated with "<b class="r">,</b>"</td>
</tr>
<tr id="forbiddenfiletypesblocklist">
<td class="tdacc"><div style="padding-left:5px;"><label><input type="text" id="opt_rename_these_filetypes_to" name="opt_rename_these_filetypes_to" size="3" maxlength="3" value="" />&nbsp;Extension preventive</label></div></td>
<td class="tdacc">To prevent users to rename the file with forbidden extension (.php, .asp, .cgi, etc)</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_forbidden_filetypes_block" name="opt_forbidden_filetypes_block" value="1" />&nbsp;Block download of forbidden file types</label></td>
<td class="tdacc"><b>False</b> - rename forbidden file types, <b>True</b> - completely block them</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="opt_rename_underscore" name="opt_rename_underscore" value="1" /><label for="opt_rename_underscore">&nbsp;Replace spaces for underscore in file names</label></td>
<td class="tdacc"><b>True</b> replace spaces for underscores in file names</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="opt_check_these_before_unzipping" name="opt_check_these_before_unzipping" value="1" /><label for="opt_check_these_before_unzipping">&nbsp;Check forbidden file in archive</label></td>
<td class="tdacc"><b class="o">True</b> Don't allow extraction/creation of these filetypes from file actions</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="opt_show_all" name="opt_show_all" value="1" /><label for="opt_show_all">&nbsp;Show all files</label></td>
<td class="tdacc">To show all files in the catalog</td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_disable_action" name="opt_disable_action" value="1" /><label for="opt_disable_action">&nbsp;Disable Actions files.<b>Checked: Disable All</b></label></td>
<td class="tdacc">Disable upload, split, zip, rename, delete, etc.</td>
</tr>
<tr id="disableactionlist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_upload" name="opt_disable_to[act_upload]" value="1" />&nbsp;Disable Upload</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_ftp" name="opt_disable_to[act_ftp]" value="1" />&nbsp;Disable Ftp</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_mail" name="opt_disable_to[act_mail]" value="1" />&nbsp;Disable Email</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_boxes" name="opt_disable_to[act_boxes]" value="1" />&nbsp;Disable Mass Submits</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_split" name="opt_disable_to[act_split]" value="1" />&nbsp;Disable Split</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_merge" name="opt_disable_to[act_merge]" value="1" />&nbsp;Disable Merge</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_md5" name="opt_disable_to[act_md5]" value="1" />&nbsp;Disable MD5 / Changer</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_archive_compression" name="opt_disable_to[act_archive_compression]" value="1" />&nbsp;Disable compression(tar, zip, rar)</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_pack" name="opt_disable_to[act_pack]" value="1" />&nbsp;Disable Pack</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_zip" name="opt_disable_to[act_zip]" value="1" />&nbsp;Disable Zip</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_unzip" name="opt_disable_to[act_unzip]" value="1" />&nbsp;Disable UnZip</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_rar" name="opt_disable_to[act_rar]" value="1" />&nbsp;Disable RAR Files</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_unrar" name="opt_disable_to[act_unrar]" value="1" />&nbsp;Disable UnRAR Files</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_rename" name="opt_disable_to[act_rename]" value="1" />&nbsp;Disable Rename</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_mrename" name="opt_disable_to[act_mrename]" value="1" />&nbsp;Disable Mass-Rename</li>
<li class="ndot"><input type="checkbox" id="opt_disable_to_act_delete" name="opt_disable_to[act_delete]" value="1" />&nbsp;Disable Delete</li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li>Disallow users upload files</li>
<li>Disallow users using FTP</li>
<li>Disallow users submit email</li>
<li>Disallow users mass submits</li>
<li>Disallow users Split files</li>
<li>Disallow users Merge files</li>
<li>Disallow users MD5 View/Change</li>
<li>Disallow users change ratio compression(0%) in tar, zip and rar</li>
<li>Disallow users Pack files</li>
<li>Disallow users Zip files</li>
<li>Disallow users UnZip files</li>
<li id="rar">Disallow users RAR files&nbsp;<span class='g'><b>Your Server</b> doesn't support rar feature OR <b><?php echo BINARY_DIR .'rar/rar';?></b> Not Found.</span></li>
<li id="unrar">Disallow users UnRAR files&nbsp;<span class='g'><b>Your Server</b> doesn't support unrar feature OR <b><?php echo BINARY_DIR .'rar/unrar';?></b> Not Found.</span></li>
<li>Disallow users Rename files</li>
<li>Disallow users Mass-Rename files</li>
<li>Disallow users Delete files</li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_show_column_sfile_all" name="opt_show_column_sfile_all" value="1" /><label for="opt_show_column_sfile_all">&nbsp;Server Files Column. <b>Checked: Show All</b></label></td>
<td class="tdacc">Customized Server Files table column</td>
</tr>
<tr id="showcolumnsfilealllist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><input type="checkbox" id="opt_show_column_sfile_md5" name="opt_show_column_sfile[md5]" value="1" />&nbsp;Show MD5</li>
<li class="ndot"><input type="checkbox" id="opt_show_column_sfile_downloadlink" name="opt_show_column_sfile[downloadlink]" value="1" />&nbsp;Show Download_Link</li>
<li class="ndot"><input type="checkbox" id="opt_show_column_sfile_comments" name="opt_show_column_sfile[comments]" value="1" />&nbsp;Show Comments</li>
<li class="ndot"><input type="checkbox" id="opt_show_column_sfile_date" name="opt_show_column_sfile[date]" value="1" />&nbsp;Show Date</li>
<li class="ndot"><input type="checkbox" id="opt_show_column_sfile_age" name="opt_show_column_sfile[age]" value="1" />&nbsp;Show Age</li>
<li class="ndot"><input type="checkbox" id="opt_show_column_sfile_ip" name="opt_show_column_sfile[ip]" value="1" />&nbsp;Show IP</li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li><span>Show MD5 column</span></li>
<li><span>Show Download_Link column</span></li>
<li><span>Show Comments column</span></li>
<li><span>Show Date column</span></li>
<li><span>Show Age column</span></li>
<li><span>Show IP column</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_2gb_fix" name="opt_2gb_fix" value="1" /><label for="opt_2gb_fix">&nbsp;2GB limit fix (32bit OS)</label></td>
<td class="tdacc">Try to list files bigger than 2GB in 32bit OS</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="opt_bw_save" name="opt_bw_save" value="1" /><label for="opt_bw_save">&nbsp;Bandwidth saving</label></td>
<td class="tdacc">To save bandwidth, if the file name is exist, then stop leeching</td>
</tr>
</tbody>
<!-- ==========================END FILES CONFIG -->

<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>

<!-- ==========================START VIEW-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trview" onclick="trclick(this);">
<td class="tdacc"><h1><span id="nod_trview"></span>VIEW CONFIG</h1></td><td>&nbsp;</td></tr>
<tbody id="bd_trview" <?php echo (isset($_COOKIE["trview"])?($_COOKIE["trview"]==1?$styledisplay:''):$styledisplay);?>>
<tr>
<td class="tdacc"><input type="checkbox" id="opt_navi_left_all" name="opt_navi_left_all" value="1" /><label for="opt_navi_left_all">&nbsp;Navigation Left View. <b>Checked: Show All</b></label></td>
<td class="tdacc">Customized Navigation Left View</td>
</tr>
<tr id="navileftalllist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><input type="checkbox" id="opt_navi_left_showcpanel" name="opt_navi_left[showcpanel]" value="1" />&nbsp;Show Cpanel button</li>
<li class="ndot"><input type="checkbox" id="opt_navi_left_showplugins" name="opt_navi_left[showplugins]" value="1" />&nbsp;Show Plugins list</li>
<li class="ndot"><input type="checkbox" id="opt_navi_left_showaudl" name="opt_navi_left[showaudl]" value="1" />&nbsp;Show Auto Download</li>
<li class="ndot"><input type="checkbox" id="opt_navi_left_showauul" name="opt_navi_left[showauul]" value="1" />&nbsp;Show Auto Upload</li>
<li class="ndot"><input type="checkbox" id="opt_navi_left_showlynx" name="opt_navi_left[showlynx]" value="1" />&nbsp;Show Listed Files</li>
<li class="ndot"><input type="checkbox" id="opt_navi_left_showmtn" name="opt_navi_left[showmtn]" value="1" />&nbsp;Show Movie Thumbnailer</li>
<li class="ndot"><input type="checkbox" id="opt_navi_left_server_info" name="opt_navi_left[server_info]" value="1" />&nbsp;Show Server info</li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>Show CPanel button</span></li>
<li class="ndot"><span>Show Plugins supported</span></li>
<li class="ndot"><span>Show Auto Download Button</span></li>
<li class="ndot"><span>Show Auto Upload Button</span></li>
<li class="ndot"><span>Show Listed Files Button</span></li>
<li class="ndot"><span>Show Movie Thumbnailer Button</span></li>
<li class="ndot"><span>Disk space &amp; Time Info</span></li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="opt_forbid_all" name="opt_forbid_all" value="1" /><label for="opt_forbid_all">&nbsp;Restrict Page Access.<b>Checked: Forbid All</b></label></td>
<td class="tdacc">Forbid audl, auul, lynx etc.</td>
</tr>
<tr id="forbidalllist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><input type="checkbox" id="opt_forbid_audl" name="opt_forbid[audl]" value="1" />&nbsp;Forbidden to AuDL</li>
<li class="ndot"><input type="checkbox" id="opt_forbid_auul" name="opt_forbid[auul]" value="1" />&nbsp;Forbidden to AuUL</li>
<li class="ndot"><input type="checkbox" id="opt_forbid_lynx" name="opt_forbid_lynx" value="1"  />&nbsp;Forbidden to Listed&nbsp;Files</li>
<li class="ndot"><input type="checkbox" id="opt_forbid_mtn" name="opt_forbid[mtn]" value="1" />&nbsp;Forbidden to MTN</li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>Restrict access to Auto Download</span></li>
<li class="ndot"><span>Restrict access to Auto Upload</span></li>
<li class="ndot"><span>Restrict access to Listed&nbsp;Files</span></li>
<li class="ndot"><span>Restrict access to Movie Thumbnailer</span></li>
</ul></div></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><b class='o'>Auto Download Config</b></div></td>
<td class="tdacc" id="showaudl">Customized Auto Download Configuration&nbsp;<b class="r">AUDL DISABLED</b></td>
</tr>
<tr id="audlconfiglist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><label><input type="text" id="opt_audl" name="opt_audl" size="5" maxlength="4" value="" />&nbsp;<b class="g">Link</b> (AuDL)</label></div></li>
<li class="ndot"><label><input type="checkbox" id="opt_showautoclose" name="opt_showautoclose" value="1" />&nbsp;Auto-close audl popup</label></li>
<li id='showautoclose' class="ndot"><div style="padding-left:5px;"><label><input type="text" id="opt_timeautoclose" name="opt_timeautoclose" maxlength="5" size="4" value="" />&nbsp;<b class="g">ms</b></label></div></li>
<li class="ndot"><label><input type="checkbox" id="opt_autochecklink" name="opt_autochecklink" value="1" />&nbsp;Auto-Check Link</label></li>
<li class="ndot"><div style="padding-left:5px;"><label><input type="text" id="opt_iframealocate" name="opt_iframealocate" size="2" maxlength="2" value="" />&nbsp;IFrame to allocate in audl</label></div></li>
<li class="ndot"><label><input type="checkbox" id="opt_premium_acc_audl" name="opt_premium_acc_audl" value="1"  />&nbsp;Enable Premix in AUDL</label></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>How many link allow to Auto-download work. set to 0 or blank to disable</span></li>
<li class="ndot"><span>Close Window pop-up after leech file completed</span></li>
<li class="ndot" id='showautocloseket'><span>Timer for closing Auto-download window pop-up. default: 500 milliseconds</span></li>
<li class="ndot"><span>Auto check submited link in audl</span></li>
<li class="ndot"><span>How many iframe to allocate for <b class="g">manual</b> method in audl</span></li>
<li class="ndot"><span><b class="r">False</b> Disable <b class="g">True</b> Enable - Premium Account in autodownload</span></li>
</ul></div></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><b class='o'>Auto Upload Config</b></div></td>
<td class="tdacc" id="showauul">Customized Auto Upload Configuration&nbsp;<b class="r">AUUL DISABLED</b></td>
</tr>
<tr id="auulconfiglist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><div style="padding-left:5px;"><label><input type="text" id="opt_auul" name="opt_auul" size="5" maxlength="4" value="" />&nbsp;<b class="g">Link</b> (AuUL)</label></div></li>
<li class="ndot"><div style="padding-left:5px;"><label><input type="text" id="opt_openwindows" name="opt_openwindows" size="5" maxlength="4" value="" />&nbsp;IFrame to allocate in auul</label></div></li>
<li class="ndot"><label><input type="checkbox" id="opt_myuploads_disable" name="opt_myuploads_disable" value="1" />&nbsp;Disable myuploads.txt</label></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><span>How many file allow to Auto-upload work. set to 0 or blank to disable</span></li>
<li class="ndot"><span>How many iframe to allocate for <b class="g">manual</b> method in auul</span></li>
<li class="ndot"><span><b class="r">True</b> Disable <b class="g">False</b> Enable - myuploads.txt creation</span></li>
</ul></div></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><b class='o'>Movie Thumbnailer Config</b></div></td>
<td class="tdacc" id="showmtn">Customized Movie Thumbnailer Configuration&nbsp;<b class="r">MTN DISABLED</b></td>
</tr>
<tr id="mtnconfiglist">
<td class="tdacc"><div class="div_left_ndot"><ul>
<li class="ndot"><label><input type="checkbox" id="opt_mtn_showconfig_enable" name="opt_mtn_showconfig[enable]" value="1" />&nbsp;Show Configuration at MTN</label></li>
<li id="mtnshowconfig" class="ndot"><label><input type="checkbox" id="opt_mtn_showconfig_showtext" name="opt_mtn_showconfig[showtext]" value="1" />&nbsp;Show Text &amp; Output Suffix at MTN</label></li>
<li class="ndot">&nbsp;Column&nbsp;x&nbsp;Rows
<select id="opt_mtn_colrow_columns" name="opt_mtn_colrow[columns]">
<?php
		for ($i=1; $i<6; $i++) {
			echo "<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select>&nbsp;x&nbsp;
<select id="opt_mtn_colrow_rows" name="opt_mtn_colrow[rows]">
<?php
		for ($i=1; $i<11; $i++) {
			echo "<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select></li>
<li class="ndot"><label for="opt_mtn_width">&nbsp;Width&nbsp;:</label><input type="text" id="opt_mtn_width" name="opt_mtn_width" size="5" maxlength="4" value="" /></li>
<li class="ndot"><label for="opt_mtn_height">&nbsp;Minimum Height&nbsp;:</label><input type="text" id="opt_mtn_height" name="opt_mtn_height" size="5" maxlength="4" value="" /></li>
<li class="ndot"><label for="opt_mtn_text">&nbsp;Text&nbsp;:</label><input type="text" size="25" id="opt_mtn_text" name="opt_mtn_text" value="" /></li>
<li class="ndot"><label for="opt_mtn_suffix"&nbsp;>&nbsp;Output Suffix&nbsp;:</label><input type="text" id="opt_mtn_suffix" name="opt_mtn_suffix" value="" /></li>
<li class="ndot"><label for="opt_mtn_bgcolor">&nbsp;Background Color&nbsp;:</label><input class="color" id="opt_mtn_bgcolor" name="opt_mtn_bgcolor" size="5" value="" /></li>
<li class="ndot">&nbsp;Jpeg Quality&nbsp;:
<select id="opt_mtn_quality" name="opt_mtn_quality">
<option value="60">Very Low</option>
<option value="80">Low</option>
<option value="90">Normal</option>
<option value="100">High</option>
</select></li>
<li class="ndot">&nbsp;Edge&nbsp;:
<select id="opt_mtn_edge" name="opt_mtn_edge">
<?php
	for ($i = 0; $i < 6; $i++) {
		echo "<option value='{$i}'>{$i}</option>\r\n";
	}
?>
</select></li>
<li class="ndot">&nbsp;Step&nbsp;:<input type="text" id="opt_mtn_cuttime_cut" name="opt_mtn_cuttime[cut]" size="5" maxlength="4" value="" />&nbsp;
<select id="opt_mtn_cuttime_time" name="opt_mtn_cuttime[time]">
<option value="sec">seconds</option>
<option value="min">minutes</option>
</select></li>
<li class="ndot"><label><input type="checkbox" id="opt_mtn_individualshots" name="opt_mtn_individualshots" value="1" />&nbsp;Individual Shots</label></li>
<li class="ndot"><label><input type="checkbox" id="opt_mtn_saveinfo" name="opt_mtn_saveinfo" value="1" />&nbsp;Save Info</label></li>
<li class="ndot"><label><input type="checkbox" id="opt_mtn_video_options_enable" name="opt_mtn_video_options[enable]" value="1" />&nbsp;Video Info</label></li>
<li class="ndot" id="mtnvideooptions" style="width: 300px;">&nbsp;Color <input class="color" id="opt_mtn_video_options_txtcolor" name="opt_mtn_video_options[txtcolor]" size="4" value="" />
&nbsp;Font <select id="opt_mtn_video_options_txtfont" name="opt_mtn_video_options[txtfont]">
<?php
		$font_dir = STATIC_DIR . 'font';
		$exts = array(".ttf", '.otf');
		$fonts = array();
		$fonts = vidlist($font_dir,$exts);
		foreach($fonts as $font) {
			echo "<option value='{$font}'>".substr($font, 0, -4)."</option>\r\n";
		}
?>
</select>
&nbsp;Size <select id="opt_mtn_video_options_txtsize" name="opt_mtn_video_options[txtsize]">
<?php
		for($i=8; $i<16; $i++) {
			echo "<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select></li>
<li class="ndot"><label><input type="checkbox" id="opt_mtn_time_enable" name="opt_mtn_time[enable]" value="1" />&nbsp;Time</label></li>
<li class="ndot" id="mtntime">&nbsp;Shadow <input class="color" id="opt_mtn_time_tshadow" name="opt_mtn_time[tshadow]" size="5" />&nbsp;Color <input class="color" id="opt_mtn_time_tcolor" name="opt_mtn_time[tcolor]" size="5" /></li>
<li class="ndot" style="width: 300px;">&nbsp;Location&nbsp;:
<?php
	$mtn_loc = array(1 => 'Lower Left', 2 => 'Lower Right', 3 => 'Upper Right', 4 => 'Upper Left');
?>
&nbsp;Info&nbsp;<select id="opt_mtn_location_info" name="opt_mtn_location[info]">
<?php
	foreach ($mtn_loc as $k => $v) {
		echo "<option value='{$k}'>{$v}</option>\r\n";
	}
?>
</select>
&nbsp;Time&nbsp;<select id="opt_mtn_location_time" name="opt_mtn_location[time]">
<?php
	foreach ($mtn_loc as $k => $v) {
		echo "<option value='{$k}'>{$v}</option>\r\n";
	}
?>
</select></li>
</ul></div></td>
<td class="tdacc"><div class="div_right_ndot"><ul>
<li class="ndot"><b class="r">True</b> Show <b class="g">False</b> Hide - Configuration Movie Thumbnailer (User Interaction)</li>
<li class="ndot" id="mtnshowconfigket"><b class="r">True</b> Show <b class="g">False</b> Hide - Text &amp; Output Suffix (User Interaction)</li>
<li class="ndot">How many colums &amp; rows of output image for Movie Thumbnailer?</li>
<li class="ndot">How many width of output image for Movie Thumbnailer?</li>
<li class="ndot">Minimum height of each shot, will reduce of column to fit</li>
<li class="ndot">Add text above output image</li>
<li class="ndot">Add suffix above output image</li>
<li class="ndot">Background color for the output image</li>
<li class="ndot">Quality of the output image</li>
<li class="ndot">Gap between each shot&nbsp;(set 0 to disable)</li>
<li class="ndot">Cut movie and thumbnails not more than the specified seconds</li>
<li class="ndot">Save individual shots</li>
<li class="ndot">Save info text to a file .txt</li>
<li class="ndot">Movie Thumbnailer Video Info</li>
<li class="ndot" id="mtnvideooptionsket">Available Options for Movie Thumbnailer Video Info</li>
<li class="ndot">Movie Thumbnailer Time</li>
<li class="ndot" id="mtntimeket">Available Options for Movie Thumbnailer Time</li>
<li class="ndot">Movie Thumbnailer Info &amp; Time Location</li>
</ul></div></td>
</tr>

<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_deletelink_in_lynx" name="opt_deletelink_in_lynx" value="1" />&nbsp;Show Delete Link (lynx.php)</label></td>
<td class="tdacc" id="showlynx">Show Delete-Link in Lynx.php (always disabled if Actions files - Delete is disabled)&nbsp;<b class="r">LYNX DISABLED</b></td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_cpuUsageNFO" name="opt_cpuUsageNFO" value="1" />&nbsp;Show CPU Usage</label></td>
<td class="tdacc" id='showcpuusagenfo'>Show CPU Usage Info&nbsp;<b class='r'>SERVER INFO DISABLED</b></td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_OnlineVisitor" name="opt_OnlineVisitor" value="1" />&nbsp;Show Online Visitor</label></td>
<td class="tdacc">Show how many Visitor is Online</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_premix_status" name="opt_premix_status" value="1" />&nbsp;Show premiX acc Status</label></td>
<td class="tdacc">Display (RS) Premix ACC status</td>
</tr>
<tr id="premixstatuslist">
<td class="tdacc">
<div style="padding-left:25px;" class="float"><input type="text" id="opt_ip_premixstat_list" name="opt_ip_premixstat_list" value="" />&nbsp;<span>Trusted IP List</span></div>
</td>
<td class="tdacc"><div style="padding-left:25px;">Trusted IP, can view detil premix acc status. eg: 192.168.0.1, <b class="g"><?php echo $visitors->userip;?></b></div></td>
</tr>
</tbody>
<!-- ==========================END VIEW CONFIG -->


<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>


<!-- ==========================START MISC-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trmisc" onclick="trclick(this);">
<td class="tdacc"><h1><span id="nod_trmisc"></span>MISC CONFIG</h1></td><td>&nbsp;</td></tr>
<tbody id="bd_trmisc" <?php echo (isset($_COOKIE["trmisc"])?($_COOKIE["trmisc"]==1?$styledisplay:''):$styledisplay);?>>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_no_cache" name="opt_no_cache" value="1" />&nbsp;No cache page</label></td>
<td class="tdacc">Prohibition by Browser (You should leave this checked unless you know what you are doing)</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_redir" name="opt_redir" value="1" />&nbsp;Redirrect</label></td>
<td class="tdacc">Redirect passive method (You should leave this checked unless you know what you are doing)</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_new_window" name="opt_new_window" value="1" />&nbsp;Transload files in a new window</label></td>
<td class="tdacc"><b class="r">False</b> disabled, <b class="g">True</b> use new window</td>
</tr>
<tr id="newwindowlist">
<td class="tdacc"><label><input type="checkbox" id="opt_new_window_js" name="opt_new_window_js" value="1" />&nbsp;Use javascript window</label></td>
<td class="tdacc">(Only used when new_window in options enabled) <b class="o">True</b> full size window, <b class="r">False</b> javascript window</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_use_curl" name="opt_use_curl" value="1" />&nbsp;Use curl as default</label></td>
<td class="tdacc"><b class="g">True</b> Will use curl instead stream socket client(especially in ssl connection). Need curl exec/extension enable in your server</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;">
<select id="opt_compressed_web" name="opt_compressed_web">
<option value="0">Disabled</option>
<option value="1">Compress all</option>
<option value="2">Compress main</option>
</select>&nbsp;Toggle compressed mode</div></td>
<td class="tdacc"><b class="g">0</b> Disable <b class="g">1</b> Compress all page source <b class="g">2</b> Compress main body only</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_upload_html_disable" name="opt_upload_html_disable" value="1" />&nbsp;Disable *.upload.html creation</label></td>
<td class="tdacc"><b class="r">True</b> Disable *.upload.html creation when use autoupload or upload action</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_disable_ajax" name="opt_disable_ajax" value="1" />&nbsp;Disable Ajax</label></td>
<td class="tdacc">Do not use Ajax in Serverfiles.</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_disable_ajaxren" name="opt_disable_ajaxren" value="1" />&nbsp;Disable Instant-Rename</label></td>
<td class="tdacc">Do not load Ajax Instant-Rename. <small>require: ajax.js</small></td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_logact" name="opt_logact" value="1" />&nbsp;User-Log Activity</label></td>
<td class="tdacc">Record IP, Browser, Refferer; time access of the users</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_alternatefree" name="opt_alternatefree" value="1" />&nbsp;Alternate switch to freedownload</label></td>
<td class="tdacc">Auto switch to free download if premium not available</td>
</tr>
<tr>
<td class="tdacc"><label><input type="checkbox" id="opt_mip_enabled" name="opt_mip_enabled" value="1" />&nbsp;Enable Multi-IP</label></td>
<td class="tdacc">Enable Multi-IP based on mip_iplist.txt</td>
</tr>
<tr id="mipenabledlist">
<td class="tdacc float" ><div style="padding-left:20px;">
<input type="checkbox" name="opt_mip_arotate" id="opt_mip_arotate" value="1" />&nbsp;<label for="opt_mip_arotate">IP-Rotate</label><div style="height:3px;"></div>
<textarea type="text" name="mip_list" rows="4" cols="20"><?php echo htmlspecialchars($mip_list);?></textarea><br />
</div>
</td>
<td class="tdacc" valign="top" style="height:110px;">
 <div style="padding-left:10px;">
  Should IP changed to next ip after transload process
  <div style="height:8px;"></div>
  List of IP :: <?php echo (!defined('LOG_DIR') ? "Not found: LOG_DIR" : "");$fmip = LOG_DIR."mip_iplist.txt";
  if(file_exists($fmip)){echo (!fopen($fmip,"a")? " -&gt; <b class='r'>Can't write to ":"<b class='g'>")."{$fmip}</b>";}else{echo "{$fmip} Not Found.";}
  ?>
  <br />(separate each IP by new line)
 </div>
</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" id="opt_timezone" name="opt_timezone" size="2" maxlength="2" value="" />&nbsp;Time Zone</div></td>
<td class="tdacc">Set your server timezone. Eg. It is GMT+ <b class="g">7</b> for Indonesia</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;">
<select id="opt_lang" name="opt_lang" title="Select Language">
<?php
	foreach ($arlang as $k => $v) {
		echo "<option value='{$k}'>{$v}</option>";
	}
?>
</select>
&nbsp;<b class="g">Language</b></div></td>
<td class="tdacc">Set your default Language.</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;">
<select id="opt_template_used" name="opt_template_used" onchange='javascript:function findmyvalue(id){var mydate = new Date();var cklive = eval(1000 * 60);var result = d.getElementById(id).value;mydate.setTime(mydate.getTime() + cklive);d.cookie = id + " = " + result + "; path=" + dirpath + "; expires=" + mydate.toGMTString() + "; ";location.reload(true);}findmyvalue("opt_template_used");' title="Select Template" >
<?php
	$tpl = !empty($_COOKIE['opt_template_used']) ? $_COOKIE['opt_template_used'] : $options['template_used'];
	foreach($arTPL as $k => $v){
	 $select = $tpl == $v ? " selected='selected'":"";
	 echo "<option value='".$v."'".$select.">".$v."</option>";
	}
?>
</select>
&nbsp;<b class="g">Template</b></div></td>
<td class="tdacc">Set Default Template to your RL: <b class="g">default</b> Default template of RL Rx08</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;">
<select id="opt_csstype" name="opt_csstype" title="Select Style">
<?php
	foreach($arCSS[$tpl] as $k => $v){
	 $select = $options['csstype'] == $v ? " selected='selected'":"";
	 echo "<option value='".$v."'".$select.">".$v."</option>";
	}
?>
</select>
&nbsp;<b class="g">Style</b></div></td>
<td class="tdacc">Set Default Style to your RL: <b class="g">_default</b> Default Style/CSS of RL Rx08</td>
</tr>
</tbody>
<!-- ==========================END MISC-CONFIG -->

<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>

<!-- ==========================START ACC CONFIG -->
<!-- =============================================-->
<tr>
<td class="tdacc">
<h1>PREMIUM ACC CONFIG</h1></td><td>&nbsp;</td></tr>
<tr>
<td class="tdacc"><input type="checkbox" id="usepcc" name="usepcc" onclick="javascript:var displ=this.checked?'':'none';d.getElementById('divpccL').style.display=displ;d.getElementById('divkccL').style.display=displ;d.getElementById('divcccL').style.display=displ;" <?php if($showpostn){if(isset($_POST['usepcc']) && $_POST['usepcc']=='on')echo ' checked="checked"';}elseif((isset($premium_acc)) && $ada_pre_acc) echo ' checked="checked"';?> /><label for="usepcc"><b>Use Premium Account</b></label></td>
<td class="tdacc"><span>Activate PCC Array</span></td></tr>

<tr>
<td colspan="2">
<div id="divpccL" style="display:<?php if(!$showpostn){if(isset($_POST['usepcc']) && $_POST['usepcc']=='on'){echo '';}else{echo 'none';}}elseif((isset($premium_acc))&& $ada_pre_acc){echo '';}else{echo 'none';}?>">
<div class="float">
<ul class="pret"><li class="ndot">
<table border="1px"><tbody>
<?php
// Premium Account with user & pass.
	foreach ($ar_pre_upass_acc as $k => $v) {
		// Let's start to rock
		if (in_array($v, $ar_pre_multi_acc)) {
?>
<tr>
<td class="tdacc" style="width:240px;">
<?php
			$id = false; $chk = '';
			if (!$showpostn) {
				if (isset($_POST[$v.'_premix']) && $_POST[$v.'_premix'] == 'on') {
					$chk = ' checked="checked"'; $id = true;
				} elseif(isset($premium_acc[$v]) && ((isset($premium_acc[$v]["user"]) && $premium_acc[$v]["user"]!='' && isset($premium_acc[$v]["pass"]) && $premium_acc[$v]["pass"]!='') ||
					(isset($premium_acc[$v][0]["user"]) && $premium_acc[$v][0]["user"]!=''&& isset($premium_acc[$v][0]["pass"]) && $premium_acc[$v][0]["pass"]!=''))) {
					$chk = ' checked="checked"'; $id = true;
				}
			}
?>
<input type="checkbox" id="<?php echo $v;?>_premix" name="<?php echo $v;?>_premix" onclick="clk(this,'<?php echo $v;?>box','<?php echo $v;?>ket','<?php echo $id;?>');" /><label for="<?php echo $v;?>_premix">&nbsp;<?php echo ucfirst(str_replace('_','.', $v));?></label></td>
<td class="tdacc" style="width:350px;">
<div id="<?php echo $v;?>box"<?php echo $styledisplay;?>>
user: <input type="text" id="user_<?php echo $v;?>0" name="user_<?php echo $v;?>0" value="" />
pass: <input type="password" id="pass_<?php echo $v;?>0" name="pass_<?php echo $v;?>0" value="" />
<span id="<?php echo $v;?>pcc"></span>
<span id="<?php echo $v;?>buttonplus0">
&nbsp;<input id="<?php echo str_replace('_', '', $v);?>id_0" type="image" src="<?php echo IMAGE_DIR;?>plus.png" alt="+" title="Add Row" onclick="addElement('<?php echo $v;?>box', this.id, ['user_<?php echo $v;?>', 'pass_<?php echo $v;?>'], ['addition', '<?php echo $v;?>buttonplus'], '<?php echo $acc;?>' ); return false;" style="vertical-align:middle;" />
</span></div>
<span id="<?php echo $v;?>ket">
<?php if(!$id){
  echo "<div>".ucfirst(str_replace('_','.', $v))." Premium Account</div>";
 }else{
  //echo (isset($arr_acchashmulti)?(count($arr_acchashmulti)>1?count($arr_acchashmulti):'?'):(isset($premium_acc["rapidshare_com"]["user"])?1:'?'))."&nbsp;".ACC_EMBED;
  echo (isset($premium_acc[$v][0]["user"])?(count($premium_acc[$v])):(isset($premium_acc[$v]["user"])?1:'?'))."&nbsp;".ACC_EMBED;
  }
?>
</span>
</td></tr>
<?php
		} else {
?>
<tr>
<td class="tdacc" style="width:240px;">
<?php
			$id = false; $chk = '';
			if(!$showpostn) {
				if (isset($_POST[$v.'_premix']) && $_POST[$v.'_premix'] == 'on') {
					$chk = ' checked="checked"'; $id = true;
				} elseif(isset($premium_acc[$v]) && (!empty($premium_acc[$v]['user']) && !empty($premium_acc[$v]['pass']))) {
					$chk = ' checked="checked"'; $id = true;
				}
			}
?>
<input type="checkbox" id="<?php echo $v;?>_premix" name="<?php echo $v;?>_premix" onclick="clk(this,'<?php echo $v;?>box','<?php echo $v;?>ket','<?php echo $id;?>');"<?php echo $chk;?> /><label for="<?php echo $v;?>_premix">&nbsp;<?php echo ucfirst(str_replace('_','.', $v));?></label>
</td>
<td class="tdacc" style="width:350px;">
<div id="<?php echo $v;?>box"<?php echo $styledisplay;?> class="float">
user: <input type="text" id="user_<?php echo $v;?>" name="user_<?php echo $v;?>" value="" />&nbsp;
pass: <input type="password" id="pass_<?php echo $v;?>" name="pass_<?php echo $v;?>" value="" />
</div>
<span id="<?php echo $v;?>ket"><?php if(!$id){echo "<div>".ucfirst(str_replace('_','.', $v))." Premium or Member Account</div>"; ;}else{echo ACC_EMBED;}?></span>
</td>
</tr>
<?php
		}
	}
	unset($v);
?>
	</tbody></table>
	</li></ul>
</div>
</div>
</td>
</tr>

<tr>
<td colspan="2">
<div id="divkccL" style="display:<?php if(!$showpostn){if(isset($_POST['usepcc']) && $_POST['usepcc']=='on'){echo '';}else{echo 'none';}}elseif((isset($premium_acc))&& $ada_pre_acc){echo '';}else{echo 'none';}?>">
<div class="float">
<ul class="pret"><li class="ndot">
<table border="1px"><tbody>
<?php
	foreach ($ar_pre_key_acc as $k => $v) {
?>
<tr>
<td class="tdacc" style="width:240px;">
<?php
		$id = false; $chk = '';
		if(!$showpostn) {
			if (isset($_POST[$v.'_keypremix']) && $_POST[$v.'_keypremix'] == 'on') {
				$chk = ' checked="checked"'; $id = true;
			} elseif(isset($premium_acc[$v]['key']) && (!empty($premium_acc[$v]['key']))) {
				$chk = ' checked="checked"'; $id = true;
			}
		}
?>
<input type="checkbox" id="<?php echo $v;?>_keypremix" name="<?php echo $v;?>_keypremix" onclick="clk(this,'<?php echo $v;?>keybox','<?php echo $v;?>keyket','<?php echo $id;?>');"<?php echo $chk;?> /><label for="<?php echo $v;?>_keypremix">&nbsp;<?php echo ucfirst(str_replace('_','.', $v));?> Key</label>
</td>
<td class="tdacc" style="width:350px;">
<div id="<?php echo $v;?>keybox"<?php echo $styledisplay;?> class="float">
Key: <input type="text" id="<?php echo $v;?>_key" name="<?php echo $v;?>_key" value=""  size="40" />
</div>
<span id="<?php echo $v;?>keyket"><?php if(!$id){echo "<span>".ucfirst(str_replace('_','.', $v))."  Premium Account Key.</span>"; ;}else{echo KEY_EMBED;}?></span>
</td>
</tr>

<?php
	}
	unset($v);
?>
	</tbody></table>
	</li></ul>
</div>
</div>
</td>
</tr>

<tr>
<td colspan="2">
<div id="divcccL" style="display:<?php if(!$showpostn){if(isset($_POST['usepcc']) && $_POST['usepcc']=='on'){echo '';}else{echo 'none';}}elseif((isset($premium_acc))&& $ada_pre_acc){echo '';}else{echo 'none';}?>">
<div class="float">
<ul class="pret"><li class="ndot">
<table border="1px"><tbody>
<?php
	foreach ($ar_pre_cookie_acc as $k => $v) {
?>
<tr>
<td class="tdacc" style="width:240px;">
<?php
		$id = false; $chk = '';
		if($showpostn) {
			if (isset($_POST[$v.'_hashpremix']) && $_POST[$v.'_hashpremix'] == 'on') {
				$chk = ' checked="checked"'; $id = true;
			} elseif(isset($premium_acc[$v]['cookie']) && (!empty($premium_acc[$v]['cookie']))) {
				$chk = ' checked="checked"'; $id = true;
			}
		}
?>
<input type="checkbox" id="<?php echo $v;?>_hashpremix" name="<?php echo $v;?>_hashpremix" onclick="clk(this,'<?php echo $v;?>hashbox','<?php echo $v;?>hashket','<?php echo $id;?>');"<?php echo $chk;?> /><label for="<?php echo $v;?>_hashpremix">&nbsp;<?php echo ucfirst(str_replace('_','.', $v));?> Cookie</label>
</td>
<td class="tdacc" style="width:350px;">
<div id="<?php echo $v;?>hashbox"<?php echo $styledisplay;?> class="float">
<?php echo $k;?>= <input type="text" id="<?php echo $v;?>_cookie" name="<?php echo $v;?>_cookie" value=""  size="40" />
</div>
<span id="<?php echo $v;?>hashket"><?php if(!$id){echo "<span>".ucfirst(str_replace('_','.', $v))."  Premium Account Cookie.</span>"; ;}else{echo CK_EMBED;}?></span>
</td>
</tr>

<?php
	}
	unset($v);
?>
	</tbody></table>
	</li></ul>
</div>
</div>
</td>
</tr>

<tr>
<td align="left" colspan="3">
<?php
$exist_accrs = (isset($premium_acc["rapidshare_com"]) ?
  ( isset($premium_acc["rapidshare_com"]["user"]) && $premium_acc["rapidshare_com"]["user"]!='' && isset($premium_acc["rapidshare_com"]["pass"]) && $premium_acc["rapidshare_com"]["pass"]!='' ? true :
    (isset($premium_acc["rapidshare_com"][0]["user"]) && $premium_acc["rapidshare_com"][0]["user"]!='' && isset($premium_acc["rapidshare_com"][0]["pass"]) && $premium_acc["rapidshare_com"][0]["pass"]!='' ? true : false)
  ) : false );
?>
<div id="parentnfoconfig">
<div id="nfoconfig" align="center" style="width:250px;padding:4px 0 5px 8px; margin-left:0px; display:<?php echo (($ch_curl==1) && ($exist_accrs)?"":"none;");?>">
 <a href="javascript:;" onclick="<?php echo ($ajax_serverfiles?'stacc(); this.blur()':'alert(\'Ajax Disabled\');')?>" style="float:right;margin-top:-1px;" title="refresh"><small>refresh</small></a>
 <div id="accshowresults" style="display:none;"></div>
 <div id="accwaiting" align="center" style="display:none;" alt="waiting..." title="please wait bro.."></div>
</div>

<?php  if($ajax_serverfiles){
?>
<script type="text/javascript">
 function stacc() { document.poiuy.submit("staccounts","accwaiting","accshowresults",""); }
setTimeout("stacc()",100);
</script>
<?php } ?>
</td>
</tr>
<!-- ==========================END ACC CONFIG -->



<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>
<!--================Upload-Acc Config====================--->

<tr>
<td class="tdacc">
<h1>UPLOAD ACC CONFIG</h1></td><td>&nbsp;</td></tr>
<tr>
<td class="tdacc">
<input type="checkbox" id="useulcc" name="useulcc" onclick="displ=this.checked?'':'none';document.getElementById('divulccL').style.display=displ;" <?php if(!$showpostn){if(isset($_POST['useulcc']) && $_POST['useulcc']=='on')echo ' checked="checked"';}elseif((isset($upload_acc))&&(is_array($upload_acc))&& $ada_upl_acc) echo ' checked="checked"';?> /><label for="useulcc"><b>Use Upload acc</b></label>
<div class="spc">&nbsp;</div>
</td>
<td class="tdacc"><span>Activate UCC Array</span></td></tr>

<tr>
<td colspan="2">
<div id="divulccL" style="display:<?php if(!$showpostn){if(isset($_POST['useulcc']) && $_POST['useulcc']=='on'){echo '';}else{echo 'none';}}elseif((isset($upload_acc))&&(is_array($upload_acc))){echo '';}else{echo 'none';}?>"><div class="float">
<ul class="pret"><li class="ndot">
<table border="1px"><tbody>
<?php
	foreach ($ar_up_acc as $k => $v) {
?>
<tr>
<td class="tdacc" style="width:240px;">
<?php
		$id = false; $chk = '';
		if($showpostn) {
			if (isset($_POST[$v.'_upload']) && $_POST[$v.'_upload'] == 'on') {
				$chk = ' checked="checked"'; $id = true;
			} elseif(isset($upload_acc[$v])  && (!emtpy($upload_acc[$v]['user']) && !empty($upload_acc[$v]['pass']))) {
				$chk = ' checked="checked"'; $id = true;
			}
		}
?>
<input type="checkbox" id="<?php echo $v;?>_upload" name="<?php echo $v;?>_upload" onclick="clk(this,'<?php echo $v;?>_upbox','<?php echo $v;?>_upket','<?php echo $id;?>');"<?php echo $chk;?> /><label for="<?php echo $v;?>_upload">&nbsp;<?php echo ucfirst(str_replace('_','.', $v));?></label>
</td>
<td class="tdacc" style="width:350px;">
<div id="<?php echo $v;?>_upbox"<?php echo $styledisplay;?> class="float">
user: <input type="text" id="user_<?php echo $v;?>_up" name="user_<?php echo $v;?>_up" value="" />&nbsp;
pass: <input type="password" id="pass_<?php echo $v;?>_up" name="pass_<?php echo $v;?>_up" value="" />
</div>
<span id="<?php echo $v;?>_upket"><?php if(!$id){echo "<div>".ucfirst(str_replace('_','.', $v))." Upload Account</div>"; ;}else{echo ACC_EMBED;}?></span>
</td>
</tr>
<?php
		}
        unset($v);
?>
	</tbody></table>
	</li></ul>
</div>
</div>
</td>
</tr>

<!---================End Upload-Acc Config=====================--->

<tr><td colspan="2"><div><hr id="grs1" /></div></td></tr>

<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="secretkey" value="<?php if($showpostn){if(!empty($_POST['secretkey']))echo $_POST['secretkey'];}elseif(!empty($secretkey)) echo $secretkey; ?>" />&nbsp;Secret Key</div></td>
<td class="tdacc"><b class='b'>WARNING..! IF THIS IS NOT SET BEFORE YOU USE PREMIUM SERVICES, YOU WILL BE WARNED BY THE RAPIDLEECH SCRIPT. OTHERWISE YOUR PREMIUM ACCOUNTS AND/OR COOKIES COULD BE COMPROMISED!</b></td>
</tr>


<tr><td colspan="2"><div class="spc" style="padding-top:5px;border-bottom:1px solid #1E2637;">&nbsp;</div></td></tr>

</tbody>
</table>

<script type="text/javascript">
var trtab = Array('limit','files','view','misc');
if(typeof(imgpath) === 'undefined'){imgpath = '<?php echo IMAGE_DIR;?>';}
for(var i=0; i<trtab.length; i++){ cueview = getCookie('tr'+trtab[i]); pm=(cueview==1||cueview==null?'plus':'min'); d.getElementById('nod_tr'+trtab[i]).innerHTML = '<input type="image" src="'+imgpath+pm+'.png" onclick="" style="vertical-align:middle;">&nbsp;';}
</script>

<input type="hidden" id="cplHash" name="cplHash" value="" size="90" />
<input type="hidden" id="rlauthHash" name="rlauthHash" value="" size="90" />


<div align="center" style="padding-top:4px;">
<input type="hidden" name="setupsave" value="1" />
<input type="submit" name="submit" id="saveAll" value="Save" />&nbsp;&nbsp;&nbsp;
<input type="button" onclick="location.href='./index.php'" value="Cancel" />
	<div style="padding-top:10px;"></div>
<?php if(!$options['disableadvanceeditor']){
?>
&nbsp;&nbsp;&nbsp;
<input type="button" onclick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=editor\'';?>" value="Advanced Editor" />
	<?php
}
?>
<input type="button" onclick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=viewlog\'';?>" value="View Log" />

</div>
</form>

<?php
	}
?>