<?php

if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class movreel_com extends DownloadClass{

    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["movreel_com"] ["user"] && $premium_acc ["movreel_com"] ["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }
    private function DownloadPremium($link){
        html_error("Not support now");
    }
    private function DownloadFree($link){
        $Urllogin="http://movreel.com/login.html";
        $post=array();
        $post['op']="login";
        $post['redirect']=$link;
        $post['login']="dvdriprl";
        $post['password']="123456";
        $post['x']="33";
        $post['y']="14";
        $page=$this->GetPage($Urllogin,0,$post,$link);
        $Cookies=GetCookies($page);
        $page=$this->GetPage($link, $Cookies, 0, $link);
        $id=cut_str($page, 'name="id" value="', '"');
        $FileName=cut_str($page, 'name="fname" value="', '"');
        unset($post);
        $post['op']="download1";
        $post['usr_login']="";
        $post['id']=$id;
        $post['fname']=$FileName;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $page=$this->GetPage($link,$Cookies,$post,$link);
        $rand=cut_str($page, 'name="rand" value="', '"');
        unset($post);
        $post['op']="download2";
        $post['id']=$id;
        $post['rand']=$rand;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $post['method_premium']="";
        $post['down_direct']="1";
        $post['down_direct']="1";
        $post['x']=rand(0,70);
        $post['y']=rand(0,19);
        $page=$this->GetPage($link, $Cookies, $post, $link);
        if (!preg_match('#(http://.+:182.+)">#', $page,$dlink)){
            html_error("Error 0x01: Plugin is out of date");
        }
        $this->RedirectDownload(trim($dlink[1]), $FileName, $Cookies, 0, $link);
        exit;
    }

}

//by vdhdevil
?>
