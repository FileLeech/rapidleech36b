<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
class videoweed_com extends DownloadClass {
    public function Download($link){
        $page=$this->GetPage($link);
        $Cookies=GetCookies($page);
        if (!preg_match('#flashvars.file="(.*)"#', $page,$dlink)){
            html_error("Error - Download link not found");
        }
        $Url=parse_url($dlink[1]);
        $FileName=basename($Url["path"]);
        $this->RedirectDownload($dlink[1], $FileName,$Cookies);
        //echo "<pre>";var_dump(nl2br(htmlentities($page)));echo "</pre>";
        exit;
    }
}
/*
 * by vdhdevil 21-Dec-2010
 */
?>
