<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<title>Movie Thumbnailer</title>
<link href="misc/tpl/_default/style_sujancok_default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<center><img src="misc/tpl/_default/rl_lgo.png" alt="RapidLeech" border="0" /></center>
<center>
<br><br><br>

<?php 
//---- Configuracion -------------------
$dirhome = "/home/USERNAME HERE/public_html/" 
//--------------------------------------
?>

<div id="content"><div class="box"><font size="5" face="Arial"><b>Configuration:</b></font><form action="mtn.php" method="post"><br><br>
<table width="47%" >
<tr>
<td><font size="2" face="Arial"><b>File:</b></font>&nbsp;&nbsp;&nbsp;</td>
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
	<td><font size="2" face="Arial"><b>All?:</b></font></td>
	<td><select name="todo"> 
	<option value="no" SELECTED>No</option>
	<option value="si" >Yes</option></select></td>
		
</tr>	
<tr>
<td></td>
<td>(Select Yes to make thumbnails to all files)</td>
</tr>

</table>
<br>

<table>
<tr>
<td><font size="2" face="Arial"><b>Columns:</b></font></td>
<td><select name="c"> 
	<option value="1">1</option>
	<option value="2" SELECTED>2</option>
	<option value="3">3</option></select></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Rows:</b></font></td>
	<td><select name="r">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3" SELECTED>3</option>
	<option value="4">4</option>
    <option value="5">5</option>
	<option value="6">6</option>
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
	<option value="10" SELECTED >10</option>
	<option value="11">11</option>
	<option value="12">12</option>
	<option value="13">13</option> 
    <option value="14">14</option> 
    <option value="15">15</option></select></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Additional Text:</b></font></td>
	<td><input type="text" name="text"> &nbsp;(Add text below the video info. Do not add spaces)</td>
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Image Quality:</b></font></td>
	<td><select name="calidad"> 
	<option value="10">10</option>
	<option value="20">20</option>
	<option value="30">30</option>
	<option value="40">40</option>
	<option value="50">50</option>
	<option value="60">60</option>
	<option value="70">70</option>
	<option value="80">80</option>
	<option value="90" SELECTED>90</option>
	<option value="100">100</option></select> 
</tr>
<tr>
	<td><font size="2" face="Arial"><b>Precision:</b></font></td>
	<td><select name="d"> 
	<option value="0" SELECTED >0</option>
	<option value="2">2</option>
	<option value="4">4</option>
	<option value="6">6</option>
	<option value="8">8</option></select> &nbsp;(Choose the precision of the thumbnails to prevent blur. 0 = Disable)</td>
</tr>
<tr>
	<td><br><font size="2" face="Arial"><b>Text Color:</b></font></td>
	<td><br><input type="radio" name="group1" value="000000" checked > Black 
	<input type="radio" name="group1" value="FFFFFF"> White 
	<input type="radio" name="group1" value="FF0000"> Red
	<input type="radio" name="group1" value="0080C0"> Blue 
	<input type="radio" name="group1" value="008000"> Green 
	<input type="radio" name="group1" value="FFFF00"> Yellow
	<input type="radio" name="group1" value="FF8C00"> Orange 
	<input type="radio" name="group1" value="696969"> Grey 
	<input type="radio" name="group1" value="511C7C"> Indigo 
	</td>
</tr>
<tr>
	<td><br><font size="2" face="Arial"><b>Background Color:</b></font></td>
	<td><br><input type="radio" name="group2" value="000000"> Black 
	<input type="radio" name="group2" value="FFFFFF" checked> White 
	<input type="radio" name="group2" value="FF0000"> Red
	<input type="radio" name="group2" value="0080C0"> Blue 
	<input type="radio" name="group2" value="008000"> Green 
	<input type="radio" name="group2" value="FFFF00"> Yellow
	<input type="radio" name="group2" value="FF8C00"> Orange 
	<input type="radio" name="group2" value="696969"> Grey 
	<input type="radio" name="group2" value="511C7C"> Indigo
	</td>
</tr>
</table>
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
	
	exec("".$dirhome."/mtn -f ".$dirhome."/tahomabd.ttf -F ".$n.":".$m." -k ".$f." -c ".$c." -j ".$j." -r ".$r." ".$i." ".$I." ".$t." -D ".$d." ".$texto." ".$dirhome."/0x14/".$archivo.""); 

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
	exec("".$dirhome."/mtn -f ".$dirhome."/tahomabd.ttf -F ".$n.":".$m." -k ".$f." -c ".$c." -r ".$r." -j ".$j." ".$i." ".$I." ".$t." -D ".$d." ".$texto." ".$dirhome."/0x14/*.wmv ".$dirhome."/0x14/*.mpeg ".$dirhome."/0x14/*.mov ".$dirhome."/0x14/*.mp4 ".$dirhome."/0x14/*.flv ".$dirhome."/0x14/*.avi ".$dirhome."/0x14/*.mkv ".$dirhome."/0x14/*.3gp ".$dirhome."/0x14/*.f4v ".$dirhome."/0x14/*.mpg ".$dirhome."/0x14/*.swf ".$dirhome."/0x14/*.asf ".$dirhome."/0x14/*.3g2 ".$dirhome."/0x14/*.divx ".$dirhome."/0x14/*.dsm ".$dirhome."/0x14/*.evo ".$dirhome."/0x14/*.m1v ".$dirhome."/0x14/*.m2ts ".$dirhome."/0x14/*.vob");
	  
	   echo "<br><br><font size=\"2\" color=\"#9FFF9F\" face=\"Arial\"><b>Thumbnails Successfully Created.</b></font><br>";

	}

?>
<br><br><a href="index.php"><font size="2" face="Arial">Back to RapidLeech</b></a>

<br><br><br><font size="2" face="Arial"><b><u>Video formats supported:</u></b> <br>.3gp, .3g2, .asf, .avi, .divx, .dsm, .evo, .f4v, .flv, .mkv, .m1v, .m2ts, .mov, .mp4, .mpg, .mpeg, .swf, .vob, .wmv<br></b></font>
<br>
<font size="2" face="Arial"><b><u>Notes:</u></b> <br>* To take thumbnails of an individual file, it can not contain special characters such as spaces, ( ), %, ?, &iquest;, ", etc, in name of the file.<br> 
* To take thumbnails of all video files there is no character restrictions.<br>
* To save thumbnails in separate files is recommended to set columns to 1 to conserve original resolution of video.<br>
**MADE BY Sakib [<a href="http://www.itleech.com">ITLEECH</a>] For OLC Mods Script**
<br><br></font>
<br>
</center></body></html>
