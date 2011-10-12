<?php
if (!defined('RAPIDLEECH')) {
    require('404.php');
    exit();
}

class wupload_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc, $Referer;
        $Referer = $link;
        // define the main domain first so we can get the home location
        if (!$_REQUEST['step']) {
            $check = $this->GetPage('http://www.wupload.com/');
            if (preg_match('@Location: (http:\/\/www\.([^\/]+)\/?)@i', $check, $temp)) {
                $reloc = trim($temp[2]);
            } else { //if it's not redirected
                $reloc = 'wupload.com';
            }
            unset($check);
        }
        $link = preg_replace('@http:\/\/www\.([^\/]+)\/@', "http://www.$reloc/", $link);
        if (preg_match("@http:\/\/www\.$reloc\/folder\/[^|\r|\n]+@i", $link, $folder)) {
            if (!$folder[0]) html_error('Invalid wupload folder link!');
            $page = $this->GetPage($link, "isJavascriptEnable=1");
            is_present($page, 'Error 9002', 'The requested folder is set to private and can\'t be downloaded!');
            preg_match_all('%<\/span><a href="(.*)">%', $page, $arr_link);
            $this->moveToAutoDownloader($arr_link[1]);
        } else { //only single link, defined the id first
            if (!preg_match('@\/file\/[a-zA-Z]\d+\/(\d+)\/?@i', $link, $check)) {
                preg_match('@\/file\/(\d+)\/?@i', $link, $check);
            }
            $id = trim($check[1]);
            // display error messages if id is not define
            if (empty($id) || $id == '') html_error('Can\'t find wupload link id, the format link should be like this: http://www.wupload.com/file/000111!');
            $link = "http://www.$reloc/file/$id";
        }
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["wupload"] ["user"] && $premium_acc ["wupload"] ["pass"] )) {
            $this->Login("http://www.$reloc/", $link);
        } elseif ($_POST['step'] == "password") {
            $post["passwd"] = $_POST['password'];
            $link = urldecode($_POST['referer']);
            $Referer = urldecode($_POST['link']);
            if ($_POST['pass'] == 'premium') {
                $cookie = decrypt(urldecode(trim($_POST["cookie"])));
                return $this->Premium($this->GetPage($link, $cookie, $post, $Referer."\r\nX-Requested-With: XMLHttpRequest"), $link, $cookie);
            } else {
                $cookie = urldecode($_POST['cookie']);
                return $this->Free($this->GetPage($link, $cookie, $post, $Referer."\r\nX-Requested-With: XMLHttpRequest"), $link, $cookie);
            }
        } elseif ($_POST['step'] == "Captcha") {
            $post['recaptcha_challenge_field'] = $_POST["recaptcha_challenge_field"];
            $post['recaptcha_response_field'] = $_POST["recaptcha_response_field"];
            $cookie = urldecode($_POST["cookie"]);
            $link = urldecode($_POST["referer"]);
            $Referer = urldecode($_POST['link']);
            return $this->Free($this->GetPage($link, $cookie, $post, $Referer), $link, $cookie);
        } elseif ($_POST['step'] == "countdown") {
            $link = urldecode($_POST['referer']);
            $cookie = urldecode($_POST['cookie']);
            $Referer = urldecode($_POST['link']);
            return $this->Free($this->GetPage($link, $cookie, 0, $Referer), $link, $cookie);
        } else {
            $page = $this->GetPage($link, "isJavascriptEnable=1"); // we need to manipulate cookie to set display form process
            is_present($page, 'class="deletedFile"', 'Sorry, this file has been removed.');
            $cookie = GetCookies($page) . "; isJavascriptEnable=1";
            // define the base link for free download process, if not exist display an error messages
            if (preg_match('%<a href="(.*)" id="free_download">%', $page, $match)) $link = "http://www.$reloc/file/$match[1]";
            else html_error('Can\'t find wupload free link');
            $this->Free($this->GetPage($link, $cookie, 0, $Referer), $link, $cookie);
        }
    }

    private function Free($page, $link, $cookie) {
        global $Referer;
        is_present($page, 'Download 1 file at a time', 'You can only download 1 file at a time. Please try again!');
        // get the download timer
        if (preg_match('/var countDownDelay = (\d+);/', $page, $wait)) {
            if ($wait[1] > 90) {
                $data = $this->DefaultParamArr($Referer, $cookie, $link);
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
            $data = $this->DefaultParamArr($Referer, $cookie, $link);
            $data['step'] = 'password';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (stristr($page, 'Please enter the captcha below:')) {
            if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $cid)) {
                html_error('Can\'nt find captcha data');
            }
            $data = $this->DefaultParamArr($Referer, $cookie, $link);
            $data['step'] = 'Captcha';
            $this->Show_reCaptcha($cid[1], $data);
            exit();
        }
        if (!preg_match('@http:\/\/[\w.]+\/download\/[^|\'?"?]+@i', $page, $dl)) html_error('Error, can\'t find final download link for wupload free!');
        $dlink = trim($dl[0]);
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, $cookie, 0, $Referer);
        exit();
    }

    private function Login($domain, $link) {
        global $premium_acc, $Referer;

        $email = ($_REQUEST["premium_user"] ? $_REQUEST["premium_user"] : $premium_acc["wupload"]["user"]);
        $password = ($_REQUEST["premium_pass"] ? $_REQUEST["premium_pass"] : $premium_acc["wupload"]["pass"]);
        if (empty($email) || empty($password)) {
            html_error("Login Failed: Email or Password is empty. Please check login data.");
        }

        $post = array();
        $post['email'] = urlencode($email);
        $post['redirect'] = urlencode('/');
        $post['password'] = urlencode($password);
        $post['rememberMe'] = '1';
        $page = $this->GetPage($domain.'/account/login', "isJavascriptEnable=1", $post, $domain."\r\nX-Requested-With: XMLHttpRequest");
        $cookie = CookiesToStr(GetCookiesArr($page, true, $dval)). "; isJavascriptEnable=1";
        // the form page same as filesonic, kinda boring...
        is_present($page, 'Provided password does not match.', 'Error, invalid password!');
        is_present($page, 'No user found with such email.', 'Error, invalid username!');
        is_present($cookie, 'role=free', 'Account free, login not validated!');

        return $this->Premium($this->GetPage($link, $cookie, 0, $Referer), $link, $cookie);
    }

    private function Premium($page, $link, $cookie) {
        global $Referer;
        is_present($page, 'class="deletedFile"', 'Sorry, this file has been removed.');
        if (stristr($page, 'Please Enter Password')) {
            $data = $this->DefaultParamArr($Referer, encrypt($cookie), $link);
            $data['step'] = 'password';
            $data['pass'] = 'premium';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (!preg_match('@http:\/\/[\w.]+\/download\/[^|\r?|\n?|\'?"?]+@i', $page, $dl)) html_error('Error, can\'t find final download link for wupload premium!');
        $dlink = trim($dl[0]);
        $filename = parse_url($dlink);
        $FileName = basename($filename['path']);
        $this->RedirectDownload($dlink, $FileName, $cookie);
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
        exit;
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
}

//Wupload download plugin by Ruud v.Tony 20-09-2011
//Fixed for relocation also bad hash problem (haven't thought that, in vdhdevil private server, I see it clearly, they set $Referer as link, oh well)
//Add improvement in check domain location also link id so user dont have to start over again!
?>