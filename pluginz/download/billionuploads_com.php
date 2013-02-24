<?php

if (!defined('RAPIDLEECH')) {
	require_once ('index.html');
	exit();
}

class billionuploads_com extends DownloadClass {
	private $page, $cookie;
	public function Download($link) {
		global $premium_acc;
		$this->cookie = array('lang' => 'english');

		if (empty($_POST['step']) || $_POST['step'] != '1') {
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, 'The file you were looking for could not be found');
			//is_present($this->page, 'No such file with this filename', 'Error: Invalid filename, check your link and try again.');
		}

		if ($_REQUEST['premium_acc'] == 'on' && ((!empty($_REQUEST['premium_user']) && !empty($_REQUEST['premium_pass'])) || (!empty($premium_acc['billionuploads_com']['user']) && !empty($premium_acc['billionuploads_com']['pass'])))) $this->Login($link);
		else $this->FreeDL($link);
	}

	private function FreeDL($link) {
		if (empty($_POST['step']) || $_POST['step'] != '1') {
			if (preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@', $this->page, $err)) html_error('Error: '.$err[0]);

			$page2 = cut_str($this->page, '<form name="F1" method="POST"', '</form>'); //Cutting page

			$data = $this->DefaultParamArr($link, (empty($this->cookie['xfss'])) ? 0 : encrypt(CookiesToStr($this->cookie)));
			$data['T8[op]'] = trim(cut_str($page2, 'name="op" value="', '"'));
			if (stripos($data['T8[op]'], 'download') !== 0) html_error('Error parsing download post data.');
			$data['T8[id]'] = trim(cut_str($page2, 'name="id" value="', '"'));
			$data['T8[rand]'] = trim(cut_str($page2, 'name="rand" value="', '"'));
			$data['T8[method_free]'] = urlencode(html_entity_decode(cut_str($page2, 'name="method_free" value="', '"')));
			$data['step'] = '1';

			if (!preg_match('@https?://(?:[a-zA-Z\d\-]+\.)*billionuploads\.com/captchas/\w+\.jpe?g@i', $page2, $imgurl)) html_error('Error: CAPTCHA not found.');
			$imgurl = $imgurl[0];
			if (preg_match('@<span id="countdown_str">[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $page2, $count) && $count[1] > 0) $this->CountDown($count[1]);

			//Download captcha img.
			$capt_page = $this->GetPage($imgurl, $this->cookie);
			$capt_img = substr($capt_page, strpos($capt_page, "\r\n\r\n") + 4);
			$imgfile = DOWNLOAD_DIR . 'billionuploads_captcha.jpg';

			if (file_exists($imgfile)) unlink($imgfile);
			if (!write_file($imgfile, $capt_img)) html_error('Error getting CAPTCHA image.');
			unset($capt_page, $capt_img);

			$this->EnterCaptcha($imgfile.'?'.time(), $data);
			exit;
		} else {
			if (empty($_POST['captcha'])) html_error('You didn\'t enter the image verification code.');
			if (!empty($_POST['cookie'])) $this->cookie = StrToCookies(decrypt(urldecode($_POST['cookie'])));

			$post = array();
			$post['op'] = $_POST['T8']['op'];
			$post['id'] = $_POST['T8']['id'];
			$post['rand'] = $_POST['T8']['rand'];
			$post['referer'] = '';
			$post['method_free'] = $_POST['T8']['method_free'];
			$post['code'] = urlencode($_POST['captcha']);
			$post['down_direct'] = 1;

			$page = $this->GetPage($link, $this->cookie, $post);
			is_present($page, '>Skipped countdown', 'Error: Skipped countdown?.');
			is_present($page, '>Wrong captcha<', 'Error: Wrong Captcha Entered.');
			is_present($page, '>Expired session<', 'Error: Expired Download Session.');
			if (preg_match('@You can download files up to \d+ [KMG]b only.@i', $page, $err)) html_error('Error: '.$err[0]);
			if (!preg_match('@https?://[^/\r\n]+/(?:(?:files)|(?:dl?))/[^\'\"\t<>\r\n]+@i', $page, $dlink)) html_error('Error: Download link not found.');

			$FileName = urldecode(basename(parse_url($dlink[0], PHP_URL_PATH)));
			$this->RedirectDownload($dlink[0], $FileName);
		}
	}

	private function Login($link) {
		html_error('Not done yet... :D');
	}
}

// [28-8-2012]  Written by Th3-822. (XFS, XFS everywhere. D:)
// [09-2-2013]  Added captcha support & Fixed regexp for files with whitespaces at name. - Th3-822

?>