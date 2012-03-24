<?php
/*
This value stored in config.php already
//If you need to disable multiple ip support, set to false
$options['mip_enabled'] = true;
//Auto change to next ip after start transload process
$options['mip_arotate'] = true;

-- end config

Some hosts need to be changed to be compatible with this, they are in the same archive as this file.

INSTRUCTIONS:
	Copy this file to the rapidleech root
	Create the file /logfile/mip_iplist.txt
	Add 1 ip per line on /logfile/mip_iplist.txt
	Check php has read access to /logfile/mip_iplist.txt
	Apply the modifications
*/

/* Required modification 1: (/templates/plugmod/main.php)

ADD
<?php $mip_action = "select"; @include('mip.php'); ?>

AFTER:
<input type="submit" value="<?php echo lang(209); ?>">
OR
<input type="submit" value="Transload File">


*/

/* Required modification 2: (/classes/http.php)
REPLACE
$fp = @fsockopen($proxyHost ? $scheme.$proxyHost : $scheme.$host, $proxyPort ? $proxyPort : $port, $errno, $errstr, 15);
OR
$fp = @stream_socket_client($host,$errno,$errstr);
OR
$fp = @stream_socket_client ( $hosts, $errno, $errstr, 120, STREAM_CLIENT_CONNECT );

TO:
$mip_action = "download"; @include('mip.php');
*/

/* Optional modification: (/classes/http.php)

IF OLDER RAPIDLEECH:
REPLACE
echo "<p>Connected to proxy: <b>".$proxyHost."</b> at port <b>".$proxyPort."</b>...<br>\n";
TO:
echo "<p>Connected to proxy: <b>".$proxyHost."</b> at port <b>".$proxyPort."</b>...<br>\n";
echo "using IP: ".$mip_ip."<br>\n";

REPLACE
echo "<p>Connected to: <b>".$host."</b> at port <b>".$port."</b>...<br>";
TO:
echo "<p>Connected to: <b>".$host."</b> at port <b>".$port."</b>...<br>";
echo "using IP: ".$mip_ip."<br>\n";

IF NEWER RAPIDLEECH:

REPLACE:
echo '<p>'.sprintf(lang(89),$proxyHost,$proxyPort).'<br />';
TO:
echo '<p>'.sprintf(lang(89),$proxyHost,$proxyPort).'<br />';
echo "using IP: ".$mip_ip."<br>\n";

REPLACE
printf(lang(90),$host,$port);
TO:
printf(lang(90),$host,$port);
echo "<br>using IP: ".$mip_ip."<br>\n";
*/

/*
END OF INSTRUCTIONS
*/

if (!defined('RAPIDLEECH')) {
	//require ('deny.php');
	exit();
}

if (!$options['mip_enabled']) {
	$mip_ip = "Default IP";
	$fp = @fsockopen($proxyHost ? $scheme.$proxyHost : $scheme.$host, $proxyPort ? $proxyPort : $port, $errno, $errstr, 60);
}
elseif (!empty($GLOBALS['page_upload'])) {
	$mip_ip = "Default IP (Uploading)";
	$fp = @fsockopen($proxyHost ? $scheme.$proxyHost : $scheme.$host, $proxyPort ? $proxyPort : $port, $errno, $errstr, 60);
}
elseif (!defined('LOG_DIR')) {
	$mip_ip = "Default IP (LOG_DIR constant not defined)";
	$fp = @fsockopen($proxyHost ? $scheme.$proxyHost : $scheme.$host, $proxyPort ? $proxyPort : $port, $errno, $errstr, 60);
}
elseif (!function_exists('stream_socket_client')) {
		$mip_ip = "Default IP (PHP4)";
		$fp = @fsockopen($proxyHost ? $scheme.$proxyHost : $scheme.$host, $proxyPort ? $proxyPort : $port, $errno, $errstr, 60);
}
else {
	$mip_error['begin'] = '<p class="r style1">Multiple IP problem: ';
	$mip_error['end'] = '</p>';
	$mip_error[0] = $mip_error['begin']."Can not read 'logfile/mip_iplist.txt'".$mip_error['end'];
	$mip_error[1] = $mip_error['begin']."Can not read 'logfile/mip_as.txt'".$mip_error['end'];
	$mip_error[2] = $mip_error['begin']."Can not write 'logfile/mip_as.txt'".$mip_error['end'];
	$mip_error[3] = $mip_error['begin']."Can not write 'logfile/mip_iplast.txt'".$mip_error['end'];
	if (!isset($mip_iplist)) {
		$mip_iplist = @file(LOG_DIR."mip_iplist.txt") or die($mip_error[0]);
		foreach ($mip_iplist as &$mip_tmp1) { $mip_tmp1 = trim($mip_tmp1); }
	}
	if (!file_exists(LOG_DIR."mip_as.txt") || abs(time() - filemtime(LOG_DIR."mip_as.txt")) > 3600) {
		@file_put_contents(LOG_DIR."mip_as.txt", "//Multi IP assignation file\r\n", LOCK_EX) or die($mip_error[2]);
	}
	if (!is_writable(LOG_DIR."mip_as.txt")) { die($mip_error[2]); }

	$mip_fileas = @file(LOG_DIR."mip_as.txt") or die($mip_error[1]);
	foreach ($mip_fileas as &$mip_tmp1) {
		if (strpos($mip_tmp1, "<del>") === false) { unset($mip_tmp1); }
		$mip_tmp1 = explode("<del>", trim($mip_tmp1));
	}
	array_merge($mip_fileas);
	if ($mip_action == "select") {
		$mip_ip = null;
		if ($options['mip_arotate']) {
			$mip_ip = @file_get_contents(LOG_DIR."mip_iplast.txt") or -1;
			$mip_ip = intval($mip_ip) + 1;
			$mip_ip = (!isset($mip_iplist[$mip_ip]) || trim($mip_iplist[$mip_ip]) == null ? 0 : $mip_ip);
			$mip_ip = $mip_iplist[$mip_ip];
		}
?>
<div style="height:5px;"></div>
<select name="source_ip" title="Source IP">
<option value="">Source IP</option>
<?php
		foreach ($mip_iplist as $mip_k => $mip_v) {
			if (trim($mip_v) == null) { continue; }
			echo '<option value="'.$mip_v.'"'.($mip_v == $mip_ip ? ' selected' : '').'>'.$mip_v.'</option>';
		}
?>
</select>
<?php
	}
	elseif ($mip_action == "download") {
//if (!isset($_GET['image'])) {	echo "<br><br><br><br>"; var_export($_GET); echo "<br><br>"; var_export($_POST); echo "<br><br><br><br>"; }
		$mip_found = false;
		$mip_rlink = trim(urldecode(isset($_POST['link']) ? $_POST['link'] : $_GET['link']));
		$mip_url = $url;

//netload.in Fix 2
		if (stripos(strtolower(urldecode($_POST['referer'])), "netload.in/index.php") !== false) {
			foreach ($mip_fileas as $mip_file) {
				if (stripos($mip_file[2], "/".urldecode($_POST['filename'])) !== false) {
					$mip_rlink = $mip_file[0]; break;						
				}
			}
		}
//
//netload.in Fix 1
		elseif (stripos($mip_rlink, "netload.in/") !== false || stripos($host, "netload.in") !== false) {
			if (isset($_GET['source_ip'])) {
				$mip_tmp1 = stripos($mip_rlink, "datei") + 5;
				$mip_tmp2 = stripos($mip_rlink, ".", $mip_tmp1);
				$mip_rlink = "netload.in ".trim(substr($mip_rlink, $mip_tmp1, $mip_tmp2 - $mip_tmp1));
				foreach ($mip_fileas as &$mip_file) {
					if ($mip_file[0] == $mip_rlink) {
						$mip_ip = trim($_GET['source_ip']);
						break;
					}
				}
			}
			elseif (array_key_exists('ofilename', $_POST)) {
				$mip_rlink = "netload.in ".trim($_POST['file_id']);
				foreach ($mip_fileas as &$mip_file) {
					if ($mip_file[0] == $mip_rlink) {
						$mip_url = '/'.trim($_POST['ofilename']);
						$mip_ip = $mip_file[1];
						@file_put_contents(LOG_DIR."mip_as.txt", $mip_rlink."<del>".trim($mip_ip)."<del>url:".$mip_url."<del>".time()."<del>\r\n", FILE_APPEND | LOCK_EX) or die($mip_error[2]);
						break;
					}
				}
			}
			elseif (stripos(@$_GET['referer'], 'file_id=') !== false) {
				$mip_tmp1 = stripos($_GET['referer'], "file_id=") + 8;
				$mip_tmp2 = stripos($_GET['referer'], "&", $mip_tmp1);
				$mip_rlink = "netload.in ".trim(substr($_GET['referer'], $mip_tmp1, $mip_tmp2 - $mip_tmp1));
			}
		}
//
//general Fix
		elseif (array_key_exists('mu_cookie', $_POST)) { $mip_rlink = $mip_rlink; }
		else { $mip_rlink = trim(urldecode(isset($_POST['referer']) ? $_POST['referer'] : $_GET['referer'])); }
//
		if (isset($_GET['source_ip']) && in_array($_GET['source_ip'], $mip_iplist)) {
			$mip_ip = $_GET['source_ip'];
			@file_put_contents(LOG_DIR."mip_as.txt", $mip_rlink."<del>".trim($mip_ip)."<del>url:".$mip_url."<del>".time()."<del>\r\n", FILE_APPEND | LOCK_EX) or die($mip_error[2]);
			@file_put_contents(LOG_DIR."mip_iplast.txt", array_search($mip_ip, $mip_iplist), LOCK_EX) or die($mip_error[3]);
		}
		else {
			foreach ($mip_fileas as $mip_file) {
				if (($mip_file[0] == $mip_rlink)) {
					$mip_ip = $mip_file[1];
					if (!in_array($mip_ip, $mip_iplist)) { $mip_ip = $mip_iplist[0]; }
					$mip_found = true;
					break;
				}
			}
			if ($mip_found == false) {
				$mip_ip = @file_get_contents(LOG_DIR."mip_iplast.txt") or -1;
				$mip_ip = intval($mip_ip) + 1;
				$mip_ip = (!isset($mip_iplist[$mip_ip]) || trim($mip_iplist[$mip_ip]) == null ? 0 : $mip_ip);
				@file_put_contents(LOG_DIR."mip_iplast.txt", $mip_ip, LOCK_EX) or die($mip_error[3]);
				$mip_ip = $mip_iplist[$mip_ip];
				@file_put_contents(LOG_DIR."mip_as.txt", $mip_rlink."<del>".trim($mip_ip)."<del>url:".$mip_url."<del>".time()."<del>\r\n", FILE_APPEND | LOCK_EX) or die($mip_error[2]);
			}
		}
		$mip_opts = array('socket' => array('bindto' => $mip_ip.':0'));
		$mip_context = stream_context_create($mip_opts);
		$fp = stream_socket_client("tcp://".($proxyHost ? $scheme.$proxyHost : $scheme.$host).":".($proxyPort ? $proxyPort : $port), $errno, $errstr, 60, STREAM_CLIENT_CONNECT, $mip_context);
	}
}
$mip_action = "";
?>