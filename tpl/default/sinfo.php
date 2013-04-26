<?php
if (!defined('RAPIDLEECH')) {
	require_once("index.html");
	exit();
}
?>
<hr />
<table cellspacing="2" cellpadding="2">
<tr><td>
<span id="server"></span>
<span style="color:#FF8700;">&nbsp;(<?php echo $L->say['server_time'];?>)</span><br />
<span id="clock"></span>
<?php if($is_CPUerror==0) {
?>
 <div style="padding-top:3px;"></div>
 <small>
 <span class="c"><?php echo $cpu_string[0];?></span>&nbsp;
 <?php echo $cpu_string[1];?><br />
 <?php if ($gd_enable) {
   echo '<img src="'.CLASS_DIR.'bar.php?rating='.round($cpulast,"2").'" border="0" /><br />';
 }
 ?>
 </small><br /><?php
} else {
   echo (isset($cpu_string) ? $cpu_string[0]:"");
}
if(isset($diskspace_callable) && $diskspace_callable==1){
?>
 <div style="padding-top:3px;"></div>
 <small>&nbsp;Free&nbsp;
 <b><span class='s'><?php echo ZahlenFormatieren($free);?></span></b>&nbsp;of&nbsp;
 <b><span class='g'><?php echo ZahlenFormatieren($total);?></span></b></small><br />
 <?php if($gd_enable) {
  echo '<img src="'.CLASS_DIR.'bar.php?rating='.round((100-$free_percent),"2").'" border="0" />';
 }
 ?>
 <div style='text-align:right;'><small>
 <b class='s'><?php echo $free_percent;?>%</b> Free
 </small></div><?php
}
?>
</td></tr>
</table>
<script type="text/javascript">
/* <![CDATA[ */
function goforit(){
 setTimeout("getthedate()",1000);
 timeDiff('<?php echo date('Y', TIME_NOW); ?>', '<?php echo date('n', TIME_NOW); ?>', '<?php echo date('j', TIME_NOW); ?>', '<?php echo date('G', TIME_NOW); ?>', '<?php echo date('i', TIME_NOW); ?>', '<?php echo date('s', TIME_NOW); ?>', 'dd-mm-yyyy');
};
$(document).ready(function() { goforit(); });
/* ]]> */
</script>