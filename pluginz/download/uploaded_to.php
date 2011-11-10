<?php
if (!defined('RAPIDLEECH')) {
    require_once ("404.php");
    exit ();
}

class uploaded_to extends DownloadClass {

    public function Download($link) {
        global $premium_acc, $ul_cookie_login_value;
        if (preg_match('/http:\/\/uploaded\.to\/folder\/[^"]+/i', $link, $dir)) {
            if (!$dir[0]) {
                html_error('Could\'nt find any link, please check again!');
            }
            $page = $this->GetPage($link);
            preg_match_all('%href="(file\/\w+\/from\/\w+)%', $page, $match, PREG_SET_ORDER);
            foreach ($match as $temp) {
                $arr_link[] = str_ireplace('href="', '', "http://uploaded.to/$temp[0]");
            }
            $this->moveToAutoDownloader($arr_link);
        } else {
            $page = $this->GetPage($link);
            is_present($page, "/404", "File not found");
        }
        unset($page);
        if ($_REQUEST["ul_acc"] == "on" && (!empty($_GET["ul_cookie"]) || !empty($_GET["ul_hash"]) || !empty($ul_cookie_login_value))) {
            if (!empty($_GET["ul_cookie"])) {
                $cookie = $_GET["ul_cookie"];
            } elseif (!empty($_GET["ul_hash"])) {
                $cookie = strrev(dcd($_GET["ul_hash"]));
            } else {
                $cookie = $ul_cookie_login_value;
            }
            $this->DownloadPremium($link, $cookie);
        } elseif (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["uploaded"] ["user"] && $premium_acc ["uploaded"] ["pass"])) {
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
        $Cookies = GetCookies($page);
        if (!preg_match('#(\d+)</span> seconds#', $page, $count)) {
            html_error("Error 0x01: Plugin is out of date");
        }
        insert_timer($count[1]);
        $page = $this->GetPage("http://www.google.com/recaptcha/api/challenge?k=6Lcqz78SAAAAAPgsTYF3UlGf2QFQCNuPMenuyHF3");
        $ch = cut_str($page, "challenge : '", "'");
        $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
        $page = $this->GetPage($img);
        $headerend = strpos($page, "\r\n\r\n");
        $pass_img = substr($page, $headerend + 4);
        write_file($download_dir . "uploaded_captcha.jpg", $pass_img);
        $data = $this->DefaultParamArr($link);
        $data["recaptcha_challenge_field"] = $ch;
        $data["step"] = "1";
        $data["Cookies"] = $Cookies;
        $this->EnterCaptcha($download_dir . "uploaded_captcha.jpg", $data, "10");
        exit;
    }

    private function DownloadFree($link) {
        $Cookies = $_POST["Cookies"];
        $post = array();
        $post["recaptcha_challenge_field"] = $_POST["recaptcha_challenge_field"];
        $post["recaptcha_response_field"] = $_POST["captcha"];
        $tmplink = str_replace("/file/", "/io/ticket/captcha/", $link);
        is_present($page, 'err:"limit-dl"', "You've reached your Free account limit");
        $page = $this->GetPage($tmplink, $Cookies, $post, $link);
        if (!preg_match("#http://.+/dl/[^']+#", $page, $dlink)) {
            html_error("Error 0x02: Plugin is out of date");
        }
        $this->RedirectDownload(trim($dlink[0]), "uploaded", $Cookies, 0, $link);
        exit;
    }

    private function DownloadPremium($link, $cookie = false) {
        $cookie = $this->login($cookie);
        $page = $this->GetPage($link, $cookie);
        is_present($page, "Traffic exhausted", "Premium account is out of Bandwidth");

        if (!preg_match('#http:\/\/stor(\d+)?\.uploaded\.to/dl\/[^\r"]+#', $page, $dlink)) {
            html_error("Error 1x01: Plugin is out of date");
        }
        $this->RedirectDownload(trim($dlink[0]), "uploaded", $cookie, 0, $link);
    }

    private function login($loginc = false) {
        global $premium_acc;
        if (!$loginc) {
            $user = ($_REQUEST["premium_user"] ? $_REQUEST["premium_user"] : $premium_acc["uploaded"]["user"]);
            $pass = ($_REQUEST["premium_pass"] ? $_REQUEST["premium_pass"] : $premium_acc["uploaded"]["pass"]);
            if (empty($user) || empty($pass)) {
                html_error("Login Failed: Username or Password is empty. Please check login data.");
            }
            $posturl = "http://uploaded.to/";
            $post = array();
            $post["id"] = $user;
            $post["pw"] = $pass;
            $page = $this->GetPage($posturl."io/login", 0, $post, $posturl."\r\nX-Requested-With: XMLHttpRequest"); //other way add xml request without edit http.php
            $cookie = GetCookies($page);
            is_present($page, 'err:"User and password do not match', 'Login Failed, please check your account');
        } elseif (strlen($loginc) == 84) {
            $cookie = 'login=' . $loginc;
        } else {
            html_error("[Cookie] Invalid cookie (" . strlen($loginc) . " != 84). Try to encode your cookie first!");
        }

        $page = $this->GetPage($posturl."me", $cookie);
        $cookie = $cookie . '; ' . GetCookies($page);
        is_present($page, '<em>Free</em>', 'Account free, please check ur premium account');
        is_present($page, 'ocation: http://uploaded.to', 'Cookie failed, please check ur account');

        return $cookie;
    }

}

/*
 * by vdhdevil 15-March-2011
 * Updated 01-May-2011
 * Fixed by Ruud v.Tony also add some improvement 11-09-2011
 *
 */
?>
