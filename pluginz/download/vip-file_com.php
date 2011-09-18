<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
   	
if (($_GET["premium_acc"] == "on" && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["vip-file"]["pass"]))
{

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	is_present($page,"File not found");
	
$uid = cut_str($page,'uid" value="','"');
if (!$uid) {
	$post['inoneweek']="1";
$post['submit_mbc']="%D0%9F%D1%80%D0%B0%D0%B2%D0%B8%D0%BB%D0%B0+%D0%BF%D1%80%D0%B8%D0%BD%D0%B8%D0%BC%D0%B0%D1%8E";

	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, 0, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);
		is_present($page,"File not found");
		$cookie.="; ".biscottiDiKaox($page);
}

$uid = cut_str($page,'uid" value="','"');
$name = cut_str($page,'name" value="','"');
$pin = cut_str($page,'pin" value="','"');
$realuid = cut_str($page,'realuid" value="','"');
$realname = cut_str($page,'realname" value="','"');
$md5crypt = cut_str($page,'md5crypt" value="','"');
$host = cut_str($page,'host" value="','"');
$ssserver = cut_str($page,'ssserver" value="','"');
$optiondir = cut_str($page,'optiondir" value="','"');
$sssize = cut_str($page,'sssize" value="','"');
$submit = cut_str($page,'download" value="','"');

$temp=  cut_str ( $page ,'id="block' ,'</form>' );
$UrlAct = "http://vip-file.com".cut_str ( $temp ,'action="' ,'"' );
//$UrlAct="http://vip-file.com/sms/check.php";
unset($post);
$post['pass']=$premium_acc["vip-file"]["pass"];
$post['uid']=$uid;
$post['name']=$name;
$post['fid']='';
$post['pin']=$pin;
$post['realuid']=$realuid;
$post['realname']=$realname;
$post['md5crypt']=$md5crypt;
$post['host']=$host;
$post['ssserver']=$ssserver;
$post['optiondir']=$optiondir;
$post['sssize']=$sssize;
$post['submit']=$submit;

$Url=parse_url($UrlAct);

$cookie.="; ".biscottiDiKaox($page);

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"],$pauth);
is_page($page);
$dwu = cut_str ( $page ,"href='" ,"'" );
$Url=parse_url($dwu);
$FileName = !$FileName ? basename($Url["path"]) : $FileName;
insert_location("index.php?filename=".urlencode($FileName)."&force_name=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));


}else{
	
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	is_present($page,"File not found");
	preg_match('/http:\/\/vip-file\.com\/download[^"]+/i', $page, $loca);
	$Url = parse_url($loca[0]);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
    preg_match('/Location:.+?\\r/i', $page, $loca);
    $redir = rtrim($loca[0]);
    preg_match('/http:.*/i', $redir, $loca);
    $Url=parse_url($loca[0]);
    $FileName = !$FileName ? basename($Url["path"]) : $FileName;
insert_location("index.php?filename=".urlencode($FileName)."&force_name=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
}

function biscottiDiKaox($content)
 {
 preg_match_all("/Set-Cookie: (.*)\n/",$content,$matches);
 foreach ($matches[1] as $coll) {
 $bis0=split("; ",$coll);
 $bis1=trim($bis0[0])."; ";
 $bis2=split("=",$bis1);
 $cek=$bis2[0]."=;"; 
 if(strpos($bis1,"=deleted")!== false || strpos($bis1,$cek) !== false) {
 }else{
if  (substr_count($bis,$cek)>0)
{$patrn=" ".$bis2[0]."=[^ ]+";
$bis=preg_replace("/$patrn/"," ".$bis1,$bis);     
} else {$bis.=$bis1;}}}  
$bis=str_replace("  "," ",$bis);     
return rtrim($bis);}

/*********\
 by kaox
\*********/

?>
