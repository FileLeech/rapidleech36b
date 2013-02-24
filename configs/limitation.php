<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

if ($options['limitbyIP']) {
	if ($visitors->is_error != 0) html_error($visitors->ret_msg);
	$visitors->ipcounter(); // check and count real ip downloader
	$ada_acc = false;
	if (isset($premium_acc)) {
		foreach ($premium_acc as $dhost => $val) {
			if ($val) {
				$ada_acc = true;
				break;
			}
		}
	}
	// check if max download reach
	$trheute = $visitors->heute + 1;
	if ($trheute > $options["maximum_free_downloads"]) {
		$msgNya = $L->sprintf($L->say['alert_premix_free'], $options["maximum_free_downloads"], $options["delay_per_ip"]);
	}
}
// Init call create_list
if ($czFlst == 0 && (!isset($_COOKIE["showAll"]) || (isset($_COOKIE["showAll"]) && $_COOKIE["showAll"] != 1) )) {
	_create_list(false, true); // medic mode, to force list in downloaded files
	updateListInFile(FILES_LST); // relist file
}

// Check traffic
$is_exceed = $is_expired = false;
if ($options["limitbytraffic"]) {
	require_once(CLASS_DIR . "limit_traffic.class.php");
	$traffic = new traffics();

	if ($traffic->is_error != 0) html_error($traffic->ret_msg, 0);
	$traffic->get();
	if ($options["day_reset_trafic"] > 0) $traffic->autoreset();
	$is_exceed = ($traffic->total - $traffic->max) >= 0;
	$is_expired = ($traffic->is_expired);
}

// Check working time
$is_worktime = ( $options["limit_timework"] ? cek_worktime($options["workstart"], $options["workend"]) : true );

// Check storages
$serverfiles = calcUsedSpace();
$storage_exceed = false;
if ($options["storage_limit"] > 0) {
	if ($serverfiles > ($options["storage_limit"] * 1024 * 1024)) {
		$storage_exceed = true;
	}
}

// Check download limit by ip
$FilesDownloadedPerTime = 0;
$is_dllimit = false;
if ($options['downloadLimitbyIP']) {
	if ($visitors->is_error != 0) html_error($visitors->ret_msg);
	$visitors->_create_list_LIP();
	foreach ($list as $k => $file) {
		if ($file ["ip"] == $visitors->userip) {
			if (TIME_NOW - $options["downloadDelayPerIP"] < $file ['date']) {
				$FilesDownloadedPerTime++;
			}
		}
	}
	$is_dllimit = ($FilesDownloadedPerTime >= $options["downloadsPerIP"]);
}

// Check cpuload
$alert_sloadhigh = false;
if ($options['cpuUsageNFO'] && file_exists(CLASS_DIR . 'sload.php')) {
	$nocpus = 0; $loadcpu = '';
	if (!isset($_GET["link"]) && !isset($_POST["link"])) {
		$main = 1;
	}
	$srvload = "<p id=\"srvload\" style=\"color:red; position:absolute;\">" . $L->say['alert_srvload'] . "</p>\n";
	// will return $alert_sloadhigh
	require_once(CLASS_DIR . 'sload.php');
	// force alert_sloadhigh false if no limit_cpuload
	if (!$options['limit_cpuload']) $alert_sloadhigh = false;
}


// Build Message for any limitation occurs
$limit_msg = null;
if ($is_expired || $is_exceed || !$is_worktime || $storage_exceed || $alert_sloadhigh || $is_dllimit) {
	$limitmsg = "";
	if ($is_expired || $is_exceed) {
		$limitmsg = $L->say['quote_alert'];
		$limit_msg.=$L->say['quote_status'];
	}
	// limitbytraffic alerted
	if ($is_exceed) {
		$limit_msg.=($limit_msg != "" ? "<br />" : "") . $L->say['exceed_alert'];
	}
	if ($is_expired) {
		$limit_msg.=($limit_msg != "" ? "<br />" : "") . $L->say['expired_since'] . date("d-M-Y", $traffic->expiredate);
	}

	if ($alert_sloadhigh) {
		$limit_msg.=($limit_msg != "" ? "<br />" : $L->say['cpuload_sloadhigh']);
	}  // limit_cpuload alerted
	if ($is_dllimit) {
		$limit_msg.=($limit_msg != "" ? "<br />" : $L->sprintf($L->say['exceed_download'], $options["downloadsPerIP"], sec1time($options["downloadDelayPerIP"])));
	}  // downloadLimitbyIP alerted
	if (!$is_worktime) {
		if (!empty($limitmsg)) $limitmsg.="<br />";
		$limitmsg.=$L->sprintf($L->say['worktime_alert'], $options["workstart"]);
	}  // limit_timework alerted
	if ($storage_exceed) {
		if (!empty($limitmsg)) $limitmsg.="<br />";
		$limitmsg.=$L->say['maxstorage_alert_1'];
		if ($options["auto_del_time"] > 0) $limitmsg.=$L->say['maxstorage_alert_2'];
	}  //  storage_limit alerted

	$limitation_alert = true;  // Was here... there's limitation alert
}

$ref = '';
if (isset($_SERVER['HTTP_REFERER'])) {
	$arRef = explode("http://", $_SERVER['HTTP_REFERER']);
	$arRef = explode("/", $arRef[1]);
	if (trim($arRef[0]) !== $_SERVER['HTTP_HOST']) $ref = $_SERVER['HTTP_REFERER'];
}
?>