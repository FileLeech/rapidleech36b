<?php

if (! defined ( 'RAPIDLEECH' )) {
        require_once ("index.html");
        exit ();
}

class cramit_in extends DownloadClass {

    public function Download($link) {
        global $premium_acc, $Referer;
        if (($_GET ["premium_acc"] == "on" && $_GET ["premium_user"] && $_GET ["premium_pass"]) ||
            ($_GET ["premium_acc"] == "on" && $premium_acc ["cramit_in"] ["user"] && $premium_acc ["cramit_in"] ["pass"]))
        {
            $this->DownloadPremium($link);
        } elseif ($_POST['step'] == "1") {
            $this->DownloadFree($link);
        } else {
            $this->PrepareFree($link);
        }
    }

    private function PrepareFree($link) {
        global $Referer, $download_dir;
        $page = $this->GetPage($link);
        is_present($page, "File Not Found", "The file expired");

        $id = cut_str($page, 'name="id" value="','"');
        $fname = cut_str($page, 'name="fname" value="','"');

        $post = array();
        $post['rand_input'] = "";
        $post['op'] = "download1";
        $post['usr_login'] = "";
        $post['id'] = $id;
        $post['fname'] = $fname;
        $post['referer'] = $link;
        $post['method_free'] = "FREE DOWNLOAD";
        $page = $this->GetPage($link, 0, $post, $link);

        $page = $this->GetPage(urldecode("http://api.recaptcha.net/challenge?k=6LcBtAoAAAAAAOHu8QLaIYchB%2d6DEdyKClk%2daGBx"));
        if (preg_match('/Location: (.*)/i', $page, $linkc)) {
            $page = $this->GetPage($linkc[1]);
        }
        $ch = cut_str($page, "challenge : '", "'");
        $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
        $page = $this->GetPage($img);
        $headerend = strpos($page, "\r\n\r\n");
        $pass_img = substr($page, $headerend + 4);
        write_file($download_dir . "cramit_in.jpg", $pass_img);
        $data = array();
        $data['step'] = "1";
        $data['link'] = $link;
        $data['id'] = $id;
        $data['referer'] = $link;
        $data['recaptcha_challenge_field'] = $ch;
        $this->EnterCaptcha($download_dir . "cramit_in.jpg", $data, 20);
        exit;
    }

    private function DownloadFree($link) {
        $rand = cut_str($page,'name="rand" value="','"');

        $post = array();
        $post['op'] = "download2";
        $post['id'] = $_POST['id'];
        $post['rand'] = $rand;
        $post['referer'] = $link;
        $post['method_free'] = 'FREE DOWNLOAD';
        $post['method_premium'] = "";
        $post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
        $post['recaptcha_response_field'] = $_POST['captcha'];
        $post['down_direct'] = "1";
        $page = $this->GetPage($link, 0, $post, $link);
        if (!preg_match('%<a href="(http:\/\/[^\/]+\.[^\/]+\.[^\/]+\/d\/[^\/]+\/[^\/]+)">click here</a>%', $page, $dlink)) {
            html_error("Error, Downloadlink not found");
        }
        $dwn = trim($dlink[1]);
        $Url = parse_url($dwn);
        $Filename = basename($Url['path']);
        $this->RedirectDownload($dwn, $Filename, 0, 0, $link);
        exit;
    }

    private function DownloadPremium($link) {
        html_error("Please donate premium account to build downloading Premium");
    }    
}

//Cramit.in Free Download Plugin by Ruud v.Tony 2-4-2011
?>