<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

//use PREMIUM
if (($_GET ["premium_acc"] == "on" && $_GET ["premium_user"] && $_GET ["premium_pass"]) || ($_GET ["premium_acc"] == "on" && $premium_acc ["sharebase"] ["user"] && $premium_acc ["sharebase"] ["pass"])) {
    $sbemail =$premium_acc ["sharebase"] ["user"];
    $sbpass = $premium_acc ["sharebase"] ["pass"];
    $posturl = 'http://sharebase.to/mlogin';
    $site="http://sharebase.to/";
    $postfields = array();
    $postfields['lg_mail'] = $sbemail;
    $postfields['lg_pass'] = $sbpass;
    $postfields['m_login'] = 'Login';
    $Url = parse_url($posturl);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),  $site, 0, $postfields, 0, $_GET["proxy"],$pauth);
    is_page($page);
    $PHPSID = cut_str($page, 'Set-Cookie: PHPSESSID=', ';');
    $memm = 'memm=' . cut_str($page, 'Set-Cookie: memm=', ';');
    $memp = 'memp=' . cut_str($page, 'Set-Cookie: memp=', ';');
    if( !$memp )    html_error( "Login Failed!. Please check your login detail" , 0 );
    $Url=parse_url($LINK);
    $sharebase_cookie = $memm .'; ' . $memp;
	$sharebase_cookie_phpsid = "PHPSESSID=$PHPSID; " . $sharebase_cookie;
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),  $site, $sharebase_cookie_phpsid, 0, 0, $_GET["proxy"],$pauth);
    is_page($page);
	is_present($page,"doesnt exist", "The file doesnt exist or has been cancelled", 0);
	
    $post = array();
    $post['asi'] = $PHPSID;
    $post["$PHPSID"] = urlencode('Download Now !');
    $Url = parse_url($LINK);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $sharebase_cookie_phpsid, $post, 0, $_GET["proxy"],$pauth);
    is_page($page);
    if (!$redir = trim(cut_str($page, 'Location: ', "\n"))) html_error("Download locator not found", 0);
    $Url = parse_url($redir);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $sharebase_cookie_phpsid, 0, 0, $_GET["proxy"],$pauth);    
    is_page($page);
    if (!$redir = trim(cut_str($page, 'Location: ', "\n"))) html_error("Final download link not found", 0);
    $flink = 'http://' . $Url['host'] . $redir;
    $Url = parse_url($flink);
    $FileName=basename($flink);
    insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($sharebase_cookie_phpsid)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".$LINK.($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
}
//use FREE
else
{
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, 0, 0, $_GET["proxy"],$pauth);
    is_page($page);
    is_present($page, "The download is deleted or the Download-Link is wrong.");
    
    $post=array();
    $post["free"]="Free";
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, $post, 0, $_GET["proxy"],$pauth);
    is_page($page);
    $snap = cut_str ( $page ,'class="a2"' ,'</div' );
    $FileName = trim(cut_str ( $snap ,'>' ,'<' ));
    if ($PHPSID = cut_str($page, 'Set-Cookie: PHPSESSID=', ';'))
    {
        $sharebase_cookie_phpsid = "PHPSESSID=$PHPSID;";
    } 
    else
    {
        html_error("Cookie not found", 0);
    }
    $wait = trim(cut_str ( $page ,'nCountDown =' ,';' ));
    insert_timer($wait);
    $post = array();
    $post['asi'] = $PHPSID;
    $post["$PHPSID"] = 'Download Now !';
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $sharebase_cookie_phpsid, $post, 0, $_GET["proxy"],$pauth);    
    is_page($page);
    if (preg_match('%you still must wait\s(.+)\s!%i', $page, $time)) html_error('Service says you must wait ' . $time[1], 0);
    is_present($page,"You already have", "You already have started a Download.", 0); 
    if (!$redir = trim(cut_str($page, 'Location: ', "\n"))) html_error("Download locator not found", 0);
    $Url = parse_url($redir);
    insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($sharebase_cookie_phpsid)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".$LINK.($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
}

/*************************\
 UPDATE BY KAOX 11-oct-09
\*************************/
?>