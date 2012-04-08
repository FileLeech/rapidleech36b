<?php
if (!defined('RAPIDLEECH')) {
    require_once("index.html");
    exit();
}

class filezup_com extends DownloadClass {

    public function Download($link) {
        if ($_POST['step'] == "1") {
            $this->DownloadFree($link);
        } else {
            $this->Retrieve($link);
        }
    }

    private function Retrieve($link) {
        global $Referer;
        $page = $this->GetPage($link);
        is_present($page, "File Not Found", "The file you were looking for could not be found");

        $id = cut_str($page, 'name="id" value="', '"');
        $fname = cut_str($page, 'name="fname" value="', '"');

        $post = array();
        $post['op'] = "download1";
        $post['usr_login'] = "";
        $post['id'] = $id;
        $post['fname'] = $fname;
        $post['referer'] = "";
        $post['method_free'] = "Free Download";
        $page = $this->GetPage($link, 0, $post, $link);
        $rand = cut_str($page, 'name="rand" value="', '"');
        if (preg_match("#You have to wait (\d+) minutes, (\d+) seconds till next download#", $page, $message)) {
            html_error($message[0]);
        }
        if (preg_match('#(\d+)</span> seconds#', $page, $wait)) {
            $this->CountDown($wait[1]);
        }
        if (stristr($page, "Enter code below")) {
            preg_match('#(http:\/\/www\.filezup.com\/captchas/.+)"#', $page, $temp);

            $data = array();
            $data['step'] = '1';
            $data['link'] = $link;
            $data['id'] = $id;
            $data['rand'] = $rand;
            $data['referer'] = urlencode($link);
            $this->EnterCaptcha($temp[1], $data, 20);
            exit();
        }
    }

    private function DownloadFree($link) {
        $post = array();
        $post['op'] = "download2";
        $post['id'] = $_POST['id'];
        $post['rand'] = $_POST['rand'];
        $post['referer'] = urldecode($_POST['referer']);
        $post['method_free'] = "Free Download";
        $post['method_premium'] = "";
        $post['code'] = $_POST['captcha'];
        $post['down_script'] = "1";
        $link = $_POST['link'];
        $page = $this->GetPage($link, 0, $post, $link);
        if (strpos($page, "Wrong captcha")) {
            return $this->Retrieve($link);
        }
        if (!stristr($page, "Location:")) {
            html_error("Sorry, download link couldn't be found. Contact the author n give the link which u have this error!");
        }
        $dlink = trim(cut_str($page, "Location: ", "\n"));
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, 0, 0, $link);
        exit();
    }

}

//filezup.com free download plugin by Ruud v.Tony 28-06-2011
?>
