<?php
if (!defined('RAPIDLEECH')) {
    require_once ("404.php");
    exit ();
}

class filesflash_com extends DownloadClass {

    public function Download($link) {
        $page = $this->GetPage($link);
        is_present($page, 'That file is not available for download', 'File not found!');
        $cookie = GetCookies($page);

        $post = array();
        $post['token'] = cut_str($page, 'name="token" value="','"');
        $post['freedl'] = " Free Download ";
        $page = $this->GetPage('http://filesflash.com/freedownload.php', $cookie, $post, $link);

        if (preg_match('/count=(\d+)/', $page, $wait)) $this->CountDown($wait[1]);
        $dlink = cut_str($page, '<div id="link" style="display:none"><a href="','">');
        if (!$dlink) html_error("Error: Download link not found???");
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, $cookie, 0, 'http://filesflash.com/freedownload.php');
        exit();
    }
}

/*
 * Filesflash free download plugin by Ruud v.Tony 26/07/2011
 */
?>
