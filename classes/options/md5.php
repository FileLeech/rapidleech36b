<?php
function rl_md5() {
 global $list, $L, $PHP_SELF;
	$n_md5 = count($_GET["files"])
?>
	<script type="text/javascript">
	var mL = 0, cnt = <?php echo (is_numeric($n_md5) ? $n_md5 : 0);?>;
	var d = document, formMd5, tmrLoop;
	var singlemd5 = true;

	function chAll(){singlemd5 = false; initMD5change(mL);nextFile(mL);loopchk(mL);}

	function loopchk(id){
	  if(id < cnt){ret = d.getElementById('actstat'+id).innerHTML;if(ret.indexOf('_result') != -1){mL++; nextFile(mL);}tmrLoop = setTimeout("loopchk("+mL+")", 50); } else { clearTimeout(tmrLoop); singlemd5=true; mL=0; return;}}

	// send filename to form input hidden
	function nextFile(id){d.getElementById('misionMd5').value = d.getElementById('md5fname'+id).innerHTML; setTimeout("FireUp("+id+")", 200);}

	function FireUp(id){d.getElementById('misionMd5').value += ":" + d.getElementById('impurity').selectedIndex;formMd5.action = 'javascript:get("misionMd5","actstat'+id+'","actstat'+id+'","")';formMd5.submit();}

	function initMD5change(id){var btnAll = d.getElementById('changeall');formMd5 = d.getElementById('frmMD5');if(btnAll.style.display!='none'){ setTimeout("d.getElementById('changeall').style.display='none'", 420);}if(singlemd5){ nextFile(id); }else { for(var i=0; i<cnt; i++){d.getElementById('actstat'+i).innerHTML = 'wait..';  }}}
	 function ntfy(){
	  var msg = "How this MD5 Changer is working?\n\n"; msg += "It is modifying your file by adding single or more char to the end of file.\n"; msg += "It's not guaranted it works properly for every file,\nsomeside effect like corrupted files may occurs.\n"; msg += "Select Impurity Char to pick char(s) that may suitable to your file instead using the default char: 0 (zero)\n\n"; msg += "T_T''"; alert(msg);}
	</script>
	<form name="frmMD5" id="frmMD5" action="<?php echo $PHP_SELF;?>">
	   <input type="hidden" id="misionMd5" name="misionMd5" value="">
	</form>
	<?php 
	echo $L->say['you_selected'];?>&nbsp;<b class="g"><?php echo $n_md5;?></b>&nbsp;files.<div id="changeall"><?php echo $L->say['you_sure_ch_md5'];?><br />
	<input type="button" name="md5ch" onclick="chAll();" value="<?php echo $L->say['_yes'];?>"></div><div style="padding-top:8px;"></div>
	<table align="center" border=0 cellspacing="1" cellpadding="3">
		<tr class="rowtitleChangemd5">
		 <td align="center">File</td><td align="center">Size</td><td align="center"><?php echo $L->say['cur_md5'];?></td>
		 <td valign="middle" width="200">
		  <select id="impurity" name="impurity" title="Impurity Char" style="float:right;" width=60>
			<option selected='selected'>Impurity Char</option>
			<option value="char0">0 (Default)</option>
			<option value="char1">" "</option>
			<option value="char2">//</option>
			<option value="char3">/**/</option>
			<option value="char4">#</option>
		  </select><?php echo $L->say['new_md5'];?>&nbsp;&nbsp;<a href="javascript:;" onclick="ntfy();" title="RTFM"><b style="color:red"><blink>?</blink></b></a>
		 </td></tr>
	<?php
	for($i = 0; $i < $n_md5; $i++){
	  $file = $list[($_GET["files"][$i])];
	  if (file_exists($file["name"])) {
	?>
		<tr class="rowChangemd5">
		<td nowrap>&nbsp;<b id="md5fname<?php echo $i;?>"><?php echo basename($file["name"]);?></b></td>
		<td align="center" class="txtmd5">&nbsp;<span title="<?php echo $file["size"];?>" style="color:#FFA300; cursor:pointer;">
		 <?php 
		  $max2gb = 2147483647;		  
		  //$mdfsize = filesize($file["name"]);
		  $mdfsize = getfilesize($file["name"]);
		  $mdfsize = ($mdfsize > $max2gb || $mdfsize < 0 ? -1 : $mdfsize . " B");
		  echo ($mdfsize);
		 ?></span>&nbsp;</td>
		<td nowrap class="txtmd5">&nbsp;<span id="md5hash<?php print $i;?>" style="color:#FFA300"><?php echo ($mdfsize >= 0 ? md5_file($file["name"]) : "File to big"); ?></span>&nbsp;</td>
		<td align="center">
		<?php if($mdfsize>=0){?>
		 <div id="actstat<?php print $i;?>">
		 <a href="javascript:;" onclick="initMD5change('<?php print $i;?>')" class="y"><?php echo $L->say['change_md5'];?></a></div>
		<?php }?>
		</td>
		</tr>
	<?php
	  }
	}
	?>
	</table><br />
	<script type="text/javascript">
	 var mproces = d.getElementById('md5_process');
	 mproces.style.display = 'none';
	</script>
<?php
}
?>