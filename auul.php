<?php
  /*****************************************************
   * Rapidleech 36B Rv.8
   *
   * $Id: auul.php - Idx$
  *****************************************************/

$id=1;
// A work of Chaza and TheOnly92!
// Presents auto-upload script!
// We define some constants here, essential for some parts in rapidleech
define('RAPIDLEECH', 'yes');

require_once './global.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charSet; ?>" />
<title>AuUL :: <?php echo $RL_VER; ?> ::</title>
<link type="text/css" href="<?php print IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen" />
<link type="image/gif" rel="shortcut icon" href="<?php echo IMAGE_DIR . 'ico_home.gif?' . rand(11, 9999); ?>" />
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>js.php?auul"></script>
<script type="text/javascript">
/* <![CDATA[ */
var dcontainer, ifrm;
function openmyList(src) {
	var hpath = d.location.href;
	hpath = hpath.substring(0, hpath.lastIndexOf("/") + 1);
	ifrm.src = hpath + src + Math.floor(Math.random() * 100);
	dcontainer.style.display = '';
	obj = d.body;
	obj.scrollTop = obj.scrollHeight;
}

function closeframe() {
	ifrm.src = "about:blank";
	dcontainer.style.display = 'none';
}

function cleanResidue() {
	var prblock = d.getElementsByTagName('table');
	var dvnfo = d.getElementsByTagName('div');
	for (var i in prblock) {
		if (prblock[i].id == 'progressblock')
			prblock[i].innerHTML = '';
	}
	for (var i in dvnfo) {
		if (dvnfo[i].id == 'info')
			dvnfo[i].innerHTML = '';
	}
}
/* ]]> */
</script>
</head>
<body>
<div align="center" class="head_container">
<a href="<?php echo $options['index_file']; ?>" class="tdheadolgo" title="Rapidleech"></a>
</div>
<br />
<center>
<?php
//SHOW TIME WORK
if( $options["limit_timework"] ){
  $is_worktime = cek_worktime($options["workstart"], $options["workend"]);
  $limitmsg="";
  if(!$is_worktime){
    if(!empty($limitmsg)){$limitmsg.="<br />";}$limitmsg.=$L->sprintf($L->say['worktime_alert'], $options["workstart"]);
    echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class=\"warn_alert\">{$limitmsg}</div></div>";
	if($options["navi_left"]["server_info"]){
	  if(@file_exists(CLASS_DIR."sinfo.php")) {
		require_once(CLASS_DIR."sinfo.php");
		echo "<div style='padding-left:5px;text-align:center;width:auto;margin-top:-5px;'><small>{$server['property']}". $L->sprintf($L->say["page_load"], $maintimer->stop())."</small></div>";
	  }
	}else echo "<hr />";
	echo "</center></body></html>";
	exit();
  }
}
if ($options['auto_del_time'] > 0) {
	list($ddelay, $autodel_unit_time) = autodel_formatted($options['auto_del_time']);
	echo "<span class=\"c\">" . $L->say['_autodel'] . ":&nbsp;<b class=\"g\">" . $ddelay . "</b>&nbsp;" . $autodel_unit_time . "</span>";
	//auto_del($options['auto_del_time']);
	purge_files($options['auto_del_time']);
}
if ($options['auul'] > 0) {
	echo "&nbsp;||&nbsp;<span class=\"c\">File Allow:&nbsp;<b class=\"g\">" . $options['auul'] . "</b>&nbsp;file" . ($options['auul'] > 1 ? "s" : "") . "</span>";
}
if ($options['limit_timework']) {
	echo "<br /><span class=\"c\">" . $L->say['_timework'] . ":&nbsp;</span><b class=\"s\">" . $options['workstart'] . "</b>&nbsp;" . $L->say['_upto'] . "&nbsp;<b class=\"s\">" . $options['workend'] . "</b>";
}
?>
<?php
// If the user submit to upload, go into upload page
if (isset($_GET['action']) && $_GET['action'] == 'upload') {
	// Initialize some variables here
	$uploads = array();
	$total = 0;
	$hostss = array();
	// Sort the upload hosts and files
	foreach ($_POST['files'] as $file) {
		if ($options['auul'] > 0) {  // if there's a limitation in auul file submission
			if (count($_POST['files']) > $options['auul']) {
				echo '<script type="text/javascript">function dopostback(){d.backpost.submit();}</script><form action="' . basename($PHP_SELF) . '" name="backpost" id="backpost" method="post"><div style="display:none;"></div></form>';
				die('<span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>' . $L->sprintf($L->say['reach_lim_auul'], $options['auul']) . '</b></span><br/><br/><a href="javascript:;" onclick="dopostback()"><b>[ ' . $L->say['back_main'] . ' ]</b></a>' . "\r\n" . '</body></html>');
			}
		}
		foreach ($_POST['hosts'] as $host) {
			$hostss[] = $host;
			$uploads[DOWNLOAD_DIR . $file][$host] = "";
			$total++;
		}
	}
	// Clear out duplicate hosts
	$hostss = array_unique($hostss);
	// If there aren't anything
	if (count($uploads) == 0) {
		echo $L->say['no_file_host'];
		exit;
	}
	$did = 1;
	// Start uploading!
	foreach ($uploads as $file => $hosts) {
		// If file does not exist
		if (!file_exists($file)) {
			html_error($L->sprintf($L->say['file_not_exists'], $file));
		}
		// If file is not readable
		if (is_readable($file)) {
			$lfile = $file;
			$lname = basename($lfile);
		} else {
			html_error($L->sprintf($L->say['error_read_file'], $file));
		}
		// Get the size of the file
		$fsize = getSize($lfile);
		// Start uploading this file
		echo "Uploading <b>" . basename($file) . "</b>&nbsp[<b class='g'>" . bytesToKbOrMbOrGb($fsize) . "</b>]<br />";
		// Upload to different hosts


		foreach ($hosts as $host => $value) {
			echo "Transload destination: <b style='color:#FFFF00'>" . $host . "</b><br/>";
?>
<script type="text/javascript">
var orlink='<?php echo basename($file); ?> to <?php echo $host; ?>';
</script>
<?php
			//ob_start("callback");
			// If there is a plugin to the host
			if (file_exists(UPLOAD_DIR . $host . ".php")) {
				// Read the configuration file for the host
				include_once(UPLOAD_DIR . $host . ".index.php");
				// If the size of the file is bigger than the max for the host
				if ($max_file_size["$host"] != false) if ($fsize > $max_file_size["$host"] * 1024 * 1024) html_error("Bigger than file-size");

				// Upload it!
				include(UPLOAD_DIR . $page_upload["$host"]);
			}else html_error('This service not allowed || Plugin not exist: ' . UPLOAD_DIR . trim($host) . ".php");
			// Save the download link
			$uploads[$file][$host] = $download_link;
			$uploads[$file]['date'] = date("Y-m-d H:i:s");
			echo "<script type=\"text/javascript\">cleanResidue();</script>\r\n</td></tr></table>\r\n";
			//ob_get_contents();
			//@ob_end_flush();
			//@ob_get_clean();
		}
	}

	//////////////////////////////New code added here///////
	// Write links to a file
	if (!file_exists(MYUPLOAD_LST)) @touch(MYUPLOAD_LST);
	$fh = @fopen(MYUPLOAD_LST, 'r');
	$fcontent = ($fh && @filesize(MYUPLOAD_LST) > 0 ? @fread($fh, @filesize(MYUPLOAD_LST)) : "");
	$fh2 = @fopen(MYUPLOAD_LST, 'w+');
	$dash = ""; $ul_log = "";
	for ($i = 0; $i <= 5; $i++) $dash.="====";
	foreach ($uploads as $file => $hosts) {
		//$ul_log = "[".$hosts["date"]."] -- " . basename($file) . $ul_log;
		foreach ($hosts as $vk => $val) {
			if ($vk != "date") {
				$ul_log = "[" . $hosts["date"] . "] -- " . basename($file) . "\r\n" . $val . "\r\n" . $dash . "\r\n" . $ul_log;
			}
		}
	}
	if ($fh2) {
		$towrite = $ul_log . $fcontent;
		fwrite($fh2, $towrite);
		fclose($fh);
		fclose($fh2);
	}
	//////////////////////////////
	//fill table element residue from the plugin uploader
?>

	<textarea cols="80" rows="16" onclick="this.focus();this.select();">
<?php
	// Here we print the download links
	$output = "";
	foreach ($hostss as $host => $value) {
		foreach ($uploads as $file => $hosts) {
			$output .= $hosts[$value] . "\r\n";
		}
		$output .= "\r\n\r\nOR\r\n\r\n";
	}
	// remove the last OR
	$output = substr($output, 0, -6);
	echo $output;
?></textarea><br />
<button name="backauul" onclick="location.href='<?php echo $PHP_SELF;?>'"><?php echo $L->say['back_main'];?></button><br />
<a onclick="openmyList('<?php echo MYUPLOAD_LST . "?" . rand();?>'); return false;" style="color: #99C9E6" href="<?php echo MYUPLOAD_LST . "?" . rand();?>" target="_blank"><b>myuploads</b></a>

<br/><br/>
<?php
} else {
	_create_list();
	unset($Path);

	if (!$list) {
?>
<center>No files found</center>
<a href="javascript:showAll();"><?php echo $L->say['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1) {
  d.write("<?php echo $L->say['_downloaded'];?>");
}else {
  d.write("<?php echo $L->say['_everything'];?>");
}
</script></a>&nbsp;|&nbsp;
<a onclick="openmyList('<?php echo MYUPLOAD_LST . "?" . rand();?>'); return false;" style="color: #99C9E6" href="<?php echo MYUPLOAD_LST . "?" . rand();?>" target="_blank"><b>myuploads</b></a>
<?php
//end- No File Found
	} else {
?>
<form name="flist" method="post" action="<?php echo basename($PHP_SELF);?>?action=upload">
<table width="100%">
<tr>
<td width="70%" valign="top">
<center>
<div class="navi">
<a href="javascript:setCheckboxes(1);" style="color: #99C9E6;">Check All</a> |
<a href="javascript:setCheckboxes(0);" style="color: #99C9E6;">Un-Check All</a> |
<a href="javascript:setCheckboxes(2);" style="color: #99C9E6;">Invert Selection</a> |
<a href="javascript:showAll();"><?php echo $L->say['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1) {
  d.write("<?php echo $L->say['_downloaded'];?>");
}else {
  d.write("<?php echo $L->say['_everything'];?>");
}
</script></a>&nbsp;|&nbsp;
<a onclick="openmyList('<?php echo MYUPLOAD_LST . "?" . rand();?>'); return false;" style="color: #99C9E6" href="<?php echo MYUPLOAD_LST . "?" . rand();?>" target="_blank"><b>myuploads</b></a>

</div>

<div id="divcontainer" >
<table cellpadding="3" cellspacing="1" width="100%" class="filelist">
	<tr bgcolor="#4B433B" valign="bottom" align="center" style="color: white;">
		<th width="30"><a href="javascript:setCheckboxes(1);" style="color: #99C9E6;">All</a></th>
		<th>Name</th>
		<th width="120">Size</th>
	</tr>
 <tbody id="tcontainer" style="height:<?php echo (count($list)<13? "100%" : "280px");?>; padding:5px; white-space:nowrap; overflow:auto;">
<?php
		$brs = 1;
		foreach ($list as $key => $file) {
			if (isset($file["name"]) && @file_exists($file["name"])) {
?>
<tr id='brs<?php echo $brs;?>' class='rowlist' onmousedown='clk("chkfL-<?php echo $brs;?>")' onmouseout='if(document.getElementById("chkfL-<?php echo $brs;?>").checked) {this.className="rowlist_checked";}else{this.className="rowlist";}'>
 <td align="center"><input type="checkbox" name="files[]" id="chkfL-<?php echo $brs;?>" onclick="clk(this.id)" value="<?php echo basename($file["name"]); ?>" /></td>
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
		$d = opendir(UPLOAD_DIR);
		$bftxt = '';
		while (false !== ($modules = readdir($d))) {
			if ($modules != "." && $modules != "..") {
				if (is_file(UPLOAD_DIR . $modules)) {
					if (strpos($modules, ".index.php")) include_once(UPLOAD_DIR . $modules);
				}
			}
		}
		if (empty($upload_services)) {
			$bftxt = "<span style='color:#FF6600'><b>$L->say['no_support_upl_serv']</b></span>";
		} else {
			sort($upload_services);
			reset($upload_services);
			$cc = 0; $nn = "\n";
			$i = 0;
			foreach ($upload_services as $upl) {
				$bftxt.= 'dHost[' . $i . ']=new Object();' . $nn;
				$bftxt.= 'dHost[' . $i . ']["upl"]="' . $upl . '"; ';
				$bftxt.= 'dHost[' . $i . ']["size"]=" (' . ($max_file_size[$upl] == false ? "~" : $max_file_size[$upl] . "Mb") . ')";' . $nn;
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
		if (empty($upload_services)) {
			echo "<tr><td>" . $bftxt . "</td></tr>";
		} else {
 ?>
<script type="text/javascript">
/* <![CDATA[ */
var dHost = new Object();
<?php echo $bftxt;?>
tr_tpl = '<tr><td><input type="checkbox" name="hosts[]" value="((upl))" /></td>';
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
/* ]]> */
</script>
 <?php
		}
 ?>
</tbody>
</table>
 <?php
		if (!empty($upload_services)) {
			echo '<script type="text/javascript">setTimeout("inserthost()", 50);</script>';
		}
?>
</div>
</center>
</td>
</tr>
<tr>
<td colspan="2" style="text-align: center;"><input type="submit" name="submit" value="Upload" /></td>
</tr>
</table>
</form>
<?php
	} //end- File Found
} //end- Not action upload
?>
<div id="diviframe" style="display:none;">
 <div id="closer"><a href="javascript:;" onclick="closeframe()" title="Close this List"><b>Close</b></a></div>
 <iframe id="myul_iframe" name="myul_iframe" src="about:blank" style="width:98%;">Frames not supported</iframe>
</div><br/><br/>
<script type="text/javascript">dcontainer = d.getElementById('diviframe');ifrm = d.getElementById('myul_iframe');</script>
</center>
</body>
</html>