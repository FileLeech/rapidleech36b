<?php
if (!defined('RAPIDLEECH')) {
	require_once ( "index.html" );
	exit;
}

if (isset($_POST['dellog']) || isset($_POST['fntrafic'])) {
	foreach ($options['loginCp'] as $u => $p) {
		$cp_login[0] = $u;
		$cp_login[1] = $p;
	}

	$authtraf = base64_encode($cp_login[0] . ":" . $cp_login[1]);

	//=====================================# Flush Traffic
	if (isset($_POST['fntrafic'])) {
		$postauth = $_POST['fntrafic'];
		if ($authtraf === $postauth) {
			$fn_trafic = TRAFFIC_LST;
			if (@file_exists($fn_trafic)) {
				$isinya = ($options['day_reset_trafic'] > 0 ? '0:' . strtotime("now") : '0');
				if (!write_traffic($fn_trafic, $isinya)) {
					echo "&lt;!&gt;";
				} else {
					echo $isinya;
				}
			}
		}
	}

	//=====================================# Flush Log Visitor
	if (isset($_POST['dellog'])) {
		$postauth = $_POST['dellog'];
		if ($authtraf === $postauth) {
			if (@file_exists(LASTLOG_LST)) {
				@write_file(LASTLOG_LST, "Last-IP-Visitor");
			}
			if (@file_exists(LOG_PHP)) {
				//$isinya = "Log-System-Storage";
				@write_file(LOG_PHP, "", 1);
				echo @filesize(LOG_PHP) . " B";
			}
		}
	}
}
?>