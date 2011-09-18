<?php
if (!defined('RAPIDLEECH')) {
    require_once("404.php");
    exit;
}

class profitupload_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["profitupload_com"] ["user"] && $premium_acc ["profitupload_com"] ["pass"])) {
            $this->DownloadPremium($link);
        } else {
            $this->DownloadFree($link);
        }
    }

    private function DownloadFree($link) {
        $page = $this->GetPage($link);
        is_present($page, "El archivo que que buscas no existe o ha sido eliminado.", "El archivo que que buscas no existe o ha sido eliminado.");
        $Cookies = GetCookies($page);
        insert_timer(18);
        if (!preg_match('#http:\/\/.*\/get\/[^\'"]+#', $page, $getlink)) {
            html_error("Error 0x01: Plugin is out of date");
        }
        $page = $this->GetPage($getlink[0], $Cookies, 0, $link);
        $post = array();
        $post['task'] = "download";
        $post['submit.x'] = "114";
        $post['submit.y'] = "33";
        $Url = parse_url($getlink[0]);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($getlink[0], $FileName, $Cookies, $post, $getlink[0]);
        exit;
    }

    private function DownloadPremium($link) {
        html_error("Not Support Now");
    }

    /*
     * by vdhdevil Jan-08-2011
     */
}

?>
