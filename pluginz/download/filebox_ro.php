<?php
if (!defined('RAPIDLEECH')) {
    require_once ("index.html");
    exit ();
}

class filebox_ro extends DownloadClass {
    public function Download($link) {
        global $Referer;
            $page = $this->GetPage($link);
            if (preg_match('/Location: (.*)/i', $page, $tlink)) {
                $link = $tlink[1];
                $page = $this->GetPage($link);
            }
            $this->debug($page);
    }
}
?>
