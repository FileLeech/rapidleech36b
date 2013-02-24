<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class filesline_com extends DownloadClass {
	
	public function Download($link) {
		global $premium_acc;
		
		if (!$_REQUEST['step']) {
			$this->cookie = array('lang' => 'english');
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, 'This server is in maintenance mode.');
		}
	}
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
