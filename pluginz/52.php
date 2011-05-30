<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

	$urlPathValue = $Url["path"];
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	$cookie = "";
	preg_match_all("/Set-Cookie: ([^;]+;)/", $page, $cook);
	$arraySize = count($cook);

	for ( $i=0;$i<$arraySize;$i++)
	{
		$cookie=$cookie.array_shift($cook[1]);
	}
	$fileNumber = trim(cut_str($urlPathValue, "/doc/", "/"));
	
	$Href = "http://www.esnips.com/nsdoc/".$fileNumber."/?action=forceDL";
	$Url = parse_url($Href);
	$FileName = "file";
	
	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&cookie=".urlencode($cookie)."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
?>