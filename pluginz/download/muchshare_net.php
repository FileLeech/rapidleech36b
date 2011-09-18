<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
class muchshare_net extends DownloadClass{
    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["muchshare_net"] ["user"] && $premium_acc ["muchshare_net"] ["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }
    private function DownloadFree($link){
        $post=array();
        $post['op']="login";
        $post['redirect']=$link;
        $post['login']="dvdriprl";
        $post['password']="220687";
        $page=$this->GetPage("http://muchshare.net/login.html", 0, $post, $link);
        $Cookies=GetCookies($page);
        $page=$this->GetPage($link,$Cookies,0,$link);
        if (preg_match("#You have to wait \d+ seconds till next download#", $page,$message)){
            html_error($message,0);
        }
        if (!preg_match("#.*>(\d+)<#",$page,$count)){
            html_error("Error: Get Count Time Error");
        }
        insert_timer($count[1]);
        $id=cut_str($page, 'name="id" value="', '"');
        $rand=cut_str($page, 'name="rand" value="', '"');
        $textfield=cut_str($page,'name="textfield" onclick="this.select()" value="','"');
        unset($post);
        $post["op"]="download2";
        $post["id"]=$id;
        $post["rand"]=$rand;
        $post["referer"]=$link;
        $post["method_free"]="";
        $post["method_premium"]="";
        $post["down_script"]="1";
        $post["textfield"]=$link;
        $post["textfield"]=$textfield;
        $page=$this->GetPage($link, $Cookies, $post,$link);
        if (!preg_match("#Location: (.*)#", $page,$temp)){
            html_error("Error: Download link not found");
        }
        $dlink=trim($temp[1]);
        $Url=parse_url($dlink);
        $FileName=basename($Url["path"]);
        $this->RedirectDownload($dlink, $FileName, $Cookies,0,$link);
        exit;
    }
    private function DownloadPremium($link){
        html_error("Please donate premium account for bulding this plugin");
    }
}

//by vdhdevil
?>
