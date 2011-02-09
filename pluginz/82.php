<?php 

if (!defined('RAPIDLEECH'))
  {
  require_once("404.php");
  exit;
  }
  

    // pastikan jadi rghost.net
    $LINK = preg_replace("/rghost.ru/","rghost.net",$LINK);
    $Url = parse_url(trim($LINK));

    // klo misal ada private key.. contoh link : http://rghost.ru/2801924/private/e042e06445513359f5232318248e2f8e
    if(preg_match("%http://rghost.net/[0-9]+/private/(.+)%i", $LINK, $privatekeynya)) {
        $Url["query"] = "key=".$privatekeynya[1];
    }    
    
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, 0, 0, $_GET["proxy"],$pauth);
    is_page($page);        
    
    if (preg_match("%href=\"(http://rghost.net/download/[0-9]+/.+)\" class=\"header_link\"%i", $page, $regs)) {
        $dwnl = $regs[1];
    } else {
        if (preg_match("%href=\"(http://rghost.net/download/private/[0-9]+/.+)\" class=\"header_link\"%i", $page, $regs)) {
            $dwnl = $regs[1];
        }
        else {
            html_error("Download link not found",0);
        }
    }
    
    $Url = parse_url($dwnl);
    $FileName = basename($dwnl);
    insert_location("index.php?filename=".urlencode($FileName)."&force_name=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=" . $_GET["idx"] : ""));
    
    
// created by R0CKABILLY - cyberleech.info - 30 Sept 2010
// updated - 4 Oct 2010 - added private key support & rghost.ru support

?>