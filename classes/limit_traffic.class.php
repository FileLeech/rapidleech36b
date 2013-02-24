<?php
/*****************************************************
 * Rapidleech 36B Limit Traffic Class
 *
 * $Id: limit_traffic.class.php - 12apr2010-Idx $
 *****************************************************/

if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

class traffics {

	// filename
	var $fname = TRAFFIC_LST;
	// get from log
	var $total; // current used traffic
	var $initdate; // initial date
	var $is_expired; // status traffic is expired (if delayday > 0)
	var $expiredate; // (d-day traffic quota expired). unix time
	// get from options
	var $max; // (in MB). eg: 1 GB = 1 * (1024) MB
	var $delayday; // auto reset traffic. delay in days; 

	/**
	 * Constructor of class.
	 */
	//function __constructor() {

	function traffics() {
		global $options;
		$this->is_error = 0;
		$this->ret_msg = '';

		/*  touch the file */
		if (!file_exists($this->fname)) {
			@touch($this->fname);
			if (file_exists($this->fname)) {
				if (!is_writable($this->fname)) $this->ret_msg = $L->sprintf($L->say['not_writable'], $this->fname);
			} else {
				$this->ret_msg = $L->sprintf($L->say['file_not_exists'], $this->fname);
			}
			$this->is_error = 1;
		}

		if ($this->is_error == 0) {
			$this->max = ($options["max_trafic"] * 1024 * 1024);
			$this->delayday = $options["day_reset_trafic"];
			if ($options["date_trafic"]) {
				$date = explode("/", $options["date_trafic"]);
				if (is_array($date)) $this->is_expr($date);
			}
		}
	}

	/*
	 * Get Current Traffic
	 */
	function get() {

		$buftxt = array(' ', null);

		if (@file_exists($this->fname)) {
			$buftxt = unserialize(@file_get_contents($this->fname));
		}
		if (!is_numeric($buftxt[0])) {
			$buftxt[0] = 0;
		}
		if (!preg_match("/\d{10}/i", $buftxt[1])) {
			$buftxt[1] = TIME_NOW;
		}
		$this->total = $buftxt[0];
		$this->initdate = $buftxt[1];
		if ($this->total == 0) $this->updTraffic();

		return $buftxt;
	}

	/*
	 * Check auto-reset
	 */
	function autoreset() {

		if ($this->delayday <= 0) return;

		$doreset = false;
		if ($this->initdate > TIME_NOW) {
			$this->initdate = TIME_NOW;
			$doreset = true;
		}

		$next_date = strtotime("+" . $this->delayday . " day", $this->initdate);
		if ($next_date <= TIME_NOW) {
			$doreset = true;
			$this->initdate = TIME_NOW;
		}
		if ($doreset) {
			$cur_trafic = ($doreset ? '0' : $this->total);
			$this->updTraffic();
		}
		return $this->initdate;
	}

	/*
	 * Check is it expired based on date_trafic
	 */
	function is_expr($date) {

		$this->expiredate = strtotime("{$date[2]}-{$date[1]}-{$date[0]}");
		$this->is_expired = ((TIME_NOW - $this->expiredate) > 0 ? 1 : 0);
		return $this->is_expired;
	}

	/*
	 * Update traffic
	 */
	function updTraffic() {
		if (@file_exists($this->fname)) {
			if (!$this->initdate) $this->initdate = TIME_NOW;
			if (!$this->total) $this->total = 0;
			$value_trafic = serialize(array($this->total, $this->initdate));
			$ret = @file_put_contents($this->fname, $value_trafic);
		}
	}

	/*
	 * Count remaining time
	 */
	function timeremain() {
		$next_date = strtotime("+" . $this->delayday . " day", $this->initdate);
		return ( $next_date - TIME_NOW );
	}

// =======
}

?>