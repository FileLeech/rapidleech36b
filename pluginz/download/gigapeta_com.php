<?php
if (!defined('RAPIDLEECH')) {
    require_once('index.html');
    exit();
}

class gigapeta_com extends DownloadClass {

    public function Download($link) {
        if ($_REQUEST['step'] == 'Captcha') {
            $post['captcha_key'] = $_POST['captcha_key'];
            $post['captcha'] = $_POST['captcha'];
            $post['download'] = 'Download';
            $link = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            $page = $this->GetPage($link, $cookie, $post, $link);
        } else {
            $page = $this->GetPage($link, "lang=us");
            is_present($page, 'Attention! This file has been deleted from our server by user who uploaded it on the server.');
            $cookie = GetCookies($page) . "; lang=us";
            if (preg_match('#(\d+)</b> second#', $page, $wait)) $this->CountDown($wait[1]);

            $this->rand = rand(0, 100000000);
            $data = $this->DefaultParamArr($link, $cookie);
            $data['step'] = 'Captcha';
            $data['captcha_key'] = $this->rand;
            $this->EnterCaptcha('http://gigapeta.com/img/captcha.gif?x=' . $this->rand, $data, 20);
            exit();
        }
        is_present($page, 'Entered figures don&#96;t coincide with the picture', 'You entered a wrong CAPTCHA code. Please try again.');
        if (!preg_match('@Location: (http:\/\/[a-z0-9]+\.gigapeta\.com\/download[^|\r|\n]+)@i', $page, $dl)) html_error('Error: Download link not found!');
        $dlink = trim($dl[1]);
        $FileName = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $FileName, $cookie, 0, $link);
        exit();
    }
}

/*
 * Gigapeta.com free download plugin by Ruud v.Tony 05-11-2011
 */
?>
