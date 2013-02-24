<?php
/***************************************************************
 * Rapidleech 36B Visitors User Class
 *
 * $Id: visitors.class.php - 06apr2010-Idx $
 * $Add support for limitation by ip - 12aug2012 - _R_V_T_™ $
 ***************************************************************/

if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

class Visitor {

	// filename
	var $FN = array(
		// online visitor property
		"online" => VISITOR_LST,
		// Log activity property
		"log_last" => LASTLOG_LST,
		"log_php" => LOG_PHP,
		// download limit by ip property
		"leech_log" => IP_L33CH_L0G,
	);
	var $is_error;
	var $ret_msg;
	// online visitor property
	var $online = array(
		"list" => array(),
		"timeoffset" => 15, // time offset for online user
		"total" => 0,
	);
	var $onlineList;
	var $userip;
	// visitor log property
	var $salt_ip = "i!X9@_=>~|:/'?[}"; //  store salted md5 latest ip
	// limit by ip property
	var $tmp_dir;
	var $heute;
	var $logs;

	/**
	 * Constructor of class.
	 */
	function __construct() {
		global $options, $L;

		$this->is_error = 0;
		$this->ret_msg = '';
		$this->userip = $this->get_real_ip();
		$this->tmp_dir = CONFIG_DIR . 'tmp/';

		/* Check the log file/directory */ {
			/*  Log user activity */
			if ($options['logact']) {
				if (!file_exists($this->FN['log_last'])) {
					@touch($this->FN['log_last']);
					if (file_exists($this->FN['log_last'])) {
						if (!is_writable($this->FN['log_last'])) $this->ret_msg .= $L->sprintf($L->say['not_writable'], $this->FN['log_last']);
					} else {
						$this->ret_msg .= $L->sprintf($L->say['file_not_exists'], $this->FN['log_last']);
					}
					$this->is_error = 1;
				}
				if (!file_exists($this->FN['log_php'])) {
					@touch($this->FN['log_php']);
					if (file_exists($this->FN['log_php'])) {
						if (!is_writable($this->FN['log_php'])) $this->ret_msg .= $L->sprintf($L->say['not_writable'], $this->FN['log_php']);
					} else {
						$this->ret_msg .= $L->sprintf($L->say['file_not_exists'], $this->FN['log_php']);
					}
					$this->is_error = 1;
				}
			}

			/*  Online Visitor	 */
			if ($options['OnlineVisitor']) {
				if (!@file_exists($this->FN['online'])) {
					@touch($this->FN['online']);
					if (file_exists($this->FN['online'])) {
						if (!is_writable($this->FN['online'])) $this->ret_msg .= $L->sprintf($L->say['not_writable'], $this->FN['online']);
					} else {
						$this->ret_msg .= $L->sprintf($L->say['file_not_exists'], $this->FN['online']);
					}
					$this->is_error = 1;
				}
			}

			/* Download Limit by IP */
			if ($options['downloadLimitbyIP']) {
				if (!@file_exists($this->FN['leech_log'])) {
					@touch($this->FN['leech_log']);
					if (@file_exists($this->FN['leech_log'])) {
						if (!@is_writable($this->FN['leech_log'])) $this->ret_msg .= $L->sprintf($L->say['not_writable'], $this->FN['leech_log']);
					} else {
						$this->ret_msg .= $L->sprintf($L->say['file_not_exists'], $this->FN['leech_log']);
					}
					$this->is_error = 1;
				}
			}
			/* Limit Download Premium by IP (Only selected premium hostmatches will count) */
			if ($options['limitbyIP']) {
				if (!@is_dir($this->tmp_dir)) {
					// try to create temporary directory with full permission and recursively
					@mkdir(CONFIG_DIR . 'tmp/', 0777, true);
					// check if it's writeable or not
					if (@is_dir($this->tmp_dir)) {
						if (!@is_writable($this->tmp_dir)) $this->ret_msg .= $L->sprintf($L->say['not_writable'], $this->tmp_dir);
					} else {
						$this->ret_msg .= $L->sprintf($L->say['dir_not_exists'], $this->tmp_dir);
					}
					$this->is_error = 1;
				}
			}

			clearstatcache();
			//give the user error message if the file/directory not exist
			return $this->is_error;
		}
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

	/**
	 * Update Visitor Online
	 */
	function updOnlineUser() {
		if (@file_exists($this->FN['online'])) {
			$this->online['list'] = $this->getLog($this->FN['online']);
			$this->online['timeoffset'] = 15;
			$this->online['list'][$this->userip] = TIME_NOW;
			$this->onlineList = $this->getOnlineUser();
			@file_put_contents($this->FN['online'], serialize($this->onlineList));
		}
	}

	/**
	 * Get Visitor Online
	 */
	function getOnlineUser() {
		$this->online['total'] = 0;
		if (is_array($this->online['list']) && count($this->online['list']) > 0) {
			$onlineList = array();
			foreach ($this->online['list'] as $ip => $time) {
				if (TIME_NOW - $time < ($this->online['timeoffset'] * 60)) {
					$this->online['total']++;
					$onlineList[$this->userip] = $time;
				}
			}
		}

		return $onlineList;
	}

	// =======

	/**
	 * Update Log Visitor
	 */
	function updLogVisitor($act, $fname=0, $fsize=0) {

		$details = array(
			'date' => TIME_NOW,
			'ip' => $this->userip,
			'act' => ($act),
			'fn' => ($fname != 0 ? $fname : ''),
			'fz' => ($fsize != 0 ? $fsize : ''),
		);
		if (file_exists($this->FN['log_php'])) {
			$current = $this->getLog($this->FN['log_php']);
			$current[] = $details;
			@file_put_contents($this->FN['log_php'], serialize($current));
			// Update last log
			@file_put_contents($this->FN['log_last'], md5($this->userip . $this->salt_ip));
		}
	}

	/**
	 * get current log, returned array
	 */
	function getLog($logfile) {
		if (@file_exists($logfile)) {
			//$fl = fopen($this->FN['log_php'], 'r');
			//$buftxt = @fread($fl, @filesize($this->FN['log_php']));
			$buftxt = unserialize(file_get_contents($logfile));
			//fclose($fl);
			return $buftxt;
		}
	}

	/**
	 * Latest Visitor
	 */
	function is_latestVisitor() {

		$is_latest = false;
		if (@file_exists($this->FN['log_last'])) {
			//$fl = fopen($this->FN['log_last'], 'r');
			//$buftxt = @fread($fl, @filesize(LASTLOG_LST));
			$buftxt = ( file_get_contents($this->FN['log_last']) );
			//fclose($fl);
			$is_latest = ($buftxt != "" && md5($this->userip . $this->salt_ip) === $buftxt);
		}
		return $is_latest;
	}

	// =======

	/* Create server files list based on user IP */

	function _create_list_LIP() {
		global $list;

		$glist = array();
		$glistReformat = null;
		if (@file_exists($this->FN['leech_log'])) {
			$glist = @file($this->FN['leech_log']);
			foreach ($glist as $key => $record) {
				foreach (@unserialize($record) as $field => $value) {
					$listReformat[$key][$field] = $value;
					if ($field == "date") $date = $value;
				}
				$glist[$date] = $listReformat[$key];
				unset($glist[$key], $glistReformat[$key]);
			}
		}
		$list = $glist;
	}

	/* Create/Remove if the user has reach the limit/renew the limit if the time has been set */

	function purge_files_ip($delay) {
		if (@file_exists($this->FN['leech_log']) && is_numeric($delay) && $delay > 0) {
			$files_lst = @file($this->FN['leech_log']);
			$files_new = "";
			foreach ($files_lst as $files_line) {
				$files_data = @unserialize(trim($files_line));
				if (TIME_NOW - $files_data["date"] < ($delay)) {
					$files_new .= $files_line;
				}
			}
			@file_put_contents($this->FN['leech_log'], $files_new);
		}
	}

	// =======

	/* Create the counter based on limit premium by IP */
	function ipcounter() {
		global $options;

		$xhandle = @opendir($this->tmp_dir);
		if ($xhandle) {
			while ($buin = readdir($xhandle)) {
				if (preg_match("@$this->userip@i", $buin)) {
					$this->heute = $this->heute + 1;
				}
			}
			$ckusr = decEnti((string) base64_decode(urldecode($_COOKIE['rxyzusr'])));
			$ckusr = (is_numeric($ckusr) ? $ckusr : '0');
			if (($ckusr != '') && ($ckusr > $this->heute)) {
				$this->heute = $ckusr;
			} else {
				setcookie('rxyzusr', base64_encode(encEnti((string) $this->heute)), TIME_NOW + ($options['delay_per_ip'] * 3600));
			}
			closedir($xhandle);

			error_reporting(false);
			$xhandle = @opendir($this->tmp_dir);
			while ($buin = readdir($xhandle)) {
				if ($buin == "." || $buin == "..") {
					$buin = "fu";
				}
				$xd = @filemtime($this->tmp_dir . $buin);
				$xd = getNowzone($xd);
				$altr = TIME_NOW - $xd;
				if ($altr > $options['delay_per_ip'] * 3600) {
					if (is_dir($this->tmp_dir . $buin)) {
						@rmdir($this->tmp_dir . $buin);
					} else {
						@unlink($this->tmp_dir . $buin);
					}
				}
			}
			closedir($xhandle);
			error_reporting(true);
		}
	}

}

?>