<br>
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
<br>
<div id="resume" align="center"></div>
<script type="text/javascript">
function pr(percent, received, speed){
	document.getElementById("received").innerHTML = '<b>' + received + '</b>';
	document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
	document.getElementById("progress").style.width = percent + '%';
	document.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
	document.title = percent + '%..';
	return true;
	}

function mail(str, field) {
	document.getElementById("mailPart." + field).innerHTML = str;
	return true;
	}
</script>
<br>
