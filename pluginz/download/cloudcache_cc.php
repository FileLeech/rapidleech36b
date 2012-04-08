<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class cloudcache_cc extends DownloadClass {
    
    public function Download($link) {
        global $premium_acc;
        
        if (!$_REQUEST['step']) {
            $this->page = $this->GetPage($link, "lang=english");
            is_present($this->page, "The file you were looking for could not be found, sorry for any inconvenience.");
            $this->cookie = "lang=english";
        }
        if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['cloudcache_cc']['user'] && $premium_acc['cloudcache_cc']['pass']))) {
            return $this->Premium($link);
        } elseif ($_REQUEST['step'] == 'passpre') {
            return $this->Premium($link, 1);
        } else {
            return $this->Free($link);
        }
    }
    
    private function Premium($link, $password = 0) {
        if ($password == 1) {
            $post['op'] = $_POST['op'];
            $post['id'] = $_POST['id'];
            $post['rand'] = $_POST['rand'];
            $post['referer'] = $_POST['referer'];
            $post['method_free'] = '';
            $post['method_premium'] = $_POST['method_premium'];
            $post['password'] = $_POST['password'];
            $post['down_direct'] = $_POST['down_direct'];
            $cookie = decrypt(urldecode($_POST['cookie']));
            $link = urldecode($_POST['link']);
            $page = $this->GetPage($link, $cookie, $post, $link);
        } else {
            $cookie = $this->login();
            $page = $this->GetPage($link, $cookie);
        }
        if (!preg_match('@http:\/\/[\d.]+\/files\/[^\r\n"]+@', $page, $dl)) {
            $form = cut_str($page, '<Form name="F1" method="POST"', '</Form>');
            if (!preg_match_all('%<input type="hidden" name="([^"]+)" value="([^"]+)?">%', $form, $match)) html_error('Error [Post Data PREMIUM not found!]');
            $match = array_combine($match[1], $match[2]);
            if (stripos($form, 'Password:')) {
                if (stripos($page, 'Wrong password')) echo ("<center><font color='red'><b>Wrong Password File Entered!</b></font></center>");
                $data = array_merge($this->DefaultParamArr($link, encrypt($cookie)), $match);
                $data['step'] = 'passpre';
                $this->EnterPassword($data);
                exit();
            }
            $post = array();
            foreach ($match as $k => $v) {
                $post[$k] = $v;
            }
            $page = $this->GetPage($link, $cookie, $post, $link);
            if (!preg_match('@http:\/\/[\d.]+\/files\/[^"]+@', $page, $dl)) html_error('Error [Download Link PREMIUM not found!]');
        }
        $dlink = trim($dl[0]);
        $filename = basename(parse_url($dlink, PHP_URL_PATH));
        $this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
    }
    
    private function login() {
        global $premium_acc;
        
        $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["cloudcache_cc"] ["user"]);
        $pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["cloudcache_cc"] ["pass"]);
        if (empty($user) || empty($pass)) html_error("Login failed, username or password is empty!");
        
        $posturl = 'http://www.cloudcache.cc/';
        $post['op'] = 'login';
        $post['redirect'] = $posturl;
        $post['login'] = $user;
        $post['password'] = $pass;
        $post['x'] = rand(11,64);
        $post['y']= rand(11, 23);
        $check = $this->GetPage($posturl, $this->cookie, $post, $posturl."login.html");
        is_present($check, 'Incorrect Login or Password');
        $cookie = $this->cookie ."; " .  GetCookies($check);
        
        //check account
        $check = $this->GetPage($posturl."?op=my_account", $cookie, 0, $posturl);
        is_notpresent($check, '<TD>Username:</TD>', 'Invalid Account!');
        is_notpresent($check, '<TD>Premium account expire:</TD>', 'Account Type : FREE');
        
        return $cookie;
    }
    
    private function EnterPassword($inputs) {
        global $PHP_SELF;
        if (!is_array($inputs)) {
            html_error("Error parsing password data.");
        }
        echo "\n" . '<center><form action="' . $PHP_SELF . '" method="post" >' . "\n";
        foreach ($inputs as $name => $input) {
            echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
        }
        echo '<h4>Enter password here: <input type="text" name="password" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Submit" /></h4>' . "\n";
        echo "<script type='text/javascript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
        echo "\n</form></center>\n</body>\n</html>";
        exit();
    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
