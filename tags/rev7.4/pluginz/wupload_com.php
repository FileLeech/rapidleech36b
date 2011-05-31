<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class wupload_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
           ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["wupload_com"] ["user"] && $premium_acc ["wupload_com"] ["pass"])) {
            $this->DownloadPremium($link);
        } elseif ($_POST['step'] == "1") {
            $this->DownloadFree($link);
        } else {
            $this->PrepareFree($link);
        }
    }

    private function PrepareFree($link) {
        global $Referer;

        $page = $this->GetPage($link);
        is_present($page, "Sorry! This file has been deleted.", "Sorry! This file has been deleted.");
        if (preg_match('/\/file\/(\d+)/i', $link, $match)) {
            $id = $match[1];
        }
        if (preg_match('/<a href="(.*)" id="free_download">/', $page, $match)) {
            $link = "http://www.wupload.com/file/$id/$match[1]";
        }
        $page = $this->GetPage($link, 0, 0, $referer);
        if (stristr ( $page, "Please wait" )) {
            preg_match('/var countDownDelay = ([0-9]+);/', $page, $wait);
            $this->CountDown($wait[1]);
        }
        $tm = cut_str($page, "name='tm' value='","'");
        $tm_hash = cut_str($page, "name='tm_hash' value='","'");
        $post = array();
        $post['tm'] = $tm;
        $post['tm_hash'] = $tm_hash;
        $page = $this->GetPage($link, 0, $post, $referer);
        if (stristr ( $page, "Please enter the captcha below:" )) {
            if (preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $k)) {
                $k = $k[1];
                $cachestop = rand();
            } else {
                html_error("Error getting CAPTCHA data.", 0);
            }
            $page = $this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$k&cachestop=".$cachestop."&ajax=1");
            $ch = cut_str($page, "challenge : '", "'");
            if ($ch) {
                $img = "http://www.google.com/recaptcha/api/image?c=".$ch;
                $page = $this->GetPage($img);
                $capt_img = substr($page, strpos($page, "\r\n\r\n") + 4);
                $imgfile = DOWNLOAD_DIR."wupload.jpg";
                if (file_exists($imgfile)) {
                    unlink($imgfile);
                }
                write_file($imgfile, $capt_img);
            } else {
                html_error("Error getting CAPTCHA image.", 0);
            }

            $data = array();
            $data['step'] = '1';
            $data['link'] = $link;
            $data['recaptcha_challenge_field'] = $ch;
            $this->EnterCaptcha($imgfile, $data, 20);
            exit();
        }
    }

    private function DownloadFree($link) {
        global $Referer;

        $post = array();
        $post['recaptcha_challenge_field'] = $_POST["recaptcha_challenge_field"];
        $post['recaptcha_response_field'] = $_POST["captcha"];
        $link = $_POST["link"];
        $page = $this->GetPage($link, 0, $post, $referer);
        if (!preg_match('/http:\/\/.+wupload\.com\/download\/[^\'"]+/i', $page, $dl)) {
            html_error("Error: Download link not found, plugin need to be updated!");
        }
        $dlink = trim($dl[0]);
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, 0, 0, $referer);
        exit ();
    }

    private function DownloadPremium($link) {
        global $premium_acc;
        $post = array();
        $post["email"] = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["wupload_com"] ["user"];
        $post["redirect"] = "/";
        $post["password"] = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["wupload_com"] ["pass"];
        $post["rememberMe"] = 1;
        $page = $this->GetPage("http://www.wupload.com/account/login", 0, $post, "http://www.wupload.com/");
        preg_match('#Set-Cookie: PHPSESSID=([a-zA-Z0-9]+); expires=#', $page, $cookie4id);
        $cookie1 = "lang=".cut_str($page,'Set-Cookie: lang=',";")."; ";
        $cookie2 = "role=premium; ";
        $cookie3 = "PHPSESSID=".$cookie4id[1]."; ";
        $cookie4 = "rememberMe=".cut_str($page,'Set-Cookie: rememberMe=',";")."; ";
        $cookie5 = "email=".cut_str($page,'Set-Cookie: email=',";")."; ";
        $cookie6 = "nickname=".cut_str($page,'Set-Cookie: nickname=',";")."; ";
        $cookie7 = "isAffiliate=".cut_str($page,'Set-Cookie: isAffiliate=',";")."; ";
        $cookies = "$cookie1$cookie2$cookie3$cookie4$cookie5$cookie6$cookie7";
        $page = $this->GetPage($link, $cookies, 0, 0);
        if (!preg_match('#Location: (http:\/\/s([0-9]+)\.wupload\.com\/.+\/([a-z0-9]+))#', $page, $dlink)) {
            html_error("Error 1x01: Plugin is out of date");
        }
        $this->RedirectDownload(trim($dlink[1]), "wupload", $cookies, 0, $link);
        exit;
    }

}

//wupload free download plugin by Ruud v.Tony 16-05-2011
//updated by nastrove to support premium
?>