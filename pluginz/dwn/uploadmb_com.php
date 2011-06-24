<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
class uploadmb_com extends DownloadClass {
    public function Download($link) {
        $page=$this->GetPage($link);
        is_present($page, "The file you are requesting to download is not available","The file you are requesting to download is not available");
        $Cookies=GetCookies($page);
        insert_timer(6);
        $phpsessid=explode('=',$Cookies);
        $id=explode('=', $link);
        $post=array();
        $post["PHPSESSID"]=$phpsessid[1];
        $post["turingno"]="0";
        $post["id"]=$id[1];
        $post["DownloadNow"]="Download File";
        $page=$this->GetPage($link, $Cookies, $post, $link);
        if (!preg_match("#href='(/file.php.*)'>#",$page,$temp)){
            html_error("Error: Download Link Not Found");
        }
        preg_match("#addthis_title  = '(.*)'#", $page,$FileName);
        $dlink="http://www.uploadmb.com".$temp[1];
        $this->RedirectDownload($dlink, $FileName[1],$Cookies,0,$link,$FileName[1]);
        exit;
    }
}

//by vdhdevil
?>
