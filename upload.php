<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: upload.php - Idx$
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";

if (!$_REQUEST["uploaded"]) {
	html_error("Not selected upload services", 0);
}

//if (!$_REQUEST["filename"]) {
if (!$_REQUEST["filedate"]) {
	html_error("Not select file to upload", 0);
}

//$_REQUEST["filename"]=base64_decode($_REQUEST["filename"]);
/* Fixed - Bkis */
//$_REQUEST["filename"]=htmlentities(DOWNLOAD_DIR.basename($_REQUEST["filename"])); 
$_REQUEST["uploaded"] = htmlentities(str_replace('/', '', $_REQUEST["uploaded"]));
/* Fixed - Bkis */

/* Mod ~Idx */
_create_list();
if ($list) {
	$_REQUEST["filename"] = $list[$_REQUEST["filedate"]]["name"];
} else {
	html_error("ERROR: file not exist", 0);
}

header("Content-type: text/html; charset=$charSet");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charSet; ?>" />
<link type="text/css" href="<?php echo IMAGE_DIR; ?>style_sujancok<?php echo $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen" />
<title>Uplot file <?php echo htmlspecialchars(basename($_REQUEST["filename"])); ?> to <?php echo $_REQUEST["uploaded"]; ?></title>
<script type="text/javascript">
/* <![CDATA[ */
var orlink='<?php echo addslashes(basename($_REQUEST["filename"])); ?> to <?php echo $_REQUEST["uploaded"]; ?>';
function highlight(field) { field.focus(); field.select(); }
/* ]]> */
</script>
</head>
<body>
<center>
<div class="head_container"><center>
<a href="javascript:;" class="tdheadolgo" title="Rapidleech"><span>Rapidleech36B</span></a>
</center></div>

<?php
if (!file_exists($_REQUEST["filename"])) {
	html_error("ERROR: file not exist $filename", 0);
}

if (is_readable($_REQUEST["filename"])) {
	$lfile = $_REQUEST["filename"];
	$lname = basename($lfile);
} else {
	html_error("ERROR: not readable $filename", 0);
}

$fsize = getSize($lfile);

if (file_exists(UPLOAD_DIR . $_REQUEST["uploaded"] . ".php")) {
	echo "<center>Trying Upload to: <b style='color:#FFFF00'>" . $_REQUEST["uploaded"] . "</b></center>";
	include_once(UPLOAD_DIR . $_REQUEST["uploaded"] . ".index.php");
	if ($max_file_size["$_REQUEST[uploaded]"] != false) if ($fsize > $max_file_size["$_REQUEST[uploaded]"] * 1024 * 1024) html_error("Bigger than file-size", 0);
	include_once(UPLOAD_DIR . $page_upload["$_REQUEST[uploaded]"]);
}else {
	html_error('This service not allowed', 0);
}
?>
</td></tr></table>
<?php
if ($download_link || $delete_link || $stat_link || $adm_link) {
	echo "\n<table width=100% border=0>";
	echo ($download_link ? "<tr><td width=100 nowrap align=right><b>Download-Link:</b><td width=\"80%\"><input type='text' value='$download_link' onfocus='highlight(this)'; style=\"width: 470px;\" readonly /></tr>" : "");
	echo ($delete_link ? "<tr><td width=100 nowrap align=right>Delete-Link:<td width=\"80%\"><input type='text' value='$delete_link' onfocus='highlight(this)'; style=\"width: 470px;\" readonly /></tr>" : "");
	echo ($stat_link ? "<tr><td width=100 nowrap align=right>Stat-Link:<td width=\"80%\"><input type='text' value='$stat_link' onfocus='highlight(this)'; style=\"width: 470px;\" readonly /></tr>" : "");
	echo ($adm_link ? "<tr><td width=100 nowrap align=right>Admin-Link:<td width=\"80%\"><input type='text' value='$adm_link' onfocus='highlight(this)'; style=\"width: 470px;\" readonly/ ></tr>" : "");
	echo ($user_id ? "<tr><td width=100 nowrap align=right>USER-ID:<td width=\"80%\"><input type='text' value='$user_id' onfocus='highlight(this)'; style=\"width: 470px; \" readonly></tr>" : "");
	echo ($ftp_uplink ? "<tr><td width=100 nowrap align=right>FTP UPLOAD:<td width=\"80%\"><input type='text' value='$ftp_uplink' onfocus='highlight(this)'; style=\"width: 470px;\" readonly /></tr>" : "");
	echo ($access_pass ? "<tr><td width=100 nowrap align=right>PASSWD:<td width=\"80%\"><input type='text' value='$access_pass' onfocus='highlight(this)'; style=\"width: 470px;\" readonly /></tr>" : "");
	echo "</table>\n";

	//if(!file_exists(trim($lfile).".upload.html"))
	if (!file_exists(trim($lfile) . ".upload.html") && !isset($_GET['auul']) && !$options['upload_html_disable']) {
		$html_header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
					<html xmlns=\"http://www.w3.org/1999/xhtml\">
					<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
					<title>RapidleechPlugMod - UploadLinks</title>
					<style type=\"text/css\">
body {
	font-family: tahoma, arial, \"times New Roman\", georgia, verdana, sans-serif;
	font-size: 11px;
	color: #333333;
	background-color: #EFF0F4;
	margin: 0px;
	padding: 0px;
}
.linktitle {
	width: 576px;
	background-color: #C291F9;
	text-align: center;
	padding:3px;
	margin-top: 25px;
	margin-right: auto;
	margin-bottom: 0;
	margin-left: auto;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 0px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #C7C4FB;
	border-right-color: #C7C4FB;
	border-bottom-color: #C7C4FB;
	border-left-color: #C7C4FB;
}
.bluefont {
	color: #0E078F;
	font-family: tahoma, arial, \"times New Roman\", georgia, verdana, sans-serif;
	font-size: 11px;
}
hr {
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: solid;
	height: 1px;
	background-color: #046FC6;
	color: #046FC6;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	width: 90%;
}
.host .links {
	width: 95%;
	margin:0 auto;
	text-align:left;
	padding:3px 0 3px 10px;
	border: 1px dashed #666666;
	background-color: #F2F1FE;
}
.host {
	width: 600px;
	margin: 10px auto 10px;
}
.host .links a {
	text-decoration:none;
	color: #666666;
	font-size: 11px;
}
.host .links a:hover {
	text-decoration:none;
	color:#E8740B
}
.host .title {
	width: 95%;
	margin:0 auto;
	text-align:left;
	padding:3px 0 3px 10px;
	background-color: #C7C4FB;
	color: #000000;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 0px;
	border-left-width: 1px;
	border-top-style: dashed;
	border-right-style: dashed;
	border-bottom-style: dashed;
	border-left-style: dashed;
	border-top-color: #333333;
	border-right-color: #333333;
	border-bottom-color: #333333;
	border-left-color: #333333;
	font-size: 12px;
	font-family: Georgia, \"Times New Roman\", Times, serif;
}
					</style>
					</head>
					<body>
					";
		$fp = fopen(trim($lfile) . ".upload.html", 'w');
		fwrite($fp, $html_header);
		fwrite($fp, "<div class=\"linktitle\">Upload Links for <strong>" . $lname . "</strong> - <span class=\"bluefont\">Size: <strong>" . bytesToKbOrMb($fsize) . "</strong></span></div>");
		fclose($fp);
	}
	if (!$options['upload_html_disable']) {
		$fr = fopen(trim($lfile) . ".upload.html", 'a+');
		if ($fr) {
			fwrite($fr, "<div class=\"host\"><div class=\"title\"><strong>" . $_REQUEST["uploaded"] . "</strong> - <span class=\"bluefont\">" . date("Y-m-d H:i:s") . "</span></div>");
			fwrite($fr, "<div class=\"links\">");
			if ($download_link) fwrite($fr, "<strong>Download link: <a href=\"" . $download_link . "\" target=\"_blank\">" . $download_link . " </a></strong>");
			if ($delete_link) fwrite($fr, "<br />Delete link: <a href=\"" . $delete_link . "\" target=\"_blank\">" . $delete_link . " </a>");
			if ($stat_link) fwrite($fr, "<br />Stat link: <a href=\"" . $stat_link . "\" target=\"_blank\">" . $stat_link . " </a>");
			if ($adm_link) fwrite($fr, "<br />Admin link: <a href=\"" . $adm_link . "\" target=\"_blank\">" . $adm_link . " </a>");
			if ($user_id) fwrite($fr, "<br />USER ID: <a href=\"" . $user_id . "\" target=\"_blank\">" . $user_id . " </a>");
			if ($access_pass) fwrite($fr, "<br />Password: <a href=\"" . $access_pass . "\" target=\"_blank\">" . $access_pass . " </a>");
			if ($ftp_uplink) fwrite($fr, "<br />FTP upload: <a href=\"" . $ftp_uplink . "\" target=\"_blank\">" . $ftp_uplink . " </a>");
			fwrite($fr, " </div></div>");
			fclose($fr);
		}
	}
}
echo $not_done ? "" : '<br /><center><a href="javascript:;" onclick="window.close();"><b>DONE</b></a></center>';
?>
</center>

<?php
//-- mod: Auto Upload ------------------------------------------
if ($download_link != '') {
	// Write links to a file
	if (!file_exists(MYUPLOAD_LST)) @touch(MYUPLOAD_LST);
	$fh = @fopen(MYUPLOAD_LST, 'r');
	if ($fh) {
		$fcontent = (filesize(MYUPLOAD_LST) > 0 ? @fread($fh, filesize(MYUPLOAD_LST)) : "");
		$fh2 = @fopen(MYUPLOAD_LST, 'w+');
		if ($fh2) {
			$dash = "";
			for ($i = 0; $i <= 80; $i++) $dash.="=";
			$towrite = "[" . date("Y-m-d H:i:s") . "] -- " . $lname . "\r\n" . $dash . "\r\n" . $download_link . "\r\n\r\n" . $fcontent;
			fwrite($fh2, $towrite);
			fclose($fh);
			fclose($fh2);
		}
	}
}
//-- end: Auto Upload ------------------------------------------
if (isset($_GET['auul'])) echo '<script type="text/javascript">parent.nextlink' . $_GET['auul'] . '();</script>';
?>	
</body>
</html>