<?php
define('RAPIDLEECH', 'yes');

error_reporting(0);

//error_reporting(E_ALL); 
@ini_set('display_errors', true); 

define('MISC_DIR', 'misc/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', 'configs/');
define('LANG_DIR', 'languages/');
$PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;

$rev_num = '36B.Rv7.3';
$RL_VER = 'Rx08.ii'.$rev_num;
$msg = "Powered by OLC";
$VERSION = '<small style="color:#239FD9"><b>[ <a href="http://ocean-leecher.net/forum/index.php" target="_blank">olc</a> :: <a href="javascript:void(0)" title="room #14">ccpb</a> :: <a href="http://kaskus.us/" target="_blank">kaskus</a> ]</b></small>';
require_once(CONFIG_DIR."config.php");
require_once (CLASS_DIR."other.php");

// Load languages set for del
$vpage = "del";
require_once(LANG_DIR."language.{$options['lang']}.inc.php");

$charSet = (isset($charSet) && !empty($charSet) ? $charSet : 'charset=UTF-8');
//$charSet = 'charset=UTF-8';

define('DOWNLOAD_DIR', (substr($options['download_dir'], 0, 6) == "ftp://" ? '' : $options['download_dir']));
define('TPL_PATH', 'tpl'. '/' . $options['csstype'] . '/');
define('IMAGE_DIR', MISC_DIR . TPL_PATH);
error_reporting(6135);
header("Content-type: text/html; $charSet");

function notexist() {
	global $msg, $VERSION, $rev_num, $RL_VER, $options, $dtxt;
	echo "<html><head><title>:: ".$RL_VER." ::</title><meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\"><style type=\"text/css\"><!--@import url(\"".IMAGE_DIR."style_sujancok".$options['csstype'].".css\");--></style></head><body><br><h1>".$RL_VER.": NoP</h1><center><b class='r'>".$dtxt['_inexist']."</b><br><br><hr style='width:500;'>".$msg."<br>".$VERSION."</center></body></html>";
	exit ();
}

function deleteIt($fileName, $fileID, $list){
	@unlink ( $fileName );
	unset($list[$fileID]);
	updateListInFile ( $list );
}

//=======/end func

$wajax = false;
if(isset($_GET['met'])) {
	if($_GET['met']=='ajax') $wajax = true;
}

if(!isset($_GET['d'])) {
	notexist();
}else{
	$dkey = $_GET['d'];
	$ck_showall = (isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"]:false); 
	
	if(!preg_match('/^[a-zA-Z0-9]{20}-[0-9]{2}$/', $dkey, $match)){
	  notexist(); 
	}else
	{
      $rotKey = stristr($dkey, "-");
	  $rotKey = (int)substr($rotKey, 1, strlen($rotKey));
	  $hashdel = explode(":", trim(base64_decode(rotN($_GET ['d'],-($rotKey)))));
	  $fileDate = (int) $hashdel[0];
	  $backupshow_all = $options['show_all'];
	  $options['show_all'] = true;
	  _create_list(true, false, 1);
	}
	if(!$list) { 
	  notexist(); 
	}else{
	  $fileName = $list[$fileDate]["name"];	  
	  
      { // we load $list from show download mode
	    unset($list);
	    $options['show_all'] = $backupshow_all;
	    _create_list(true, false, 2);
	  }
	}
	if(!file_exists($fileName) || strlen($fileName)==0)
	{ 
	 notexist(); 
	}
}


if(!$wajax){
?>
<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type"	content="text/html; <?php echo $charSet;?>">
<meta name="robots" content="noindex, nofollow">
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>style_sujancok<?php print $options['csstype'];?>.css");
-->
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
</style>
<title>:: Rx08.ii<?php echo $rev_num;?> ::</title>
<script type="text/javascript"> d = document;</script>
</head>
<body>
<iframe src="about:blank" name="ifrmAct" id="ifrmAct" width="0" height="0" style="display:none;"></iframe>

<div class="head_container" id="head_container" ><center>
<div class="tdheadolgo">&nbsp;</div></center>
</div>
<center>
<?php 
if(!isset($_POST['submit'])) {
?>
<div id="del_container">
  <form name="frm_delete" action="<?php echo $PHP_SELF."?d=".$_GET['d']; ?>" method="post">
	<input type="hidden" name="task" value="doDel">
	<p><?php echo $dtxt['_rsure'];?> <b class="b"><?php echo $dtxt['_todelete'];?></b>: </p>
	<b class="g"><?php echo basename($fileName)?></b><div id="separator" style="height:10px;"></div>
	<input id="submiter" type="submit" name="submit" value="Delete">
	<input id="jssubmiter" type="button" class="blueButton" name="jsubmit" value="Delete" onclick="pler();">
	
  </form>
</div>
<script type="text/javascript">
  thisdelfile = '<?php echo basename($PHP_SELF);?>';
  paramdel = '?d=<?php echo $_GET["d"];?>';
  lineid = '<?php echo (isset($_GET["lineid"])?$_GET["lineid"]:"");?>';
  cl = d.location.pathname;
  caller = cl.substring(cl.lastIndexOf('/')+1, cl.length);
  if(caller!=thisdelfile){
   tgt = 'submiter'; 
   d.getElementById('head_container').style.display='none';
  }else{
   tgt = 'jssubmiter';
   d.getElementById('ifrmAct').style.display='none';
  }
  d.getElementById(tgt).style.display='none';
  function pler(){
    d.getElementById('ifrmAct').src = thisdelfile + paramdel + '&met=ajax';
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
</script>


<?php
}
else
{
 $task= (isset($_POST['task']) ? $_POST['task'] : "");
 if($task='doDel')
 { 
   if (file_exists($fileName) && strlen($fileName)>0) {
	deleteIt($fileName, $fileDate, $list);
	$fn = htmlspecialchars(basename($fileName));
	echo "<p><b class=\"g\">{$fn}</b><br><span class=\"g\">{$dtxt['_sucesdelete']}</span></p><p>{$dtxt["_thx"]}</p>";
?>
<p id="closebtn"></p>
<script type="text/javascript">
function reloadparent(){
  window.opener.location.reload();
  window.close(self);
}
if(window.opener!=null){
  document.getElementById('closebtn').innerHTML="<button onclick='reloadparent();'>CloseZ</button>";
}
</script>
<?php
   }
 }
}
?>

<br><hr style='width:500;border-top:2px solid #ACACAC;'>
<div class="footer_container"> <?php echo $msg."<br>".$VERSION;?></div>
</center>
</body>
</html>

<?php

} else { // with ajax, do delete then call the parent function, upd_parent()

 $task= (isset($_POST['task']) ? $_POST['task'] : ""); 
 if($task='doDel'){
  if(file_exists($fileName) && strlen($fileName)>0) {
   deleteIt($fileName, $fileDate, $list);
   $fn = htmlspecialchars(basename($fileName));
   $ret = "<p><b class=\"g\">{$fn}</b><br><span class=\"g\">{$dtxt['_sucesdelete']}</span></p><p>{$dtxt["_thx"]}</p>";
   ?>
<html><body>
<script type="text/javascript">
if(window.parent){
   retxt = '<?php echo $ret;?>';
   window.parent.upd_parent(retxt);
}
</script>

</body></html>
   <?php
  }
 }

}
?>