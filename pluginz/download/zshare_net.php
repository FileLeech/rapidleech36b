<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

	    $Url["path"] = str_replace("/video/","/download/",$Url["path"]);
	    $Url["path"] = str_replace("/audio/","/download/",$Url["path"]);

		$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);
		
		is_present($page,"file-404","File Not Found");
	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookie = implode(';',$temp[1]);

		
		$post = array();
		$post["referer2"] = cut_str($page, 'referer2" value="', '"');
		$post["download"] = 1;
		$post["imageField.x"] = rand(1,140);
		$post["imageField.y"] = rand(1,20);
		$Referer = $LINK;

		$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);
	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookie = implode(';',$temp[1]);

	    
	    $enclink= cut_str($page,"link_enc=new Array('","')");
	    $linkdown = preg_replace('/[,\']/i', '', $enclink);
	  
		if($linkdown){
			$Url = parse_url($linkdown);
		$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);


		}else{html_error("Link not found",0);}
		insert_timer("60");


	$FileName = basename($Url["path"]);
		
	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));



?>