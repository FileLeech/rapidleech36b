<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class secureupload_eu extends DownloadClass {
	
	public function Download($link) {
		global $premium_acc;
		
		if (!$_REQUEST['step']) {
			$this->cookie = array('lang' => 'english');
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, 'The file was removed');
		}
	}
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
