<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

if ($_GET ["step"] != "second") {

		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);

	if(preg_match('/Location: (.*)/', $page, $newredir))
	{
	$newdir = $newredir[1];
	$LINK = trim($newredir[1]);
	$Url = parse_url($newdir);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookie = "language=en; ".implode(';',$temp[1]);
	}
	$nfd = cut_str($page,'Next free download from your ip will be available in <b>','</b>');
	is_present($page,"Next free download from your ip", "Next free download from your ip will be available in <b>$nfd minutes</b>.", 0);
	is_present($page,"File not found", 0);
	is_present($page,"File is temporary unavailable", 0);
	is_present($page,"Only premium users can download files of this size", "This file is only available for premium users.", 0);

	preg_match('%download_timeleft_timer" id="(.*)">(.*)</div>%U', $page, $count);
	$countdown = $count[2];
//	insert_timer($countdown, "Please wait until download will start");
	$cimg = cut_str($page,'src="/capture.gif?','"');
	$cimg = "http://www.extabit.com/capture.gif?".$cimg;

	$imgfile = $PHP_SELF."?image=".urlencode($cimg)."&referer=".urlencode($LINK)."&cookie=".urlencode($cookie); 

	$code = '<center>';
	$code .= '<form id="cmn_form" method="post" action="'.$PHP_SELF.(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "").'">'.$nn;
	$code .= '<input type="hidden" name="step" value="second">'.$nn;
	$code .= '<input type="hidden" name="link" value="'.$LINK.'">'.$nn;
	$code .= '<h4><img src='.$imgfile.'><br>here: <input type="text" name="captcha"><br>'.$nn;
	$code .= '<input type="hidden" name="cookie" value="'.urlencode($cookie).'">'.$nn;
	$code .= '<input type="submit" value="Download">'.$nn;
	$code .= '</form></h4></center>';
		echo ($code) ;

}else{

    	$cookie = urldecode($_REQUEST["cookie"]);
    	$LINK = $_REQUEST[link];
	$Referer = $LINK."?go";
	$caplink = $LINK."?capture=".$_REQUEST["captcha"];

	$Url = parse_url("$caplink");
	$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

		$err_info = cut_str($page,'err":"','"');
		if($err_info){echo "<center>$err_info</center>";}
	is_notpresent ( $page, '"ok":true', "Entered digits are incorrect." );
	$dlink = cut_str($page,'\/file\/','"');
	if (!$dlink){html_error( "Error getting download link" , 0 );}
	$dlink = "http://www.extabit.com/file/".$dlink;

	$Url = parse_url($dlink);
	$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	$mtl = trim(cut_str($page,'download_filename" class="df_archive">','<'));
	$alink = cut_str($page,'gen_link">','>click here to download');
	$ddlink = trim(cut_str($alink,'href="','"'));
	if (!$ddlink || strpos($ddlink,'http://')===false){html_error("Error getting direct link.");}

	$Url = parse_url($ddlink);
	$FileName = $mtl;

insert_location("$PHP_SELF?filename=". urlencode($FileName)."&force_name=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));


}
?>