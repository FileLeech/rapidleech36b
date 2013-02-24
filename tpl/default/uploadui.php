<div id="<?php echo $id; ?>">
<table cellspacing="0" cellpadding="0" style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;" id="progressblock">
<tr>
	<td width="100">&nbsp;</td>
	<td width="300" nowrap>
		<div style="border:#BBBBBB 1px solid; width:300px; height:10px;" class="progressborder">
    		<div id="progress" style="background-color:#18f20d; margin:1px; width:0%; height:8px;"></div>
		</div>
	</td>
<td width="100">&nbsp;</td>
<tr>
	<td align="right" id="received" width="100" nowrap>0 KB</td>
	<td align="center" id="percent" width="300">0%</td>
	<td align="left" id="speed" width="100" nowrap>0 KB/s</td>
</tr>
</table>
</div>
<script>
function pr(percent, received, speed)
{
	document.getElementById("received").innerHTML = '<b>' + received + '</b>';
	document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
	document.title= '['+percent + '%]->['+orlink+']'+' Uploaded';
	if (percent > 90) {percent=percent-1;}
	document.getElementById("progress").style.width = percent + '%';
	document.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
	return true;
}

function mail(str, field)
{
	document.getElementById("mailPart." + field).innerHTML = str;
	return true;
}
</script>
<br />
