<?php
if (!defined('RAPIDLEECH')){
  require_once("index.html");
  exit;
}

class animeground_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
           ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["animeground_com"] ["user"] && $premium_acc ["animeground_com"] ["pass"])) {
            $this->DownloadMember($link);
        } else {
            html_error("You need to be member of animeground.com for downloading the file!");
        }
    }

    private function DownloadMember($link) {
        global $premium_acc;

            $Username = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["animeground_com"] ["user"];
            $password = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["animeground_com"] ["pass"];
            $auth = base64_encode($Username.":".$password);
            if (function_exists('encrypt')) {
                $auth = encrypt($auth);
            }
            $Url = parse_url($link);
            $FileName = basename($Url['path']);
            $this->RedirectDownload($link, $FileName, 0, 0, "http://www.animeground.com/index.asp", 0, $auth);
        exit;
    }
}

//by vdhdevil & Ruud v.Tony 13-04-2011
?>

