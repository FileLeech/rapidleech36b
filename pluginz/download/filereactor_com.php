<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class filereactor_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["filereactor_com"] ["user"] && $premium_acc ["filereactor_com"] ["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }
    private function DownloadFree($link){
        $page=$this->GetPage($link);
        is_present($page, "No such file with this filename","No such file with this filename");
        is_present($page, 'File Not Found','File Not Found');
        $Cookies=GetCookies($page);
        $id=cut_str($page, 'name="id" value="', '"');
        $FileName=cut_str($page,'name="fname" value="','"');
        $post=array();
        $post['op']="download1";
        $post['usr_login']="";
        $post['id']=$id;
        $post['fname']=$FileName;
        $post['referer']="";
        $post['method_free']="Free Download";
        $page=$this->GetPage($link,$Cookies,$post,$link);
        $rand=cut_str($page,'name="rand" value="','"');
        if (preg_match('#You have to wait (\d+) minutes, (\d+) seconds till next download#',$page,$message)){
            //insert_timer($message[1]*60+$message[2]);
            html_error($message[0]);
        }
        if (!preg_match('#(\d+)</span> seconds#', $page,$wait)){
            insert_timer($wait[1]);
        } else {
            insert_timer(30);
        }
        unset($post);
        $post['op']="download2";
        $post['id']=$id;
        $post['rand']=$rand;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $post['method_premium']="";
        $post['down_direct']="1";
        $page=$this->GetPage($link,$Cookies,$post,$link);
        if (!preg_match('#(http.*:182.*)">#', $page,$dlink)){
            html_error("Error 1: Plugin is out of date");
        }
        $this->RedirectDownload(trim($dlink[1]), $FileName, $Cookies, 0, $link,$FileName);
        exit;
    }
    private function DownloadPre($link){
        html_error("Please donate premium account to build downloading Premium");
    }
}

//by vdhdevil
?>
