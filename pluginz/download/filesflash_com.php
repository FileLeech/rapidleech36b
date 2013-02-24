<?php
if (!defined('RAPIDLEECH')) {
	require_once ("index.html");
	exit();
}

class filesflash_com extends DownloadClass {

	public function Download($link) {
		global $premium_acc;

		if (!$_REQUEST['step']) {
			$this->page = $this->GetPage($link);
			is_present($this->page, 'That file is not available for download');
			$this->cookie = GetCookiesArr($this->page);
		}
		$this->link = $link;
		$this->url = 'http://filesflash.com/';
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || (!empty($premium_acc['filesflash_com']['user']) && !empty($premium_acc['filesflash_com']['pass'])))) {
			return $this->premium();
		} else {
			return $this->free();
		}
	}

	private function premium() {

		$cookie = $this->login();
		$page = $this->GetPage($this->link, $cookie);
		if (!preg_match("@Location: (http:\/\/[^\r\n]+)@i", $page, $dl)) html_error("Error: Download Link [PREMIUM] not found!");
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
	}

	private function login() {
		global $premium_acc;

		$email = ($_REQUEST["premium_user"] ? $_REQUEST["premium_user"] : $premium_acc["filesflash_com"]["user"]);
		$pass = ($_REQUEST["premium_pass"] ? $_REQUEST["premium_pass"] : $premium_acc["filesflash_com"]["pass"]);
		if (empty($email) || empty($pass)) html_error('Error[Username : ' . $email . ' or Password : ' . $pass . ' is empty]');

		$post = array();
		$post['email'] = urlencode($email);
		$post['password'] = urlencode($pass);
		$post['submit'] = 'Login';
		$page = $this->GetPage($this->url . "login.php", $this->cookie, $post, $this->url);
		is_present($page, "Invalid email address or password.");
		$cookie = GetCookiesArr($page, $this->cookie);
		// check account
		$page = $this->GetPage($this->url . "myaccount.php", $cookie, 0, $this->url . "index.php");
		is_present($page, "<td>Premium Status:</td><td>Not Premium", "Account Status: Free");

		return $cookie;
	}

	private function free() {

		if ($_REQUEST['step'] == '1') {
			$post = array();
			$post['token'] = $_POST['token'];
			$post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
			$post['recaptcha_response_field'] = $_POST['captcha'];
			$post['submit'] = 'Submit';
			$this->cookie = urldecode($_POST['cookie']);
			$page = $this->GetPage($this->url . 'freedownload.php', $this->cookie, $post, $this->link);
		} else {
			$post = array();
			$post['token'] = cut_str($this->page, 'name="token" value="', '"');
			$post['freedl'] = cut_str($this->page, 'name="freedl" value="', '"');
			$page = $this->GetPage($this->url . 'freedownload.php', $this->cookie, $post, $this->link);
		}
		if (stripos($page, 'recaptcha/api/challenge')) {
			$data = $this->DefaultParamArr($this->link, $this->cookie);
			$data['step'] = '1';
			$data['token'] = cut_str($page, 'name="token" value="', '"');
			//get the captcha
			if (!preg_match('/\/recaptcha\/api\/challenge\?\?rand=\d+\&amp;k=([^\r\n"]+)/', $page, $c)) html_error('Error[Captcha Data not found!]');
			//download the captcha image
			$ch = cut_str($this->GetPage('http://www.google.com/recaptcha/api/challenge?k=' . $c[1]), "challenge : '", "'");
			$capt = $this->GetPage("http://www.google.com/recaptcha/api/image?c=" . $ch);
			$capt_img = substr($capt, strpos($capt, "\r\n\r\n") + 4);
			$imgfile = DOWNLOAD_DIR . "filesflash_captcha.jpg";

			if (file_exists($imgfile)) unlink($imgfile);
			if (empty($capt_img) || !write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.", 0);
			// Captcha img downloaded

			$data['recaptcha_challenge_field'] = $ch;
			$this->EnterCaptcha($imgfile, $data, 20);
			exit;
		}
		is_present($page, "Your link has expired. Please try again.");
		if (!preg_match('/count=(\d+)/', $page, $wait)) html_error("Error: Timer not found!");
		$this->CountDown($wait[1]);
		$dlink = cut_str($page, '<div id="link" style="display:none"><a href="', '">');
		if (!$dlink) html_error("Error: Download Link [FREE] not found???");
		$FileName = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $FileName, $this->cookie, 0, $this->url . 'freedownload.php');
		exit();
	}

}

/*
 * Filesflash free download plugin by Ruud v.Tony 26/07/2011
 * Updated to support premium by Ruud v.Tony 11-01-2012
 * Small fix in premium setting so it wont mess up with other by Ruud v.Tony 12-01-2012
 * Fixed free download code by Tony Fauzi Wihana/Ruud v.Tony 09-02-2013
 */
?>
