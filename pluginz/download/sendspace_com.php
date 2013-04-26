<?php
if (!defined('RAPIDLEECH')) {
	require_once("index.html");
	exit;
}

class sendspace_com extends DownloadClass {

	public function Download($link) {
		global $premium_acc;
		if (!$_REQUEST['step']) {
			$this->page = $this->GetPage($link);
			if (preg_match("/Location: (http:\/\/.+sendspace\.com\/dlpro\/[^\s\t\r\n]+)/i", $this->page, $check)) {
				$dlink = html_entity_decode(urldecode(trim($check[1])), ENT_QUOTES, 'UTF-8');
				$filename = basename(parse_url($dlink, PHP_URL_PATH));
				$this->RedirectDownload($dlink, $filename);
				continue;
			}
			is_present($this->page, 'Sorry, the file you requested is not available.');
			$this->cookie = GetCookiesArr($this->page);
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($premium_acc['sendspace_com']['user'] && $premium_acc['sendspace_com']['pass']))) {
			$this->Premium();
		} else {
			$this->Free();
		}
	}

	private function Free() {
		if (!preg_match('/http:\/\/fs\d+?n\d+?\.sendspace\.com\/[^|\s|\t|\r|\n|\'"]+/i', $this->page, $dl)) html_error('Error[Download Link - FREE not found!]');
		$dlink = html_entity_decode(urldecode(trim($dl[0])));
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $this->cookie);
	}

	private function Premium() {
		$cookie = $this->login();
		$page = $this->GetPage($this->link, $cookie);
		if (!preg_match('/http:\/\/fs\d+?n\d+?\.sendspace\.com\/[^|\s|\t|\r|\n|\'"]+/i', $page, $dl)) html_error('Error[Download Link - PREMIUM not found!]');
		$dlink = html_entity_decode(urldecode(trim($dl[0])));
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
	}

	private function login() {
		global $premium_acc, $L;
		$user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["sendspace_com"] ["user"]);
		$pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["sendspace_com"] ["pass"]);
		if (empty($user) || empty($pass)) html_error("Login failed, username[$user] or password[$pass] is empty!");

		$posturl = 'http://www.sendspace.com/';
		$post = array();
		$post['action'] = 'login';
		$post['submit'] = 'login';
		$post['target'] = urlencode('%2F');
		$post['action_type'] = 'login';
		$post['remember'] = 1;
		$post['username'] = $user;
		$post['password'] = $pass;
		$post['remember'] = 'on';
		$page = $this->GetPage($posturl . 'login.html', $this->cookie, $post, $posturl);
		$cookie = GetCookiesArr($page, $this->cookie);
		is_present($cookie['ssal'], "deleted", "Login incorrect retype your username or password correctly");

		$page = $this->GetPage($posturl . 'mysendspace/myindex.html', $cookie);
		if (!preg_match('/<li>You[\s\t]?have[\s\t]?([\d\.]+)([\w]?B)[\s\t]?available[\s\t]?bandwidth<\/li>/', $page, $q)) html_error('Error[Can\'t check Premium Bandwidth Limit or Account Free!]');
		$this->changeMesg($L->say['_retrieving'] . "<br />Sendspace.com Premium Download<br />You have: {$q[1]} {$q[2]} available bandwidth.");

		return $cookie;
	}

}

// Use PREMIUM? [szalinski 09-May-09]
// fix free download by kaox 19-dec-2009
// Fix premium & free by Ruud v.Tony 03-Okt-2011
// fixed by Tony Fauzi Wihana/Ruud v.Tony 22/04/2013
?>