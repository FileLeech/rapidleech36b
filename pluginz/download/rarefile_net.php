<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class rarefile_net extends DownloadClass {
	
	public function Download($link) {
		global $premium_acc;
		
		if (!$_REQUEST['step']) {
			$this->cookie = array('lang' => 'english');
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, '<b>File Not Found</b>');
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['rarefile_net']['user'] && $premium_acc['rarefile_net']['pass']))) {
			return $this->Premium();
		} else {
			return $this->Free();
		}
	}
	
	private function Free() {
		if ($_REQUEST['step'] == '1') {
			$this->link = urldecode($_POST['link']);
			$this->cookie = urldecode($_POST['cookie']);
			$post = array();
			foreach ($_POST['tmp'] as $k => $v) {
				$post[$k] = $v;
			}
			$post['code'] = $_POST['captcha'];
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		} else {
			is_present($page, cut_str($page, '<div class="err">', '<br>'));
			$form = cut_str($this->page, '<Form name="F1" method="POST"', '</Form>');
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Post Data - FREE not found]');
			$match = array_combine($match[1], $match[2]);
			if (!preg_match('/(\d+)<\/span> seconds/', $form, $w)) html_error('Error[Timer not found!]');
			$this->CountDown($w[1]);
			//get captcha image
			if (!preg_match('/http:\/\/.+rarefile\.net\/captchas\/[^\r\n\'"]+/', $form, $c)) html_error('Error[Captcha Data not found!]');
			$cap = $this->GetPage($c[0]);
			$capt_img = substr($cap, strpos($cap, "\r\n\r\n") + 4);
			$imgfile = DOWNLOAD_DIR."rarefile_net_captcha.jpg";
			if (file_exists($imgfile)) unlink ($imgfile);
			if (!write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.");
			
			$data = $this->DefaultParamArr($this->link, $this->cookie);
			$data['step'] = '1';
			foreach ($match as $k => $v) {
				$data["tmp[$k]"] = $v;
			}
			$this->EnterCaptcha("$imgfile?" . time(), $data);
			exit;
		}
		is_present($page, cut_str($page, '<div class="err">', '</div>'));
		if (!preg_match('/Location: (https?:\/\/[^\r\n]+)/i', $page, $dl)) html_error('Error[Download Link - FREE not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $this->cookie, 0, $this->link);
		exit;
	}
	
	private function Premium() {
		
		$cookie = $this->login();
		$page = $this->GetPage($this->link, $cookie);
	}
	
	private function login() {
		global $premium_acc;
		
		$user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["rarefile_net"] ["user"]);
		$pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["rarefile_net"] ["pass"]);
		if (empty($user) || empty($pass)) html_error("Login failed, username or password is empty!");
		
		$posturl = 'http://www.rarefile.net/';
		$post = array();
		$post['op'] = 'login';
		$post['redirect'] = urlencode($posturl);
		$post['login'] = $user;
		$post['password'] = $pass;
		$page = $this->GetPage($posturl, $this->cookie, $post, $posturl.'login.html');
		is_present($page, 'Your account was banned by administrator.');
		is_present($page, 'Incorrect Login or Password');
		$cookie = GetCookiesArr($page, $this->cookie);
		
		//check account
		$page = $this->GetPage($posturl.'?op=my_account', $cookie, 0, $posturl);
		is_notpresent($page, 'Premium account expire:', 'Error[Account isn\'t Premium!]');
		
		return $cookie;
		
	}
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
