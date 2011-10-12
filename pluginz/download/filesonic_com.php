<?php
if (!defined('RAPIDLEECH')) {
    require_once("404.php");
    exit;
}

class filesonic_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc, $Referer;
        // define the main domain first so we can get the home location
        if (!$_REQUEST['step']) {
            $check = $this->GetPage('http://www.filesonic.com/');
            if (preg_match('@Location: (http:\/\/www\.([^\/]+)\/?)@i', $check, $temp)) {
                $home = trim($temp[2]);
            } else { //if it's not redirected
                $home = 'filesonic.com';
            }
            unset($check);
        }
        $link = preg_replace('@http:\/\/www\.([^\/]+)\/@', "http://www.$home/", $link);
        if (preg_match("@http:\/\/www\.$home\/folder\/[^|\r|\n]+@i", $link, $dir)) {
            if (!$dir[0]) html_error('Invalid filesonic folder link!');
            $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1");
            preg_match_all('/<\/span><a href="(.*)">/i', $page, $single);
            if (!$single[1]) html_error('Can\'t find any filesonic single link!');
            $this->moveToAutoDownloader($single[1]);
        } else {
            if (!preg_match('@\/file\/(\d+)\/?@i', $link, $ids)) {
                preg_match('@\/file\/[a-zA-Z]\d+\/(\d+)\/?@i', $link, $ids);
            }
            $id = trim($ids[1]);
            if (!isset($id) && $id == '') html_error('Can\'t find filesonic link id');
            $link = "http://www.$home/file/$id";
        }
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["filesonic"] ["user"] && $premium_acc ["filesonic"] ["pass"])) {
            $this->Login($link, "http://www.$home/");
        } elseif ($_POST['step'] == "password") {
            $post["passwd"] = $_POST['password'];
            $link = urldecode($_POST['link']);
            if ($_POST['download'] == 'premium') {
                $cookie = decrypt(urldecode(trim($_POST["cookie"])));
                return $this->DownloadPremium($link, $cookie, $this->GetPage($link, $cookie, $post, $Referer."\r\nX-Requested-With: XMLHttpRequest"));
            } else {
                $cookie = urldecode($_POST['cookie']);
                return $this->DownloadFree($link, $cookie, $this->GetPage($link, $cookie, $post, $Referer."\r\nX-Requested-With: XMLHttpRequest"));
            }
        } elseif ($_POST['step'] == "Captcha") {
            $post['recaptcha_challenge_field'] = $_POST["recaptcha_challenge_field"];
            $post['recaptcha_response_field'] = $_POST["recaptcha_response_field"];
            $cookie = urldecode($_POST["cookie"]);
            $link = urldecode($_POST["link"]);
            return $this->DownloadFree($link, $cookie, $this->GetPage($link, $cookie, $post, $Referer));
        } elseif ($_POST['step'] == 'countdown') {
            $link = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            return $this->DownloadFree($link, $cookie, $this->GetPage($link, $cookie, 0, $Referer));
        } else {
            $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1");
            is_present($page, 'This file has been marked as private', 'This file has been marked as private by its owner and cannot be downloaded');
            is_present($page, 'This file was deleted', 'This file was deleted');
            $cookie = GetCookies($page) . "; lang=en; isJavascriptEnable=1";
            if (preg_match('%<a href="(.*)" id="free_download">%', $page, $match)) $link = "http://www.$home/file/$match[1]";
            $this->DownloadFree($link, $cookie, $this->GetPage($link, $cookie, 0, $Referer));
        }
    }

    private function DownloadFree($link, $cookie, $page) {
        global $Referer;

        is_present($page, 'Free users may only download 1 file at a time.', 'Free users may only download 1 file at a time.');
        if (preg_match('/var countDownDelay = (\d+);/', $page, $wait)) {
            if ($wait[1] > 90) {
                $data = $this->DefaultParamArr($link, $cookie);
                $data['step'] = 'countdown';
                $this->JSCountdown($wait[1], $data);
            } else {
                $this->CountDown($wait[1]);
            }
        }
        $tm = cut_str($page, "name='tm' value='", "'");
        $tm_hash = cut_str($page, "name='tm_hash' value='", "'");
        if (!empty($tm) && !empty($tm_hash)) {
            $page = $this->GetPage($link, $cookie, array('tm' => $tm, 'tm_hash' => $tm_hash), $Referer);
        }
        if (stristr($page, 'Please Enter Password')) {
            $data = $this->DefaultParamArr($link, $cookie);
            $data['step'] = 'password';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (stristr($page, "Please Enter Captcha")) {
            if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $cid)) {
                html_error('Can\'nt find captcha data');
            }
            $data = $this->DefaultParamArr($link, $cookie, $Referer);
            $data['step'] = 'Captcha';
            $data['recaptcha_challenge_field'] = cut_str($this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$cid[1]&cachestop=" . rand() . "&ajax=1"), "challenge : '", "'");
            $this->Show_reCaptcha($cid[1], $data);
            exit();
        }
        if (!preg_match('@http:\/\/[\w.]+\/download\/[^|\'?"?]+@i', $page, $dl)) html_error('Error: Final Download link for filesonic free can\'t be found!');
        $dlink = trim($dl[0]);
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, $cookie, 0, $Referer);
        exit();
    }

    private function Login($link, $domain) {
        global $premium_acc, $Referer;

        $user = $pass = '';
        if ($_REQUEST['iuser'] != '' && $_REQUEST['ipass'] != '') {
            $user = $_REQUEST['iuser'];
            $pass = $_REQUEST['ipass'];
        } else if ($_REQUEST['premium_user'] != '' && $_REQUEST['premium_pass'] != '') {
            $user = $_REQUEST['premium_user'];
            $pass = $_REQUEST['premium_pass'];
        } else if ($premium_acc["filesonic"]['user'] && $premium_acc["filesonic"]['pass']) {
            $user = $premium_acc["filesonic"]['user'];
            $pass = $premium_acc["filesonic"]['pass'];
        } else {
            html_error('Login Failed: Can\'t get filesonic username or password!');
        }

        $post = array();
        $post['email'] = $user;
        $post['redirect'] = '/';
        $post['password'] = $pass;
        $post['rememberMe'] = '1';
        $page = $this->GetPage($domain."user/login/", "lang=en; isJavascriptEnable=1", $post, $domain."\r\nX-Requested-With: XMLHttpRequest");
        $cookie = CookiesToStr(GetCookiesArr($page, true, $dval)) . "; lang=en; isJavascriptEnable=1";
        is_present($page, "Provided password does not match.", "Provided password does not match.");
        is_present($page, "No user found with such email.", "No user found with such email.");
        is_present($cookie, 'role=free', 'Account Free, Please check your premium account!');

        return $this->DownloadPremium($link, $cookie, $this->GetPage($link, $cookie, 0, $Referer));

    }

    private function DownloadPremium($link, $cookie, $page) {
        global $Referer;

        is_present($page, 'This file has been marked as private', 'This file has been marked as private by its owner and cannot be downloaded');
        is_present($page, 'This file was deleted', 'This file was deleted');
        if (stristr($page, "Please Enter Password")) {
            $data = $this->DefaultParamArr($link, encrypt($cookie));
            $data['step'] = 'password';
            $data['download'] = 'premium';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (!preg_match('@http:\/\/[\w.]+\/download\/[^|\r|\n|\'?"?]+@i', $page, $dl)) html_error('Error: Final Download link for premium can\'t be found!');
        $dlink = trim($dl[0]);
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, $cookie);
    }

    private function EnterPassword($page, $inputs) {
        global $PHP_SELF;

        echo "\n" . '<center><form action="' . $PHP_SELF . '" method="post" >' . "\n";
        foreach ($inputs as $name => $input) {
            echo "<input type='hidden' name='$name' value='$input' />\n";
        }
        echo '<h4>Enter password here: <input type="text" name="password" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Submit" /></h4>' . "\n";
        echo "<script type='text/javascript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
        echo "\n</form></center>\n</body>\n</html>";
        exit();
    }

    private function Show_reCaptcha($pid, $inputs) {
        global $PHP_SELF;

        if (!is_array($inputs)) {
            html_error("Error parsing captcha data.");
        }
        // Themes: 'red', 'white', 'blackglass', 'clean'
        echo "<script language='JavaScript'>var RecaptchaOptions={theme:'white', lang:'en'};</script>\n";
        echo "\n<center><form name='dl' action='$PHP_SELF' method='post' ><br />\n";
        foreach ($inputs as $name => $input) {
            echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
        }
        echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$pid'></script>";
        echo "<noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$pid' height='300' width='500' frameborder='0'></iframe><br />";
        echo "<textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br />";
        echo "<input type='submit' name='submit' onclick='javascript:return checkc();' value='Enter Captcha' />\n";
        echo "<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n";
        echo "</form></center>\n</body>\n</html>";
        exit();
    }

}

// Written by VinhNhaTrang 21.10.2010
// fix by VinhNhaTrang 21.11.2010
// updated by Ruud v.Tony 07-09-2011
// updated for redirected location in filesonic domain by Ruud v.Tony 12-09-2011
// Updated regex in single link, folder link, download link, also updated captcha code so the image can be refreshed without reloaded browser by Ruud v.Tony 14-09-2011
?>