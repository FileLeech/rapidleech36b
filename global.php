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
if (ob_get_level()) @ob_end_clean();
if (isset($_POST['link'])) {
	@ob_implicit_flush(true);
}
ignore_user_abort(true);
clearstatcache();
error_reporting(6135);

$nn = "\r\n";
$fromaddr = "RapidLeech";
$dev_name = 'Development Stage';
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

//check config.php is exist or not, run setup file only if needed
require_once(CONFIG_DIR . 'setup.php');

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
$charSet = $L->settings["charset"];

// Check DOWNLOAD_DIR and FILES_LST
$czFlst = checkExistence();

// Strict check firbidden file in DOWNLOAD_DIR
checkStrict();

define('THIS_SCRIPT', basename($PHP_SELF));
// check for forbidden page (audl, auul, lynx, mtn)
$keyfn = str_replace("." . get_extension(THIS_SCRIPT), "", THIS_SCRIPT);
if (isset($options['forbid'][$keyfn]) && $options['forbid'][$keyfn]) {
	get_tpl("404", ucwords(do_strtolower($keyfn)) . " " . $L->say["disabled"]);
	exit();
}

// Load server spec
$server = getServerPlatf();

// Load visitor class, user ip defined in here
require_once (CLASS_DIR . 'visitors.class.php');
$visitors = new Visitor();

switch (THIS_SCRIPT) {
	case $options['index_file']:
		if ($options["no_cache"]) {
			header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
		}

		register_shutdown_function("pause_download");

		break;


	case 'auul.php': case 'upload.php':

		if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', HOST_DIR . 'upload/');
		if (!defined('CRLF')) define('CRLF', "\r\n");
		require_once(CLASS_DIR . "http.php");
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

$deleted = ($options['auto_del_time'] > 0 ? purge_files($options['auto_del_time']) : 0);

$options['downloadLimitbyIP'] ? $visitors->purge_files_IP($options['downloadDelayPerIP']) : '';

($options['OnlineVisitor'] || $options['logact']) && $visitors->is_error != 0 ? html_error($visitors->ret_msg, 0) : '';

$options['OnlineVisitor'] ? $visitors->updOnlineUser() : '';

$options['logact'] && !$visitors->is_latestVisitor() ? $visitors->updLogVisitor('IN') : '';

?>