<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: ajax_main.php - 06apr2010-Idx $
 *****************************************************/

define('RAPIDLEECH', 'yes');

if (!isset($_POST)) { return; }

require_once "./global.php";

define('AJAX_DIR', CLASS_DIR.'ajax/');

// ==MAIN==
// Initiate for Table Load
if (isset($_POST['TBLoad'])) {
	//=====================================# Initiate counter   
	$maintimer->remove();
	$maintimer->timer();
}

// Check ACC RS Multi and Single ( acc.status )
if (isset($_POST['accounts']) || isset($_POST['staccounts'])) {
	//=====================================# RS ACCOUNTS (Status & Checker)
	require_once(AJAX_DIR . "rs_accounts.php");
//end rs_accounts
} elseif (isset($_POST['dellog']) || isset($_POST['fntrafic'])) {
	//=====================================# LOG FLUSH
	require_once(AJAX_DIR . "log_flush.php");
	require_once(CLASS_DIR . 'limit_traffic.class.php');
	$traffic = new traffics();
// end log_flush
} elseif (isset($_POST['md5fn'])) {
	//=====================================# MD5 Changer
	require_once(AJAX_DIR . "md5_changer.php");
// end md5_changer
} elseif (isset($_POST['TBLoad'])) {
	//=====================================# TB LOADER
	require_once(AJAX_DIR . "tb_loadfiles.php");
// end tb_loadfiles
} elseif (isset($_POST['ren'])) {
//=======================================# Instant-RENAME
	require_once(AJAX_DIR . "inst_rename.php");
// end inst_rename
} elseif (isset($_POST['url'])) {
//=======================================# Ajax-Link Checker
	require_once(AJAX_DIR . "ajax_lnk.php");
} // end Ajax-Link Checker

exit();
?>