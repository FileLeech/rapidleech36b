<?php 
if(!defined('RAPIDLEECH'))
  { require_once("index.html"); exit; }

?><div align="center" class="tdfootcredits"></div>
<div align="center" id="main_footer">
<?php print CREDITS; ?><br /><hr /><?php print VERSION; ?><br />
</div>
<?php if($show_w3c_validator){?>
<div id="w3cvalidator" style="text-align:center;font-size:9px;">
<a href="http://validator.w3.org/check?uri=referer" target="_blank">HTML 4.01</a>&nbsp;&mdash;&nbsp;<a href="http://jigsaw.w3.org/css-validator/check/referer/" target="_blank">CSS 2.1 / 3.0</a></div>
<?php }?>
</td>
</tr>
</table>
<div id="preleft_nfo" style="display:none;">
<?php if($options["navi_left"]["server_info"]) {
  if(@file_exists(CLASS_DIR."sinfo.php")) require(CLASS_DIR."sinfo.php");
}
echo "<div style='padding-left:5px;'><small>".($server['property'])."<br />".$L->sprintf($L->say["page_load"], $maintimer->stop())."</small></div>";

?>
</div>
<script type="text/javascript">
 var inner_left = document.getElementById('preleft_nfo').innerHTML;
 document.getElementById('left_NFO').innerHTML = inner_left;
 <?php if($options["cpuUsageNFO"] && isset($srvload)){?>
 setTimeout("document.getElementById('srvload').style.display='none'", 2500);
 <?php }?>
</script>
<?php if(file_exists(STATIC_DIR."sorttable.js")){
?>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR;?>sorttable.js"></script>
<?php } ?>
</body>
</html>