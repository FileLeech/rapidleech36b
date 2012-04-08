<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class lightupload_net extends DownloadClass {

    public function Download($link) {
        $page = $this->GetPage($link);
        is_present($page, "The file you have requested does not exists.");
        $cookie = GetCookies($page);
        if (!preg_match('@var time = (\d+);@', $page, $wait)) html_error('Error: Timer not found!');
        $this->CountDown ($wait[1]);
        $post = array('task' => 'download', 'submit.x' => rand(111,999), 'submit.y' => rand(11,99));
        if (!preg_match('@http:\/\/lightupload\.net\/get\/[^\']+@', $page, $dl)) html_error("Error: Download link result : ".trim($dl[0])."");
        $dlink = trim($dl[0]);
        $FileName = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $FileName, $cookie, $post, $link);
        exit();
    }
}

/*
 * lightupload.net download plugin by Ruud v.Tony 30-Dec-2011
 */
?>
