<?php
/*****************************************************
 * Rapidleech 36B Visitors User Class
 *
 * $Id: visitors.class.php - 06apr2010-Idx $
 *****************************************************/

if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

class Visitor {

	// filename
	var $FN = array(
		"online" => VISITOR_LST,
		// Log activity
		"log_last" => LASTLOG_LST,
		"log_php" => LOG_PHP,
	);
	var $is_error;
	var $ret_msg;
	var $userip;
	// online visitor property
	var $online = array(
		"list" => array(),
		"timeoffset" => 15, // time offset for online user
		"total" => 0,
	);
	// visitor log property
	var $salt_ip = "i!X9@_=>~|:/'?[}"; //  store salted md5 latest ip
	var $logs;

	/**
	 * Constructor of class.
	 */
	function __constructor() {
		$this->is_error = 0;
		$this->ret_msg = '';

		/*  Log user activity */ {
			if (!file_exists($this->FN['log_last'])) {
				@touch($this->FN['log_last']);
				if (!file_exists($this->FN['log_last'])) {
					$this->is_error = 1;
					$this->ret_msg = "Can't find " . $this->FN['online'];
				}
			}
			if (!file_exists($this->FN['log_php'])) {
				@touch($this->FN['log_php']);
				if (!file_exists($this->FN['log_php'])) {
					$this->is_error = 1;
					$this->ret_msg = "Can't find " . $this->FN['log_php'];
				}
			}
		}

		/*  Online Visitor	 */ {
			if (!file_exists($this->FN['online'])) {
				@touch($this->FN['log_last']);
			} else {
				$this->is_error = 1;
				$this->ret_msg = "Can't find " . $this->FN['online'];
			}
			$this->online['list'] = unserialize(file_get_contents($this->FN['online']));
			$this->online['timeoffset'] = 15;
		}
	}

	/**
	 * Update Visitor Online
	 */
	function updOnlineUser() {
		if (@file_exists($this->FN['online'])) {
			$this->online['list'][$this->userip] = TIME_NOW;
			$this->getOnlineUser();
			@file_put_contents($this->FN['online'], serialize($this->onlineList));
		}
	}

	/**
	 * Get Visitor Online
	 */
	function getOnlineUser() {
		$this->online['total'] = 0;
		if (is_array($this->online['list']) && count($this->online['list']) > 0) {
			$still_onlineList = array();
			foreach ($this->online['list'] as $ip => $time) {
				if (TIME_NOW - $time < ($this->online['timeoffset'] * 60)) {
					$this->online['total']++;
					$still_onlineList[$this->userip] = $time;
				}
			}
			$this->onlineList = $still_onlineList;
		}
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
			$current = $this->getLog();
			$current[] = $details;
			@file_put_contents($this->FN['log_php'], serialize($current));
			// Update last log
			@file_put_contents($this->FN['log_last'], md5($this->userip . $this->salt_ip));
		}
	}

	/**
	 * get current log, returned array
	 */
	function getLog() {
		if (file_exists($this->FN['log_php'])) {
			//$fl = fopen($this->FN['log_php'], 'r'); 
			//$buftxt = @fread($fl, @filesize($this->FN['log_php'])); 
			$buftxt = unserialize(file_get_contents($this->FN['log_php']));
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

}

?>