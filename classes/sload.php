<?php
if(!defined('RAPIDLEECH'))
  { require_once("404.php"); exit; }

function sloadhigh($msg) {
?>
<html><head>
<meta http-equiv="Content-Type"	content="text/html; charset=windows-1251">
<title>Error...</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>rl_style_pm.css");
-->
</style></head>
<body><center><img src="<?php	print IMAGE_DIR;?>logo_pm.gif" alt="RAPIDLEECH PLUGMOD"></center>
<br><br>
<center>
<?php
echo "<span style=\"color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00; line-height:25px\"><b>$msg</b></span><br>";
?><br>
<a href="javascript: void(0);" onclick="window.location.reload();">Refresh</a><br />
<?php
require_once (CLASS_DIR . "sinfo.php");
?>
<hr>
<?php
print CREDITS;
?><br>
</center></body></html>
<?php
	exit ();
}
// end Server too high


//===============================
// General MAIN cpu load check
{
	//if ($os == "windows") {
	if ($server["is_windows"]) {
		$wmi = new COM("Winmgmts://");
		$cpus = $wmi->execquery("SELECT * FROM Win32_Processor");
		$cpulast = 0; $nocpus = 0; $loadcpu ='';
		foreach ( $cpus as $cpu ) {
			$cpulast += $cpu->loadpercentage;
			$loadcpu.= " :: ".color_cpu($cpulast)."%";
			$nocpus ++;
		}
		$cpulast = $cpulast / $nocpus;
		//if ($cpulast >= $options['ServerLoadAllowed'] && ! isset ( $main )) {
		if ($cpulast >= $options['ServerLoadAllowed']) {
			//html_error ( "Server load too high, come back later." );
			$alert_sloadhigh = true;
		}
		
		
		
	//} elseif ($os == "linux") {
	} elseif ($server["is_linux"]) {
	
		function sload_getStat($_statPath) {
			if (trim ( $_statPath ) == '') {
				$_statPath = '/proc/stat';
			}
			
			ob_start ();
			if ($options['passthru_allowed']) {
				@passthru ( 'cat ' . $_statPath );
				$stat = ob_get_contents ();
				@ob_end_clean ();
				
				if (substr ( $stat, 0, 3 ) == 'cpu') {
					$parts = explode ( " ", preg_replace ( "!cpu +!", "", $stat ) );
				} else {
					return false;
				}
				
				$return = array ();
				$return ['user'] = $parts [0];
				$return ['nice'] = $parts [1];
				$return ['system'] = $parts [2];
				$return ['idle'] = $parts [3];
			} else {
				$stat = @file ( '/proc/stat' );
				$parts = explode ( " ", preg_replace ( "!cpu +!", "", $stat [0] ) );
				$return = array ();
				$return ['user'] = $parts [0];
				$return ['nice'] = $parts [1];
				$return ['system'] = $parts [2];
				$return ['idle'] = $parts [3];
			}
			return $return;
		}

		function sload_getCpuUsage($_statPath = '/proc/stat') {
			//return null;
			$msge = "<div><small>getCpuUsage(): couldn't access STAT path or STAT file invalid</small></div>\n";
			$time1 = sload_getStat($_statPath) or false;
			sleep(1);
			$time2 = sload_getStat($_statPath) or false;			

			$delta = array ();
			
			foreach ( $time1 as $k => $v ) {
				$delta [$k] = $time2 [$k] - $v;
			}
			
			$deltaTotal = array_sum ( $delta );
			$percentages = array ();
			
			foreach ( $delta as $k => $v ) {
				$percentages [$k] = round ( $v / $deltaTotal * 100, 2 );
			}
			return $percentages;
		}
		if ($options['CpuLoadFormat'] == "percent") {
			$cpu = sload_getCpuUsage ();
			if ($cpu) {
				$cpulast = 100 - $cpu ['idle'];
			} else {
				$cpulast = 0;
			}
		} else {
			$stats = @exec ( 'uptime' );
			if (! $stats) {
				$stats = @shell_exec ( 'uptime' );
			}
			preg_match ( '/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/', $stats, $regs );
			$cpulast = $regs [1];
		}
		//if ($cpulast >= $options['ServerLoadAllowed'] && ! isset ( $main )) {
		if ($cpulast >= $options['ServerLoadAllowed']) {
			//sloadhigh ( "Server load too high, come back later" );
			$alert_sloadhigh = true;
		}
	} //--end linux os
}