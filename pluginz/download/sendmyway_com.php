<?php
if (!defined('RAPIDLEECH')) {
    require_once ("index.html");
    exit();
}

class sendmyway_com extends DownloadClass {

    public function Download($link) {
        global $PHP_SELF;
        
        if ($_REQUEST['down_direct'] == '1') {
            $post['op'] = $_POST['op'];
            $post['id'] = $_POST['id'];
            $post['rand'] = $_POST['rand'];
            $post['referer'] = $_POST['referer'];
            $post['method_free'] = $_POST['method_free'];
            $post['method_premium'] = '';
            $post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
            $post['recaptcha_response_field'] = $_POST['recaptcha_response_field'];
            $link = urldecode($_POST['link']);
            $page = $this->GetPage($link, 'lang=english', $post, $link);
        } else {
            $page = $this->GetPage($link, 'lang=english');
            is_present($page, 'The file expired', 'File not found!');
            is_present($page, 'The file was deleted by its owner ', 'File not found!');
            is_present($page, 'The file was deleted by administration', 'File not found!');

            $form = cut_str($page, '<Form method="POST"', '</Form>');
            if (!preg_match_all('@<input type="hidden" name="([^"]+)" value="([^"]+)?">@', $form, $one) || !preg_match_all('@<input type="submit" name="(\w+_free)" value="([^"]+)">@', $form, $two)) html_error("Error: Post Data 1 [FREE] not found!");
            $match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
            $post = array();
            foreach ($match as $key => $value) {
                $post[$key] = $value;
            }
            $page = $this->GetPage($link, 'lang=english', $post, $link);
        }
        if (preg_match('@(\d+)<\/span> seconds@', $page, $wait)) $this->CountDown($wait[1]);
        if (preg_match('@api\/challenge[?]k=([^"]+)@i', $page, $cap) && preg_match('@api\/noscript[?]k=([^"]+)@i', $page, $cap)) {
            if (!preg_match_all('@<input type="hidden" name="([^"]+)" value="([^"]+)?">@', $page, $match)) html_error("Error: Post Data 2 [FREE] not found!");
            $data = array_merge($this->DefaultParamArr($link), array_combine($match[1], $match[2]));
            echo "<script language='JavaScript'>var RecaptchaOptions={theme:'red', lang:'en'};</script>\n";

            echo "\n<center><form name='dl' action='$PHP_SELF' method='post' ><br />\n";
            foreach ($data as $name => $input) {
                    echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
            }
            echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$cap[1]'></script>";
            echo "<noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$cap[1]' height='300' width='500' frameborder='0'></iframe><br />";
            echo "<textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br />";
            echo "<input type='submit' name='submit' onclick='javascript:return checkc();' value='Download File' />\n";
            echo "<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n";
            echo "</form></center>\n</body>\n</html>";
            exit();
        }
        is_present($page, cut_str($page, '<p class="err">', '<br />'));
        if (!preg_match('@http:\/\/\w+\.sendmyway\.com(:\d+)?\/d\/[^"]+@', $page, $dl)) html_error("Error: Download Link [FREE] not found!");
        $dlink = trim($dl[0]);
        $filename = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $filename, 0, 0, $link);
        exit;
    }

}

/*
 * sendmyway.com free download plugin by Ruud v.Tony 08-08-2011
 * fixed for sendmyway captcha layout by Ruud v.Tony 02-02-2012
 */
?>
