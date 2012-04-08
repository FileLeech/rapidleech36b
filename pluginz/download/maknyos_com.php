<?php
if (!defined('RAPIDLEECH')) {
    require_once('index.html');
    exit();
}

class maknyos_com extends DownloadClass {
    
    public function Download($link) {
        global $premium_acc;
        
        //I'm boring to type host address over & over, so I make that here...
        $this->host = "http://www.maknyos.com/";
        $this->link = $link;
        //We need to set language to indonesian first...
        if (!$_REQUEST['step']) {
            $check = $this->GetPage($this->host."?op=change_lang&lang=indonesia");
            $this->cookie = GetCookies($check);
            // We've got the cookie...
        }
        if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($premium_acc['maknyos_com']['user'] && $premium_acc['maknyos_com']['pass']))) {
            return $this->Login();
        } else {
            return $this->DownloadFree();
        }
    }
    
    private function Login() {
        global $premium_acc;

        $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["maknyos_com"] ["user"]);
        $password = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["maknyos_com"] ["pass"]);
        if (empty($user) || empty($password)) html_error("Login gagal, username atau password kosong!");
        
        $post = array();
        $post['op'] = 'login';
        $post['redirect'] = $this->host;
        $post['login'] = $user;
        $post['password'] = $password;
        $post['x'] = rand(11, 99);
        $post['y'] = rand(11, 99);
        $page = $this->GetPage($this->host, $this->cookie, $post, $this->host."login.html");
        is_present($page, "Login dan Kata sandi tidak tepat");
        $this->cookie = GetCookies($page). "; lang=indonesia";
        // From Here, we can check the account premium or not, but since we only use free account, we can skip that...
        
        return $this->DownloadFree();
    }
    
    private function DownloadFree() {
        $page = $this->GetPage($this->link, $this->cookie, 0, $this->link);
        is_present($page, "File yang sedang anda cari tidak dapat ditemukan, maaf untuk ketidaknyamanan anda saat ini.");
        $form = cut_str($page, "<Form method=\"POST\" action=''>", "</Form>");
        if (!preg_match_all('<input type="hidden" name="([^"]+)" value="([^"]+)">', $form, $one) || !preg_match_all('@<input type="submit" name="(\w+_free)" value="([^"]+)">@', $form, $two)) html_error("Error: Post Data [1] tidak dapat ditemukan!");
        $match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
        $post = array();
        foreach ($match as $kunci => $nilai) {
            $post[$kunci] = $nilai;
        }
        $page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
        unset($post);
        $form = cut_str($page, '<Form name="F1" method="POST"', '</Form>');
        is_present($form, cut_str($form, '<p class="err">', '<br />'));
        if (!preg_match('@(\d+)<\/span> detik@', $form, $tunggu)) html_error("Error: Timer tidak dapat ditemukan!");
        $this->CountDown($tunggu[1]);
        if (!preg_match_all("#<span style='position:absolute;padding-left:(\d+)px;padding-top:\d+px;'>&\#(\d+);</span>#", $form, $temp)) html_error("Error: Form Captcha tidak dapat ditemukan!");
        for ($i = 0; $i < 3; $i++) {
            for ($j = $i + 1; $j <= 3; $j++) {
                if ($temp[1][$i] > $temp[1][$j]) {
                    $t = $temp[1][$i];
                    $temp[1][$i] = $temp[1][$j];
                    $temp[1][$j] = $t;
                    $t = $temp[2][$i];
                    $temp[2][$i] = $temp[2][$j];
                    $temp[2][$j] = $t;
                }
            }
        }
        $captcha = "";
        for ($i = 0; $i <= 3; $i++) {
            $captcha.=$temp[2][$i] - 48;
        }
        if (!preg_match_all('<input type="hidden" name="([^"]+)" value="([^"]+)?">', $form, $match)) html_error("Error: Post Data [2] tidak dapat ditemukan!");
        $match = array_combine($match[1], $match[2]);
        $post = array();
        foreach ($match as $kunci => $nilai) {
            $post[$kunci] = $nilai;
        }
        $post['code'] = $captcha;
        $page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
        is_present($page, cut_str($page, '<p class="err">','</p>'));
        if (!preg_match('/(http:\/\/.+maknyos\.com\/files\/[^"]+)">/', $page, $dl)) html_error("Error: Link Download tidak dapat ditemukan!");
        $dlink = trim($dl[1]);
        $filename = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $filename, $this->cookie, 0, $this->link);
        exit();
    }
}

/*
 * by Ruud v.Tony 23-01-2012
 */
?>
