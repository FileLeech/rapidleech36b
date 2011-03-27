<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

/*
@@
@@ Author: KOK EDIT BY SAKIB
@@ VISIT MY site sakib.x7host.org 
@@ Happy Leeching
@@
*/

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], 0, 0, 0, 0, $_GET["proxy"],$pauth);
is_page($page);

if(preg_match("/videourl=\"(.*?)\";/i",$page, $match)){
preg_match("/<title>(.*?)<\/title>/i",$page, $title);
}else{
	html_error("URL not found.", 0);
}

$Url = parse_url($match[1]);
$FileName = $title[1].".flv";

insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));

?>