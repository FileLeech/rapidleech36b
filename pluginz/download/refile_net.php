<?php
if (!defined('RAPIDLEECH')) {
    require_once("index.html");
    exit();
}

class refile_net extends DownloadClass {

    public function Download($link) {
        if ($_REQUEST['step'] == 'captcha') {
            $link = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            $id = $_POST['id'];

            $post = array();
            $post['__VIEWSTATE'] = $_POST['__VIEWSTATE'];
            $post[$id] = "";
            $post['e_enc'] = $_POST['e_enc'];
            $post['recaptcha_challenge_field'] = $_POST['challenge'];
            $post['recaptcha_response_field'] = $_POST['captcha'];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
        } else {
            $page = $this->GetPage($link);
            is_present($page, "This file is set to Private.");
            is_present($page, "Download File Not Found");
            is_present($page, "Please Enter File Password:", "This file is password protected.");
            $cookie = GetCookies($page);
        }
        if (strstr($page, "Recaptcha")) {
            $k = cut_str($page, 'api/challenge?k=', '"');
            $ch = cut_str($this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$k", $cookie), "challenge : '", "'");
            $cap = $this->GetPage("http://www.google.com/recaptcha/api/image?c=" . $ch);
            $capt_img = substr($cap, strpos($cap, "\r\n\r\n") + 4);
            $imgfile = DOWNLOAD_DIR . "refile_captcha.jpg";

            if (file_exists($imgfile)) unlink($imgfile);
            if (empty($capt_img) || !write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.");

            $data = $this->DefaultParamArr($link, $cookie);
            $data['challenge'] = urlencode($ch);
            $data['__VIEWSTATE'] = urlencode(cut_str($page, 'id="__VIEWSTATE" value="', '"'));
            $data['id'] = urlencode(cut_str($page, '<input type="hidden" id="', '"'));
            $data['e_enc'] = urlencode(cut_str($page, 'name="e_enc" value="', '"'));
            $data['step'] = 'captcha';
            $this->EnterCaptcha($imgfile, $data, 20);
            exit();
        }
        if (preg_match('@Location: (\/d\/[^\r\n]+)@i', $page, $check)) {
            $cookie = $cookie . "; " . GetCookies($page);
            $page = $this->GetPage('http://refile.net' . $check[1], $cookie, 0, $link);
            if (!preg_match("@(http(s)?:\/\/\w+\.refile\.net\/file\/[^\"]+)\">@", $page, $dl)) html_error('Error: Download link not found!');
            $dlink = trim($dl[1]);
            $filename = basename(parse_url($dlink, PHP_URL_PATH));
            $this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
            exit();
        }
    }

}

/*
 * by Ruud v.Tony 24-01-2012
 */
?>
