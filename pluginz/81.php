<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
        
    $filed=split("/",$Url["path"]);        
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, 0, 0, 0, $_GET["proxy"],$pauth);

    is_page($page);
    is_present($page,"File not found");
    $html = file(urldecode($_GET["link"]));

    foreach ($html as $line) {
        
        if (preg_match('/href="([^"]+)"/', $line,$match)) {

            if (eregi(".com/files",$match[0])) {
                $splitted = ereg_replace("href=","",strip_tags($match[0]));
                $text = str_replace('"', '', $splitted);
                //echo $text;
                    };
        };    
    };


    preg_match_all('/Set-Cookie: *(.+);/', $page, $cook);
    $cookie = implode(';', $cook[1]);
    echo $cookie;
        $dwnlk="http://www.savefile.com/downloadmin/".$filed["2"]."?".$cookie;

    $Url = parse_url($dwnlk);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
    is_page($page);
    preg_match('/url=[^\'"]+/', $page, $down);
    $downf = str_replace("url=","",$text);
    $Url = parse_url($downf);
    $FileName = !$FileName ? basename($Url["path"]) : $FileName;

    insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
    // fixed by kaox
?>