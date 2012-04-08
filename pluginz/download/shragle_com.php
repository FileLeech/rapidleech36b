<?php
if (!defined("RAPIDLEECH")) {
    require_once("index.html");
    exit();
}

class shragle_com extends DownloadClass {
    
    public function Download($link) {
        global $premium_acc;
        
        if ($_REQUEST["premium_acc"] == "on" && (($_REQUEST["premium_user"] && $_REQUEST["premium_pass"]) || ($premium_acc["shragle_com"]["user"] && $premium_acc["shragle_com"]["pass"]))) {
            return $this->Premium($link);
        } else {
            return $this->Free($link);
        }
    }
    
    private function Premium($link) {
        
        $cookie = $this->login();
        $page = $this->GetPage($link, $cookie);
        is_present($page, cut_str($page, '<div class="error">', '</div>'));
        if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $rd)) { //non direct download
            $form = cut_str($page, '<form name="download"', '</form>');
            if (!preg_match('@action="([^"]+)"@', $form, $pl)) html_error("Error: Post Link [PREMIUM] not found!");
            if (!preg_match_all('%<input type="hidden" name="([^"]+)" value="([^"]+)?" \/>%', $page, $pre)) html_error("Error: Post Data [PREMIUM] not found!");
            $match = array_combine($pre[1], $pre[2]);
            $post = array();
            foreach ($match as $key => $value) {
                $post[$key] = $value;
            }
            $page = $this->GetPage(trim($pl[1]), $cookie, $post, $link);
            if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $rd)) html_error("Error: Redirect Link 1 [PREMIUM] not found!");
            $redirect = trim($rd[1]);
            $cookie = $cookie."; ".GetCookies($page);
            $page = $this->GetPage($redirect, $cookie, 0, $link);
            if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $rd)) html_error("Error: Download Link 1 [PREMIUM] not found!");
            $dlink = trim($rd[1]);
            $filename = basename(parse_url($dlink, PHP_URL_PATH));
            $this->RedirectDownload($dlink, $filename, $cookie);
        } else {
            $redirect = trim($rd[1]);
            $cookie = $cookie."; ".GetCookies($page);
            $page = $this->GetPage($redirect, $cookie, 0, $link);
            if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $rd)) html_error("Error: Redirect Link 2 [PREMIUM] not found!");
            $redirect = trim($rd[1]);
            $page = $this->GetPage($redirect, $cookie, 0, $link);
            if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $dl)) html_error("Error: Download Link 2 [PREMIUM] not found!");
            $dlink = trim($dl[1]);
            $filename = basename(parse_url($dlink, PHP_URL_PATH));
            $this->RedirectDownload($dlink, $filename, $cookie);
        }
    }
    
    private function login() {
        global $premium_acc;

        $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["shragle_com"] ["user"]);
        $pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["shragle_com"] ["pass"]);
        if (empty($user) || empty($pass)) html_error("Login failed, username or password is empty!");
        
        $posturl = "http://www.shragle.com/";
        
        $post['username'] = $user;
        $post['password'] = $pass;
        $post['submit'] = 'Login';
        $post['cookie'] = 'on';
        $page = $this->GetPage($posturl."login", 0, $post, $posturl);
        is_present($page, cut_str($page, '<div class="error">', '</div>'));
        $cookie = GetCookies($page);
        
        //check account
        $page = $this->GetPage($posturl."user/", $cookie, 0, $posturl);
        is_present($page, "<span class=\"bold\">Free</span>", "Account Type: Free!");
        
        return $cookie;
        
    }
    
    private function Free($link) {
        $page = $this->GetPage($link);
        $cookie = GetCookies($page);
        is_present($page, cut_str($page, '<div class="error">', '</div>'));
        if (!preg_match('/var downloadWait = (\d+);/', $page, $wait)) html_error("Error: Timer id not found!");
        $this->CountDown($wait[1]);
        $form = cut_str($page, '<form name="download"', '</form>');
        if (!preg_match('/action="([^"]+)" method="post"/', $form, $fr)) html_error("Error: Post Link [1] not found!");
        if (!preg_match_all('@<input type="hidden" name="([^"]+)" value="([^"]+)?" \/>@i', $form, $match)) html_error("Error: Post Data not found!");
        $post = array();
        $match = array_combine($match[1], $match[2]);
        foreach ($match as $k => $v) {
            $post[$k] = $v;
        }
        $page = $this->GetPage(trim($fr[1]), $cookie, $post, $link);
        if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $fr)) html_error("Error: Post Link [2] not found!");
        $page = $this->GetPage(trim($fr[1]), $cookie, 0, $link);
        if (!preg_match('/Location: (http:\/\/[^\r\n]+)/i', $page, $dl)) html_error("Error: Download Link [FREE] not found!");
        $dlink = trim($dl[1]);
        $filename = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
        exit();
    }
}

/*
 * Shragle.com free download plugin by Ruud v.Tony 12-01-2012
 * Updated to support premium by Ruud v.Tony 17-01-2012
 */
?>
