<?php
if (!defined('RAPIDLEECH')) {
    require_once('index.html');
    exit();
}

class multiupload_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (preg_match('@http:\/\/.+multiupload\.com\/([^\r\n]+)\/?@', $link, $match)) {
            $this->id = trim($match[1]);
        }
        if (!$_REQUEST['step']) {
            $this->page = $this->GetPage($link);
            is_present($this->page, "Unfortunately, the link you have clicked is not available.");
        }
        if (($_REQUEST['premium_acc'] == 'on' && $_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($_REQUEST['premium_acc'] == 'on' && $premium_acc['multiupload_com']['user'] && $premium_acc['multiupload_com']['pass'])) {
            $this->Premium($link);
        } else {
            $this->Free($link);
        }
    }

    private function Free($link) {
        if ($_REQUEST['step'] == '1') {
            $post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
            $post['recaptcha_response_field'] = $_POST['recaptcha_response_field'];
            $postlink = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            $challenge = $_POST['challenge'];
            $this->page = $this->GetPage($postlink, $cookie, $post, $link);
        } else {
            $cookie = GetCookies($this->page);
            if (!preg_match("@\{ xmlobject\.open\('POST', '([^']+)',@i", $this->page, $rd)) html_error("Error: Multiupload Direct link not found!");                
            $postlink = $link . $rd[1];
            if (!preg_match('@Recaptcha\.create\("([^"]+)",@i', $this->page, $cap)) html_error("Error: Captcha id not found!");
            //send the captcha data
            $data = $this->DefaultParamArr($postlink, $cookie);
            $data['step'] = '1';
            $data['challenge'] = $cap[1]; //incase we have input the wrong captcha
            $this->Show_reCaptcha($cap[1], $data);
            exit();
        }
        if (!preg_match('@\{"([^"]+)":"([^"]+)"\}@', $this->page, $check)) html_error("Error: Unknown Response Page!");
        switch ($check[1]) {
            case 'response':
                echo  ("<center><font color='red'><b>The captcha wasn't entered correctly. Please try again</b></font></center>");
                $data = $this->DefaultParamArr($postlink, $cookie);
                $data['step'] = '1';
                $data['challenge'] = $challenge;
                $this->Show_reCaptcha($challenge, $data);
                break;

            case 'href':
                $dlink = str_replace('\\', '', $check[2]);
                $filename = basename(parse_url($dlink, PHP_URL_PATH));
                $this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
                break;
        }
    }
    
    private function Premium($link) {
        global $premium_acc;
        
        html_error("Not supported now!");
    }

    private function Show_reCaptcha($pid, $inputs) {
        global $PHP_SELF;

        if (!is_array($inputs)) {
            html_error("Error parsing captcha data.");
        }

        // Themes: 'red', 'white', 'blackglass', 'clean'
        echo "<script language='JavaScript'>var RecaptchaOptions={theme:'red', lang:'en'};</script>\n";

        echo "\n<center><form name='dl' action='$PHP_SELF' method='post' ><br />\n";
        foreach ($inputs as $name => $input) {
            echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
        }
        echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$pid'></script>";
        echo "<noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$pid' height='300' width='500' frameborder='0'></iframe><br />";
        echo "<textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br />";
        echo "<input type='submit' name='submit' onclick='javascript:return checkc();' value='Download File' />\n";
        echo "<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n";
        echo "</form></center>\n</body>\n</html>";
        exit;
    }

}

/*
 * Multiupload (Direct link) Free Download Plugin by Ruud v.Tony 10/01/2012
 */
?>
