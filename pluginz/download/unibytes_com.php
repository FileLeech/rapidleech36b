<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class unibytes_com extends DownloadClass {
    
    public function Download($link) {
        global $Referer;
        
        if ($_POST['submit'] == 'Download') {
            $post['step'] = $_POST['step'];
            $post['s'] = $_POST['s'];
            $post['referer'] = $_POST['referer'];
            $post['captcha'] = $_POST['captcha'];
            $link = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            $page = $this->GetPage($link, $cookie, $post, $Referer);
        } else {
            $page = $this->GetPage($link);
            is_present($page, 'File not found or removed');
            $cookie = GetCookies($page);
            $form = cut_str($page, '<form style="display: none" id="startForm"', '</form>');
            $post = $this->AutomateData($form, '%<input type="hidden" name="([^"]+)" value="([^\r\n"]+)?"\/>%', true);
            if (!preg_match('/action="([^"]+)">/', $form, $fr)) html_error('Error [Post Link 1 not found!]');
            $link = 'http://www.unibytes.com'.$fr[1];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            unset($post);
            if (preg_match('/(\d+)<\/span> sec/', $page, $w)) $this->CountDown ($w[1]);
            $form = cut_str($page, '<form id="stepForm" method="post"', '</form>');
            $post = $this->AutomateData($form, '%<input type="hidden" name="([^"]+)" value="([^\r\n"]+)?"\/>%', true);
            if (!preg_match('/action="([^"]+)">/', $form, $fr)) html_error('Error [Post Link 2 not found!]');
            $link = 'http://www.unibytes.com'.$fr[1];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            unset($post);
            $form = cut_str($page, '<form id="stepForm" method="post"', '</form>');
            $post = $this->AutomateData($form, '%<input type="hidden" name="([^"]+)" value="([^\r\n"]+)?"\/>%', true);
            if (!preg_match('/action="([^"]+)">/', $form, $fr)) html_error('Error [Post Link 3 not found!]');
            $link = 'http://www.unibytes.com'.$fr[1];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
        }
        if (stripos($page, 'To continue type the code from the picture:')) {
            $form = cut_str($page, '<form id="stepForm" method="post"', '</form>');
            
            //download captcha
            $cap = $this->GetPage('http://www.unibytes.com/captcha.jpg', $cookie, 0, $link); // Yes, the cookie is needed
            $capt_img = substr($cap, strpos($cap, "\r\n\r\n") + 4);
            $imgfile = DOWNLOAD_DIR . "unibytes_captcha.jpg";

            if (file_exists($imgfile)) unlink($imgfile);
            if (empty($capt_img) || !write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.", 0);
            
            $match = $this->AutomateData($form, '%<input type="hidden" name="([^"]+)" value="([^\r\n"]+)?"\/>%');
            if (!preg_match('/action="([^"]+)">/', $form, $fr)) html_error('Post Link Captcha not found!');
            $link = 'http://www.unibytes.com'.$fr[1];

            $data = array_merge($this->DefaultParamArr($link, $cookie), $match);
            $data['submit'] = 'Download';
            $this->EnterCaptcha($imgfile, $data);
            exit();
        }
        if (!preg_match('%<a href="(http:\/\/[^\r\n"]+)">Download%', $page, $dl)) html_error('Error [Download Link not found!]');
        $dlink = trim($dl[1]);
        $filename = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $filename, $cookie, 0, $Referer);
        exit();
    }
    
    private function AutomateData($source, $regex, $post = false) {
        if (!preg_match_all($regex, $source, $match)) html_error('Error [Post Data not found!]');
        $match = array_combine($match[1], $match[2]);
        if ($post) {
            $post = array();
            foreach ($match as $k => $v) {
                $post[$k] = $v;
            }
            return $post;
        } else {
            return $match;
        }
    }
}

/*
 * Ditulis oleh Jihad Akbar Hidayatullah, 13 tahun, Bandung, Indonesia
 */
?>
