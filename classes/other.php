<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}

/* Index Function */ {
// THIS FILE CONTAINS GENERAL FUNCTION NEEDED
// METHODS IN THIS FILE:
//   create_hosts_file()
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
//   _create_list_LIP()
//   checkmail()
//   fixfilename()
//   getfilesize()
//   bytesToKbOrMb()
//   bytesToKbOrMbOrGb()
//   defport()
//   getSize()
//   purge_files()
//   purge_files_ip()
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
//   get_real_ip()
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
//   ip counter()
//   renderAction()
//   vidlist()
//   ________()
}

function create_hosts_file($host_file = "hosts.php") {
	global $L;
	$fp = opendir(HOST_DIR . 'download/');
	while (($file = readdir($fp)) !== false) {
		if (substr($file, - 4) == ".inc") {
			require_once (HOST_DIR . 'download/' . $file);
		}
	}
	if (!is_array($host)) {
		print $L->say['no_host'];
	} else {
		$fs = fopen(HOST_DIR . 'download/' . $host_file, "wb");
		if (!$fs) {
			print $L->say['cant_write_host'];
		} else {
			fwrite($fs, "<?php\r\n\$host = array(\r\n");
			$i = 0;
			foreach ($host as $site => $file) {
				if ($i != (count($host) - 1)) {
					fwrite($fs, "'" . $site . "' => '" . $file . "',\r\n");
				} else {
					fwrite($fs, "'" . $site . "' => '" . $file . "');\r\n?>");
				}
				$i++;
			}
			closedir($fp);
			fclose($fs);
		}
	}
}

function login_check() {
	global $options;
	if ($options['login']) {

		function logged_user($ul) {
			foreach ($ul as $user => $pass) {
				if ($_SERVER['PHP_AUTH_USER'] == $user && $_SERVER['PHP_AUTH_PW'] == $pass) {
					return true;
				}
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

function is_present($lpage, $mystr, $strerror = "", $head = 0) {
	$strerror = $strerror ? $strerror : $mystr;
	if (stripos($lpage, $mystr) !== false) {
		html_error($strerror, $head);
	}
}

function is_notpresent($lpage, $mystr, $strerror, $head = 0) {
	if (stripos($lpage, $mystr) === false) {
		html_error($strerror, $head);
	}
}

function insert_location($newlocation) {
	if (isset($_GET ["GO"]) && $_GET ["GO"] == "GO") {
		list ( $location, $list ) = explode("?", $newlocation);
		$list = explode("&", $list);
		foreach ($list as $l) {
			list ( $name, $value ) = explode("=", $l);
			$_GET [$name] = $value;
		}
	} else {
		global $nn;
		list ( $location, $list ) = explode("?", $newlocation);
		$list = explode("&", $list);
		print "<form action=\"$location\" method=\"post\">" . $nn;
		foreach ($list as $l) {
			list ( $name, $value ) = explode("=", $l);
			print "<input type=\"hidden\" name=\"$name\" value=\"$value\" />" . $nn;
		}
		echo ('<script type="text/javascript">void(document.forms[0].submit());</script>');
		echo ('</form>');
		echo ('</body>');
		echo ('</html>');
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
	if ($trunk == 1) {
		$mode = "wb";
	} elseif ($trunk == 0) {
		$mode = "ab";
	}
	$fp = fopen($file_name, $mode);
	if (!$fp) {
		return FALSE;
	} else {
		if (!flock($fp, LOCK_EX)) {
			return FALSE;
		} else {
			if (!fwrite($fp, $data)) {
				return FALSE;
			} else {
				if (!flock($fp, LOCK_UN)) {
					return FALSE;
				} else {
					if (!fclose($fp)) {
						return FALSE;
					}
				}
			}
		}
	}
	return TRUE;
}

function read_file($file_name, $count = -1) {
	if ($count == - 1) {
		$count = filesize($file_name);
	}
	$fp = fopen($file_name, "rb");
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
	global $PHP_SELF, $options, $L, $RL_VER, $nn;
	//if ($head == 1)
	if (!headers_sent()) {
		$litehead = 1;
		include(TEMPLATE_DIR . 'header.php');
	}

	echo ('<div align="center">');
	echo ('<span class="htmlerror"><b>' . $msg . '</b></span><br /><br />');

	if ($retry == 1) {
		echo buildRetryform($msg);
	} elseif ($options['new_window'] && $newwin) {
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
		foreach ($list as $key => $value) {
			$list [$key] = serialize($value);
		}
		//echo file_put_contents ( FILES_LST, implode ( "\r\n", $list ) . "\r\n" );
		if (!file_put_contents(FILES_LST, implode("\r\n", $list) . "\r\n") && count($list) > 0) {
			return FALSE;
		} else {
			return TRUE;
		}
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
	if (isset($a["date"]) && isset($b["date"])) {
		return strcmp($a["date"], $b["date"]);
	} else {
		return strcmp($a["name"], $b["name"]);
	}
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
			@exec('stat' . (stristr(@php_uname('s'), 'bsd') !== false ? '-f %m ' : ' -c %Y ') . escapeshellarg($file), $time, $tmp);
			if ($tmp == 0) {
				$time = trim(implode($time));
			}
			@exec('stat' . (stristr(@php_uname('s'), 'bsd') !== false ? '-f %z ' : ' -c %s ') . escapeshellarg($file), $size, $tmp);
			if ($tmp == 0) {
				$size = trim(implode($size));
			}
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
	//$unix_now = ( TIME_NOW - date("Z") + (3600 * $options["timezone"]));
	if (!$d_showall) $d_showall = (isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"] : false);
	if (($options["show_all"] && ($d_showall == 1)) || $medic) {
		// Show Everything
		$totsize = 0; $cnt = 0;
		$dir = @dir(DOWNLOAD_DIR);
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
	} else {

		// Show Downloaded
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

function _create_list_LIP() {
	global $list;
	$glist = array();
	$glistReformat = null;
	if (@file_exists(IP_L33CH_L0G)) {
		$glist = file(IP_L33CH_L0G);
		foreach ($glist as $key => $record) {
			foreach (unserialize($record) as $field => $value) {
				$listReformat[$key][$field] = $value;
				if ($field == "date") $date = $value;
			}
			$glist[$date] = $listReformat[$key];
			unset($glist[$key], $glistReformat[$key]);
		}
	}
	$list = $glist;
}

function checkmail($mail) {
	if (strlen($mail) == 0) {
		return false;
	}
	if (!preg_match("/^[a-z0-9_\.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|" . "edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-" . "9]{1,3}\.[0-9]{1,3})$/is", $mail)) {
		return false;
	}
	return true;
}

/* Fixed Shell exploit by: icedog */

function fixfilename($fname, $fpach = '') {
	$f_name = basename($fname);
	$f_dir = dirname(preg_replace("@\.\./@i", "", $fname));
	$f_dir = ($f_dir == '.') ? '' : $f_dir;
	$f_dir = preg_replace("@\.\./@i", "", $f_dir);
	$fpach = preg_replace("@\.\./@i", "", $fpach);
	$f_name = preg_replace("@\.(php|hta|pl|cgi|sph)@i", ".xxx", $f_name);
	$ret = ($fpach) ? $fpach . DIRECTORY_SEPARATOR . $f_name : ($f_dir ? $f_dir . DIRECTORY_SEPARATOR : '') . $f_name;
	return $ret;
}

function getfilesize($f) {
	global $is_windows;
	$stat = stat($f);

	if ($is_windows) return sprintf("%u", $stat [7]);
	if (($stat [11] * $stat [12]) < 4 * 1024 * 1024 * 1024) return sprintf("%u", $stat [7]);

	global $max_4gb;
	if ($max_4gb === false) {
		$tmp_ = trim(@shell_exec(" ls -Ll " . @escapeshellarg($f)));
		while (strstr($tmp_, '  ')) {
			$tmp_ = @str_replace('  ', ' ', $tmp_);
		}
		$r = @explode(' ', $tmp_);
		$size_ = $r [4];
	} else {
		$size_ = - 1;
	}

	return $size_;
}

function bytesToKbOrMb($bytes) {
	$size = ($bytes >= (1024 * 1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024 * 1024), 2) . " TB" : (($bytes >= (1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024), 2) . " GB" : (($bytes >= (1024 * 1024)) ? round($bytes / (1024 * 1024), 2) . " MB" : round($bytes / 1024, 2) . " KB"));
	return $size;
}

// Updated function to be able to format up to Yotabytes!
function bytesToKbOrMbOrGb($bytes) {
	if (is_numeric($bytes)) {
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
	if ($urls ["port"] !== '' && isset($urls ["port"])) return $urls ["port"];

	switch (strtolower($urls ["scheme"])) {
		case "http" :
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
		if (!(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')) {
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
				$filedate = getNowzone(@filemtime($files_data["name"]));
				if ((TIME_NOW - $filedate) >= ($delay * 3600)) {
					@unlink($files_data["name"]);
					$deleted["count"]++;
					$deleted["files"][] = $options["download_dir"] . basename($files_data["name"]);
				} else {
					$files_new .= $files_line;
				}
			}
		}
		file_put_contents(FILES_LST, $files_new);
	}
	purge_files_ip($delay);
	//return $cnt_deleted;
	return $deleted;
}

// Delete ip log user download
function purge_files_ip($delay) {
	if (file_exists(IP_L33CH_L0G) && is_numeric($delay) && $delay > 0) {
		$files_lst = file(IP_L33CH_L0G);
		$files_new = "";
		foreach ($files_lst as $files_line) {
			$files_data = unserialize(trim($files_line));
			$filedate = getNowzone(@filemtime($files_data["name"]));
			if (TIME_NOW - $filedate < ($delay * 3600)) {
				$files_new .= $files_line;
			}
		}
		file_put_contents(IP_L33CH_L0G, $files_new);
	}
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
		$pos = 0;
		$len = strlen($chunk);
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
	global $PHP_SELF;
	$inCurrDir = strstr(dirname($filename), ROOT_DIR) ? TRUE : FALSE;
	if ($inCurrDir) {
		$Path = parse_url($PHP_SELF);
		$Path = substr($Path["path"], 0, strlen($Path["path"]) - strlen(strrchr($Path["path"], "/")));
		$Path = str_replace('\\', '/', $Path . substr(dirname($filename), strlen(ROOT_DIR) - 1));
	} elseif (dirname($PHP_SELF . 'safe') != '/') {
		$in_webdir_path = dirname(str_replace('\\', '/', $PHP_SELF . 'safe'));
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
		if ($only_link) {
			return '';
		}
	}
	$basename = xmlentities(basename($filename));
	$Path = htmlentities($Path) . '/' . rawurlencode(basename($filename));
	$current_link = $time = '';
	if ($checktime) {
		$time = @filemtime($Path);
	}
	if ($only_link) {
		$current_link = 'http://' . urldecode($_SERVER['HTTP_HOST']) . $Path;
	} elseif ($Path === FALSE) {
		$current_link = '<span>' . $basename . '</span>';
	} else {
		$current_link = '<a href="' . $Path . '">' . $basename . '</a>';
	}
	if ($checktime) {
		return array($current_link, $time);
	} else {
		return $current_link;
	}
}

/**
 * Textarea for debugging variable
 * @param string The variable you want to debug
 * @param int Column for variable display
 * @param int Rows for variable display
 * @param bool Options to continue or not process
 * @param string Charset encoding for htmlentities
 */
function textarea($var, $cols = 200, $rows = 30, $stop = false, $char = '') {
	$cols = ($cols == 0) ? 200 : $cols;
	$rows = ($rows == 0) ? 30 : $rows;
	echo "\n<br /><textarea cols='$cols' rows='$rows' readonly='readonly'>";
	if (is_array($var)) echo htmlentities(print_r($var, true), ENT_QUOTES, $char);
	else echo htmlentities($var, ENT_QUOTES, $char);
	echo "</textarea><br />\n";
	if ($stop) exit;
}

// Get time in miliseconds, like getTime() in javascript
function jstime() {
	list($u, $s) = explode(' ', microtime());
	return sprintf('%d%03d', $s, $u * 1000);
}

function check_referer() {
	$refhost = !empty($_SERVER['HTTP_REFERER']) ? cut_str($_SERVER['HTTP_REFERER'], '://', '/') : false;
	if (!$refhost) return;

	if (!empty($_SERVER['HTTP_HOST'])) $httphost = preg_replace('@(:\d+)$@', '', $_SERVER['HTTP_HOST']);
	$httphost = !empty($httphost) && $httphost != $_SERVER['SERVER_NAME'] ? "|($httphost)" : '';

	if (!preg_match(str_replace('.', '\.', "@({$_SERVER['SERVER_NAME']})|({$_SERVER['SERVER_ADDR']})$httphost(:\d+)?$@i"), $refhost)) {
		// Uncomment next line if you want rickroll the users from Form leechers.
		// header("Location: http://www.youtube.com/watch?v=oHg5SJYRHA0");
		html_error($L->sprintf($L->say['not_allowed_leech'], $refhost, 'Referer not allowed.'));
	}
}

function rebuild_url($url) {
	return $url['scheme'] . "://" . (!empty($url['user']) && !empty($url['pass']) ? rawurlencode($url['user']) . ":" . rawurlencode($url['pass']) . "@" : '') . $url['host'] . (!empty($url['port']) && $url['port'] != 80 && $url['port'] != 443 ? ":" . $url['port'] : "") . (empty($url['path']) ? "/" : $url['path']) . (!empty($url['query']) ? "?" . $url['query'] : "") . (!empty($url['fragment']) ? "#" . $url['fragment'] : "");
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
// returned filesize of the FILES_LST
function checkExistence() {
	global $L;
	//Checking DOWNLOAD_DIR
	if (file_exists(DOWNLOAD_DIR)) {
		if (!is__writable(DOWNLOAD_DIR)) {
			html_error($L->sprintf($L->say['not_writable'], DOWNLOAD_DIR));
		}
	} else {
		html_error($L->sprintf($L->say['dir_not_exists'], DOWNLOAD_DIR));
	}

	// Check FILES_LST is not writable,
	if (file_exists(FILES_LST)) {
		if (!is__writable(FILES_LST)) {
			html_error($L->sprintf($L->say['not_writable'], FILES_LST));
		}
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

// Get real IP
function get_real_ip() {
	$ipRL = false;
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ipRL = $_SERVER['HTTP_CLIENT_IP'];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ipRL) {
			array_unshift($ips, $ipRL);
			$ipRL = false;
		}
		for ($i = 0; $i < count($ips); $i++) {
			if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
				if (version_compare(phpversion(), "5.0.0", ">=")) {
					if (ip2long($ips[$i]) != false) {
						$ipRL = $ips[$i];
						break;
					}
				} else {
					if (ip2long($ips[$i]) != - 1) {
						$ipRL = $ips[$i];
						break;
					}
				}
			}
		}
	}
	return ($ipRL ? $ipRL : $_SERVER['REMOTE_ADDR']);
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
	$pt = "";
	$bfC = $c;
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
	$pt = "";
	$pj = strlen($c);
	$i = 0;
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
	global $secretkey;
	if (empty($string)) {
		return '';
	}
	if (!$secretkey) {
		return html_error('Value for $secretkey is empty, please create a random one (56 chars max) in your config!', 0);
	}
	require_once 'class.pcrypt.php';
	/*
	  MODE: MODE_ECB or MODE_CBC
	  ALGO: BLOWFISH
	  KEY:  Your secret key :) (max lenght: 56)
	 */
	$crypt = new pcrypt(MODE_CBC, "BLOWFISH", "$secretkey");

	// Return encrypted string
	return $crypt->encrypt($string);
}

function decrypt($string) {
	global $secretkey;
	if (empty($string)) {
		return '';
	}
	if (!$secretkey) {
		return html_error('Value for $secretkey is empty, please create a random one (56 chars max) in your config!', 0);
	}
	require_once 'class.pcrypt.php';
	/*
	  MODE: MODE_ECB or MODE_CBC
	  ALGO: BLOWFISH
	  KEY:  Your secret key :) (max lenght: 56)
	 */
	$crypt = new pcrypt(MODE_CBC, "BLOWFISH", "$secretkey");

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

	if ($options["showautoclose"] == "true" && $forceNext) {
		$bfRet.="\r\n<!-- \n var time = " . $options["timeautoclose"] . ";\nif(autoDL==true && not_simultan){parent.nextlink(id);}\nfunction vbaccept(){\ntime--;frm = document.vbaccept;\nif(frm)frm.submit.value = 'Auto-Close through '+time+'';\n";
		$bfRet.="if(time>0){window.setTimeout(\"vbaccept()\",1);}\n else \nif(frm){frm.submit.value = 'done';\nfrm.submit.disabled=0;window.close(self);}}\n";
		if ($audl == 'sims') {
			$bfRet.="if(autoDL==true && not_simultan==false){ if(window.opener!=null){vbaccept(); var da = document.getElementById('tdone'); da.href='javascript:if(autoDL==true && not_simultan){window.opener.nextlink(id);}window.close(self);'; document.getElementById('txtdone').innerHTML='Done';}}\n\n";
		}
		$bfRet.="--></script>";
		$bfRet.="<form id=vbaccept name=vbaccept><input type=submit name=submit style='color:#fff;' value=\"done\" disabled></form>";
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

// check and count ip download
function ipcounter() {
	global $heute, $options, $ipmu;
	$xhandle = @opendir("tmp");
	if ($xhandle) {
		while ($buin = readdir($xhandle)) {
			if (preg_match("@$ipmu@i", $buin)) {
				$heute = $heute + 1;
			}
		}
		$ckusr = decEnti((string) base64_decode(urldecode($_COOKIE['rxyzusr'])));
		$ckusr = (is_numeric($ckusr) ? $ckusr : '0');
		if (($ckusr != '') && ($ckusr > $heute)) {
			$heute = $ckusr;
		} else {
			setcookie('rxyzusr', base64_encode(encEnti((string) $heute)), TIME_NOW + ($options['delay_per_ip'] * 60 * 60));
		}
		closedir($xhandle);

		error_reporting(0);
		$xhandle = @opendir("tmp");
		while ($buin = readdir($xhandle)) {
			if ($buin == "." || $buin == "..") {
				$buin = "fu";
			}
			$xd = @filemtime("tmp/$buin");
			$xd = getNowzone($xd);
			$altr = TIME_NOW - $xd;
			if ($altr > $options['delay_per_ip'] * 60 * 60) {
				if (is_dir("tmp/$buin")) {
					@rmdir("tmp/$buin");
				} else {
					@unlink("tmp/$buin");
				}
			}
		}
		closedir($xhandle);
		error_reporting(1);
	}
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
	if (!$options['disable_to']['act_md5']) $return .= '<option value="md5">' . $L->say['act_md5'] . " / Changer". '</option>';
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

function vidlist($dir, $exts) {
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

function view_size($size) {
	if (!is_numeric($size)) {
		return FALSE;
	} else {
		if ($size >= 1073741824) {
			$size = round($size / 1073741824 * 100) / 100 . "&nbsp;GB";
		} elseif ($size >= 1048576) {
			$size = round($size / 1048576 * 100) / 100 . "&nbsp;MB";
		} elseif ($size >= 1024) {
			$size = round($size / 1024 * 100) / 100 . "&nbsp;KB";
		} else {
			$size = $size . "&nbsp;B";
		}
		return $size;
	}
}

function lnkfilter($siLink) {
	$linkres = $siLink;
	if (strpos($siLink, "rapid*share.com/") || (strpos($siLink, "rapid^share.com/")) || (strpos($siLink, "rapdshare.com/"))) {
		$rssuspect = array('rapid*share.com/', 'rapid^share.com/', 'rapdshare.com/');
		$rsabs = 'rapidshare.com/';
		$linkres = str_replace($rssuspect, $rsabs, $linkres);
	}
	return $linkres;
}

function get_traffic($filena) {
	$isinya = array(' ', null);
	$fl = @fopen($filena, "r");
	if ($fl) {
		$buftxt = @fgets($fl);
		@fclose($fl);
		$isinya = explode(":", trim($buftxt));
	}
	if (!is_numeric($isinya[0])) {
		$isinya[0] = 0;
	}
	return $isinya;
}

function autoreset_traffic($days, $c_traf) {
	global $options;
	$zone = (3600 * $options['timezone']);

	if ($days > 0) {
		$reset_traffic = false;
		$start_date = $c_traf[1];
		$unix_now = strtotime("now") + $zone;

		if (!preg_match("/\d{10}/i", $start_date)) {
			$start_date = $unix_now;
		} else {
			if ($start_date > $unix_now) {
				$start_date = $unix_now;
				$reset_traffic = true;
			}
		}

		$next_date = strtotime("+" . $days . " day", $start_date);

		if ($next_date <= $unix_now) {
			$reset_traffic = true;
			$start_date = $unix_now;
		}

		if ($next_date <= $start_date) {
			$value_trafic = ($reset_traffic ? '0' : $c_traf[0]);
			$start_date = upd_traffictime($value_trafic); // reset traffic
		}
		return $start_date;
	} else {
		return false;
	}
}

function upd_traffictime($cur_traffic) {
	global $options;
	$zone = (3600 * $options['timezone']);

	$fn_trafic = TRAFFIC_LST;
	if (@file_exists($fn_trafic)) {
		$unix_now = strtotime("now") + $zone;
		$value_trafic = $cur_traffic . ":" . $unix_now;
		$ret = @write_traffic($fn_trafic, $value_trafic);
		return $unix_now;
	}
}

function timeremain_traffic($days, $start_date) {
	global $options;
	$zone = (3600 * $options['timezone']);

	$unix_now = strtotime("now") + $zone;
	$next_date = strtotime("+" . $days . " day", $start_date);
	return ( $next_date - $unix_now );
}

// Log System
function chklatesvisitor($curvisit) {
	$err = false;
	$latestvisit = false;
	if (!file_exists(LASTLOG_LST)) @touch(LASTLOG_LST);
	//Check is this user the last IP
	if (@file_exists(LASTLOG_LST)) {
		$fl = fopen(LASTLOG_LST, 'r');
		$buftxt = @fread($fl, @filesize(LASTLOG_LST));
		fclose($fl);
		if ($buftxt != "") {
			if ($curvisit === $buftxt) {
				$latestvisit = true;
			}
		}
	} else {
		$err = true;
	}
	if ($err) {
		echo "<br />an error occurs with log system.";
	}
	return $latestvisit;
}

function saveLogsys($curvisit) {
	global $ipmu, $ref, $options;
	if (!file_exists(LOG_PHP)) @touch(LOG_PHP);

	if (@file_exists(LOG_PHP)) {
		// Get current content
		$fl = fopen(LOG_PHP, 'r');
		$buftxt = @fread($fl, @filesize(LOG_PHP));
		fclose($fl);

		$buftxt = cut_str($buftxt, "<isi id='isilog'>", "</isi>");
		$_php = "<?php if(!defined(\"RAPIDLEECH\")){\n require_once(\"404.php\");exit;\n}?>\n";
		//$style = "$_php\n<style>\nbody{\nfont-family:verdana;\n font-size:10px;\n color:#FFFFFF;\n background-color:#010e17;\n background-image:url(background_pm.gif);\n background-repeat:repeat-x;\n}\n.g{color:#00FF00;}\n.t{color:#00FF00;\nfont-size:14px;}\n</style>\n";
		$style = "$_php\n\n";
		$h = $options['timezone']; //  GMT+7 for Indonesia.
		$ms = $h * 60 * 60;
		$gmdate = gmdate("d M Y H:i:s", time() + ($ms));
		$time = ('GMT+' . $h);
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$fl2 = fopen(LOG_PHP, 'w+');
		$dash = "";
		for ($i = 0; $i <= 50; $i++) {
			$dash.="-";
		}
		$title = "<b class='t'>Log System <small>(descending sorted)</small></b><br />";
		$towrite =
				"$style<body>$title\n<isi id='isilog'><br />\nDate: $gmdate $time" . ($ref != "" ? "<br />\nRefferer: $ref" : "") .
				"<br />\nBrowser: $agent<br />\nUser IP: <b class='g'>$ipmu</b><br />$dash\n$buftxt</isi></body>";
		fwrite($fl2, $towrite);
		fclose($fl2);
		// Update IP Visitor
		$fl = fopen(LASTLOG_LST, 'w');
		fwrite($fl, $curvisit);
		fclose($fl);
	} else {
		$err = true;
	}
	if ($err) {
		echo "<br />an error occurs with log system.";
	}
}

//---- Log System
//Online User
function GetOnline() {
	if (!file_exists(VISITOR_LST)) @touch(VISITOR_LST);
	if (@file_exists(VISITOR_LST)) {
		$timeoffset = 15; // time offset for online user
		$onlines = file_get_contents(VISITOR_LST);
		$onlineList = unserialize($onlines);
		$online = 0;
		if (is_array($onlineList)) {
			foreach ($onlineList as $time) {
				if (time() - $time < $timeoffset * 60) {
					$online++;
				}
			}
		}
		return $online;
	}
}

function UpdateOnline() {
	global $ipmu;
	if (!file_exists(VISITOR_LST)) @touch(VISITOR_LST);
	if (@file_exists(VISITOR_LST)) {
		$onlines = file_get_contents(VISITOR_LST);
		$onlineList = unserialize($onlines);
		//$onlineList[$_SERVER['REMOTE_ADDR']] = time();
		$onlineList[$ipmu] = time();
		$content = serialize($onlineList);
		file_put_contents(VISITOR_LST, $content);
	}
}

//--End Online User

function ongoingAdd() {
	global $onGoing;
	if (!file_exists(ONGOING_LST)) @touch(ONGOING_LST);
	if (@file_exists(ONGOING_LST)) {
		$ongoings = file_get_contents(ONGOING_LST);
		(is_numeric($ongoings) ? $ongoings++ : $ongoings = 1);
		file_put_contents(ONGOING_LST, $ongoings);
	}
	$onGoing = true;
}

function ongoingGet() {
	if (@file_exists(ONGOING_LST)) {
		$ongoings = file_get_contents(ONGOING_LST);
		return (int) $ongoings;
	}
}

function ongoingRemove() {
	global $onGoing;
	if (@file_exists(ONGOING_LST)) {
		$ongoings = file_get_contents(ONGOING_LST);
		(is_numeric($ongoings) ? ($ongoings > 0 ? $ongoings-- : 0) : $ongoings = 0);
		file_put_contents(ONGOING_LST, $ongoings);
	}
	$onGoing = false;
}

function write_traffic($fname, $isi) {
	$fl = fopen($fname, "w");
	if (!$fl) {
		return FALSE;
	} else {
		if (!flock($fl, LOCK_EX)) {
			return FALSE;
		} else {
			if (!fwrite($fl, $isi)) {
				return FALSE;
			} else {
				if (!flock($fl, LOCK_UN)) {
					return FALSE;
				} else {
					if (!fclose($fl)) {
						return FALSE;
					}
				}
			}
		}
	}
	//@fclose($fname);
	return TRUE;
}

#============================

function updateCozEmpty($f) {
	global $list;
	updateListInFile($list);
}

function getBigFilesize($file) {
	$INT = 4294967295; //2147483647+2147483647+1;
	$size = filesize($file);
	$fp = @fopen($file, 'r');
	if ($fp) {
		fseek($fp, 0, SEEK_END);
		if (ftell($fp) == 0) {
			$size += $INT;
		}
		if ($size < 0) {
			$size += $INT;
		}
		@fclose($fp);
	}
	return $size;
}

// Load language in language folder; get css type; return array
function getArrayfromfile($loc, $predmatch, $succmatch, $find='file') {
	if ($loc == '') {
		return false;
	}
	$_CONSTANTS['D_DIR'] = $loc;
	$cleanfn = array();
	$dir = @dir($_CONSTANTS['D_DIR']);
	if ($dir) {
		while (false !== ($file = $dir->read())) {
			switch ($find) {
				case 'file':
					if ($file != "." && $file != ".." && is_file($_CONSTANTS['D_DIR'] . $file)) {
						preg_match("/$predmatch([a-zA-Z0-9_]+)$succmatch/", $file, $match);
						if (count($match) > 0) {
							$cleanfn[] = $match[1];
						}
					}
					break;
				case 'dir':
					if ($file != "." && $file != ".." && is_dir($_CONSTANTS['D_DIR'] . $file)) {
						preg_match("/$predmatch([a-zA-Z0-9_]+)$succmatch/", $file, $match);
						if (count($match) > 0) {
							$cleanfn[] = $match[1];
						}
					}
					break;
			}
		}
	}
	return $cleanfn;
}

// This generate for pointboost manner
function genReload($dlink, $dlay, $cktahu, $numindex) {
	$nn = "\r\n";
	$html_form = $nn . '<div style="padding-top:10px;"><b>Pointboost mode-<span class="g">ON</span></b><br /><small>This page will reload in [<b id="tmr" class="g">~</b>] seconds</small></div>';
	$html_form .= '<input type="button" name="reboost" value=" GO " onclick="document.frmbooster.submit();">';
	$html_form .= '<div style="display:none;">' . $nn . '<form name="frmbooster" action="' . $PHP_SELF . '" method="post">';
	$html_form .= $nn . '<input type="hidden" name="link" id="link" value="' . $dlink . '">';
	$html_form .= $nn . '<input type="hidden" name="numidx" id="numidx" value="' . (int) $numindex . '">';
	$rnum = rand(11, 99); // $cktahu encoded base64
	$html_form .= $nn . '<input type="hidden" name="sssid" value="' . encEnti(rotN($cktahu, $rnum)) . $rnum . '">';
	$html_form .= '</form></div><br />';
	$html_form .= counteritung('frmbooster', $dlay);
	return $html_form;
}

// for debug manner, dump array
function vdump($varray = array(), $textarea=false, $with_br=true) {
	$buf = print_r($varray, true);
	if ($textarea) {
		$buf = "<textarea cols='90' rows='20' style='width:100%;font-size:11px;line-height:10px;'>" . $buf . "</textarea>";
	} else {
		$buf = ($with_br ? str_replace("\n", "<br />", $buf) : $buf);
	}
	print_r($buf);
}

?>