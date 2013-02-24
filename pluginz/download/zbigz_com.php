<?php
if (!defined('RAPIDLEECH')) {
	require('index.html');
	exit();
}

class zbigz_com extends DownloadClass {
	
	public function Download($link) {
		global $premium_acc;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['zbigz_com']['user'] && $premium_acc['zbigz_com']['pass']))) {
			$this->Premium($link);
		} else {
			html_error('Can\'t Download without an account');
		}
	}
	
	private function Premium($link) {
		global $premium_acc;
		
        $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["zbigz_com"] ["user"]);
        $pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["zbigz_com"] ["pass"]);
        if (empty($user) || empty($pass)) html_error("Login failed, $user [user] or $pass [password] is empty!");
		
		$purl = 'http://zbigz.com/';
		$post['login'] = $user;
		$post['password'] = $pass;
		$page = $this->GetPage($purl."login.php", 0, $post, $purl."\r\nX-Requested-With: XMLHttpRequest");
		$cookie = GetCookiesArr($page);
		
		$page = $this->GetPage($link, $cookie);
		$dlink = cut_str($page, 'Location: ', '\r\n');
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
		exit();
		
	}
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
