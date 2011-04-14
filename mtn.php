<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<title>Mtn V3.0 Reloaded</title>
<link href="misc/tpl/_default/style_sujancok_default.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
<script type="text/javascript" src="jscolor/jscolor.js"></script>	
<style type="text/css">
body {font-weight: bold;}
</style>
</head>
<body>
<center><img src="misc/tpl/_default/rl_lgo.png" alt="RapidLeech" border="0" /></center>
<center>
<br><br><br>

<?php 
//---- Configuracion -------------------
$dirhome = "/home/example/leech" 
//--------------------------------------
?>

<div id="content"><div class="box"><font size="5" face="Arial"><b>Configs For Thumbnails:</b></font><form action="mtn.php" method="post"><br><br>
<table>

<tr>
<td><font size="2" face="Arial"><b>File <span class="nav_text" onmouseover="document.getElementById('help_text1').style.display='block'" onmouseout="document.getElementById('help_text1').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
<td>
<select name="video">

<?php

$exts=array(".3gp", ".3g2", ".asf", ".avi", ".dat", ".divx", ".dsm", ".evo", ".flv", ".m1v", ".m2ts", ".m2v", ".m4a", ".mj2", ".mjpg", ".mjpeg", ".mkv", ".mov", ".moov", ".mp4", ".mpg", ".mpeg", ".mpv", ".nut", ".ogg", ".ogm", ".qt", ".swf", ".ts", ".vob", ".wmv", ".xvid");
$ext="";

function vidlist($dir) 
{
	$results = array();
	$handler = opendir($dir);
	while ($file = readdir($handler)) 
	{
		if (strrchr($file,'.')!="")
		{
			$ext=strtolower(strrchr($file,'.'));
		}
		if ($file != '.' && $file != '..' && in_array($ext,$GLOBALS["exts"]))
		{
				$results[] = $file;
		}
	}
closedir($handler);
sort($results);
return $results;
}
$files = vidlist("./0x14");
foreach($files as $file)
{
	echo '<option value="'.$file.'">'.$file.'</option>';
}


?>

</select></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>All <span class="nav_text" onmouseover="document.getElementById('help_text2').style.display='block'" onmouseout="document.getElementById('help_text2').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
	<td><select name="todo"> 
	<option value="no" SELECTED>No</option>
	<option value="si" >Yes</option></select></td>
		
</tr>	

<br>

<tr>
<td><font size="2" face="Arial"><b>Columns:</b></font></td>
<td><select name="c"> 
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4" SELECTED>4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option></select></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Rows:</b></font></td>
	<td><select name="r">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
    <option value="5">5</option>
	<option value="6" SELECTED>6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	<option value="11">11</option>
	<option value="12">12</option>
	<option value="13">13</option>
	<option value="14">14</option>
	<option value="15">15</option></select></td>
</tr>	
<tr>
	<td><font size="2" face="Arial"><b>Text Size:</b></font></td>
	<td><select name="m"> 
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10" SELECTED >10</option>
	<option value="11">11</option>
	<option value="12">12</option>
	<option value="13">13</option> 
    <option value="14">14</option> 
    <option value="15">15</option></select></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Additional Text <span class="nav_text" onmouseover="document.getElementById('help_text3').style.display='block'" onmouseout="document.getElementById('help_text3').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
	<td><input type="text" name="text"> &nbsp;</td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Image Quality:</b></font></td>
	<td><select name="calidad"> 
	<option value="80">Low</option>
	<option value="90" SELECTED>Normal</option>
	<option value="100">God</option></select> 
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Precision <span class="nav_text" onmouseover="document.getElementById('help_text6').style.display='block'" onmouseout="document.getElementById('help_text6').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
	<td><select name="d"> 
	<option value="0" SELECTED>0</option>
	<option value="2">2</option>
	<option value="4">4</option>
	<option value="6">6</option>
	<option value="8">8</option></select> &nbsp;</td>
</tr>

<tr>
    <td><br><font size="2" face="Arial"><b>Text Font <span class="nav_text" onmouseover="document.getElementById('help_text4').style.display='block'" onmouseout="document.getElementById('help_text4').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
    <td><select name="font">
	<option value="tisto_font.ttf">Tiesto</option>
	<option value="varsity_regular.ttf" SELECTED>Varsity</option>
	<option value="tahomabd.ttf">Tahomabd</option>
	<option value="CAPACITR.ttf">Capacitor</option>
	<option value="AtomicSushi.ttf">Atomic</option>
	<option value="AGITPM__.TTF">AgitPRO</option></select>
</tr>

<tr>
	<td><br><font size="2" face="Arial"><b>Text Color <span class="nav_text" onmouseover="document.getElementById('help_text7').style.display='block'" onmouseout="document.getElementById('help_text7').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
	<td><br><input class="color" name="group1" value="ffffff">
	</td>
</tr>
<tr>
	<td><br><font size="2" face="Arial"><b>Background Color <span class="nav_text" onmouseover="document.getElementById('help_text8').style.display='block'" onmouseout="document.getElementById('help_text8').style.display='none'" style="cursor:help"> [?]</span>:</b></font></td>
	<td><br><input class="color" name="group2" value="000000">
	</td>
</tr>
</table>
<br>
<br>
<tr>
<td></td>
<span id="help_text1" style="display:none">Formats supported: <br/><br/>.3gp, .3g2, .asf, .avi, .divx, .dsm, .evo, .f4v, .flv, .mkv, .m1v, .m2ts, .mov, .mp4, .mpg, .mpeg, .swf, .vob, .wmv, etc..</span>
<span id="help_text2" style="display:none">Select Yes to make thumbnails to all files</span>
<span id="help_text3" style="display:none">Add text below the video info. Do not add spaces</span>
<span id="help_text4" style="display:none"><img src="/font.png"/></span>
<span id="help_text5" style="display:none">Remove Video Info</span>
<span id="help_text6" style="display:none">Choose the precision of the thumbnails to prevent blur. 0 = Disable</span>
<span id="help_text7" style="display:none">Select from the tablet one color For the Text</span>
<span id="help_text8" style="display:none">Select from the tablet one color For the Background</span>
</tr>
<br>

<input type="checkbox" name="info" value="-i" /><font size="2" face="Arial">Remove Video Info&nbsp;</font><br>
<input type="checkbox" name="time" value="-t" /><font size="2" face="Arial">Remove Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
<br><input type="checkbox" name="info" value="-I" /><font size="2" face="Arial">Save thumbnails in separated files</font><br><br><br>
<input  type="submit" style="width:10%; font-size:16px; color:#FFFFFF; border: 1px solid #FFFFFF; font-weight:bold; cursor:pointer; background-color: #14130E;"  name="cap"  value="Capture"/></div></div>

<?php

$r = $_POST['r'];
$c = $_POST['c'];
$f = $_POST['group2'];
$i = $_POST['info'];
$I = $_POST['separado'];
$t = $_POST['time'];
$n = $_POST['group1'];
$font=$_POST['font'];
$m = $_POST['m'];
$tx = $_POST['text'];
$d = $_POST['d'];
$archivo = $_POST['video'];
$j = $_POST['calidad'];

if ($tx==""){
	$texto = "";
}else{
	$texto = '-T '.$tx.'';
}
	
	if ($_POST['todo']=="no"){
	
	exec("".$dirhome."/mtn -f 'font/".$font."' -F ".$n.":".$m." -k ".$f." -c ".$c." -j ".$j." -r ".$r." ".$i." ".$I." ".$t." -D ".$d." ".$texto." ".$dirhome."/0x14/".$archivo.""); 

	$ext=strtolower(strrchr($archivo,'.'));
	$vdofile=str_ireplace($ext,"_s.jpg",$archivo);
	if (file_exists(getcwd()."/0x14/".$vdofile)){	
		echo '<br><br><img src="0x14/'.$vdofile.'" /><BR />';
	}
	else
	{
		echo '<br>Error in generating ScreenShot of <b><i>'.$archivo.'</i></b> <BR />';
	}
	  
	}
	
	if ($_POST['todo']=="si"){
	exec("".$dirhome."/mtn -f 'font/".$font."' -F ".$n.":".$m." -k ".$f." -c ".$c." -r ".$r." -j ".$j." ".$i." ".$I." ".$t." -D ".$d." ".$texto." ".$dirhome."/0x14/*.wmv ".$dirhome."/0x14/*.mpeg ".$dirhome."/0x14/*.mov ".$dirhome."/0x14/*.mp4 ".$dirhome."/0x14/*.flv ".$dirhome."/0x14/*.avi ".$dirhome."/0x14/*.mkv ".$dirhome."/0x14/*.3gp ".$dirhome."/0x14/*.f4v ".$dirhome."/0x14/*.mpg ".$dirhome."/0x14/*.swf ".$dirhome."/0x14/*.asf ".$dirhome."/0x14/*.3g2 ".$dirhome."/0x14/*.divx ".$dirhome."/0x14/*.dsm ".$dirhome."/0x14/*.evo ".$dirhome."/0x14/*.m1v ".$dirhome."/0x14/*.m2ts ".$dirhome."/0x14/*.vob");
	  
	   echo "<br><br><font size=\"2\" color=\"#9FFF9F\" face=\"Arial\"><b>Thumbnails Successfully Created.</b></font><br>";

	}

?>
<br><br><a href="index.php"><font size="2" face="Arial">Back to RapidLeech</b></a>

<br><br><br><br>

<font size="2" face="Arial"><b><u>Notes:</u></b> <br>
<OL TYPE=A>
<LI>To take thumbnails of an individual file, it can not contain special characters such as spaces, ( ), %, ?, &iquest;, ", etc, in name of the file.<br> 
<LI>To take thumbnails of all video files there is no character restrictions.<br>
<LI>To save thumbnails in separate files is recommended to set columns to 1 to conserve original resolution of video.<br>
</OL>
**Made BY Sakib  [<a href="http://www.itleech.com"> ITLEECH </a>] Modified By Slider324 [<a href="http://www.incelugi.info"> Incelugi </a>] For OLC Mods Script**
<br><br></font>

<br>
</center></body></html>
