<?php
function upload() {
	global $upload_services, $L, $list;
	
	 $d = opendir(UPLOAD_DIR);
	 if($d){
	    while (false !== ($modules = readdir($d))){
			if($modules!="." && $modules!=".."){
					if(is_file(UPLOAD_DIR.$modules)){
						if (strpos($modules,".index.php")) include_once(UPLOAD_DIR.$modules);
					}
			}
	    }
	 }

	 if (empty($upload_services)) {
			echo "<span style='color:#FF6600'><b>".$L->say['no_support_upl_serv']."</b></span>";
		} else {
			sort($upload_services); 
			reset($upload_services);
			$cc=0;
			foreach($upload_services as $upl) {
			  $uploadtype.="upservice[".($cc++)."]=new Array('".$upl."',' (".($max_file_size[$upl]==false ? "Unlim" : $max_file_size[$upl]."Mb").")"."');\n";
			}
?>
<script type="text/javascript">
	var upservice = new Array();
	function fill_option(id){
	 var elem=document.getElementById(id);					
	 for (var i=0; i<upservice.length;i++){
		elem.options[elem.options.length]=new Option(upservice[i][0].replace("_"," ") + upservice[i][1]);
		elem.options[elem.options.length-1].value=upservice[i][0];
	 }
	}
<?php echo $uploadtype; ?>
	function openwinup(id){
		var options = "width=700,height=200,toolbar=no,location=yes,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no";
		win=window.open('', id, options);win.focus();
		return true;
	}
	</script>
	        
	        <table align="center">
			<?php
/* dont need this
<input type="hidden" name="filename" value='<?php echo base64_encode(($file["name"])); ?>' />
*/			
			
			for($i = 0; $i < count($_GET["files"]); $i++) {
		      $file = $list[($_GET["files"][$i])];
		      $tid=md5(time()."_file".$_GET["files"][$i]);
			?>
			<tr><td>
			<form action="upload.php" method="get" target="<?php echo 'wp_'.$tid; ?>" onSubmit="return openwinup('<?php echo 'wp_'.$tid; ?>');">
			<table>
			<tr valign="middle">			 
			 <td><b><?php echo htmlspecialchars(basename($file["name"]))."</b>  , ". $file["size"] ?></td>
			 <td><select name="uploaded" id="d_<?php echo $tid; ?>"></select><script type="text/javascript">fill_option('d_<?php echo $tid; ?>');</script></td>
			 <td><input type="submit" value="Upload" /></td>
			</tr>
			<tr>
			 <td colspan="3" align="center">			 
			 <input type="hidden" name="filedate" value='<?php echo $file["date"]; ?>' />
			 </td>
			</tr>			
		    <tr>
		     <td colspan="3" align="center">
		 	 <label><input type="checkbox" id="useuproxy" name="useuproxy" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('uproxyconfig').style.display=displ;" />&nbsp;<?php echo $L->say["use_proxy"]; ?></label>
		     </td>
	        </tr>
	        <tr>
	         <td colspan="2" align="center">
	           <table style="display:none;" width="150" border="0" id="uproxyconfig">
	           <tr><td><?php echo $L->say["_proxy"];?>&nbsp;</td><td><input type="text" name="uproxy" id="uproxy" size="20" /></td></tr>
	           <tr><td><?php echo $L->say["_uname"];?>&nbsp;</td><td><input type="text" name="uproxyuser" id="uproxyuser" size="20" /></td></tr>
	           <tr><td><?php echo $L->say["_pass"];?>&nbsp;</td><td><input type="text" name="uproxypass" id="uproxypass" size="20" /></td></tr>
	           </table>
	         </td>
	        </tr>
			</table>
			</form>
			</td></tr>
			<?php
			}
			?>
			</table><br />
			<?php
		}
}
?>