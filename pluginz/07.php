<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"] ,$pauth);
is_page($page);

if(preg_match('%- *(.+)</title>%', $page, $fn)){
	$name = str_replace(array(" ", ".", "'", "&", "/"), '_', $fn[1]). ($_GET['ytube_mp4'] ? '_HQ.mp4' : '_LQ.flv');

}elseif(preg_match("/name=\"title\"\scontent=\"(.*)\">/i", $page, $jd)){
	$name = preg_replace("/[^a-zA-Z0-9\-]/", "_", ucwords(strtolower($jd[1]))) . ($_GET['ytube_mp4'] ? '_HQ.mp4' : '_LQ.flv');

}

if(preg_match('/, "t": "(.+?)",/', $page, $yt)){
	$t = $yt[1];
}

if(preg_match('/"video_id": "(.+?)"/', $page, $yid)){
	$id = $yid[1];
}

if($_GET['ytube_mp4']){
	$directfile = 'http://youtube.com/get_video?video_id='.$id.'&t='.$t.'&fmt=18';
}else{
	$directfile = 'http://youtube.com/get_video?video_id='.$id.'&t='.$t;
}

$Url = parse_url($directfile);

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);


if(preg_match_all('/Set-Cookie: (.*)/i', $page, $cook)){
	$cookie = implode(";", $cook[1]);
}
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $directfile, $cookie, 0, 0, $_GET["proxy"],$pauth);
if(preg_match('/ocation: (.+)/i', $page, $loc)){
	$directlink = trim($loc[1]);
}else{
	echo "Error[getLOCATION1]";
	die;
}
$Url = parse_url($directlink);
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $directlink, $cookie, 0, 0, $_GET["proxy"],$pauth);
if(preg_match('/ocation: (.+)/i', $page, $loc)){
	$directlink = trim($loc[1]);
}else{
	echo "Error[getLOCATION1]";
	die;
}
/*
ob_start();
if($ch = curl_init()){
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_NOBODY, 0);
curl_setopt($ch, CURLOPT_URL, $directlink);
curl_exec($ch);
curl_close($ch);
$head_res = ob_get_contents();
}
ob_end_clean();
if(preg_match('/ocation: (.+)/', $head_res, $location)){
	$link = $location[1];
	$Url = parse_url($link);
	//die($head_res);
}else{
	$Url = parse_url($directlink);
}
*/
$Url = parse_url($directlink);
if($name){
	$FileName = $name;
}elseif($_GET['ytube_mp4']){
	$FileName = basename($Url["path"]);
}else{
	$FileName = basename($Url["path"]).".flv";
}
if($_GET['ytube_mp4'])
  {
insert_location("$PHP_SELF?filename=".urlencode($FileName)."&force_name=".urlencode($name)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($directlink)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
  }
else
  {
insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($directlink)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
  }
?>