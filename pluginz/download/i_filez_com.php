<?php
if (!defined('RAPIDLEECH')) {
    require_once ('index.html');
    exit();
}

class i_filez_com extends DownloadClass {

    public function Download($link) {
		global $premium_acc;
		if (!$_REQUEST['step']) {
			$this->page = $this->GetPage($link);
			is_present($this->page, 'File was not found in the i-filez.com database. ');
			is_present($this->page, "The owner of the chosen file stopped sharing it (the file is not active).");
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['i-filez_com']['user'] && $premium_acc['i-filez_com']['pass']))) {
			return $this->Premium();
		} else {
			return $this->Free();
		}
	}
	
	private function Free() {
        if ($_POST['step'] == '1') {
            $post['vvcid'] = $_POST['vvcid'];
            $post['verifycode'] = $_POST['captcha'];
            $post['FREE'] = 'Regular+download';
            $this->link = urldecode($_POST['link']);
            $cookie = urldecode($_POST['cookie']);
            $page = $this->GetPage($this->link, $cookie, $post, $this->link);
        } else {
            $cookie = GetCookies($this->page);

            if (!preg_match('@\/includes\/vvc\.php[?]vvcid=(\d+)@', $this->page, $cap)) html_error ('Captcha link not found!');
            $imglink = 'http://i-filez.com'.$cap[0];

            $data = $this->DefaultParamArr($this->link, $cookie);
            $data['step'] = '1';
            $data['vvcid'] = $cap[1];
            $this->EnterCaptcha($imglink, $data);
            exit();
        }
		if (preg_match("@<p class='notice'>(.*)<\/p>@", $page, $msg)) html_error($msg[1]);
		if (preg_match('@var sec=(\d+)@', $page, $wait)) $this->CountDown ($wait[1]);
		//actually we can get the download link directly without having to wait, but eeergh maybe they gave that for a reason...
		$dlink = urldecode(cut_str($page, "wait_input\").value= unescape('", "')"));
		$filename = parse_url($dlink);
		$FileName = basename($filename['path']);
		$this->RedirectDownload($dlink, $FileName, $cookie, 0, $this->link);
		exit();
    }
	
	private function Premium() {
		global $premium_acc;
		
        $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["i-filez_com"] ["user"]);
        $pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["i-filez_com"] ["pass"]);
        if (empty($user) || empty($pass)) html_error("Login failed, username[$user] or password[$pass] is empty!");
		
		$postlogin = 'http://i-filez.com/';
		$post['login'] = 'login';
		$post['loginusername'] = $user;
		$post['loginpassword'] = $pass;
		$post['submit'] = 'login';
		$post['rememberme'] = 'on';
		$page = $this->GetPage($postlogin, 0, $post, $postlogin);
		is_present($page, 'User not found');
		$cookie = GetCookies($page);
		$page = $this->GetPage($this->link, $cookie);
		$dlink = cut_str($page, '<th>Download:', '</td>');
		$dlink = cut_str($dlink, "<a href='", "'>");
		if (empty($dlink)) html_error('Error [Download Link PREMIUM not found!]');
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
	}
}

/*
 * i-filez.com free download plugin by Ruud v.Tony 17-10-2011
 * updated to support premium by Ruud v.Tony 24-03-2012
 */
?>
