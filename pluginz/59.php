<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}
	
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	is_present($page,"disabled=\"disabled\"","Downloading is in process from your IP-Address. Try again after few minutes");
	$cookie = GetCookies($page);
	$frmfree=cut_str($page,'id="dvifree">','</form');
	$act="http://up-file.com".cut_str($frmfree,'action="','"');
	$md5crypt=cut_str($frmfree,'md5crypt" value="','"');
	$post=array();
	$post["md5crypt"]=$md5crypt;
	$post["free"]="Download+the+file";
    $Url=parse_url($act);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $referrer, $cookie, $post, 0, $_GET["proxy"],$pauth);
    is_page($page);
    preg_match('/location: *(.*)/i', $page, $redir);
	$Href = rtrim($redir[1]);
	$Url = parse_url($Href);
	
	$fileName = basename($Url["path"]);
	
	insert_location("$PHP_SELF?filename=" . urlencode ( $FileName ) ."&force_name=".urlencode($fileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
	
// written by kaox 05/07/2009
?>