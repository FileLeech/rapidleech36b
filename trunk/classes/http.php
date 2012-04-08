<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

/**
 * Pauses for countdown timer in file hosts
 * @param int The number of seconds to count down
 * @param string The text you want to display when counting down
 * @param string The text you want to display when count down is complete
 * @param bool
 * @return bool
 */
function insert_timer($countd, $caption = "", $timeouttext = "", $hide = false) {
	global $disable_timer, $L;

	if ($disable_timer === true) return true;
	if (!$countd || !is_numeric($countd)) return false;

	$timerid = rand(1000, TIME_NOW);
	echo ('<div align="center">');
	echo ('<span id="global' . $timerid . '">');
	echo ('<br />');
	echo ('<span class="caption">' . $caption . '</span>&nbsp;&nbsp;');
	echo ('<span id="timerlabel' . $timerid . '" class="caption"></span></span>');
	echo ('</div>');
	echo ('<script type="text/javascript">');
	echo ('var count' . $timerid . '=' . $countd . ';');
	echo ('function timer' . $timerid . '() {');
	echo ('if(count' . $timerid . ' > 0) {');
	echo ('$("#timerlabel' . $timerid . '").html("' . $L->sprintf($L->say['_pwait_sec'], '" + count' . $timerid . ' + "') . '");');
	echo ('count' . $timerid . '--;');
	echo ('setTimeout("timer' . $timerid . '()", 1000);');
	echo ('}');
	echo ('}');
	echo ('timer' . $timerid . '();');
	echo ('</script>');
	flush();
	for ($nnn = 0; $nnn < $countd; $nnn++) {
		sleep(1);
	}
	flush();

	if ($hide === true) {
		echo ('<script type="text/javascript">$("#global' . $timerid . '").css("display","none");</script>');
		flush();
		return true;
	}

	if ($timeouttext) {
		echo ('<script type="text/javascript">$("#global' . $timerid . '").html("' . $timeouttext . '");</script>');
		flush();
		return true;
	}
	return true;
}

/**
 * Counter for those filehosts that displays mirror after countdown
 * @param int The number of seconds to count down
 * @param string Text you want to display above the counter
 * @param string The text you want to display when counting down
 * @param string The text you want to display when count down is complete
 */
function insert_new_timer($countd, $displaytext, $caption = "", $text = "") {
	global $L;
	if (!is_numeric($countd)) {
		html_error($L->say['_wrong_counter']);
	}
	echo ('<div id="code"></div>');
	echo ('<div align="center">');
	echo ('<div id="dl"><h4>' . $L->say['js_disable'] . '</h4></div></div>');
	echo ('<script type="text/javascript">var c = ' . $countd . ';fc("' . $caption . '","' . $displaytext . '");</script>');
	if (!empty($text)) {
		print $text;
	}
	echo ('</body></html>');
}

/**
 * Function to check if geturl function has completed successfully
 */
function is_page($lpage) {
	global $L, $lastError;
	if (!$lpage) {
		html_error($L->say['_error_retrieve'] . "<br />$lastError");
	}
}

function geturl($host, $port, $url, $referer = 0, $cookie = 0, $post = 0, $saveToFile = 0, $proxy = 0, $pauth = 0, $auth = 0, $scheme = "http", $resume_from = 0, $XMLRequest=0) {
	global $nn, $lastError, $PHP_SELF, $AUTH, $IS_FTP, $FtpBytesTotal, $FtpBytesReceived, $FtpTimeStart, $FtpChunkSize, $Resume, $bytesReceived, $fs, $force_name, $options, $L, $ipmu, $ada_acc;

	$scheme .= "://";
	if (($post !== 0) && ($scheme == "http://" || $scheme == "https://")) {
		$method = "POST";
		$postdata = formpostdata($post);
		$length = strlen($postdata);
		$content_tl = "Content-Type: application/x-www-form-urlencoded" . $nn . "Content-Length: " . $length . $nn;
	} else {
		$method = "GET";
		$postdata = "";
		$content_tl = "";
	}

	$cookies = '';
	if ($cookie) {
		if (is_array($cookie)) {
			if (count($cookie) > 0) $cookies = "Cookie: " . CookiesToStr($cookie) . $nn;
		} else {
			$cookies = "Cookie: " . trim($cookie) . $nn;
		}
	}
	$referer = $referer ? "Referer: " . $referer . $nn : "";
	if ($scheme == "https://") {
		$scheme = "ssl://";
		$port = 443;
	}

	if ($proxy) {
		list ( $proxyHost, $proxyPort ) = explode(":", $proxy);
		$host = $host . ($port != 80 && $port != 443 ? ":" . $port : "");
		$url = $scheme . $host . $url;
	} else {
		$proxyHost = '';
		$proxyPort = '';
	}

	if ($scheme != "ssl://") {
		$scheme = "";
	}
	$http_auth = (!empty($auth)) ? "Authorization: Basic " . $auth . $nn : "";
	$proxyauth = (!empty($pauth)) ? "Proxy-Authorization: Basic " . $pauth . $nn : "";

	$request = $method . " " . str_replace(" ", "%20", $url) . " HTTP/1.1" . $nn . "Host: " . $host . $nn . "User-Agent: Opera/9.80 (Windows NT 6.1; U; en-US) Presto/2.10.229 Version/11.61" . rand(5, 20) . $nn . "Accept: */*" . $nn . "Accept-Language: en-us;q=0.7,en;q=0.3" . $nn . "Accept-Charset: utf-8,windows-1251;q=0.7,*;q=0.7" . $nn . "Pragma: no-cache" . $nn . "Cache-Control: no-cache" . $nn . ($Resume ["use"] === TRUE ? "Range: bytes=" . $Resume ["from"] . "-" . $nn : "") . $http_auth . $proxyauth . $referer . ($XMLRequest ? "X-Requested-With: XMLHttpRequest" . $nn : "") . $cookies . "Connection: Close" . $nn . $content_tl . $nn . $postdata;

	if (isset($options['mip_enabled']) && $options['mip_enabled']) {
		$mip_action = "download";
		echo "<p>Multi IP Enabled</b>...<br />\n";
		if (file_exists(MISC_DIR . "mip.php")) @include(MISC_DIR . "mip.php");
	}else {
		$errno = 0;
		$errstr = "";
		$hosts = ($proxyHost ? $scheme . $proxyHost : $scheme . $host) . ':' . ($proxyPort ? $proxyPort : $port);
		$fp = @stream_socket_client($hosts, $errno, $errstr, 120, STREAM_CLIENT_CONNECT);
	}
	if (!$fp) {
		$dis_host = $proxyHost ? $proxyHost : $host;
		$dis_port = $proxyPort ? $proxyPort : $port;
		html_error($L->sprintf($L->say['_couldnt_con_to'], $dis_host, $dis_port));
	}
	if ($errno || $errstr) {
		$lastError = $errstr;
		return false;
	}
	if ($saveToFile) {
		if ($proxy) {
			echo '<p>' . $L->sprintf($L->say['_con_proxy'], $proxyHost, $proxyPort) . '<br />';
			echo "GET: <b>" . $url . "</b>...<br />\n";
		} else {
			echo "<p>";
			echo $L->sprintf($L->say['_con_to'], $host, $port) . '<br />';
			echo (isset($options['mip_enabled']) && $options['mip_enabled'] ? "using IP: " . $mip_ip . "<br />\n" : "");
		}
	}

	#########################################################################
	fputs($fp, $request);
	fflush($fp);
	$timeStart = getmicrotime();

	// Rewrote the get header function according to the proxy script
	// Also made sure it goes faster and I think 8192 is the best value for retrieving headers
	// Oops.. The previous function hooked up everything and now I'm returning it back to normal
	$llen = 0;
	$header = '';
	do {
		$header .= fgets($fp, 16384);
		$len = strlen($header);
		if (!$header || $len == $llen) {
			$lastError = $L->say['_noheader'];
			return false;
		}
		$llen = $len;
	} while (strpos($header, $nn . $nn) === false);
	#########################################################################
	if ($saveToFile) {
		if (!isset($_GET['dis_plug']) || $_GET ['dis_plug'] != "on") {
			foreach ($GLOBALS['host'] as $site => $file) {
				if (preg_match("/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}(:\d+)?$/", $host)) break;
				if (preg_match("/^(.+\.)?" . str_replace('.', '\.', $site) . "(:\d+)?$/i", $host)) {
					require_once (HOST_DIR . "DownloadClass.php");
					require_once (HOST_DIR . 'download/' . $file);
					$class = substr($file, 0, -4);
					$firstchar = substr($file, 0, 1);
					if ($firstchar > 0) $class = "d" . $class;
					if (class_exists($class) && method_exists($class, 'CheckBack')) {
						$hostClass = new $class();
						$hostClass->CheckBack($header);
					}
				}
			}
		}

		if (preg_match('/^HTTP\/1\.(?:0|1) ([0-9]+) .*/', $header, $responsecode) && ($responsecode[1] == 404 || $responsecode[1] == 403)) {
			// Do some checking, please, at least tell them what error it was
			if ($responsecode [1] == 403) {
				$lastError = $L->say['_page_notfound'];
			} elseif ($responsecode [1] == 404) {
				$lastError = $L->say['_forbidden_access'];
			} else {
				// Weird, it shouldn't come here...
				$lastError = $L->say['_notfound_forbidden'];
			}
			return false;
		}
		$bytesTotal = trim(cut_str($header, "Content-Length:", "\n"));
		if ($options['limitsize'] > 0) {
			if ($bytesTotal > $options['limitsize'] * 1024 * 1024) {
				$lastError = $L->sprintf($L->say['_sorry_tobig'], bytesToKbOrMbOrGb($bytesTotal), $options["limitsize"]);
				return false;
			}
		}
		if ($options['lowlimitsize'] > 0) {
			if ($bytesTotal < $options['lowlimitsize'] * 1024 * 1024) {
				$lastError = $L->sprintf($L->say['_sorry_tosmall'], bytesToKbOrMbOrGb($bytesTotal), $options["lowlimitsize"]);
				return false;
			}
		}
		// check storage limit (it is in MB)
		if ($options['storage_limit'] > 0) {
			$serverfiles = calcUsedSpace();
			if (($serverfiles + $bytesTotal) > $options['storage_limit'] * 1024 * 1024) {
				$lastError = $L->sprintf($L->say['_sorry_insuficient_storage'], bytesToKbOrMbOrGb($serverfiles), $options["storage_limit"]);
				return false;
			}
		}

		if ($options['limitbyip']) {
			if ($ada_acc) {
				global $premium_acc;

				$ahost = $host;
				$ar_host = explode(".", $ahost);
				$_host = ''; $pjg = 0;
				for ($i = 0; $i < count($ar_host); $i++) {
					if ($pjg < 2) {
						$_host = $ar_host[count($ar_host) - $i - 1] . ($i == 0 ? "" : ".") . $_host;
					}
					$pjg++;
				}
				$hostmatch = str_replace('.', '_', $_host);
				$dumhost = array(); $i = 0;
				foreach ($GLOBALS['host'] as $dh => $flnya) {
					$dumhost[$i] = $dh; $i++;
				}
				$host = $ahost;
				if (in_array($_host, $dumhost) && isset($premium_acc["$hostmatch"])) {
					$fname = basename($saveToFile) . "." . rand(1, 1000);
					$limitedfile = "./tmp/$ipmu.$fname";
					$ret = mkdir("$limitedfile", 0777);
				}
			} //-- ada_acc
		} //-- limitbyip

		$redir = "";
		if (trim(preg_match('/[^\-]Location: *(.+)(\r|\n)+/i', $header, $redir))) {
			$redirect = $redir [1];
			$lastError = $L->sprintf($L->say['_error_redirectto'], $redirect);
			return FALSE;
		}
		if (in_array(cut_str($header, "WWW-Authenticate: ", " "), array("Basic", "Digest"))) {
			$lastError = $L->say['_req_auth'];
			return FALSE;
		}
		$ContentType = trim(cut_str($header, "Content-Type:", "\n"));
		if ($Resume ["use"] === TRUE && !stristr($header, "Content-Range:")) {
			if (stristr($header, "503 Limit Exceeded")) {
				$lastError = $L->say['_error_resume'];
			} else {
				$lastError = $L->say['_error_noresume'];
			}
			return FALSE;
		}
		if ($force_name) {
			$FileName = $force_name;
			$saveToFile = dirname($saveToFile) . PATH_SPLITTER . $FileName;
		} else {
			$ContentDisposition = trim(cut_str($header, "Content-Disposition:", "\n")) . "\n";
			if ($ContentDisposition && stripos($ContentDisposition, "filename=") !== false) {
				$FileName = trim(trim(trim(trim(trim(cut_str($ContentDisposition, "filename=", "\n")), "="), "?"), ";"), '"');
				if (strpos($FileName, "/") !== false) $FileName = basename($FileName);
				if (preg_match("/UTF\-8\?B\?(.*)$/i", $FileName, $b64)) $FileName = preg_replace("/[^a-zA-Z0-9\-\.]/", "_", base64_decode($b64[1]));
				$saveToFile = dirname($saveToFile) . PATH_SPLITTER . $FileName;
			}
		}

		if (!empty($options["add_ext_5city"]) || !empty($options['rename_suffix']) || !empty($options['rename_prefix']) || $options['rename_underscore']) {
			if (!empty($options["add_ext_5city"])) {
				$ext = str_replace(".", "", $options["add_ext_5city"]);
				$File_Name = basename($saveToFile) . "." . $options["add_ext_5city"];
			}
			if (!empty($options['rename_prefix'])) {
				$File_Name = $options['rename_prefix'] . '_' . basename($saveToFile);
			}
			if (!empty($options['rename_suffix'])) {
				$ext = strrchr(basename($saveToFile), ".");
				$before_ext = explode($ext, basename($saveToFile));
				$File_Name = $before_ext [0] . '_' . $options['rename_suffix'] . $ext;
			}
			if ($options['rename_underscore']) {
				$File_Name = str_replace(array(' ', '%20'), '_', basename($saveToFile));
			}
			$saveToFile = dirname($saveToFile) . PATH_SPLITTER . $File_Name;
		}
		$filetype = strrchr($saveToFile, ".");
		if (is_array($options['forbidden_filetypes']) && in_array(strtolower($filetype), $options['forbidden_filetypes'])) {
			if ($options['forbidden_filetypes_block']) {
				$lastError = $L->sprintf($L->say['_forbid_filetype'], $filetype);
				return false;
			} else {
				$saveToFile = str_replace($filetype, $options['rename_these_filetypes_to'], $saveToFile);
			}
		}
		if (@file_exists($saveToFile) && $options['bw_save']) {
			html_error($L->say['_download'] . ': ' . link_for_file($saveToFile), 0);
		}
		if (@file_exists($saveToFile) && $Resume ["use"] === TRUE) {
			$fs = @fopen($saveToFile, "ab");
			if (!$fs) {
				$lastError = $L->sprintf($L->say['_error_cantsave'], basename($saveToFile)) . '<br />' . $L->say['_error_trychmod'] . '<br /><a href="javascript:location.reload();">' . $L->say['_error_tryagain'] . '</a>';
				return FALSE;
			}
		} else {
			if (@file_exists($saveToFile)) {
				$saveToFile = dirname($saveToFile) . PATH_SPLITTER . TIME_NOW . "_" . basename($saveToFile);
			}
			$fs = @fopen($saveToFile, "wb");
			if (!$fs) {
				$secondName = dirname($saveToFile) . PATH_SPLITTER . str_replace(":", "", str_replace("?", "", basename($saveToFile)));
				$fs = @fopen($secondName, "wb");
				if (!$fs) {
					$lastError = $L->sprintf($L->say['_error_cantsave'], basename($saveToFile)) . '<br />' . $L->say['_error_trychmod'] . '<br /><a href="javascript:location.reload();">' . $L->say['_error_tryagain'] . '</a>';
					return FALSE;
				}
			}
		}
		flock($fs, LOCK_EX);
		if ($Resume ["use"] === TRUE && stristr($header, "Content-Range:")) {
			list ( $temp, $Resume ["range"] ) = explode(" ", trim(cut_str($header, "Content-Range:", "\n")));
			list ( $Resume ["range"], $fileSize ) = explode("/", $Resume ["range"]);
			$fileSize = bytesToKbOrMbOrGb($fileSize);
		} else {
			$fileSize = bytesToKbOrMbOrGb($bytesTotal);
		}
		$chunkSize = GetChunkSize($bytesTotal);
		$File_Name = basename($saveToFile);
		if (!empty($options["add_ext_5city"])) {
			$ext = "." . get_extension(basename($saveToFile));
			$File_Name = str_replace($ext, "", basename($saveToFile));
		}
		echo($L->sprintf($L->say['_saveprogres'], $File_Name, $ext, $fileSize) . '<br />');
		//$scriptStarted = false;
		require (TEMPLATE_DIR . 'transloadui.php');
		if ($Resume ["use"] === TRUE) {
			$received = bytesToKbOrMbOrGb(filesize($saveToFile));
			$percent = round($Resume ["from"] / ($bytesTotal + $Resume ["from"]) * 100, 2);
			echo "<script type='text/javascript'>pr('" . $percent . "', '" . $received . "', '0');</script>";
			//$scriptStarted = true;
			flush();
		}
	} else {
		$page = "";
	}
	$time = $last = $lastChunkTime = 0;
	do {
		$data = @fread($fp, ($saveToFile ? $chunkSize : 16384)); // 16384 saw this value in Pear HTTP_Request2 package // (fix - szal) using this actually just causes massive cpu usage for large files, too much data is flushed to the browser!)
		//$data = @fread ( $fp, ($saveToFile ? $chunkSize : 8192) );	// 16384 saw this value in Pear HTTP_Request2 package // (fix - szal) using this actually just causes massive cpu usage for large files, too much data is flushed to the browser!)
		if ($data == '') break;
		if ($saveToFile) {
			$bytesSaved = fwrite($fs, $data);
			if ($bytesSaved > - 1) {
				$bytesReceived += $bytesSaved;
			} else {
				$lastError = $L->sprintf($L->say['_error_imposible_record'], $saveToFile);
				return false;
			}
			if ($bytesReceived >= $bytesTotal) {
				$percent = 100;
			} else {
				$percent = @round(($bytesReceived + $Resume ["from"]) / ($bytesTotal + $Resume ["from"]) * 100, 2);
			}
			if ($bytesReceived > $last + $chunkSize) {
				$received = bytesToKbOrMbOrGb($bytesReceived + $Resume ["from"]);
				$time = getmicrotime() - $timeStart;
				$chunkTime = $time - $lastChunkTime;
				$chunkTime = $chunkTime ? $chunkTime : 1;
				$lastChunkTime = $time;
				$speed = @round($chunkSize / 1024 / $chunkTime, 2);
				/* if (!$scriptStarted) {
				  echo('<script type="text/javascript">');
				  $scriptStarted = true;
				  } */
				echo "<script type='text/javascript'>pr('" . $percent . "', '" . $received . "', '" . $speed . "');</script>";
				$last = $bytesReceived;
			}
		} else {
			$page .= $data;
		}
	} while (strlen($data) > 0);
	//echo('</script>');
	if ($saveToFile) {
		flock($fs, LOCK_UN);
		fclose($fs);
		if ($bytesReceived <= 0) {
			$lastError = $L->say['_error_misc'];
			fclose($fp);
			return FALSE;
		}
	}
	fclose($fp);
	if ($saveToFile) {
		return array("time" => sec2time(round($time)), "speed" => @round($bytesTotal / 1024 / (getmicrotime() - $timeStart), 2), "received" => true, "size" => $fileSize, "bytesReceived" => ($bytesReceived + $Resume ["from"]), "bytesTotal" => ($bytesTotal + $Resume ["from"]), "file" => $saveToFile);
	} else {
		if (stripos($header, "Transfer-Encoding: chunked") !== false && function_exists('http_chunked_decode')) {
			$dechunked = http_chunked_decode($page);
			if ($dechunked !== false) $page = $dechunked;
			unset($dechunked);
		}
		$page = $header . $page;
		return $page;
	}
}

function cURL($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0, $opts = 0) {
	global $pauth;
	if (!extension_loaded('curl') || !function_exists('curl_init') || !function_exists('curl_exec')) html_error('cURL isn\'t enabled or cURL\'s functions are disabled');
	$arr = explode("\r\n", $referer);
	$header = array();
	if (count($arr) > 1) {
		$referer = $arr[0];
		unset($arr[0]);
		$header = array_filter(array_map('trim', $arr));
	}
	if (is_array($cookie)) {
		if (count($cookie) > 0) $cookie = CookiesToStr($cookie);
		else $cookie = false;
	} else {
		$cookie = $cookie != false ? trim($cookie) : false;
	}
	$opt = array(CURLOPT_HEADER => 1, CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FOLLOWLOCATION => 0, CURLOPT_FAILONERROR => 0,
		CURLOPT_FORBID_REUSE => 1, CURLOPT_FRESH_CONNECT => 1,
		CURLOPT_USERAGENT => "Opera/9.80 (Windows NT 6.1; U; en-US) Presto/2.10.229 Version/11.61");
	if ($referer != false) $opt[CURLOPT_REFERER] = $referer;
	if ($cookie !== false) $opt[CURLOPT_COOKIE] = $cookie;
	// Send more headers...
	$headers = array("Accept-Language: en-us;q=0.7,en;q=0.3", "Accept-Charset: utf-8,windows-1251;q=0.7,*;q=0.7", "Pragma: no-cache", "Cache-Control: no-cache", "Connection: Close");
	if (count($header) > 0) $headers = array_merge($headers, $header);
	$opt[CURLOPT_HTTPHEADER] = $headers;
	if ($post != '0') {
		$opt[CURLOPT_POST] = 1;
		$opt[CURLOPT_POSTFIELDS] = formpostdata($post);
	}
	if ($auth) {
		$opt[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
		$opt[CURLOPT_USERPWD] = base64_decode($auth);
	}
	if (isset($_GET["useproxy"]) && !empty($_GET["proxy"])) {
		$opt[CURLOPT_HTTPPROXYTUNNEL] = true;
		$opt[CURLOPT_PROXY] = $_GET["proxy"];
		if ($pauth) $opt[CURLOPT_PROXYUSERPWD] = base64_decode($pauth);
	}
	$opt[CURLOPT_CONNECTTIMEOUT] = $opt[CURLOPT_TIMEOUT] = 120;
	if (is_array($opts) && count($opts) > 0) foreach ($opts as $O => $V)
			$opt[$O] = $V;

	$link = str_replace(array(" ", "\r", "\n"), array("%20"), $link);
	$ch = curl_init($link);
	foreach ($opt as $O => $V) { // Using this instead of 'curl_setopt_array'
		curl_setopt($ch, $O, $V);
	}
	$page = curl_exec($ch);
	$info = curl_getinfo($ch);
	$errz = curl_errno($ch);
	$errz2 = curl_error($ch);
	curl_close($ch);

	if (!empty($opt[CURLOPT_PROXY])) $page = preg_replace("@^(HTTP/1\.[0-1] \d+ [^\r|\n]+)\r\n\r\n(HTTP/1\.[0-1] \d+ [^\r|\n]+)@i", "$2\r\ncURL-Proxy: $1", $page, 1); // The proxy response header can break some functions in plugins, let move and rename it...
	if ($errz != 0) html_error("[cURL:$errz] $errz2");

	return $page;
}

// This new function requires less line and actually reduces filesize :P
// Besides, using less globals means more variables available for us to use
function formpostdata($post=array()) {
	$postdata = "";
	foreach ($post as $k => $v) {
		$postdata .= "$k=$v&";
	}
	// Remove the last '&'
	$postdata = substr($postdata, 0, - 1);
	return $postdata;
}

// function to reconvert array cookie into string
function CookiesToStr($cookie=array()) {
	if (count($cookie) == 0) return '';
	$cookies = "";
	foreach ($cookie as $k => $v) {
		$cookies .= "$k=$v; ";
	}
	// Remove the last ';'
	$cookies = substr($cookies, 0, -2);
	return $cookies;
}

function GetCookies($content) {
	// The U option will make sure that it matches the first character
	// So that it won't grab other information about cookie such as expire, domain and etc
	preg_match_all('/Set-Cookie: (.*)(;|\r\n)/U', $content, $temp);
	$cookie = $temp [1];
	$cook = implode('; ', $cookie);
	return $cook;
}

/**
 * Function to get cookies & converted into array
 * @param string The content you want to get the cookie from
 * @param array The array cookies to be updated [optional]
 * @param bool Options to remove temporary cookie (usually it named as 'deleted') [optional]
 * @param mixed The default name for temporary cookie, values are accepted in an array [optional]
 */
function GetCookiesArr($content, $cookie=array(), $del=true, $dval='deleted') {
	if (!is_array($cookie)) $cookie = array();
	if (!preg_match_all('/Set-Cookie: (.*)(;|\r\n)/U', $content, $temp)) return $cookie;
	foreach ($temp[1] as $v) {
		$v = explode('=', $v, 2);
		$cookie[$v[0]] = $v[1];
		if ($del) {
			if (!is_array($dval)) $dval = array($dval);
			foreach ($dval as $dv)
				if ($v[1] == $dv) unset($cookie[$v[0]]);
		}
	}
	return $cookie;
}

// function to convert a string of cookies into an array
function StrToCookies($cookies='', $cookie=array(), $del=true, $dval='deleted') {
	if (!is_array($cookie)) $cookie = array();
	$cookies = trim($cookies);
	if (empty($cookies)) return $cookie;
	foreach (array_filter(array_map('trim', explode(';', $cookies))) as $v) {
		$v = array_map('trim', explode('=', $v, 2));
		$cookie[$v[0]] = $v[1];

		if ($del) {
			if (!is_array($dval)) $dval = array($dval);
			foreach ($dval as $dv)
				if ($v[1] == $dv) unset($cookie[$v[0]]);
		}
	}
	return $cookie;
}

function GetChunkSize($fsize) {
	if ($fsize <= 1024 * 1024) {
		return 4096;
	}
	if ($fsize <= 1024 * 1024 * 10) {
		return 4096 * 10;
	}
	if ($fsize <= 1024 * 1024 * 40) {
		return 4096 * 30;
	}
	if ($fsize <= 1024 * 1024 * 80) {
		return 4096 * 47;
	}
	if ($fsize <= 1024 * 1024 * 120) {
		return 4096 * 65;
	}
	if ($fsize <= 1024 * 1024 * 150) {
		return 4096 * 70;
	}
	if ($fsize <= 1024 * 1024 * 200) {
		return 4096 * 85;
	}
	if ($fsize <= 1024 * 1024 * 250) {
		return 4096 * 100;
	}
	if ($fsize <= 1024 * 1024 * 300) {
		return 4096 * 115;
	}
	if ($fsize <= 1024 * 1024 * 400) {
		return 4096 * 135;
	}
	if ($fsize <= 1024 * 1024 * 500) {
		return 4096 * 170;
	}
	if ($fsize <= 1024 * 1024 * 1000) {
		return 4096 * 200;
	}
	return 4096 * 210;
}

function upfile($host, $port, $url, $referer, $cookie, $post, $file, $filename, $fieldname, $field2name = "", $proxy = 0, $pauth = 0, $upagent = "Opera/9.80 (Windows NT 6.1; U; en-US) Presto/2.10.229 Version/11.61") {
	global $nn, $lastError, $sleep_time, $sleep_count, $L;

	$scheme = "http://";
	$bound = "--------" . md5(microtime());
	$saveToFile = 0;

	$postdata = "";
	if ($post) {
		foreach ($post as $key => $value) {
			$postdata .= "--" . $bound . $nn;
			$postdata .= 'Content-Disposition: form-data; name="' . $key . '"' . $nn . $nn;
			$postdata .= $value . $nn;
		}
	}
	$fileSize = getSize($file);
	$fieldname = $fieldname ? $fieldname : file . md5($filename);
	if (!is_readable($file)) {
		$lastError = $L->sprintf($L->say['_error_readfile'], $file);
		return FALSE;
	}
	if ($field2name != '') {
		$postdata .= "--" . $bound . $nn;
		$postdata .= "Content-Disposition: form-data; name=\"$field2name\"; filename=\"\"" . $nn;
		$postdata .= "Content-Type: application/octet-stream" . $nn . $nn;
	}
	$postdata .= "--" . $bound . $nn;
	$postdata .= "Content-Disposition: form-data; name=\"$fieldname\"; filename=\"$filename\"" . $nn;
	$postdata .= "Content-Type: application/octet-stream" . $nn . $nn;

	$cookies = "";
	if ($cookie) {
		if (is_array($cookie)) {
			if (count($cookie) > 0) $cookies = "Cookie: " . CookiesToStr($cookie) . $nn;
		} else {
			$cookies = "Cookie: " . trim($cookie) . $nn;
		}
	}
	$referer = $referer ? "Referer: " . $referer . $nn : "";
	if ($scheme == "https://") {
		$scheme = "ssl://";
		$port = 443;
	}
	if ($proxy) {
		list ( $proxyHost, $proxyPort ) = explode(":", $proxy);
		$host = $host . ($port != 80 && $port != 443 ? ":" . $port : "");
		$url = $scheme . $host . $url;
	}
	if ($scheme != "ssl://") {
		$scheme = "";
	}
	$http_auth = (!empty($auth)) ? "Authorization: Basic " . $auth . $nn : "";
	$proxyauth = (!empty($pauth)) ? "Proxy-Authorization: Basic " . $pauth . $nn : "";

	$zapros = "POST " . str_replace(" ", "%20", $url) . " HTTP/1.0" . $nn . "Host: " . $host . $nn . $cookies . "Content-Type: multipart/form-data; boundary=" . $bound . $nn . "Content-Length: " . (strlen($postdata) + strlen($nn . "--" . $bound . "--" . $nn) + $fileSize) . $nn . "User-Agent: " . $upagent . $nn . "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5" . $nn . "Accept-Language: en-en,en;q=0.5" . $nn . "Accept-Charset: utf-8,windows-1251;koi8-r;q=0.7,*;q=0.7" . $nn . "Connection: Close" . $nn . $http_auth . $proxyauth . $referer . $nn . $postdata;
	$errno = 0; $errstr = "";
	$posturl = (!empty($proxyHost) ? $scheme . $proxyHost : $scheme . $host) . ':' . (!empty($proxyPort) ? $proxyPort : $port);
	$fp = @stream_socket_client($posturl, $errno, $errstr, 120, STREAM_CLIENT_CONNECT);
	//$fp = @fsockopen ( $host, $port, $errno, $errstr, 150 );
	//stream_set_timeout ( $fp, 300 );
	if (!$fp) {
		$dis_host = $proxyHost ? $proxyHost : $host;
		$dis_port = $proxyPort ? $proxyPort : $port;
		html_error($L->sprintf($L->say['_couldnt_con_to'], $dis_host, $dis_port));
	}
	if ($errno || $errstr) {
		$lastError = $errstr;
		return false;
	}
	if ($proxy) {
		echo '<p>' . $L->sprintf($L->say['_con_proxy'], $proxyHost, $proxyPort) . '<br />';
		echo "UPLOAD: <b>" . $url . "</b>...<br />\n";
	} else {
		echo "<p>";
		echo $L->sprintf($L->say['_con_to'], $host, $port);
		echo "</p>";
	}
	echo($L->sprintf($L->say['_uplprogres'], $filename, bytesToKbOrMb($fileSize)) . '<br />');
	global $id;
	$id = md5(TIME_NOW * rand(0, 10));
	require (TEMPLATE_DIR . 'uploadui.php');
	flush();
	$timeStart = getmicrotime();
	//$chunkSize = 16384;		// Use this value no matter what (using this actually just causes massive cpu usage for large files, too much data is flushed to the browser!)
	$chunkSize = GetChunkSize($fileSize);
	fputs($fp, $zapros);
	fflush($fp);
	$fs = fopen($file, 'r');
	$local_sleep = $sleep_count;
	//echo('<script type="text/javascript">');
	$totalsend = $time = $lastChunkTime = 0;
	while (!feof($fs)) {
		$data = fread($fs, $chunkSize);
		if ($data === false) {
			fclose($fs);
			fclose($fp);
			html_error($L->say['_error_readdata']);
		}

		if (($sleep_count !== false) && ($sleep_time !== false) && is_numeric($sleep_time) && is_numeric($sleep_count) && ($sleep_count > 0) && ($sleep_time > 0)) {
			$local_sleep--;
			if ($local_sleep == 0) {
				usleep($sleep_time);
				$local_sleep = $sleep_count;
			}
		}

		$sendbyte = fputs($fp, $data);
		fflush($fp);

		if ($sendbyte === false) {
			fclose($fs);
			fclose($fp);
			html_error($L->say['_error_senddata']);
		}

		$totalsend += $sendbyte;

		$time = getmicrotime() - $timeStart;
		$chunkTime = $time - $lastChunkTime;
		$chunkTime = $chunkTime ? $chunkTime : 1;
		$lastChunkTime = $time;
		$speed = round($sendbyte / 1024 / $chunkTime, 2);
		$percent = round($totalsend / $fileSize * 100, 2);
		echo "<script type='text/javascript'>pr('" . $percent . "', '" . bytesToKbOrMb($totalsend) . "', '" . $speed . "');</script>\n";
		flush();
	}
	//echo('</script>');
	fclose($fs);

	fputs($fp, $nn . "--" . $bound . "--" . $nn);
	fflush($fp);

	$page = "";
	while (!feof($fp)) {
		$data = fgets($fp, 16384);
		if ($data === false) {
			break;
		}
		$page .= $data;
	}

	fclose($fp);

	return $page;
}

?>