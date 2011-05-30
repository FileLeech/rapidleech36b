<?php
$id=1;
// A work of Chaza and TheOnly92!
// 
// A work of Chaza and TheOnly92!
// Presents auto-upload script!
// We define some constants here, essential for some parts in rapidleech
define('RAPIDLEECH', 'yes');

$PHP_SELF = !isset($PHP_SELF) ? $_SERVER["PHP_SELF"] : $PHP_SELF;
define('ROOT_DIR', realpath("./"));
define('HOST_DIR', 'pluginz/');
define('UPL_DIR', 'upl/');
define('MISC_DIR', 'misc/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', './');

$nn = "\r\n";
$rev_num = '36B.Rv7.3';
$RL_VER = 'Rx08.ii'.$rev_num;

// Some configuration
error_reporting(0);	// This sets error reporting to none, which means no errors will be reported

//error_reporting(6135);
//error_reporting(E_ALL); 
//@ini_set('display_errors', true); 

//ini_set('display_errors', 1);	// This sets error reporting to all, all errors will be reported
@set_time_limit(0);	// Removes the time limit, so it can upload as many as possible
ini_alter("memory_limit", "1024M");	// Set memory limit, in case it runs out when processing large files
@ob_end_clean();	// Cleans any previous outputs
@ob_implicit_flush(TRUE);	// Sets so that we can update the page without refreshing
ignore_user_abort(1);	// Continue executing the script even if the page was stopped or closed
clearstatcache();	// Clear caches created by PHP
require_once(CONFIG_DIR."config.php");	// Reads the configuration file, so we can pick up any accounts needed to use

// Include other useful functions
require_once(CLASS_DIR."other.php");
require_once(HOST_DIR."hosts.php");
require_once(CLASS_DIR."http.php");

define('DOWNLOAD_DIR', (substr($download_dir, 0, 6) == "ftp://" ? '' : $download_dir));	// Set the download directory constant

define('TPL_PATH', 'tpl'. '/' . $csstype . '/');
define('IMAGE_DIR', MISC_DIR . TPL_PATH);

// If you set password for your rapidleech site, this asks for the password
//================

//Cek ip yg banned || is it listed as authorized ip || check country limit
if($limited_edition || $limited_area)
{
  $dlimitation = array($limited_edition, $limited_area);
  require_once("limit_district.php");
}

if(!$forbid_auul){
 if ($login===true){
 if(!isset($_SERVER['PHP_AUTH_USER']) || ($loggeduser = logged_user($users)) === false)
	{
		header('WWW-Authenticate: Basic realm="Rx08"');
		header('HTTP/1.0 401 Unauthorized');
		exit("<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\"><style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body>$nn<h1>$RL_VER: NuLL</h1>$nn</body>$nn</html>");
	}
 }
}else {
 echo "<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">$nn<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style></head>$nn<body>$nn<h1>:: $RL_VER :: <br>AuUL Disabled</h1>$nn</body>$nn</html>";
 exit();
}

$page = 'auul';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>AuUL :: <?php echo $RL_VER;?> ::</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>style_sujancok<?php print $csstype;?>.css");
-->
.container td {	background-color:#001825; padding:2px;}
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
#diviframe{
 background-color: #fff;
 padding: 5px 0 0 0;
 width: 95%;
}
#closer{
 margin:0;
 float:right;
 padding: 0 10px 10px 0;
}
#myul_iframe{
 border:0;
}
</style>
<script type="text/javascript" src="<?php echo MISC_DIR;?>js.php?auul"></script>
<script type="text/javascript">
function showAll(){
  if(getCookie("showAll") == 1){
    deleteCookie("showAll");
  }else{
    d.cookie = "showAll = 1;";
  }
  location.href = "<?php echo $PHP_SELF."?act=files"; ?>"; 
}
function clk(idck){
 var cur = d.getElementById(idck).checked;
 d.getElementById(idck).checked = !cur;
}

var dcontainer, ifrm;
function openmyList(src){
 var hpath = d.location.href;
 hpath = hpath.substring(0, hpath.lastIndexOf("/")+1); 
 ifrm.src= hpath + src + Math.floor(Math.random()*100);
 dcontainer.style.display=''; 
 obj=d.body;
 obj.scrollTop=obj.scrollHeight;
}

function closeframe(){
 ifrm.src= "about:blank"; 
 dcontainer.style.display='none'; 
}

function cleanResidue(){
 var prblock = d.getElementsByTagName('table');
 var dvnfo = d.getElementsByTagName('div');
 for(var i in prblock){
  if(prblock[i].id=='progressblock') prblock[i].innerHTML = '';
 }
 for(var i in dvnfo){
  if(dvnfo[i].id=='info') dvnfo[i].innerHTML = '';
 }
}
</script>
</head>
<body>
<div class="head_container"><center>
<a href="<?php echo $index_file;?>" alt="Rapidleech 2.3"><div class="tdheadolgo">&nbsp;</div></a></center>
</div>
<center>
<?php
	// If the user submit to upload, go into upload page
	if (isset($_GET['action']) && $_GET['action'] == 'upload') {
		// Define another constant
		if(!defined('CRLF')) define('CRLF',"\r\n");
		// The new line variable
		$nn = "\r\n";
		// Initialize some variables here
		$uploads = array();
		$total = 0;
		$hostss = array();
		// Sort the upload hosts and files
		foreach ($_POST['files'] as $file) {
			foreach ($_POST['hosts'] as $host) {
				$hostss[] = $host;
				$uploads[DOWNLOAD_DIR.$file][$host] = "";
				$total++;
			}
		}
		// Clear out duplicate hosts
		$hostss = array_unique($hostss);
		// If there aren't anything
		if (count($uploads) == 0) {
			echo "No files or hosts selected for upload";
			exit;
		}
		$did = 1;
		// Start uploading!
		foreach ($uploads as $file=>$hosts) {
			// If file does not exist
			if (!file_exists($file))
				{
					html_error("ERROR: file not exist $file", 0);
				}
			// If file is not readable
			if (is_readable($file))
				{
					$lfile=$file;
					$lname=basename($lfile);
				}
					else
				{
					html_error("ERROR: not readable $file", 0);
				}
			// Get the size of the file
			$fsize = getSize($lfile);
			// Start uploading this file			
			echo "Uploading <b>".basename($file)."</b>&nbsp[<b class='g'>".bytesToKbOrMbOrGb($fsize)."</b>]<br />";
			// Upload to different hosts

			
			foreach ($hosts as $host=>$value) {
			echo "Transload destination: <b style='color:#FFFF00'>".$host."</b><br/>";
			?>


<script type="text/javascript">
var orlink='<?php echo basename($file); ?> to <?php echo $host; ?>';
</script>
			<?php
		
			//ob_start("callback");
				// If there is a plugin to the host
				if (file_exists(UPL_DIR.$host.".php")){
					// Read the configuration file for the host
					include_once(UPL_DIR.$host.".index.php");
					// If the size of the file is bigger than the max for the host
					if ($max_file_size["$host"]!=false)
						if ($fsize > $max_file_size["$host"]*1024*1024)       
							html_error("Bigger than file-size", 0);
							
							// Upload it!
					include(UPL_DIR.$page_upload["$host"]);
				}else html_error('This service not allowed || Plugin not exist: '.UPL_DIR.trim($host).".php", 0); 
				// Save the download link
				$uploads[$file][$host] = $download_link;
				$uploads[$file]['date'] = date("Y-m-d H:i:s");
				echo "<script>cleanResidue();</script>" 
				     . "\r\n</td></tr></table>\r\n";				
			//ob_get_contents();
			//@ob_end_flush();
			//@ob_get_clean();
			}
		}
	
	//////////////////////////////New code added here///////
	// Write links to a file
	if(!file_exists(MYUPLOAD_LST))@touch(MYUPLOAD_LST);
	$fh = @fopen(MYUPLOAD_LST, 'r');
	$fcontent = ($fh && @filesize(MYUPLOAD_LST)>0 ? @fread($fh, @filesize(MYUPLOAD_LST)) : "");
	$fh2 = @fopen(MYUPLOAD_LST, 'w+');
	$dash = "";	$ul_log ="";
	for($i=0;$i<=5;$i++) $dash.="====";
	foreach($uploads as $file => $hosts){
	 //$ul_log = "[".$hosts["date"]."] -- " . basename($file) . $ul_log;
	 foreach($hosts as $vk => $val){
	  if($vk !="date"){
		$ul_log = "[".$hosts["date"]."] -- " . basename($file) . "\r\n" . $val . "\r\n".$dash."\r\n" . $ul_log;
	  }
	 }
	}
	if($fh2){
	 $towrite = $ul_log . $fcontent;
	 fwrite($fh2, $towrite); fclose($fh); fclose($fh2);
	}
	//////////////////////////////

//fill table element residue from the plugin uploader
?>

	<textarea cols="80" rows="16" onclick="this.focus();this.select();">
<?php
	// Here we print the download links
	$output = "";
	foreach ($hostss as $host=>$value) {
		foreach ($uploads as $file=>$hosts) {
			$output .= $hosts[$value]."\r\n";
		}
		$output .= "\r\n\r\nOR\r\n\r\n";
	}
	// remove the last OR
	$output = substr($output,0,-6);
	echo $output;
?></textarea><br />
<button name="backauul" onclick="location.href='<?php echo $PHP_SELF;?>'">Back to Main</button><br>
<a onclick="openmyList('<?php echo MYUPLOAD_LST . "?" . rand();?>'); return false;" style="color: #99C9E6" href="<?php echo MYUPLOAD_LST . "?" . rand();?>" target="_blank"><b>myuploads</b></a>

<br/><br/>
<?php
	} else {
_create_list();
unset($Path);

if (!$list) {
?>
	<center>No files found</center>
<a href="javascript:showAll();"><?php echo "Show&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1)

  {
  document.write("<?php echo "Downloaded";?>");
  }
else
  {
  document.write("<?php echo "Everything";?>");
  }
</script></a> |
<a onclick="openmyList('<?php echo MYUPLOAD_LST . "?" . rand();?>'); return false;" style="color: #99C9E6" href="<?php echo MYUPLOAD_LST . "?" . rand();?>" target="_blank"><b>myuploads</b></a>
<?php

//end- No File Found
} else {
?>
<form name="flist" method="post" action="auul.php?action=upload">
<table width="100%">
<tr>
<td width="70%" valign="top">
<center>
<div class="navi">
<a href="javascript:setCheckboxes(1);" style="color: #99C9E6;">Check All</a> |
<a href="javascript:setCheckboxes(0);" style="color: #99C9E6;">Un-Check All</a> |
<a href="javascript:setCheckboxes(2);" style="color: #99C9E6;">Invert Selection</a> |
<a href="javascript:showAll();"><?echo "Show&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1)
  {
  document.write("<?php echo "Downloaded";?>");
  }
else
  {
  document.write("<?php echo "Everything";?>");
  }
</script></a> |
<a onclick="openmyList('<?php echo MYUPLOAD_LST . "?" . rand();?>'); return false;" style="color: #99C9E6" href="<?php echo MYUPLOAD_LST . "?" . rand();?>" target="_blank"><b>myuploads</b></a>

</div>

<div id="divcontainer" >
<table cellpadding="3" cellspacing="1" width="100%" class="filelist">
	<tr bgcolor="#4B433B" valign="bottom" align="center" style="color: white;">
		<th width="30"><a href="javascript:setCheckboxes(1);" style="color: #99C9E6;">All</a></th>
		<th>Name</th>
		<th width="120">Size</th>
	</tr>
 <tbody id="divcontainer" style="height:<?php echo (count($list)<13? "100%" : "280px");?>; padding:5px; white-space:nowrap; overflow:auto;">
<?php
	$brs = 1;
	foreach($list as $key => $file) {
		if(isset($file["name"]) && @file_exists($file["name"])) {
?>	
<tr id='brs<?php echo $brs;?>' class='rowlist' onMouseDown='clk("chkfL-<?php echo $brs;?>")' onMouseOut='if(document.getElementById("chkfL-<?php echo $brs;?>").checked) {this.className="rowlist_checked";}else{this.className="rowlist";}'>
 <td align="center"><input type=checkbox name="files[]" id="chkfL-<?php echo $brs;?>" onclick='clk(this.id)' value="<?php echo basename($file["name"]); ?>"></td>
 <td><?php echo basename($file["name"]); ?></td>
 <td align="center"><?php echo $file["size"]; ?></td>
</tr>
<?php
		$brs++;
		}		
	}
?>
 </tbody>
</table>
</div>
</center>
</td>
<td width="30%">
<center>
<div id="host_list" style="overflow:auto; height:300px; width:100%">
<?php
	$d = opendir(UPL_DIR); $bftxt = '';
	while (false !== ($modules = readdir($d))){
		if($modules!="." && $modules!=".."){
			if(is_file(UPL_DIR.$modules)){
				if (strpos($modules,".index.php")) include_once(UPL_DIR.$modules);
			}
		}
	}
	if (empty($upload_services)){
		$bftxt = "<span style='color:#FF6600'><b>No Supported Upload Services!</b></span>";
	} else {
		sort($upload_services); reset($upload_services);
		$cc=0; $nn="\n";
 $i = 0; 
 foreach($upload_services as $upl){
  $bftxt.= 'dHost['.$i.']=new Object();'.$nn;
  $bftxt.= 'dHost['.$i.']["upl"]="'.$upl.'"; ';
  $bftxt.= 'dHost['.$i.']["size"]=" ('.($max_file_size[$upl]==false ? "~" : $max_file_size[$upl]."Mb").')";'.$nn;
  $i++;
}
?>


<?php
	}
?>

<table>
	<tr>
		<th></th>
		<th>Hosts</th>
	</tr>
<tbody id="trhost">
 <?php
 if (empty($upload_services)){
  echo "<tr><td>".$bftxt."</td></tr>";
 }else{
 ?>
<script type="text/javascript">
var dHost = new Object();
<?php echo $bftxt;?>
tr_tpl = '<tr><td><input type=checkbox name="hosts[]" value="((upl))"></td>';
tr_tpl+= '<td>((name))</td>';
tr_tpl+= '</tr>';
function inserthost(){
  var text = "", d = document;
  for(var idx in dHost) {  
    var phost = dHost[idx]; var _tpl = tr_tpl;
	_tpl = _tpl.replace(/\(\(upl\)\)/g, phost["upl"]);
	_tpl = _tpl.replace(/\(\(name\)\)/g, phost["upl"].replace("_"," ") + phost["size"]);
    text += _tpl;
   }
  d.getElementById('trhost').innerHTML = text;
}
</script>
 <?php
 
 }
 ?>
</tbody>
</table>
 <?php
 if(!empty($upload_services)){
 echo '
 <script type="text/javascript">
  setTimeout("inserthost()", 50);
 </script>';
 }
?>
</div>
</center>
</td>
</tr>
<tr>
<td colspan="2" style="text-align: center;"><input type=submit name="submit" value="Upload" /></td>
</tr>
</table>
</form>
<?php
 } //end- File Found
} //end- Not action upload

?>
<div id="diviframe" style="display:none;">
 <div id="closer"><a href="javascript:void(0)" onclick="closeframe()" title="Close this List"><b>Close</b></a></div>
 <iframe id="myul_iframe" name="myul_iframe" src="about:blank" style="width:98%;">Frames not supported</iframe>
</div><br/><br/>
<script type="text/javascript">dcontainer = d.getElementById('diviframe');ifrm = d.getElementById('myul_iframe');</script>
</center>
</body>
</html>