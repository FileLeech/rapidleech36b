<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class filebase_to extends DownloadClass {
    public function Download($link) {
        global $premium_acc, $options;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["filebase_to"] ["user"] && $premium_acc ["filebase_to"] ["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }
    private function DownloadPremium($link){
        html_error("Please donate premium account for support building premium download");
    }
    private function DownloadFree($link){
        $page=$this->GetPage($link);
        is_present($page, "Fehler 404 - Dieses Datei wurde leider nicht gefunden", "Message: File Not Found");
        $Cookies="flanguage=en";
        $uid=cut_str($page, 'id="uid" value="', '"');
        $post['uid']=$uid;
        $post['dl_free3']="Normal Download";
        $page=$this->GetPage($link,$Cookies,$post,$link);
        insert_timer(30);
        unset($post);
        $post['submit']="Download";
        $post['captcha']="ok";
        $post['uid']=$uid;
        $post['filetype']="file";
        $page=$this->GetPage($link, $Cookies, $post, $link);
        if (!preg_match('#(http://\d+.\d+.*)" #', $page,$dlink)){
            html_error("Error 0x01: Plugin is out of date");
        }
        $Url=parse_url(trim($dlink[1]));
        $FileName=basename($Url['path']);
        $this->RedirectDownload(trim($dlink[1]), $FileName, $Cookies);
        exit();
    }
}
// by vdhdevil Jan-02-2011
?>
