<?php  
 error_reporting(1);
 define('RAPIDLEECH', 'yes');
 define('ROOT_DIR', realpath("./"));
 define('CONFIG_DIR', 'configs/');
 define('CLASS_DIR', 'classes/');
 define('LANG_DIR', 'languages/');
 define('AJAX_DIR', CLASS_DIR . 'ajax/');
 
 $PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;

//Initiate Counter load time
if (isset($_POST['TBLoad'])) { 
 $time2 = explode (' ', microtime());
 $begintime2 = $time2[1] + $time2[0]; unset($time2);
}

 require_once(CONFIG_DIR."config.php");
 require_once(CLASS_DIR."other.php");

 define('DOWNLOAD_DIR', (substr($options['download_dir'], 0, 6) == "ftp://" ? '' : $options['download_dir']));
 
// Check ACC RS Multi and Single ( acc.status )
if (isset($_POST['accounts']) || isset($_POST['staccounts'])) {
 //=====================================# RS ACCOUNTS (Status & Checker)
  require_once(AJAX_DIR . "rs_accounts.php");
}  //end rs_accounts


elseif (isset($_POST['dellog']) || isset($_POST['fntrafic'])) {
 //=====================================# LOG FLUSH
 require_once(AJAX_DIR . "log_flush.php");
}  // end log_flush


elseif (isset($_POST['md5fn'])) {
 //=====================================# MD5 Changer
 require_once(AJAX_DIR . "md5_changer.php");
}  // end md5_changer


elseif (isset($_POST['TBLoad'])) {
 //=====================================# TB LOADER
 require_once(AJAX_DIR . "tb_loadfiles.php");
} // end tb_loadfiles


elseif(isset($_POST['ren'])) {
//=======================================# Instant-RENAME
 require_once(AJAX_DIR . "inst_rename.php");
} // end inst_rename

exit();
?>

