<?php
if (!defined('RAPIDLEECH'))
  { require_once("404.php"); exit; }
  
if($ajax_serverfiles){
?>
<script type="text/javascript" language="javascript" src="rsajax.js"></script>
<?php } ?>


<?php
switch($task)
{
	
// Advanced Editor Mode
	case 'editor':
		
?>

<script type="text/javascript">
var dId;
function highlight(field) {
   field.focus(); field.select(); }	
</script>

	
<table id="entered" width="100%">
<tr> <td width="100%" valign="top">

<div id='editor' style="display:block;">
<center>
<table border=0 style="width:710px;">
<tbody>
<tr><td>

<?php
  $r = file_get_contents($fileconfig);
  echo "<div align=left class=a>:: Editor Mode ::</div>";
  echo "<form action=\"".basename($PHP_SELF)."?mode=editor\" method=POST><textarea class=\"redtxtarea\" style=\"width:100%;font-size:12px;\" name=\"edit_text\" cols=\"122\" rows=\"28\" width=\"100%\">".htmlspecialchars($r)."</textarea><br>";
?>
<div class="warn_alert"><span class='b'>Warning..!</span> Any misconfiguration on this config file may bring <b class="a">XPanel</b>&nbsp;and <b class="a">RapidLeech</b> not working.<br><b class="a">You know wot you're doin here, don't you ?!</b></div>
<div align="center" style="padding-top:10px;">
<input type="submit" name="submit" value="Save">&nbsp;&nbsp;&nbsp;
<input type="reset" value="Reset">&nbsp;&nbsp;&nbsp;
<input type="button" onClick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=\'';?>" value="Back to Xpanel"></div>
</form>

</td></tr>
</tbody>
</table></center>
</div>


</td></tr>
</table></b>
	
	
<?php
  break; //end -case 'editor':




// View Log Mode
  case 'viewlog':
	$fnlog = LOG_PHP;
	$fnsize = @filesize($fnlog);
	$showfnlog = false; // Are you gonna show the filename here??
?>
<form name="dlog" id="dlog" action="javascript:get('deletelog','logwaiting','logshowresults','');" >
   <input type="hidden" id="deletelog" value="<?php print base64_encode($cp_login[0].":".$cp_login[1]);?>">
</form>

<?php if($fnsize>3000){
 echo '<input type="button" id="topback" onClick="location.href=\'./'.basename($PHP_SELF).'?mode=\'" value="Back to Xpanel">';}
?>

&nbsp;&nbsp;<span>Log Filesize<?php echo ($showfnlog ? " <b>[".$fnlog."]</b>&nbsp;":"");?>:&nbsp;<span id="cur_log"><b class="g"><span id="logshowresults" style="float:center;display:;"><?php echo bytesToKbOrMbOrGb($fnsize);?></span>&nbsp;&nbsp;<a href="javascript:void(0)" id="resetlog" onclick="<?php echo ($ajax_serverfiles?'if(confirm(\'This will reset Log-System, proceed?\')){flushlog();};' : 'alert(\'Ajax Disabled\')');?>" style="display:none;"><small>FLUSH LOG-FILE</small></a></b></span></span><div id="logwaiting" style="display:none;"></div>
<div style="padding-top:15px;"><div>

<table id='vlog' width='100%'>
<tr><td><tbody id="vlogtd">
<?php
 $fnlog = LOG_PHP;
 if(@file_exists($fnlog))
	{ require_once($fnlog);}else
	{echo "<br><center><b class='r'>$fnlog</b> not found!</center><br>";}
 ?>
</div>
</td></tr>
</table>
<div><input type="button" onClick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=\'';?>" value="Back to Xpanel">
<?php if($fnsize>3000){ ?>
 &nbsp;&nbsp;<a href="javascript:void(0);" onclick="scroll(0,0);"><b>TOP</b></a>
<?php } ?>
</div>
<script type="text/javascript">
<?php if($ajax_serverfiles){
?>
function flushlog(){
  d.dlog.submit();
  d.getElementById('isilog').style.display = 'none';
  if(d.getElementById('topback')!=null){d.getElementById('topback').style.display='none';}
}
<?php } ?>
d.getElementById('resetlog').style.display='';
</script>	
<?php
	break; // end -case 'viewlog':




//=================================
// XPanel Mode
//=================================
default:
	?>

<form name="poiuy" id="poiuy" action="javascript:get('staccounts','accwaiting','accshowresults','');" >
   <input type="hidden" id="staccounts" value="lol">
</form>
<form name="trew" id="trew" action="javascript:get('posttraffic','trafwaiting','trafshowresults','');" >
   <input type="hidden" id="posttraffic" value="<?php print base64_encode($cp_login[0].":".$cp_login[1]);?>">
</form>
<form method="post" action="<?php print basename($PHP_SELF);?>">
<table width="100%">
<tbody><tr><td class="xpTitleColoum" width="100">Option</td><td class="xpTitleColoum">Description</td>
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
<input name="logincpanel" disabled="disabled" checked="checked" type="checkbox">&nbsp;Login Cpanel</td>
<td class="tdacc"><div id="cpbtnChange" style=""><input type="button" value="Change" onClick="var d=document; d.getElementById('cpbtnChange').style.display='none';d.getElementById('newlogincp').style.display='';d.getElementById('cplHash').value='1';d.getElementById('newusercp').focus();"><span id="isdefcp"><?php if($defaultcp)print "&nbsp;<small class='b'>Default Login CP Detected (admin:admin)</small>&nbsp;";?></span></div>
<div id="newlogincp" style="display:none;">
user: <input type="text" id="newusercp" name="newusercp"> 
pass: <input type="password" id="newpascp" name="newpascp"> 
<input type="button" id="cpcancel" value="Cancel" onclick="doCancel('newusercp','newpascp','waitc','cplHash','cpbtnChange','newlogincp')">
<span id="waitc"></span></div></td>
</tr>

<tr>
<td class="tdlog"><input type="checkbox" id="login" name="login" onClick="clk(this,'rlbtnChange','lip',0); document.getElementById('loginrl').style.display='none';"<?php $logindontdisplay=true;if($showpostn){if(isset($_POST['login']) && $_POST['login']=='on'){echo ' checked';$logindontdisplay=false;}}elseif($login){echo ' checked';$logindontdisplay=false;} ?>>&nbsp;<label for=login>Login index page</label></td>
<td class="tdacc">
<div id="rlbtnChange"<?php if($logindontdisplay) echo $styledisplay; ?>><input type="button" value="Change" id="loginchange" onClick="var d=document; d.getElementById('loginrl').style.display=''; d.getElementById('rlbtnChange').style.display='none';d.getElementById('rlauthHash').value='1';d.getElementById('user').focus();"></div>

<div id="loginrl" style="display:none">
user: <input type="text" id="user" name="user"> 
pass: <input type="password" id="pass" name="pass">
<input type="button" id="chngsvn" onclick="doCancel('user','pass','lipu','rlauthHash','rlbtnChange','loginrl');" value="Cancel"> 
<span id="lipu"></span></div>

<span id="lip" <?php if(!$logindontdisplay) echo $styledisplay; ?>>RL-Login Authorization Mode is <b class="b">OFF</b></span></td>
</tr>
<!-- ==========================END LOGIN-CONFIG -->



<tr><td colspan="2"><div><hr id="grs1"></div></td></tr>




<!-- ==========================START ACC CONFIG -->
<!-- =============================================-->
<tr>
<td class="tdacc">
<h1>PREMIX ACC CONFIG</h1></td><td>&nbsp;</td></tr>
<tr>
<td class="tdacc">
<input type="checkbox" id="usepcc" name="usepcc" onClick="displ=this.checked?'':'none';document.getElementById('divpccL').style.display=displ;" <?php if($showpostn){if(isset($_POST['usepcc']) && $_POST['usepcc']=='on')echo ' checked';}elseif((isset($premium_acc)) && $ada_acc) echo ' checked';?>><label for=usepcc><b>Use premiX acc</b></label>
&nbsp;&nbsp;&nbsp;
<input type="checkbox" id="autodlpremix" name="autodlpremix" <?php if($showpostn){if(isset($_POST['autodlpremix']) && $_POST['autodlpremix']=='on')echo ' checked';}elseif((isset($premium_acc))&&(isset($premium_acc_audl))&&$premium_acc_audl) echo ' checked'; ?>><label for="autodlpremix" title="Enable Premix on Audl">Enable on Audl</label>
</td>
<td class="tdacc"><span>Activate PCC Array</span></td></tr>

<tr>
<td colspan="2">
<div id="divpccL" style="display:<?php if($showpostn){if(isset($_POST['usepcc']) && $_POST['usepcc']=='on'){echo '';}else{echo 'none';}}elseif((isset($premium_acc))&& $ada_acc){echo '';}else{echo 'none';}?>"><div class="float">
<ul class="pret"><li class="ndot">
	<table border="1px"><tbody>
	<tr>
	<td class="tdacc" style="width:240px;" valign="top">
<?php 
	$rsencchecked=false;
	$chk ='';
	if($showpostn){
	 if(isset($_POST['rs_com_premix']) && $_POST['rs_com_premix']=='on')
	  {$chk=' checked';$rsencchecked=true;}
	}elseif(isset($premium_acc["rs_com"]) && ((isset($premium_acc["rs_com"]["user"]) && $premium_acc["rs_com"]["user"]!='' && isset($premium_acc["rs_com"]["pass"]) && $premium_acc["rs_com"]["pass"]!='')
	  || (isset($premium_acc["rs_com"][0]["user"]) && $premium_acc["rs_com"][0]["user"]!=''&& isset($premium_acc["rs_com"][0]["pass"]) && $premium_acc["rs_com"][0]["pass"]!='')))
	  
	  {$chk=' checked';$rsencchecked=true;}
?>
<input type="checkbox" id="rs_com_premix" name="rs_com_premix" id="rs_com_premix" onClick="clk(this,'rsenc','rsauth2','<?php print $rsencchecked;?>');"<?php echo $chk;?>>
<label for="rs_com_premix">Rapidshare.com</label></td>
	<td class="tdacc">
	<div id="rsenc"<?php echo $styledisplay;?>>
	user: <input type="text" id="rpl0" name="rpl0" value="">
	pass: <input type="password" id="rppl0" name="rppl0" value="">
	
	<span id="rspcc"></span>
	<span id="buttonplus0">
	&nbsp;<input id="rsid_0" type="image" src="<?php echo IMAGE_DIR;?>plus.png" alt="+" title="Add Row" onclick="addElement('rsenc', this.id, ['rpl', 'rppl'], ['addition', 'buttonplus'] ); return false;" style="vertical-align:middle;">
	</span></div>
	<span id="rsauth2">
	<?php if(!$rsencchecked){
	  echo "<div>Rapidshare.com premiX ACC</div>";
	 }else{
	  //echo (isset($arr_acchashmulti)?(count($arr_acchashmulti)>1?count($arr_acchashmulti):'?'):(isset($premium_acc["rs_com"]["user"])?1:'?'))."&nbsp;".ACC_EMBED;
	  echo (isset($premium_acc["rs_com"][0]["user"])?(count($premium_acc["rs_com"])):(isset($premium_acc["rs_com"]["user"])?1:'?'))."&nbsp;".ACC_EMBED;
	  }
	?>
	</span>
	</td></tr>
	
	<tr>
	<td class="tdacc">
<?php 
  $rsdechecked=false;$chk ='';
  if($showpostn){
   if(isset($_POST['rs_de_premix']) && $_POST['rs_de_premix']=='on')
    {$chk= ' checked';$rsdechecked=true;}
  }elseif(isset($premium_acc['rs_de']))
    {$chk= ' checked';$rsdechecked=true;}
?>
<input type="checkbox" id="rs_de_premix" name="rs_de_premix" onClick=" clk(this,'rsencde','rsauth2de','<?php echo $rsdechecked;?>');"<?php print $chk;?>>
<label for="rs_de_premix">&nbsp;Rapidshare.de</label>
	</td>
	<td class="tdacc">
	<div id="rsencde"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="rplde" name="rplde" value="">&nbsp;
	pass: <input type="password" id="rpplde" name="rpplde" value="">
	</div>
	<span id="rsauth2de"><?php if(!$rsdechecked){echo "<div>Rapidshare.de premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
 $megupld=false;$chk='';
 if($showpostn){
   if(isset($_POST['megaupload_premix']) && $_POST['megaupload_premix']=='on')
    {$chk= ' checked';$megupld=true;}
 }elseif(isset($premium_acc['megaupload']))
    {$chk= ' checked';$megupld=true;}
?>

<input type="checkbox" id="megaupload_premix" name="megaupload_premix" onClick="clk(this,'muacc','muket','<?php echo $megupld;?>');"<?php echo $chk;?>>
<label for="megaupload_premix">&nbsp;Megaupload.com</label>
	</td>
	<td class="tdacc">
	<div id="muacc"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrmu" name="usrmu" value="">&nbsp;
	pass: <input type="password" id="passmu" name="passmu" value="">
	</div>
	<span id="muket"><?php if(!$megupld){echo "<div>Megaupload.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $megshr=false;$chk='';
  if($showpostn){
    if(isset($_POST['megashare_premix']) && $_POST['megashare_premix']=='on')
	 {$chk= ' checked';$megshr=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['megashare'])))
    {$chk= ' checked'; $megshr=true;} 
?>

<input type="checkbox" id="megashare_premix" name="megashare_premix" onClick="clk(this,'megabox','megaket','<?php echo $megshr;?>');"<?php echo $chk;?>>
<label for="megashare_premix">&nbsp;Megashare.com</label>
	</td>
	<td class="tdacc">
	<div id="megabox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrmega" name="usrmega" value="">&nbsp;
	pass: <input type="password" id="passmega" name="passmega" value="">
	</div>
	<span id="megaket" ><?php if(!$megshr){echo "<div>Megashare.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $ntld=false;$chk='';
  if($showpostn){
    if(isset($_POST['netload_premix']) && $_POST['netload_premix']=='on')
	  {$chk= ' checked';$ntld=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['netload'])))
    {$chk= ' checked';$ntld=true;} 
?>

<input type="checkbox" id="netload_premix" name="netload_premix" onClick="clk(this,'netbox','netket','<?php echo $ntld;?>');"<?php echo $chk;?>>
<label for="netload_premix">&nbsp;Netload.in</label>
	</td>
	<td class="tdacc">
	<div id="netbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrnet" name="usrnet" value="">&nbsp;
	pass: <input type="password" id="passnet" name="passnet" value="">
	</div>
	<span id="netket" ><?php if(!$ntld){echo "<div>Netload.in premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $gigasz=false;$chk='';
  if($showpostn){
    if(isset($_POST['gigasize_premix']) && $_POST['gigasize_premix']=='on')
	  {$chk= ' checked';$gigasz=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['gigasize'])))
    {$chk= ' checked';$gigasz=true;} 
?>

<input type="checkbox" id="gigasize_premix" name="gigasize_premix" onClick="clk(this,'gzbox','gzket','<?php echo $gigasz;?>');"<?php echo $chk;?>>
<label for="gigasize_premix">&nbsp;Gigasize.com</label>
	</td>
	<td class="tdacc">
	<div id="gzbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrgz" name="usrgz" value="">&nbsp;
	pass: <input type="password" id="passgz" name="passgz" value="">
	</div>
	<span id="gzket" ><?php if(!$gigasz){echo "<div>Gigasize.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $shareonln=false;$chk='';
  if($showpostn){
    if(isset($_POST['share_online_premix']) && $_POST['share_online_premix']=='on')
	  {$chk= ' checked';$shareonln=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['share_online'])))
    {$chk= ' checked';$shareonln=true;} 
?>

<input type="checkbox" id="share_online_premix" name="share_online_premix" onClick="clk(this,'shobox','shoket','<?php echo $shareonln;?>');"<?php echo $chk;?>>
<label for="share_online_premix">&nbsp;Share-Online.com</label>
	</td>
	<td class="tdacc">
	<div id="shobox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrsho" name="usrsho" value="">&nbsp;
	pass: <input type="password" id="passsho" name="passsho" value="">
	</div>
	<span id="shoket" ><?php if(!$shareonln){echo "<div>Share-Online premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $vbulacc=false;$chk='';
   if($showpostn){
     if(isset($_POST['vBulletin_acc_premix']) && $_POST['vBulletin_acc_premix']=='on')
	  {$chk= ' checked';$vbulacc=true;}
     }elseif((isset($premium_acc))&&(isset($premium_acc['vBulletin_acc'])))
      {$chk= ' checked';$vbulacc=true;} 
?>
<input type="checkbox" id="vBulletin_acc_premix" name="vBulletin_acc_premix" onClick="clk(this,'vbulbox','vbulket','<?php echo $vbulacc;?>');"<?php echo $chk;?>>
<label for="vBulletin_acc_premix">&nbsp;vBulletin</label>
	</td>
	<td class="tdacc">
	<div id="vbulbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrvbul" name="usrvbul" value="">&nbsp;
	pass: <input type="password" id="passvbul" name="passvbul" value="">
	</div>
	<span id="vbulket" ><?php if(!$vbulacc){echo "<div>vBulletin premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	
	<tr>
	<td class="tdacc">
<?php 
  $upl_to=false; $chk='';
  if($showpostn){
   if(isset($_POST['uploaded_to_premix']) && $_POST['uploaded_to_premix']=='on')
    {$chk= ' checked';$upl_to=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['uploaded_to'])))
    {$chk= ' checked';$upl_to=true;} 
?>
<input type="checkbox" id="uploaded_to_premix" name="uploaded_to_premix" onClick="clk(this,'uptobox','uptoket','<?php echo $upl_to;?>');"<?php echo $chk;?>>
<label for="uploaded_to_premix">&nbsp;Upload.to</label>
	</td>
	<td class="tdacc">
	<div id="uptobox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrupto" name="usrupto" value="">&nbsp;
	pass: <input type="password" id="passupto" name="passupto" value="">
	</div>
	<span id="uptoket" ><?php if(!$upl_to){echo "<div>Upload.to premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	
	<tr>
	<td class="tdacc">
<?php 
  $easysh=false; $chk='';
  if($showpostn){
   if(isset($_POST['easyshare_premix']) && $_POST['easyshare_premix']=='on')
    {$chk= ' checked';$easysh=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['easyshare'])))
    {$chk= ' checked';$easysh=true;} 
?>
<input type="checkbox" id="easyshare_premix" name="easyshare_premix" onClick="clk(this,'easybox','easyket','<?php echo $easysh;?>');"<?php echo $chk;?>>
<label for="easyshare_premix">&nbsp;Easy-Share.com</label>
	</td>
	<td class="tdacc">
	<div id="easybox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usreasy" name="usreasy" value="">&nbsp;
	pass: <input type="password" id="passeasy" name="passeasy" value="">
	</div>
	<span id="easyket" ><?php if(!$easysh){echo "<div>Easy-Share.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $deposit=false; $chk='';
  if($showpostn){
   if(isset($_POST['depositfiles_premix']) && $_POST['depositfiles_premix']=='on')
    {$chk= ' checked';$deposit=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['depositfiles'])))
    {$chk= ' checked';$deposit=true;} 
?>
<input type="checkbox" id="depositfiles_premix" name="depositfiles_premix" onClick="clk(this,'deposbox','deposket','<?php echo $deposit;?>');"<?php echo $chk;?>>
<label for="depositfiles_premix">&nbsp;Depositfiles.com</label>
	</td>
	<td class="tdacc">
	<div id="deposbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrdepos" name="usrdepos" value="">&nbsp;
	pass: <input type="password" id="passdepos" name="passdepos" value="">
	</div>
	<span id="deposket" ><?php if(!$deposit){echo "<div>Depositfiles.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $hotfile=false; $chk='';
  if($showpostn){
   if(isset($_POST['hotfile_com_premix']) && $_POST['hotfile_com_premix']=='on')
    {$chk= ' checked';$hotfile=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['hotfile_com'])))
    {$chk= ' checked';$hotfile=true;} 
?>
<input type="checkbox" id="hotfile_com_premix" name="hotfile_com_premix" onClick="clk(this,'hotfilebox','hotfileket','<?php echo $hotfile;?>');"<?php echo $chk;?>>
<label for="hotfile_com_premix">&nbsp;Hotfile.com</label>
	</td>
	<td class="tdacc">
	<div id="hotfilebox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrhotfile" name="usrhotfile" value="">&nbsp;
	pass: <input type="password" id="passhotfile" name="passhotfile" value="">
	</div>
	<span id="hotfileket" ><?php if(!$hotfile){echo "<div>Hotfile.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $ulding=false; $chk='';
  if($showpostn){
   if(isset($_POST['uploading_premix']) && $_POST['uploading_premix']=='on')
    {$chk= ' checked';$ulding=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['uploading'])))
    {$chk= ' checked';$ulding=true;} 
?>
<input type="checkbox" id="uploading_premix" name="uploading_premix" onClick="clk(this,'uldingbox','uldingket','<?php echo $ulding;?>');"<?php echo $chk;?>>
<label for="uploading_premix">&nbsp;Uploading.com</label>
	</td>
	<td class="tdacc">
	<div id="uldingbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usruploading" name="usruploading" value="">&nbsp;
	pass: <input type="password" id="passuploading" name="passuploading" value="">
	</div>
	<span id="uldingket" ><?php if(!$ulding){echo "<div>Uploading.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
	<?php 
  $ff=false; $chk='';
  if($showpostn){
   if(isset($_POST['filefactory_premix']) && $_POST['filefactory_premix']=='on')
    {$chk= ' checked';$ff=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['filefactory'])))
    {$chk= ' checked';$ff=true;} 
	?>
<input type="checkbox" id="filefactory_premix" name="filefactory_premix" onClick="clk(this,'ffbox','ffket','<?php echo $ff;?>');"<?php echo $chk;?>>
<label for="filefactory_premix">&nbsp;Filefactory.com</label>
	</td>
	<td class="tdacc">
	<div id="ffbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrfilefactory" name="usrfilefactory" value="">&nbsp;
	pass: <input type="password" id="passfilefactory" name="passfilefactory" value="">
	</div>
	<span id="ffket" ><?php if(!$ff){echo "<div>Filefactory.com premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
	<?php 
  $ifit=false; $chk='';
  if($showpostn){
   if(isset($_POST['ifile_it_premix']) && $_POST['ifile_it_premix']=='on')
    {$chk= ' checked';$ifit=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['ifile_it'])))
    {$chk= ' checked';$ifit=true;} 
	?>
<input type="checkbox" id="ifile_it_premix" name="ifile_it_premix" onClick="clk(this,'ifbox','ifket','<?php echo $ifit;?>');"<?php echo $chk;?>>
<label for="ifile_it_premix">&nbsp;ifile.it</label>
	</td>
	<td class="tdacc">
	<div id="ifbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrifile" name="usrifile" value="">&nbsp;
	pass: <input type="password" id="passifile" name="passifile" value="">
	</div>
	<span id="ifket" ><?php if(!$ifit){echo "<div>ifile.it Free/premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
	<?php 
  $sendspace=false; $chk='';
  if($showpostn){
   if(isset($_POST['sendspace_premix']) && $_POST['sendspace_premix']=='on')
    {$chk= ' checked';$sendspace=true;}
  }elseif((isset($premium_acc))&&(isset($premium_acc['sendspace'])))
    {$chk= ' checked';$sendspace=true;} 
	?>
<input type="checkbox" id="sendspace_premix" name="sendspace_premix" onClick="clk(this,'sendbox','sendket','<?php echo $sendspace;?>');"<?php echo $chk;?>>
<label for="sendspace_premix">&nbsp;ifile.it</label>
	</td>
	<td class="tdacc">
	<div id="sendbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrsendspace" name="usrsendspace" value="">&nbsp;
	pass: <input type="password" id="passsendspace" name="passsendspace" value="">
	</div>
	<span id="sendket" ><?php if(!$sendspace){echo "<div>sendspace Free/premiX ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>

	</tbody></table>
	</li></ul>
</div>
</div>
</td>
</tr>


<tr>
<td class="tdacc" style="padding-left:15px;">
<?php 
  $megcook=false;$chk='';
  if($showpostn){
   if(isset($_POST['muhash_premix']) && $_POST['muhash_premix']=='on')
    {$chk= ' checked';$megcook=true;}
   }elseif((isset($mu_cookie_user_value))&&(!empty($mu_cookie_user_value)))
    {$chk= ' checked';$megcook=true;}
?>

<input type="checkbox" id="muhash_premix" name="muhash_premix" onClick="clk(this,'muacchash','muket2','<?php echo $megcook;?>');"<?php echo $chk;?>>
<label for="muhash_premix">&nbsp;Megaupload.com cookie</label>
</td>
<td class="tdacc">
	<div id="muacchash"<?php echo $styledisplay;?> class="float">
	user= <input type="text" id="muhashcookie" name="muhashcookie" value="" size="40">
	</div>
	<span id="muket2"><?php if(!$megcook){echo "<span>Megaupload.com premiX ACC cookie.</span>";}else{echo CK_EMBED;}?></span>
</td>
</tr>

<tr>
<td class="tdacc" style="padding-left:15px;">
<?php 
  $hfcook=false;$chk='';
  if($showpostn){
   if(isset($_POST['hfhash_premix']) && $_POST['hfhash_premix']=='on')
    {$chk= ' checked';$hfcook=true;}
   }elseif(isset($hf_cookie_auth_value)&&(!empty($hf_cookie_auth_value)))
    {$chk= ' checked';$hfcook=true;}
?>

<input type="checkbox" id="hfhash_premix" name="hfhash_premix" onClick="clk(this,'hfacchash','hfket2','<?php echo $hfcook;?>');"<?php echo $chk;?>>
<label for="hfhash_premix">&nbsp;Hotfile.com cookie</label>
</td>
<td class="tdacc">
	<div id="hfacchash"<?php echo $styledisplay;?> class="float">
	auth= <input type="text" id="hfhashcookie" name="hfhashcookie" value="" size="40">
	</div>
	<span id="hfket2"><?php if(!$hfcook){echo "<span>Hotfile.com premiX ACC cookie.</span>";}else{echo CK_EMBED;}?></span>
</td>
</tr>

<tr>
<td class="tdacc" style="padding-left:15px;">
<?php 
  $rscook=false;$chk='';
  if($showpostn){
   if(isset($_POST['rshash_premix']) && $_POST['rshash_premix']=='on')
    {$chk= ' checked';$rscook=true;}
   }elseif(isset($rs_cookie_enc_value)&&(!empty($rs_cookie_enc_value)))
    {$chk= ' checked';$rscook=true;}
?>

<input type="checkbox" id="rshash_premix" name="rshash_premix" onClick="clk(this,'rsacchash','rsket2','<?php echo $rscook;?>');"<?php echo $chk;?>>
<label for="rshash_premix">&nbsp;Rapidshare.com cookie</label>
</td>
<td class="tdacc">
	<div id="rsacchash"<?php echo $styledisplay;?> class="float">
	auth= <input type="text" id="rshashcookie" name="rshashcookie" value="" size="40">
	</div>
	<span id="rsket2"><?php if(!$rscook){echo "<span>Hotfile.com premiX ACC cookie.</span>";}else{echo CK_EMBED;}?></span>
</td>
</tr>


<tr>
<td class="tdacc" style="padding-left:15px;">
<?php 
  $img_hackt=false;$chk='';
  if($showpostn){
    if(isset($_POST['imghack_premix']) && $_POST['imghack_premix']=='on')
	 {$chk= ' checked';$img_hackt=true;}
  }elseif(isset($imageshack_acc)&&!empty($imageshack_acc["user"])&&!empty($imageshack_acc["pass"]))
    {$chk= ' checked';$img_hackt=true;} 
?>

<input type="checkbox" id="imghack_premix" name="imghack_premix" onClick="clk(this,'imghackbox','imghackket','<?php echo $img_hackt;?>');"<?php echo $chk;?>>
<label for="imghack_premix">&nbsp;Imageshack Torrent</label>
</td>
<td class="tdacc">
	<div id="imghackbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrimghk" name="usrimghk" value="">&nbsp;
	pass: <input type="password" id="passimghk" name="passimghk" value="">
	</div>
	<span id="imghackket" ><?php if(!$img_hackt){echo "<span>Imagehack Torrent premiX ACC</span>";}else{echo ACC_EMBED;}?></span>

</td>
</tr>

<tr>
<td align="left" colspan="3">
<?php
$exist_accrs = (isset($premium_acc["rs_com"]) ? 
  ( isset($premium_acc["rs_com"]["user"]) && $premium_acc["rs_com"]["user"]!='' && isset($premium_acc["rs_com"]["pass"]) && $premium_acc["rs_com"]["pass"]!='' ? true : 
    (isset($premium_acc["rs_com"][0]["user"]) && $premium_acc["rs_com"][0]["user"]!='' && isset($premium_acc["rs_com"][0]["pass"]) && $premium_acc["rs_com"][0]["pass"]!='' ? true : false)
  ) : false );
?>
<div id="parentnfoconfig">
<div id="nfoconfig" align="center" style="width:250px;padding:4px 0 5px 8px; margin-left:0px; display:<?php echo (($ch_curl==1) && ($exist_accrs)?"":"none;");?>">
 <a href="javascript:void(0)" onclick="<?php echo ($ajax_serverfiles?'stacc(); this.blur()':'alert(\'Ajax Disabled\');')?>" style="float:right;margin-top:-1px;" title="refresh"><small>refresh</small></a>
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



<tr><td colspan="2"><div><hr id="grs1"></div></td></tr>
<!--================Upload-Acc Config====================--->

<tr>
<td class="tdacc">
<h1>UPLOAD ACC CONFIG</h1></td><td>&nbsp;</td></tr>
<tr>
<td class="tdacc">
<input type="checkbox" id="useulcc" name="useulcc" onClick="displ=this.checked?'':'none';document.getElementById('divulccL').style.display=displ;" <?php if($showpostn){if(isset($_POST['useulcc']) && $_POST['useulcc']=='on')echo ' checked';}elseif((isset($upload_acc))&&(is_array($upload_acc))) echo ' checked';?>><label for=useulcc><b>Use Upload acc</b></label>
<div class="spc">&nbsp;</div>
</td>
<td class="tdacc"><span>Activate UCC Array</span></td></tr>

<tr>
<td colspan="2">
<div id="divulccL" style="display:<?php if($showpostn){if(isset($_POST['useulcc']) && $_POST['useulcc']=='on'){echo '';}else{echo 'none';}}elseif((isset($upload_acc))&&(is_array($upload_acc))){echo '';}else{echo 'none';}?>"><div class="float">
<ul class="pret"><li class="ndot">
	<table border="1px"><tbody>
	<tr>
	<td class="tdacc">
<?php 
 $mf_up=false;$chk='';
 if($showpostn){
   if(isset($_POST['mediafire_up_upload']) && $_POST['mediafire_up_upload']=='on')
    {$chk= ' checked';$mf_up=true;}
 }elseif(isset($upload_acc['mediafire_up']))
    {$chk= ' checked';$mf_up=true;}
?>
<input type="checkbox" id="mediafire_up_upload" name="mediafire_up_upload" onClick="clk(this,'mfbox','mfket','<?php echo $mf_up;?>');"<?php echo $chk;?>>
<label for="mediafire_up_upload">&nbsp;Mediafire.com</label>
	</td>
	<td class="tdacc">
	<div id="mfbox"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrmf_up" name="usrmf_up" value="">&nbsp;
	pass: <input type="password" id="passmf_up" name="passmf_up" value="">
	</div>
	<span id="mfket"><?php if(!$mf_up){echo "<div>Mediafire Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $rs_com_coll_up=false;$chk='';
  if($showpostn){
    if(isset($_POST['rs_com_coll_upload']) && $_POST['rs_com_coll_upload']=='on')
	 {$chk= ' checked';$rs_com_coll_up=true;}
  }elseif(isset($upload_acc['rs_com_coll']))
    {$chk= ' checked'; $rs_com_coll_up=true;} 
?>
<input type="checkbox" id="rs_com_coll_upload" name="rs_com_coll_upload" onClick="clk(this,'rs_com_coll_box','rs_com_coll_ket','<?php echo $rs_com_coll_up;?>');"<?php echo $chk;?>>
<label for="rs_com_coll_upload">&nbsp;Rapidshare.com Col-Zone</label>
	</td>
	<td class="tdacc">
	<div id="rs_com_coll_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrrs_com_coll_up" name="usrrs_com_coll_up" value="">&nbsp;
	pass: <input type="password" id="passrs_com_coll_up" name="passrs_com_coll_up" value="">
	</div>
	<span id="rs_com_coll_ket" ><?php if(!$rs_com_coll_up){echo "<div>Rapidshare.com Collector Zone Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $rs_com_pre_up=false;$chk='';
  if($showpostn){
    if(isset($_POST['rs_com_pre_upload']) && $_POST['rs_com_pre_upload']=='on')
	  {$chk= ' checked';$rs_com_pre_up=true;}
  }elseif(isset($upload_acc['rs_com_pre']))
    {$chk= ' checked';$rs_com_pre_up=true;} 
?>

<input type="checkbox" id="rs_com_pre_upload" name="rs_com_pre_upload" onClick="clk(this,'rs_com_pre_box','rs_com_pre_ket','<?php echo $rs_com_pre_up;?>');"<?php echo $chk;?>>
<label for="rs_com_pre_upload">&nbsp;Rapidshare.com Pre</label>
	</td>
	<td class="tdacc">
	<div id="rs_com_pre_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrrs_com_pre_up" name="usrrs_com_pre_up" value="">&nbsp;
	pass: <input type="password" id="passrs_com_pre_up" name="passrs_com_pre_up" value="">
	</div>
	<span id="rs_com_pre_ket" ><?php if(!$rs_com_pre_up){echo "<div>Rapidshare.com Pre Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $mu_pre_up=false;$chk='';
  if($showpostn){
    if(isset($_POST['mu_pre_upload']) && $_POST['mu_pre_upload']=='on')
	  {$chk= ' checked';$mu_pre_up=true;}
  }elseif(isset($upload_acc['mu_pre']))
      {$chk= ' checked';$mu_pre_up=true;} 
?>

<input type="checkbox" id="mu_pre_upload" name="mu_pre_upload" onClick="clk(this,'mu_pre_box','mu_pre_ket','<?php echo $mu_pre_up;?>');"<?php echo $chk;?>>
<label for="mu_pre_upload">&nbsp;Megaupload.com Pre</label>
	</td>
	<td class="tdacc">
	<div id="mu_pre_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrmu_pre_up" name="usrmu_pre_up" value="">&nbsp;
	pass: <input type="password" id="passmu_pre_up" name="passmu_pre_up" value="">
	</div>
	<span id="mu_pre_ket" ><?php if(!$mu_pre_up){echo "<div>Megaupload.com Pre Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $hf_com_up=false;$chk='';
  if($showpostn){
    if(isset($_POST['hf_com_upload']) && $_POST['hf_com_upload']=='on')
	  {$chk= ' checked';$hf_com_up=true;}
  }elseif(isset($upload_acc['hf_com']))
    {$chk= ' checked';$hf_com_up=true;} 
?>

<input type="checkbox" id="hf_com_upload" name="hf_com_upload" onClick="clk(this,'hf_com_box','hf_com_ket','<?php echo $hf_com_up;?>');"<?php echo $chk;?>>
<label for="hf_com_upload">&nbsp;Hotfile.com</label>
	</td>
	<td class="tdacc">
	<div id="hf_com_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrhf_com_up" name="usrhf_com_up" value="">&nbsp;
	pass: <input type="password" id="passhf_com_up" name="passhf_com_up" value="">
	</div>
	<span id="hf_com_ket" ><?php if(!$hf_com_up){echo "<div>Hotfile.com Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $easysh_mem_up=false;$chk='';
   if($showpostn){
     if(isset($_POST['easysh_mem_upload']) && $_POST['easysh_mem_upload']=='on')
	  {$chk= ' checked';$easysh_mem_up=true;}
     }elseif(isset($upload_acc['easysh_mem']))
      {$chk= ' checked';$easysh_mem_up=true;} 
?>
<input type="checkbox" id="easysh_mem_upload" name="easysh_mem_upload" onClick="clk(this,'easysh_mem_box','easysh_mem_ket','<?php echo $easysh_mem_up;?>');"<?php echo $chk;?>>
<label for="easysh_mem_upload">&nbsp;easy-share.com</label>
	</td>
	<td class="tdacc">
	<div id="easysh_mem_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usreasysh_mem_up" name="usreasysh_mem_up" value="">&nbsp;
	pass: <input type="password" id="passeasysh_mem_up" name="passeasysh_mem_up" value="">
	</div>
	<span id="easysh_mem_ket" ><?php if(!$easysh_mem_up){echo "<div>easy-share.com Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $deposit_up_up=false;$chk='';
   if($showpostn){
     if(isset($_POST['deposit_up_upload']) && $_POST['deposit_up_upload']=='on')
	  {$chk= ' checked';$deposit_up_up=true;}
     }elseif(isset($upload_acc['deposit_up']))
      {$chk= ' checked';$deposit_up_up=true;} 
?>
<input type="checkbox" id="deposit_up_upload" name="deposit_up_upload" onClick="clk(this,'deposit_up_box','deposit_up_ket','<?php echo $deposit_up_up;?>');"<?php echo $chk;?>>
<label for="deposit_up_upload">&nbsp;depositfiles.com</label>
	</td>
	<td class="tdacc">
	<div id="deposit_up_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usrdeposit_up_up" name="usrdeposit_up_up" value="">&nbsp;
	pass: <input type="password" id="passdeposit_up_up" name="passdeposit_up_up" value="">
	</div>
	<span id="deposit_up_ket" ><?php if(!$deposit_up_up){echo "<div>depositfiles.com Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	<tr>
	<td class="tdacc">
<?php 
  $uploading_up_up=false;$chk='';
   if($showpostn){
     if(isset($_POST['uploading_up_upload']) && $_POST['uploading_up_upload']=='on')
	  {$chk= ' checked';$uploading_up_up=true;}
     }elseif(isset($upload_acc['uploading_up']))
      {$chk= ' checked';$uploading_up_up=true;} 
?>
<input type="checkbox" id="uploading_up_upload" name="uploading_up_upload" onClick="clk(this,'uploading_up_box','uploading_up_ket','<?php echo $uploading_up_up;?>');"<?php echo $chk;?>>
<label for="uploading_up_upload">&nbsp;uploading.com</label>
	</td>
	<td class="tdacc">
	<div id="uploading_up_box"<?php echo $styledisplay;?> class="float">
	user: <input type="text" id="usruploading_up_up" name="usruploading_up_up" value="">&nbsp;
	pass: <input type="password" id="passuploading_up_up" name="passuploading_up_up" value="">
	</div>
	<span id="uploading_up_ket" ><?php if(!$uploading_up_up){echo "<div>uploading.com Upload ACC</div>";}else{echo ACC_EMBED;}?></span>
	</td>
	</tr>
	</tbody></table>
	</li></ul>
</div>
</div>
</td>
</tr>

<!---================End Upload-Acc Config=====================--->

<tr><td colspan="2"><div><hr id="grs1"></div></td></tr>





<!-- ==========================START LIMITATION-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trlimit" onclick="trclick(this);">
<td colspan="2" class="tdacc"><h1><span id="nod_trlimit"></span>LIMITATION</h1>
</td></tr>
<tbody id="bd_trlimit" <?php echo (isset($_COOKIE["trlimit"])?($_COOKIE["trlimit"]==1?$styledisplay:''):$styledisplay);?>>

<tr>
<td class="tdacc">
<input type="checkbox" id=limitbyip name="limitbyip" onClick="var displ=this.checked?'':'none';document.getElementById('limitip').style.display=displ;document.getElementById('limitip2').style.display=displ;"<?php $limitipchecked=true;if($showpostn){if(isset($_POST['limitbyip']) && $_POST['limitbyip']=='on'){echo ' checked';$limitipchecked=false;}}elseif($limitbyip){echo ' checked';$limitipchecked=false;} ?>><label for=limitbyip>&nbsp;<b>Limit Leeching per IP</b></label></td>
<td class="tdacc">This feature only enable if you have any premium acc in your RL.
</td>
</tr>
<tr>
<td class="tdacc" id="limitip"<?php if($limitipchecked) echo $styledisplay; ?>><div style="padding-left:20px;margin-top: 0px;">
<input type="text" name="max_free_dl" size="4" maxlength="4" value="<?php if($showpostn){if(!empty($_POST['max_free_dl']))echo $_POST['max_free_dl'];}else echo $maximum_free_downloads; ?>">&nbsp;<b class="g">Times</b> leeching per IP<br>
<input type="text" name="delayip" size="4" maxlength="3" value="<?php if($showpostn){if(!empty($_POST['delayip'])){echo $_POST['delayip'];}else{echo $delay_per_ip;}}else{echo $delay_per_ip;}?>">&nbsp;<b class="g">Hours</b> delay per IP</div>
</td>
<td class="tdacc" id="limitip2" style="display:<?php echo ($limitipchecked?'none':'');?>"><div style="padding-left:20px; margin: -11px;">
<span>How many times users can use your RL per IP</span>
<div style="height:5px;">&nbsp;</div>
<span>How long it will recycle allowable download per IP</span>
</div>
</td>
</tr>

<tr>
<td class="tdacc">
<input type="checkbox" id=downloadLimitbyip name="downloadLimitbyip" onClick="var displ=this.checked?'':'none';document.getElementById('downloadLimitip').style.display=displ;document.getElementById('downloadLimitip2').style.display=displ;"<?php $downloadLimitipchecked=true;if($showpostn){if(isset($_POST['downloadLimitbyip']) && $_POST['downloadLimitbyip']=='on'){echo ' checked';$downloadLimitipchecked=false;}}elseif($downloadLimitbyip){echo ' checked';$downloadLimitipchecked=false;} ?>><label for=downloadLimitbyip>&nbsp;<b>Limit Leeching by IP</b></label></td>
<td class="tdacc">This feature always enable although you don't use <span class="r">Rapidshare.com ACC or Megaupload ACC</b></td>
</td>
<tr>
<td class="tdacc" id="downloadLimitip"<?php if($downloadLimitipchecked) echo $styledisplay; ?>><div style="padding-left:20px;margin-top: 0px;">
<input type="text" name="dl_limit_max" size="4" maxlength="4" value="<?php if($showpostn){if(!empty($_POST['dl_limit_max']))echo $_POST['dl_limit_max'];}else echo $downloadsPerIP; ?>">&nbsp;<b class="g">Times</b> leeching per IP<br>
<input type="text" name="dl_per_ip" size="4" maxlength="6" value="<?php if($showpostn){if(!empty($_POST['dl_per_ip'])){echo $_POST['dl_per_ip'];}else{echo $downloadDelayPerIP;}}else{echo $downloadDelayPerIP;}?>">&nbsp;<b class="g">Seconds</b> delay per IP</div>
</td>
<td class="tdacc" id="downloadLimitip2" style="display:<?php echo ($downloadLimitipchecked?'none':'');?>"><div style="padding-left:20px; margin: 1px;">
<span>How many times users can use your RL per IP</span>
<div style="height:5px;">&nbsp;</div>
<span>How long it will limit download per IP</span>

</td>

</tr>

<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="audl" size="5" maxlength="4" value="<?php if($showpostn){if(!empty($_POST['audl']))echo $_POST['audl'];}else echo $audl; ?>">&nbsp;<b class="g">Link</b> (AuDL)</td>
<td class="tdacc">How many link allow to Auto-download work. set to 0 or blank to disable</td>
</tr><tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="auul" size="5" maxlength="4" value="<?php if($showpostn){if(!empty($_POST['auul']))echo $_POST['auul'];}else echo $auul; ?>">&nbsp;<b class="g">Link</b> (AuUL)</td>
<td class="tdacc">How many file allow to Auto-upload work. set to 0 or blank to disable</td>
</tr>
<tr>

<td class="tdacc"><input type="checkbox" id="limitbytraffic" name="limitbytraffic" onClick="var displ=this.checked?'':'none';document.getElementById('limittraf').style.display=displ;document.getElementById('limittraf2').style.display=displ;"<?php $limitbytrafficchk=true;if($showpostn){if(isset($_POST['limitbytraffic']) && $_POST['limitbytraffic']=='on'){echo ' checked';$limitbytrafficchk=false;}}elseif($limitbytraffic){echo ' checked';$limitbytrafficchk=false;} ?>><label for="limitbytraffic">&nbsp;<b>Limit Traffic</b></label></td>
<td class="tdacc">This feature Limit Traffic in your RL
</td>
</tr>
<tr>
<td class="tdacc" id="limittraf"<?php if($limitbytrafficchk) echo $styledisplay; ?>>
<script type="text/javascript" src="misc/date.js"></script>
<link rel="stylesheet" type="text/css" href="misc/date.css" />

<div style="padding-left:20px;">
<input type="text" name="max_trafic" size="5" value="<?php if($showpostn){if(!empty($_POST['max_trafic']))echo $_POST['max_trafic'];}else echo $max_trafic; ?>">&nbsp;<b class="g">MB</b> Quota Traffic<br>
<div style="float:left;"><input name="YetAnotherDate" type="text" size="10" maxlength="10" value="<?php if($showpostn){if(!empty($_POST['date_trafic'])){echo $_POST['date_trafic'];}else{echo $date_trafic;}}else{echo $date_trafic;}?>"></div>
<div class="pngDate" onclick="displayDatePicker('YetAnotherDate', false, 'dmy', '/');"><img title="pick date" src="misc/calendar.png" height="15">&nbsp;</div>
<b class="g" title="Day/Month/Year">DMY</b> Expire Due-date
<div></div><div style="float:left;"><input type="text" name="day_reset_trafic" size="5" maxlength="2" value="<?php if($showpostn){if(!empty($_POST['day_reset_trafic']))echo $_POST['day_reset_trafic'];}else echo $day_reset_trafic; ?>">&nbsp;<b class="g">day<small>(s)</small></b> Reset Traffic</div>
</div>
</td>
<td class="tdacc" id="limittraf2" style="display:<?php echo ($limitbytrafficchk?'none':'');?>">
<div id="curtrafic" align="center" style="position:absolute;float:left;width:200px;padding-left:270px; margin-top:-10px; display:<?php echo ($limitbytraffic?"":"none;");?>"><div id="trafwaiting" align="left" style="display:none;"></div></div>
<div style="padding-left:20px; margin-top: 0px;">
<span>Quota Traffic allowed. Currently traffic:&nbsp;<span id="cur_traffic"><b class="g" style="position:absolute;"><span id="trafshowresults" style="position:float:center;display:;"><?php $cur_trf = get_traffic(TRAFFIC_LST); echo bytesToKbOrMbOrGb($cur_trf[0]);?></span>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="<?php echo ($ajax_serverfiles?'if(confirm(\'This will reset Current Traffic, proceed?\')){trafst();}' : 'alert(\'Ajax Disabled\');');?>;"><small>reset</small></a></b></span></span> 
<div style="height:3px;">&nbsp;</div>
<div style="float:left;">The D-Day traffic quota will expired</div><br><div style="height:3px;">&nbsp;</div>
<div style="float:left;">Auto-Reset traffic. set to 0 or blank to disable.
<?php if($day_reset_trafic > 0) {
 echo "<small class='g'>remain: " . sec2time(timeremain_traffic($day_reset_trafic, $cur_trf[1])) . "</small>";
 }
 ?>
</div>
<?php if($ajax_serverfiles){
?>
<script type="text/javascript">function trafst() {document.trew.submit("posttraffic","trafwaiting","trafshowresults",""); }</script>
<?php } ?>
</div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="limited_edition" name="limited_edition" onClick="var displ=this.checked?'':'none';document.getElementById('limitauthip').style.display=displ;document.getElementById('limitauthip2').style.display=displ;"<?php $limitauthbyip=true;if($showpostn){if(isset($_POST['limited_edition']) && $_POST['limited_edition']=='on'){echo ' checked';$limitauthbyip=false;}}elseif($limited_edition){echo ' checked';$limitauthbyip=false;} ?>><label for="limited_edition">&nbsp;<b>Limit Authorize by IP</b></label></td>
<td class="tdacc">Limit access to your RL by authorized IP only.</td>
</tr>
<tr>
<td class="tdacc" id="limitauthip"<?php if($limitauthbyip) echo $styledisplay; ?>><div style="padding-left:15px;">
<input type="text" name="list_allow_ip" value="<?php if($showpostn){if(!empty($_POST['list_allow_ip'])){echo $_POST['list_allow_ip'];}else{echo (trim($list_allow_ip)!='' ? $list_allow_ip:get_real_ip());}}else{echo (trim($list_allow_ip)!='' ? $list_allow_ip:get_real_ip());}?>"> White List IP<br>
<input type="text" name="list_baned_ip" value="<?php if($showpostn){if(!empty($_POST['list_baned_ip'])){echo $_POST['list_baned_ip'];}else{echo $list_baned_ip;}}else{echo $list_baned_ip;}?>"> Black Listed IP</div>
</td>
<td class="tdacc" id="limitauthip2"<?php if($limitauthbyip) echo $styledisplay; ?>><div style="padding-left:15px; margin-top: 0px;">
<span>White list IP. eg. <b class="g"><?php echo get_real_ip();?></b>, 111.111.111.111, 255.*.*.*, // <span class="r">never blank this if you set limitation=true</span></span>
<div style="height:5px;">&nbsp;</div>
<span>Banned / Blacklist IP,!. eg. 111.111.111.111, 222.*.*.*, 212.212.212.*</span>
</div></td>
</tr>


<tr>
<?php 
$geo_class = CLASS_DIR."geoip.inc.php"; $geo_db = CLASS_DIR."GeoIP.dat"; $msg_CID = "";
if(!file_exists($geo_class) || !file_exists($geo_db)) {
  $msg_CID = " <span class='g'><b>{$geo_class}</b> OR <b>{$geo_db}</b> Not Found.</span>";
}
?>
<td class="tdacc"><input type="checkbox" id="limited_area" name="limited_area" onClick="var displ=this.checked?'':'none';document.getElementById('limitautharea').style.display=displ;document.getElementById('limitautharea2').style.display=displ;"<?php $limitauthbyarea=true;if($showpostn){if(isset($_POST['limited_area']) && $_POST['limited_area']=='on' && $msg_CID==""){echo ' checked';$limitauthbyarea=false;}}elseif($limited_area && $msg_CID==""){echo ' checked';$limitauthbyarea=false;} echo ($msg_CID!="" ? " disabled='true'":"");?>><label for="limited_area">&nbsp;<b>Limit Authorize by Country ID Area</b></label></td>
<td class="tdacc">Limit access to your RL by Country ID.<?php echo $msg_CID;?></td>
</tr>
<tr>
<td class="tdacc" id="limitautharea"<?php if($limitauthbyarea) echo $styledisplay; ?>><div style="padding-left:15px;">
<input type="text" name="allow_CID" value="<?php if($showpostn){if(!empty($_POST['allow_CID'])){echo $_POST['allow_CID'];}else{echo $allow_CID;}}else{echo $allow_CID;}?>"> White List CID<br>
<input type="text" name="baned_CID" value="<?php if($showpostn){if(!empty($_POST['baned_CID'])){echo $_POST['baned_CID'];}else{echo $baned_CID;}}else{echo $baned_CID;}?>"> Black CID</div>
</td>
<td class="tdacc" id="limitautharea2"<?php if($limitauthbyarea) echo $styledisplay; ?>><div style="padding-left:15px; margin-top: 0px;">
<span>White list Country ID, set blank mean all country is allowed. eg. 'ID', 'MY' // allow only Indonesia And Malaysia</span>
<div style="height:5px;">&nbsp;</div>
<span>Blacklist Country ID, set blank mean no country is banned. eg. 'US' // all country from US being banned.</span>
</div></td>
</tr>




<tr>
<td class="tdacc"><input type="checkbox" id="limit_timework" name="limit_timework" onClick="var displ=this.checked?'':'none';document.getElementById('limittdtime').style.display=displ;document.getElementById('limittdtime2').style.display=displ;"<?php $limitedtime=true;if($showpostn){if(isset($_POST['limit_timework']) && $_POST['limit_timework']=='on'){echo ' checked';$limitedtime=false;}}elseif($limit_timework){echo ' checked';$limitedtime=false;} ?>><label for="limit_timework">&nbsp;<b>Limit by Time</b></label></td>
<td class="tdacc">Limit access your RL by Time.</td>
</tr>
<tr>
<td class="tdacc" id="limittdtime"<?php if($limitedtime) echo $styledisplay; ?>><div style="padding-left:15px;">
<input type="text" size="9" name="workstart" value="<?php if($showpostn){if(!empty($_POST['workstart']))echo $_POST['workstart'];}else echo $workstart; ?>">&nbsp;&nbsp;-&nbsp;
<input type="text" size="9" name="workend" value="<?php if($showpostn){if(!empty($_POST['workend'])){echo $_POST['workend'];}else{echo $workend;}}else{echo $workend;}?>"></div>
</td>
<td class="tdacc" id="limittdtime2"<?php if($limitedtime) echo $styledisplay; ?>><div style="padding-left:15px; margin-top: 0px;">
<span>Your RL Start & End to Work. eg. 08:00:00 - 17:00:00</span>
</div></td>
</tr>

<tr>
<td class="tdacc"><div class="spc">&nbsp;</div><div style="padding-left:5px;">
<input type="text" name="auto_delet" maxlength="5" size="5" value="<?php if($showpostn){if(!empty($_POST['auto_delet']))echo $_POST['auto_delet'];}else{echo $auto_del_time;}?>">&nbsp;<b class="g">Hours</b> auto delete</td>
<td class="tdacc">Auto delete file after leeching. set to 0 or blank to disable</td>
</tr><tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="limitsize" size="5" maxlength="4" value="<?php if($showpostn){if(!empty($_POST['limitsize']))echo $_POST['limitsize'];}else echo $limitsize; ?>">&nbsp;<b class="g">MB</b> maximum file size</td>
<td class="tdacc">Limit transfering each file by <b class="o">maximum</b> filesize. set to 0 or blank to disable</td>
</tr><tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="lowlimitsize" size="5" maxlength="4" value="<?php if($showpostn){if(!empty($_POST['lowlimitsize']))echo $_POST['lowlimitsize'];}else echo $lowlimitsize; ?>">&nbsp;<b class="g">MB</b> minimum file size</td>
<td class="tdacc">Limit transfering each file by <b class="o">minimum</b> filesize. set to 0 or blank to disable</td>
</tr><tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="storage_limit" size="5" maxlength="6" value="<?php if($showpostn){if(!empty($_POST['storage_limit']))echo $_POST['storage_limit'];}else echo $storage_limit; ?>">&nbsp;<b class="g">MB</b> storage limit</td>
<td class="tdacc">Limit storage in Server Files. set to 0 or blank to disable</td>
</tr><tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="cpulimit" size="5" maxlength="3" value="<?php if($showpostn){if(!empty($_POST['cpulimit']))echo $_POST['cpulimit'];}else echo $ServerLoadAllowed; ?>">&nbsp;<b class="g">%</b> CPU load</td>
<td class="tdacc">Limit CPU Server load. set to 0 or blank to disable</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="serverjob" size="5" maxlength="3" value="<?php if($showpostn){if(!empty($_POST['serverjob']))echo $_POST['serverjob'];}else echo $MaxServerJob; ?>">&nbsp;Maximum Server Job</td>
<td class="tdacc">Limit Server Job. set to 0 or blank to disable</td>
</tr>
</tbody>
<!-- ==========================END LIMITATION CONFIG -->



<tr><td colspan="2"><div><hr id="grs1"></div></td></tr>





<!-- ==========================START FILES-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trfiles" onclick="trclick(this);">
<td class="tdacc"><h1><span id="nod_trfiles"></span>FILES CONFIG</h1></td><td>&nbsp;</td></tr>
<tbody id="bd_trfiles" <?php echo (isset($_COOKIE["trfiles"])?($_COOKIE["trfiles"]==1?$styledisplay:''):$styledisplay);?>>
<tr>
<td class="tdacc"><input type="checkbox" id=dirchange name="dirchange"<?php if($showpostn){if(isset($_POST['dirchange']) && $_POST['dirchange']=='on')echo ' checked';}elseif($download_dir_is_changeable) echo ' checked'; ?>><label for=dirchange>&nbsp;Download Directory is changeable</label></td>
<td class="tdacc">Don't check this to disallow users to change the download directory!</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="dirchangeaudl" name="dirchangeaudl"<?php if($showpostn){if(isset($_POST['dirchangeaudl']) && $_POST['dirchangeaudl']=='on')echo ' checked';}elseif($maysaveto) echo ' checked'; ?>><label for="dirchangeaudl">&nbsp;Download Dir is changeable&nbsp;(audl.php)</label></td>
<td class="tdacc">Don't check this to disallow users to change the download directory in <b>audl.php</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="dir" value="<?php if($showpostn){if(!empty($_POST['dir']))echo $_POST['dir'];}elseif($download_dir) echo ($download_dir); ?>">&nbsp;Download directory</div></td>
<td class="tdacc">This is where the downloaded files are saved</td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="prefix" value="<?php if($showpostn){if(!empty($_POST['prefix']))echo $_POST['prefix'];}elseif(!empty($rename_prefix)) echo $rename_prefix; ?>">&nbsp;Rename prefix</div></td>
<td class="tdacc">Example: <b class="o">www.mysite.com</b> =&gt; <b class="o">www.mysite.com_</b><b class="g">file_name.rar</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="suffix" value="<?php if($showpostn){if(!empty($_POST['suffix']))echo $_POST['suffix'];}elseif(!empty($rename_suffix)) echo $rename_suffix;?>">&nbsp;Rename suffix</div></td>
<td class="tdacc">Example: <b class="o">www.mysite.com</b> =&gt; <b class="g">file_name</b><b class="o">_www.mysite.com</b><b class="g">.rar</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="extension" size="7" maxlength="7" value="<?php if($showpostn){if(!empty($_POST['extension']))echo $_POST['extension'];}elseif(!empty($add_ext_5city)) echo $add_ext_5city; ?>">&nbsp;Add filename extension</div></td>
<td class="tdacc">Example: <b class="o">ccpb</b> =&gt; <b class="g">file_name.rar</b><b class="o">.ccpb</b></td>
</tr>
<tr>
<td class="tdacc"><div style="padding-left:5px;"><input type="text" name="autorenfiletype" size="2" maxlength="3" value="<?php if($showpostn){if(!empty($_POST['autorenfiletype']))echo $_POST['autorenfiletype'];}elseif(is_string($rename_these_filetypes_to)) echo substr($rename_these_filetypes_to,1); ?>">&nbsp;Extension preventive</div></td>
<td class="tdacc">To prevent users to rename the file with forbidden extension (.php, .asp, .cgi, etc)</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=showallfiles name="showallfiles"<?php if($showpostn){if(isset($_POST['showallfiles']) && $_POST['showallfiles']=='on')echo ' checked';}elseif($show_all) echo ' checked'; ?>><label for=showallfiles>&nbsp;Show all files</label></td>
<td class="tdacc">To show all files in the catalog</td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="disall" name="disall" onClick="var displ=this.checked?'none':'';document.getElementById('disalldiv').style.display=displ;document.getElementById('dis1').style.display=displ;"<?php $disall=false;if($showpostn){if(isset($_POST['disall']) && $_POST['disall']=='on'){echo ' checked';$disall=true;}}elseif($disable_action){echo ' checked';$disall=true;} ?>><label for=disall>&nbsp;Disable Actions files.</label></td>
<td class="tdacc">Disable upload, split, zip, rename, delete, etc.</td>
</tr>
<tr>
<td class="tdacc" id="disalldiv" style="display:<?php echo ($disable_action?'none':'');?>">
<div class="div_left_ndot"><ul>
<li class="ndot">
<input type="checkbox" id="act_upload" name="act_upload"<?php if($showpostn){if(isset($_POST['act_upload']) && $_POST['act_upload']=='on')echo ' checked';}elseif($disable_to["act_upload"]) echo ' checked'; ?>><label for="act_upload">&nbsp;Disable Upload</label></li>
<li class="ndot">
<input type="checkbox" id="act_ftp" name="act_ftp"<?php if($showpostn){if(isset($_POST['act_ftp']) && $_POST['act_ftp']=='on')echo ' checked';}elseif($disable_to["act_ftp"]) echo ' checked'; ?>><label for=act_ftp>&nbsp;Disable Ftp</label></li>
<li class="ndot">
<input type="checkbox" id="act_mail" name="act_mail"<?php if($showpostn){if(isset($_POST['act_mail']) && $_POST['act_mail']=='on')echo ' checked';}elseif($disable_to["act_mail"]) echo ' checked'; ?>><label for=act_mail>&nbsp;Disable Email</label></li>
<li class="ndot">
<input type="checkbox" id="act_boxes" name="act_boxes"<?php if($showpostn){if(isset($_POST['act_boxes']) && $_POST['act_boxes']=='on')echo ' checked';}elseif($disable_to["act_boxes"]) echo ' checked'; ?>><label for=act_boxes>&nbsp;Disable Mass Submits</label></li>
<li class="ndot">
<input type="checkbox" id="act_split" name="act_split"<?php if($showpostn){if(isset($_POST['act_split']) && $_POST['act_split']=='on')echo ' checked';}elseif($disable_to["act_split"]) echo ' checked'; ?>><label for=act_split>&nbsp;Disable Split</label></li>
<li class="ndot">
<input type="checkbox" id="act_merge" name="act_merge"<?php if($showpostn){if(isset($_POST['act_merge']) && $_POST['act_merge']=='on')echo ' checked';}elseif($disable_to["act_merge"]) echo ' checked'; ?>><label for=act_merge>&nbsp;Disable Merge</label></li>
<li class="ndot">
<input type="checkbox" id="act_md5" name="act_md5"<?php if($showpostn){if(isset($_POST['act_md5']) && $_POST['act_md5']=='on')echo ' checked';}elseif($disable_to["act_md5"]) echo ' checked'; ?>><label for=act_md5>&nbsp;Disable MD5 / Changer</label></li>
<li class="ndot">
<input type="checkbox" id="act_pack" name="act_pack"<?php if($showpostn){if(isset($_POST['act_pack']) && $_POST['act_pack']=='on')echo ' checked';}elseif($disable_to["act_pack"]) echo ' checked'; ?>><label for=act_pack>&nbsp;Disable Pack</label></li>
<li class="ndot">
<input type="checkbox" id="act_zip" name="act_zip"<?php if($showpostn){if(isset($_POST['act_zip']) && $_POST['act_zip']=='on')echo ' checked';}elseif($disable_to["act_zip"]) echo ' checked'; ?>><label for=act_zip>&nbsp;Disable Zip</label></li>
<li class="ndot">
<input type="checkbox" id="act_unzip" name="act_unzip"<?php if($showpostn){if(isset($_POST['act_unzip']) && $_POST['act_unzip']=='on')echo ' checked';}elseif($disable_to["act_unzip"]) echo ' checked'; ?>><label for=act_unzip>&nbsp;Disable UnZip</label></li>
<li class="ndot">
<input type="checkbox" id="act_rename" name="act_rename"<?php if($showpostn){if(isset($_POST['act_rename']) && $_POST['act_rename']=='on')echo ' checked';}elseif($disable_to["act_rename"]) echo ' checked'; ?>><label for=act_rename>&nbsp;Disable Rename</label></li>
<li class="ndot">
<input type="checkbox" id="act_mrename" name="act_mrename"<?php if($showpostn){if(isset($_POST['act_mrename']) && $_POST['act_mrename']=='on')echo ' checked';}elseif($disable_to["act_mrename"]) echo ' checked'; ?>><label for=act_mrename>&nbsp;Disable Mass-Rename</label></li>
<li class="ndot">
<input type="checkbox" id="act_delete" name="act_delete"<?php if($showpostn){if(isset($_POST['act_delete']) && $_POST['act_delete']=='on')echo ' checked';}elseif($disable_to["act_delete"]) echo ' checked'; ?>><label for=act_delete>&nbsp;Disable Delete</label></li>
</div>
</td>
<td class="tdacc" id="dis1" style="display:<?php echo ($disable_action?'none':'');?>">
<div class="div_right_ndot"><ul>
<li>
Disallow users upload files</li>
<li>
Disallow users using FTP</li>
<li>
Disallow users submit email</li>
<li>
Disallow users mass submits</li>
<li>
Disallow users Split files</li>
<li>
Disallow users Merge files</li>
<li>
Disallow users MD5 View/Change</li>
<li>
Disallow users Pack files</li>
<li>
Disallow users Zip files</li>
<li>
Disallow users UnZip files</li>
<li>
Disallow users Rename files</li>
<li>
Disallow users Mass-Rename files</li>
<li>
Disallow users Delete files</li>
</ul>
</div>
</td>
</tr>

<tr>
<td class="tdacc">
<?php
$kolshowall = true;
foreach($show_column_sfile as $kol => $kolval)
{ $kolshowall = $kolshowall && $kolval; }
?>
<input type="checkbox" id="show_column_sfile" name="show_column_sfile" onClick="var displ=this.checked?'none':'';document.getElementById('col_sfile').style.display=displ; document.getElementById('col_dis1').style.display=displ;"<?php if(isset($_POST['show_column_sfile']) && $_POST['show_column_sfile']=='on'){echo ' checked';$kolshowall=true;}elseif($kolshowall){echo ' checked';} ?>><label for="show_column_sfile">&nbsp;Server Files Column. <b>Checked: Show All</b></label></td>
<td class="tdacc">Customized Server Files table column</td>
</tr>

<tr>
<td class="tdacc" id="col_sfile" style="display:<?php echo ($kolshowall?'none':'');?>">
<div class="div_left_ndot"><ul>
<li class="ndot">
	<input type="checkbox" id="md5" name="md5"<?php if($showpostn){if(isset($_POST['md5']) && $_POST['md5']=='on')echo ' checked';}elseif($show_column_sfile["md5"]) echo ' checked'; ?>><label for="md5">&nbsp;Show MD5</label></li>
<li class="ndot">
	<input type="checkbox" id="downloadlink" name="downloadlink"<?php if($showpostn){if(isset($_POST['downloadlink']) && $_POST['downloadlink']=='on')echo ' checked';}elseif($show_column_sfile["downloadlink"]) echo ' checked'; ?>><label for="downloadlink">&nbsp;Show Download_Link</label></li>
<li class="ndot">	
	<input type="checkbox" id="comments" name="comments"<?php if($showpostn){if(isset($_POST['comments']) && $_POST['comments']=='on')echo ' checked';}elseif($show_column_sfile["comments"]) echo ' checked'; ?>><label for="comments">&nbsp;Show Comments</label></li>
<li class="ndot">	
	<input type="checkbox" id="date" name="date"<?php if($showpostn){if(isset($_POST['date']) && $_POST['date']=='on')echo ' checked';}elseif($show_column_sfile["date"]) echo ' checked'; ?>><label for="date">&nbsp;Show Date</label></li>
<li class="ndot">	
	<input type="checkbox" id="age" name="age"<?php if($showpostn){if(isset($_POST['age']) && $_POST['age']=='on')echo ' checked';}elseif($show_column_sfile["age"]) echo ' checked'; ?>><label for="age">&nbsp;Show Age</label></li>
<li class="ndot">	
	<input type="checkbox" id="ip" name="ip"<?php if($showpostn){if(isset($_POST['ip']) && $_POST['ip']=='on')echo ' checked';}elseif($show_column_sfile["ip"]) echo ' checked'; ?>><label for="ip">&nbsp;Show IP</label></li>
</div>
</td>
<td class="tdacc" id="col_dis1" style="display:<?php echo ($kolshowall?'none':'');?>">
<div style="padding:0 0 0 10px; margin:-5px 0 -10px 0;"><ul>
<li>
<span>Show MD5 column</span><br></li>
<li style="padding:5px 0 0 0;">
<span>Show Download_Link column</span><br></li>
<li style="padding:5px 0 0 0;">
<span>Show Comments column</span><br></li>
<li style="padding:5px 0 0 0;">
<span>Show Date column</span></li>
<li style="padding:5px 0 0 0;">
<span>Show Age column</span></li>
<li style="padding:5px 0 0 0;">
<span>Show IP column</span></li>
</ul>
</div>
</td>
</tr>





<tr>
<td class="tdacc"><input type="checkbox" id="bandwidthsave" name="bandwidthsave"<?php if($showpostn){if(isset($_POST['bandwidthsave']) && $_POST['bandwidthsave']=='on')echo ' checked';}elseif($bw_save) echo ' checked'; ?>><label for=bandwidthsave>&nbsp;Bandwidth saving</label></td>
<td class="tdacc">To save bandwidth, if the file name is exist, then stop leeching</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="deletelink_in_lynx" name="deletelink_in_lynx"<?php if($showpostn){if(isset($_POST['deletelink_in_lynx']) && $_POST['deletelink_in_lynx']=='on')echo ' checked';}elseif($deletelink_in_lynx) echo ' checked'; ?>><label for="deletelink_in_lynx">&nbsp;Show Delete Link (lynx.php)</label></td>
<td class="tdacc">Show Delete-Link in Lynx.php (always disabled if Actions files - Delete is disabled)</td>
</tr>
</tbody>
<!-- ==========================END FILES CONFIG -->



<tr><td colspan="2"><div><hr id="grs1"></div></td></tr>





<!-- ==========================START VIEW-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trview" onclick="trclick(this);">
<td class="tdacc"><h1><span id="nod_trview"></span>VIEW CONFIG</h1></td><td>&nbsp;</td></tr>
<tbody id="bd_trview" <?php echo (isset($_COOKIE["trview"])?($_COOKIE["trview"]==1?$styledisplay:''):$styledisplay);?>>
<tr>
<td class="tdacc"><input type="checkbox" id="showcpanel" name="showcpanel"<?php if($showpostn){if(isset($_POST['showcpanel']) && $_POST['showcpanel']=='on')echo ' checked';}elseif($navi_left["showcpanel"]) echo ' checked'; ?>><label for="showcpanel">&nbsp;Show Cpanel button</label></td>
<td class="tdacc">Show CPanel button</td>
</tr>
<tr>
<td class="tdacc"><label for="showplugins"><input type="checkbox" id="showplugins" name="showplugins"<?php if($showpostn){if(isset($_POST['showplugins']) && $_POST['showplugins']=='on')echo ' checked';}elseif($navi_left["showplugins"]) echo ' checked'; ?>>&nbsp;Show Plugins list</label></td>
<td class="tdacc">Show Plugins supported</td>
</tr>

<tr>
<td class="tdacc"><label for="showaudl"><input type="checkbox" id="showaudl" name="showaudl" onClick="var displ=this.checked?'none':'';d.getElementById('audlforbid').style.display=displ;d.getElementById('audlforbidket').style.display=displ;" <?php if($showpostn){if(isset($_POST['showaudl']) && $_POST['showaudl']=='on')echo ' checked';}elseif($navi_left["showaudl"]) echo ' checked'; ?>>&nbsp;Show Auto Download</label></td>
<td class="tdacc">Show Auto Download Button <?php echo ($forbid_audl? "<b class='r'>AUDL DISABLED</b>":"");?></td>
</tr>
<tr>
<td class="tdacc" id="audlforbid" style="display:<?php echo ($navi_left["showaudl"]?'none':'');?>">
<div style="padding-left:15px;"><label for="forbid_audl">
<input type="checkbox" id="forbid_audl" name="forbid_audl"<?php if($showpostn){if(isset($_POST['forbid_audl']) && $_POST['forbid_audl']=='on')echo ' checked';}elseif($forbid_audl) echo ' checked'; ?>>&nbsp;*&nbsp;Forbidden to AuDL</label>
</div>
</td>
<td class="tdacc" id="audlforbidket" style="display:<?php echo ($navi_left["showaudl"]?'none':'');?>">Restrict access to Auto Downlaod</td>
</tr>

<tr>
<td class="tdacc"><label for="showauul"><input type="checkbox" id="showauul" name="showauul" onClick="var displ=this.checked?'none':'';d.getElementById('auulforbid').style.display=displ;d.getElementById('auulforbidket').style.display=displ;"<?php if($showpostn){if(isset($_POST['showauul']) && $_POST['showauul']=='on')echo ' checked';}elseif($navi_left["showauul"]) echo ' checked'; ?>>&nbsp;Show Auto Upload</label></td>
<td class="tdacc">Show Auto Upload Button <?php echo ($forbid_auul? "<b class='r'>AUUL DISABLED</b>":"");?></td>
</tr>
<tr>
<td class="tdacc" id="auulforbid" style="display:<?php echo ($navi_left["showauul"]?'none':'');?>">
<div style="padding-left:15px;"><label for="forbid_auul">
<input type="checkbox" id="forbid_auul" name="forbid_auul"<?php if($showpostn){if(isset($_POST['forbid_auul']) && $_POST['forbid_auul']=='on')echo ' checked';}elseif($forbid_auul) echo ' checked'; ?>>&nbsp;*&nbsp;Forbidden to AuUL</label>
</div>
</td>
<td class="tdacc" id="auulforbidket" style="display:<?php echo ($navi_left["showauul"]?'none':'');?>">Restrict access to Auto Upload</td>
</tr>

<tr>
<td class="tdacc"><label for="showlynx"><input type="checkbox" id="showlynx" name="showlynx" onClick="var displ=this.checked?'none':'';d.getElementById('lynxforbid').style.display=displ;d.getElementById('lynxforbidket').style.display=displ;"<?php if($showpostn){if(isset($_POST['showlynx']) && $_POST['showlynx']=='on')echo ' checked';}elseif($navi_left["showlynx"]) echo ' checked'; ?>>&nbsp;Show Listed Files</label></td>
<td class="tdacc">Show Listed Files Button <?php echo ($forbid_lynx? "<b class='r'>LYNX DISABLED</b>":"");?></td>
</tr>
<tr>
<td class="tdacc" id="lynxforbid" style="display:<?php echo ($navi_left["showlynx"]?'none':'');?>">
<div style="padding-left:15px;">
<input type="checkbox" id="forbid_lynx" name="forbid_lynx"<?php if($showpostn){if(isset($_POST['forbid_lynx']) && $_POST['forbid_lynx']=='on')echo ' checked';}elseif($forbid_lynx) echo ' checked'; ?>><label for="forbid_lynx">&nbsp;*&nbsp;Forbidden to Listed&nbsp;Files</label>
</div>
</td>
<td class="tdacc" id="lynxforbidket" style="display:<?php echo ($navi_left["showlynx"]?'none':'');?>">Restrict access to Listed&nbsp;Files</td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id="server_info" name="server_info" onclick="var displ=this.checked?'':'none';document.getElementById('cpu_nfo').style.display=displ;document.getElementById('cpu_nfoket').style.display=displ;"<?php if($showpostn){if(isset($_POST['server_info']) && $_POST['server_info']=='on')echo ' checked';}elseif($navi_left["server_info"]) echo ' checked'; ?>><label for="server_info">&nbsp;Show server info</label></td>
<td class="tdacc">Disk space &amp; Time Info</td>
</tr>
<tr>
<td class="tdacc" id="cpu_nfo" style="display:<?php echo ($navi_left["server_info"]? '':'none');?>">
<div style="padding-left:15px;">
<input type="checkbox" id="cpuUsageNFO" name="cpuUsageNFO"<?php if($showpostn){if(isset($_POST['cpuUsageNFO']) && $_POST['cpuUsageNFO']=='on')echo ' checked';}elseif($cpuUsageNFO) echo ' checked'; ?>><label for="cpuUsageNFO">&nbsp;Show CPU Usage</label>
</div>
</td>
<td class="tdacc" id="cpu_nfoket" style="display:<?php echo ($navi_left["server_info"]? '':'none');?>">
 <div style="padding-left:15px;">Show CPU Usage Info</div>
</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="onlinevisit" name="onlinevisit"<?php if($showpostn){if(isset($_POST['onlinevisit']) && $_POST['onlinevisit']=='on')echo ' checked';}elseif($OnlineVisitor) echo ' checked'; ?>><label for="onlinevisit">&nbsp;Show Online Visitor</label></td>
<td class="tdacc">Show how many Visitor is Online</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=showpremixstatus name="showpremixstatus" onclick="var displ=this.checked?'':'none';document.getElementById('ip_list').style.display=displ;document.getElementById('ip_list_ket').style.display=displ;"<?php $shpremixst=true; if($showpostn){if(isset($_POST['showpremixstatus']) && $_POST['showpremixstatus']=='on')echo ' checked';$shpremixst=false;}elseif($premix_status) echo ' checked';$shpremixst=false; ?>><label for=showpremixstatus>&nbsp;Show premiX acc Status</label></td>
<td class="tdacc">Display (RS) Premix ACC status</td>
</tr>
<tr>
<td class="tdacc" id="ip_list" style="display:<?php echo ($premix_status? '':'none');?>">
<div style="padding-left:20px;" class="float"><input type="text" name="ip_list" value="<?php if($showpostn){if(!empty($_POST['ip_list']))echo $_POST['ip_list'];}else echo $cain_ip; ?>">&nbsp;<span>Trusted IP List</span></div>
</td>
<td class="tdacc" id="ip_list_ket" style="display:<?php echo ($premix_status? '':'none');?>"><div style="padding-left:10px;">Trusted IP, can view detil premix acc status. eg: 192.168.0.1, <b class="g"><?php echo get_real_ip();?></b></div></td>
</tr>
</tbody>
<!-- ==========================END VIEW CONFIG -->


<tr><td colspan="2"><div><hr id="grs1"></div></td></tr>




<!-- ==========================START MISC-CONFIG -->
<!-- ==================================================-->
<tr class="tdacchead" id="trmisc" onclick="trclick(this);">
<td class="tdacc"><h1><span id="nod_trmisc"></span>MISC CONFIG</h1></td><td>&nbsp;</td></tr>
<tbody id="bd_trmisc" <?php echo (isset($_COOKIE["trmisc"])?($_COOKIE["trmisc"]==1?$styledisplay:''):$styledisplay);?>>
<tr>
<td class="tdacc"><input type="checkbox" id=nocache name="nocache"<?php if($showpostn){if(isset($_POST['nocache']) && $_POST['nocache']=='on')echo ' checked';}elseif($no_cache) echo ' checked'; ?>><label for=nocache>&nbsp;No cache page</label></td>
<td class="tdacc">Prohibition by Browser (You should leave this checked unless you know what you are doing)</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=redirrect name="redirrect"<?php if($showpostn){if(isset($_POST['redirrect']) && $_POST['redirrect']=='on')echo ' checked';}elseif($redir) echo ' checked'; ?>><label for=redirrect>&nbsp;Redirrect</label></td>
<td class="tdacc">Redirect passive method (You should leave this checked unless you know what you are doing)</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=disable_ajax name="disable_ajax"<?php if($showpostn){if(isset($_POST['disable_ajax']) && $_POST['disable_ajax']=='on')echo ' checked';}elseif($disable_ajax) echo ' checked'; ?>><label for=disable_ajax>&nbsp;Disable Ajax</label></td>
<td class="tdacc">Do not use Ajax in Serverfiles.</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=disable_ajaxren name="disable_ajaxren"<?php if($showpostn){if(isset($_POST['disable_ajaxren']) && $_POST['disable_ajaxren']=='on')echo ' checked';}elseif($disable_ajaxren) echo ' checked'; ?>><label for=disable_ajaxren>&nbsp;Disable Instant-Rename</label></td>
<td class="tdacc">Do not load Ajax Instant-Rename. <small>require: rsajax.js</small></td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=logact name="logact"<?php if($showpostn){if(isset($_POST['logact']) && $_POST['logact']=='on')echo ' checked';}elseif($logact) echo ' checked'; ?>><label for=logact>&nbsp;User-Log Activity</label></td>
<td class="tdacc">Record IP, Browser, Refferer; time access of the users</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id=alternatefree name="alternatefree"<?php if($showpostn){if(isset($_POST['alternatefree']) && $_POST['alternatefree']=='on')echo ' checked';}elseif($alternatefree) echo ' checked'; ?>><label for=alternatefree>&nbsp;Alternate switch to freedownload</label></td>
<td class="tdacc">Auto switch to free download if premium not available</td>
</tr>
<tr>
<td class="tdacc"><input type="checkbox" id="auto_cl" name="auto_cl" onclick="var displ=this.checked?'':'none';document.getElementById('line_autClket').style.display=displ;"<?php if($showpostn){if(isset($_POST['auto_cl']) && $_POST['auto_cl']=='on')echo ' checked';}elseif($redir) echo ' checked'; ?>><label for="auto_cl">&nbsp;Auto-close audl popup</label></td>
<td class="tdacc">Close Window pop-up after leech file completed</td>
</tr>
<tr id="line_autClket" style="display:<?php echo ($showautoclose? '':'none');?>">
<td class="tdacc float"><div style="padding-left:20px;">
<input type="text" name="auto_close" maxlength="5" size="4" value="<?php if($showpostn){if(!empty($_POST['auto_close']))echo $_POST['auto_close'];}else echo $timeautoclose; ?>">&nbsp;<b class="g">ms</b></div></td>
<td class="tdacc"><div style="padding-left:10px;">Timer for closing Auto-download window pop-up. default: 500 milliseconds</div></td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id=autochecklink name="autochecklink"<?php if($showpostn){if(isset($_POST['autochecklink']) && $_POST['autochecklink']=='on')echo ' checked';}elseif($autochecklink) echo ' checked'; ?>><label for=autochecklink>&nbsp;Auto-Check Link</label></td>
<td class="tdacc">Auto check submited link in audl</td>
</tr>

<tr>
<td class="tdacc"><input type="checkbox" id=mip_enabled name="mip_enabled" onclick="var displ=this.checked?'':'none';document.getElementById('line_mip').style.display=displ;"<?php if($showpostn){if(isset($_POST['mip_enabled']) && $_POST['mip_enabled']=='on')echo ' checked';}elseif($mip_enabled) echo ' checked'; ?>/><label for=mip_enabled>&nbsp;Enable Multi-IP</label></td>
<td class="tdacc">Enable Multi-IP based on mip_iplist.txt</td>
</tr>
<tr id="line_mip" style="display:<?php echo ($mip_enabled? '':'none');?>">
<td class="tdacc float" ><div style="padding-left:20px;">
<input type="checkbox" name="mip_arotate" id="mip_arotate"<?php if($showpostn){if(isset($_POST['mip_arotate']) && $_POST['mip_arotate']=='on')echo ' checked';}elseif($mip_arotate) echo ' checked'; ?>>&nbsp;<label for=mip_arotate>IP-Rotate</label><div style="height:3px;"></div>
<textarea type="text" name="mip_list" rows="4" cols="20"><?php echo htmlspecialchars($mip_list);?></textarea><br/>
</div>
</td>
<td class="tdacc" valign="top" style="height:110px;">
 <div style="padding-left:10px;">
  Should IP changed to next ip after transload process
  <div style="height:8px;"></div>
  List of IP :: <?php echo (!defined('LOG_DIR') ? "Not found: LOG_DIR" : "");$fmip = LOG_DIR."mip_iplist.txt";
  if(file_exists($fmip)){echo (!fopen($fmip,"a")? " -&gt; <b class='r'>Can't write to ":"<b class='g'>")."{$fmip}</b>";}else{echo "{$fmip} Not Found.";}
  ?>
  <br/>(separate each IP by new line)
 </div>
</td>
</tr>

<tr>
<td class="tdacc"><div class="spc" style="padding-left:3px;">&nbsp;<input type="text" name="iframealocate" size="2" maxlength="2" value="<?php if($showpostn){if(!empty($_POST['iframealocate']))echo $_POST['iframealocate'];}else echo $iframealocate; ?>">&nbsp;IFrame to allocate in audl</div></td>
<td class="tdacc">How many iframe to allocate for <b class="g">manual</b> method in audl</td>
</tr>

<tr>
<td class="tdacc"><div class="spc" style="padding-left:3px;">&nbsp;<input type="text" name="pointbooster" size="2" maxlength="3" value="<?php if($showpostn){if(!empty($_POST['pointbooster']))echo $_POST['pointbooster'];}else echo $pointboost; ?>">&nbsp;<b class="g">Point</b> RS Point-Booster</div></td>
<td class="tdacc"><div style="padding-left:10px;">How many times download-loop to occurs. default: 255 point. set to 0 or blank to disable</div></td>
</tr>
<tr>
<td class="tdacc"><div class="spc" style="padding-left:3px;">&nbsp;<input type="text" name="timezone" size="2" maxlength="2" value="<?php if($showpostn){if(!empty($_POST['timezone']))echo $_POST['timezone'];}else echo $timezone; ?>">&nbsp;Time Zone</div></td>
<td class="tdacc"><div style="padding-left:10px;">Set your server timezone. Eg. It is GMT+ <b class="g">7</b> for Indonesia</div></td>
</tr>
<tr>
<td class="tdacc"><div class="spc" style="padding-left:3px;">
<select id="language" name="language" title="Select Language">
<?php
for($l=0; $l < count($arlang); $l++) {
 $selek = ($lang == $arlang[$l] ? " SELECTED='SELECTED'":"");
 echo "<option value='$l'$selek>".$arlang[$l]."</option>";
}
?>
</select>
&nbsp;<b class="g">Language</b></div></td>
<td class="tdacc"> set your default Language.</td>
</tr>
<tr>
<td class="tdacc"><div class="spc" style="padding-left:3px;">

<select id="theme" name="theme" title="Select Theme">
<?php
foreach($arCSS as $k => $vcss){
 $selek = (($csstype==$vcss) ? " SELECTED='SELECTED'":"");
 echo "<option value='".$k."'".$selek.">".$vcss."</option>";
}
?>
</select>
&nbsp;<b class="g">Themes</b></div></td>
<td class="tdacc"> set Theme to your RL: <b class="g">default</b> Default style of RL Rx08</td>
</tr>
</tbody>
<!-- ==========================END MISC-CONFIG -->
<tr><td colspan="2"><div class="spc" style="padding-top:5px;border-bottom:1px solid #1E2637;">&nbsp;</div></td></tr>



</td></tr>
</tbody>
</table>

<script type="text/javascript">
var trtab = Array('limit','files','view','misc');
if(imgpath==undefined){imgpath = '<?php echo IMAGE_DIR;?>';}
for(var i=0; i<trtab.length; i++){ cueview = getCookie('tr'+trtab[i]); pm=(cueview==1||cueview==null?'plus':'min'); d.getElementById('nod_tr'+trtab[i]).innerHTML = '<input type="image" src="'+imgpath+pm+'.png" onclick="" style="vertical-align:middle;">&nbsp;';}
</script>

<input type="hidden" id="cplHash" name="cplHash" value="" size=90>
<input type="hidden" id="rlauthHash" name="rlauthHash" value="" size=90>


<div align="center" style="padding-top:4px;"><input type="submit" name="submit" id="saveAll" value="Save">&nbsp;&nbsp;&nbsp;
<input type="button" onClick="location.href='./index.php'" value="Cancel">
	<div style="padding-top:10px;"></div>
<?php if(!$disableadvanceeditor){
?>
&nbsp;&nbsp;&nbsp;
<input type="button" onClick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=editor\'';?>" value="Advanced Editor">
	<?php 
}
?>
<input type="button" onClick="<?php echo 'location.href=\'./'.basename($PHP_SELF).'?mode=viewlog\'';?>" value="View Log">

</div>
</form>

<?php
}
?>