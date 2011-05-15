<?php

if (!defined('RAPIDLEECH')) {
    require_once("index.html");
    exit;
}

class turbobit_net extends DownloadClass {

    public function Download($link) {
        global $premium_acc, $options;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["turbobit_net"] ["pass"])) {
            $this->DownloadPremium($link);
        } elseif ($_POST['step'] == "1") {
            $this->DownloadFree($link);
        } else {
            $this->Retrieve($link);
        }
    }

    private function Retrieve($link) {
        global $download_dir;
        $page = $this->GetPage($link);
        is_present($page, "The file you are looking for is not available", "The file has been deleted");
        
        preg_match_all('#Set-Cookie: ([^;]+)#', $page, $tmp);
        $TmpCookies = $tmp[1][2];
        $Cookies = $tmp[1][1] . "; " . $tmp[1][2];
        $tmp = cut_str($page, 'class="free wide" href="', '"');
        if (!$tmp) {
            html_error("Plugin is out of date");
        }
        $flink = "http://turbobit.net" . $tmp;
        $page = $this->GetPage($flink, $Cookies, 0, $link);
        preg_match_all('#Set-Cookie: ([^;]+)#', $page, $tmp);
        $Cookies = $TmpCookies . "; " . $tmp[1][1];
        if (preg_match('#(\d+)</span> seconds#', $page,$count)){
            html_error("You have reached the limit of connections,try downloading again after ".$count[1]." seconds");
        }
        if (!preg_match("#value = '(.*)' name = 'captcha_type'#", $page, $captcha_type)) {
            html_error("Error 0x02:Plugin is out of date");
        }
        if (!preg_match("#value = '(.*)' name = 'captcha_subtype'#", $page, $captcha_subtype)) {
            html_error("Error 0x03: Plugin is out of date");
        }
        $data = array();
        $data['step'] = "1";
        $data['link'] = urlencode($link);
        $data['Cookies'] = $Cookies;
        $data['flink'] = $flink;
        $data['captcha_type'] = $captcha_type[1];
        $data['captcha_subtype'] = $captcha_subtype[1];
        if (!preg_match('#http.+/captcha/[^"]+#', $page, $img)) {
            if (strpos($page, "http://api.recaptcha.net/noscript?k=6LcTGLoSAAAAAHCWY9TTIrQfjUlxu6kZlTYP50_c")) {
                $page = $this->GetPage("http://www.google.com/recaptcha/api/challenge?k=6LcTGLoSAAAAAHCWY9TTIrQfjUlxu6kZlTYP50_c");
                $ch = cut_str($page, "challenge : '", "'");
                $data["recaptcha_challenge_field"] = $ch;
                $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
                $page = $this->GetPage($img);
                $headerend = strpos($page, "\r\n\r\n");
                $pass_img = substr($page, $headerend + 4);
                write_file($download_dir . "turbobit_captcha.jpg", $pass_img);
                $img_src = $download_dir . "turbobit_captcha.jpg";
            }else
                html_error("Error 0x03: Plugin is out of date");
        } else {
            $page = $this->GetPage($img[0], $Cookies, 0, $flink);
            $headerend = strpos($page, "\r\n\r\n");
            $pass_img = (substr($page, $headerend + 4));
            if (preg_match("#\w{4}\r\n#", $pass_img)){
                $t=strpos($pass_img, "P");
                $pass_img=ltrim(substr($pass_img, $t-2),"\r\n");
            }
            write_file($download_dir . "turbobit_captcha.png", $pass_img);
            $img_src = $download_dir . "turbobit_captcha.png";           
        }
        $this->EnterCaptcha($img_src, $data, '10');
        exit;
    }

    private function DownloadFree($link) {
        $post = array();
        if (!empty ($_POST["recaptcha_challenge_field"]))
        {   $post['recaptcha_challenge_field']=$_POST["recaptcha_challenge_field"];
            $post['recaptcha_response_field']=$_POST['captcha'];
        } else {
            $post['captcha_response'] = urlencode($_POST['captcha']);
        }
        $post['captcha_type'] = $_POST['captcha_type'];
        $post['captcha_subtype'] = $_POST['captcha_subtype'];
        $Cookies = $_POST['Cookies'];
        $flink = $_POST['flink'];
        $page = $this->GetPage($flink, $Cookies, $post, $flink);
        is_present($page, "Incorrect, try again!", "Incorrect Captcha");
        if (!strpos($page, "limit: 60")) {
            html_error("Error 0x11: Plugin is out of date");
        }
        insert_timer(60);
        $rlink=str_replace("/free/", "/timeout/", $flink);
        $page=$this->GetPage($rlink, $Cookies, 0, $flink);
        if (!preg_match("#/download/[^']+#", $page,$tmp)){
            echo "<pre>";var_dump(nl2br(htmlentities($page)));echo "</pre>";
            html_error("Error 0x12: Plugin is out of date");
        }
        $dlink="http://turbobit.net".$tmp[0];
        $page=$this->GetPage($dlink, $Cookies, 0, $rlink);
        if (!preg_match("#Location: (.*)#", $page,$rlink)){
            html_error("Error 0x13: Plugin is out of date");
        }
        $this->RedirectDownload(trim($rlink[1]), "turbobit", 0, 0, $dlink);
        exit;
    }

    private function DownloadPremium($link) {
        global $premium_acc;

        $page = $this->GetPage('http://turbobit.net/');
        preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
        $cookie = $temp[1][2]."; ".$temp[1][1];
        $page = $this->GetPage("http://turbobit.net/en", $cookie, 0, 0);

        $post = array();
        $post["code"] = ($_GET["premium_pass"] ? $_GET["premium_pass"] : $premium_acc["turbobit"]["pass"]);
        $page = $this->GetPage("http://turbobit.net/payments/getaccess/", $cookie, $post, 0);
        is_notpresent($page, "Turbo-access granted", "Invalid password");

        $page = $this->GetPage($link, $cookie, 0, 0);
        is_present($page, "The file you are looking for is not available", "The file has been deleted");
        
        $dsrg = cut_str($page,'<div class="download-file">','Download file');
        $durl = cut_str($dsrg,'href="','"');
        $link = "http://turbobit.net".$durl;
        $page = $this->GetPage($link, $cookie, 0, 0);
		
		if (!stristr ( $page, "Location:" )) {
			html_error("Error : Plugin out of date!");
		}
        $dlink = cut_str ($page ,"Location: ","\r");
        $Url = parse_url($dlink);
        $FileName = cut_str($dlink, 'name=','&');
        $this->RedirectDownload($dlink, $FileName, $cookie);
        exit();

    }

}

//Turbobit Download Plugin by vdhdevil & Ruud v.Tony 8-4-2011
?>