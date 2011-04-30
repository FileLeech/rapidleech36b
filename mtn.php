<?php
define('RAPIDLEECH', 'yes');
error_reporting(0);
set_time_limit(0);
session_start();
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', './');
require_once(CONFIG_DIR."config.php");
require_once(CLASS_DIR."other.php");
define ( 'TEMPLATE_DIR', 'misc/tpl/'.$csstype.'/' );
define ('CREDITS', '<small class="small-credits">Sakib Hossain | Slider234 | jmsmarcelo | DarkNight</small><br />');
$nn = "\r\n";
$rev_num = '36B.Rv7.4 Unofficial';
$RL_VER = 'Rx08.ii'.$rev_num;
$charSet = 'UTF-8';
require_once(LANG_DIR."language.$lang.inc.php");
?>
<html>
<head>
<title>Movie Thumbnailer</title>
<link href="misc/tpl/<? echo $csstype;?>/style_sujancok_default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jscolor/jscolor.js"></script>
<script type="text/javascript" language="javascript" src="rsajax.js"></script>
</head>
<body>
<center><a href="index.php"><img src="misc/tpl/<? echo $csstype;?>/rl_lgo.png" alt="RapidLeech" border="0" /></a><br /><br /><br />
<?
if($limited_edition || $limited_area)
{
  $dlimitation = array($limited_edition, $limited_area);
  require_once("limit_district.php");
}
if($limit_timework)
{  
    $is_worktime = cek_worktime($workstart, $workend); 
    exit("<h1>".$gtxt['worktime_alert']."</h1>");
}
if (!$navi_left['showmtn'])
{
    exit("<h1>Movie Thumbnailer is Disable</h1>");
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

$files = vidlist($download_dir);
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
            <center><input style="height: 50px;width: 140px;font-size:x-large;background-color: #393136;" type="submit" value="Generate" name="mtn" /><br/><br/><br/>
            <a href="index.php"><font size="2" face="Arial"><b>Back to RapidLeech</b></font></a><br/>
            <a href="javascript:window.close();"><font size="2" face="Arial"><b>Close Windows</a></b></font><br/><br/></center>
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
<?php include('mtn/create.php');?>
<br /><?php
echo CREDITS;?>
<br />
</center>
</body>
</html>