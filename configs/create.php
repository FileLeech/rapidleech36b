<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}

$c = $options['mtn_col_row']['mtn_colums'];
$r = $options['mtn_col_row']['mtn_rows'];
$text = $options['mtn_text'];
$videoenable = $options['mtn_video_option']['enable'];
$timeenable = $options['mtn_time']['enable'];
$font = $options['mtn_video_option']['txtfont'];
$infocolor = $options['mtn_video_option']['txtcolor'];
$size = $options['mtn_video_option']['txtsize'];
$tcolor = $options['mtn_time']['tcolor'];
$ed = $options['mtn_edge'];
$bg = $options['mtn_bgcolor'];
$q = $options['mtn_quality'];
$exec_dir = BINARY_DIR. 'media/mtn';
$font_dir = STATIC_DIR. 'font/';
if ($_POST['video'] != "") {
	$files = $options['download_dir'] . $vdofile;
	if ($_POST['all'] == "true") {
		$video = array();
		$video = vidlist($options['download_dir']);
	} else {
		$video = array();
		$video[0] = $_POST['video'];
	}
	foreach ($video as $vdo) {
		$cmd = getcwd() . $exec_dir;
		if (!$videoenable) {
			$cmd.=" -i";
		}
		if (!$timeenable) {
			$cmd.=" -t";
		}
		$cmd.=" -T '" . $text . "' -f '$font_dir" . $font . "' -b 0.60 -B 0.0 -C 6000 -D 8 -g " . $ed . " -F '" . $infocolor . ":" . $size . ":'$font_dir" . $font . "':" . $tcolor . ":" . $tcolor . ":" . $size . "' -j " . $q . " -k " . $bg . " -E 0.0 -c " . $c . " -r " . $r . " '" . getcwd() . "/$files/" . $vdo . "'";
		shell_exec($cmd);
		$ext = strtolower(strrchr($vdo, '.'));
		$vdofile = str_ireplace($ext, "_s.jpg", $vdo);
		if (file_exists(getcwd() . $options['download_dir'] . $vdofile)) {
			$image = $options['download_dir'] . $vdofile;
			echo '<h2><a href="' . $options['download_dir'] .  $vdofile . '">' . $vdo . '</a></h2>';
			echo "<img src=\"$image\">";
		} else {
			echo '<BR />Error in generating <b><i>' . $vdo . '</i></b> <BR />';
		}
	}
}
?>