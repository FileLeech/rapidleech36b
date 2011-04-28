<?php
if (!defined('RAPIDLEECH')) {
	require('../index.php');
	exit;
}
$c=$col_row['mtn_colums'];
$r=$col_row['mtn_rows'];
$text = $mtn_text;
$videoenable = $video_option['enable'];
$timeenable = $time['enable'];
$font = $video_option['txtfont'];
$infocolor = $video_option['txtcolor'];
$size = $video_option['txtsize'];
$tcolor = $time['tcolor'];
$ed = $mtn_edge;
$bg = $bgcolor;
$q = $mtn_quality;
if ($_POST['video']!="")
{
    $files = $download_dir."$vdofile";
	if ($_POST['all']=="true")
	{
		$video = array();
		$video = vidlist($download_dir);
	}
	else
	{
		$video=array();		
		$video[0] = $_POST['video'];
	}
foreach ($video as $vdo)
{
	$cmd=getcwd()."/mtn/mtn";
	if (!$videoenable)
	{
		$cmd.=" -i";
	}
	if (!$timeenable)
	{
		$cmd.=" -t";
	}
	$cmd.=" -T '".$text."' -f 'mtn/font/".$font."' -b 0.60 -B 0.0 -C 6000 -D 8 -g ".$ed." -F '".$infocolor.":".$size.":'mtn/font/".$font."':".$tcolor.":".$tcolor.":".$size."' -j ".$q." -k ".$bg." -E 0.0 -c ".$c." -r ".$r." '".getcwd()."/$files/".$vdo."'";
	shell_exec($cmd);
	$ext=strtolower(strrchr($vdo,'.'));
	$vdofile=str_ireplace($ext,"_s.jpg",$vdo);
	if (file_exists(getcwd()."/$files/".$vdofile))
	{
        $image = $download_dir."$vdofile";
		echo '<h2><a href="'.$download_dir.''.$vdofile.'">'.$vdo.'</a></h2>';
	}
	else
	{
		echo '<BR />Error in generating <b><i>'.$vdo.'</i></b> <BR />';
	}
                echo "<img src=\"$image\">";
}
}
?>