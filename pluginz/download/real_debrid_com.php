<?php
if (!defined('RAPIDLEECH')) {
    require_once('index.html');
    exit();
}

class real_debrid_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;

        $this->posturl = "http://real-debrid.com/";
        if (preg_match("@$this->posturl[?]([^\r\n]+)@i", $link, $ck)) {
            $check = $ck[1];
            if (stristr($check, "|")) {
                $arr = explode('|', $check);
                $this->url = urlencode($arr[0]);
                $this->password = urlencode($arr[1]);
            } else { //no password input
                $this->url = urlencode($check);
            }
        } else {
            html_error('Format link unknown, please input like this http://real-debrid.com/?http://www.megaupload.com/?d=VLV1UJ0C');
        }
        if (($_REQUEST["cookieuse"] == "on" && preg_match("@auth=(\w{114})@i", $_REQUEST["cookie"], $c)) || ($_REQUEST["premium_acc"] == "on" && $premium_acc["real-debrid_com"]["cookie"])) {
            $cookie = (empty($c[1]) ? $premium_acc["real-debrid_com"]["cookie"] : $c[1]);
            return $this->Premium($cookie);
        } elseif (($_REQUEST['premium_acc'] == 'on' && $_REQUEST['premium_acc']['user'] && $_REQUEST['premium_acc']['pass']) || ($_REQUEST['premium_acc'] == 'on' && $premium_acc['real-debrid_com']['user'] && $premium_acc['real-debrid_com']['pass'])) {
            return $this->Premium();
        } else {
            html_error("This plugin need <a href='http://www.real-debrid.com/'><font color='red'>Real-Debrid</font></a> Premium Account");
        }
    }

    private function Premium($cookie = false) {
        $cookie = $this->Login($cookie);
        $page = $this->GetPage($this->posturl . "ajax/unrestrict.php?link={$this->url}&password={$this->password}&remote=0&time=" . round(microtime(true) * 1000) . "", $cookie, 0, $this->posturl . "downloaders\r\nX-Requested-With: XMLHttpRequest");
        if (preg_match('@\{"([^"]+)":(\d+),"([^"]+)":"([^"]+)"\}?(,"([^"]+)":"([^"]+)","([^"]+)":"([^"]+)",)?@', $page, $match)) {
            if ($match[2] == '0') {
                $dlink = str_replace('\\', '', cut_str($match[9], '|-|', '\r\n'));
                $filename = trim($match[4]);
                $this->RedirectDownload($dlink, $filename, $cookie, 0, $posturl . "downloaders");
            } else {
                html_error("$match[1][$match[2]] : $match[4]");
            }
        } else {
            html_error("Error: Unknown Page Response!");
        }
    }

    private function Login($auth = false) {
        global $premium_acc;

        if (!$auth) {
            if (!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"])) {
                $user = $_REQUEST["premium_user"];
                $pass = $_REQUEST["premium_pass"];
            } else {
                $user = $premium_acc["real-debrid_com"]['user'];
                $pass = $premium_acc["real-debrid_com"]['pass'];
            }
            if (empty($user) || empty($pass)) html_error("Username or password is empty, you need to insert your login detail!");
            $page = $this->GetPage($this->posturl . "ajax/login.php?user=" . urlencode($user) . "&pass=" . urlencode($pass) . "", "lang=en", 0, $this->posturl . "\r\nX-Requested-With: XMLHttpRequest");
            $cookie = GetCookies($page) . "; lang=en";
            is_present($page, 'Your login informations are incorrect !');
        } elseif (strlen($auth) == 114) {
            $cookie = "auth=$auth; lang=en";
        } else {
            html_error("[Cookie] Invalid cookie (" . strlen($auth) . " != 114).");
        }
        //check account
        $page = $this->GetPage($this->posturl . "account", $cookie, 0, $this->posturl);
        is_present($page, "<h3>403 - Forbidden</h3>", "Account invalid!");
        is_present($page, 'A dedicated server has been detected and your account will not be Premium on this IP address.');
        is_present($page, '<strong>Free</strong>', 'Account Free, login not validated!');

        return $cookie;
    }

}

// real-debrid download plugin by Ruud v.Tony 23-10-2011
?>
