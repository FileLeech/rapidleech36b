<?php
if (!defined('RAPIDLEECH')) {
    require('404.php');
    exit();
}

class wupload_com extends DownloadClass {

    private $domain, $id, $relocation;

    public function Download($link) {
        global $premium_acc, $Referer;
        $this->relocation = $link;
        $page = $this->GetPage('http://www.wupload.com/');
        if (preg_match('@Location: (http:\/\/www\.([^\/]+)\/?)@i', $page, $temp)) {
            $this->domain = trim($temp[2]);
        } else { //if it's not redirected
            $this->domain = 'wupload.com';
        }
        $link = preg_replace('#http:\/\/www\.([^\/]+)\/#', "http://www.$this->domain/", $link);
        // we need to check the link first
        if (preg_match('@http:\/\/www\.'.$this->domain.'\/folder\/[^\'"]+@i', $link, $dir)) {
            if (!$dir[0]) html_error('invalid wupload folder link, please check again!');
            $page = $this->GetPage($link, "isJavascriptEnable=1");
            is_present($page, 'Error 9002', 'The requested folder is set to private and can\'t be downloaded!');
            preg_match_all('%<\/span><a href="(.*)">%', $page, $arr_link);
            $this->moveToAutoDownloader($arr_link[1]);
        } else { //only single link, defined the id first
            if (!preg_match('@\/file\/[a-zA-Z]\d+\/(\d+)\/?@i', $link, $id)) {
                preg_match('@\/file\/(\d+)\/?@i', $link, $id);
            }
            $this->id = trim($id[1]);
            // display error messages if id is not define
            if (empty($this->id) || $this->id == '') html_error('Can\'t find wupload link id, the format link should be like this: http://www.wupload.com/file/000111!');
            $link = "http://www.$this->domain/file/$this->id";
        }
        unset($page);
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["wupload"] ["user"] && $premium_acc ["wupload"] ["pass"] )) {
            $this->Premium($link);
        } elseif ($_POST['pass'] == "premium") {
            $post['passwd'] = $_POST['password'];
            $cookie = decrypt(urldecode($_POST['cookie']));
            $link = $_POST['linkpw'];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            if (preg_match('@Location: ([^|\r|\n]+)@i', $page, $dl)) {
                $filename = parse_url(trim($dl[1]));
                $FileName = basename($filename['path']);
                $this->RedirectDownload(trim($dl[1]), $FileName, $cookie);
            } else {
                return $this->Premium($link, $predl);
            }
        } elseif ($_POST['pass'] == "free") {
            $post['passwd'] = $_POST['password'];
            $cookie = urldecode($_POST['cookie']);
            $link = $_POST['linkpw'];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            return $this->Retrieve($link);
        } elseif ($_POST['step'] == "1") {
            $this->Free($link);
        } else {
            $this->Retrieve($link);
        }
    }

    private function Free($link) {
        global $Referer;

        $post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
        $post["recaptcha_response_field"] = $_POST["recaptcha_response_field"];
        $cookie = urldecode($_POST["cookie"]);
        $link = $_POST["freelink"];
        $FileName = $_POST['name'];
        $page = $this->GetPage($link, $cookie, $post, $Referer);
        if (strpos($page, 'Please enter the captcha below:')) {
            return $this->Retrieve($link);
        }
        if (!preg_match('@http:\/\/.+'.$this->domain.'\/download\/[^\'"]+@i', $page, $dl)) html_error('Error, can\'t find final download link for wupload free!');
        $this->RedirectDownload(trim($dl[0]), $FileName, $cookie, 0, $Referer);
        exit();
    }

    private function Retrieve($link) {
        global $Referer;

        $page = $this->GetPage($link, "isJavascriptEnable=1"); // we need to manipulate cookie to set display form process
        is_present($page, 'class="deletedFile"', 'Sorry, this file has been removed.');
        $cookie = GetCookies($page) . "; isJavascriptEnable=1";
        // define the filename first to prevent bad hash...
        if (preg_match('%<input type="text" value="(.*)" name="URL_%', $page, $name)) $FileName = basename($name[1]);
        // define the base link for free download process, if not exist display an error messages
        if (preg_match('%<a href="(.*)" id="free_download">%', $page, $match)) {
            $link = "http://www.$this->domain/file/$match[1]";
        } else {
            html_error('Can\'t find wupload free link');
        }
        // start to retrieve the free download process
        $page = $this->GetPage($link, $cookie, 0, $Referer);
        is_present($page, 'Download 1 file at a time', 'You can only download 1 file at a time. Please try again!');
        // get the download timer
        if (preg_match('/var countDownDelay = (\d+);/', $page, $wait)) {
            if ($wait[1] > 90) {
                $data = $this->DefaultParamArr($this->relocation, $cookie);
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
            $data = $this->DefaultParamArr($this->relocation, $cookie);
            $data['linkpw'] = $link;
            $data['pass'] = 'free';
            $this->EnterPassword($page, $data);
        }
        if (preg_match('@http:\/\/.+'.$this->domain.'\/download\/[^\'"]+@i', $page, $dl)) {
            $this->RedirectDownload(trim($dl[0]), $FileName, $cookie, 0, $Referer);
            exit();
        }
        if (stristr($page, 'Please enter the captcha below:')) {
            if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $cid)) {
                html_error('Can\'nt find captcha data');
            }
            $data = $this->DefaultParamArr($this->relocation, $cookie);
            $data['freelink'] = $link;
            $data['step'] = '1';
            $data['recaptcha_challenge_field'] = trim(cut_str($this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$cid[1]&cachestop=" . rand() . "&ajax=1"), "challenge : '", "'"));
            $data['name'] = $FileName;
            $this->Show_reCaptcha($cid[1], $data);
            exit();
        }
    }

    private function Premium($link, $predl) {
        global $premium_acc, $Referer;

        if (!$predl) {
            $user = $pass = '';
            if ($_REQUEST['iuser'] != '' && $_REQUEST['ipass'] != '') {
                $user = $_REQUEST['iuser'];
                $pass = $_REQUEST['ipass'];
            } else if ($_REQUEST['premium_user'] != '' && $_REQUEST['premium_pass'] != '') {
                $user = $_REQUEST['premium_user'];
                $pass = $_REQUEST['premium_pass'];
            } else if ($premium_acc["wupload"]['user'] && $premium_acc["wupload"]['pass']) {
                $user = $premium_acc["wupload"]['user'];
                $pass = $premium_acc["wupload"]['pass'];
            } else {
                html_error('Login Failed: Can\'t get wupload username or password!');
            }

            $post = array();
            $post['email'] = urlencode($user);
            $post['redirect'] = urlencode('/');
            $post['password'] = urlencode($pass);
            $post['rememberMe'] = '1';
            $page = $this->GetPage('http://www.'.$this->domain.'/account/login', "isJavascriptEnable=1", $post, 'http://www.'.$this->domain.'/', 0, 1);
            $cookie = CookiesToStr(GetCookiesArr($page, true, $dval)). "; isJavascriptEnable=1";
            // the form page same as filesonic, kinda boring...
            is_present($page, 'Provided password does not match.', 'Error, invalid password!');
            is_present($page, 'No user found with such email.', 'Error, invalid username!');
            is_present($cookie, 'role=free', 'Account free, login not validated!');
        }

        $page = $this->GetPage($link, $cookie);
        is_present($page, 'class="deletedFile"', 'Sorry, this file has been removed.');
        if (stristr($page, 'Please Enter Password')) {
            $data = $this->DefaultParamArr($this->relocation, encrypt($cookie));
            $data['linkpw'] = $link;
            $data['pass'] = 'premium';
            $this->EnterPassword($page, $data);
            exit();
        }
        if (!preg_match('@Location: ([^|\r|\n]+)@i', $page, $dl)) html_error('Error, can\'t find final download link for wupload premium!');
        $filename = parse_url(trim($dl[1]));
        $FileName = basename($filename['path']);
        $this->RedirectDownload(trim($dl[1]), $FileName, $cookie);
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
?>