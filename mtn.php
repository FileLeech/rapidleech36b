<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: mtn.php $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";

define ('CREDITS', '<small class="small-credits">Sakib Hossain | Slider234 | jmsmarcelo | DarkNight</small><br />');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charSet; ?>" />
<title>MTN :: <?php echo $RL_VER; ?> ::</title>
<link type="text/css" href="<?php print ROOT_URL.IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen" />
<link type="image/gif" rel="shortcut icon" href="<?php echo ROOT_URL.IMAGE_DIR . 'ico_home.gif?' . rand(11, 9999); ?>" />
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR; ?>js.php"></script>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR; ?>jscolor/jscolor.js"></script>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR; ?>ajax.js"></script>
</head>
<body>
<div class="head_container"><center>
<a href="<?php echo ROOT_URL.$options['index_file']; ?>" class="tdheadolgo" title="Rapidleech"><span>Rapidleech36B</span></a>
</center></div>
<br />
<center>
<?php
if ($options['limit_timework']) {
	$is_worktime = cek_worktime($options['workstart'], $options['workend']);
	$limitmsg = "";
	if (!$is_worktime) {
		$limitmsg.= (!empty($limitmsg) ? "<br />" : "") . $L->say['worktime_alert'];
		echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class='warn_alert'>" . $limitmsg . "</div></div>";
		if ($options['navi_left']['server_info']) {
			if (@file_exists(CLASS_DIR . "sinfo.php")) require_once(CLASS_DIR . "sinfo.php");
		}else echo "<hr />";
		echo "</body></html>";
		exit();
	}
}
?>
<font size="6" face="Arial">Movie Thumbnailer</font><br /><br /><br />

<form method="post">
<table>
   <tr>
		<td>Video File <span class="nav_text" onmouseover="document.getElementById('help_text').style.display='block'" onmouseout="document.getElementById('help_text').style.display='none'" style="cursor:help"> [*]</span> : 
		</td>
		<td>
			<select name="video">
<?PHP
$exts=array(".3gp", ".3g2", ".asf", ".avi", ".dat", ".divx", ".dsm", ".evo", ".flv", ".m1v", ".m2ts", ".m2v", ".m4a", ".mj2", ".mjpg", ".mjpeg", ".mkv", ".mov", ".moov", ".mp4", ".mpg", ".mpeg", ".mpv", ".nut", ".ogg", ".ogm", ".qt", ".swf", ".ts", ".vob", ".wmv", ".xvid");
$ext="";

$files = vidlist($options['download_dir']);
foreach($files as $file)
{
	echo '<option value="'.$file.'">'.$file.'</option>';
}
?>
            </select>
            <input type="checkbox" name="all" value="true" /> Generate all. 
    </tr>
    <tr>
        <td colspan="2"><br /><br /><br />
            <center><input style="height: 50px;width: 140px;font-size:x-large;background-color: #393136;" type="submit" value="Generate" name="mtn" /><br /><br /><br />
            <a href="index.php"><font size="2" face="Arial"><b>Back to RapidLeech</b></font></a><br />
            <a href="javascript:window.close();"><font size="2" face="Arial"><b>Close Windows</a></b></font><br /><br /></center>
        </td>
    </tr>
</table>
<table>
	<tr>
		<td>
			<span id="help_text" style="font: bolder;font-size: larger;display:none">File should be supported: 3gp, 3g2, asf, avi, dat, divx, dsm, evo, flv, M1V, m2ts, m2v, m4a, MJ2, moov mjpg. mjpeg. mkv. mov .. mp4,. mpg,. mpeg,. mpv. nut. ogg. ogm. qt. swf. ts. vob,. wmv,. xvid.</span>
		</td>
	</tr>
</table>
</form>
<?php include(CONFIG_DIR .'create.php');?>
<br /><?php echo CREDITS;?>
<br />
</center>
</body>
</html>