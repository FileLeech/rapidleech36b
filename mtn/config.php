<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}
if ($col_row['colums']>0 && $col_row['colums']<6)
{
	$c=$col_row['colums'];
}
else
{
	$c=" 1";
}
if ($col_row['rows']>0 && $_col_row['rows']<11)
{
	$r=$col_row['rows'];
}
else
{
	$r=" 1";
}
$videoenable = $video['enable'];
$timeenable = $time['enable'];
$font = $video['font'];
$infocolor = $video['color'];
$size = $video['size'];
$tcolor = $time['color'];
$ed = $edge;
$bg = $bgcolor;
$q = $quality;
if ($_POST['video']!="")
{
    $files = $download_dir."$vdofile";
	if ($_POST['all']=="true")
	{
		$video = array();
		$new_dir = "./".str_replace("/",'',$download_dir);
		$video = vidlist($new_dir);
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
	//$cmd.=" -T '".$_POST['T']."' -f 'mtn/".$_POST['f']."' -b 0.60 -B 0.0 -C 6000 -D 8 -g ".$_POST['g']." -h ".$_POST['h']." '".$_POST['I']."' -L '".$_POST['iL'].":".$_POST['tL']."' -F '".$_POST['Tc'].":".$_POST['Ts'].":'mtn/".$_POST['f']."':".$_POST['tc'].":".$_POST['ts'].":".$_POST['Ts']."' -j ".$_POST['j']." -k ".$_POST['k']." -E 0.0 -c ".$c." -r ".$r." '".getcwd()."/$files/".$vdo."'";
	$cmd.=" -T '".$text."' -f 'mtn/".$font."' -b 0.60 -B 0.0 -C 6000 -D 8 -g ".$ed." -F '".$infocolor.":".$size.":'mtn/".$font."':".$tcolor.":".$tcolor.":".$size."' -j ".$q." -k ".$bg." -E 0.0 -c ".$c." -r ".$r." '".getcwd()."/$files/".$vdo."'";
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