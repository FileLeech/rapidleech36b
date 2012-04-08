<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class howfile_com extends DownloadClass {
    
    public function Download($link) {
        $page = $this->GetPage($link);
        is_present($page, "File not found or System under maintanence.");
        if (!preg_match('@Set-Cookie: (JSESSIONID=\w+);@', $page, $ca)) html_error("Error: JSESSIONID Cookie not found!");
        if (!preg_match_all('@setCookie\("(v\w+)", "(\w+)",@', $page, $cb)) html_error("Error: vid\vid1 Cookie not found!");
        $cookie = "$ca[1];". CookiesToStr(array_combine($cb[1],$cb[2]));
        if (!preg_match("@<a href=\"([^\"]+)\" onclick='setCookie@", $page, $dl)) html_error("Error: Download Link not found!");
        $dlink = trim($dl[1]);
        $filename = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $filename, $cookie, 0, $link, $filename);
        exit();
    }
}

/*
 * howfile.com free download plugin by Ruud v.Tony 18-01-2012
 */
?>
