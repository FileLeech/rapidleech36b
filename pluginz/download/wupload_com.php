<?php
if (!defined('RAPIDLEECH')) {
    require('404.php');
    exit();
}

class wupload_com extends DownloadClass {

    private $id;

    public function Download($link) {
        global $premium_acc, $Referer;
        // we need to check the link first
        if (preg_match('@http:\/\/www\.wupload\.com\/folder\/[^\'"]+@i', $link, $dir)) {
            if (!$dir[0]) html_error('invalid wupload folder link, please check again!');
            $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1");
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
            $link = "http://www.wupload.com/file/$this->id";
            $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1");
            is_notpresent($page, '<p class="fileInfo filename">', 'Sorry, this file has been removed or link not exist.');
        }
        unset($page);
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["wupload"] ["user"] && $premium_acc ["wupload"] ["pass"] )) {
            $this->Premium($link);
        } elseif ($_POST['pass'] == "premium") {
            $post['passwd'] = $_POST['password'];
            $cookie = decrypt(urldecode($_POST['cookie']));
            $link = urldecode($_POST['link']);
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            return $this->Premium($link, $predl);
        } elseif ($_POST['pass'] == "free") {
            $post['passwd'] = $_POST['password'];
            $cookie = urldecode($_POST['cookie']);
            $link = urldecode($_POST['link']);
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            return $this->Retrieve($link);
        } elseif ($_POST['step'] == "1") {
            $this->Free($link);
        } else {
            $this->Retrieve($link);
        }
    }

    private function Free($link) {

        $post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
        $post["recaptcha_response_field"] = $_POST["recaptcha_response_field"];
        $cookie = urldecode($_POST["cookie"]);
        $link = urldecode($_POST["link"]);
        $Referer = urldecode($_POST['referer']);
        $FileName = $_POST['name'];
        $page = $this->GetPage($link, $cookie, $post, $Referer);
        if (strpos($page, 'Please enter the captcha below:')) {
            return $this->Retrieve($link);
        }
        if (!preg_match('@http:\/\/[a-zA-Z](\d+)?\.wupload\.com\/download\/'.$this->id.'\/[^\'"]+@i', $page, $dl)) html_error('Error, can\'t find final download link for wupload free!');
        $this->RedirectDownload(trim($dl[0]), $FileName, $cookie, 0, $Referer);
        exit();
    }

    private function Retrieve($link) {
        global $Referer;

        $page = $this->GetPage($link, "lang=en; isJavascriptEnable=1"); // we need to manipulate cookie to set display form process
        $cookie = GetCookies($page) . "; lang=en; isJavascriptEnable=1";
        // define the filename first since most of server get error in downloading
        if (preg_match('%<input type="text" value="(.*)" name="URL_%', $page, $name)) $FileName = basename($name[1]);
        // define the base link for free download process, if not exist display an error messages
        if (preg_match('%<a href="(.*)" id="free_download">%', $page, $match)) {
            $link = "http://www.wupload.com/file/$this->id/$match[1]";
        } else {
            html_error('Can\'t find wupload free link');
        }
        // start to retrieve the free download process
        $page = $this->GetPage($link, $cookie, 0, $Referer);
        is_present($page, 'Download 1 file at a time', 'You can only download 1 file at a time. Please try again!');
        // get the download timer
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
        if (stristr($page, 'Please Enter Password')) {
            if (!preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $pw)) {
                html_error('Error, Can\'t find wupload password link!');
            }
            $link = "http://www.wupload.com$pw[1]";

            $data = $this->DefaultParamArr($link, $cookie);
            $data['pass'] = 'free';
            $this->EnterPassword($page, $data);
        }
        if (preg_match('@http://[a-zA-Z](\d+)?\.wupload\.com\/download\/' . $this->id . '\/[^\'"]+@i', $page, $dl)) {
            $this->RedirectDownload(trim($dl[0]), $FileName, $cookie, 0, $Referer);
            exit();
        }
        if (stristr($page, 'Please enter the captcha below:')) {
            if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $cid)) {
                html_error('Can\'nt find captcha data');
            }
            $data = $this->DefaultParamArr($link, $cookie, $Referer);
            $data['step'] = '1';
            $data['recaptcha_challenge_field'] = trim(cut_str($this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$cid[1]&cachestop=" . rand() . "&ajax=1"), "challenge : '", "'"));
            $data['name'] = $FileName;
            $this->Show_reCaptcha($cid[1], $data);
            exit();
        }
    }

    private function Premium($link, $predl = false) {
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
            $page = $this->GetPage('http://www.wupload.com/account/login', "lang=en; isJavascriptEnable=1", $post, 'http://www.wupload.com/', 0, 1);
            $cookie = CookiesToStr(GetCookiesArr($page, true, $dval)). "; lang=en; isJavascriptEnable=1";
            // the form page same as filesonic, kinda boring...
            is_present($page, 'Provided password does not match.', 'Error, invalid password!');
            is_present($page, 'No user found with such email.', 'Error, invalid username!');
            is_present($cookie, 'role=free', 'Account free, login not validated!');
        }

        $page = $this->GetPage($link, $cookie);
        if (stristr($page, 'Please Enter Password')) {
            if (!preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $pw)) {
                html_error('Error, Can\'t find wupload password link!');
            }
            $link = "http://www.wupload.com$pw[1]";

            $data = $this->DefaultParamArr($link, encrypt($cookie));
            $data['pass'] = 'premium';
            $this->EnterPassword($page, $data);
        }
        if (!preg_match('@Location: (http:\/\/[a-zA-Z](\d+)?\.wupload\.com/download\/'.$this->id.'\/[^\r|\n]+)@i', $page, $dl)) html_error('Error, can\'t find final download link for wupload premium!');
        $Url = parse_url(trim($dl[1]));
        $FileName = basename($Url['path']);
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
?>