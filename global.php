<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: global.php - 05apr2010-Idx $
 *****************************************************/

if (!defined("RAPIDLEECH")) {
	die("Direct initialization of this file is not allowed.<br /><br />RAPIDLEECH is not defined.");
}

error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(E_ALL);
//error_reporting(0);
@ini_set('display_errors', true);

@set_time_limit(0);
ini_alter("memory_limit", "1024M");
@ob_end_clean();
if (isset($_POST['link'])) {
	@ob_implicit_flush(TRUE);
}
ignore_user_abort(false);
clearstatcache();
error_reporting(6135);

$nn = "\r\n";
$fromaddr = "RapidLeech";
$dev_name = ' - Release.Candidate';
$rev_num = '36B.Rv8.0';
$plusrar_v = '4.1';
$RL_VER = 'Rx08.ii' . $rev_num;

$PHP_SELF = $_SERVER['SCRIPT_NAME'];
define('PATH_SPLITTER', (strstr(realpath("./"), "\\") !== false ? "\\" : "/"));
define('ROOT_DIR', realpath("./") . PATH_SPLITTER);
define('HOST_DIR', 'pluginz/');
define('STATIC_DIR', 'static/');
define('BINARY_DIR', 'binary/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', 'configs/');
define('LANG_DIR', CLASS_DIR . 'languages/');

$loca["thisFile"] = str_replace('\\', '/', __FILE__);
$loca["docRoot"] = $_SERVER['DOCUMENT_ROOT'] . (substr($_SERVER['DOCUMENT_ROOT'], -1) != "/" ? "/" : "");
$webRoot = str_replace(array($loca["docRoot"], basename(__FILE__)), '', $loca["thisFile"]);
unset($loca);
if ($webRoot == "/") $webRoot = "";
define('ROOT_URL', "http://" . $_SERVER["SERVER_NAME"] . "/" . $webRoot);

// check fileconfig existence
if (!@file_exists(CONFIG_DIR . "config.php")) {

	define('DEFAULT_CONFIG_FILE', CONFIG_DIR . 'config.default.php');
	require_once DEFAULT_CONFIG_FILE;

	$fninstall = CONFIG_DIR . "setup.php";
	if (file_exists($fninstall)) {
		require_once($fninstall);
	} else {
		die("File not found " . $fninstall);
	}
	exit();
}

// Load configuration & accounts
require_once(CONFIG_DIR . "config.php");
require_once(CONFIG_DIR . "accounts.php");

// Load function and class
require_once(CLASS_DIR . "other.php");

// Set server timezone; TIME_NOW will set here
$tzone = getNowzone();
define("TIME_NOW", $tzone);

require_once(CLASS_DIR . "timers.class.php");
$maintimer = new timer();
$maintimer->timer();

// $download_dir should always end with '/'
if (substr($options["download_dir"], -1) != '/') $options["download_dir"] .= '/';
define('DOWNLOAD_DIR', (substr($options["download_dir"], 0, 6) == "ftp://" ? '' : $options["download_dir"]));

define('TEMPLATE_DIR', 'tpl/' . $options['template_used'] . '/');
define('IMAGE_DIR', TEMPLATE_DIR . 'skin/' . $options["csstype"] . '/');

// Language initialisation
require_once(CLASS_DIR . "lang.class.php");
$L = new RxLang;
$L->set_path(LANG_DIR);

// Check language
if (!$options['lang'] || ($options['lang'] && !$L->language_exists($options['lang']))) {
	$options['lang'] = 'english';
}
// Load language
$L->set_language($options['lang']);
$L->load();
$charSet = $L->settings["charset"];
//==== language global loaded here
// Check DOWNLOAD_DIR and FILES_LST
$czFlst = checkExistence();

// Strict check firbidden file in DOWNLOAD_DIR
checkStrict();

define('THIS_SCRIPT', basename($PHP_SELF));
// check for forbidden page (audl, auul, lynx, mtn)
$keyfn = str_replace("." . get_extension(THIS_SCRIPT), "", THIS_SCRIPT);
if (isset($options["forbid"]["_{$keyfn}"]) && $options["forbid"]["_{$keyfn}"]) {
	get_tpl("404", ucwords(do_strtolower($keyfn)) . " " . $L->say["disabled"]);
	exit();
}

// Load server spec
$server = getServerPlatf();
// get real IP
$ipmu = get_real_ip();

switch (THIS_SCRIPT) {
	case $options['index_file']:
		if ($options["no_cache"]) {
			header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
		}

		if (!defined('CRLF')) define('CRLF', "\r\n");
		if (!defined("FTP_AUTOASCII")) define("FTP_AUTOASCII", -1);
		if (!defined("FTP_BINARY")) define("FTP_BINARY", 1);
		if (!defined("FTP_ASCII")) define("FTP_ASCII", 0);
		if (!defined('FTP_FORCE')) define('FTP_FORCE', TRUE);
		define('FTP_OS_Unix', 'u');
		define('FTP_OS_Windows', 'w');
		define('FTP_OS_Mac', 'm');
		
		register_shutdown_function("pause_download");

		break;


	case 'auul.php': case 'upload.php':

		if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', HOST_DIR . 'upload/');
		if (!defined('CRLF')) define('CRLF', "\r\n");
		include(CLASS_DIR . "http.php");
		require_once(HOST_DIR . "download/hosts.php");

		break;

	case "lynx.php":

		$server = getServerPlatf(1);

		break;
} // end switch

if (THIS_SCRIPT == $options['index_file'] || THIS_SCRIPT == "audl.php") {
	require_once(CONFIG_DIR . "limitation.php");
}
//Cek ip yg banned || is it listed as authorized ip || check country limit
if ($options["limited_edition"] || $options["limited_area"]) {
	$dlimitation = array($options["limited_edition"], $options["limited_area"]);
	require_once(CLASS_DIR . "limit_district.php");
}


login_check();

$deleted = ($options["auto_del_time"] > 0 ? purge_files($options["auto_del_time"]) : 0);

if ($options["downloadLimitbyip"]) purge_files_ip($options["downloadDelayPerIP"]);

if ($options["OnlineVisitor"] || $options["logact"]) {
	require_once(CLASS_DIR . "visitors.class.php");
	$visitors = new Visitor;
}
if ($options["OnlineVisitor"]) $visitors->updOnlineUser();

if ($options["logact"]) {
	if (!$visitors->is_latestVisitor()) $visitors->updLogVisitor('IN');
}
?>