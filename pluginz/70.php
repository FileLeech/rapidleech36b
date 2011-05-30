<?php
if (! defined ( 'RAPIDLEECH' ))
  {require_once("404.php");exit;}

	$Href = $LINK;
	$Url = parse_url($Href);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	
	$cookie = "";
	preg_match_all("/Set-Cookie: ([^;]+;)/", $page, $cook);
	$arraySize = count($cook);

	for ( $i=0;$i<$arraySize;$i++)
	{
		$cookie=$cookie.array_shift($cook[1]);
	}
	
	$post = array ();
	$post ["chosen_free"] = "Basic Download";

	global $Referer;
	$Referer = $Href;
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
	
	$count = trim ( cut_str ( $page, "var x = ", ";" ) );
	
	$current = trim ( cut_str ( $page, '<input type="hidden" name="current" value="', '">' ) );
	$limitReached = trim ( cut_str ( $page, '<input type="hidden" name="limit_reached" value="', '">' ) );
	
	insert_timer( $count, "Waiting link timelock");
	
	$post = array ();
	$post ["current"] = $current;
	$post ["limit_reached"] = $limitReached;
	$post ["download_now"] = "Click here to download";
	
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
	is_present ( $page, "Performing scheduled network maintenance" );
		
	preg_match('/Location: *(.+)/i', $page, $newredir );		
	
	$FileName = "";		
	$Href = trim ( $newredir [1] );
	$Url = parse_url ( $Href );
	$FileName = ! $FileName ? basename ( $Url ["path"] ) : $FileName;
	
	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($Href).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));	

// Created by rajmalhotra on 29 Nov 09	
// Updated by rajmalhotra on 14 Dec 09 for adding server maintaince error message	
?>