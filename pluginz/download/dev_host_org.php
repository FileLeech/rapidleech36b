<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class dev_host_org extends DownloadClass {

    public function Download($link) {

        $novi = $this->GetPage($link);
        is_present($novi, 'The file you were looking for could not be found, sorry for any inconvenience');

        $ruud = array('op' => 'download2', 'id' => cut_str($novi, 'name="id" value="', '"'), 'rand' => cut_str($novi, 'name="rand" value="', '"'), 'referer' => $link, 'method_free' => '', 'method_premium' => '', 'down_script' => '1');
        $novi = $this->GetPage($link, 0, $ruud, $link);
        if (!preg_match('@Location: ([^|\r|\n]+)@i', $novi, $check)) html_error ('Error: Download link not found?');
        $pregnant = trim($check[1]);
        $ninemonth = parse_url($pregnant);
        $seven = basename($ninemonth['path']);
        $this->RedirectDownload($pregnant, $seven, 0, 0, $link);
        exit();
    }
}

/*
 * Dev-host.org free download plugin by Ruud v.Tony 04-10-2011
 */

?>
