<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

	if($_GET["imageshack_tor"] == "on" && $_GET["tor_user"] && $_GET["tor_pass"] || $_GET["imageshack_tor"] == "on" && $imageshack_acc['user'] && $imageshack_acc['pass'])
	{
		$mainlink = $LINK;
		$auth_link = 'http://'.$Url["host"].'/auth.php';
		
		$Url = parse_url($auth_link);
		
		$post = array();
		$post["username"] = $_GET["tor_user"] ? $_GET["tor_user"] : $imageshack_acc['user'];
		$post["password"] = $_GET["tor_pass"] ? $_GET["tor_pass"] : $imageshack_acc['pass'];
		
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);
		
		if(preg_match('/fail/i', $page))
		{
			html_error('Incorrect un/pass', 0);
		}
		
		if(preg_match_all('/Set-Cookie: *(.+);/', $page, $cook)){
			$cookie = implode(';', $cook[1]);
		}else{
			html_error('Cookie not found.', 0);
		}
		if(preg_match('/\?action=zip/', $mainlink))
		{
			$Url = parse_url($mainlink);
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
		
			preg_match('/Location:.*(\?id.*)/', $page, $final);
			$mainlink = 'http://'.$Url["host"].$Url["path"].$final[1];
			//die($mainlink);
		}
		
		$Url = parse_url($mainlink);
		$FileName = !$FileName ? basename($Url["path"]) : $FileName;
		
		insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=GET&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".$_POST["$LINK"].($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
		
	}else{
		html_error('Use imageshack account', 0);
	}
?>