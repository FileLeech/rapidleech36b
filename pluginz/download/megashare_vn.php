<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class megashare_vn extends DownloadClass {
      public function Download($link) {
        global $premium_acc, $options;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["megashare_vn"] ["user"] && $premium_acc ["megashare_vn"] ["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }
    private function DownloadFree($link){
        html_error("Download Free not support now");
    }
    private function DownloadPremium($link){
        global $premium_acc;
        $page=$this->GetPage("https://id.megaplus.vn/login?service=http%3A%2F%2Fshare.vnn.vn%2Fmegavnnplus.php%3Fservice%3Dlogin");
        $Cookies=GetCookies($page);
        $lt=cut_str($page, 'name="lt" value="', '"');
        $post=array();
        $post["username"] = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["megashare_vn"] ["user"];
        $post["password"] = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["megashare_vn"] ["pass"];
        $post["lt"]=$lt;
        $post["_eventId"]="submit";
        $post["submit"]="%C3%90%C4%82NG+NH%E1%BA%ACP+";
        $page=$this->GetPage("https://id.megaplus.vn/login?service=http%3A%2F%2Fshare.vnn.vn%2Fmegavnnplus.php%3Fservice%3Dlogin", $Cookies, $post);
        $Cookies.="; ".GetCookies($page);
        if (!preg_match("#Location: (.*)#i", $page, $tlink)){
            html_error("Error 1x01: Plugin is out of date");
        }
        $page=$this->GetPage(trim($tlink[1]), $Cookies);
        $tmp=explode(";", GetCookies($page));
        $Cookies.="; ".$tmp[1];
        $page=$this->GetPage($link, $Cookies, 0, $link);
        if (!preg_match("#location: (.*)#i", $page, $tlink)){
            html_error("Error 1x02: Plugin is out of date");
        }
        $page=$this->GetPage(trim($tlink[1]), $Cookies, 0, $link);
        if (!preg_match('#http://.+8080[^"]+#', $page,$dlink)){
            html_error("Error 1x03: Plugin is out of date");
        }
        $Url=parse_url($dlink[0]);
        $FileName=basename($Url['path']);
        $this->RedirectDownload($dlink[0], $FileName, $Cookies);
        exit;
    }
}
/*
 * by vdhdevil 26-March-2011
 */
?>
