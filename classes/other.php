<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}

/* Index Function */ {
// THIS FILE CONTAINS GENERAL FUNCTION NEEDED
// METHODS IN THIS FILE:
//   login_check()
//   is_present()
//   is_notpresent()
//   insert_location()
//   pause_download()
//   cut_str()
//   write_file()
//   read_file()
//   pre()
//   getmicrotime()
//   html_error()
//   sec1time()
//   sec2time()
//   updateListInFile()
//   _cmp_list_enums()
//   file_data_size_time()
//   _create_list()
//   checkmail()
//   fixfilename()
//   getfilesize()
//   bytesToKbOrMb()
//   bytesToKbOrMbOrGb()
//   defport()
//   getSize()
//   purge_files()
//   file_put_contents()
//   file_get_contents()
//   http_chunked_decode()
//   is_hex()
//   is__writable()
//   link_for_file()
//   textarea()
//   jstime()
//   check_referer()
//   rebuild_url()
//   getNowzone()
//   checkExistence()
//   calcUsedSpace()
//   do_strtolower()
//   get_extension()
//   get_tpl()
//   urlcleaner()
//   trimlink()
//   xmlentities()
//   strip_quotes()
//   buildRetryform()
//   chk_this_ip()
//   dcd()
//   mcd()
//   encEnti()
//   decEnti()
//   rotN()
//   encrypt()
//   decrypt()
//   autoNext()
//   getServerPlatf()
//   color_cpu()
//   count_age()
//   cek_worktime()
//   relink_path()
//   utf8_strrev()
//   checkStrict()
//   renderAction()
//   vidlist()
//   autodel_formatted()
//   ________()
}

function login_check() {
	global $options;
	if ($options['login']) {

		function logged_user($ul) {
			foreach ($ul as $user => $pass) {
				if ($_SERVER['PHP_AUTH_USER'] == $user && $_SERVER['PHP_AUTH_PW'] == $pass) return true;
			}
			return false;
		}

		if ($options['login_cgi']) {
			list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = @explode(':', base64_decode(substr((isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : $_SERVER['REDIRECT_HTTP_AUTHORIZATION']), 6)), 2);
		}
		if (empty($_SERVER['PHP_AUTH_USER']) || !logged_user($options['users'])) {
			header('WWW-Authenticate: Basic realm="Rx08"');
			header("HTTP/1.0 401 Unauthorized");
			get_tpl("404", "NuLL");
			exit;
		}
	}
}

function is_present($lpage, $mystr, $strerror = '', $head = 0) {
	if (stripos($lpage, $mystr) !== false) html_error((!empty($strerror) ? $strerror : $mystr), $head);
}

function is_notpresent($lpage, $mystr, $strerror, $head = 0) {
	if (stripos($lpage, $mystr) === false) html_error($strerror, $head);
}

function insert_location($inputs, $action = 0) {
	if (!is_array($inputs)) {
		if (strpos($inputs, '?') !== false) list($action, $inputs) = explode('?', $inputs, 2);
		$query = explode('&', $inputs);
		$inputs = array();
		foreach ($query as $q) {
			list($name, $value) = explode('=', $q, 2);
			if (empty($name) || empty($value)) continue;
			$inputs[$name] = $value;
		}
		unset($query);
	}
	if (isset($_GET['GO']) && $_GET['GO'] == 'GO') $_GET = array_merge($_GET, $inputs);
	else {
		if ($action === 0) $action = $_SERVER['SCRIPT_NAME'];
		$fname = 'r' . TIME_NOW . 'l';
		echo "\n<form name='$fname' " . (!empty($action) ? "action='$action' " : '') . "method='POST'>\n";
		foreach ($inputs as $name => $value) echo "\t<input type='hidden' name='$name' value='$value' />\n";
		echo "</form>\n<script type='text/javascript'>void(document.$fname.submit());</script>\n</body>\n</html>";
		flush();
	}
}

function pause_download() {
	global $pathWithName, $PHP_SELF, $_GET, $nn, $bytesReceived, $fs, $fp;
	$status = connection_status();
	if (($status == 2 || $status == 3) && $pathWithName && $bytesReceived > - 1) {
		flock($fs, LOCK_UN);
		fclose($fs);
		fclose($fp);
	}
}

// tweaked cut_str with pluresearch functionality
function cut_str($str, $left, $right, $cont=1) {
	for ($iii = 1; $iii <= $cont; $iii++) {
		$str = substr(stristr($str, $left), strlen($left));
	}
	$leftLen = strlen(stristr($str, $right));
	$leftLen = $leftLen ? - ($leftLen) : strlen($str);
	$str = substr($str, 0, $leftLen);
	return $str;
}

function write_file($file_name, $data, $trunk = 1) {
	if ($trunk == 1) $mode = 'wb';
	elseif ($trunk == 0) $mode = 'ab';
	$fp = fopen($file_name, $mode);
	if (!$fp || !flock($fp, LOCK_EX) || !fwrite($fp, $data) || !flock($fp, LOCK_UN) || !fclose($fp)) return FALSE;
	return TRUE;
}

function read_file($file_name, $count = -1) {
	if ($count == -1) $count = filesize($file_name);
	$fp = fopen($file_name, 'rb');
	flock($fp, LOCK_SH);
	$ret = fread($fp, $count);
	flock($fp, LOCK_UN);
	fclose($fp);
	return $ret;
}

function pre($var, $stop = false) {
	if (!is_array($var) && strip_tags($var) != $var) {
		echo "<pre>"; var_dump(htmlentities($var)); echo "</pre>";
	} else {
		echo "<pre>"; var_dump($var); echo "</pre>";
	}
	if ($stop) exit;
}

function getmicrotime() {
	list ( $usec, $sec ) = explode(" ", microtime());
	return ((float) $usec + (float) $sec);
}

function html_error($msg, $newwin = 1, $retry = 0) {
	global $PHP_SELF, $options, $L, $RL_VER, $onGoing, $nn;

	if (strtolower(basename($PHP_SELF)) == 'audl.php' && isset($_REQUEST['GO']) && $_REQUEST['GO'] == 'GO' && $_REQUEST['server_side'] == 'on' && !empty($GLOBALS['isHost'])) throw new Exception($msg); // Audl-Server Side, called from a plugin.
	else {
		//if ($head == 1)
		if (!headers_sent()) {
			$litehead = 1;
			$page_title = 'Upps...';
			include(TEMPLATE_DIR . 'header.php');
		}

		echo ('<div align="center">');
		echo ('<span class="htmlerror"><b>' . $msg . '</b></span><br /><br />');

		if ($retry == 1) {
			echo buildRetryform($msg);
		} elseif (!empty($options['new_window']) && $newwin) {
			echo '<a href="javascript:window.close();">' . $L->say['closewin'] . '</a>';
		} else {
			echo ('<a id="tdone" href="' . $PHP_SELF . '"><b>' . $L->say['back_main'] . '</b></a>');
		}
		if ($audlparam != '') {
			$audlparam = explode('|', $audlparam);
			if ($audlparam[0] != '') {
				$audlparam[0] = true;
			}
			if ($audlparam[1] == '') {
				$audlparam[1] = 'none';
			}
			echo autoNext($audlparam[0], false, $audlparam[1]);
		}
		flush();
		echo ('</div>');
		echo ($nn . '</body></html>');
		exit();
	}
}

function sec1time($time) {
	global $L;
	$hour = round($time / 3600, 2);
	if ($hour >= 1) {
		$hour = floor($hour);
		$time -= $hour * 3600;
	}
	$min = round($time / 60, 2);
	if ($min >= 1) {
		$min = floor($min);
		$time -= $min * 60;
	}
	$sec = $time;
	$hour = ($hour > 1) ? $hour . " " . $L->say['hours'] . " " : ($hour == 1) ? $hour . " " . $L->say['hours'] . " " : "";
	$min = ($min > 1) ? $min . " " . $L->say['minutes'] . " " : ($min == 1) ? $min . " " . $L->say['minutes'] . " " : "";
	$sec = ($sec > 1) ? $sec . " " . $L->say['seconds'] . "" : ($sec == 1) ? $sec . " " . $L->say['seconds'] . "" : "";
	return $hour . $min . $sec;
}

function sec2time($time) {
	global $L;
	$day = round($time / (3600 * 24), 2);
	if ($day >= 1) {
		$day = floor($day);
		$time -= $day * 3600 * 24;
	}
	$hour = round($time / 3600, 2);
	if ($hour >= 1) {
		$hour = floor($hour);
		$time -= $hour * 3600;
	}
	$min = round($time / 60, 2);
	if ($min >= 1) {
		$min = floor($min);
		$time -= $min * 60;
	}
	$sec = $time;
	$day = ( ($day > 1) ? $day . " " . $L->say['days'] . " " : (($day == 1) ? $day . " " . $L->say['days'] . " " : "") );
	$hour = ($hour > 1) ? $hour . " " . $L->say['hours'] . " " : ($hour == 1) ? $hour . " " . $L->say['hours'] . " " : "";
	$min = ($min > 1) ? $min . " " . $L->say['minutes'] . " " : ($min == 1) ? $min . " " . $L->say['minutes'] . " " : "";
	$sec = ($sec > 1) ? $sec . " " . $L->say['seconds'] : ($sec == 1 || $sec == 0) ? $sec . " " . $L->say['seconds'] : "";
	return $day . $hour . $min . $sec;
}

function updateListInFile($list) {
	if (is_array($list) && count($list) > 0) {
		foreach ($list as $key => $value) $list [$key] = serialize($value);
		//echo file_put_contents ( FILES_LST, implode ( "\r\n", $list ) . "\r\n" );
		if (!file_put_contents(FILES_LST, implode("\r\n", $list) . "\r\n") && count($list) > 0) return FALSE;
		else return TRUE;
	} elseif (@file_exists(FILES_LST)) {
		// Truncate files.lst instead of removing it since we don't have full
		// read/write permission on the configs folder
		$fh = fopen(FILES_LST, 'w');
		fclose($fh);
		return true;
	}
}

function _cmp_list_enums($a, $b) {
	//return strcmp ( $a ["name"], $b ["name"] );
	if (isset($a["date"]) && isset($b["date"])) return strcmp($a["date"], $b["date"]);
	else return strcmp($a["name"], $b["name"]);
}

function file_data_size_time($file) {
	global $options;
	$size = $time = false;
	if (is_file($file)) {
		$size = @filesize($file);
		$time = @filemtime($file);
	}
	if ($size === false && $options['2gb_fix'] && file_exists($file) && !is_dir($file) && !is_link($file)) {
		if (substr(PHP_OS, 0, 3) !== "WIN") {
			@exec('stat' . (stripos(@php_uname('s'), 'bsd') !== false ? '-f %m ' : ' -c %Y ') . escapeshellarg($file), $time, $tmp);
			if ($tmp == 0) $time = trim(implode($time));
			@exec('stat' . (stripos(@php_uname('s'), 'bsd') !== false ? '-f %z ' : ' -c %s ') . escapeshellarg($file), $size, $tmp);
			if ($tmp == 0) $size = trim(implode($size));
		}
	}
	if ($size === false || $time === false) {
		return false;
	}
	return array($size, $time);
}

function _create_list($lynx = false, $medic=false, $d_showall=false) {
	global $list, $_COOKIE, $options;
	$glist = array();
	$showall = ($options['show_all'] && ((isset($_COOKIE['showAll']) && $_COOKIE['showAll']) || $d_showall)) || $medic ? true : false;
//	if (!$d_showall) $d_showall = (isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"] : false);
//	if (($options["show_all"] && ($d_showall == 1)) || $medic) {
	if ($showall) {
		// Show Everything
		$totsize = 0; $cnt = 0; $dir = @dir(DOWNLOAD_DIR);
		while (false !== ($file = $dir->read())) {
			if ($file != "." && $file != ".." && is_array($options["forbidden_filetypes"]) && !in_array("." . get_extension($file), $options["forbidden_filetypes"]) && is_file(DOWNLOAD_DIR . $file) && basename($file) != basename(FILES_LST) && basename($file) != 'index.html' && basename($file) != 'index.php') {
				$file = DOWNLOAD_DIR . $file;
				if (($tmp = file_data_size_time($file)) === false) continue; list($size, $time) = $tmp;
				$time = getNowzone($time);
				while (isset($glist[$time])) $time++;
				$agefile = (TIME_NOW - $time);
				$glist[$time] = array("name" => realpath($file), "size" => bytesToKbOrMbOrGb($size), "date" => $time, "age" => count_age($agefile));
				if ($options["show_column_sfile"]["md5"] && !$lynx) $glist[$time]["md5"] = md5_file($file);
				$totsize+=$size; $cnt++;
			}
		}
		$dir->close();
		@uasort($glist, "_cmp_list_enums");
		if ($cnt > 0) {
			$glist["files"]["totalsize"] = $totsize;
			$glist["files"]["totalfile"] = $cnt;
			$glist["files"]["misc"] = "files";
		}
	} else {  // Show Downloaded
		if (@file_exists(FILES_LST)) {
			$glist = file(FILES_LST);
			$glistReformat = null;
			foreach ($glist as $key => $record) {
				$recfile = @unserialize($record);
				if (is_array($recfile)) {
					foreach ($recfile as $field => $value) {
						if (in_array($field, array("date", "age", "misc"))) {
							$time = @filemtime($recfile["name"]);
							$time = getNowzone($time);
							//$unix_zone = ($time != $value ? $time - date("Z") + (3600 * $options["timezone"]) : $value);
							if ($field == "age") {
								$agefile = (TIME_NOW - $time);
								$listReformat[$key]["age"] = count_age($agefile);
							}
							if ($field == "date") {
								$listReformat[$key][$field] = $time;
								$date = $time;
							}
						}
						if ($field != "age" && $field != "date") $listReformat[$key][$field] = $value;
					}
					$glist[$date] = $listReformat[$key];
					unset($glist[$key], $glistReformat[$key]);
				}
			}
			@uasort($glist, "_cmp_list_enums");
		}
	}
	$list = $glist;
}

function checkmail($mail) {
	if (strlen($mail) == 0 || strpos($mail, '@') === false || strpos($mail, '.') === false || !preg_match('/^[a-z0-9_\.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is', $mail)) return false;
	return true;
}

/* Fixed Shell exploit by: icedog */

function fixfilename($fname, $fpach = '') {
	$f_name = basename($fname);
	$f_dir = dirname(preg_replace('@\.\./@i', '', $fname));
	$f_dir = ($f_dir == '.') ? '' : $f_dir;
	$f_dir = preg_replace('@\.\./@i', '', $f_dir);
	$fpach = preg_replace('@\.\./@i', '', $fpach);
	$f_name = preg_replace('@\.(((s|\d)?php)|(hta)|(p[l|y])|(cgi)|(sph))@i', '.xxx', $f_name);
	$ret = ($fpach) ? $fpach . DIRECTORY_SEPARATOR . $f_name : ($f_dir ? $f_dir . DIRECTORY_SEPARATOR : '') . $f_name;
	return $ret;
}

function getfilesize($f) {
	global $server;
	$stat = stat($f);

	if ($server['is_windows'] || (($stat[11] * $stat[12]) < 4 * 1024 * 1024 * 1024)) return sprintf('%u', $stat[7]);

	global $max_4gb;
	if ($max_4gb === false) {
		$tmp_ = trim(@shell_exec(' ls -Ll ' . @escapeshellarg($f)));
		while (strstr($tmp_, '  ')) $tmp_ = @str_replace('  ', ' ', $tmp_);
		$r = @explode(' ', $tmp_);
		$size_ = $r[4];
	} else $size_ = -1;

	return $size_;
}

function bytesToKbOrMb($bytes) {
	$size = ($bytes >= (1024 * 1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024 * 1024), 2) . " TB" : (($bytes >= (1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024), 2) . " GB" : (($bytes >= (1024 * 1024)) ? round($bytes / (1024 * 1024), 2) . " MB" : round($bytes / 1024, 2) . " KB"));
	return $size;
}

// Updated function to be able to format up to Yotabytes!
function bytesToKbOrMbOrGb($bytes) {
	if (is_numeric($bytes) && $bytes >= 0) {
		$s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$e = floor(log($bytes) / log(1024));
		//return sprintf ( '%.2f ' . $s [$e], @($bytes / pow ( 1024, floor ( $e ) )) );
		return (pow(1024, floor($e)) > 0 ? sprintf('%.2f ' . $s[$e], ($bytes / pow(1024, floor($e)))) : '0 B');
	} else {
		//$size = "Unknown";
		$size = sprintf("%u", filesize($file));
	}
	return $size;
}

function defport($urls) {
	if (!empty($urls['port'])) return $urls['port'];
	switch (strtolower($urls['scheme'])) {
		case 'http' :
			return '80';
		case "https" :
			return '443';
		case "ftp" :
			return '21';
	}
}

function getSize($file) {
	$size = filesize($file);
	if ($size < 0) {
		if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
			$size = @escapeshellarg($file);
			$size = trim(`stat -c%s $file`);
		} else {
			$fsobj = new COM("Scripting.FileSystemObject");
			$f = $fsobj->GetFile($file);
			$size = $file->Size;
		}
	}
	return $size;
}

function purge_files($delay) {
	global $options;
	if (file_exists(FILES_LST) && is_numeric($delay) && $delay > 0) {
		$files_lst = file(FILES_LST);
		$files_new = "";
		$deleted["count"] = 0;
		$deleted["files"] = array();
		foreach ($files_lst as $files_line) {
			$files_data = unserialize(trim($files_line));
			if (isset($files_data["name"]) && @file_exists($files_data["name"]) && is_file($files_data["name"])) {
				if ((TIME_NOW - $files_data["date"]) >= ($delay * 60)) {
					@unlink($files_data["name"]);
					$deleted["count"]++;
					$deleted["files"][] = $options["download_dir"] . basename($files_data["name"]);
				} else {
					$files_new .= $files_line;
				}
			}
		}
		//file_put_contents("purge.log", "\r\n\r\n", FILE_APPEND);
		file_put_contents(FILES_LST, $files_new);
	}
//	purge_files_ip($delay);
	return $deleted;
}

// PHP4 compatibility
if (!function_exists("file_put_contents") && !defined("FILE_APPEND")) {
	define("FILE_APPEND", 1);

	function file_put_contents($n, $d, $flag = false) {
		$mode = ($flag == FILE_APPEND || strtoupper($flag) == "FILE_APPEND") ? "a" : "w";
		$f = @fopen($n, $mode);
		if ($f === false) {
			return 0;
		} else {
			if (is_array($d)) {
				$d = implode($d);
			}
			$bytes_written = fwrite($f, $d);
			fclose($f);
			return $bytes_written;
		}
	}

}

if (!function_exists("file_get_contents")) {

	function file_get_contents($filename, $incpath = false) {
		if (false === $fh = fopen($filename, "rb", $incpath)) {
			trigger_error("file_get_contents() failed to open stream: No such file or directory", E_USER_WARNING);
			return false;
		}
		clearstatcache();
		if (($fsize = @filesize($filename))) {
			$data = fread($fh, $fsize);
		} else {
			$data = "";
			while (!feof($fh)) {
				$data .= fread($fh, 8192);
			}
		}
		fclose($fh);
		return $data;
	}

}

if (!function_exists('http_chunked_decode')) {

	// Added implementation from a comment at php.net's function page
	function http_chunked_decode($chunk) {
		$pos = 0; $len = strlen($chunk);
		$dechunk = null;

		while (($pos < $len) && ($chunkLenHex = substr($chunk, $pos, ($newlineAt = strpos($chunk, "\n", $pos + 1)) - $pos))) {
			if (!is_hex($chunkLenHex)) {
				trigger_error('Value is not properly chunk encoded_', E_USER_WARNING);
				return false;
			}

			$pos = $newlineAt + 1;
			$chunkLen = hexdec(rtrim($chunkLenHex, "\r\n"));
			$dechunk .= substr($chunk, $pos, $chunkLen);
			$pos = strpos($chunk, "\n", $pos + $chunkLen) + 1;
		}
		return $dechunk;
	}

	function is_hex($hex) {
		$hex = strtolower(trim(ltrim($hex, "0")));
		if (empty($hex)) $hex = 0;
		$dec = hexdec($hex);
		return ($hex == dechex($dec));
	}

}

// Using this function instead due to some compatibility problems
function is__writable($path) {
	//will work in despite of Windows ACLs bug
	//NOTE: use a trailing slash for folders!!!
	//see http://bugs.php.net/bug.php?id=27609
	//see http://bugs.php.net/bug.php?id=30931

	if ($path {strlen($path) - 1} == '/') return is__writable($path . uniqid(mt_rand()) . '.tmp'); // recursively return a temporary file path
	else if (is_dir($path)) return is__writable($path . '/' . uniqid(mt_rand()) . '.tmp');
	// check tmp file for read/write capabilities
	$rm = file_exists($path);
	$f = @fopen($path, 'a');
	if ($f === false) return false;
	fclose($f);
	if (!$rm) unlink($path);
	return true;
}

function link_for_file($filename, $only_link = false, $checktime = false) {
	$inCurrDir = strpos(dirname($filename), ROOT_DIR) !== FALSE ? TRUE : FALSE;
	if ($inCurrDir) {
		$Path = parse_url($_SERVER ['SCRIPT_NAME']);
		$Path = substr($Path["path"], 0, strlen($Path["path"]) - strlen(strrchr($Path["path"], "/")));
		$Path = str_replace('\\', '/', $Path . substr(dirname($filename), strlen(ROOT_DIR) - 1));
	} elseif (dirname($_SERVER ['SCRIPT_NAME'] . 'safe') != '/') {
		$in_webdir_path = dirname(str_replace('\\', '/', $_SERVER ['SCRIPT_NAME'] . 'safe'));
		$in_webdir_sub = substr_count($in_webdir_path, '/');
		$in_webdir_root = str_replace('\\', '/', ROOT_DIR);
		for ($i = 1; $i <= $in_webdir_sub; $i++) {
			$in_webdir_path = substr($in_webdir_path, 0, strrpos($in_webdir_path, '/'));
			$in_webdir_root = realpath($in_webdir_root . '/../') . '/';
			$in_webdir = (strpos(str_replace('\\', '/', dirname($filename) . '/'), str_replace('\\', '/', $in_webdir_root)) === 0) ? TRUE : FALSE;
			if ($in_webdir) {
				$Path = dirname($in_webdir_path . '/' . substr($filename, strlen($in_webdir_root)));
				break;
			}
		}
	} else {
		$Path = FALSE;
		if ($only_link) return '';
	}
	$basename = xmlentities(basename($filename));
	$Path = htmlentities($Path) . '/' . rawurlencode(basename($filename));
	$current_link = $time = '';
	if ($checktime) $time = @filemtime($Path);
	if ($only_link) $current_link = 'http://' . urldecode($_SERVER['HTTP_HOST']) . $Path;
	elseif ($Path === FALSE) $current_link = '<span>' . $basename . '</span>';
	else $current_link = '<a href="' . $Path . '">' . $basename . '</a>';
	if ($checktime) return array($current_link, $time);
	else return $current_link;
}

/*
 * Textarea for debugging variable
 * @param string The variable you want to debug
 * @param int Column for variable display
 * @param int Rows for variable display
 * @param bool Options to continue or not process
 * @param string Charset encoding for htmlentities
 */

function textarea($var, $cols = 200, $rows = 30, $stop = false, $char = 'UTF-8') {
	$cols = ($cols == 0) ? 200 : $cols;
	$rows = ($rows == 0) ? 30 : $rows;
	if ($char === false) $char = 'ISO-8859-1';
	echo "\n<br /><textarea cols='$cols' rows='$rows' readonly='readonly'>";
	if (is_array($var)) $text = htmlentities(print_r($var, true), ENT_QUOTES, $char);
	else $text = htmlentities($var, ENT_QUOTES, $char);
	if (empty($text) && !empty($var)) { // Fix "empty?" textarea bug
		$char = ($char == 'ISO-8859-1') ? '' : 'ISO-8859-1';
		if (is_array($var)) $text = htmlentities(print_r($var, true), ENT_QUOTES, $char);
		else $text = htmlentities($var, ENT_QUOTES, $char);
	}
	echo "$text</textarea><br />\n";
	if ($stop) exit;
}

// Get time in miliseconds, like getTime() in javascript
function jstime() {
	list($u, $s) = explode(' ', microtime());
	return sprintf('%d%03d', $s, $u * 1000);
}

function check_referer() {
	$refhost = !empty($_SERVER['HTTP_REFERER']) ? cut_str($_SERVER['HTTP_REFERER'], '://', '/') : false;
	if (empty($refhost)) return;

	//Remove the port.
	$httphost = ($pos = strpos($_SERVER['HTTP_HOST'], ':')) !== false ? substr($_SERVER['HTTP_HOST'], 0, $pos) : $_SERVER['HTTP_HOST'];
	$refhost = ($pos = strpos($refhost, ':')) !== false ? substr($refhost, 0, $pos) : $refhost;
	// If there is a login on the referer, remove it.
	$refhost = ($pos = strpos($refhost, '@')) !== false ? substr($refhost, $pos + 1) : $refhost;

	$whitelist = array($httphost, 'localhost', 'rapidleech.com');
	$is_ext = ($refhost == $_SERVER['SERVER_ADDR'] ? false : true);
	if ($is_ext) foreach ($whitelist as $host)
			if (host_matches($host, $refhost)) {
				$is_ext = false;
				break;
			}

	if ($is_ext) {
		// Uncomment next line if you want rickroll the users from Form leechers.
		// header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");
		html_error($L->sprintf($L->say['not_allowed_leech'], $refhost, 'Referer not allowed.'));
	}
}

function rebuild_url($url) {
	return $url['scheme'] . '://' . (!empty($url['user']) && !empty($url['pass']) ? rawurlencode($url['user']) . ':' . rawurlencode($url['pass']) . '@' : '') . $url['host'] . (!empty($url['port']) && $url['port'] != 80 && $url['port'] != 443 ? ':' . $url['port'] : '') . (empty($url['path']) ? '/' : $url['path']) . (!empty($url['query']) ? '?' . $url['query'] : '') . (!empty($url['fragment']) ? '#' . $url['fragment'] : '');
}

function host_matches($site, $host) {
	if (empty($site) || empty($host)) return false;
	if (strtolower($site) == strtolower($host)) return true;
	$slen = strlen($site);
	$hlen = strlen($host);
	if (($pos = strripos($host, $site)) !== false && ($pos + $slen == $hlen) && $pos > 1 && substr($host, $pos - 1, 1) == '.') return true;
	return false;
}

function GetDefaultParams() {
	global $options;
	$DParam = array();
	if (isset($_GET['useproxy']) && $_GET['useproxy'] == 'on' && !empty($_GET['proxy'])) {
		global $pauth;
		$DParam['useproxy'] = 'on';
		$DParam['proxy'] = $_GET['proxy'];
		if ($pauth) $DParam['pauth'] = urlencode(encrypt($pauth));
	}
	if (isset($_GET['autoclose'])) $DParam['autoclose'] = '1';
	if (isset($_GET['idx'])) $DParam['idx'] = $_GET['idx'];
	if (($options['download_dir_is_changeable'] || $options['maysaveto']) && !empty($_GET['path'])) $DParam['saveto'] = urlencode($_GET['path']);
	$params = array('add_comment', 'domail', 'comment', 'email', 'split', 'partSize', 'method', 'uploadlater', 'uploadtohost');
	foreach ($params as $key) if (!empty($_GET[$key])) $DParam[$key] = $_GET [$key];
	return $DParam;
}

// This function manualy set server timezone,
// believe or not triggering date_default_timezone_set() may perform lil faster in manipulating date()
function getNowzone($t='') {
	global $options;
	if ($t == '') $t = time();
	// incompatibile for PHP4
	if (!function_exists("date_default_timezone_set") || !function_exists("date_default_timezone_get")) {
		$nowzone = $t;
	} elseif ($options["timezone"] > 0) {
		date_default_timezone_set('UTC');
		//$nowzone = strtotime(date("Y M d H:i:s", time() - date("Z") + (3600 * $options["timezone"]) ) );
		$nowzone = ($t + round(3600 * $options["timezone"], 0) );
	} else {
		date_default_timezone_set(date_default_timezone_get());
		$nowzone = $t;
	}
	return $nowzone;
}

// This function check primary file and folder
function checkExistence() {
	global $options, $L;
	//Checking DOWNLOAD_DIR
	if (file_exists(DOWNLOAD_DIR)) {
		if (!is__writable(DOWNLOAD_DIR)) html_error($L->sprintf($L->say['not_writable'], DOWNLOAD_DIR));
	} else {
		html_error($L->sprintf($L->say['dir_not_exists'], DOWNLOAD_DIR));
	}
	// Check FILES_LST is not writable,
	if (file_exists(FILES_LST)) {
		if (!is__writable(FILES_LST)) html_error($L->sprintf($L->say['not_writable'], FILES_LST));
		return @filesize(FILES_LST);
	} else {
		html_error($L->sprintf($L->say['file_not_exists'], FILES_LST));
	}
}

function calcUsedSpace() {
	global $list;
	$total_size = 0;
	if (!$list) {
		_create_list();
	}
	if (isset($list["files"]["totalsize"])) {
		$total_size = $list["files"]["totalsize"];
	} else {
		foreach ($list as $key => $file) {
			if (@file_exists($file ["name"])) {
				$total_size += filesize($file ["name"]);
			}
		}
	}
	return $total_size;
}

/* lowers the case of a string, mb strings accounted for */

function do_strtolower($string) {
	if (function_exists("mb_strtolower")) {
		$string = mb_strtolower($string);
	} else {
		$string = strtolower($string);
	}
	return $string;
}

/* Returns the extension of a file. */

function get_extension($file) {
	return do_strtolower(substr(strrchr($file, "."), 1));
}

/* Render (dead) template needed */

function get_tpl($keyname, $msg="Halted") {
	global $RL_VER, $options, $charSet, $nn;
	//error_reporting(E_ALL);

	$dead_msg = $msg;
	require_once(ROOT_DIR . $keyname . ".php");
}

// Clean Link from * if any. //kaskus filter
function urlcleaner($url) {
	if (strpos($url, "*")) {
		return str_replace("*", "", $url);
	} else {
		return $url;
	}
}

// Limit long path download link
function trimlink($link) {
	$max = 50;
	$range = 5;
	if (strlen($link) > ($max + $range)) {
		$opr = '://';
		$stfrom = (strpos($link, $opr) + strlen($opr) + 1);
		// get the host name
		$lhost = substr($link, 0, strpos(substr($link, $stfrom), "/") + $stfrom);
		$partlnk = explode("/", $link);
		$fname = $partlnk[count($partlnk) - 1];
		// trim filename
		if (strlen($lhost . $fname) > $max) {
			$fname = '&#8230;' . substr($fname, strlen($lhost . $fname) - $max, strlen($fname));
		}
		return $lhost . '/&#8230;/' . $fname;
	} else {
		return $link;
	}
}

// XML Entity Mandatory Escape Characters
function xmlentities($string, $entities=true) {
	$string = ($entities ? htmlentities($string) : $string);
	return str_replace(array('&', '"', "'", '<', '>', ' '), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;', '&#32;'), $string);
}

// Inspector strip quotes Characters
function strip_quotes($string) {
	return str_replace(array('"', "'", ' '), array('_', '_', '_'), $string);
}

// Return form for html_error for retry free download mode
function buildRetryform($msg, $delay = 15) {
	global $PHP_SELF, $LINK, $L, $options;

	if (!$options['alternatefree']) html_error($msg);
	$form = '';
	$form .= ('<script type="text/javascript">');
	$form .= ('var d=document, di=' . $delay . '; // in second');
	$form .= ('function itung(){if(di>0){setTimeout("itung()",1000); di--;');
	$form .= ('d.getElementById("tmr").innerHTML=di; } else');
	$form .= ('{ d.frmretry.submit(); }}setTimeout("itung()", 1000);');
	$form .= ('</script>');
	$form .= ($L->say['try_free_dl'] . '[<b id="tmr" class="g">~</b>]&nbsp;');
	$form .= ('<a href="javascript:;" onclick=\"document.frmretry.submit();\">');
	$form .= ('[' . $L->say['execute'] . ']</a><br /><br />');
	$form .= ('<form name="frmretry" action="' . $PHP_SELF . '" method="post">');
	$form .= ('<input type="hidden" name="link" value="' . $LINK . '" />');
	$keyname = array('premium_acc', 'df_acc', 'hf_acc', 'net_acc', 'rs_acc', 'ul_acc', 'upl_acc', 'cookie_use');
	foreach ($keyname as $k => $v) {
		$form .= ('\n<input type="hidden" name="' . $v . '" value=""/>');
	}
	return $form;
}

//Check allow / Banned IP Address
function chk_this_ip($meth, $user_ip) {
	global $options, $L;
	$ip_allow_ar = array();
	if ($meth == 'is_baned') {
		$list_ip = $options["list_baned_ip"];
	} elseif ($meth == 'is_allow') {
		$list_ip = $options["list_allow_ip"];
	}
	$ip_allow_ar = explode(',', $list_ip);
	ksort($ip_allow_ar);
	$check_is = false;
	$ada_bintang = 0;
	$i = 0;
	if ($meth == 'is_allow' && !$ip_allow_ar[$i]) {
		html_error($L->say['malfunction_ip']);
	}
	while (!$check_is && ($ip_allow_ar[$i])) {
		$ada_bintang = ($ip_allow_ar[$i] != '*.*.*.*' ? substr_count($ip_allow_ar[$i], '.*') : 4);
		if ($ada_bintang > 0) {
			if ($ada_bintang != 4) {
				$sub_ip = substr($ip_allow_ar[$i], 0, strpos($ip_allow_ar[$i], '.*')) . ".";
				$u_ip_ar = explode('.', $user_ip);
				$bit = (int) (3 - $ada_bintang);
				$u_ip = '';
				for ($j = 0; $j <= $bit; $j++) {
					$u_ip = $u_ip . $u_ip_ar[$j] . ".";
				}
				if (trim($u_ip) == trim($sub_ip)) {
					$check_is = true;
				}
			} else {
				$check_is = true;
			}
		} else {
			if (trim($user_ip) == trim($ip_allow_ar[$i])) {
				$check_is = true;
			}
		}
		$i++;
	}
	return $check_is;
}

//encryption
function dcd($c) {
	$pt = ""; $bfC = $c;
	if ($c == "") {
		return $pt;
	}
	$rRot = (int) substr($c, -2);
	if (!is_numeric($rRot)) {
		return false;
	} else {
		$bfC = strtoupper(substr($c, 0, -2));
		$pj = strlen($bfC);
		$i = 0;
	}
	if ($pj % 2 != 0) {
		return false;
	}
	while ($i < $pj) {
		$hs = chr(hexdec(substr($bfC, $i, 2)));
		$pt.=$hs;
		$i = $i + 2;
	}
	$pt = strrev(rotN($pt, -$rRot));
	return $pt;
}

function mcd($armacc) {
	$dar = array();
	$n_a = count($armacc);
	for ($i = 0; $i < $n_a; $i++) {
		$buf_ac = array();
		$cl_all = array();
		$buf_ac = explode(chr(hexdec("3A")), dcd($armacc[$i]));
		$cl_all["user"] = $buf_ac[0];
		$cl_all["pass"] = $buf_ac[1];
		$dar[] = $cl_all;
	}
	return $dar;
}

function encEnti($v) {
	$rt = "";
	for ($i = 0; $i < strlen($v); $i++) {
		$ktr = strtoupper(dechex(ord($v{$i})));
		$rt.=$ktr;
	}
	return $rt;
}

function decEnti($c) {
	$pt = ""; $pj = strlen($c); $i = 0;
	if ($pj % 2 != 0) {
		return false;
	}
	while ($i < $pj) {
		$hs = chr(hexdec(substr($c, $i, 2)));
		$pt.=$hs;
		$i = $i + 2;
	}
	return $pt;
}

function rotN($s, $n) {
	$s2 = "";
	for ($i = 0; $i < strlen($s); $i++) {
		$char2 = $char = ord($s{$i});
		$cap = $char & 32;
		$char &= ~ $cap;
		$char = $char > 64 && $char < 123 ? (($char - 65 + $n) % 26 + 65) : $char;
		$char |= $cap;
		if ($char < 65 && $char2 > 64 || ($char > 90 && $char < 97 && ($char2 < 91 || $char2 > 96))) $char += 26;
		else if ($char > 122 && $char2 < 123) $char -= 52;
		if (strtoupper(chr($char2)) === chr($char2)) $char = strtoupper(chr($char)); else $char = strtolower(chr($char));
		$s2 .= $char;
	}
	return $s2;
}

function encrypt($string) {
	global $options;
	if (empty($string)) {
		return '';
	}
	$secretkey = $options['secretkey'];
	if (empty($secretkey) || $secretkey == 'UijSY5wjP1Ii') {
		return html_error("Value for \$options['secretkey'] is empty or use default secretkey value, please create a random one (56 chars max) in your configs/config.php!", 0);
	}
	require_once 'class.pcrypt.php';
	/*
	  MODE: MODE_ECB or MODE_CBC
	  ALGO: BLOWFISH
	  KEY:  Your secret key :) (max lenght: 56)
	 */
	$crypt = new pcrypt(MODE_CBC, "BLOWFISH", $secretkey);

	// Return encrypted string
	return $crypt->encrypt($string);
}

function decrypt($string) {
	global $options;
	if (empty($string)) {
		return '';
	}
	$secretkey = $options['secretkey'];
	if (empty($secretkey) || $secretkey == 'UijSY5wjP1Ii') {
		return html_error("Value for \$options['secretkey'] is empty or use default secretkey value, please create a random one (56 chars max) in your configs/config.php!", 0);
	}
	require_once 'class.pcrypt.php';
	/*
	  MODE: MODE_ECB or MODE_CBC
	  ALGO: BLOWFISH
	  KEY:  Your secret key :) (max lenght: 56)
	 */
	$crypt = new pcrypt(MODE_CBC, "BLOWFISH", $secretkey);

	// Return decrypted string
	return $crypt->decrypt($string);
}

/* ==========
  autoNext function
  param:
  $iset : should it set autoDL true or not
  $forceNext: if download found error keep evaluate next link
  $audl: wot method send from audl.php. eg. sims, queue, manual
  ========== */

function autoNext($iset, $forceNext, $audl) {
	global $options;
	$bfRet = "";
	$bfRet = "\r\n<script type='text/javascript'>\n";
	$bfRet.="try{if(parent.isAuto==true){id=parent.current_dlink;\n";
	$bfRet.="parent.startisdone(id);}\nelse{\n id=self.name.substring(self.name.length-1); idLnk=parent.document.getElementById('idxlink'+id).innerHTML; parent.iframeReady(id); idx=eval(idLnk-1); parent.startisdone(idx);\n}\n}\ncatch(e){}\n";
	if ($audl != "") {
		if ($audl == "sims") {
			$bfRet.="id=self.name;id=id.substring(id.length-1);\n";
			$bfRet.="if(window.opener!=null){window.opener.startisdone(id);}\n";
		} elseif ($audl == "queue" || $audl == "manual") {
			$bfRet.="try{id=parent.current_dlink;\n";
			$bfRet.="parent.startisdone(id);}catch(e){}\n";
		}
	}
	$bfRet.="</script>\r\n";

	if ($iset) {
		$bfRet.="\r\n<script type='text/javascript'>var autoDL = true;\n";
	} else {
		$bfRet.= "\r\n<script type='text/javascript'>var autoDL;\n";
	}
	switch ($audl) {
		case "sims" :
			$bfRet.= "var not_simultan = false;";
			break;
		default :
			$bfRet.= "var not_simultan = true;";
			break;
	}

	if ($options['showautoclose'] == true && $forceNext) {
		$bfRet.="\r\n<!-- \n var time = " . $options['timeautoclose'] . ";\nif(autoDL==true && not_simultan){parent.nextlink(id);}\nfunction vbaccept(){\ntime--;frm = document.vbaccept;\nif(frm)frm.submit.value = 'Auto-Close through '+time+'';\n";
		$bfRet.="if(time>0){window.setTimeout(\"vbaccept()\",1);}\n else \nif(frm){frm.submit.value = 'done';\nfrm.submit.disabled=0;window.close(self);}}\n";
		if ($audl == 'sims') {
			$bfRet.="if(autoDL==true && not_simultan==false){ if(window.opener!=null){vbaccept(); var da = document.getElementById('tdone'); da.href='javascript:if(autoDL==true && not_simultan){window.opener.nextlink(id);}window.close(self);'; document.getElementById('txtdone').innerHTML='Done';}}\n\n";
		}
		$bfRet.="--></script>";
		$bfRet.="<form id='vbaccept' name='vbaccept'><input type='submit' name='submit' style='color:#fff;' value='done' disabled></form>";
	} else {
		$bfRet.="\r\nif(autoDL==true && not_simultan){parent.nextlink(id);}\n if(autoDL==true && not_simultan==false){if(window.opener!=null){\r\n var da = document.getElementById('tdone');  \n da.href='javascript:if(autoDL==true && not_simultan){window.opener.nextlink(id);} window.close(self);';\n document.getElementById('txtdone').innerHTML='Close';}}</script>";
	}
	return $bfRet;
}

// get server platform
function getServerPlatf($dash=0) {
	$server["is_windows"] = (strtoupper(substr(PHP_OS, 0, 3)) == "WIN");
	$server["is_linux"] = (stristr(PHP_OS, "Linux"));
	$server["is_mac"] = (stristr(PHP_OS, "Darwin"));
	$server["32bit"] = (PHP_INT_SIZE == 4);
	$server["64bit"] = (PHP_INT_SIZE == 8);
	$server["property"] = "Host:";
	foreach ($server as $field => $vtrue) {
		if ($vtrue === true) {
			$server["property"].= str_replace("is_", "&nbsp;", $field) . "&nbsp;";
		}
	}
	$server["property"].= ($dash == 1 ? "&#8212;&nbsp;" : "");
	return $server;
}

// Needed for sload.php & sinfo.php
if (!function_exists('color_cpu')) {

	function color_cpu($nilai) {
		global $alert_sloadhigh;
		if (!is_numeric($nilai)) {
			return FALSE;
		} else {
			if ($nilai <= 33) {
				$clr = "#3D612C";
			} elseif ($nilai <= 66) {
				$clr = "#FFCC33";
			} else {
				$clr = "#FF0000";
			}
			$nilai = '<b ' . ($alert_sloadhigh ? 'class="r' : 'style="color:' . $clr) . '">' . $nilai . '</b>';
			return $nilai;
		}
	}

}

// age file facebook looks like
function count_age($age) {
	global $L;
	if ($age <= 60) return $L->say['less_a_minute'];
	$jam_str = "";
	$jam = floor($age / 3600);
	if ($jam >= 1) {
		$jam_str = ($jam . " " . $L->say['hours'] . " " );
		$age -= $jam * 3600;
	}
	if ($age >= 60) {
		$min = floor($age / 60);
	} else {
		$sec = $age;
	}
	return $jam_str .
		(isset($min) && $min > 0 ? $min . " " . $L->say['minutes'] : "") .
		(isset($sec) && $sec > 0 ? $sec . " " . $L->say['seconds'] : "") .
		($L->say['ago'] != "" ? " " . $L->say['ago'] : "");
}

// get working time
function cek_worktime($workstart, $workend) {
	global $options;
	$tdebug = false;
	//$zone=3600 * $options["timezone"]; // GMT +7 ~ Indonesia
	//$tn = getdate(strtotime(date("d M Y H:i:s", time() - date("Z") + $zone)));
	$tn = getdate(TIME_NOW);
	$dtstr = " Apr 1983 ";
	$tgl = "14";

	$tnH = $tn["hours"];
	$tnM = $tn["minutes"];
	$tnS = $tn["seconds"];
	if ($tnH < 10) $tnH = '0' . $tnH;
	if ($tnM < 10) $tnM = "0" . $tnM;
	if ($tnS < 10) $tnS = "0" . $tnS;

	$wstart = strtotime($tgl . $dtstr . $workstart);
	$nowUnix = strtotime("$tgl $dtstr $tnH:$tnM:$tnS");
	$ws = explode(":", $workstart);
	$we = explode(":", $workend);

	if ((int) $ws[0] >= (int) $we[0]) {
		$tgl = "15";
		if ((int) $tnH <= $we[0]) {
			$nowUnix = strtotime("$tgl $dtstr $tnH:$tnM:$tnS");
		}
	}
	$wend = strtotime($tgl . $dtstr . $workend);

	if ($tdebug) {
		echo $tn . "<br />" . "$tgl $dtstr $tnH:$tnM:$tnS<br />$nowUnix<br />" . "$workstart-" . $wstart . "-" . $wend . "-$workend";
	}
	if (($nowUnix - $wstart) > 0 && ($wend - $nowUnix) > 0) {
		return true;
	} else {
		return false;
	}
}

// rebuild link download_dir if notin current dir
function relink_path($DL_link, $phpself = "", $spliter = "/", $drname = false) {
	global $PHP_SELF;

	if ($phpself == "") {
		$phpself = (!$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF);
	}
	$ddot_len = substr_count($DL_link, '../', 0);

	$rPth = parse_url($phpself);
	if ($phpself != "") {
		$rPth = substr($rPth["path"], 0, strlen($rPth["path"]) - strlen(strrchr($rPth["path"], $spliter)));
	}
	for ($i = 0; $i < $ddot_len; $i++) {
		$rPth = parse_url($rPth);
		$rPth = substr($rPth["path"], 0, strlen($rPth["path"]) - strlen(strrchr($rPth["path"], $spliter)));
	}
	$rPth = ( (substr($rPth, -1) == $spliter) ? substr($rPth, 0, strlen($rPth) - 1) : $rPth);
	$cPath["root"] = $rPth;
	$cPath["download"] = str_replace("../", "", $DL_link);
	return $cPath;
}

// reverse string
function utf8_strrev($str) {
	preg_match_all('/./us', $str, $ar);
	return join('', array_reverse($ar[0]));
}

// check strictly forbidden file in download dir
function checkStrict() {
	global $options;
	$dir = @dir(DOWNLOAD_DIR);
	while (false !== ($file = $dir->read())) {
		if (in_array(strtolower(strrchr($file, ".")), $options['forbidden_filetypes']) && $file != '.htaccess' && $file != '.htpasswd' && $file != 'index.html') {
			@unlink(DOWNLOAD_DIR . $file);
		}
	}
	$dir->close();
}

/**
 * Renders the file actions, this function does NOT output directly to the browser
 *
 * @return string The string will contain the <select></select> tag
 *
 */
function renderActions() {
	global $options, $L;
	$return = "";
	$return .= '<select name="act" onchange="javascript:void(document.flist.submit());" style="float:left;">';
	$return .= '<option selected="selected">' . $L->say['action'] . '</option>';
	if (!$options['disable_to']['act_upload']) $return .= '<option value="upload">' . $L->say['act_upload'] . '</option>';
	if (!$options['disable_to']['act_ftp']) $return .= '<option value="ftp">' . $L->say['act_ftp'] . '</option>';
	if (!$options['disable_to']['act_mail']) $return .= '<option value="mail">' . $L->say['act_mail'] . '</option>';
	if (!$options['disable_to']['act_boxes']) $return .= '<option value="boxes">' . $L->say['act_boxes'] . '</option>';
	if (!$options['disable_to']['act_split']) $return .= '<option value="split">' . $L->say['act_split'] . '</option>';
	if (!$options['disable_to']['act_merge']) $return .= '<option value="merge">' . $L->say['act_merge'] . '</option>';
	if (!$options['disable_to']['act_md5']) $return .= '<option value="md5">' . $L->say['act_md5'] . " / Changer" . '</option>';
	if ((file_exists(CLASS_DIR . "pear.php") || file_exists(CLASS_DIR . "tar.php")) && !$options['disable_to']['act_pack']) $return .= '<option value="pack">' . $L->say['act_pack'] . '</option>';
	if (file_exists(CLASS_DIR . "pclzip.php") && !$options['disable_to']['act_zip']) $return .= '<option value="zip">' . $L->say['act_zip'] . '</option>';
	if (file_exists(CLASS_DIR . "unzip.php") && !$options['disable_to']['act_unzip']) $return .= '<option value="unzip">' . $L->say['act_unzip'] . '</option>';
	if (substr(PHP_OS, 0, 3) != "WIN" && @file_exists(CLASS_DIR . "rar.php")) {
		if (!$options['disable_to']['act_rar']) {
			$return .= '<option value="rar">' . $L->say['act_rar'] . '</option>';
		}
		if (!$options['disable_to']['act_unrar']) {
			if (@file_exists(BINARY_DIR . 'rar/rar') || @file_exists(BINARY_DIR . 'rar/unrar')) {
				$return .= '<option value="unrar">' . $L->say['act_unrar'] . '</option>';
			}
		}
	}
	if (!$options['disable_to']['act_rename']) $return .= '<option value="rename">' . $L->say['act_rename'] . '</option>';
	if (!$options['disable_to']['act_mrename']) $return .= '<option value="mrename">' . $L->say['act_mrename'] . '</option>';
	if (!$options['disable_to']['act_delete']) $return .= '<option value="delete">' . $L->say['act_delete'] . '</option>';
	$return .= '</select>&nbsp;&nbsp;';
	return $return;
}

function vidlist($dir, $exts = '') {
	$results = array();
	$handler = opendir($dir);
	while ($file = readdir($handler)) {
		if (strrchr($file, '.') != "") {
			$ext = strtolower(strrchr($file, '.'));
		} else {
			$ext = '';
		}
		if ($file != '.' && $file != '..' && in_array($ext, $GLOBALS["exts"])) {
			$results[] = $file;
		}
	}
	closedir($handler);
	sort($results);
	return $results;
}

/* return the formatted output of auto delete time */
function autodel_formatted($delay) {
	global $L;
	if ($delay < 1) {
		$delay = round($delay * 60);
		$unit_time = $L->say['seconds'];
	} elseif ($delay > 60) {
		$delay = round($delay / 60, 1);
		$unit_time = $L->say['hours'];
	} else {
		$unit_time = $L->say['minutes'];
	}
	return array($delay, $unit_time);
}
?>