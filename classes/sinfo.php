<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit();
}

function sload_winosname() {
	$wUnameB = php_uname("v");
	$wUnameBM = php_uname("r");
	$wUnameB = preg_replace("@build @i", "", $wUnameB);
	if ($wUnameBM == "5.0" && ($wUnameB == "2195")) {
		$wVer = "Windows 2000";
	}
	if ($wUnameBM == "5.1" && ($wUnameB == "2600")) {
		$wVer = "Windows XP";
	}
	if ($wUnameBM == "5.2" && ($wUnameB == "3790")) {
		$wVer = "Windows Server 2003";
	}
	if ($wUnameBM == "6.0" && (php_uname("v") == "build 6000")) {
		$wVer = "Windows Vista";
	}
	if ($wUnameBM == "6.0" && (php_uname("v") == "build 6001")) {
		$wVer = "Windows Vista SP1";
	}
	return $wVer;
}

// Initalize condition
$is_CPUerror = 1;
$gd_enable = ( extension_loaded('gd') && function_exists('gd_info') );


if ($server["is_windows"]) {
	if ($options["cpuUsageNFO"]) {
		if ($nocpus == 0 || $loadcpu == '') {
			$cpulast = 0;
			$wmi = new COM("Winmgmts://");
			$cpus = @$wmi->execquery("SELECT * FROM Win32_Processor");
			foreach ($cpus as $cpu) {
				$cpulast += $cpu->loadpercentage;
				$nocpus++;
				$loadcpu.= " ~" . color_cpu($cpulast) . "%";
			}
			$alert_sloadhigh = ($options["limit_cpuload"] && $cpulast >= $options["ServerLoadAllowed"]);
		}
		$is_CPUerror = 0;
		$cpu_string[0] = ($nocpus > 0 ? '<b>' . $nocpus . '</b> ' : '') . 'CPU :: ' . $loadcpu;
		$cpu_string[1] = '&#187;&nbsp;' . ($options["ServerLoadAllowed"] > 0 ? '(' . ($alert_sloadhigh ? '<blink>' : '') . color_cpu($cpulast) . ($alert_sloadhigh ? '</blink>' : '') .
						' of <b>' . $options["ServerLoadAllowed"] . '</b>)' : '<span style="cursor:help;" title="' . $L->say['nolimit_cpu'] . '">~</span>');
	} //--end windows os
} elseif ($server["is_linux"]) {

	if ($options["cpuUsageNFO"]) {

		function getStat($_statPath) {
			if (trim($_statPath) == '') {
				$_statPath = '/proc/stat';
			}
			ob_start();
			@readfile($_statPath);
			$stat = @ob_get_contents();
			@ob_end_clean();
			if (substr($stat, 0, 3) == 'cpu') {
				$parts = explode(" ", preg_replace("!cpu +!", "", $stat));
			} else {
				return false;
			}
			$return = array();
			$return['user'] = $parts[0];
			$return['nice'] = $parts[1];
			$return['system'] = $parts[2];
			$return['idle'] = $parts[3];
			return $return;
		}

		//--end getStat

		function getCpuUsage($_statPath = '/proc/stat') {
			$msge = "<div id='getCpuUsage'><small class='r'>getCpuUsage():<br />couldn't access STAT path</small></div><script type='text/javascript'>function killElement(){ document.getElementById('getCpuUsage').style.display='none'; } setTimeout('killElement()', 5000);</script>";
			$time1 = getStat($_statPath) or false;
			sleep(1);
			$time2 = getStat($_statPath) or false;

			$delta = array();
			if ($time1 != false && $time2 != false) {
				foreach ($time1 as $k => $v) {
					$delta[$k] = $time2[$k] - $v;
				}
				$deltaTotal = array_sum($delta);
				$percentages = array();

				foreach ($delta as $k => $v) {
					$percentages[$k] = @round($v / $deltaTotal * 100, 2);
				}
				return $percentages;
			} else {
				return $msge;
				//return false;
			}
		}

		//--end getCpuUsage


		$cpu = getCpuUsage();
		//if($cpu!==false){
		if (is_array($cpu)) {
			if ($options["CpuLoadFormat"] == "percent") {
				$cpulast = ($cpu ? (100 - $cpu['idle']) : 0);
				$cpu_string[0] = "CPU :: " . color_cpu(round($cpulast, "0")) . "%";
			} else {
				$stats = @exec('uptime');
				if (!$stats) {
					$stats = @shell_exec('uptime');
				}
				preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/', $stats, $regs);
				$cpulast = $regs[1];
				$cpu_string[0] = "CPU :: " . color_cpu($cpulast);
			}
			$alert_sloadhigh = ($options["limit_cpuload"] && $cpulast >= $options["ServerLoadAllowed"]);
			$is_CPUerror = 0;
			$cpu_string[1] = '&#187;&nbsp;' . ($options["ServerLoadAllowed"] > 0 ? '(' . ($alert_sloadhigh ? '<blink>' : '') . color_cpu($cpulast) . ($alert_sloadhigh ? '</blink>' : '') .
							' of <b>' . $options["ServerLoadAllowed"] . '</b>)' : '<span style="cursor:help;" title="' . $L->say['nolimit_cpu'] . '">~</span>');
		} else {
			$cpu_string[0] = $cpu;
		}
	} //--end linux cpuUsageNFO
} //--end linux os


#if (is_callable("disk_free_space"))
$space_callable = 0;
if (function_exists('disk_free_space') && function_exists('disk_total_space')) {

	function ZahlenFormatieren($Wert) {
		if ($Wert > 1099511627776) {
			$Wert = number_format($Wert / 1099511627776, 2, ".", ",") . " TB";
		} elseif ($Wert > 1073741824) {
			$Wert = number_format($Wert / 1073741824, 2, ".", ",") . " GB";
		} elseif ($Wert > 1048576) {
			$Wert = number_format($Wert / 1048576, 2, ".", ",") . " MB";
		} elseif ($Wert > 1024) {
			$Wert = number_format($Wert / 1024, 2, ".", ",") . " kB";
		} else {
			$Wert = number_format($Wert, 2, ".", ",") . " Bytes";
		}
		return $Wert;
	}

	$d = realpath("./");
	$free = disk_free_space($d);
	$total = disk_total_space($d);
	if (($free === FALSE) || ($free < 0)) {
		$free = 0;
	}
	if (($total === FALSE) || ($total < 0)) {
		$total = 0;
	}
	$free_percent = ($total > 0 ? round(($free * 100) / $total, 2) : 0);
	if ($free) $diskspace_callable = 1;
}
require_once(TEMPLATE_DIR . "sinfo.php");
?>