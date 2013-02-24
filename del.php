<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: del.php - 06apr2010-Idx $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";

$msg = "Powered by OLC";
$VERSION = '<small style="color:#239FD9"><b>[ <a href="http://ocean-leecher.net/forum/index.php" target="_blank">olc</a> :: <a href="javascript:;" title="room #14">ccpb</a> :: <a href="http://kaskus.co.id/" target="_blank">kaskus</a> ]</b></small>';

function notexist() {
	global $msg, $VERSION, $RL_VER, $options, $L;
	echo "<html><head><title>:: $RL_VER ::</title><meta http-equiv='Content-Type' content='text/html; charset=" . ($charSet != "" ? $charSet : "utf-8") . "'><link type='text/css' href='" . IMAGE_DIR . "style_sujancok" . $options['csstype'] . "css?" . rand(1, 9999) . "' rel='stylesheet'></head><body><br /><h1>$RL_VER: NoP</h1><center><b class='r'>" . $L->say['_inexist'] . "</b><br /><br /><hr style='width:500px;' />$msg<br />$VERSION</center></body></html>";
	exit();
}

function deleteIt($fileName, $fileID, $list) {
	@unlink($fileName);
	unset($list[$fileID]);
	updateListInFile($list);
}

//************************
// MAIN ...
//************************
header("Content-type: text/html; charset=" . ($charSet != "" ? $charSet : "utf-8"));

$wajax = false;
if (isset($_GET['met'])) {
	if ($_GET['met'] == 'ajax') $wajax = true;
}

if (!isset($_GET['d'])) {
	notexist();
} else {
	$dkey = $_GET['d'];
	$ck_showall = (isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"] : false);

	if (!preg_match('/^[a-zA-Z0-9]{20}-[0-9]{2}$/', $dkey, $match)) {
		notexist();
	} else {
		$rotKey = stristr($dkey, "-");
		$rotKey = (int) substr($rotKey, 1, strlen($rotKey));
		$hashdel = explode(":", trim(base64_decode(rotN($_GET ['d'], -($rotKey)))));
		$fileDate = (int) $hashdel[0];
		$backupshow_all = $options['show_all'];
		$options['show_all'] = true;
		_create_list(true, false, 1);
	}
	if (!$list) {
		notexist();
	} else {
		$fileName = $list[$fileDate]["name"];
		// we load $list from show download mode
		unset($list);
		$options['show_all'] = $backupshow_all;
		_create_list(true, false, 2);
	}

	if (!file_exists($fileName) || strlen($fileName) == 0) notexist();
}

if (!$wajax) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"	content="text/html; charset=<?php echo $charSet; ?>" />
<meta name="robots" content="noindex, nofollow" />
<link type="text/css" href="<?php print IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen" />
<title>:: Rx08.ii<?php echo $rev_num; ?> ::</title>
<script type="text/javascript"> d = document;</script>
</head>
<body>
<iframe src="about:blank" name="ifrmAct" id="ifrmAct" width="0" height="0" style="display:none;"></iframe>

<div class="head_container" id="head_container" align="center">
<a href="javascript:;" class="tdheadolgo" title="Rapidleech36B"></a>&nbsp;
</div>
<center>
<?php
	if (!isset($_REQUEST['submit'])) {
?>
<div id="del_container">
<form name="frm_delete" action="<?php echo $PHP_SELF . "?d=" . $_GET['d']; ?>" method="post">
<input type="hidden" name="task" value="doDel" />
<p><?php echo $L->say['_rsure']; ?> <b class="b"><?php echo $L->say['_todelete']; ?></b>: </p>
<b class="g"><?php echo basename($fileName) ?></b><div id="separator" style="height:10px;"></div>
<input id="submiter" type="submit" name="submit" value="Delete" />
<input id="jssubmiter" type="button" class="blueButton" name="submit" value="Delete" onclick="del();" />
</form>
</div>
<script type="text/javascript">
/* <![CDATA[ */
	thisdelfile = '<?php echo $PHP_SELF; ?>';
	paramdel = '?d=<?php echo $_GET["d"]; ?>';
	lineid = '<?php echo (isset($_GET["lineid"]) ? $_GET["lineid"] : ""); ?>';
	cl = d.location.pathname;
	if(cl!=thisdelfile){
		tgt = 'submiter';
		d.getElementById('head_container').style.display='none';
	}else{
		tgt = 'jssubmiter';
		d.getElementById('ifrmAct').style.display='none';
	}
	d.getElementById(tgt).style.display='none';
	function del(){
		iframeUri = thisdelfile + paramdel + '&met=ajax&task=doDel&submit=Delete';
		d.getElementById('ifrmAct').src = iframeUri;
	}
	function wipeLine(){
		d.getElementById('brs'+lineid).style.display = 'none';
		try{d.getElementById('chkfL-'+lineid).checked = false;}catch(e){}
	}
	function upd_parent(retxt){
		d.getElementById('del_container').innerHTML = retxt;
		setTimeout("wipeLine()", 1300);
		setTimeout("$.facebox.close()", 1000);
	}
/* ]]> */
</script>
<?php
	} else {
		$task = (isset($_POST['task']) ? $_POST['task'] : "");
		if (($task = 'doDel')) {
			if (file_exists($fileName) && strlen($fileName) > 0) {
				deleteIt($fileName, $fileDate, $list);
				$fn = htmlspecialchars(basename($fileName));
				echo "<p><b class=\"g\">{$fn}</b><br /><span class=\"g\">{$L->say['_sucesdelete']}</span></p><p>{$L->say["_thx"]}</p>";
?>
<p id="closebtn"></p>
<script type="text/javascript">
/* <![CDATA[ */
	function reloadparent(){
		window.opener.location.reload();
		window.close(self);
	}
	if(window.opener!=null){
		document.getElementById('closebtn').innerHTML="<button onclick='reloadparent();'>CloseZ</button>";
	}
/* ]]> */
</script>
<?php
			}
		}
	}
?><br /><hr style='width:500px; border-top:1px solid #ACACAC;' />
<div class="footer_container"> <?php echo $msg . "<br />" . $VERSION; ?></div>
</center>
</body>
</html>
<?php
} else { // with ajax, do delete then call the parent function, upd_parent()
	$task = (isset($_REQUEST['task']) ? $_REQUEST['task'] : "");
	if (($task = 'doDel')) {
		if (file_exists($fileName) && strlen($fileName) > 0) {
			deleteIt($fileName, $fileDate, $list);
			$fn = htmlspecialchars(basename($fileName));
			$ret = "<p><b class=\"g\">{$fn}</b><br /><span class=\"g\">{$L->say['_sucesdelete']}</span></p><p>{$L->say["_thx"]}</p>";
?>
<html><body>
<script type="text/javascript">
/* <![CDATA[ */
	if(window.parent){
		retxt = '<?php echo $ret; ?>';
		window.parent.upd_parent(retxt);
	}
/* ]]> */
</script>

</body></html>
<?php
		}
	}
}
?>