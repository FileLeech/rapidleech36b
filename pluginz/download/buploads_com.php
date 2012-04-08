<?php
if (!defined('RAPIDLEECH')) {
    require_once('index.html');
    exit();
}

class buploads_com extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (!$_REQUEST['step']) {
            $page = $this->GetPage($link);
            is_present($page, 'DL_FileNotFound', 'File not found');
            $CookieArr = GetCookiesArr($page);
            $cookie = CookiesToStr($CookieArr);
        }
        unset($page);
        if (($_REQUEST['premium_acc'] == 'on' && $_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($_REQUEST['premium_acc'] == 'on' && $premium_acc['buploads_com']['user'] && $premium_acc['buploads_com']['pass'])) {
            $user = ($_REQUEST["premium_user"] ? $_REQUEST["premium_user"] : $premium_acc["buploads_com"]["user"]);
            $pass = ($_REQUEST["premium_pass"] ? $_REQUEST["premium_pass"] : $premium_acc["buploads_com"]["pass"]);
            if (empty($user) || empty($pass)) {
                html_error("Login Failed: Username or Password is empty. Please check login data.");
            }

            $Url = "http://buploads.com/";

            $post = array();
            $post['refer_url']= urlencode($Url);
            $post['act'] = 'login';
            $post['user'] = $user;
            $post['pass'] = $pass;
            $post['autologin'] = '1';
            $post['login'] = 'Log me in';
            $page = $this->GetPage($Url."en/login.php", $cookie, $post, $Url."en/login.php");
            is_present($page, 'Username/Password can not be found in our database!');
            $cookie = CookiesToStr(array_merge($CookieArr, GetCookiesArr($page)));

            //check account
            $page = $this->GetPage($Url."en/members.php", $cookie, 0, $Url);
            is_notpresent($page, 'Premium</a>', 'Account Free, You can\'t use premium access!');

            $page = $this->GetPage($link, $cookie);
            if (!preg_match('@Location: (http:\/\/[\d.]+?[\w.]+?\/getfile\.php[^|\r|\n]+)@i', $page, $dl)) html_error('Error: Premium Download link not found!');
            $dlink = trim($dl[1]);
            $FileName = basename(parse_url($dlink, PHP_URL_PATH));
            $this->RedirectDownload($dlink, $FileName, $cookie);
        } else {
            html_error("This filehost owner remove free support, <img src='http://us.i1.yimg.com/us.yimg.com/i/mesg/emoticons7/10.gif'>");
        }
    }
}

/*
 * buploads.com download plugin by Ruud v.Tony 09-11-2011
 */
?>
