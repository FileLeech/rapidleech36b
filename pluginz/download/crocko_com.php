<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class crocko_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (stristr($link, 'easy-share.com')) {
            $link = str_replace('easy-share.com', 'crocko.com', $link);
        }
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc["crocko_com"]["user"] && $premium_acc["crocko_com"]["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }

    /*
     * exit for terminated download
     * return for continue download
     * $content is header content before download
     */

    public function CheckBack($content) {
        if (!strpos($content, "ontent-Disposition: attachment; ")) {
            html_error("You have input wrong captcha, Please try again!");
        }
        return;
    }

    private function DownloadFree($link) {
        global $Referer;

        if ($_POST['step'] == '1') {
            $post["recaptcha_challenge_field"] = $_POST['recaptcha_challenge_field'];
            $post["recaptcha_response_field"] = $_POST['recaptcha_response_field'];
            $post["id"] = $_POST['id'];
            $cookie = urldecode($_POST['cookie']);
            $dlink = urldecode($_POST['link']);
            $FileName = $_POST['name'];
            $this->RedirectDownload($dlink, $FileName, $cookie, $post, $Referer);
            $this->CheckBack($dlink);
            exit();
        } else {
            $page = $this->GetPage($link, 'language=en');
            is_present($page, 'Requested file is deleted.');
            is_present($page, 'There is another download in progress from your IP. Please try to downloading later.');
            $cookie = GetCookies($page) . "; language=en";
            $FileName = trim(str_replace(" ", ".", cut_str($page, 'Download ', ',')));
            // first timer
            if (preg_match('/wf = (\d+);/', $page, $wait)) $this->CountDown($wait[1]);
            if (preg_match("/u='(.+)'/", $page, $cap)) $link = "http://www.crocko.com$cap[1]";
            $page = $this->GetPage($link, $cookie, 0, $Referer);
            //get new timer, then refresh the page
            if (preg_match("/w='(\d+)'/", $page, $wait)) {
                if ($wait[1] > 90) {
                    $this->JSCountdown($wait[1]);
                } else {
                    $this->CountDown($wait[1]);
                }
                $page = $this->GetPage($link, $cookie, 0, $Referer);
            }
            if (preg_match('%<form  method="post" action="(.*)">%', $page, $match)) $link = $match[1];
            // now we start to display the captcha data
            if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $cid)) html_error('Can\'t find chaptcha id');
            $data = $this->DefaultParamArr($link, $cookie);
            $data['step'] = '1';
            $data['id'] = cut_str($page, 'name="id" value="', '"');
            $data['name'] = $FileName;
            $this->Show_reCaptcha($cid[1], $data);
            exit();
        }
    }

    private function DownloadPremium($link) {
        global $premium_acc;

        $post = array();
        $post ["login"] = $_REQUEST ["premium_user"] ? $_REQUEST ["premium_user"] : $premium_acc ["crocko_com"] ["user"];
        $post ["password"] = $_REQUEST ["premium_pass"] ? $_REQUEST ["premium_pass"] : $premium_acc ["crocko_com"] ["pass"];
        $post ["remember"] = "1";
        $page = $this->GetPage("https://www.crocko.com/accounts/login", 0, $post, "https://www.crocko.com/\r\nX-Requested-With: XMLHttpRequest");
        $cookies = GetCookies($page);
        if (!preg_match("#PREMIUM=[\w%]+#", $cookies, $Premium)) {
            html_error("Login Failed , Bad username/password combination");
        }
        preg_match("#PHPSESSID=\w+#", $cookies, $PhpSessId);
        $page = $this->GetPage($link, $cookies, 0, $Referer, $pauth);
        is_present($page, 'The requested file is temporarily unavailable', 'Link is not available');
        $cookies = $PhpSessId[0] . "; " . $Premium[0] . "; " . GetCookies($page);
        $FileName = basename(parse_url($link, PHP_URL_PATH));
        $FileName = $FileName ? $FileName : "Crocko";
        if (!preg_match("#Location: (.*)#", $page, $prelink)) {
            html_error("Error 1x01: Plugin is out of date");
        }
        $this->RedirectDownload($prelink[1], $FileName, $cookies, 0, $link);
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
            echo "<input type='hidden' name='$name' value='$input' />\n";
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

/*
 * crocko.com download plugin by Ruud v.Tony & vdhdevil 19-10-2011
 */
?>
