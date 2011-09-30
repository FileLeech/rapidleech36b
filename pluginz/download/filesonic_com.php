<?php
if (!defined('RAPIDLEECH')) {
    require_once("404.php");
    exit;
}

class filesonic_com extends DownloadClass {

    private $domain, $id;

    public function Download($link) {
        global $premium_acc, $Referer;
        // define the main domain first so we can get the home location
        $page = $this->GetPage('http://www.filesonic.com/');
        if (preg_match('@Location: (http:\/\/www\.([^\/]+)\/?)@i', $page, $temp)) {
            $this->domain = trim($temp[2]);
        } else { //if it's not redirected
            $this->domain = 'filesonic.com';
        }
        if (stristr($link, '/folder/')) {
            $link = preg_replace('#http:\/\/www\.([^\/]+)\/folder#', "http://www.$this->domain/folder", $link);
            if (preg_match('@http:\/\/www\.' . $this->domain . '\/folder\/[^\r|\n]+@i', $link, $dir)) {
                if (!$dir[0]) html_error('Invalid filesonic folder link!');
                $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1");
                preg_match_all('/<\/span><a href="(.*)">/i', $page, $single);
                if (!$single[1]) html_error('Can\'t find any filesonic single link!');
                $this->moveToAutoDownloader($single[1]);
            }
        }
        if (stristr($link, '/file/')) {
            if (!preg_match('@\/file\/(\d+)\/?@i', $link, $id)) {
                preg_match('@\/file\/[a-zA-Z]\d+\/(\d+)\/?@i', $link, $id);
            }
            $this->id = trim($id[1]);
            if (!isset($this->id) && $this->id == '') html_error('Can\'t find filesonic link id');
            $link = "http://www.$this->domain/file/$this->id";
        }
        unset($page);
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["filesonic"] ["user"] && $premium_acc ["filesonic"] ["pass"])) {
            $this->Premium($link);
        } elseif ($_POST['pass'] == "premium") {
            $post["passwd"] = $_POST['password'];
            $link = urldecode($_POST['link']);
            $cookie = decrypt(urldecode(trim($_POST["cookie"])));
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            return $this->Premium($link, $predl);
        } elseif ($_POST['pass'] == "free") {
            $post["passwd"] = $_POST['password'];
            $link = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            return $this->Retrieve($link);
        } elseif ($_POST['step'] == "1") {
            $this->Free($link);
        } else {
            $this->Retrieve($link);
        }
    }

    private function Retrieve($link) {
        global $Referer;

        $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1");
        is_present($page, 'This file has been marked as private', 'This file has been marked as private by its owner and cannot be downloaded');
        is_present($page, 'This file was deleted', 'This file was deleted');
        $cookie = GetCookies($page) . "; lang=en; isJavascriptEnable=1";
        if (preg_match('%<input type="text" value="(.*)" name="URL_%', $page, $name)) $FileName = basename($name[1]);
        if (preg_match('%<a href="(.*)" id="free_download">%', $page, $match)) {
            $link = "http://www.$this->domain/file/$this->id/$match[1]";
        } else {
            html_error('Can\'t find filesonic free link');
        }
        $page = $this->GetPage($link, $cookie, 0, $Referer);
        is_present($page, 'Free users may only download 1 file at a time.', 'Free users may only download 1 file at a time.');
        if (preg_match('/var countDownDelay = (\d+);/', $page, $wait)) {
            if ($wait[1] > 90) {
                $data = $this->DefaultParamArr($link, $cookie, $Referer);
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
        if (preg_match('@http:\/\/.+'.$this->domain.'\/download\/[^\'"]+@i', $page, $dl)) {
            $this->RedirectDownload(trim($dl[0]), $FileName, $cookie, 0, $Referer);
            exit();
        }
        if (stristr($page, 'Please Enter Password')) {
            if (preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $pw)) {
                $link = "http://www.$this->domain$pw[1]";
            } else {
                html_error('Can\'t find password link!');
            }
            $data = $this->DefaultParamArr($link, $cookie);
            $data['pass'] = 'free';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (stristr($page, "Please Enter Captcha")) {
            if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $cid)) {
                html_error('Can\'nt find captcha data');
            }
            $data = $this->DefaultParamArr($link, $cookie, $Referer);
            $data['step'] = '1';
            $data['recaptcha_challenge_field'] = cut_str($this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$cid[1]&cachestop=" . rand() . "&ajax=1"), "challenge : '", "'");
            $data['name'] = $FileName;
            $this->Show_reCaptcha($cid[1], $data);
            exit();
        }
        is_present($page, 'Free users may only download 1 file at a time.', 'Free users may only download 1 file at a time.');
    }

    private function Free($link) {
        $post['recaptcha_challenge_field'] = $_POST["recaptcha_challenge_field"];
        $post['recaptcha_response_field'] = $_POST["recaptcha_response_field"];
        $cookie = urldecode($_POST["cookie"]);
        $link = urldecode($_POST["link"]);
        $Referer = urldecode($_POST['referer']);
        $FileName = $_POST['name'];
        $page = $this->GetPage($link, $cookie, $post, $Referer);
        if (strpos($page, 'Please Enter Captcha')) {
            return $this->Retrieve($link);
        }
        if (!preg_match(''@http:\/\/.+'.$this->domain.'\/download\/[^\'"]+@i', $page, $dl)) {
            html_error('Error: Final Download link for filesonic free can\'t be found!');
        }
        $this->RedirectDownload(trim($dl[0]), $FileName, $cookie, 0, $Referer);
        exit();
    }

    private function Premium($link, $predl = false) {
        global $premium_acc;

        if (!$predl) {
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
            $page = $this->GetPage("http://www.$this->domain/user/login/", "lang=en; isJavascriptEnable=1", $post, "http://www.$this->domain/", 0, 1);
            $cookie = CookiesToStr(GetCookiesArr($page, true, $dval)) . "; lang=en; isJavascriptEnable=1";
            is_present($page, "Provided password does not match.", "Provided password does not match.");
            is_present($page, "No user found with such email.", "No user found with such email.");
            is_present($cookie, 'role=free', 'Account Free, Please check your premium account!');
        }

        $page = $this->GetPage($link, $cookie);
        is_present($page, 'This file has been marked as private', 'This file has been marked as private by its owner and cannot be downloaded');
        is_present($page, 'This file was deleted', 'This file was deleted');
        if (strpos($page, "Please Enter Password")) {
            if (preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $pw)) {
                $link = "http://www.$this->domain$pw[1]";
            } else {
                html_error('Can\'t find password link!');
            }
            $data = $this->DefaultParamArr($link, encrypt($cookie));
            $data['pass'] = 'premium';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (!preg_match('@Location: ([^|\r|\n]+)@i', $page, $dl)) {
            html_error('Error: Final Download link for premium can\'t be found!');
        }
        $Url = parse_url(trim($dl[1]));
        $FileName = basename($Url['path']);
        $this->RedirectDownload(trim($dl[1]), $FileName, $cookie);
    }

    private function EnterPassword($page, $inputs) {
        global $PHP_SELF;

        echo "\n" . '<center><form action="' . $PHP_SELF . '" method="post" >' . "\n";
        foreach ($inputs as $name => $input) {
            echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
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