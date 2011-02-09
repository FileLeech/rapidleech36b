<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

		$LINK =  str_replace("https", "http", $LINK);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);
		
		preg_match('/action="([^\"]*)"/i', $page, $redir);
		$Href = trim($redir[1]);
		
		$random_furk = cut_str($page,'<input type="hidden" name="rand" value="','" />');
		$countd = cut_str($page,'startFreeDownload(',');');
	
		insert_timer($countd, "Waiting link timelock");
	
		$post = Array();
		$post["rand"] = $random_furk;
		$Url = parse_url($Href);
		
		$FileName = !$FileName ? basename($Url["path"]) : $FileName;
	
	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Href)."&post=".urlencode(serialize($post))."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));

// by plapla
// Fix coutdown timer by Th3-882
?>