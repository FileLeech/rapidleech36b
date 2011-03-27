<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}


	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	is_present($page, "Page not found", "Sorry, the video at this URL has either been deleted or never existed in the first place.");

	$clid = cut_str($page,'clip_id=','"');
	$mt = cut_str($page,'name="title" content="','"');
	$FileName = str_replace (Array ("\\", "/", ":", " ", "*", "?", "\"", "<", ">", "|"), "_", trim($mt)).".flv";

	$Url = parse_url("http://www.vimeo.com/moogaloop/load/clip:{$clid}");
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	$resi = cut_str($page,'<request_signature>','</request_signature>');
	$resiex = cut_str($page,'<request_signature_expires>','</request_signature_expires>');
	$nlink = "http://www.vimeo.com/moogaloop/play/clip:{$clid}/{$resi}/{$resiex}/?q=sd";

	$Url = parse_url($nlink);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);

	if(preg_match('/Location: *(.+)/i', $page, $redir )){
	$dlink = trim( $redir[1] );
	$Url = parse_url( $dlink );
	}else{html_error( "Error getting download link" , 0 );}

	$FileName = !$FileName ? basename($Url["path"]) : $FileName;

insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));

?>
