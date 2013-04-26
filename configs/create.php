<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}

function is_error($errmsg) {
  echo "<div>{$errmsg}</div>";
  echo '<div><br /><br /><b><a href="'.$PHP_SELF.'">Back</a></b><br /><br />'.CREDITS.'</div>';
  echo '</div></body></html>';
  exit();
}

function execOutput($command) {
	exec($command.' 2>&1', $output);
	return implode('<br />', $output);
}

$font_dir = STATIC_DIR . 'font/';

if (isset($_POST['generate'])) {
  $exec_file = '';
  if (strtoupper(substr(PHP_OS, 0, 3)) == "WIN") $exec_file = ROOT_DIR . 'binary\media\mtn.exe';
  else $exec_file = BINARY_DIR . 'media/mtn';
  $errmsg = ''; $iserr = false;
  if (!file_exists($exec_file)) $errmsg .="<b class='r'><i>MTN binary file not found!</i></b><br />"; $iserr = true;
  if (!function_exists('exec')) $errmsg .="<b class='r'><i>Exec function is disabled in your server!</i></b><br />"; $iserr = true;

  if ($_POST['video'] != '') {
	if ($mtn['mtn_showconfig']['enable']) {
		$mtn['mtn_colrow']['columns'] = $_POST['mtn_colrow']['columns'];
		$mtn['mtn_colrow']['rows'] = $_POST['mtn_colrow']['rows'];
		$mtn['mtn_width'] = $_POST['mtn_width'];
		$mtn['mtn_height'] = $_POST['mtn_height'];
		if ($mtn['mtn_showconfig']['showtext']) {
			$mtn['mtn_text'] = $_POST['mtn_text'];
			$mtn['mtn_suffix'] = $_POST['mtn_suffix'];
		}
		$mtn['mtn_bgcolor'] = $_POST['mtn_bgcolor'];
		$mtn['mtn_quality'] = $_POST['mtn_quality'];
		$mtn['mtn_edge'] = $_POST['mtn_edge'];
		$mtn['mtn_cuttime']['cut'] = $_POST['mtn_cuttime']['cut'];
		$mtn['mtn_cuttime']['time'] = $_POST['mtn_cuttime']['time'];
		$mtn['mtn_individualshots'] = $_POST['mtn_individualshots'];
		$mtn['mtn_saveinfo'] = $_POST['mtn_saveinfo'];
		$mtn['mtn_video_options']['enable'] = $_POST['mtn_video_options']['enable'];
		$mtn['mtn_video_options']['txtcolor'] = $_POST['mtn_video_options']['txtcolor'];
		$mtn['mtn_video_options']['txtfont'] = $_POST['mtn_video_options']['txtfont'];
		$mtn['mtn_video_options']['txtsize'] = $_POST['mtn_video_options']['txtsize'];
		$mtn['mtn_time']['enable'] = $_POST['mtn_time']['enable'];
		$mtn['mtn_time']['tshadow'] = $_POST['mtn_time']['tshadow'];
		$mtn['mtn_time']['tcolor'] = $_POST['mtn_time']['tcolor'];
		$mtn['mtn_location']['info'] = $_POST['mtn_location']['info'];
		$mtn['mtn_location']['time'] = $_POST['mtn_location']['time'];
	}
	$mtn_colrow = $mtn['mtn_colrow']['columns'] * $mtn['mtn_colrow']['rows'] + 1;
	if($mtn['mtn_cuttime']['time'] == 'min') $cal_step = $mtn_colrow * 60 * $mtn['mtn_cuttime']['cut'];
	else $cal_step = $mtn_colrow * $mtn['mtn_cuttime']['cut'];

	if ($_POST['all'] == true) {
		$exts = array('.3gp', '.3g2', '.asf', '.avi', '.dat', '.divx', '.dsm', '.evo', '.flv', '.m1v', '.m2ts', '.m2v', '.m4a', '.mj2', '.mjpg', '.mjpeg', '.mkv', '.mov', '.moov', '.mp4', '.mpg', '.mpeg', '.mpv', '.nut', '.ogg', '.ogm', '.qt', '.swf', '.ts', '.vob', '.wmv', '.xvid');
		$video = array();
		$video = vidlist($options['download_dir'], $exts);
	} else {
		$video = array();
		$video[0] = $_POST['video'];
	}
	foreach ($video as $vdo) {
		$cmd = $exec_file;
		if ($mtn['mtn_video_options']['enable'] == false) $cmd .= ' -i ';
		if ($mtn['mtn_time']['enable'] == false) $cmd .= ' -t ';
		if ($mtn['mtn_individualshots'] == true) { $cmd .= ' -I '; }
		if ($mtn['mtn_width'] != '' && $mtn['mtn_width'] > 0 && $mtn['mtn_width'] < 2001) { $cmd .= ' -w '.$mtn['mtn_width']; }
		if($mtn['mtn_cuttime']['cut'] != '') { $cmd .= ' -C '.$cal_step; }
		if($mtn['mtn_text'] != '') { $cmd .= ' -T '.escapeshellarg($mtn['mtn_text']); }
		if($mtn['mtn_height'] != '') { $cmd .= ' -h '.$mtn['mtn_height']; }
		if($mtn['mtn_saveinfo'] == true) { $cmd .= ' -N _'.escapeshellarg($mtn['mtn_suffix']).'.txt'; }
		$cmd .= ' -c '.$mtn['mtn_colrow']['columns'].' -r '.$mtn['mtn_colrow']['rows'].' -o _'.escapeshellarg($mtn['mtn_suffix']).'.jpg -k '.$mtn['mtn_bgcolor'].' -j '.$mtn['mtn_quality'].' -g '.$mtn['mtn_edge'].' -F '.$mtn['mtn_video_options']['txtcolor'].':'.$mtn['mtn_video_options']['txtsize'].':'.$font_dir.$mtn['mtn_video_options']['txtfont'].':'.$mtn['mtn_time']['tcolor'].':'.$mtn['mtn_time']['tshadow'].':'.$mtn['mtn_video_options']['txtsize'].' -f '.$font_dir.''.$mtn['mtn_video_options']['txtfont'].' -L '.$mtn['mtn_location']['info'].':'.$mtn['mtn_location']['time'].' -P '.$options['download_dir'].escapeshellarg($vdo);

		echo execOutput($cmd);

		$ext = strtolower(strrchr($vdo, '.'));
		$videofile = str_ireplace($ext, '_'.$mtn['mtn_suffix'].'.jpg', $vdo);
		if (file_exists($options['download_dir'].$videofile)) {
			$image = $options['download_dir'].$videofile;
			echo '<div><a href="'.$image.'" title="'.$videofile.'"><img src="'.$image.'"></a></div>';
			echo '<div><br /><br /><b><a href="'.$PHP_SELF.'">Back</a></b><br /><br />'.CREDITS.'</div>';
			echo '</div></body></html>';
			exit();
		} else {
			$iserr = true;
			$errmsg .='<b class="r">Error in generating Screenshot of <b><i>'.$vdo.'</i></b><br />';
		}
	}
  } elseif ($_POST['video'] == '') {
	$iserr = true;
	$errmsg .= '<b class="r"><i>No video selected for generate Screenshot </i></b><br />';
  }
  if ($iserr) is_error($errmsg);
} else {
?>
<form name="mtn_form" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" method="post">
<table align="center" border="0" cellpadding='2' cellspasing='10'>
<tr>
<td style="text-align: right;">Video File <span class="nav_text" id="videofile" style="cursor:help">[?]</span> :</td>
<td><select name="video">
<?PHP
	$exts = array(".3gp", ".3g2", ".asf", ".avi", ".dat", ".divx", ".dsm", ".evo", ".flv", ".m1v", ".m2ts", ".m2v", ".m4a", ".mj2", ".mjpg", ".mjpeg", ".mkv", ".mov", ".moov", ".mp4", ".mpg", ".mpeg", ".mpv", ".nut", ".ogg", ".ogm", ".qt", ".swf", ".ts", ".vob", ".wmv", ".xvid");
	$files = array();
	$files = vidlist($options['download_dir'], $exts);
	foreach ($files as $file) {
		echo "\t<option value='{$file}'>{$file}</option>\r\n";
	}
?>
</select>
<input type="checkbox" name="all" value="1" /> Generate all.
</td>
</tr>
<?php
	if($mtn['mtn_showconfig']['enable']) {
?>
<tr>
<td style="text-align: right;">Columns x Rows : </td>
<td><select id="mtn_colrow_columns" name="mtn_colrow[columns]">
<?php
		for ($i=1; $i<6; $i++) {
			echo "\t<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select> x
<select id="mtn_colrow_rows" name="mtn_colrow[rows]">
<?php
		for ($i=1; $i<11; $i++) {
			echo "\t<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select></td>
</tr>
<tr>
<td style="text-align: right;">Width <span class="nav_text" id="width" style="cursor:help">[?]</span> : </td><td><input type="text" id="mtn_width" name="mtn_width" size="3" value="" /></td>
</tr>
<tr>
<td style="text-align: right;">Minimum Height <span class="nav_text" id="height" style="cursor:help">[?]</span> : </td><td><input type="text" id="mtn_height" name="mtn_height" size="3" value="" /></td>
</tr>
<?php
		if($mtn['mtn_showconfig']['showtext']) {
?>
<tr>
<td style="text-align: right;">Text <span class="nav_text" id="text" style="cursor:help">[?]</span> : </td><td><input type="text" id="mtn_text" name="mtn_text" size="25" value="" /></td>
</tr>
<tr>
<td style="text-align: right;">Output Suffix : </td><td><input type="text" id="mtn_suffix" name="mtn_suffix" size="25" value="" /></td>
</tr>
<?php
		}
?>
<tr>
<td style="text-align: right;">Background Color : </td><td><input class="color" id="mtn_bgcolor" name="mtn_bgcolor" size="5" value="" /></td>
</tr>
<tr>
<td style="text-align: right;">Jpeg Quality : </td>
<td><select id="mtn_quality" name="mtn_quality">
<?php
		$mtnquality = array(60 => 'Very Low', 80 => 'Low', 90 => 'Normal', 100 => 'High');
		foreach ($mtnquality as $k => $v) {
			echo "\t<option value='{$k}'>{$v}</option>\r\n";
		}
?>
</select></td>
</tr>
<tr>
<td style="text-align: right;">Edge <span class="nav_text" id="edge" style="cursor:help">[?]</span> : </td>
<td><select id="mtn_edge" name="mtn_edge">
<?php
		for ($i = 0; $i < 6; $i++) {
			echo "\t<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select></td>
</tr>
<tr>
<td style="text-align: right;">Step <span class="nav_text" id="step" style="cursor:help">[?]</span> : </td>
<td><input type="text" id="mtn_cuttime_cut" name="mtn_cuttime[cut]" size="5" value="" />&nbsp;
<select id="mtn_cuttime_time" name="mtn_cuttime[time]">
	<option value="min">minutes</option>
	<option value="sec">seconds</option>
</select></td>
</tr>
<tr>
<td style="text-align: right;">Individual Shots <span class="nav_text" id="individualshots" style="cursor:help">[?]</span> : </td><td><input type="checkbox" id="mtn_individualshots" name="mtn_individualshots" value="1" /></td>
</tr>
<tr>
<td style="text-align: right;">Save Info <span class="nav_text" id="saveinfo" style="cursor:help">[?]</span> : </td><td><input type="checkbox" id="mtn_saveinfo"  name="mtn_saveinfo" value="1" /></td>
</tr>
<tr>
<td style="text-align: right;">Video Info : </td><td><input type="checkbox" id="mtn_video_options_enable" name="mtn_video_options[enable]" value="1" /></td>
</tr>
<tr id="mtn_video_options_0">
<td style="text-align: right;">Available Video Info Setting : </td>
<td>&nbsp;Color <input class="color" id="mtn_video_options_txtcolor" name="mtn_video_options[txtcolor]" size="4" value="" />
&nbsp;Font <select id="mtn_video_options_txtfont" name="mtn_video_options[txtfont]">
<?php
		$exts=array(".ttf", '.otf');
		$fonts = array();
		$fonts = vidlist($font_dir,$exts);
		foreach($fonts as $font) {
			echo "\t<option value='{$font}'>".substr($font, 0, -4)."</option>\r\n";
		}
?>
</select>
&nbsp;Size <select id="mtn_video_options_txtsize" name="mtn_video_options[txtsize]">
<?php
		for($i=8; $i<16; $i++) {
			echo "\t<option value='{$i}'>{$i}</option>\r\n";
		}
?>
</select></td>
</tr>
<tr>
<td style="text-align: right;">Time : </td><td><input type="checkbox" id="mtn_time_enable" name="mtn_time[enable]" value="1" /></td>
</tr>
<tr id="mtn_time_0">
<td style="text-align: right;">Available Time Setting : </td>
<td>&nbsp;Shadow <input class="color" id="mtn_time_tshadow" name="mtn_time[tshadow]" size="5" />&nbsp;Color <input class="color" id="mtn_time_tcolor" name="mtn_time[tcolor]" size="5" /></td>
</tr>
<tr>
<td style="text-align: right;">Location : </td>
<?php
		$mtn_loc = array(1 => 'Lower Left', 2 => 'Lower Right', 3 => 'Upper Right', 4 => 'Upper Left');
?>
<td>&nbsp;Info <select id="mtn_location_info" name="mtn_location[info]">
<?php
		foreach ($mtn_loc as $k => $v) {
			echo "\t<option value='{$k}'>{$v}</option>\r\n";
		}
?>
</select>
&nbsp;Time <select id="mtn_location_time" name="mtn_location[time]">
<?php
		foreach ($mtn_loc as $k => $v) {
			echo "\t<option value='{$k}'>{$v}</option>\r\n";
		}
?>
</select></td>
</tr>
<?php
	}
?>
<tr>
<td colspan="2"><br /><br /><br />
<div style="text-align: center;"><input style="height: 50px;width: 140px;font-size:large;background-color: #393136;" type="submit" id="generate" name="generate" value="Generate" disabled="disabled" /><br /><br /><br />
<a href="<?php echo $options['index_file']; ?>" style="font-size: 15px; font-family: Arial;">Back to RapidLeech</a><br />
<a href="javascript:window.close();" style="font-size: 15px; font-family: Arial;">Close Windows</a><br /><br /></div>
</td>
</tr>
</table>
<div align="center">
<span id="help_videofile" style="font: bolder;font-size: larger; display: none;">File should be supported: 3gp, 3g2, asf, avi, dat, divx, dsm, evo, flv, M1V, m2ts, m2v, m4a, MJ2, moov mjpg, mjpeg, mkv, mov, mp4, mpg, mpeg, mpv, nut, ogg, ogm, qt, swf, ts, vob, wmv, xvid</span>
<span id="help_width" style="font: bolder;font-size: larger; display: none;">Width of output image</span>
<span id="help_height" style="font: bolder;font-size: larger; display: none;">Minimum height of each shot, will reduce of column to fit</span>
<span id="help_text" style="font: bolder;font-size: larger; display: none;">Add text above output image</span>
<span id="help_edge" style="font: bolder;font-size: larger; display: none;">Gap between each shot</span>
<span id="help_step" style="font: bolder;font-size: larger; display: none;">Cut movie and thumbnails not more than the specified seconds</span>
<span id="help_individualshots" style="font: bolder;font-size: larger; display: none;">Save individual shots</span>
<span id="help_saveinfo" style="font: bolder;font-size: larger; display: none;">Save info text to a file .txt</span>
</div>
</form>
<?php
}
?>

