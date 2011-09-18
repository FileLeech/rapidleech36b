<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
class filestrack_com extends DownloadClass{
    public function Download($link){
        if ($_POST['step'] == "1") {
            $this->DownloadFree($link);
        } else {
            $this->Retrieve($link);
        }
    }
    private function Retrieve($link){
        $page=$this->GetPage($link);
        $Cookies="lang=english";
        $id=cut_str($page, 'name="id" value="', '"');
        $FileName=cut_str($page, 'name="fname" value="', '"');
        $post=array();
        $post['op']="download1";
        $post['usr_login']="";
        $post['id']=$id;
        $post['fname']=$FileName;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $page=$this->GetPage($link, $Cookies, $post, $link);
        is_present($page, "The file You requested  reached max downloads limit for Free Users","The file You requested reached max downloads limit for Free Users");
        if (preg_match("#You have to wait (\d+) minute, (\d+) seconds till next download#", $page,$message)){
            html_error($message);
            //insert_timer($message[1]*60+$message(2));
        }
        if (!preg_match("#(\d+)</span> seconds#", $page,$wait)){
            html_error("Error 0x01: Plugin is out of date");
        }
        $rand=cut_str($page, 'name="rand" value="', '"');
        $page = $this->GetPage("http://www.google.com/recaptcha/api/challenge?k=6LfsBboSAAAAAPgMPrGX2sPmOZNw9lkRcitBg4Z2+");
        $ch = cut_str($page, "challenge : '", "'");
        $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
        insert_timer($wait[1]);
        $data = $this->DefaultParamArr($link);
        $data['step']="1";
        $data['id']=$id;
        $data['rand']=$rand;
        $data['FileName']=$FileName;
        $data['recaptcha_challenge_field']=$ch;
        $this->EnterCaptcha($img, $data, 18);
        exit();
    }
    private function DownloadFree($link){
        $post=array();
        $post['op']="download2";
        $post['id']=$_POST['id'];
        $post['rand']=$_POST['rand'];
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $post['method_premium']="";
        $post['recaptcha_challenge_field']=$_POST['recaptcha_challenge_field'];
        $post['recaptcha_response_field']=$_POST['captcha'];
        $post['down_direct']="1";
        $FileName=$_POST['FileName'];
        $page=$this->GetPage($link, 0, $post, $link);
        if (!preg_match('#(http://.+:182.*)">#', $page, $dlink)){
            html_error("Error 0x02 :Plugin is out date");
        }
        $this->RedirectDownload($dlink[1], $FileName, 0, 0, $link);
        exit();
    }
}

/*
* by vdhdevil
*/
?>
