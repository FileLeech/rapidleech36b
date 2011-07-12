<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
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
            if (isset($_POST['password'])) {
                $password=$_POST['password'];
            }
            $this->PrepareFree($link, $password);
        }
    }

    private function PrepareFree($link, $password) {
        global $Referer;
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
            if (stristr($page, 'class="err"')) {
                $errmsg = cut_str($page, 'class="err">', '<br>');
                html_error($errmsg);
            }
            $rand = cut_str($page,'name="rand" value="','"');
            if (strpos ($page, "Password :") && !isset($password)) {
                echo "\n" . '<form action="' . $PHP_SELF . '" method="post" >' . "\n";
                echo '<input type="hidden" name="link" value="' . $link . '" />' . "\n";
                echo '<input type="hidden" name="id" value="' . $id . '" />' . "\n";
                echo '<input type="hidden" name="rand" value="' . $rand . '" />' . "\n";
                echo '<input type="hidden" name="referer" value="' . $link . '" />' . "\n";
                echo '<h4>Enter password here: <input type="text" name="password" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Submit" /></h4>' . "\n";
                echo "<script language='JavaScript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
                echo "</form>\n</body>\n</html>";
                exit();
            }
            if (strpos($page, "Enter the code below:")) {
                preg_match('#(http:\/\/.+captchas\/[^"]+)">#', $page, $temp);

                $data = array();
                $data['step'] = '1';
                $data['link'] = $link;
                $data['id'] = $id;
                $data['rand'] = $rand;
                $data['referer'] = urlencode($link);
                $data['password'] = $password;
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
        $post['method_free'] = 'FREE DOWNLOAD';
        $post['method_premium'] = "";
        $post['code'] = $_POST['captcha'];
        $post['down_direct'] = "1";
        $post['password'] = $_POST['password'];
        $link = $_POST['link'];
        $page = $this->GetPage($link, 0, $post, $link);
        if (strpos($page, "Wrong password") || strpos($page, "Wrong captcha")) {
            return $this->PrepareFree($link, $password);
        }
        if (!preg_match('#(http:\/\/.+cramit\.in\/d\/[^"]+)">click here#', $page, $dl)) {
            html_error("Sorry, Download link. Contact the author n give him the link which u have this error!");
        }
        $dlink = trim($dl[1]);
        $Url = parse_url($dlink);
        $Filename = basename($Url['path']);
        $this->RedirectDownload($dlink, $Filename, 0, 0, $link);
        exit;
    }

    private function DownloadPremium($link) {
        html_error("Please donate premium account to build downloading Premium");
    }
}

//Cramit.in Free Download Plugin by Ruud v.Tony 2-4-2011
//Updated 11-5-2011 to support password protected files by help vdhdevil
//Fixed for site layout change by Ruud v.Tony 24-06-2011
//Update for the captcha failure n the error message 06-07-2011
?>