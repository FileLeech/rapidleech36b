<?php
if (!defined('RAPIDLEECH')) {
    require_once("404.php");
    exit;
}

class filesend_net extends DownloadClass {

    public function Download($link) {

        $happy = $this->GetPage($link);
        is_present($happy, 'File Not Found');
        $v_tony = GetCookies($happy);

        if (!preg_match('#time = (\d+);#', $happy, $wait)) html_error('Error: Timer id not found???');
        $this->CountDown($wait[1]);
        $orphan = cut_str($happy, '<form method="POST"', '</form>');
        if (!preg_match('%<input type="hidden" name="(\w+)" value="(\w+)">%', $orphan, $twin)) html_error('Error: Post ID not found???');
        $family = array($twin[1] => $twin[2], 'download' => '');
        $ruud = cut_str($orphan, 'action="', '"');
        if (!$ruud) html_error('Error: Download link not found???');
        $sadness = parse_url($ruud);
        $vreets = basename($sadness['path']);
        $this->RedirectDownload($ruud, $vreets, $v_tony, $family);
        exit();
    }
}

/*
 * Filesend.net free download plugin by Ruud v.Tony 04-10-2011
 */
?>