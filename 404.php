<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: 404.php - _R_V_T_™ $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once './global.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"	content="text/html; charset=<?php echo $charSet;?>" />
<meta name="robots" content="noindex, nofollow" />
<link rel="shortcut icon" type="image/gif" href="<?php echo IMAGE_DIR.'ico_home.gif'.'?'.rand(11,9999);?>" />
<link type="text/css" href="<?php print IMAGE_DIR;?>style_sujancok<?php print $options["csstype"];?>.css?<?php echo rand(1,9999);?>" rel="stylesheet" media="screen" />
<title><?php echo ':: ' . $RL_VER . ' ::';?></title>
</head><body>
<div class="head_container" align="center"><a href="javascript:;" class="tdheadolgo" title="Rapidleech36B"></a>&nbsp;</div>
<div align="center"><h1>
<?php echo $RL_VER;?>
<br />
<?php
if (!isset($dead_msg)) {
	echo $L->say['_deny'];
} else {
	echo htmlentities($dead_msg);
}
?>
</h1>
</div></body>
</html>
