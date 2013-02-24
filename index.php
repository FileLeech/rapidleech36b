<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: index.php - 05apr2010-Idx $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";

define('BUILD', '23082007');
define('CREDITS', '<a href="http://www.rapidleech.com/" style="text-decoration:none"><b>RL</b></a>&nbsp;<b class="footer1">PlugMod(eqbal) rev. ' . $rev_num . '</b> <span class="footer2">' . $dev_name . '</span><br /><small class="footer2">Credits to Pramode &amp; Checkmate &amp; Kloon. Mod by: MsNeil &amp; Idoenk</small>');
define('VERSION', '<small class="footer3"><span>[ olc | ccpb | kaskus ]</span><br />[ Shout: Viant77, Jujuan, Huka, Limpy ]</small>');

//************************
// MAIN ...
//************************

$_REQUEST['premium_acc'] = $_POST['premium_acc'] = isset($_REQUEST['premium_acc']) && $_REQUEST['premium_acc'] == 'on' ? 'on' : false;
$_REQUEST['df_acc'] = $_POST['df_acc'] = isset($_REQUEST['df_acc']) && $_REQUEST['df_acc'] == 'on' ? 'on' : false;
$_REQUEST['hf_acc'] = $_POST['hf_acc'] = isset($_REQUEST['hf_acc']) && $_REQUEST['hf_acc'] == 'on' ? 'on' : false;
$_REQUEST['net_acc'] = $_POST['net_acc'] = isset($_REQUEST['net_acc']) && $_REQUEST['net_acc'] == 'on' ? 'on' : false;
$_REQUEST['rs_acc'] = $_POST['rs_acc'] = isset($_REQUEST['rs_acc']) && $_REQUEST['rs_acc'] == 'on' ? 'on' : false;
$_REQUEST['ul_acc'] = $_POST['ul_acc'] = isset($_REQUEST['ul_acc']) && $_REQUEST['ul_acc'] == 'on' ? 'on' : false;
$_REQUEST['upl_acc'] = $_POST['upl_acc'] = isset($_REQUEST['upl_acc']) && $_REQUEST['upl_acc'] == 'on' ? 'on' : false;
$_REQUEST['cookieuse'] = $_POST['cookieuse'] = isset($_REQUEST['cookieuse']) && $_REQUEST['cookieuse'] == 'on' ? 'on' : false;

foreach ($_POST as $key => $value) {
	$_GET[$key] = $value;
}

if (!$_COOKIE) {
	if (isset($_SERVER['HTTP_COOKIE']) && strpos($_SERVER['HTTP_COOKIE'], ';') !== false) {
		foreach (explode('; ', $_SERVER['HTTP_COOKIE']) as $key => $value) {
			list ($var, $val) = explode('=', $value);
			$_COOKIE[$var] = $val;
		}
	} elseif (!empty($_SERVER['HTTP_COOKIE'])) {
		list($var, $val) = @explode('=', $_SERVER['HTTP_COOKIE']);
		$_COOKIE[$var] = $val;
	}
}

require_once(CLASS_DIR . 'cookie.php');

if (!@file_exists(HOST_DIR . 'download/hosts.php')) html_error($L->say['_nohost']);

// require "hosts.php";
require_once(HOST_DIR . 'download/hosts.php');

if (!empty($_GET['image'])) {
	require_once(CLASS_DIR . 'http.php');
	require_once(CLASS_DIR . 'image.php');
	exit();
}

if (isset($_GET['useproxy']) && (empty($_GET['proxy']) || strpos($_GET['proxy'], ':') === false)) html_error($L->say['wrong_proxy']);
if (!empty($_GET['pauth'])) $pauth = decrypt(urldecode(trim($_GET['pauth'])));
else $pauth = (!empty($_GET['proxyuser']) && !empty($_GET['proxypass'])) ? base64_encode($_GET['proxyuser'] . ':' . $_GET['proxypass']) : '';

if (empty($_GET['path']) || ($options['download_dir_is_changeable'] || $options['maysaveto']) == false) {
	if (empty($_GET['host'])) $_GET['path'] = (substr($options['download_dir'], 0, 6) != 'ftp://') ? realpath(DOWNLOAD_DIR) : $options['download_dir'];
	else $_GET['saveto'] = (substr($options['download_dir'], 0, 6) != 'ftp://') ? realpath(DOWNLOAD_DIR) : $options['download_dir'];
}

if (empty($_GET ["filename"]) || empty($_GET ["host"]) || empty($_GET ["path"])) {
	if (!isset($_POST["link"])) {
		if (isset($_GET["idx"])) {
			// link was sent from audl
			$LINK = (isset($_GET["link"]) ? utf8_strrev(base64_decode($_GET['link'])) : "");
		} else {
			$LINK = (isset($_GET["link"]) ? $_GET["link"] : "");
		}
	} else {
		$LINK = (isset($_POST["link"]) ? $_POST["link"] : "");
	}
	// urldecode, trim, strip * in link
	$LINK = !empty($_GET['link']) ? urlcleaner(trim(rawurldecode($LINK))) : false;

	if (!$LINK) {

		_create_list();

		require_once(CLASS_DIR . "main.php");
		exit();
	} elseif ($limitation_alert === true) {
		html_error('Limitation alerted, can not use RL! ' . $msg, 1);
	}

	check_referer();

	// Detect if it doesn't have a protocol assigned
	if (stripos($LINK, '://') === false || (substr($LINK, 0, 7) != 'http://' && substr($LINK, 0, 6) != 'ftp://' && substr($LINK, 0, 6) != 'ssl://' && substr($LINK, 0, 8) != 'https://')) {
		// Automatically assign http://
		$LINK = 'http://' . $LINK;
	}

	if (!empty($_GET['saveto']) && empty($_GET['path'])) html_error($L->say['path_not_defined']);

	if (empty($_GET['useproxy'])) $_GET['proxy'] = '';

	if (!empty($_GET['domail']) && !checkmail($_GET['email'])) {
		html_error($L->say['didnt_enter_mail']);
		if (!empty($_GET['split']) && !is_numeric($_GET['partSize'])) html_error($L->say['size_not_true']); // T-8: Check this.
	}

	$Url = parse_url($LINK);

	$Url['path'] = (empty($Url['path'])) ? '/' : str_replace('%2F', '/', rawurlencode(rawurldecode($Url['path'])));
	$LINK = rebuild_url($Url);

	if (empty($_GET['referer'])) {
		$Referer = $Url;
		// Remove login from Referer
		unset($Referer['user'], $Referer['pass']);
		$Referer = rebuild_url($Referer);
	} else $Referer = trim(rawurldecode($_GET['referer']));

	if ($Url['scheme'] != 'http' && $Url['scheme'] != 'https' && $Url['scheme'] != 'ftp') {
		$LINK = urlcleaner(utf8_strrev(base64_decode($LINK)));
		$Url = parse_url($LINK);
		if ($Url['scheme'] != 'http' && $Url['scheme'] != 'https' && $Url['scheme'] != 'ftp') {
			html_error($L->say['url_unknown']);
		}
	}

	if (empty($Url['user']) xor empty($Url['pass'])) {
		unset($Url['user'], $Url['pass']);
		$LINK = rebuild_url($Url);
	}

	if (isset($_GET['user_pass']) && $_GET['user_pass'] == 'on' && !empty($_GET['iuser']) && !empty($_GET['ipass'])) {
		$Url['user'] = $_GET['iuser'];
		$Url['pass'] = $_GET['ipass'];
		// Rebuild url
		$LINK = rebuild_url($Url);
	}
	// If Url has user & pass, use them as premium login for plugins and set $auth for direct download.
	if (!empty($Url['user']) && !empty($Url['pass'])) {
		if (empty($_REQUEST['premium_acc'])) $_GET['premium_acc'] = $_POST['premium_acc'] = $_REQUEST['premium_acc'] = 'on';
		$_GET['premium_user'] = $_POST['premium_user'] = $_REQUEST['premium_user'] = $Url['user'];
		$_GET['premium_pass'] = $_POST['premium_pass'] = $_REQUEST['premium_pass'] = $Url['pass'];

		$auth = urlencode(encrypt(base64_encode(rawurlencode($Url['user']) . ':' . rawurlencode($Url['pass']))));

		// Lets delete User and Pass from link because isn't needed now.
		unset($Url['user'], $Url['pass']);
		$LINK = rebuild_url($Url);
	} else {
		$auth = "";
	}
	$litehead = 1;
	if (!isset($_GET['dis_plug']) || $_GET ['dis_plug'] != 'on') {
		// check Domain-Host
		foreach ($host as $site => $file) {
			if (host_matches($site, $Url['host'])) {
				$page_title = $L->say['prep_dl'] . " " . $LINK;
				$shortcut_icon = IMAGE_DIR . "rsload_2.gif";
				include(TEMPLATE_DIR . 'header.php');
				require_once(CLASS_DIR . "http.php");
				require_once (HOST_DIR . "DownloadClass.php");
				require_once(HOST_DIR . 'download/' . $file);
				$class = substr($file, 0, -4);
				$firstchar = substr($file, 0, 1);
				if ($firstchar > 0) $class = "d$class";
				if (class_exists($class)) {
					$hostClass = new $class();
					$hostClass->Download($LINK);
				}
				exit();
			}
		}
	}

	$page_title = $L->say['leeching'];
	$shortcut_icon = IMAGE_DIR . "rsload_2.gif";
	include(TEMPLATE_DIR . 'header.php');

	$FileName = isset($Url ["path"]) ? basename($Url ["path"]) : '';
	$mydomain = ($pos = strpos($_SERVER['HTTP_HOST'], ':')) !== false ? substr($_SERVER['HTTP_HOST'], 0, $pos) : $_SERVER['HTTP_HOST'];
	if ($options['bw_save'] && ($Url['host'] == $_SERVER['SERVER_ADDR'] || host_matches($mydomain, $Url['host']))) html_error($L->sprintf($L->say['leech_not_allowed'], $mydomain, $_SERVER['SERVER_ADDR']));

	$redir = GetDefaultParams();
	$redir['dis_plug'] = 'on';
	$redir['filename'] = urlencode($FileName);
	$redir['host'] = urlencode($Url['host']);
	if (!empty($Url['port'])) $redir['port'] = urlencode($Url['port']);
	$redir['path'] = urlencode($Url['path'] . (!empty($Url['query']) ? '?' . $Url['query'] : ''));
	if (!empty($Referer)) $redir['referer'] = urlencode($Referer);
	$redir['link'] = urlencode($LINK);
	if (!empty($_GET['cookie'])) $redir['cookie'] = urlencode(encrypt($_GET['cookie']));
	if (!empty($auth)) $redir['auth'] = $auth;

	insert_location($redir);
} else {
	$page_title = 'nYedoTz...';
	$shortcut_icon = IMAGE_DIR . "rsload_2.gif";
	$litehead = 1;
	include(TEMPLATE_DIR . 'header.php');
	check_referer();
	echo('<div align="center">');

	/* Deprecated of useless and unworth feature for some cases might fail this status.
	  if ($options['MaxServerJob'] > 0) {
	  if ($options['MaxServerJob'] <= ongoingGet()) {
	  html_error($L->sprintf($L->say['maxjob_limited'], $options['MaxServerJob']));
	  }
	  } */

	if ($options["limitbyIP"]) {
		if ($ada_acc) {
			$trheute = $visitors->heute + 1;
			if ($trheute > $options['maximum_free_downloads']) {
				html_error($msgNya);
			}
		}
	}

	/* Deprecated of useless and unworth feature for some cases might fail this status.
	  ongoingAdd(); */

	do {
		$_GET['filename'] = urldecode(trim($_GET['filename']));
		if (strpos($_GET['filename'], '?') !== false) $_GET['filename'] = substr($_GET['filename'], 0, strpos($_GET['filename'], '?'));
		$_GET['saveto'] = urldecode(trim($_GET['saveto']));
		$_GET['host'] = urldecode(trim($_GET['host']));
		$_GET['path'] = urldecode(trim($_GET['path']));
		$_GET['port'] = !empty($_GET['port']) ? urldecode(trim($_GET['port'])) : 0;
		$_GET['referer'] = !empty($_GET['referer']) ? urldecode(trim($_GET['referer'])) : 0;
		$_GET['link'] = urldecode(trim($_GET['link']));

		$_GET['post'] = !empty($_GET['post']) ? unserialize(decrypt(urldecode(trim($_GET['post'])))) : 0;
		$_GET['cookie'] = !empty($_GET['cookie']) ? decrypt(urldecode(trim($_GET['cookie']))) : '';
		$_GET['proxy'] = !empty($_GET['proxy']) ? trim(urldecode($_GET['proxy'])) : '';
		// $resume_from = $_GET["resume"] ? intval(urldecode(trim($_GET["resume"]))) : 0;
		// if ($_GET["resume"]) {unset($_GET["resume"]);}
		$redirectto = '';

		$pauth = !empty($_GET['pauth']) ? decrypt(urldecode(trim($_GET['pauth']))) : '';

		$AUTH = array();
		$_GET['auth'] = !empty($_GET['auth']) ? trim($_GET['auth']) : '';
		if ($_GET['auth'] == '1') {
			if (!preg_match('|^(?:.+\.)?(.+\..+)$|i', $_GET['host'], $hostmatch)) html_error('No valid hostname found for authorisation!');
			$hostmatch = str_replace('.', '_', $hostmatch[1]);
			if (isset($premium_acc["$hostmatch"]) && is_array($premium_acc["$hostmatch"]) && !empty($premium_acc["$hostmatch"]['user']) && !empty($premium_acc["$hostmatch"]['pass'])) {
				$auth = base64_encode($premium_acc["$hostmatch"]['user'] . ':' . $premium_acc["$hostmatch"]['pass']);
			} else html_error('No usable premium account found for this download - please set one in accounts.php');
		} elseif (!empty($_GET['auth'])) {
			$auth = decrypt(urldecode($_GET['auth']));
			list($AUTH['user'], $AUTH['pass']) = array_map('rawurldecode', explode(':', base64_decode($auth), 2));
		} else $auth = false;

		$pathWithName = $_GET['saveto'] . PATH_SPLITTER . basename(urldecode($_GET['filename']));
		while (strpos($pathWithName, "\\\\") !== false)
			$pathWithName = str_replace("\\\\", "\\", $pathWithName);
		if (strpos($pathWithName, '?') !== false) $pathWithName = substr($pathWithName, 0, strpos($pathWithName, '?'));

		$url = parse_url($_GET['link']);
		if (empty($url['port'])) $url['port'] = $_GET['port'];
		if (isset($url['scheme']) && $url['scheme'] == 'ftp' && empty($_GET['proxy'])) {
			require_once(CLASS_DIR . 'ftp.php');
			$file = getftpurl($_GET['host'], defport($url), urldecode($_GET['path']), $pathWithName);
		} else {
			require_once(CLASS_DIR . 'http.php');
			!empty($_GET['force_name']) ? $force_name = urldecode($_GET['force_name']) : '';
			$file = geturl($_GET['host'], defport($url), $_GET['path'], $_GET['referer'], $_GET['cookie'], $_GET['post'], $pathWithName, $_GET['proxy'], $pauth, $auth, $url['scheme']);
		}

		if ($options['redir'] && $lastError && strpos($lastError, $L->say['_error'] . " " . $L->say['_redirect_to'] . " [")) {
			$redirectto = trim(cut_str($lastError, $L->say['_error'] . " " . $L->say['_redirect_to'] . " [", "]"));
			print $L->say['_redirecting_to'] . " <b>$redirectto</b> ... <br />$nn";
			$_GET['referer'] = urlencode($_GET['link']);
			if (strpos($redirectto, '://') === false) { // If redirect doesn't have the host
				$ref = parse_url(urldecode($_GET['referer']));
				unset($ref['user'], $ref['pass'], $ref['query'], $ref['fragment']);
				if (substr($redirectto, 0, 1) != '/') $redirectto = "/$redirectto";
				$purl = array_merge($ref, parse_url($redirectto));
			} else $purl = parse_url($redirectto);
			$_GET['link'] = urlencode(rebuild_url($purl));
			$_GET['filename'] = urlencode(basename($purl['path']));
			$_GET['host'] = urlencode($purl['host']);
			$_GET['path'] = urlencode($purl['path'] . (!empty($purl['query']) ? '?' . $purl['query'] : ''));
			$_GET['port'] = !empty($purl['port']) ? $purl['port'] : 80;
			$_GET['cookie'] = !empty($_GET['cookie']) ? urlencode(encrypt($_GET['cookie'])) : '';
			if (is_array($_GET['post'])) $_GET['post'] = urlencode(encrypt(serialize($_GET['post'])));
			if (!empty($_GET['proxy'])) {
				$_GET['proxy'] = urlencode($_GET['proxy']);
				if (!empty($pauth)) $_GET['pauth'] = urlencode(encrypt($pauth));
			}
			$lastError = $_GET['auth'] = ''; // With $_GET['auth'] empty it will still using the $auth
			unset($ref, $purl);
		}
	} while ($redirectto && !$lastError);

	/* Deprecated of useless and unworth feature for some cases might fail this status.
	  ongoingRemove(); */

	if ($lastError) {
		html_error($lastError, 0);
	} elseif ($file["bytesReceived"] == $file["bytesTotal"] || $file["size"] == "Unknown") {
		list($final_link, $current_time) = link_for_file(dirname($pathWithName) . "/" . basename($file["file"]), false, true);

		echo "<script type=\"text/javascript\">pr(100, '" . $file["size"] . "', '" . $file["speed"] . "')</script>";
		echo $L->sprintf($L->say['_filesaved'], $final_link, $file ["size"], $file ["time"], $file ["speed"]);
		// we do have base date from here, no need to reassign again in other.php for purge file
		$file["date"] = getNowzone($current_time);

		$tosave = array("name" => $file["file"], "size" => str_replace(" ", "&nbsp;", $file["size"]), "date" => $file["date"], "age" => $L->say['less_a_minute'], "link" => $_GET["link"], "comment" => str_replace("\n", "\\n", str_replace("\r", "\\r", $_GET["comment"])), "ip" => $visitors->userip);
		//print_r($tosave);
		$result = write_file(FILES_LST, serialize($tosave) . "\r\n", 0);
		if ($options['downloadLimitbyIP']) $result2 = write_file($visitors->FN['leech_log'], serialize(array("name" => $file["file"], "date" => $file["date"], "ip" => $visitors->userip)) . "\r\n", 0);

		if (!$result || !$result2) {
			echo $L->say['error_upd_list'] . "<br />";
		}

		if ($options["limitbytraffic"]) {
			if ($options["max_trafic"] > 0) {
				if (!is_numeric($traffic->total)) $traffic->total = 0;
				$traffic->total += $file["bytesReceived"];
				$traffic->updTraffic();
			}
		}

		if (!empty($_GET ["email"])) {
			require_once(CLASS_DIR . "mail.php");
			$_GET ["partSize"] = (isset($_GET ["partSize"]) && is_numeric($_GET ["partSize"]) ? $_GET ["partSize"] * 1024 * 1024 : false);
			if (xmail($fromaddr, $_GET ["email"], "File " . basename($file ["file"]), "File: " . basename($file ["file"]) . "\r\n" . "Link: " . $_GET ["link"] . (!empty($_GET ["comment"]) ? "\r\n" . "Comments: " . str_replace("\\r\\n", "\r\n", $_GET ["comment"]) : ""), $pathWithName, $_GET ["partSize"], ($_GET ["partSize"] && !empty($_GET ["method"]) ? $_GET ["method"] : ''))) {
				print $L->sprintf($L->say['mail_file_sent'], $_GET['email'], basename($file['file']));
			} else {
				print "<div class='acthistory_result'>" . $L->say['mail_error_send'] . "</div>";
			}
		}

		if ($options['new_window']) {
			print '<br /><a href="javascript:window.close();">' . $L->say['closewin'] . '</a>';
		} else {
			/* 			if ($options['pointboost'] > 0 && empty($_GET["idx"])) {
			  // i'd rather not delete current downloaded file
			  if(!defined("DOWNLOADED_FILE")){define('DOWNLOADED_FILE', DOWNLOAD_DIR.basename($file["file"]));}
			  @write_file(DOWNLOADED_FILE, " ");
			  if($numidx < $options["pointboost"]-1)
			  {
			  // generate reload form (link, secondcounterdown, auth, indexnumber)
			  echo genReload($_GET["link"], 3, $auth, $numidx);
			  }
			  } */
			$rnd = rand(11, 99);
			// generate delete link
			echo $L->sprintf($L->say['_del_link'], 'del.php?d=' . str_replace("=", "", rotN(base64_encode($file["date"] . ':' . '4puZ'), $rnd)) . "-" . $rnd, date("M-d, Y - H:i:s", $file["date"]));
			print "<br /><a id='tdone' href=\"" . $PHP_SELF . "\">[&nbsp;<b><span id='txtdone'>" . $L->say['back_main'] . "&nbsp;]</b></span></a><br />";
		}
	} else {
		@unlink($pathWithName);
		print $L->say['con_lost'] . "<br /><a href=\"javascript:location.reload();\">" . $L->say['_reload'] . "</a>";
		print '<script type="text/javascript">setTimeout("location.reload()",1000);</script>';
	}
	//check whether this index called from audl.php
	$audlparam = (isset($_GET["idx"])) ? $_GET["idx"] : '';
	if ($audlparam != '') {
		$audlparam = explode('|', $audlparam);
		$audlparam[0] = ($audlparam[0] != '' ? true : false);
		if ($audlparam[1] == '') {
			$audlparam[1] = 'none';
		}
		echo autoNext($audlparam[0], true, $audlparam[1]);
	}
	echo "\n</div>\n</body>\n</html>";
}
?>