<?php
if (!defined('RAPIDLEECH')) {
	require_once("index.html");
	exit();
}
?>
<br />
<table cellspacing="0" cellpadding="0" style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
<tr>
<td></td>
<td>
 <div style="border:#BBBBBB 1px solid; width:300px; height:10px;" class="progressborder">
 	<div id="progress" style="background-color:#18f20d; margin:1px; width:0%; height:8px;"></div>
 </div>
</td>
<td></td>
<tr>
<tr>
<td align="left" id="received">0 KB</td>
<td align="center" id="percent">0%</td>
<td align="right" id="speed">0 KB/s</td>
</tr>
</table>
<br />
<div id="resume" align="center"></div>
<script type="text/javascript">
/* <![CDATA[ */
function pr(percent, received, speed) {
  $('#received').html('<b>' + received + "<\/b>");
  $('#percent').html('<b>' + percent + "%<\/b>");
  $('#progress').css('width', percent + '%');
  $('#speed').html('<b>' + speed + " KB\/s<\/b>");
  document.title = percent + '% Downloaded';
  return true;
}

function mail(str, field) {
  $("#mailPart." + field +"").html(str);
  return true;
}
/* ]]> */
</script>
<br />
<?php
if ($options['enable_stop_transload']) {
  ignore_user_abort(false);
?>
<form method="post" action="<?php echo $PHP_SELF;?>" id="stoptl"><input type="submit" name="stop" value="Stop Transload" /></form>
<br />
<?php
}
?>
