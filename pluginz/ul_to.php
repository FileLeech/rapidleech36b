<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

require_once (HOST_DIR .  "uploaded_to.php");
$link = str_replace("ul.to", "uploaded.to/file", $LINK);
$hostClass = new uploaded_to();
$hostClass->Download( $link );

/**************************************************\
Updated by rajmalhotra 07 Feb 2010
Fixed by vdhdevil 16 March 2011
\**************************************************/
?>